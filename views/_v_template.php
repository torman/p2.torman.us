<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
					
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>	

    <div id='menu'>

        <a href='/'>Home</a>&nbsp;

        <!-- Menu for users who are logged in -->
        <?php if($user): ?>

            <a href='/users/logout'>Logout</a>&nbsp;
            <a href='/users/profile'>Profile</a>&nbsp;
            <a href='/posts/add'>Add Post</a>&nbsp;
			<a href='/posts/myposts'>My Posts</a>&nbsp;
			<a href='/posts/users'>Bonjourer</a>			
        <!-- Menu options for users who are not logged in -->
        <?php else: ?>

            <a href='/users/signup'>Sign up</a>&nbsp;
            <a href='/users/login'>Log in</a>

        <?php endif; ?>

    </div>

    <br>
	
	<?php if(isset($message)) echo $message; ?>

	<?php if(isset($content)) echo $content; ?>

	<?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>
