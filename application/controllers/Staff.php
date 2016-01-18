<?php
date_default_timezone_set ( 'Asia/Kuala_Lumpur' );
class Staff extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'model_staff' );
	}
	public function index() {
		show_404 ();
	}
	
	//----------------------------------------------------------
	//------------------ Staff info management -----------------
	//----------------------------------------------------------
	public function summary() {
		$userSession = $this->session->userdata ( 'logged_in' );
		if ($userSession['type'] == 1 || $userSession['type'] == 2) {
			$data ['title'] = "Staff Summary";
			$data ['staff_active_rows'] = $this->model_staff->show_staff_active ();
			$data ['staff_inactive_rows'] = $this->model_staff->show_staff_inactive ();
			$data ['get_staff_type'] = $this->model_staff->get_staff_type ();
			
			
			$data['content'] = "staff/summary";
			$this -> load -> view('templates/main', $data);
		} else {
			redirect ( 'login', 'refresh' );
		}
	}
	
	public function getStaffInfo($staff_id) {
		if ($this->session->userdata ( 'logged_in' )) {
			echo $this->model_staff->get_staff_info ( $staff_id );
		} else {
			redirect ( 'login', 'refresh' );
		}
	}
	public function staffNew() {
		// Create New student
		if ($this->session->userdata ( 'logged_in' )) {
			echo $this->model_staff->staff_new ();
		} else {
			redirect ( 'login', 'refresh' );
		}
	}
	public function staffUpdate() {
		// Update student Info
		if ($this->session->userdata ( 'logged_in' )) {
			echo $this->model_staff->staff_update ();
		} else {
			redirect ( 'login', 'refresh' );
		}
	}
	public function staffDeactivate() {
		// Deactivate Student
		if ($this->session->userdata ( 'logged_in' )) {
			echo $this->model_staff->staff_deactivate ();
		} else {
			redirect ( 'login', 'refresh' );
		}
	}
	public function staffActivate() {
		// Deactivate Student
		if ($this->session->userdata ( 'logged_in' )) {
			echo $this->model_staff->staff_activate ();
		} else {
			redirect ( 'login', 'refresh' );
		}
	}
	public function checkID($ID) {
		if ($this->session->userdata ( 'logged_in' )) {
			echo $this->model_staff->checkID ( $ID );
		} else {
			redirect ( 'login', 'refresh' );
		}
	}
}