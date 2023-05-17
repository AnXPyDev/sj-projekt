<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");
require_once("include/User.php");

return new Action(['post_id', 'type'], function($args, &$result) {
	global $auth, $database, $user;

	if (!$auth->check()) {
		$result['error'] = "Not logged in";
		goto exit_fail;
	}

	if ($user("banned") == 1) {
		$result['error'] = "You are banned from reacting to posts";
		goto exit_fail;
	}

	$db = $database->ensure();

	$qry_remove = $db->prepare('delete from reaction where post_id = :post_id and user_id = :user_id');
	$qry_set = $db->prepare('insert into reaction (score, post_id, user_id) values (:score, :post_id, :user_id)');

	$score = 0;

	switch ($args["type"]) {
	case "like":
		$score = 1;
		break;
	case "dislike":
		$score = -1;
		break;
	case "discard":
		$qry_remove->execute(["post_id" => $args["post_id"], "user_id" => $user("id")]);
		goto exit_success;
	default:
		$result['error'] = "Wrong reaction type";
		goto exit_fail;
	}
	
	$qry_remove->execute(["post_id" => $args["post_id"], "user_id" => $user("id")]);
	$qry_set->execute(["post_id" => $args["post_id"], "user_id" => $user("id"), "score" => $score]);

	exit_success:
	$result['success'] = true;
	return;
	exit_fail:
	$result['success'] = false;
});
?>
