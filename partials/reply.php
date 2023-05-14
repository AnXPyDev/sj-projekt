<form name="reply_form" id="reply_form" action="javascript:" onsubmit="reply(this);">
	<input type="hidden" name="thread_id" value="<?php echo $thread->data["id"]; ?>">
	<textarea id="incontent" name="content"></textarea>
	<div class="reply-options">
		<button type="submit"><i class="fas fa-reply"></i> Reply to thread</button>
		<button type="button" onclick="reply_cancel_button(this);"><i class="fas fa-backspace"></i> Cancel</button>
	</div>
</form>
