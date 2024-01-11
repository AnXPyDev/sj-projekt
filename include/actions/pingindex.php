<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");

return new Action([], function($args, &$result) {
	global $auth, $database, $user;

	$db = $database->ensure();

	$qry = $db->prepare('select count(*) as cnt from thread');
    $qry->execute();
    $result['threads'] = $qry->fetch(PDO::FETCH_ASSOC)['cnt'];

	exit_success:
	$result['success'] = true;
	return;
	exit_fail:
	$result['success'] = false;
});
?>
