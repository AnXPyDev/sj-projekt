<?php
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
