<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
class Course extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this -> load -> model('model_course');
	}

	public function index() {
		show_404();
	}

	public function course_home($day = null) {
		if ($this -> session -> userdata('logged_in')) {
			if (is_null($day)) {
				$url = "Course/course_home/1";
				redirect($url, 'refresh');
			}
			$data['day_selected'] = $day;
			$data['title'] = "Course Info Summary";
			$data['course_active_rows'] = $this -> model_course -> show_course_active($day);
			$data['get_course_type'] = $this -> model_course -> get_course_level();
			$data['venue_code_rows'] = $this -> model_course -> get_venue_code();
			$data['instructor_list'] = $this -> model_course -> get_instructor_list();

			$data['content'] = "course/course_home";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function category() {
		if ($this -> session -> userdata('logged_in')) {

			$data['title'] = "Course Category Summary";
			$data['category_row'] = $this -> model_course -> show_category();

			$data['content'] = "course/category";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function getCourseInfo($course_id) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> get_course_info($course_id);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function courseNew() {
		// Create New student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> course_new();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function courseUpdate() {
		// Update student Info
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> course_update();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function courseDeactivate() {
		// Deactivate Student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> course_deactivate();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function categoryCreate() {
		// Create New student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> category_create();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function categoryUpdate() {
		// Update student Info
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> category_update();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function categoryDeactivate() {
		// Deactivate Student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> category_deactivate();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function getCategoryInfo($cat_id) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> get_course_level_info($cat_id);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function checkInstructor($staff_id, $schedule_id) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> check_instructor($staff_id, $schedule_id);
		}
	}

	public function get_schedule($slot_day) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> get_schedule($slot_day);
		}
	}

	// Venue Matters
	public function venue() {
		if ($this -> session -> userdata('logged_in')) {

			$data['title'] = "Course Venue Summary";
			$data['result_rows'] = $this -> model_course -> show_venue();
			
			$data['content'] = "course/venue";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function venueCreate() {
		// Create New student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> venue_create();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function venueUpdate() {
		// Update student Info
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> venue_update();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function venueDeactivate() {
		// Deactivate Student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> venue_deactivate();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function getVenueInfo($id) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_course -> get_venue_info($id);
		} else {
			redirect('login', 'refresh');
		}
	}

}
