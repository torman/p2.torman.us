
<h2>+1 Features are listed as following</h2>
<ul>
<li>Edit and display profile : click Profile link in the menu</li>
<li>Reset password: click Profile link in the menu</li>
<li>Edit a post: click My Posts link in the menu</li>
<li>Delete a post: click My Posts link in the menu</li>
<li>Like feature: "Like" link below each post in this page</li>

</ul>
<hr>

<h1>Welcome to <?php echo APP_NAME;?><?php if($user) echo ', '.$user->first_name; ?></h1>

<?php if ($user) { ?>
	<?php foreach($posts as $post): ?>

	<article>

		<h2><?php echo $post['first_name']?> <?php echo $post['last_name']?> posted:</h2>

		<p><?php echo $post['content']?></p>

	</article>

		<time datetime="<?php echo Time::display($post['created'],'Y-m-d G:i')?>" id="post_modified">
			<?php echo Time::display($post['created'])?>
		</time>&nbsp;&nbsp;
	
	<a href='/posts/like/<?php echo $post['post_id']; ?>/<?php echo $post['num_like']; ?>'>Like</a>&nbsp
	<?php echo $post['num_like']; ?>
	<hr>
	<?php endforeach; ?>
	
<?php } ?>