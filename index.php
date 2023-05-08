<?php
require_once("include/Index.php");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("partials/head.php"); ?>
		<title>Diskusne forum</title>
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/content.css">
		<link rel="stylesheet" href="styles/thread.css">
		<link rel="stylesheet" href="styles/post.css">
	</head>
	<body>
		<header>
			<?php include("partials/header.php"); ?>
		</header>
		<main id="content-center" class="content">
			<section class="content-title">
				Index
			</section>
			<section class="thread-list">
				<?php $index->make_thread_list(); ?>
			</section>
			<section class="page-nav">
				<a href="#"><i class="fas fa-angle-double-left"></i></a>
				<a href="#">1</a>
				<a href="#">2</a>
				<a class="current" href="#">3</a>
				<a href="#">4</a>
				<a href="#">5</a>
				<a href="#"><i class="fas fa-angle-double-right"></i></a>
			</section>
		</main>
		<footer>
			<div class="footer-credit">Created by Jozef Koamaromy</div>
		</footer>
	</body>
</html>
