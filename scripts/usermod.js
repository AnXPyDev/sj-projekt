function user_delete(button) {
	if (!confirm("Are you sure you want to delete this user? This action cannot be undone!")) {
		return;
	}

	const id = parseInt(button.getAttribute("user_id"));
	post_action("usermod", {"id": id, "mod": "delete"}, (success, response) => {
		if (success) {
			window.location.reload();
		} else {
			alert("Failed to delete user: " + response["error"] ?? "No error returned");
		}
	})
}

function user_ban(button) {
	const id = parseInt(button.getAttribute("user_id"));
	post_action("usermod", {"id": id, "mod": "ban"}, (success, response) => {
		if (success) {
			window.location.reload();
		} else {
			alert("Failed to ban user: " + response["error"] ?? "No error returned");
		}
	})
}

function user_unban(button) {
	const id = parseInt(button.getAttribute("user_id"));
	post_action("usermod", {"id": id, "mod": "unban"}, (success, response) => {
		if (success) {
			window.location.reload();
		} else {
			alert("Failed to unban user: " + response["error"] ?? "No error returned");
		}
	})
}

function user_setadmin(button) {
	const id = parseInt(button.getAttribute("user_id"));
	const level = parseInt(button.getAttribute("admin_level"));
	post_action("usermod", {"id": id, "mod": "setadmin", "level": level}, (success, response) => {
		if (success) {
			window.location.reload();
		} else {
			alert("Failed to set admin level: " + response["error"] ?? "No error returned");
		}
	})
}
