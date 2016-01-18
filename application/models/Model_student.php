<?php
class Model_student extends CI_Model {
	public function __construct() {
		$this -> load -> database();
	}

	public function show_student_active() {
		$time = $this -> input -> post('searchTime');
		$day = $this -> input -> post('searchDay');
		$name = $this -> input -> post('searchName');
		$whereClause = "";
		if (isset($time) && $time != "") {
			$whereClause = $whereClause . "(slot_time >='" . $time . ":00:00' AND slot_time < '" . ($time + 1) . ":00:00') AND ";
		}
		if (isset($day) && $day != "") {
			$whereClause = $whereClause . "slot_day= " . $day . " AND ";
		}
		if (isset($name) && $name != "") {
			$whereClause = $whereClause . "s.std_name like '%" . strtoupper($name) . "%' AND ";
		}
		$sql = "select s.*, cs.*, c.id as course_id, slot_time, slot_day from student_info s
				LEFT JOIN course_info c ON c.id = s.course_id 
				LEFT JOIN course_schedule cs ON cs.schedule_id = c.schedule_id
				WHERE " . $whereClause . "s.std_status='A' LIMIT 100";
		if ($query = $this -> db -> query($sql)) {

			// print_r($query->result_array());
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
			// echo $error;
		}
	}

	public function show_student_inactive() {
		$sql = "select * from student_info WHERE std_status='I'";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// Model to get the levels available in the system
	public function get_course_level() {
		$sql = "SELECT * FROM course_level";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// Model to get venue from db
	public function get_venue_code() {
		$sql = "SELECT *, (CASE WHEN venue_id = (SELECT default_venue FROM system_profile WHERE id=1) THEN 'Y' ELSE 'N' END) AS default_place 
				FROM venue_code;";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// Model to get available time slot from db
	public function get_slot_time_with_cap($slot_day, $level, $venue_id) {

		$sql = "SELECT 	c.id, cs.slot_time, cs.slot_day, 
				s.occupied, lvl.max_capacity, (lvl.max_capacity - ifnull(s.occupied,0)) AS capacityLeft,
        		CONCAT(TIME_FORMAT(cs.slot_time, '%h:%i%p') ,' (', (lvl.max_capacity - ifnull(s.occupied,0)),')') as slot_time_str
				FROM course_info c
				LEFT JOIN course_level lvl ON lvl.level_id = c.level_id
				LEFT JOIN (
					SELECT count(id) as occupied, course_id 
					FROM student_info 
					WHERE std_status='A' GROUP BY course_id) s ON s.course_id = c.id
				LEFT JOIN course_schedule cs ON cs.schedule_id = c.schedule_id
				WHERE c.level_id = ? AND cs.slot_day = ? AND c.course_status='A' AND c.venue_id = ? AND ((lvl.max_capacity - ifnull(s.occupied,0))>0 OR lvl.private_state='Y');
				";
		$whereClause = array($level, $slot_day, $venue_id);
		if ($query = $this -> db -> query($sql, $whereClause)) {
			// echo $this->db->last_query();
			return json_encode($query -> result_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function get_student_info($std_id) {
		$sql = "SELECT s.*, c.venue_id, c.level_id, cs.slot_time, cs.slot_day 
				FROM student_info s
				LEFT JOIN course_info c ON c.id = s.course_id
				LEFT JOIN course_schedule cs ON cs.schedule_id = c.schedule_id
				WHERE s.id=?";
		if ($query = $this -> db -> query($sql, array($std_id))) {
			return json_encode($query -> row_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function get_student_details($std_id) {
		$sql = "SELECT s.*, c.*, e.*, cs.*, e.name as staff_name, s.id as student_id FROM student_info s 
				LEFT JOIN course_info c ON c.id = s.course_id
				LEFT JOIN course_schedule cs ON cs.schedule_id = c.schedule_id
				LEFT JOIN employee_info e ON e.id = c.instructor_id
				WHERE s.id=" . $std_id;
		$query = $this -> db -> query($sql);
		return $query -> row_array();

	}

	public function get_student_details_course($std_id) {
		$sql = "SELECT b.*, p.term from student_bill b 	
				LEFT JOIN course_package p ON p.package_id = b.package_id 
				WHERE std_id = " . $std_id . " AND bill_status = 'A' order by bill_id DESC LIMIT 1;";

		$query = $this -> db -> query($sql);
		$result = $query -> row_array();
		$latest_payment_date = $result['issue_date'];
		$term = $result['term'];

		$sql = "SELECT count(id) as att_count, a.* FROM student_attendance a
				WHERE student_id = " . $std_id . "
				GROUP BY bill_id, student_id
				ORDER BY id desc
				LIMIT 1
				";

		$query = $this -> db -> query($sql);
		$result = $query -> row_array();

		$att_count = $result['att_count'];
		$last_bill_id = $result['bill_id'];

		if (is_null($last_bill_id)) {
			$att_count = $att_count * -1;
		}

		$data['last_payment_date'] = $latest_payment_date;
		$data['last_bill_id'] = $last_bill_id;
		$data['att_count'] = $att_count;

		return $data;

	}

	public function get_student_payment_details($std_id) {
		$sql = "SELECT *
				FROM student_bill b
				LEFT JOIN course_package p ON p.package_id = b.package_id
				WHERE std_id = " . $std_id . " AND bill_status = 'A'
				ORDER BY bill_id DESC";

		$query = $this -> db -> query($sql);
		$result = $query -> result_array();

		return $result;

	}

	public function get_student_attendance_history($std_id) {
		$sql = "SELECT *
				FROM student_attendance a
				WHERE student_id = " . $std_id . " AND void = 'N' AND attendance_status='Y'
				ORDER BY attend_date DESC";

		$query = $this -> db -> query($sql);
		$result = $query -> result_array();

		return $result;

	}

	public function student_new() {
		$stdName = $this -> input -> post('stdName');
		$stdID = $this -> input -> post('stdID');
		$stdGender = $this -> input -> post('stdGender');
		$stdContact = $this -> input -> post('stdContact');
		$stdEmail = $this -> input -> post('stdEmail');
		$stdGuardian = $this -> input -> post('stdGuardian');
		$stdGuardianGender = $this -> input -> post('stdGuardianGender');
		$stdGuardianContact = $this -> input -> post('stdGuardianContact');
		$addr_building = $this -> input -> post('stdAddr1');
		$addr_street = $this -> input -> post('stdAddr2');
		$addr_postkod = $this -> input -> post('Postcode');
		$addr_city = $this -> input -> post('City');
		$addr_state = $this -> input -> post('State');
		$addr_country = $this -> input -> post('Country');
		$lessonType = $this -> input -> post('lessonType');
		$lessonVenue = $this -> input -> post('lessonVenue');
		$lessonDay = $this -> input -> post('lessonDay');
		$courseID = $this -> input -> post('lessonTime');

		// Assume there is no error
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (empty($stdName) || empty($stdGender) || empty($stdContact) || empty($courseID)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		// Security check if there is any collision while submitting
		$sql = "SELECT (cl.max_capacity - ifnull(s.occupied,0)) AS capacityLeft
				FROM course_info c
				LEFT JOIN course_level cl ON cl.level_id= c.level_id
				LEFT JOIN (
					SELECT count(id) as occupied, course_id
					FROM student_info
					WHERE std_status='A' GROUP BY course_id) s ON s.course_id = c.id
				WHERE c.id = " . $courseID;

		if ($query = $this -> db -> query($sql)) {
			$cap_left = $query -> row_array();
			if (is_null($cap_left['capacityLeft'])) {
				$data['error'] = true;
				$data['message'] = "Lesson Not Found In System";
			} else if ($cap_left['capacityLeft'] == 0) {
				$data['error'] = true;
				$data['message'] = "Lesson Has been taken, please try again.";
			}
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}

		// Reserve for private student
		//
		//
		if ($data['error'] == false) {
			$insertValues = array(strtoupper($stdName), $stdID, $stdGender, $stdContact, $stdEmail, $stdGuardian, $stdGuardianGender, $stdGuardianContact, $addr_building, $addr_street, $addr_postkod, $addr_city, $addr_state, $addr_country, $courseID, 'A');
			$sqlStr = "INSERT INTO student_info (std_name, std_identity, std_gender, std_contact, std_email, guardian_name, guardian_gender, guardian_contact, addr_building, addr_street, addr_postkod, addr_city, addr_state, addr_country, course_id, std_status) VALUES";
			$sqlStr = $sqlStr . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			if ($this -> db -> query($sqlStr, $insertValues)) {
				$data['error'] = false;
				$data['message'] = 'New Student register success!';
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
		// End of New student registar
	}

	public function student_update() {
		$stdID = $this -> input -> post('studentID');
		$stdName = $this -> input -> post('stdName');
		$stdContact = $this -> input -> post('stdContact');
		$stdEmail = $this -> input -> post('stdEmail');
		$stdGuardian = $this -> input -> post('stdGuardian');
		$stdGuardianGender = $this -> input -> post('stdGuardianGender');
		$stdGuardianContact = $this -> input -> post('stdGuardianContact');
		$addr_building = $this -> input -> post('stdAddr1');
		$addr_street = $this -> input -> post('stdAddr2');
		$addr_postkod = $this -> input -> post('Postcode');
		$addr_city = $this -> input -> post('City');
		$addr_state = $this -> input -> post('State');
		$addr_country = $this -> input -> post('Country');
		$lessonType = $this -> input -> post('lessonType');
		$lessonVenue = $this -> input -> post('lessonVenue');
		$lessonDay = $this -> input -> post('lessonDay');
		$courseID = $this -> input -> post('lessonTime');

		// Assume there is no error
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (empty($stdID) || empty($stdName) || empty($stdContact) || empty($courseID)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";

			return json_encode($data);
		}

		// Security check if there is any collision while submitting
		$sql = "SELECT
					(cl.max_capacity - count(std.course_id)) AS capacityLeft
				FROM student_info std
				LEFT JOIN course_info cor ON cor.id = std.course_id
				LEFT JOIN course_level cl ON cl.level_id = cor.level_id
				WHERE std.course_id = ? ";

		if ($query = $this -> db -> query($sql, $courseID)) {
			$cap_left = $query -> row_array();
			if (is_null($cap_left['capacityLeft'])) {
				$data['error'] = true;
				$data['message'] = "Lesson Not Found In System";
			} else if ($cap_left['capacityLeft'] == 0) {
				$data['error'] = true;
				$data['message'] = "Lesson Has been taken, please try again.";
			}
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}

		if ($data['error'] == false) {
			$insertValues = array($stdContact, $stdEmail, $stdGuardian, $stdGuardianGender, $stdGuardianContact, $addr_building, $addr_street, $addr_postkod, $addr_city, $addr_state, $addr_country, $courseID, $stdID, $stdName);
			$sqlStr = "UPDATE student_info SET std_contact=?, std_email=?, guardian_name=?, guardian_gender=?, guardian_contact=?, addr_building=?, addr_street=?, addr_postkod=?, addr_city=?, addr_state=?, addr_country=?, course_id=? ";
			$sqlStr = $sqlStr . " WHERE id=? AND std_name=?";
			if ($this -> db -> query($sqlStr, $insertValues)) {
				$data['error'] = false;
				$data['message'] = 'Update info success!';
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
		// End of New student registar
	}

	public function student_deactivate() {
		// Activate member
		$id = $this -> input -> post('activationID');
		$name = $this -> input -> post('activationName');
		$data = array($id, $name);
		$sql = "UPDATE student_info SET std_status='I' WHERE id=? AND std_name=?";

		if ($this -> db -> query($sql, $data)) {
			$message['error'] = false;
			$message['message'] = "success";
			$message['debug'] = $name;
		} else {
			$message['error'] = true;
			$message['message'] = "Activation Fail";
		}
		return json_encode($message);
	}

	public function student_activate($userid = NULL) {
		// Activate member
		$id = $this -> input -> post('activationID');
		$name = $this -> input -> post('activationName');
		$data = array($id, $name);
		$sql = "UPDATE student_info SET std_status='A' WHERE id=? AND std_name=?";

		if ($this -> db -> query($sql, $data)) {
			$message['error'] = false;
			$message['message'] = "success";
			// $message['sql']=$this->db->last_query();
		} else {
			$message['error'] = true;
			$message['message'] = "Activation Fail";
		}
		return json_encode($message);
	}

	public function checkID($ID) {
		$sql = "SELECT std_identity FROM student_info WHERE std_identity=?";
		if ($query = $this -> db -> query($sql, array($ID))) {
			if ($query -> num_rows() > 0) {
				$message['message'] = "Someone is using the same IC too...";
				$message['record'] = true;
			} else {
				$message['record'] = false;
			}
			$message['error'] = false;
		} else {
			$message['error'] = true;
			$message['message'] = "Activation Fail";
		}
		return json_encode($message);
	}

	public function search_name($searchText) {
		$searchText = urldecode($searchText);

		$sql = "SELECT
					*
				FROM student_info s
				WHERE 
					(s.id LIKE '%" . $searchText . "%' OR s.std_name LIKE '%" . $searchText . "%' OR s.std_identity LIKE '%" . $searchText . "%') 
					 AND s.std_status='A'";

		if ($query = $this -> db -> query($sql)) {
			return json_encode($query -> result_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function student_log_new() {
		$student_id = $this -> input -> post('student_id');
		$log = $this -> input -> post('log');
		$session_data = $this -> session -> userdata('logged_in');

		if (!isset($student_id) || !isset($log) || $student_id == "" || $log == "") {
			$message['error'] = true;
			$message['message'] = " check the fill again";
		} else {
			$sql = "INSERT INTO student_log (student_id, log, staff_id, timestamp) VALUES (?,?,?,?) ";
			$values = array($student_id, $log, $session_data['uid'], time());
			if ($this -> db -> query($sql, $values)) {
				$message['error'] = false;
				$message['message'] = "success";
				// $message['sql']=$this->db->last_query();
			} else {
				$message['error'] = true;
				$message['message'] = "Activation Fail";
			}
		}
		return json_encode($message);
	}

	public function student_log() {
		$name = $this -> input -> post('searchName');

		if (isset($name) && $name != "") {
			$sql = "SELECT l.*, e.name as staff_name, s.std_name as student_name, s.id as student_id
				FROM student_log l
				LEFT JOIN student_info s on s.id = l.student_id
				LEFT JOIN employee_info e ON e.id = staff_id
				WHERE void='N' AND s.std_name like '%?%'
				ORDER BY timestamp DESC
				LIMIT 300";
			$query = $this -> db -> query($sql, array($name));
		} else {
			$sql = "SELECT l.*, e.name as staff_name, s.std_name as student_name, s.id as student_id
				FROM student_log l
				LEFT JOIN student_info s on s.id = l.student_id
				LEFT JOIN employee_info e ON e.id = staff_id
				WHERE void='N' 
				ORDER BY timestamp DESC
				LIMIT 300";
			$query = $this -> db -> query($sql);
		}

		return $query -> result_array();
	}

	public function student_log_void() {
		// Activate member
		$id = $this -> input -> post('activationID');
		$data = array($id);
		$sql = "UPDATE student_log SET void='Y' WHERE log_id=? ";

		if ($this -> db -> query($sql, $data)) {
			$message['error'] = false;
			$message['message'] = "success";
		} else {
			$message['error'] = true;
			$message['message'] = "Activation Fail";
		}
		return json_encode($message);
	}

}
