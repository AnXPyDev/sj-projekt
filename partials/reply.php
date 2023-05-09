<form name="reply_form" id="reply_form" action="javascript:" onsubmit="reply(this);">
	<input type="hidden" name="thread_id" value="<?php echo $thread->data["id"]; ?>">
	<label for="incontent">Post content:</label><br>
	<textarea id="incontent" name="content"></textarea><br>
	<input type="submit" value="Post">
</form>
