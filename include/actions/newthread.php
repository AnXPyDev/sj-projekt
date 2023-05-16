<?php
require_once("include/config.php");
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");
require_once("include/User.php");

return new Action(['title'], function($args, &$result) {
	global $database, $auth, $user, $mysql_datetime_fmt;

	if (!$auth->check()) {
		$result['error'] = "Not logged in";
		goto exit_fail;
	}
	
	if ($user("banned") == 1) {
		$result['error'] = "You are banned from creating threads!";
		goto exit_fail;
	}
	
	$db = $database->ensure();	
	$qry_make_thread = $db->prepare('insert into thread (title, created, user_id) values (:title, :created, :user_id)');

	$user_id = $user("id");	

	$qry_make_thread->execute([
		"user_id" => $user("id"),
		"title" => $args['title'],
		"created" => date($mysql_datetime_fmt, time())
	]);

	$result['success'] = true;
	return;
	exit_fail:
	$result['success'] = false;
});

?>
