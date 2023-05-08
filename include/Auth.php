<?php
require_once("config.php");
require_once("User.php");
require_once("Database.php");

class Auth {
	private $hashtype;
	private $session_valid;
	private $user_id;
	private $user;

	private $qry_get_session;
	private $qry_set_session;
	private $qry_del_session;
	private $qry_del_all_session;

	function __construct() {
		global $database;
		$this->hashtype = 'sha256';
		$this->session_valid = NULL;
		$this->user_id = NULL;
		
		$db = $database->ensure();
		$this->qry_get_session = $db->prepare('select * from session where session=:session_id');
		$this->qry_set_session = $db->prepare('insert into session (session, created, expires, user_id) values (:session_id, :created, :expires, :user_id)');
		$this->qry_del_session = $db->prepare('delete from session where session=:session_id');
		$this->qry_del_all_session = $db->prepare('delete from session where user_id=:user_id');
		session_start();
	}

	public function gen_salt() {
		return bin2hex(random_bytes(20));
	}

	public function gen_hash($password, $salt) {
		return hash($this->hashtype, $password.$salt);
	}

	public function login($user_id) {
		global $mysql_datetime_fmt;
		if ($this->check()) {
			return false;	
		}
		$this->qry_set_session->execute([
			'session_id' => session_id(),
			'user_id' => $user_id,
			'created' => date($mysql_datetime_fmt, time()),
			'expires' => date($mysql_datetime_fmt, time() + 300)
		]);
		$this->validate_session();
		return $this->check();
	}

	public function logout() {
		if (!$this->check()) {
			return false;
		}
		$this->qry_del_session->execute(['session_id' => session_id()]);
		$this->validate_session();
		session_destroy();
		return true;
	}

	public function logoutall() {
		if (!$this->check()) {
			return false;
		}
		$this->qry_del_all_session->execute(['user_id' => $this->user_id]);
		$this->validate_session();
		session_destroy();
		return true;

	}

	private function validate_session() {
		global $users, $user;

		$this->qry_get_session->execute(['session_id' => session_id()]);
		$rc = $this->qry_get_session->rowCount();

		#TODO implement session expiration
		$session_expired = false;
		if ($rc == 1) {
			$session_data = $this->qry_get_session->fetch();
			$this->user_id = $session_data['user_id'];
		}

		if ($rc > 1 || $session_expired) {
			$this->session_valid = false;
			$this->qry_del_session->execute(['session_id' => session_id()]);
			$user = $users(-1);
			return;
		} else if ($rc == 0) {
			$this->session_valid = false;
			$user = $users(-1);
			return;
		}
		
		$user = $users($this->user_id);
		$this->session_valid = true;
		
	}

	public function check() {
		if (!isset($this->session_valid)) {
			$this->validate_session();
		}

		return $this->session_valid;
	}

	public function getuid() {
		if ($this->check()) {
			return $this->user_id;
		}
	}

	public function perm($user_id) {
		if ($this->check()) {
			if ($this->user_id == $user_id || $user("admin") > 0) {
				return true;
			}
		}

		return false;
	}
}
	
$auth = new Auth();
?>
