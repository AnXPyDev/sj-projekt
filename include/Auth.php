<?php
require_once("Database.php");

class Auth {
	private $hashtype;

	function __construct() {
		$this->hashtype = 'sha256';
		session_start();
	}

	public function gen_salt() {
		return bin2hex(random_bytes(20));
	}

	public function gen_hash($password, $salt) {
		return hash($this->hashtype, $password.$salt);
	}
}
	
$auth = new Auth();
?>
