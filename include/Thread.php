<?php
require_once("User.php");
require_once("Database.php");
require_once("Post.php");
require_once("Pager.php");

class Thread {
	public $data;
	private $posts;
	private $page;

	function __construct($row) {
		$this->data = $row;
	}

	public function make_html() {
		global $users;
		$user = $users($this->data["user_id"]);
		$date = date_create($this->data["created"]);
		$title = htmlspecialchars($this->data["title"]);
		return <<<END
<div class="thread">
	<div class="thread-left">
		<a href="thread.php?id={$this->data["id"]}" class="thread-title">{$title}</a>
	</div>
	<div class="thread-right">
			<a class="thread-user" href="user.php?id={$user("id")}"><i class="fas fa-user"></i> {$user("username")}</a>
			<div class="thread-date"><i class="fas fa-calendar-alt"></i> {$date->format('d. m. Y')}</div>
			<div class="thread-time"><i class="fas fa-clock"></i> {$date->format('H:i')}</div>
	</div>
</div>
END;
	}

	public function create_posts() {
		global $database;

		if (isset($this->posts)) {
			return;
		}

		$this->page = 1;
		if (isset($_GET["page"])) {
			$this->page = (int)$_GET["page"];
		}

		$db = $database->ensure();
		$qry = $db->prepare('select * from post where thread_id = :thread_id order by created asc, id asc limit :limit_from,:limit_count');
		$qry->bindValue(":thread_id", $this->data["id"], PDO::PARAM_INT);
		$qry->bindValue(":limit_from", ($this->page - 1) * 10, PDO::PARAM_INT);
		$qry->bindValue(":limit_count", 10, PDO::PARAM_INT);
		$qry->execute();

		$this->posts = [];
		
		while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
			$this->posts[] = new Post($row);
		}
	}

	public function make_post_list() {
		$this->create_posts();
		$html = "";
		foreach ($this->posts as $post) {
			$html = $html.$post->make_html();
		}

		return $html;
	}

	public function make_pager() {
		global $database;

		$db = $database->ensure();
		$qry_count = $db->prepare('select count(*) from post where thread_id = :thread_id');
		$qry_count->bindValue(":thread_id", $this->data["id"], PDO::PARAM_INT);
		$qry_count->execute();
		
		$post_count = $qry_count->fetch()[0];
		return new Pager("thread.php?id={$this->data["id"]}&page=", $this->page, $post_count, 10, 5);
	}

}
?>
