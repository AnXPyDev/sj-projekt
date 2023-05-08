<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");
require_once("include/User.php");

return new Action([], function($args, &$result) {
	global $database, $auth, $user;
	
	$result['session_id'] = session_id();
	$result['login'] = $auth->check();
	$result['uid'] = $auth->getuid();
	$result['user_data'] = $user->get_data();
});
?>
