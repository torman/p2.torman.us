<?php
class  users_controller extends base_controller {

	public function __construct() {

		parent::__construct();
		// echo "users_controller construct called<br><br>";
	}

	public function profile ($user_name = NULL) {
		$this->template->content = View::instance('v_users_profile');

		$this->template->title = "Profile";
		

		$client_files_head = Array("/css/profile.css");
		$this->template->client_files_head = Utils::load_client_files($client_files_head);

		$client_files_body = Array("/js/profile.min.js");
		$this->template->client_files_body = Utils::load_client_files($client_files_body);

		$this->template->content->user_name = $user_name;

		echo $this->template;
	}

	public function signup() {
		
		$this->template->content = View::instance('v_users_signup');
		$this->template->title = "Sign Up";

		echo $this->template;

	}

	public function p_signup() {

/*
		echo '<pre>';
		print_r($_POST);
		echo '<pre>';
*/
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
		echo "user ID is $user_id";
		echo "You're signed up";
	}

	public function login() {

		$this->template = View::instance('v_users_login');
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
			
			# send the user to home page now
			Router::redirect("/");
		}
/*
		
		$q = "SELECT token FROM users 
				WHERE email = '".$_POST['email']."'
				AND password = ".$_POST['password']."'";

		echo $q;
*/
		}

}
