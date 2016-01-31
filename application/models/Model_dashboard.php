<?php
class Model_dashboard extends CI_Model {
	public function __construct() {
		$this -> load -> database();
	}

	public function student_overdue() {
		$sql = "SELECT 
					s.sid, s.student_name, student_identity, cs.slot_day, cs.slot_time,
					l.level_name, 
				    count(bill_id) as overdue_count
				FROM student_info s
				LEFT JOIN course_schedule cs ON cs.course_id = s.schedule_id
				LEFT JOIN course_info c ON c.id = cs.course_id
				LEFT JOIN course_level l on l.level_id = c.level_id
				LEFT JOIN student_attendance a ON a.student_id = s.sid AND a.bill_id IS NULL
				WHERE s.student_status='A'
				GROUP BY student_id
					
				;";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function tuitionFee() {
		$year = $this -> input -> post('searchYear');

		$whereClause = "";
		if (isset($year) && $year != "") {
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		} else {
			$year = date('Y');
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		}

		$sql = "SELECT  DATE_FORMAT(issue_date, '%m') as month, 
						DATE_FORMAT(issue_date, '%Y') as year,
		        		sum(price) as price
				FROM student_bill b
				JOIN course_package p ON p.package_id = b.package_id
				WHERE " . $whereClause . " b.bill_status='A'
				GROUP BY YEAR(issue_date), MONTH(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function tuitionFee_numberPayment() {
		$year = $this -> input -> post('searchYear');

		$whereClause = "";
		if (isset($year) && $year != "") {
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		} else {
			$year = date('Y');
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		}

		$sql = "SELECT  DATE_FORMAT(issue_date, '%m') as month, 
						DATE_FORMAT(issue_date, '%Y') as year,
		        		count(price) as numberPayment
				FROM student_bill b
				JOIN course_package p ON p.package_id = b.package_id
				WHERE " . $whereClause . " b.bill_status='A'
				GROUP BY YEAR(issue_date), MONTH(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function tuitionFee_getYear() {
		$sql = "SELECT DATE_FORMAT(issue_date, '%Y') as year
				FROM student_bill b
				GROUP BY YEAR(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// Dashboard for company
	public function companyProfit() {
		$year = $this -> input -> post('searchYear');

		$whereClause = "";
		if (isset($year) && $year != "") {
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		} else {
			$year = date('Y');
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		}

		$sql = "SELECT  DATE_FORMAT(issue_date, '%m') as month, 
						DATE_FORMAT(issue_date, '%Y') as year,
		        		sum(price*(commission)/100) as commission,
		        		sum(price*(100-commission)/100) as profit,
		        		sum(price) as revenue,
		        		ifnull(salary,0) as salary
				FROM student_bill b
				LEFT JOIN course_package p ON p.package_id = b.package_id
				LEFT JOIN (
					SELECT sum(salary_basic + adjustment) as salary, DATE_FORMAT(salary_date, '%m') as month, DATE_FORMAT(salary_date, '%Y') as year
					FROM employee_salary
					WHERE (salary_date >= '" . $year . "-01-01' AND salary_date < '" . ($year + 1) . "-01-01')
					GROUP BY YEAR(salary_date), MONTH(salary_date)
					) s ON month = DATE_FORMAT(issue_date, '%m') AND year = DATE_FORMAT(issue_date, '%Y')
				WHERE " . $whereClause . " b.bill_status='A'
				GROUP BY YEAR(issue_date), MONTH(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function companyProfit_getYear() {
		$sql = "SELECT DATE_FORMAT(issue_date, '%Y') as year
				FROM student_bill b
				GROUP BY YEAR(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// Employee Payroll
	public function employee_payroll_dashboard() {

		$year = $this -> input -> post('searchYear');
		$month = $this -> input -> post('searchMonth');
		$name = $this -> input -> post('name');
		$payoutStatus = $this -> input -> post('payout_status');

		if (!isset($month) || $month == "") {
			$month = date('n');
		}
		if (!isset($year) || $year == "") {
			$year = date('Y');
		}
		$whereClause = "";
		if (isset($name) && $name != "") {
			$whereClause = $whereClause . " AND e.name like '" . $name . "'";
		}
		if (isset($payoutStatus) && $payoutStatus != "") {
			$whereClause = $whereClause . " AND taken = '" . $payoutStatus . "'";
		}

		$sql = "SELECT salary_id, i.id, i.name, i.identity, s.salary_basic, adjustment, (salary_basic + adjustment) as salary, taken, session_count, DATE_FORMAT(salary_date, '%m/%Y') as month, remark
				FROM employee_salary s
				JOIN employee_info i ON s.employee_id = i.id
				WHERE salary_date >= '" . $year . "-" . $month . "-01' AND salary_date < '" . $year . "-" . $month . "-31' " . $whereClause . "
				";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function salary_take() {
		$salary_id = $this -> input -> post('salaryID');
		$sql = "UPDATE employee_salary SET taken='Y' WHERE salary_id = " . $salary_id;
		if ($this -> db -> query($sql)) {
			$data['error'] = false;
			$data['message'] = "success";
		} else {
			$data['error'] = true;
			$data['message'] = "something wong";
		}

		return json_encode($data);
	}

	public function payroll_get_year() {
		$sql = "SELECT DATE_FORMAT(salary_date, '%Y') as year FROM employee_salary GROUP BY DATE_FORMAT(salary_date, '%Y')";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// Attendance Dashboard

	public function student_attendance_dasheboard($year, $month, $day, $time_slot) {
		$sql = "SELECT s.*, issue_date FROM student_info s
				LEFT JOIN course_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN course_info c ON cs.course_id = c.id
				LEFT JOIN
					(SELECT 
						* 
					FROM 
						student_bill 
					WHERE 
						issue_date BETWEEN '" . $year . "-" . $month . "-01' AND '" . $year . "-" . $month . "-31' 
					GROUP BY 
						std_id 
					ORDER BY 
						issue_date DESC) as b ON b.std_id = s.sid
				WHERE slot_time='" . $time_slot . "' AND slot_day = " . $day . " AND course_status='A'";
		$query = $this -> db -> query($sql);
		$student_list = $query -> result_array();
		$attendance_array = array();

		foreach ($student_list as $student) {
			$student_id = $student['sid'];
			$student_name = $student['student_name'];
			$sql = "SELECT * FROM student_attendance a
					LEFT JOIN student_info s ON s.sid = a.student_id
					WHERE a.attend_date BETWEEN '" . $year . "-" . $month . "-01' AND '" . $year . "-" . $month . "-31' AND student_id=" . $student_id . " AND void='N' AND attendance_status='Y'
					ORDER BY attend_date
					";
			$query = $this -> db -> query($sql);
			$attendance_list = $query -> result_array();

			$array = array();
			$array['student_name'] = $student_name;
			$array['student_id'] = $student_id;
			$array['latest_payment_date'] = $student['issue_date'];

			$array['attendance_date'] = array();
			$array['replacement'] = array();
			$array['attendance_status'] = array();
			foreach ($attendance_list as $attendance) {
				$array['attendance_date'][] = $attendance['attend_date'];
				$array['replacement'][] = $attendance['replacement'];
				$array['attendance_status'][] = $attendance['attendance_status'];
			}
			$attendance_array[] = $array;
		}

		// print_r($attendance_array);
		return $attendance_array;

	}

	public function studentAtt_getYear() {
		$sql = "SELECT DATE_FORMAT(attend_date, '%Y') as year
				FROM student_attendance b
				GROUP BY YEAR(attend_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function studentAtt_getMonth() {
		$sql = "SELECT DATE_FORMAT(attend_date, '%m') as month
				FROM student_attendance b
				GROUP BY MONTH(attend_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function studentAtt_getSlotTime($day) {
		$sql = "SELECT * FROM course_schedule WHERE slot_day = " . $day;

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function get_all_venue() {
		$sql = "select venue_id, venue_name FROM venue_code";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function timetable_instructor($venue_id) {
		
		$sql = "SELECT cs.slot_day, cs.slot_time, duration_minute, TIME( DATE_ADD( concat( '2000-01-01 ', cs.slot_time ) , INTERVAL duration_minute MINUTE ) ) AS end_slot_time, group_concat( e.short_name ) AS instructors_name
				FROM course_schedule cs
				LEFT JOIN course_info c ON c.id= cs.course_id
				LEFT JOIN employee_info e ON e.id = c.instructor_id
				LEFT JOIN course_level cl ON cl.level_id = c.level_id
				LEFT JOIN venue_code vc ON vc.venue_id = c.venue_id
				WHERE vc.venue_id = " . $venue_id . "
				GROUP BY cs.slot_day, end_slot_time, cs.slot_time";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function timetable_class($venue_id) {
		/*
		$sql = "SELECT
					cs.slot_day, cs.slot_time,
					TIME( DATE_ADD( concat( '2000-01-01 ', cs.slot_time ) , INTERVAL duration_minute MINUTE ) ) AS end_slot_time,
					group_concat( e.short_name ) AS instructors_name,
					cl.level_short_name,
					CONCAT(ifnull(sum(headcount),0), '/', sum(cl.max_capacity)) as headcount
				FROM course_schedule cs
				LEFT JOIN course_info  c ON c.id = cs.course_id
				LEFT JOIN employee_info e ON e.id = c.instructor_id
				LEFT JOIN (SELECT count(s.Sid)as headcount, schedule_id  FROM student_info s WHERE s.student_status = 'A' GROUP BY schedule_id) s ON s.schedule_id = cs.schedule_id
				LEFT JOIN course_level cl ON cl.level_id = c.level_id
				LEFT JOIN venue_code vc ON vc.venue_id = c.venue_id
				WHERE vc.venue_id = " . $venue_id . "
				GROUP BY cs.slot_day, end_slot_time ,cl.level_id, cs.slot_time
				";
	*/
		$sql = "SELECT
					cs.slot_day, cs.slot_time,
					slot_time_end AS end_slot_time,
					group_concat( e.short_name ) AS instructors_name,
					cl.level_short_name,
					CONCAT(ifnull(sum(headcount),0), '/', sum(cl.max_capacity)) as headcount
				FROM course_schedule cs
				LEFT JOIN course_info  c ON c.id = cs.course_id
				LEFT JOIN employee_info e ON e.id = c.instructor_id
				LEFT JOIN (SELECT count(s.Sid)as headcount, schedule_id  FROM student_info s WHERE s.student_status = 'A' GROUP BY schedule_id) s ON s.schedule_id = cs.schedule_id
				LEFT JOIN course_level cl ON cl.level_id = c.level_id
				LEFT JOIN venue_code vc ON vc.venue_id = c.venue_id
				WHERE vc.venue_id = " . $venue_id . "
				GROUP BY cs.slot_day, end_slot_time ,cl.level_id, cs.slot_time
				";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

}
