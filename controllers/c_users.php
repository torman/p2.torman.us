<?php
class  users_controller extends base_controller {

	public function __construct() {

		parent::__construct();
		// echo "users_controller construct called<br><br>";
	}

	public function profile ($user_name = NULL) {

		# if the user has not logged in, redirect to login page
		if (!$this->user) {
			Router::redirect('/users/login');
		}

		#build user profile view
		$this->template->content = View::instance('v_users_profile');

		$this->template->title = "Profile of " . $this->user->first_name;

		// echo '<pre>';
		// print_r($_POST);
		// echo '<pre>';

		$client_files_head = Array("/css/profile.css");
		$this->template->client_files_head = Utils::load_client_files($client_files_head);

		$client_files_body = Array("/js/profile.min.js");
		$this->template->client_files_body = Utils::load_client_files($client_files_body);

// echo '<pre>';
// print_r($this->user);
// echo '</pre>';

		echo $this->template;
		
		// echo "End of profile page";
	}

	public function profile_edit ($user_name = NULL) {

		# if the user has not logged in, redirect to login page
		if (!$this->user) {
			Router::redirect('/users/login');
		}

		#build user profile edit view
		$this->template->content = View::instance('v_users_profile_edit');

		$this->template->title = "Edit profile of " . $this->user->first_name;

		
		// echo '<pre>';
		// print_r($_POST);
		// echo '<pre>';

		$client_files_head = Array("/css/profile.css");
		$this->template->client_files_head = Utils::load_client_files($client_files_head);

		$client_files_body = Array("/js/profile.min.js");
		$this->template->client_files_body = Utils::load_client_files($client_files_body);

// echo '<pre>';
// print_r($this->user);
// echo '</pre>';

		echo $this->template;
	}
	
	public function p_profile_edit ($user_name = NULL) {

		// don't upate if password doesn't match password
		// the user might have entered a password which s/he doesn't want
		// retrun to profile edit form
		if ($_POST['password'] != $_POST['password2']) { 
			$error = TRUE; 
			$this->template->message = "Password not matched.";
			Router::redirect("/users/profile_edit");
		}
		
		// if all fields are empty, redirect to /users/profile_edit
		if (!isset($_POST)) {
			Router::redirect("/users/profile_edit");
		}
		// we only update those fields are not empty
		# Sanitize the user input from profile edit form
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);

		# hash the password submitted by the user
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

		// don't update the field password2, no such field in database
		unset($_POST['password2']); 

	
		$index = 0;
		$s = "";

// echo '<pre>';
// print_r($_POST);
// echo '<pre>';
		
		foreach ($_POST as $key=>$value) {
			// prepend a comma in front, but not the first item.
			if ($index != 0) {
				$s = $s . ",";
			}
			$s = $s . " $key " . "= '" . $value . "'";
			$index++;
		}

		$_POST['modified'] = Time::now();
		$s = $s . ', modified = ' . $_POST['modified'];

		$q = "UPDATE users SET " . $s . " WHERE user_id = " .
				$this->user->user_id ;
		
		// echo $q;
		DB::instance(DB_NAME)->query($q);

		$q = "SELECT token FROM users WHERE email = '"  
				. $_POST['email'] . "'"
				. " AND "
				. "password = '" 
				. $_POST['password'] . "'"; 

		$token = DB::instance(DB_NAME)->select_field($q);
		setcookie('token', $token, strtotime('+2 week'), '/');
		
		$client_files_head = Array("/css/profile.css");
		$this->template->client_files_head = Utils::load_client_files($client_files_head);

		$client_files_body = Array("/js/profile.min.js");
		$this->template->client_files_body = Utils::load_client_files($client_files_body);

// echo '<pre>';
// print_r($this->user);
// echo '</pre>';

		// go back profile page after upated
		Router::redirect("/users/profile");
	}

	public function signup() {
		
		$this->template->content = View::instance('v_users_signup');
		$this->template->title = "Sign Up";
		echo $this->template;
	}

	public function p_signup() {

		// echo '<pre>';
		// print_r($_POST);
		// echo '<pre>';

		# we want to know when the user is created and user's data is changed
		$_POST['created'] = Time::now();
		$_POST['modified'] = Time::now();

		/* encrypt user's password */
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

		# create an encrypted token via email address and a arandom string 
		$_POST['token'] = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());

		# insert the user into database
		$user_id = DB::instance(DB_NAME)->insert('users', $_POST);

		# want to see the output; should comment out later
		echo "user ID is $user_id <br>";
		echo "You're signed up";

		# login after the user signed in and redirect to home page
		# retrieve the token from database
		$q = "SELECT token FROM users WHERE email = '"  
				. $_POST['email'] . "'"
				. " AND "
				. "password = '" 
				. $_POST['password'] . "'"; 

		$token = DB::instance(DB_NAME)->select_field($q);
		setcookie('token', $token, strtotime('+2 week'), '/');
		Router::redirect("/");
	}

	public function login() {

		$this->template->content = View::instance('v_users_login');
		$this->template->title = "Login";
		echo $this->template;
	}

	public function p_login() {
		
		# Sanitize the user input from login form
		$_POST = DB::instance(DB_NAME)->sanitize($_POST);

		# hash the password submitted by the user
		$_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

		# retrieve the token from database
		$q = "SELECT token FROM users WHERE email = '"  
				. $_POST['email'] . "'"
				. " AND "
				. "password = '" 
				. $_POST['password'] . "'"; 

		$token = DB::instance(DB_NAME)->select_field($q);
		#echo $token;

		# if no token found (wrong email or password), send the user to login page. 
		if (!$token) {		
			Router::redirect("/users/login");
		} 
		else {

			setcookie('token', $token, strtotime('+2 week'), '/');
// echo "something here";
// echo "<br>";
// echo '<pre>';
// print_r($this->user);
// echo '</pre>';
// echo "END of PAGE";
			
			# send the user to home page now
			Router::redirect("/");
		}
	}

	public function logout() {

    # Generate and save a new token for next login
    $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());

    # Create the data array we'll use with the update method
    # In this case, we're only updating one field, so our array only has one entry
    $data = Array("token" => $new_token);

    # Do the update
    DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");

    # Delete their token cookie by setting it to a date in the past - effectively logging them out
    setcookie("token", "", strtotime('-1 year'), '/');

    # Send them back to the main index.
    Router::redirect("/");

}

	
}
