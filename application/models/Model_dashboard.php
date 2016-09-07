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
				FROM student_list s
				LEFT JOIN class_schedule cs ON cs.class_id = s.schedule_id
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN class_level l on l.level_id = c.level_id
				LEFT JOIN student_attendance a ON a.student_id = s.sid AND a.bill_id IS NULL
				WHERE s.IsActive=1
				GROUP BY student_id
					
				;";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
		}
	}

	public function tuitionFee() {
		$year = $this -> input -> post('searchYear');

		$whereClause = "";
		if (isset($year) && $year != "") {
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		} else {
			$year = date(1);
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		}

		$sql = "SELECT  DATE_FORMAT(issue_date, '%m') as month, 
						DATE_FORMAT(issue_date, '%Y') as year,
		        		sum(price) as price
				FROM student_bill b
				JOIN class_package p ON p.package_id = b.package_id
				WHERE " . $whereClause . " b.IsValid = 1
				GROUP BY YEAR(issue_date), MONTH(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
		}
	}

	public function tuitionFee_numberPayment() {
		$year = $this -> input -> post('searchYear');

		$whereClause = "";
		if (isset($year) && $year != "") {
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		} else {
			$year = date(1);
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		}

		$sql = "SELECT  DATE_FORMAT(issue_date, '%m') as month, 
						DATE_FORMAT(issue_date, '%Y') as year,
		        		count(price) as numberPayment
				FROM student_bill b
				JOIN class_package p ON p.package_id = b.package_id
				WHERE " . $whereClause . " b.IsValid=1
				GROUP BY YEAR(issue_date), MONTH(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
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
			return "Database Error";
		}
	}

	// Dashboard for company
	public function companyProfit() {
		$year = $this -> input -> post('searchYear');

		$whereClause = "";
		if (isset($year) && $year != "") {
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		} else {
			$year = date(1);
			$whereClause = $whereClause . "(issue_date >= '" . $year . "-01-01' AND issue_date < '" . ($year + 1) . "-01-01') AND ";
		}

		$sql = "SELECT  DATE_FORMAT(issue_date, '%m') as month, 
						DATE_FORMAT(issue_date, '%Y') as year,
		        		sum(price*(commission)/100) as commission,
		        		sum(price*(100-commission)/100) as profit,
		        		sum(price) as revenue,
		        		ifnull(salary,0) as salary
				FROM student_bill b
				LEFT JOIN class_package p ON p.package_id = b.package_id
				LEFT JOIN (
					SELECT sum(salary_basic + adjustment) as salary, DATE_FORMAT(salary_date, '%m') as month, DATE_FORMAT(salary_date, '%Y') as year
					FROM employee_salary
					WHERE (salary_date >= '" . $year . "-01-01' AND salary_date < '" . ($year + 1) . "-01-01')
					GROUP BY YEAR(salary_date), MONTH(salary_date)
					) s ON month = DATE_FORMAT(issue_date, '%m') AND year = DATE_FORMAT(issue_date, '%Y')
				WHERE " . $whereClause . " b.IsValid=1
				GROUP BY YEAR(issue_date), MONTH(issue_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
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
			return "Database Error";
		}
	}

	// Employee Payroll
	public function employee_payroll_dashboard() {

		$year = $this -> input -> post('searchYear');
		$month = $this -> input -> post('searchMonth');
		$name = $this -> input -> post('name');
		$payoutStatus = $this -> input -> post('payout_status');

		if (!isset($month) || $month == "") {
			$month = date(2);
		}
		if (!isset($year) || $year == "") {
			$year = date(1);
		}
		$whereClause = "";
		if (isset($name) && $name != "") {
			$whereClause = $whereClause . " AND e.name like '" . $name . "'";
		}
		if (isset($payoutStatus) && $payoutStatus != "") {
			$whereClause = $whereClause . " AND IsTaken = '" . $payoutStatus . "'";
		}

		$sql = "SELECT salary_id, i.id, i.name, i.identity, s.salary_basic, adjustment, (salary_basic + adjustment) as salary, IsTaken, session_count, DATE_FORMAT(salary_date, '%m/%Y') as month, remark
				FROM employee_salary s
				JOIN employee_list i ON s.employee_id = i.id
				WHERE salary_date >= '" . $year . "-" . $month . "-01' AND salary_date < '" . $year . "-" . $month . "-31' " . $whereClause . "
				";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
		}
	}

	public function salary_take() {
		$salary_id = $this -> input -> post('salaryID');
		$sql = "UPDATE employee_salary SET taken = 1 WHERE salary_id = " . $salary_id;
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
			//$error = $this -> db -> error();
			return "Database Error";
		}
	}

	// Attendance Dashboard

	public function student_attendance_dasheboard($year, $month, $day, $time_slot) {
		$sql = "SELECT s.*, issue_date FROM student_list s
				LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN class_list c ON cs.class_id = c.id
				LEFT JOIN
					(SELECT 
						* 
					FROM 
						student_bill 
					WHERE 
						issue_date BETWEEN '" . $year . "-" . $month . "-01' AND '" . $year . "-" . $month . "-31' 
					GROUP BY 
						student_id 
					ORDER BY 
						issue_date DESC) as b ON b.student_id = s.sid
				WHERE slot_time = '" . $time_slot . "' AND slot_day = " . $day . " AND s.IsActive = 1";
		$query = $this -> db -> query($sql);
		$student_list = $query -> result_array();
		$attendance_array = array();

		foreach ($student_list as $student) {
			$student_id = $student['sid'];
			$student_name = $student['student_name'];
			$sql = "SELECT * FROM student_attendance a
					LEFT JOIN student_list s ON s.sid = a.student_id
					WHERE a.attend_date BETWEEN '" . $year . "-" . $month . "-01' AND '" . $year . "-" . $month . "-31' AND student_id=" . $student_id . " AND void=2 AND IsTaken=1
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
			$array['IsTaken'] = array();
			foreach ($attendance_list as $attendance) {
				$array['attendance_date'][] = $attendance['attend_date'];
				$array['replacement'][] = $attendance['replacement'];
				$array['IsTaken'][] = $attendance['IsTaken'];
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
			return "Database Error";
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
			return "Database Error";
		}
	}

	public function studentAtt_getSlotTime($day) {
		$sql = "SELECT * FROM class_schedule WHERE slot_day = " . $day;

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
		}
	}

	public function get_all_venue() {
		$sql = "select venue_id, venue_name FROM school_details";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
		}
	}

	public function timetable_instructor($venue_id) {
		
		$sql = "SELECT cs.slot_day, cs.slot_time, duration_minute, TIME( DATE_ADD( concat( '2000-01-01 ', cs.slot_time ) , INTERVAL duration_minute MINUTE ) ) AS end_slot_time, group_concat( e.short_name ) AS instructors_name
				FROM class_schedule cs
				LEFT JOIN class_list c ON c.id= cs.class_id
				LEFT JOIN employee_list e ON e.id = c.instructor_id
				LEFT JOIN class_level cl ON cl.level_id = c.level_id
				LEFT JOIN school_details vc ON vc.venue_id = c.venue_id
				WHERE vc.venue_id = " . $venue_id . "
				GROUP BY cs.slot_day, end_slot_time, cs.slot_time";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
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
				FROM class_schedule cs
				LEFT JOIN class_list  c ON c.id = cs.class_id
				LEFT JOIN employee_list e ON e.id = c.instructor_id
				LEFT JOIN (SELECT count(s.Sid)as headcount, schedule_id  FROM student_list s WHERE s.IsActive = 1 GROUP BY schedule_id) s ON s.schedule_id = cs.schedule_id
				LEFT JOIN class_level cl ON cl.level_id = c.level_id
				LEFT JOIN school_details vc ON vc.venue_id = c.venue_id
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
				FROM class_schedule cs
				LEFT JOIN class_list  c ON c.id = cs.class_id
				LEFT JOIN employee_list e ON e.id = c.instructor_id
				LEFT JOIN (SELECT count(s.Sid)as headcount, schedule_id  FROM student_list s WHERE s.IsActive = 1 GROUP BY schedule_id) s ON s.schedule_id = cs.schedule_id
				LEFT JOIN class_level cl ON cl.level_id = c.level_id
				LEFT JOIN school_details vc ON vc.venue_id = c.venue_id
				WHERE vc.venue_id = " . $venue_id . "
				GROUP BY cs.slot_day, end_slot_time ,cl.level_id, cs.slot_time
				";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			return "Database Error";
		}
	}

}
