<?php foreach($users as $user): ?>


    <!-- Print this user's name -->
    <?php echo $user['first_name']?> <?php echo $user['last_name']?>

    <!-- If there exists a connection with this user, show a unfollow link -->
    <?php if(isset($connections[$user['user_id']])): ?>
        <a href='/posts/unfollow/<?php echo $user['user_id']?>'>Unfollow</a>

    <!-- Otherwise, show the follow link -->
    <?php else: ?>
        <a href='/posts/follow/<?php echo $user['user_id']?>'>Follow</a>
    <?php endif; ?>

    <br><br>

<?php endforeach; ?>
