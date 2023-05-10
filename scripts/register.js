function register(form) {
	const data = new FormData(form);
	const body = {
		username: data.get("username"),
		password: data.get("password")
	};

	if (!(body.username && body.password)) {
		alert("Data missing in form");
		return;
	}
	post_action("register", body, (success, data) => {
		if (success) {
			alert("register successful, please login");
			window.location = "login.php";
		} else {
			alert("Register failed: " + data["error"] ?? "no error returned");
		}
	});
}
