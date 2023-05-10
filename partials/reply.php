<form name="reply_form" id="reply_form" action="javascript:" onsubmit="reply(this);">
	<input type="hidden" name="thread_id" value="<?php echo $thread->data["id"]; ?>">
	<textarea id="incontent" name="content"></textarea>
	<button type="submit"><i class="fas fa-reply"></i> Reply to thread</button>
</form>
