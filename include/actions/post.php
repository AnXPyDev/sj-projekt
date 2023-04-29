<?php
return new Action(['content'], function($args, &$result) {
	global $database, $auth;
	if (!$auth->check()) {
		$result['success'] = false;
		$result['error'] = "Not logged in";
		return;
	}
	
	

});
?>
