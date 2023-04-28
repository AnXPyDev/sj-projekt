<?php
require_once("config.php");
require_once("Database.php");

class Auth {
	private $hashtype;
	private $session_valid;
	private $user_id;

	function __construct() {
		global $database;
		$this->hashtype = 'sha256';
		$this->session_valid = NULL;
		$this->user_id = NULL;
		
		$db = $database->ensure();
		$this->qry_get_session = $db->prepare('select * from session where session=:session_id');
		$this->qry_set_session = $db->prepare('insert into session (session, created, expires, user_id) values (:session_id, :created, :expires, :user_id)');
		$this->qry_del_session = $db->prepare('delete from session where session=:session_id');
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
		$this->qry_del_session->execute(['session_id' => session_id()]);
		$this->validate_session();
		session_destroy();
		return true;
	}

	private function validate_session() {
		$this->qry_get_session->execute(['session_id' => session_id()]);
		$rc = $this->qry_get_session->rowCount();
		$session_expired = false;
		if ($rc == 1) {
			$session_data = $this->qry_get_session->fetch();
			$this->user_id = $session_data['user_id'];
		}

		if ($rc > 1 || $session_expired) {
			$this->session_valid = false;
			$this->qry_del_session->execute(['session_id' => session_id()]);
			return;
		} else if ($rc == 0) {
			$this->session_valid = false;
			return;
		}

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
}
	
$auth = new Auth();
?>
