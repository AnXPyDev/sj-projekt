<?php
require_once("include/Database.php");
require_once("include/Thread.php");

$thread = null;

if (!isset($_GET["id"])) {
	goto thread_not_found;
}

$db = $database->ensure();
$qry = $db->prepare('select * from thread where id = :thread_id');
$qry->execute(["thread_id" => $_GET["id"]]);

if ($qry->rowCount() == 0) {
	goto thread_not_found;
}

$thread = new Thread($qry->fetch(PDO::FETCH_ASSOC));

thread_not_found:
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("partials/head.php"); ?>
		<title>Diskusne forum</title>
		<script src="scripts/reply.js"></script>
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/content.css">
		<link rel="stylesheet" href="styles/thread.css">
		<link rel="stylesheet" href="styles/post.css">
	</head>
	<body>
		<?php include("partials/header.php"); ?>
		<main id="content-center" class="content">
			<section class="thread-list">
				<?php $thread->make_html(); ?>
			</section>
			<section class="thread-list">
				<?php $thread->make_post_list(); ?>
			</section>
			<section class="page-nav">
				<?php $thread->make_pager()->make_html(); ?>
			</section>
			<?php if ($auth->check()) { include("partials/reply.php"); } ?>
		</main>
		<?php include("partials/footer.php"); ?>
	</body>
</html>
