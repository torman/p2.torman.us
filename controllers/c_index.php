<?php

class index_controller extends base_controller {
	
	/*-------------------------------------------------------------------------------------------------

	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();
	} 
		
	/*-------------------------------------------------------------------------------------------------
	Accessed via http://localhost/index/index/
	-------------------------------------------------------------------------------------------------*/
	public function index() {
		
		# Any method that loads a view will commonly start with this
		# First, set the content of the template with a view file
		$this->template->content = View::instance('v_index_index');
			
		# Now set the <title> tag
		$this->template->title = APP_NAME;

		if ($this->user) {
			$q = 'SELECT 
					posts.content,
					posts.created,
					posts.post_id,
					posts.num_like,
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
		}
			
		# CSS/JS includes
			/*
			$client_files_head = Array("");
	    	$this->template->client_files_head = Utils::load_client_files($client_files);
	    	
	    	$client_files_body = Array("");
	    	$this->template->client_files_body = Utils::load_client_files($client_files_body);   
	    	*/
	      					     		
		# Render the view
			echo $this->template;

	} # End of method
	
	
} # End of class
