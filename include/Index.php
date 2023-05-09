<?php
require_once("config.php");
require_once("Database.php");
require_once("Thread.php");
require_once("Pager.php");

class Index {
	private $qry_get_thread_count;
	private $qry_get_threads;
	private $threads;
	private $page;

	function __construct() {
		global $database;

		$db = $database->ensure();
		$this->qry_get_thread_count = $db->prepare('select count(*) from thread');
		$this->qry_get_threads = $db->prepare('select * from thread order by created desc, id desc limit :limit_from,:limit_count');
	}

	private function create_threads() {
		if (isset($this->threads)) {
			return;
		}

		$this->page = 1;
		if (isset($_GET["page"])) {
			$this->page = (int)$_GET["page"];
		}

		$qry = &$this->qry_get_threads;
		$qry->bindValue(":limit_from", ($this->page - 1) * 10, PDO::PARAM_INT);
		$qry->bindValue(":limit_count", 10, PDO::PARAM_INT);
		$qry->execute();
		
		$this->threads = [];
		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$this->threads[] = new Thread($row);
		}
	}

	public function make_thread_list() {
		$this->create_threads();
		foreach ($this->threads as $thread) {
			echo $thread->make_html();
		}
	}	

	public function make_pager() {
		$this->qry_get_thread_count->execute();
		$thread_count = $this->qry_get_thread_count->fetch()[0];
		return new Pager("index.php?page=", $this->page, $thread_count, 10, 5);
	}
}

$index = new Index();
?>
