<?php
require_once("include/config.php");
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");
require_once("include/User.php");

return new Action(['post_id', 'mod'], function($args, &$result) {
	global $database, $auth, $user, $mysql_datetime_fmt;

	if (!$auth->check()) {
		$result['error'] = "Not logged in";
		goto exit_fail;
	}

	$db = $database->ensure();
	$qry_get_post = $db->prepare('select * from post where id = :post_id');
	$qry_get_post->execute(['post_id' => $args['post_id']]);
	
	$post_data = $qry_get_post->fetch(PDO::FETCH_ASSOC);

	if ($post_data == false) {
		$result['error'] = "post_id not found";
		goto exit_fail;
	}

	if (!$auth->perm($post_data['user_id'])) {
		$result['error'] = "You dont have permissions to modify this post";
	}

	switch ($args["mod"]) {
	case "rawcontent":
		$result["content"] = $post_data["content"];
		break;
	case "delete":
		$qry = $db->prepare('delete from post where id = :post_id');
		$qry->execute(['post_id' => $post_data['id']]);
		break;
	case "edit":
		if (!isset($args["content"])) {
			$result['error'] = "No new content provided for editing";
			goto exit_fail;
		}
		$qry = $db->prepare('update post set modified = :modified, content = :content where id = :post_id');
		$qry->execute([
			"post_id" => $post_data["id"],
			"modified" => date($mysql_datetime_fmt, time()),
			"content" => $args['content']
		]);
		break;
	default:
		$result['error'] = "No such action for modifying post";
		goto exit_fail;
	}


	$result['success'] = true;
	return;
	exit_fail:
	$result['success'] = false;
});

?>
