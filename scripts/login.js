function login(form) {
	const data = new FormData(form);
	const body = {
		username: data.get("username"),
		password: data.get("password")
	};

	if (!(body.username && body.password)) {
		alert("Data missing in form");
		return;
	}
	post_action("login", body, (success, data) => {
		if (success) {
			alert("login successful");
			window.location = "index.php";
		} else {
			alert("login failed, try again");
		}
	});
}
