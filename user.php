<?php
require_once("include/Database.php");
require_once("include/Auth.php");
require_once("include/User.php");

if (!isset($_GET["id"])) {
	goto user_not_found;
}

$db = $database->ensure();
$qry = $db->prepare('select * from user where id = :user_id');
$qry->execute(["user_id" => $_GET["id"]]);

if ($qry->rowCount() == 0) {
	goto user_not_found;
}

$tuser = new User($qry->fetch(PDO::FETCH_ASSOC));

user_not_found:
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("partials/head.php"); ?>
		<title>Diskusne forum</title>
		<script src="scripts/usermod.js"></script>
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/content.css">
		<link rel="stylesheet" href="styles/post.css">
		<link rel="stylesheet" href="styles/user.css">
	</head>
	<body>
		<?php include("partials/header.php"); ?>
		<main id="content-center" class="content">
			<?php if (isset($tuser)): ?>
			<section class="content-title">
				User profile
			</section>
			<section class="post user">
				<?php echo $tuser->make_html(); ?>
			</section>
			<?php else: ?>
			<h1>User not found</h1>
			<?php endif ?>
		</main>
		<?php include("partials/footer.php"); ?>
	</body>
</html>
