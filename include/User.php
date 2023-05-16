<?php
require_once("include/Database.php");
require_once("include/Auth.php");

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

	public function make_html() {
		global $auth, $user;
		$html = <<<END
<div class="post-header user-header">
<div><i class="fas fa-user-circle"></i> {$this->data["username"]}</div>
<div><i class="fas fa-fingerprint"></i> {$this->data["id"]}</div>
END;
		
		$user_type = <<<END
<div><i class="fas fa-user"></i> Regular user</div>
END;
		switch ($this->data["admin"]) {
		case 1:
			$user_type = <<<END
<div><i class="fas fa-user-tie"></i> Moderator</div>
END;
			break;
		case 2:
			$user_type = <<<END
<div><i class="fas fa-user-secret"></i> Administrator</div>
END;
			break;
		}

		$html = $html.$user_type;

		if ($this->data["banned"] == 1) {
			$html = $html.<<<END
<div><i class="fas fa-ban"></i> Banned</div>
END;
		}

		$html = $html.<<<END
</div>
<div class="post-options user-options">
END;
		if ($auth->perm($this->data["id"])) {
			if ($user("admin") != 1 || $user("id") == $this->data["id"]) {
				$html = $html.<<<END
			<button user_id="{$this->data["id"]}" class="post-button" onclick="user_delete(this)"><i class="fas fa-user-slash"></i> Delete</button>
END;
			}

			if ($user("admin") > 0) {
				if ($this->data["banned"] == 0) {
					$html = $html.<<<END
			<button user_id="{$this->data["id"]}" class="post-button" onclick="user_ban(this)"><i class="fas fa-ban"></i> Ban</button>
END;
				} else {
					$html = $html.<<<END
			<button user_id="{$this->data["id"]}" class="post-button" onclick="user_unban(this)"><i class="far fa-check-circle"></i> Unban</button>
END;
				}
			}

			if ($user("admin") == 2) {
				$html = $html.<<<END
			<button user_id="{$this->data["id"]}" admin_level="0" class="post-button" onclick="user_setadmin(this)"><i class="fas fa-user-minus"></i> Remove admin privileges</button>
			<button user_id="{$this->data["id"]}" admin_level="1" class="post-button" onclick="user_setadmin(this)"><i class="fas fa-user-tie"></i> Make moderator</button>
			<button user_id="{$this->data["id"]}" admin_level="2" class="post-button" onclick="user_setadmin(this)"><i class="fas fa-user-secret"></i> Make administrator</button>
END;
			}
		}


		$html = $html.<<<END
</div>
END;
		return $html;
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
