<?php
require_once("include/User.php");

class Thread {
	private $data;

	function __construct($row) {
		$this->data = $row;
	}

	public function make_html() {
		global $users;
		$user = $users($this->data["user_id"]);
		$date = date_create($this->data["created"]);
		echo <<<END
<div class="thread">
	<div class="thread-left">
		<a href="thread.php?id={$this->data["id"]}" class="thread-title">{$this->data["title"]}</a>
	</div>
	<div class="thread-right">
			<a class="thread-user" href="user.php?id={$user("id")}"><i class="fas fa-user"></i> {$user("username")}</a>
			<div class="thread-date"><i class="fas fa-calendar-alt"></i> {$date->format('d. m. Y')}</div>
			<div class="thread-time"><i class="fas fa-clock"></i> {$date->format('H:i')}</div>
	</div>
</div>
END;
	}
}
?>
