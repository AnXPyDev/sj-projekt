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
		<h1>Register</h1>
		<form name="register_form" id="register_form" action="javascript:" onsubmit="register(this);">
	  		<label for="inusername">Username:</label><br>
	  		<input type="text" id="inusername" name="username"><br>
	  		<label for="inpassword">Password:</label><br>
			<input type="password" id="inpassword" name="password"><br>
			<input type="submit" value="Submit">
		</form>
	</body>
</html>
