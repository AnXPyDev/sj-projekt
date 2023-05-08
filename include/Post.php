<?php
require_once("User.php");
require_once("Auth.php");
require_once("Database.php");

class Post {
	private $data;

	function __construct($row) {
		$this->data = $row;
	}

	public function make_html() {
		global $users, $auth;
		$user = $users($this->data["user_id"]);
		$date = date_create($this->data["created"]);
		$content = $this->data["content"] ?? "";
		echo <<<END
<div id="post{$this->data["id"]}" class="post">
	<div class="post-header">
		<div class="post-header-left">
			<div class="post-id"><i class="fas fa-fingerprint"></i> {$this->data["id"]}</div>
			<a href="user.php?id={$user("id")}" class="post-username"><i class="fas fa-user"></i> {$user("username")}</a>
		</div>
		<div class="post-header-right">
			<div class="post-date"><i class="fas fa-calendar-alt"></i> {$date->format('d. m. Y')}</div>
			<div class="post-time"><i class="fas fa-clock"></i> {$date->format('H:i')}</div>
		</div>
	</div>
	<div class="post-content">{$content}</div>
END;

		if ($auth->check()) {
			echo <<<END
	<div class="post-footer">
		<div class="post-options post-options-left">
			<div class="post-button post-button-like post-button-active"><i class="fas fa-heart"></i></div>
			<div class="post-button post-button-dislike post-button-active"><i class="fas fa-dumpster-fire"></i></div>
		</div>
		<div class="post-options post-options-right">
			<div class="post-button"><i class="fas fa-reply"></i> Reply</div>
END;
			if ($auth->perm($user("id"))) {
				echo <<<END
			<div class="post-button"><i class="fas fa-edit"></i> Edit</div>
			<div class="post-button"><i class="fas fa-trash"></i> Delete</div>
END;
			}
			echo <<<END
		</div>
	</div>
END;
		}

		echo <<<END
</div>
END;
	}

	

}
?>
