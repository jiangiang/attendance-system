<?php
class Finance extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this -> load -> model('model_finance');
		$this -> load -> model('model_attendance');
	}

	public function index() {
		show_404();
	}

	public function tuitionFeeDashboard() {
		if ($this -> session -> userdata('logged_in')) {

			$month = $this -> input -> post('searchMonth');
			$name = $this -> input -> post('searchName');
			$billType = $this -> input -> post('billType');

			$data['month'] = "";
			$data['name'] = "";
			$data['proccessed'] = "";

			if (isset($month) && $month != "") {
				$data['month'] = $month;
			} else {
				$data['month'] = date('n');
			}
			if (isset($name) && $name != "") {
				$data['name'] = $name;
			}
			if (isset($billType) && $billType != "") {
				$data['proccessed'] = $billType;
			}

			$data['title'] = "Student Payment Dashboard";

			$data['bill_record_rows'] = $this -> model_finance -> tuitionFeeDashboard();
			$data['package_rows'] = $this -> model_finance -> get_package();

			$data['content'] = "finance/receivable/tuitionFee";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function searchName($searchText) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_finance -> search_name($searchText);
		} else {
			redirect('login', 'refresh');
		}
	}

	public function billDetails($billID) {
		if ($this -> session -> userdata('logged_in')) {
			if ($billID != "") {
				echo $this -> model_finance -> getBillInfo($billID);
			}
		} else {
			redirect('login', 'refresh');
		}
	}

	public function tuitionFeeReceive() {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_finance -> tuitionFeeReceive();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function tuitionFeeUpdate() {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_finance -> tuitionFeeUpdate();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function voidBill() {
		// Deactivate Student
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_finance -> voidBill();
		} else {
			redirect('login', 'refresh');
		}
	}

	public function custom_date_slots($slot_number) {
		if ($this -> session -> userdata('logged_in')) {
			$tempStr = "";
			$k = 0;
			$tempStr .= "<div class=\"custom_date_div\">";
			for ($i = 0; $i < ceil($slot_number / 3); $i++) {
				for ($j = 0; $j < 3; $j++) {
					//$tempStr .= "<div class=\"row\">";
					if ($k < $slot_number) {
						if ($j == 0) {
							$tempStr .= "<div class=\"col-md-2 custom_slot_date\" style=\"padding-left: 0px\">";
						} else {
							$tempStr .= "<div class=\"col-md-2 custom_slot_date\">";
						}
						$tempStr .= "<input id=\"slot_date\" name=\"slot_date[]\" class=\"form-control slot_date\" placeholder\"Select a date\" required type=\"text\";>";
						$tempStr .= "</div>";
						$tempStr .= "<div class=\"col-md-2 custom_slot_time\">";
						$tempStr .= "<select id=\"slot_time\" name=\"slot_time[]\" required class=\"form-control slot_time\">";
						$tempStr .= "<option selected disabled> </option>";
						$tempStr .= "</select>";
						$tempStr .= "</div>";
						$k++;
					} else {
						$tempStr .= "<div class=\"col-md-2 custom_slot_date \"></div>";
					}
					//$tempStr .= "</div>";
				}
			}
			$tempStr .= "</div>";
			$return_json['str'] = $tempStr;
			$return_json['debug'] = $slot_number;
			echo json_encode($return_json);
		}
	}

	public function package_check($package_id) {
		if ($this -> session -> userdata('logged_in')) {
			echo json_encode($this -> model_finance -> package_check($package_id));
		}
	}

	public function get_slotTime($currDate) {
		if ($this -> session -> userdata('logged_in')) {
			$currDay = date('N', strtotime($currDate));
			echo json_encode($this -> model_attendance -> get_slot_time($currDay));
		}
	}

	// ==============================
	// staff payroll
	// ==============================
	public function employee_payroll() {
		$sessionData = $this -> session -> userdata('logged_in');
		if ($sessionData && ($sessionData['type'] == 1 || $sessionData['type'] == 2)) {
			$data['title'] = "Staff Payroll";

			$year = $this -> input -> post('searchYear');
			$month = $this -> input -> post('month');
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
				$data['payout_status'] = 'N';
			}
			if (isset($salaryStatus) && $salaryStatus != "") {
				$data['salary_status'] = $salaryStatus;
			} else {
				$data['salary_status'] = 'N';
			}

			$data['payroll_records'] = $this -> model_finance -> employee_payroll();
			// $data['get_years'] = $this -> model_finance -> get_year();
			$data['content'] = "finance/payable/employee_payroll";
			$this -> load -> view('templates/main', $data);

		} else {
			redirect('login', 'refresh');
		}
	}

	public function employee_details($uid, $year, $month) {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_finance -> employee_details($uid, $year, $month);
		}
	}

	public function employee_salary_update() {
		if ($this -> session -> userdata('logged_in')) {
			echo $this -> model_finance -> employee_payroll_update();
		}
	}

}
