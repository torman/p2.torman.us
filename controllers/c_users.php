<?php
class  users_controller extends base_controller {

	public function __construct() {

		parent::__construct();
		echo "users_controller construct called<br><br>";
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
}
