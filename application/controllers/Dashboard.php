<?php

class Dashboard extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this -> load -> model('model_dashboard');
	}

	public function index() {
		show_404();
	}

	public function student_overdue() {
		// Student attendance Overview
		if ($this -> session -> userdata('logged_in')) {
			$data['title'] = "Student Overdue";

			$data['students_attendance'] = $this -> model_dashboard -> student_overdue();
			$data['content'] = "dashboard/student_overdue";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function student_attendance() {

		if ($this -> session -> userdata('logged_in')) {

			$data['title'] = "Student Attendance Dashboard";

			$slot_time = $this -> input -> post('slot_time');
			$year = $this -> input -> post('searchYear');
			$month = $this -> input -> post('searchMonth');
			$day = $this -> input -> post('searchDay');

			if (isset($month) && $month != "") {
				$data['selectedMonth'] = $month;
			} else {
				$data['selectedMonth'] = date('n');
			}

			if (isset($year) && $year != "") {
				$data['selectedYear'] = $year;
			} else {
				$data['selectedYear'] = date('Y');
			}

			if (isset($day) && $day != "") {
				$data['selectedDay'] = $day;
			} else {
				$data['selectedDay'] = date('N');
			}

			$data['list_slot_time'] = $this -> model_dashboard -> studentAtt_getSlotTime($data['selectedDay']);
			$data['getYear'] = $this -> model_dashboard -> studentAtt_getYear();
			$data['getMonth'] = $this -> model_dashboard -> studentAtt_getMonth();

			if (isset($slot_time) && $slot_time != "") {
				$data['selectedSlot'] = $slot_time;
			} else {
				if (count($data['list_slot_time']) > 0) {
					$data['selectedSlot'] = $data['list_slot_time'][0]['slot_time'];
				} else {
					$data['selectedSlot'] = "";
				}
			}

			$data['students_attendance_list'] = $this -> model_dashboard -> student_attendance_dasheboard($data['selectedYear'], $data['selectedMonth'], $data['selectedDay'], $data['selectedSlot']);
			$data['dates'] = $this -> getAllDates($data['selectedYear'], $data['selectedMonth'], $data['selectedDay']);

			$data['content'] = "dashboard/student_attendance";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function getAllDates($year, $month, $day) {
		switch($day) {
			case 1 :
				$dayInString = "Monday";
				break;
			case 2 :
				$dayInString = "Tuesday";
				break;
			case 3 :
				$dayInString = "Wednesday";
				break;
			case 4 :
				$dayInString = "Thursday";
				break;
			case 5 :
				$dayInString = "Friday";
				break;
			case 6 :
				$dayInString = "Saturday";
				break;
			case 7 :
				$dayInString = "Sunday";
				break;
			default :
				echo "getAllDateProblem";
				return;
		}

		$base_date = strtotime($year . '-' . $month . '-01');
		$date = strtotime('first ' . $dayInString . ' of ' . date('F Y', $base_date));

		$dates = array();

		do {
			$dates[] = new DateTime(date('r', $date));
			$date = strtotime('+7 days', $date);
		} while (date('m', $date) == $month);

		return $dates;
	}

	public function tuitionFee() {

		if ($this -> session -> userdata('logged_in')) {
			$data['title'] = "Tuition Fee Report";
			$month = $this -> input -> post('searchMonth');
			if (isset($month) && $month != "") {
				$data['month'] = $month;
			} else {
				$data['month'] = date('n');
			}

			$data['getYear'] = $this -> model_dashboard -> tuitionFee_getYear();
			$data['rows'] = $this -> model_dashboard -> tuitionFee();
			$data['rows2'] = $this -> model_dashboard -> tuitionFee_numberPayment();

			$data['content'] = "dashboard/tuitionFee";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function companyProfit() {

		if ($this -> session -> userdata('logged_in')) {
			$data['title'] = "Company Profit Report";
			$month = $this -> input -> post('searchMonth');
			if (isset($month) && $month != "") {
				$data['month'] = $month;
			} else {
				$data['month'] = date('n');
			}

			$data['getYear'] = $this -> model_dashboard -> companyProfit_getYear();
			$data['rows'] = $this -> model_dashboard -> companyProfit();

			$data['content'] = "dashboard/companyProfit";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function timetable_instructor() {

		if ($this -> session -> userdata('logged_in')) {

			$venue_id = $this -> input -> post('venue_id');
			if (is_null($venue_id) || !isset($venue_id)) {
				$venue_id = 1;
			}

			$data['title'] = "Intructor Timetable";
			$data['timetable'] = $this -> model_dashboard -> timetable_instructor($venue_id);
			$data['venues'] = $this -> model_dashboard -> get_all_venue();
			$data['venue_id'] = $venue_id;

			$data['content'] = "dashboard/timetable_instructor";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function timetable_classes() {

		if ($this -> session -> userdata('logged_in')) {
			$venue_id = $this -> input -> post('venue_id');
			if (is_null($venue_id) || !isset($venue_id)) {
				$venue_id = 1;
			}
			$data['title'] = "Class Timetable";
			$data['timetable'] = $this -> model_dashboard -> timetable_class($venue_id);
			$data['venues'] = $this -> model_dashboard -> get_all_venue();
			$data['venue_id'] = $venue_id;

			$data['content'] = "dashboard/timetable_class";
			$this -> load -> view('templates/main', $data);
			
		} else {
			redirect('login', 'refresh');
		}
	}

	public function employee_payroll() {
		$sessionData = $this -> session -> userdata('logged_in');
		if ($sessionData && ($sessionData['type'] == 1 || $sessionData['type'] == 2)) {
			$data['title'] = "Staff Payroll Dashboard";

			$year = $this -> input -> post('searchYear');
			$month = $this -> input -> post('searchMonth');
			$name = $this -> input -> post('name');
			$payoutStatus = $this -> input -> post('payout_status');
			$salaryStatus = $this -> input -> post('salary_status');

			$data['name'] = "";
			$data['payoutStatus'] = "";

			if (isset($month) && $month != "") {
				$data['month'] = $month;
			} else {
				$data['month'] = date('n');
			}
			if (isset($year) && $year != "") {
				$data['selectedYear'] = $year;
			} else {
				$data['selectedYear'] = date('Y');
			}
			if (isset($name) && $name != "") {
				$data['name'] = $name;
			}
			if (isset($payoutStatus) && $payoutStatus != "") {
				$data['payout_status'] = $payoutStatus;
			} else {
				$data['payout_status'] = "";
			}

			$data['payroll_records'] = $this -> model_dashboard -> employee_payroll_dashboard();
			$data['payroll_get_years'] = $this -> model_dashboard -> payroll_get_year();
			
			$data['content'] = "dashboard/employee_payroll";
			$this -> load -> view('templates/main', $data);
			
		} else {
			redirect('login', 'refresh');
		}
	}

	public function salary_take() {
		$sessionData = $this -> session -> userdata('logged_in');
		if ($sessionData && ($sessionData['type'] == 1 || $sessionData['type'] == 2)) {
			echo $this -> model_dashboard -> salary_take();
		} else {
			redirect('login', 'refresh');
		}
	}

}
