<?php
class Database {
	private $connection;

	function __construct() {
		$this->connection = NULL;
	}

	private function connect() {
		$this->connection = new PDO('mysql:host=localhost;dbname=forum;charset=utf8','root','');
	}

	public function ensure() {
		if (is_null($this->connection)) {
			$this->connect();
		}
		return $this->connection;
	}
}

$database = new Database();
?>
