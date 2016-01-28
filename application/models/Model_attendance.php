<?php
class Model_attendance extends CI_Model {
	public function __construct() {
		$this -> load -> database();
	}

	//------------------------------------------------
	//------------Student Attendance------------------
	//------------------------------------------------
	public function std_attendance_list($slot_time, $slot_day, $slot_date) {

		// 1st Union - list with payment
		// 2nd Union - list without payment
		// 2nd Union - list all student at that particular time
		// 4th Union - list with replacement

		$sql = "SELECT
					s.id as student_id, s.std_name, lesson_left, lesson_overdue, slot_day, slot_time,
					l.log, e.short_name as instructor_name, cl.level_name, issue_date,
					a.attendance_status, a.replacement, a.id as attendance_id
				FROM student_info s
				LEFT JOIN course_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN (SELECT std_id, max(issue_date) issue_date FROM student_bill
							GROUP BY std_id ORDER BY issue_date DESC ) b ON b.std_id = s.id
				LEFT JOIN course_info c ON c.id = cs.course_id
				LEFT JOIN course_level cl ON cl.level_id = c.level_id
				LEFT JOIN employee_info e ON e.id = c.instructor_id
				LEFT JOIN student_attendance a ON a.student_id = s.id AND a.attend_date = '".$slot_date."'
				LEFT JOIN student_log l ON l.student_id = s.id
				LEFT JOIN ( SELECT student_id, COUNT(id) as lesson_left FROM student_attendance
							WHERE attendance_status='N' AND void='N' GROUP BY student_id ) al ON al.student_id = s.id
				LEFT JOIN ( SELECT student_id, COUNT(id) as lesson_overdue FROM student_attendance
							WHERE bill_id IS NULL AND void='N' GROUP BY student_id ) av ON av.student_id = s.id
				WHERE cs.slot_day = '".$slot_day."' AND cs.slot_time = '".$slot_time."' AND s.std_status = 'A'
				UNION
				SELECT
						s.id as student_id, s.std_name, lesson_left, lesson_overdue, slot_day, slot_time,
						l.log, e.short_name as instructor_name, cl.level_name, issue_date,
						a.attendance_status, a.replacement, a.id as attendance_id
				FROM student_attendance a
				LEFT JOIN  student_info s ON s.id = a.student_id
				LEFT JOIN (SELECT std_id, max(issue_date) issue_date FROM student_bill
							GROUP BY std_id ORDER BY issue_date DESC ) b ON b.std_id = s.id
				LEFT JOIN course_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN course_info c ON c.id = cs.course_id
				LEFT JOIN course_level cl ON cl.level_id = c.level_id
				LEFT JOIN employee_info e ON e.id = c.instructor_id
				LEFT JOIN student_log l ON l.student_id = s.id
				LEFT JOIN ( SELECT student_id, COUNT(id) as lesson_left FROM student_attendance
							WHERE attendance_status='N' AND void='N' GROUP BY student_id ) al ON al.student_id = s.id
				LEFT JOIN ( SELECT student_id, COUNT(id) as lesson_overdue FROM student_attendance
							WHERE bill_id IS NULL AND void='N' GROUP BY student_id ) av ON av.student_id = s.id
				WHERE attendance_status='Y' AND cs.slot_time = '".$slot_time."' AND a.attend_date = '".$slot_date."'AND replacement = 'Y'";

		if ($query = $this -> db -> query($sql)) {
			// echo $this->db->last_query();
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function search_name($searchText) {
		$searchText = urldecode($searchText);

		$sql = "SELECT
					s.id, s.std_name, std_identity, cs.slot_day, cs.slot_time,
					l.level_name, e.name as instructor, IFNULL(a.expiry_date, '-') as expiry_date, IFNULL(a.bill_id, '-') as bill_id,
					IFNULL(a.last_attend, '-') as last_attend,
				    IFNULL(attend_times,0) as attend_times, p.term,
				    CASE WHEN p.term IS NULL THEN (-1*IFNULL(attend_times, 0)) ELSE (p.term - IFNULL(attend_times*1,0)) END AS lesson_left,
				    log.*
				FROM student_info s
				LEFT JOIN course_info c ON c.id = s.course_id
				LEFT JOIN course_schedule cs ON cs.schedule_id = c.schedule_id
				LEFT JOIN course_level l on l.level_id = c.level_id
				LEFT JOIN employee_info e ON e.id = c.instructor_id
				LEFT JOIN (
					SELECT 
						count(id) AS attend_times, attend_date as last_attend, student_id, a.bill_id, expiry_date, package_id
					FROM 
						student_attendance a
					LEFT JOIN
						student_bill b ON b.bill_id = a.bill_id   
					WHERE
						attendance_status='Y' AND void='N'
					GROUP BY 
						student_id
					ORDER BY
						attend_date DESC
				    ) a ON a.student_id = s.id
				LEFT JOIN course_package p ON p.package_id = a.package_id 
				LEFT JOIN (SELECT * FROM student_log WHERE void='N' GROUP BY student_id ORDER BY timestamp DESC LIMIT 1) log ON log.student_id = s.id 
				WHERE 
					(s.id LIKE '%" . $searchText . "%' OR s.std_name LIKE '%" . $searchText . "%' OR s.std_identity LIKE '%" . $searchText . "%') 
					 AND s.std_status='A'
				GROUP BY s.id;";

		if ($query = $this -> db -> query($sql)) {
			return json_encode($query -> result_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function std_attendanceUpdate() {
		$ticked_attendances = $this -> input -> post('std_attend');
		$sessionDate = $this -> input -> post('sessionDate');

		$this -> db -> trans_begin();
		if (isset($ticked_attendances)) {
			foreach ($ticked_attendances as $list) {

				//$tmpStrArr = explode('_', $list);
				//$tmpStdID = $tmpStrArr[0];
				//$tmpBillID = $tmpStrArr[1];
				$tmpStr = explode('_', $list);
				$attendance_type = $tmpStr[0];
				if ($attendance_type == "att") {
					$attendance_id = $tmpStr[1];

					if (!(empty($attendance_id))) {
						$sql = "UPDATE student_attendance SET attendance_status = 'Y' WHERE id =" . $attendance_id;
						$this -> db -> query($sql);
						//$sql = "INSERT INTO student_attendance (attend_date, bill_id, student_id, entry_datetime) VALUES (?,?,?,?);";
						//$insertValues = array($sessionDate, $tmpBillID, $tmpStdID, date('Y-m-d H:i:s'));
						//$this -> db -> query($sql, $insertValues);
					}
				} else if ($attendance_type == "ovr") {
					//print_r($list);
					$student_id = $tmpStr[1];
					$sessionTime = $tmpStr[2];
					$sessionDate = $tmpStr[3];
					// print_r($sessionTime."-".$sessionDate);

					$sql = "SELECT * FROM student_attendance WHERE attendance_status = 'N' AND student_id =" . $student_id . " AND void='N' ORDER BY attend_date LIMIT 1";
					$query = $this -> db -> query($sql);
					if ($query -> num_rows() > 0) {
						$result = $query -> row_array();
						$sql = "UPDATE student_attendance SET replacement = 'N', attend_time='" . $sessionTime . "', attend_date = '" . $sessionDate . "', attendance_status='Y', timestamp = " . time() . " WHERE id = " . $result['id'];
						$this -> db -> query($sql);
					} else {
						$sql = "INSERT INTO student_attendance (attend_date, attend_time, student_id, timestamp, replacement, attendance_status) VALUES (?,?,?,?,?,?) ";
						$insertValues = array($sessionDate, $sessionTime, $student_id, time(), 'N', 'Y');
						$this -> db -> query($sql, $insertValues);
					}
				}

			}
		}
		$this -> db -> trans_commit();
		if ($this -> db -> trans_status() === TRUE) {
			$data['message'] = 'Replacement recorded!';
			$data['error'] = false;
		} else {
			$data['error'] = true;
			$data['message'] = 'Something wrong!';
		}
		return json_encode($data);
	}

	public function std_attendanceReplaceUpdate() {
		$std_id = $this -> input -> post('id');
		$sessionDate = $this -> input -> post('sessionDate');
		$sessionTime = $this -> input -> post('sessionTime');

		// Replacement & overdue Attendance
		// If there is still classes have used, it will update the earliest record
		// else insert the attendance record with bill_id = null

		$this -> db -> trans_begin();

		$sql = "SELECT * FROM student_attendance WHERE attendance_status = 'N' AND student_id =" . $std_id . " AND void='N' ORDER BY attend_date LIMIT 1";
		$query = $this -> db -> query($sql);
		if ($query -> num_rows() > 0) {
			$result = $query -> row_array();
			$sql = "UPDATE student_attendance SET replacement = 'Y', attend_time='" . $sessionTime . "', attend_date = '" . $sessionDate . "', attendance_status='Y', timestamp = " . time() . " WHERE id = " . $result['id'];
			$this -> db -> query($sql);
		} else {
			$sql = "INSERT INTO student_attendance (attend_date, attend_time, student_id, timestamp, replacement, attendance_status) VALUES (?,?,?,?,?,?) ";
			$insertValues = array($sessionDate, $sessionTime, $std_id, time(), 'Y', 'Y');
			$this -> db -> query($sql, $insertValues);
		}

		$this -> db -> trans_commit();
		if ($this -> db -> trans_status() === TRUE) {
			$data['message'] = 'Bill recorded!';
			$data['error'] = false;
		} else {
			$data['error'] = true;
			$data['message'] = 'Something wrong!';
		}

		return json_encode($data);
	}

	public function get_slot_time($slot_day) {
		$sql = "SELECT 
					schedule_id, slot_time, date_format(slot_time, '%r') as slot_time_12
				from course_info c 
				LEFT JOIN course_schedule cs ON cs.course_id = c.id
				WHERE slot_day = ? AND c.course_status='A' 
				GROUP BY slot_time ORDER BY slot_time ; ";
		$whereClause = array($slot_day);
		if ($query = $this -> db -> query($sql, $whereClause)) {
			// echo $this->db->last_query();
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function get_next_slot_time($slot_day, $curr_time, $schedule_id) {
		if (is_null($slot_day)) {
			$sql = "SELECT 
					schedule_id, slot_time
				FROM 
					course_info c
				LEFT JOIN 
					course_schedule cs ON cs.course_id = c.id
				WHERE 
					slot_day = ?  AND course_status='A' 
				GROUP BY slot_time 
				ORDER BY slot_time LIMIT 1";
			$whereClause = array($slot_day);
		} else {
			$sql = "SELECT 
					schedule_id, slot_time
				FROM 
					course_info c
				LEFT JOIN 
					course_schedule cs ON cs.course_id = c.id
				WHERE 
					schedule_id = ?   AND course_status='A'
				GROUP BY slot_time 
				ORDER BY slot_time LIMIT 1";
			$whereClause = array($schedule_id);
		}

		if ($query = $this -> db -> query($sql, $whereClause)) {
			// echo $this->db->last_query();
			return $query -> row_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	//------------------------------------------------
	//------------Staff Attendance------------------
	//------------------------------------------------
	public function staffAttendance_getYear() {
		$sql = "SELECT DATE_FORMAT(session_date, '%Y') as year
				FROM employee_attendance
				GROUP BY YEAR(session_date);";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function staffAttendanceDashboard() {

		$month = $this -> input -> post('month');
		$name = $this -> input -> post('name');
		$payoutStatus = $this -> input -> post('payoutStatus');
		$year = $this -> input -> post('searchYear');

		$whereClause = "";
		if (isset($name) && $name != "") {
			$whereClause = "name like '" . $name . "' AND ";
		}
		if (!isset($month) || $month == "") {
			$month = date('n');
		}
		if (!isset($year) || $year == "") {
			$year = date('Y');
		}
		if (isset($payoutStatus) && $payoutStatus != "") {
			$payoutStatus = "AND payout_status = '".$payoutStatus."'";
		}else{
            $payoutStatus = "";
        }

		$sql = "SELECT name, identity, a.* 
				FROM employee_attendance a
				JOIN employee_info e ON e.id = a.employee_id 
				WHERE " . $whereClause . " session_date >= '" . $year . "-" . $month . "-01' AND session_date < '" . $year . "-" . $month . "-31' " . $payoutStatus . " and attendance_status='A'";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function staffAttendanceNew() {
		$staffID = $this -> input -> post('staff_uid');
		$session_date = $this -> input -> post('sessionDate');
		$session_count = $this -> input -> post('sessionCount');

		if (!isset($staffID) || !isset($session_date) || !isset($session_count)) {
			$data['debug'] = $session_count;
			$data['message'] = 'Make sure you have filled all the details!';
			$data['error'] = true;
			return json_encode($data);
		}

		$sql = "SELECT * FROM employee_attendance WHERE employee_id =" . $staffID . " AND session_date='" . $session_date . "'";
		$query = $this -> db -> query($sql);
		if ($query -> num_rows() > 0) {
			$data['message'] = 'Duplicate record found!';
			$data['error'] = true;
			return json_encode($data);
		}

		$this -> db -> trans_begin();

		$sql = "INSERT INTO employee_attendance (employee_id, session_date, session_count) VALUES (" . $staffID . ", '" . $session_date . "', " . $session_count . ")";
		$this -> db -> query($sql);

		$this -> db -> trans_commit();
		if ($this -> db -> trans_status() === TRUE) {
			$data['message'] = 'Replacement recorded!';
			$data['error'] = false;
		} else {
			$data['error'] = true;
			$data['message'] = 'Something wrong!';
		}
		return json_encode($data);

	}

	public function staffAttendanceUpdate() {

	}

	public function staffAttendanceVoid() {
		$attendance_id = $this -> input -> post('activationID');

		$sql = "UPDATE employee_attendance SET attendance_status ='I' WHERE attendance_id =" . $attendance_id;
		if ($this -> db -> query($sql)) {
			$message['error'] = false;
			$message['message'] = "success";
		} else {
			$message['error'] = true;
			$message['message'] = "Activation Fail";
		}
		return json_encode($message);
	}

	public function staffNameSearch($searchText) {
		$searchText = urldecode($searchText);

		$sql = "SELECT e.*
				FROM employee_info e
				WHERE (e.name like '%" . $searchText . "%' OR e.identity like '%" . $searchText . "%') AND e.status='A';";

		if ($query = $this -> db -> query($sql)) {
			return json_encode($query -> result_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

}
