<?php foreach($posts as $post): ?>

<article>

    <p><?php echo $post['content']?></p>

    <time datetime="<?php echo Time::display($post['modified'],'Y-m-d G:i')?>">
        <?php echo Time::display($post['modified'])?>
    </time>
	&nbsp;
	<a href='/posts/post_edit/<?php echo $post['post_id']; ?>'>Edit</a>

	&nbsp; 
	<a href='/posts/post_delete/<?php echo $post['post_id']; ?>'>Delete</a>
</article>

<hr>

<?php endforeach; ?>
