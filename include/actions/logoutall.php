<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");

return new Action([], function($args, &$result) {
	global $database, $auth;
	if (!$auth->check()) {
		$result['success'] = false;
		$result['error'] = "Not logged in";
		return;
	}
	$result['success'] = $auth->logoutall();
});
?>
