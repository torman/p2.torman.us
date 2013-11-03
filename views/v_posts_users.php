<?php foreach($users as $user): ?>


    <!-- Print this user's name -->
	<div id="full_name"><?php echo $user['first_name']?> <?php echo $user['last_name']?></div><br>
	<div id="date">
		
	<?php echo "Joined since "?>
    <time datetime="<?php echo Time::display($user['created'],'Y-m-d G:i')?>">
        <?php echo Time::display($user['created'])?>
    </time>

    <!-- If there exists a connection with this user, show a unfollow link -->
    <?php if(isset($connections[$user['user_id']])): ?>
        <a href='/posts/unfollow/<?php echo $user['user_id']?>' id='follow_link'>Unfollow</a>

    <!-- Otherwise, show the follow link -->
    <?php else: ?>
        <a href='/posts/follow/<?php echo $user['user_id']?>' id='follow_link'>Follow</a>
    <?php endif; ?>
	</div>
	<hr>

<?php endforeach; ?>
