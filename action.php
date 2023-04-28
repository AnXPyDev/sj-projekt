<?php
require_once("include/Auth.php");
require_once("include/Database.php");
require_once("include/Action.php");

header("Content-Type: application/json");

$result = [];
$result['success'] = false;

if (!isset($_POST['action'])) {
	$result['error'] = "No action name";
	goto respond;
}

$action_name = $_POST['action'];
$action = NULL;

$action = @include_once("include/actions/".$action_name.".php");

if (gettype($action) != "object" || get_class($action) != "Action") {
	$result['error'] = "No such action";
	goto respond;
}

$action($_POST, $result);

respond:
echo json_encode($result);
?>
