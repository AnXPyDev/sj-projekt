<?php
require_once("Database.php");
require_once("Action.php");

$actions = [];

$actions['echo'] = new Action([], function($args, &$result) {
	foreach ($args as $key => $val) {
		$result[$key] = $val;
	}

	$result['success'] = true;
});

$actions['register'] = require_once("actions/register.php");
$actions['login.php'] = require_once("actions/login.php");
?>
