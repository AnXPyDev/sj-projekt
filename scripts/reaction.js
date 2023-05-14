function delete_reaction(button, counter, score) {
	if (button.classList.contains("reaction-active")) {
		counter.textContent = parseInt(counter.textContent) - score;
		button.classList.remove("reaction-active");
	}
}

function add_reaction(button, counter, score) {
	if (!button.classList.contains("reaction-active")) {
		counter.textContent = parseInt(counter.textContent) + score;
		button.classList.add("reaction-active");
	}
}

function like_button(button) {
	const like_button = button;
	const post_id = parseInt(button.getAttribute("post_id"));
	const dislike_button = button.parentNode.querySelector(".post-button-dislike");
	const score_counter = button.parentNode.querySelector(".post-score");

	if (like_button.classList.contains("reaction-active")) {
		post_action("react", {"post_id": post_id, "type": "discard"}, (success, response) => {
			if (!success) {
				alert("Failed to discard reaction: " + response["error"] ?? "No error returned");
				return;
			}
			
			delete_reaction(like_button, score_counter, 1);
		});
	} else {
		post_action("react", {"post_id": post_id, "type": "like"}, (success, response) => {
			if (!success) {
				alert("Failed to like: " + response["error"] ?? "No error returned");
				return;
			}
			
			delete_reaction(dislike_button, score_counter, -1);
			add_reaction(like_button, score_counter, 1);
		});
	}
}

function dislike_button(button) {
	const dislike_button = button;
	const post_id = parseInt(button.getAttribute("post_id"));
	const like_button = button.parentNode.querySelector(".post-button-like");
	const score_counter = button.parentNode.querySelector(".post-score");
	
	if (dislike_button.classList.contains("reaction-active")) {
		post_action("react", {"post_id": post_id, "type": "discard"}, (success, response) => {
			if (!success) {
				alert("Failed to discard reaction: " + response["error"] ?? "No error returned");
				return;
			}
			
			delete_reaction(dislike_button, score_counter, -1);
		});
	} else {
		post_action("react", {"post_id": post_id, "type": "dislike"}, (success, response) => {
			if (!success) {
				alert("Failed to like: " + response["error"] ?? "No error returned");
				return;
			}
			
			delete_reaction(like_button, score_counter, 1);
			add_reaction(dislike_button, score_counter, -1);
		});
	}
}
