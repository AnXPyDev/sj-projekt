<?php
require_once("include/Database.php");
require_once("include/User.php");
require_once("include/Auth.php");
require_once("include/Pager.php");

if (!($auth->check() && $user("admin") == 2)) {
	goto no_permission;
}

$has_permission = true;

$page = 1;
if (isset($_GET["page"])) {
	$page = (int)$_GET["page"];
}

$db = $database->ensure();
$qry_usercount = $db->prepare('select count(*) from user');
$qry_usercount->execute();

$count = $qry_usercount->fetch()[0];
$pager = new Pager("admin.php?page=", $page, $count, 10, 5);

$qry_users = $db->prepare('select * from user order by id asc limit :limit_from,:limit_count');
$qry_users->bindValue(":limit_from", ($page - 1) * 10, PDO::PARAM_INT);
$qry_users->bindValue(":limit_count", 10, PDO::PARAM_INT);
$qry_users->execute();

$userlist = [];

while ($row = $qry_users->fetch(PDO::FETCH_ASSOC)) {
	$userlist[] = new User($row);
}


no_permission:;
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("partials/head.php"); ?>
		<title>Diskusne forum</title>
		<script src="scripts/usermod.js"></script>
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/content.css">
		<link rel="stylesheet" href="styles/thread.css">
		<link rel="stylesheet" href="styles/post.css">
		<link rel="stylesheet" href="styles/user.css">
	</head>
	<body>
		<?php include("partials/header.php"); ?>
		<main id="content-center" class="content">
			<?php if (isset($userlist)): ?>
			<section class="content-title">
				User management
			</section>
			<section class="thread-list">
				<?php
				foreach ($userlist as $tuser) {
					echo $tuser->make_html();
				}
				?>
			</section>
			<section class="page-nav">
				<?php echo $pager->make_html(); ?>
			</section>
			<?php else: ?>
			<h1>You dont have permission to access this</h1>
			<?php endif ?>
		</main>
		<?php include("partials/footer.php"); ?>
		<section id="post-preview" class="post"></section>
	</body>
</html>
