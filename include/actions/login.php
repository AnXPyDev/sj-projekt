<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");

return new Action(['username', 'password'], function($args, &$result) {
	global $database, $auth;

	if ($auth->check()) {
		$result['error'] = "Already logged in";
		goto exit_fail;
	}

	$db = $database->ensure();
	$qry_user = $db->prepare('select id, username, password_hash, password_salt from user where username = :username');

	$success = true;
	try {
		$success = $qry_user->execute(['username' => $args['username']]);
		if ($qry_user->rowCount() != 1) {
			$result['error'] = "No such user";
			$success = false;
		}
	} catch (Exception $e) {
		$result['error'] = "Exception 1";
		goto exit_fail;
	}
	if (!$success) goto exit_fail;

	$user_data = $qry_user->fetch();
	
	$hash = $auth->gen_hash($args['password'], $user_data['password_salt']);

	if ($hash != $user_data['password_hash']) {
		$result['error'] = "Wrong password";
		goto exit_fail;
	}

	if (!$auth->login($user_data['id'])) {
		$result['error'] = "Failed to login";
		goto exit_fail;
	}
	
	$result['success'] = $success;
	return;
	exit_fail:
	$result['success'] = false;
});
?>
