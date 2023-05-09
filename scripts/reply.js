async function reply(form) {
	const data = new FormData(form);
	const body = {
		thread_id: data.get("thread_id"),
		content: data.get("content")
	};

	if (!(body.content && body.thread_id)) {
		alert("Data missing in form");
		return;
	}
	
	body.thread_id = parseInt(body.thread_id);

	post_action("newpost", body, (success, data) => {
		if (success) {
			alert("Post created");
			window.location.reload();
		} else {
			alert("Post creation failed:" + data["error"] ?? "no error returned");
		}
	});
}
