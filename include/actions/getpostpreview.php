<?php
require_once("include/Database.php");
require_once("include/Post.php");
require_once("include/Action.php");

return new Action(['post_id'], function($args, &$result) {
	global $database;

	$db = $database->ensure();
	$qry = $db->prepare('select * from post where id = :post_id');
	$qry->execute(["post_id" => $args["post_id"]]);

	if ($qry->rowCount() == 0) {
		$result["error"] = "Post not found";
		goto exit_fail;
	}

	$post = new Post($qry->fetch(PDO::FETCH_ASSOC));	

	$result["html"] = $post->make_preview_html();
	
	$result["success"] = true;
	return;
	exit_fail:;
	$result["success"] = false;
});

?>
