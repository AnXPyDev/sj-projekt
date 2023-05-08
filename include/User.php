<?php
require_once("include/Database.php");

class User {
	private $data;

	function __construct($row) {
		unset($row["password_hash"]);
		unset($row["password_salt"]);

		$this->data = $row;
	}

	public function get_data() {
		return $this->data;
	}

	public function __invoke($prop) {
		return $this->data[$prop];
	}
}

class UserCache {
	private $cache;
	private $qry_get_user;

	private $user_not_found;
	private $user_anonymous;

	function __construct() {
		global $database;

		$this->cache = [];

		$db = $database->ensure();
		$this->qry_get_user = $db->prepare('select * from user where id = :user_id');

		$this->user_not_found = new User([
			"id" => -1,
			"username" => "<not found>",
			"admin" => 0,
			"banned" => 0,
			"first_name" => null,
			"last_name" => null,
			"last_seen" => null
		]);

		$this->user_anonymous = new User([
			"id" => -2,
			"username" => "anonymous",
			"admin" => 0,
			"banned" => 0,
			"first_name" => null,
			"last_name" => null,
			"last_seen" => null
		]);
	}

	public function __invoke($user_id) {
		if ($user_id < 0) {
				$this->cache[$user_id] = $this->user_not_found;
		} else if (!isset($this->cache[$user_id])) {
			$this->qry_get_user->execute(["user_id" => $user_id]);
			if ($this->qry_get_user->rowCount() == 0) {
				$this->cache[$user_id] = $this->user_not_found;
			} else {
				$this->cache[$user_id] = new User($this->qry_get_user->fetch(PDO::FETCH_ASSOC));
			}
		}

		return $this->cache[$user_id];
	}
}

$users = new UserCache;
$user = NULL;

?>
