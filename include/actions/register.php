<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");

return new Action(['username', 'password'], function($args, &$result) {
	global $database, $auth;

	#Sanitize username and password

	if (!(preg_match('/^[a-zA-Z0-9_\-]*$/', $args["username"])
		&& strlen($args["username"]) > 2
		&& htmlspecialchars($args["username"]) == $args["username"])) {
		$result["error"] = "Username can only contain letters, dash, underscore and must be at least 3 characters long";
		goto exit_fail;
	}

	if (strlen($args["password"]) < 3) {
		$result["error"] = "Password must be at least 3 characters long";
		goto exit_fail;
	}


	$db = $database->ensure();
	
	$qry_user_count = $db->prepare('select id from user where admin > 0');
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
	if (!$success) goto exit_fail;

	$salt = $auth->gen_salt();
	$hash = $auth->gen_hash($args['password'], $salt);
	$admin = ($args['admin'] ?? NULL) == "true";

	if (!$allow_admin && $admin = true) {
		$result['warning'] = "Cant register admin";
	}
		
	$userid = NULL;

	try {
		$success = $qry_insert_user->execute([
			'username' => $args['username'],
			'password_hash' => $hash,
			'password_salt' => $salt,
			'first_name' => $args['first_name'] ?? NULL,
			'last_name' => $args['last_name'] ?? NULL,
			'admin' => $allow_admin && $admin ? 2 : 0
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
