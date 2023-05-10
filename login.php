<?php
include("include/config.php");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("partials/head.php"); ?>
		<script src="scripts/login.js"></script>
		<title>Diskusne forum - Login</title>
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/content.css">
	</head>
	<body>
		<section class="center">
			<h1>Login</h1>
			<form name="login_form" id="login_form" action="javascript:" onsubmit="login(this);">
				<label for="inusername">Username:</label>
				<input type="text" id="inusername" name="username">
				<label for="inpassword">Password:</label>
				<input type="password" id="inpassword" name="password">
				<button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
			</form>
		</section>
	</body>
</html>
