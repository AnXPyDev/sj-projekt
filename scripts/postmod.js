function get_post(button) {
	const id = parseInt(button.getAttribute("post_id"));
	const post = document.getElementById("post" + id);

	return {
		id: id,
		post: post
	};
}

function post_edit(button) {
	const { id, post } = get_post(button);
	post_action("postmod", {"mod": "rawcontent", "post_id": id}, (success, response) => {
		if (!success) {
			alert("Failed to get post rawcontent: " + response["error"] ?? "No error returned");
			return;
		}

		post.classList.add("post-edit-mode");
		post.classList.remove("post-standard-mode");
		
		const editor = post.querySelector(".post-editor");
		editor.value = response["content"];
	});
}

function post_save_edit(button) {
	const { id, post } = get_post(button);
	const editor = post.querySelector(".post-editor");

	post_action("postmod", {"mod": "edit", "post_id": id, "content": editor.value}, (success, response) => {
		if (!success) {
			alert("Failed to edit post: " + response["error"] ?? "No error returned");
			post.classList.remove("post-edit-mode");
			post.classList.add("post-standard-mode");
			return;
		}
		window.location.reload();
	});
}

function post_cancel_edit(button) {
	const { post } = get_post(button);
	post.classList.remove("post-edit-mode");
	post.classList.add("post-standard-mode");
}

function post_delete(button) {
	if (!confirm("Are you sure you want to delete this post? This action cannot be undone.")) {
		return;
	}
	const { id } = get_post(button);
	post_action("postmod", {"mod": "delete", "post_id": id}, (success, response) => {
		if (!success) {
			alert("Failed to remove post: " + response["error"] ?? "No error returned");
			return;
		}
		window.location.reload();
	});
}
