<?php
require_once("include/config.php");
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");
require_once("include/User.php");

return new Action(['thread_id', 'content'], function($args, &$result) {
	global $database, $auth, $user, $mysql_datetime_fmt;

	if (!$auth->check()) {
		$result['error'] = "Not logged in";
		goto exit_fail;
	}
	
	$db = $database->ensure();
	$qry_ensure_thread = $db->prepare('select id from thread where id = :thread_id');
	$qry_ensure_thread->execute(['thread_id' => $args['thread_id']]);
	if ($qry_ensure_thread->rowCount() == 0) {
		$result['error'] = "No such thread";
		goto exit_fail;
	}

	$qry_make_post = $db->prepare('insert into post (thread_id, user_id, created, modified, content) values (:thread_id, :user_id, :created, :modified, :content)');

	$user_id = $user("id");	

	$created = date($mysql_datetime_fmt, time());

	$qry_make_post->execute([
		"thread_id" => (int)$args['thread_id'],
		"user_id" => $user("id"),
		"created" => $created,
		"modified" => $created,
		"content" => $args['content']
	]);

	$result['success'] = true;
	return;
	exit_fail:
	$result['success'] = false;
});

?>
