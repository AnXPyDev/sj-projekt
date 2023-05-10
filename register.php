<?php
include("include/config.php");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("partials/head.php"); ?>
		<script src="scripts/register.js"></script>
		<title>Diskusne forum - Login</title>
		<link rel="stylesheet" href="styles/main.css">
		<link rel="stylesheet" href="styles/content.css">
	</head>
	<body>
		<section class="center">
			<h1>Register</h1>
			<form name="register_form" id="register_form" action="javascript:" onsubmit="register(this);">
				<label for="inusername">Username:</label>
				<input type="text" id="inusername" name="username">
				<label for="inpassword">Password:</label>
				<input type="password" id="inpassword" name="password">
				<button type="submit"><i class="fas fa-user-plus"></i> Register</button>
			</form>
		</section>
	</body>
</html>
