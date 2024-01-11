<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");

return new Action(['from', 'count'], function($args, &$result) {
	global $auth, $database, $user;

	$db = $database->ensure();

	$qry = $db->prepare('select thread.*, user.username from thread join user on thread.user_id =user.id order by created desc, id desc limit :limit_from,:limit_count');
    $qry->bindValue(":limit_from", $args['from'], PDO::PARAM_INT);
    $qry->bindValue(":limit_count", $args['count'], PDO::PARAM_INT);
    $qry->execute();

    $threads = [];
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $created = date_create($row['created']);
        $threads[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'user_id' => $row['user_id'],
            'user_name' => $row['username'],
            'created_date' => $created->format('d. m. Y'),
            'created_time' => $created->format('H:i'),
        ];
    }

	exit_success:
	$result['success'] = true;
    $result['threads'] = $threads;
	return;
	exit_fail:
	$result['success'] = false;
});
?>
