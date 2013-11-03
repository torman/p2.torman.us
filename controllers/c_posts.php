<?php

class posts_controller extends base_controller {

    public function __construct() {
        parent::__construct();

        # Make sure user is logged in if they want to use anything in this controller
        if(!$this->user) {
            die("Members only. <a href='/users/login'>Login</a>");
        }
    }

    public function add() {

        # Setup view
        $this->template->content = View::instance('v_posts_add');
        $this->template->title   = "New Post";

        # Render template
        echo $this->template;
    }

    public function p_add() {

        # Associate this post with this user
        $_POST['user_id']  = $this->user->user_id;

        # Unix timestamp of when this post was created / modified
        $_POST['created']  = Time::now();
        $_POST['modified'] = Time::now();

        # Insert
        # Note we didn't have to sanitize any of the $_POST data because we're using the insert method which does it for us
        DB::instance(DB_NAME)->insert('posts', $_POST);

 		Router::redirect('/posts/myposts');
    }

	public function index() {

		# Set up the View
		$this->template->content = View::instance('v_posts_index');
		$this->template->title   = "Posts";

    $q = 'SELECT 
            posts.content,
            posts.created,
			posts.post_id,
            posts.user_id AS post_user_id,
            users_users.user_id AS follower_id,
            users.first_name,
            users.last_name
        FROM posts
        INNER JOIN users_users 
            ON posts.user_id = users_users.user_id_followed
        INNER JOIN users 
            ON posts.user_id = users.user_id
        WHERE users_users.user_id = ' . $this->user->user_id;
				
		# Run the query
		$posts = DB::instance(DB_NAME)->select_rows($q);

		# Pass data to the View
		$this->template->content->posts = $posts;

		# Render the View
		echo $this->template;
	}
	
	public function myposts() {

		# Set up the View
		$this->template->content = View::instance('v_posts_myposts');
		$this->template->title   = "My Posts";

		# only select posts created by the logged in user
		# the latest modified one is on the top
		$q = "SELECT *
			FROM posts where user_id = " . $this->user->user_id .
			" ORDER BY modified DESC";
			
// echo $q;			

		# Run the query
		$posts = DB::instance(DB_NAME)->select_rows($q);

		# Pass data to the View
		$this->template->content->posts = $posts;

		# Render the View
		echo $this->template;
	}
	
	public function post_edit($post_id = NULL) {

		# Set up the View
		$this->template->content = View::instance('v_posts_post_edit');
		$this->template->title   = "Post Edit";

		# only select posts created by the logged in user
		# the latest modified one is on the top
		$q = "SELECT *
			FROM posts where user_id = " . $this->user->user_id .
			" AND post_id = " . $post_id;

		# Run the query
		$post = DB::instance(DB_NAME)->select_row($q);

// echo "<pre>";		
// print_r($post);
// echo "</pre>";		
		
		# Pass data to the View
		$this->template->content->post = $post;

		# Render the View
		echo $this->template;
	}

	public function p_post_edit($post_id = NULL) {

		$s = "";
		$current_time = Time::now();
		
		$s = $s . "content='" . $_POST['content'] . "', " .
				'modified=' . $current_time; 
		

		$q = "UPDATE posts SET " . $s . 
				" where user_id = " . $this->user->user_id .
				" AND post_id = " . $post_id;
// echo $q;
		// # Run the query
		DB::instance(DB_NAME)->query($q);

		Router::redirect('/posts/myposts');
	}
	
	public function post_delete($post_id = NULL) {

		$q = "DELETE FROM posts" . 
				" WHERE user_id = " . $this->user->user_id .
				" AND post_id = " . $post_id;
// echo $q;
		# Run the query
		DB::instance(DB_NAME)->query($q);

		Router::redirect('/posts/myposts');
	}
	
	public function users() {

		# Set up the View
		$this->template->content = View::instance("v_posts_users");
		$this->template->title   = "Users";

		# Build the query to get all the users
		// $q = "SELECT *
			// FROM users";

		// build the query to get all users except the logged-in user
		$q = "SELECT *
			FROM users WHERE user_id != " . $this->user->user_id;
			
		# Execute the query to get all the users. 
		# Store the result array in the variable $users
		$users = DB::instance(DB_NAME)->select_rows($q);

		# Build the query to figure out what connections does this user already have? 
		# I.e. who are they following
		$q = "SELECT * 
			FROM users_users
			WHERE user_id = ".$this->user->user_id;

		# Execute this query with the select_array method
		# select_array will return our results in an array and use the "users_id_followed" field as the index.
		# This will come in handy when we get to the view
		# Store our results (an array) in the variable $connections
		$connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

		# Pass data (users and connections) to the view
		$this->template->content->users       = $users;
		$this->template->content->connections = $connections;

		$client_files_head = Array("/css/posts_users.css");
		$this->template->client_files_head = Utils::load_client_files($client_files_head);

		# Render the view
		echo $this->template;
	}	
	
	public function follow($user_id_followed) {

		# Prepare the data array to be inserted
		$data = Array(
			"created" => Time::now(),
			"user_id" => $this->user->user_id,
			"user_id_followed" => $user_id_followed
			);

		# Do the insert
		DB::instance(DB_NAME)->insert('users_users', $data);

		# Send them back
		Router::redirect("/posts/users");
	}

	public function unfollow($user_id_followed) {

		# Delete this connection
		$where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;
		DB::instance(DB_NAME)->delete('users_users', $where_condition);

		# Send them back
		Router::redirect("/posts/users");
	}

	public function like($post_id = NULL, $like = NULL) {

		$like = $like + 1;
		$q = "UPDATE posts SET num_like = " . $like . " WHERE post_id = " . $post_id ; 

// echo $q;		
		DB::instance(DB_NAME)->query($q);

		# Send them back
		Router::redirect("/");
	}
		
}
?>