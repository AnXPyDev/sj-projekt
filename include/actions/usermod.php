<?php
require_once("include/config.php");
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");
require_once("include/User.php");

return new Action(['id', 'mod'], function($args, &$result) {
	global $database, $auth, $user, $mysql_datetime_fmt;

	if (!$auth->check()) {
		$result['error'] = "Not logged in";
		goto exit_fail;
	}

	$db = $database->ensure();
	$qry_get_post = $db->prepare('select * from user where id = :user_id');
	$qry_get_post->execute(['user_id' => $args['id']]);
	
	$user_data = $qry_get_post->fetch(PDO::FETCH_ASSOC);

	if ($user_data == false) {
		$result['error'] = "user_id not found";
		goto exit_fail;
	}

	if (!$auth->perm($user_data['id'])) {
		$result['error'] = "You dont have permissions to modify this user";
	}

	switch ($args["mod"]) {
	case "delete":
		if ($user('admin') != 2 && $user('id') != $user_data['id']) {
			$result['error'] = "You dont have permission to delete this user";
			goto exit_fail;
		}
		$qry = $db->prepare('delete from user where id = :user_id');
		$qry_config = $db->prepare('delete from config where user_id = :user_id');
		$qry->execute(["user_id" => $user_data["id"]]);
		$qry_config->execute(["user_id" => $user_data["id"]]);
		break;
	case "ban":
		if ($user('admin') < 1) {
			$result['error'] = "You dont have permission to ban this user";
			goto exit_fail;
		}
		
		$qry = $db->prepare('update user set banned = 1 where id = :user_id');
		$qry->execute(["user_id" => $user_data["id"]]);
		break;
	case "unban":
		if ($user('admin') < 1) {
			$result['error'] = "You dont have permission to unban this user";
			goto exit_fail;
		}
		
		$qry = $db->prepare('update user set banned = 0 where id = :user_id');
		$qry->execute(["user_id" => $user_data["id"]]);
		break;
	case "setadmin":
		if ($user('admin') != 2) {
			$result['error'] = "Only user with admin level 2 can modify admin status of users";
			goto exit_fail;
		}

		if (!isset($args["level"])) {
			$result['error'] = "No new admin level provided";
			goto exit_fail;
		}

		$level = (int)$args["level"];
		if ($level < 0 || $level > 2) {
			$result['error'] = "Wrong admin level provided, only values between 0 and 2 allowed";
			goto exit_fail;
		}

		$qry = $db->prepare('update user set admin = :admin where id = :user_id');
		$qry->execute([
			"user_id" => $user_data["id"],
			"admin" => $level
		]);
		break;
	default:
		$result['error'] = "No such action for modifying user";
		goto exit_fail;
	}


	$result['success'] = true;
	return;
	exit_fail:
	$result['success'] = false;
});

?>
