document.addEventListener("DOMContentLoaded", (event) => {

const preview = document.getElementById("post-preview");

let post_cache = {}

for (const link of document.querySelectorAll(".post-content .post-link")) {
	const post_id = parseInt(link.getAttribute("post_id"));

	let has_hover = false;

	link.addEventListener("mouseover", (event) => {
		has_hover = true;
		if (post_cache[post_id]) {
			preview.innerHTML = post_cache[post_id];
			preview.style.visibility = "visible";
			return;
		} else if (post_cache[post_id] == undefined) {
			post_cache[post_id] = false;
			link.style.cursor = "progress";

			post_action("getpostpreview", {"post_id": post_id}, (success, response) => {
				if (success) {
					post_cache[post_id] = response["html"];
				} else {
					post_cache[post_id] = "Error: " + response["error"];
				}

				if (has_hover) {
					preview.innerHTML = post_cache[post_id];
					preview.style.visibility = "visible";
					link.style.cursor = null;
				}
			});
		}
	});

	link.addEventListener("mousemove", (event) => {
		const page = document.body.clientWidth;
		const half_page = page / 2;
		const center_offset = event.pageX - half_page;

		if (center_offset > 0) {
			preview.style.transform = "translate(calc(-100% - 2 * var(--pad)), -50%)";
		} else {
			preview.style.transform = null;
		}

		preview.style.maxWidth = "calc(" + (page - (half_page - Math.abs(center_offset))) + "px - 4 * var(--pad))";

		preview.style.top = event.pageY + "px";
		preview.style.left = event.pageX + "px";
	});
	
	link.addEventListener("mouseleave", (event) => {
		has_hover = false;
		link.style.cursor = null;
		preview.style.maxWidth = null;
		preview.style.visibility = "hidden";
	});

}

});
