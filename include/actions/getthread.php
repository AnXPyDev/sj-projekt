<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");

return new Action(['id'], function($args, &$result) {
    global $auth, $database, $user;

    $db = $database->ensure();

    $qry = $db->prepare('select thread.*, user.username from thread join user on thread.user_id=user.id where thread.id=:id;');
    $qry->bindValue(":id", $args['id'], PDO::PARAM_INT);
    $qry->execute();

    $threads = [];
    $row = $qry->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $result['error'] = "No such thread";
    }
    $created = date_create($row['created']);
    $thread = [
        'id' => $row['id'],
        'title' => $row['title'],
        'user_id' => $row['user_id'],
        'user_name' => $row['username'],
        'created_date' => $created->format('d. m. Y'),
        'created_time' => $created->format('H:i'),
    ];

    $qry_post_count = $db->prepare('select count(*) as cnt from post where post.thread_id=:id');
    $qry_post_count->bindValue(":id", $args['id'], PDO::PARAM_INT);
    $qry_post_count->execute();
    $cnt = $qry_post_count->fetch(PDO::FETCH_ASSOC)['cnt'];

    exit_success:
    $result['success'] = true;
    $result['thread'] = $thread;
    $result['posts'] = $cnt;
    return;
    exit_fail:
    $result['success'] = false;
});
?>
