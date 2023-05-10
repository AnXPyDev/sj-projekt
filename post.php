<?php

require_once("include/Database.php");

$url = "index.php";
if (!isset($_GET["id"])) {
	goto redirect;
}

$id = (int)$_GET["id"];

$db = $database->ensure();
$qry_thread_id = $db->prepare('select thread_id from post where id = :id');
$qry_thread_posts = $db->prepare('select id from post where thread_id = :thread_id order by created asc, id asc');

$qry_thread_id->execute(['id' => $id]);
if ($qry_thread_id->rowCount() == 0) {
	goto redirect;
}

$thread_id = $qry_thread_id->fetch(PDO::FETCH_ASSOC)['thread_id'];
$qry_thread_posts->execute(['thread_id' => $thread_id]);

$i = 0;
while ($row = $qry_thread_posts->fetch(PDO::FETCH_ASSOC)) {
	$i++;
	if ($row['id'] == $id) {
		break;
	}
}

$page = ceil($i / 10);

$url = "thread.php?id={$thread_id}&page={$page}#post{$id}";

redirect:
header("Location: {$url}");

?>
