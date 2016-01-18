<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Login extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_login' );
	}
	
	function index()
	{
		$this->load->helper(array('form'));
		
		//This method will have the credentials validation
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
		
		if($this->form_validation->run() == FALSE)
		{
			//Field validation failed.  User redirected to login page
			$this->load->view ( 'login' );
		}
		else
		{
			//Go to private area
			redirect('attendance/student', 'refresh');
		}
	}
	
	function check_database($password) {
		// Field validation succeeded. Validate against database
		$username = $this->input->post ( 'username' );
		
		// query the database
		$result = $this->model_login->login ( $username, $password );
		
		if ($result) {
			$sess_array = array ();
			foreach ( $result as $row ) {
				$sess_array = array (
						'uid' => $row->id,
						'username' => $row->login_name,
						'type' => $row->employee_type_id
				);
				$this->session->set_userdata ( 'logged_in', $sess_array );
			}
			return TRUE;
		} else {
			$this->form_validation->set_message ( 'check_database', 'Invalid username or password' );
			return false;
		}
	}
}

?>