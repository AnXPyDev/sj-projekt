async function post_action(action_name, args = {}, callback = (success, response) => {
	return success === true ? response : null;
}) {
	let response = { success: false };
	let success = false;

	await fetch("action.php", {
		method: "POST",
		credentials: "include",
		headers: {
			"Content-type": "application/json"
        },
		body: JSON.stringify({
			action: action_name,
			...args
		})
	}).then((response) => response.json()).then((json) => {
		response = json;
	});

	if (response.success === true) {
		success = true;
	}

	return callback(success, response);
}

