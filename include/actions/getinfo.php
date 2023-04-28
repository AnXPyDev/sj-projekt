<?php
return new Action([], function($args, &$result) {
	global $database, $auth;
	
	$result['session_id'] = session_id();
	$result['login'] = $auth->check();
	$result['uid'] = $auth->getuid();
});
?>
