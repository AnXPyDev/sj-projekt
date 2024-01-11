<?php
require_once("include/Action.php");
require_once("include/Database.php");
require_once("include/Auth.php");

return new Action(['thread_id', 'from', 'count'], function($args, &$result) {
	global $auth, $database, $user;

	$db = $database->ensure();

	$qry = $db->prepare('select post.*, user.username from post join user on post.user_id=user.id where post.thread_id=:thread_id order by created asc, id asc limit :limit_from,:limit_count');
    $qry->bindValue(":thread_id", $args['thread_id'], PDO::PARAM_INT);
    $qry->bindValue(":limit_from", $args['from'], PDO::PARAM_INT);
    $qry->bindValue(":limit_count", $args['count'], PDO::PARAM_INT);
    $qry->execute();

    $qry_get_score = $db->prepare('select ifnull(sum(score), 0) as score from reaction where post_id = :post_id');
    $qry_user_score = $db->prepare('select ifnull(sum(score), 0) as score from reaction where post_id = :post_id and user_id = :user_id');
    $get_user_score = false;
    if ($auth->check()) {
        $get_user_score = true;
        $qry_user_score->bindValue(":user_id", $user('id'), PDO::PARAM_INT);
    }

    $posts = [];
    while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
        $created = date_create($row['created']);
        $qry_get_score->bindValue(":post_id", $row['id'], PDO::PARAM_INT);
        $qry_get_score->execute();
        $score = $qry_get_score->fetch(PDO::FETCH_ASSOC)['score'];

        $user_score = 0;
        if ($get_user_score) {
            $qry_user_score->bindValue(":post_id", $row['id'], PDO::PARAM_INT);
            $qry_user_score->execute();
            $user_score = $qry_user_score->fetch(PDO::FETCH_ASSOC)['score'];
        }

        $posts[] = [
            'id' => $row['id'],
            'content' => $row['content'],
            'user_id' => $row['user_id'],
            'user_name' => $row['username'],
            'created_date' => $created->format('d. m. Y'),
            'created_time' => $created->format('H:i'),
            'score' => (int)$score,
            'reaction' => (int)$user_score
        ];
    }

	exit_success:
	$result['success'] = true;
    $result['posts'] = $posts;
	return;
	exit_fail:
	$result['success'] = false;
});
?>
