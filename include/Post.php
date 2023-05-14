<?php
require_once("User.php");
require_once("Auth.php");
require_once("Database.php");

class Post {
	private $data;

	function __construct($row) {
		global $database;
		$db = $database->ensure();

		$this->data = $row;
		$this->qry_get_score = $db->prepare('select ifnull(sum(score), 0) from reaction where post_id = :post_id');
		$this->qry_user_score = $db->prepare('select score from reaction where post_id = :post_id and user_id = :user_id');
	}

	public function process_content($content) {
		$content = htmlspecialchars($content);
		#error_log($content);
		$content = preg_replace_callback('/^(#+)(.*)$/m', function($matches) {
			$level = strlen($matches[1]);
			if ($level > 6) {
				$level = 6;
			}
			return "<h{$level}>{$matches[2]}</h{$level}>";
		}, $content);
		#$content = preg_replace('/^\\\\#/m', '#', $content);
		$content = preg_replace('/ /', '&nbsp', $content);
		$content = preg_replace('/(&gt;&gt;)([0-9]+)/', '<a class="post-link" href="post.php?id=$2">$0</a>', $content);
		#$content = preg_replace('/^\\\\/m', '\\', $content);
		$content = preg_replace('/\n/', '<br>', $content);
		return $content;
	}

	public function make_html() {
		global $users, $auth, $user;
		$post_user = $users($this->data["user_id"]);
		$date = date_create($this->data["created"]);
		$content = $this->process_content($this->data["content"] ?? "");

		

		$html = <<<END
<div id="post{$this->data["id"]}" class="post">
	<div class="post-header">
		<div class="post-header-left">
			<div class="post-id"><i class="fas fa-fingerprint"></i> {$this->data["id"]}</div>
			<a href="user.php?id={$post_user("id")}" class="post-username"><i class="fas fa-user"></i> {$post_user("username")}</a>
		</div>
		<div class="post-header-right">
			<div class="post-date"><i class="fas fa-calendar-alt"></i> {$date->format('d. m. Y')}</div>
			<div class="post-time"><i class="fas fa-clock"></i> {$date->format('H:i')}</div>
		</div>
	</div>
	<div class="post-content">{$content}</div>
END;

		if ($auth->check()) {
			$this->qry_get_score->execute(["post_id" => $this->data["id"]]);
			$score = $this->qry_get_score->fetch()[0];

			$user_like = "";
			$user_dislike = "";
			$this->qry_user_score->execute(["post_id" => $this->data["id"], "user_id" => $user("id")]);

			if ($this->qry_user_score->rowCount() > 0) {
				if ($this->qry_user_score->fetch()[0] > 0) {
					$user_like = "reaction-active";
				} else {
					$user_dislike = "reaction-active";
				}
			}


			$html = $html.<<<END
	<div class="post-footer">
		<div class="post-options post-options-left">
			<button post_id="{$this->data["id"]}" onclick="like_button(this)" class="post-button post-button-like {$user_like}"><i class="fas fa-heart"></i></button>
			<div id="score{$this->data["id"]}" class="post-score">{$score}</div>
			<button post_id="{$this->data["id"]}" onclick="dislike_button(this)" class="post-button post-button-dislike {$user_dislike}"><i class="fas fa-dumpster-fire"></i></button>
		</div>
		<div class="post-options post-options-right">
			<button post_id="{$this->data["id"]}" class="post-button" onclick="reply_button(this)"><i class="fas fa-reply"></i> Reply</button>
END;
			if ($auth->perm($post_user("id"))) {
				$html = $html.<<<END
			<div class="post-button"><i class="fas fa-edit"></i> Edit</div>
			<div class="post-button"><i class="fas fa-trash"></i> Delete</div>
END;
			}
			$html = $html.<<<END
		</div>
	</div>
END;
		}

		$html = $html.<<<END
</div>
END;
		return $html;
	}

	public function make_preview_html() {
		return $this->make_html();
	}


}
?>
