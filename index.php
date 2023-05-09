<?php
require_once("include/Index.php");
require_once("include/Auth.php");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("partials/head.php"); ?>
		<title>Diskusne forum</title>
		<script src="scripts/create_thread.js"></script>
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/content.css">
		<link rel="stylesheet" href="styles/thread.css">
		<link rel="stylesheet" href="styles/post.css">
	</head>
	<body>
		<?php include("partials/header.php"); ?>
		<main id="content-center" class="content">
			<section class="content-title">
				Index
			</section>
			<section class="thread-list">
				<?php $index->make_thread_list(); ?>
			</section>
			<section class="page-nav">
				<?php $index->make_pager()->make_html(); ?>
			</section>
			<?php if ($auth->check()) { include("partials/create_thread.php"); } ?>
		</main>
		<?php include("partials/footer.php"); ?>
	</body>
</html>
