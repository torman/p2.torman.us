<form method='POST' action='/posts/p_post_edit/
	<?php echo $post['post_id'] ?> '>
	<textarea rows="10" cols="60" name="content"><?php echo $post['content']; ?></textarea> 
	<br><br>
    <input type='submit' value='Submit Change'>
</form>