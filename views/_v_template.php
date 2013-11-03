<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	

	<link rel="stylesheet" type="text/css" href="/css/profile.css">
	
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	
	
		<div id='menu'>

			<a href='/' title="home">Home</a>&nbsp;&nbsp;

			<!-- Menu for users who are logged in -->
			<?php if($user): ?>

				<a href='/posts/add' title="add post">Add Post</a>&nbsp;&nbsp;
				<a href='/posts/myposts' title="my posts">My Posts</a>&nbsp;&nbsp;
				<a href='/posts/users' title="list of users">Bonjourer </a>&nbsp;&nbsp;			
				<a href='/users/profile' title="profile" id="profile_pos" ><?php echo $user->first_name; ?></a>&nbsp;&nbsp;
				<a href='/users/logout' title="logout" id='menu_last_item'>Logout</a>
	 
				<!-- Menu options for users who are not logged in -->
			<?php else: ?>

				<a href='/users/signup' title="sign up">Sign up</a>&nbsp;&nbsp;
				<a href='/users/login' title="login" id='menu_last_item'>Log in</a>

			<?php endif; ?>
		</div>
 
    <br>
	
	<?php if(isset($message)) echo $message; ?>

	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>
