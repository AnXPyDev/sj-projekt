<?php
require_once("include/Auth.php");
require_once("include/User.php");
?>
<header>
<div class="header-left">
	<div class="header-title">Forum</div>
	<a href="index.php"><i class="fas fa-home"></i> Index</a>
</div>
<div class="header-right">
<?php
	if ($auth->check()) {
		echo <<<END
	<a href="user.php?id={$user("id")}"><i class="fas fa-user-circle"></i> {$user("username")}</a>
	<div class="button" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</div>
END;
	} else {
		echo <<<END
	<a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
	<a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
END;
	}
?>
</div>
</header>
