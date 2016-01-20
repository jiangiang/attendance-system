<?php
class Attendance extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this -> load -> model('model_attendance');
	}

	public function index() {
		show_404();
	}

	public function student() {
		// Student attendance Overview
		if ($this -> session -> userdata('logged_in')) {
			$data['title'] = "Student Attendance";

			$slot_date = $this -> input -> post('slot_date');
			$schedule_id = $this -> input -> post('schedule_id');

			if (!isset($slot_date)) {
				$currDate = date('Y-m-d');
				$currDay = date('N');
			} else {
				$currDate = date('Y-m-d', strtotime($slot_date));
				$currDay = date('N', strtotime($slot_date));
			}

			if (!isset($schedule_id)) {
				$currTime = date('H') . ":00:00";
				$next_slot_time = $this -> model_attendance -> get_next_slot_time($currDay, $currTime, NULL);
			} else {
				$next_slot_time = $this -> model_attendance -> get_next_slot_time($currDay, NULL, $schedule_id);
			}

			$data['currDate'] = $currDate;
			$data['schedule_id'] = $next_slot_time['schedule_id'];
			$data['next_slot_time'] = $next_slot_time['slot_time'];

			$data['slot_time_list'] = $this -> model_attendance -> get_slot_time($currDay);
			$data['students_attendance'] = $this -> model_attendance -> std_attendance_list($next_slot_time['slot_time'], $currDay, $currDate);

			$data['content'] = "attendance/attendance_student";
			$this -> load -> view('templates/main', $data);
			
		} else {
			redirect('login', 'refresh');
		}
	}

	public function std_attendanceUpdate() {
		// Student attendance update
		echo $this -> model_attendance -> std_attendanceUpdate();
	}

	public function std_attendanceReplaceUpdate() {
		// Student attendance update (replacement)
		echo $this -> model_attendance -> std_attendanceReplaceUpdate();
	}

	public function std_attendanceOverdueUpdate() {
		// Student attendance update (replacement)
		echo $this -> model_attendance -> std_attendanceOverdueUpdate();
	}

	public function searchName($searchText) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_attendance -> search_name($searchText);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function get_slotTime($currDate) {
		if ($this -> session -> userdata('logged_in')) {
			$currDay = date('N', strtotime($currDate));
			echo json_encode($this -> model_attendance -> get_slot_time($currDay));
		}
	}

	//---------------------------------------------------------------
	//----------------- Staff attendance ----------------------------
	//---------------------------------------------------------------
	public function staff() {
		// Student attendance Overview
		if ($this -> session -> userdata('logged_in')) {
			$data['title'] = "Staff Attendance Dashboard";

			$month = $this -> input -> post('month');
			$name = $this -> input -> post('name');
			$payoutStatus = $this -> input -> post('payoutStatus');
			$selectedYear = $this -> input -> post('searchYear');

			$data['name'] = "";
			$data['payoutStatus'] = "";

			if (isset($month) && $month != "") {
				$data['month'] = $month;
			} else {
				$data['month'] = date('n');
			}
			if (isset($selectedYear) && $selectedYear != "") {
				$data['selectedYear'] = $selectedYear;
			} else {
				$data['selectedYear'] = date('Y');
			}
			if (isset($name) && $name != "") {
				$data['name'] = $name;
			}
			if (isset($payoutStatus) && $payoutStatus != "") {
				$data['payoutStatus'] = $payoutStatus;
			} else {
				$data['payoutStatus'] = '';
			}

			$data['years'] = $this -> model_attendance -> staffAttendance_getYear();
			$data['staff_attendance'] = $this -> model_attendance -> staffAttendanceDashboard();
			
			$data['content'] = "attendance/attendance_staff";
			$this -> load -> view('templates/main', $data);
		
		} else {
			redirect('login', 'refresh');
		}
	}

	public function staff_AttendanceNew() {
		$sessionData = $this -> session -> userdata('logged_in');
		if ($sessionData && ($sessionData['type'] == 1 || $sessionData['type'] == 2)) {
			echo $this -> model_attendance -> staffAttendanceNew();
		}
	}

	public function staff_AttendanceVoid() {
		$sessionData = $this -> session -> userdata('logged_in');
		if ($sessionData && ($sessionData['type'] == 1 || $sessionData['type'] == 2)) {
			echo $this -> model_attendance -> staffAttendanceVoid();
		}
	}

	public function search_nameStaff($searchText) {
		$sessionData = $this -> session -> userdata('logged_in');
		if ($sessionData && ($sessionData['type'] == 1 || $sessionData['type'] == 2)) {
			echo $this -> model_attendance -> staffNameSearch($searchText);
		}
	}

}
