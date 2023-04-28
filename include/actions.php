<?php
require_once("Database.php");
require_once("Action.php");
$actions = [];

$actions['ping'] = new Action([], function($args, &$result) {
	foreach ($args as $key => $val) {
		$result[$key] = $val;
	}

	$result['success'] = true;
});

$actions['register'] = new Action(['username', 'password'], function($args, &$result) {
	global $database, $auth;

	$db = $database->ensure();
	
	$qry_user_count = $db->prepare('select id from user');
	$qry_check_taken = $db->prepare('select id from user where username=:username');
	$qry_insert_user = $db->prepare('insert into user (username, password_hash, password_salt, first_name, last_name, admin) values (:username, :password_hash, :password_salt, :first_name, :last_name, :admin)');
	$qry_insert_config = $db->prepare('insert into config (user_id) values (:user_id)');
	
	
	$success = true;	

	$allow_admin = false;	
	try {
		$success = $qry_user_count->execute();
		if ($qry_user_count->rowCount() == 0) {
			$allow_admin = true;
		}
	} catch (Exception $e) {
		$result["exception"] = "1";
		goto exit_fail;
	}

	if (!$success) goto exit_fail;
	try {
		$success = $qry_check_taken->execute([
			'username' => $args['username']
		]);
		if ($qry_check_taken->rowCount() > 0) {
			$success = false;
			$result['error'] = "User already exists";
		}
	} catch (Exception $e) {
		$result["exception"] = "2";
		goto exit_fail;
	}

	$salt = $auth->gen_salt();
	$hash = $auth->gen_hash($args['password'], $salt);
	$admin = $args['admin'] ?? NULL == "true";

	if ($allow_admin && $admin = 1) {
		$result['warning'] = "Cant register admin";
	}
		
	$userid = NULL;

	if (!$success) goto exit_fail;
	try {
		$success = $qry_insert_user->execute([
			'username' => $args['username'],
			'password_hash' => $hash,
			'password_salt' => $salt,
			'first_name' => $args['first_name'] ?? NULL,
			'last_name' => $args['last_name'] ?? NULL,
			'admin' => $admin_allow && $admin ? 1 : 0
		]);

		$userid = $db->lastInsertId();
	} catch (Exception $e) {
		$result["exception"] = "3";
		goto exit_fail;
	}

	if (!$success) goto exit_fail;
	try {
		$success = $qry_insert_config->execute([
			'user_id' => $userid
		]);
	} catch (Exception $e) {
		$result["exception"] = "4";
		goto exit_fail;
	}

	$result['success'] = $success;
	return;
	exit_fail:
	$result['success'] = false;
});
?>
