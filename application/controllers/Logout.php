<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Logout extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_login' );
	}
	
	function index() {
		$this->session->unset_userdata ( 'logged_in' );
		session_destroy ();
		redirect ( 'login', 'refresh' );
	}
}

?>