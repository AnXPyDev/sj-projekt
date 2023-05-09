async function logout() {
	post_action("logout", {}, (success, response) => {
		if (success) {
			alert("Logged out");
			window.location = "index.php";
		} else {
			alert("Logout failed: " + reponse["error"] ?? "no error returned");
		}
	});
}
