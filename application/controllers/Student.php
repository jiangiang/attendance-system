<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
class Student extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this -> load -> model('model_student');
	}

	public function index() {
		show_404();
	}

	public function student_home() {
		if ($this -> session -> userdata('logged_in')) {

			$time = $this -> input -> post('searchTime');
			$day = $this -> input -> post('searchDay');
			$name = $this -> input -> post('searchName');

			$data['day'] = "";
			$data['name'] = "";
			$data['time'] = "";

			if (isset($time) && $time != "") {
				$data['time'] = $time;
			}
			if (isset($day) && $day != "") {
				$data['day'] = $day;
			}
			if (isset($name) && $name != "") {
				$data['name'] = $name;
			}

			$data['title'] = "Student Summary";
			$data['std_active_rows'] = $this -> model_student -> list_student();
			$data['std_inactive_rows'] = $this -> model_student -> show_student_inactive();
			$data['course_level_rows'] = $this -> model_student -> get_course_level();
			$data['venue_code_rows'] = $this -> model_student -> get_venue_code();

			$data['content'] = "student/student_home";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function details($uid) {
		if ($this -> session -> userdata('logged_in')) {

			$data['title'] = "Student Summary";
			$data['student_details'] = $this -> model_student -> get_student_details($uid);
			$data['student_details_payment'] = $this -> model_student -> get_student_details_course($uid);

			$data['content'] = "student/details";
			$this -> load -> view('templates/main_lite', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function payment_history($uid) {
		if ($this -> session -> userdata('logged_in')) {

			$data['title'] = "Payment History";
			$data['payment_history'] = $this -> model_student -> get_student_payment_details($uid);

			$data['content'] = "student/payment_history";
			$this -> load -> view('templates/main_lite', $data);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function attendance_history($uid) {
		if ($this -> session -> userdata('logged_in')) {

			$data['title'] = "Attendance History";
			$data['payment_history'] = $this -> model_student -> get_student_attendance_history($uid);

			$data['content'] = "student/attendance_history";
			$this -> load -> view('templates/main_lite', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function ajax_slot_capacity($slot_day, $level, $venue_id) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> ajax_slot_capacity($slot_day, $level, $venue_id);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function ajax_student_details($std_id) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> ajax_student_details($std_id);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function stdNew() {
		// Create New student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> student_new();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function stdUpdate() {
		// Update student Info
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> student_update();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function stdDeactivate() {
		// Deactivate Student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> student_deactivate();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function stdActivate() {
		// Deactivate Student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> student_activate();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function checkID($ID) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> checkID($ID);
		} else {
			redirect('login', 'refresh');
		}
	}

	// ===========================
	// Student Log
	// ===========================
	public function student_log() {
		if ($this -> session -> userdata('logged_in')) {

			$name = $this -> input -> post('searchName');

			$data['name'] = "";

			if (isset($name) && $name != "") {
				$data['name'] = $name;
			}

			$data['title'] = "Student Log";
			$data['student_logs'] = $this -> model_student -> list_student_log();

			$data['content'] = "student/student_log";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function searchName($searchText) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> search_name($searchText);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function student_log_new() {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> student_log_new();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function student_log_void() {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_student -> student_log_void();
		} else {
			redirect('login', 'refresh');
		}
	}

}
