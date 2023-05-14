function create_thread(form) {
	const data = new FormData(form);
	const body = {
		title: data.get("title"),
	};

	if (!body.title) {
		alert("Data missing in form");
		return;
	}
	post_action("newthread", body, (success, data) => {
		if (success) {
			//alert("Thread created");
			window.location = "index.php";
		} else {
			alert("Thread creation failed:" + data["error"] ?? "no error returned");
		}
	});
}
