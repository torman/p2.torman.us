<?php 
	if (isset($user_name)) { ?>
		<h1>This is the profile for <?php echo $user_name?></h1>
	<?php }
	else { ?>
		<h1>No user specified</h1>
	<?php } ?>