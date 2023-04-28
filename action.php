<?php
require_once("include/Auth.php");
require_once("include/actions.php");

header("Content-Type: application/json");

$result = [];
$result['success'] = false;

if (!isset($_POST['action'])) {
	goto respond;
}

$action_name = $_POST['action'];

if (!isset($actions[$action_name])) {
	goto respond;
}

$actions[$action_name]($_POST, $result);

respond:
echo json_encode($result);
?>
