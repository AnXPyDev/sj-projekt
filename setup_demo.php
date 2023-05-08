<?php

require_once("include/Database.php");

$result = [];

$register = @include_once("include/actions/register.php");
$login = @include_once("include/actions/login.php");
$logout = @include_once("include/actions/logout.php");
$newthread = @include_once("include/actions/newthread.php");

$logout([], $result);
print_r($result); echo "<br>";

$credentials = [
[
	"username" => "root",
	"password" => "12345",
	"admin" => true
],[
	"username" => "dominik_vrana",
	"password" => "domco123"
],[
	"username" => "jkomaromy03",
	"password" => "komaromy22"
],[
	"username" => "another343",
	"password" => "22101"
]
];

$lipsum = include_once("include/lipsum.php");

foreach ($credentials as $cred) {
	$result = [];
	$register($cred, $result);
	echo "Register ".print_r($cred, true)." ".print_r($result, true)."<br>";
}

foreach ($credentials as $cred) {
	$result = [];
	$login($cred, $result);
	echo "Login ".print_r($cred, true)." ".print_r($result, true)."<br>";
	for ($i = 0; $i < 3; $i++) {
		$result = [];
		$newthread(["title" => $lipsum[array_rand($lipsum)]], $result);
		echo "Newthread ".print_r($result, true)."<br>";
	}
	$result = [];
	$logout([], $result);
	echo "Logout ".print_r($result, true)."<br>";
}

?>
