	<?php
class Student_model extends CI_Model {
	public function __construct() {
		$this -> load -> database();
	}

	public function get_student_lists() {
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
			$whereClause = $whereClause . "s.student_name like '%" . strtoupper($name) . "%' AND ";
		}
		$sql = "select s.*, cs.*, c.id as class_id, slot_time, slot_day, z.description as IsActive
				FROM student_list s
				LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN _status_list z ON s.IsActive = z.status  
				WHERE " . $whereClause . "s.IsActive = " .YES. " ORDER BY sid DESC LIMIT 30";
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
		$sql = "select * from student_list WHERE IsActive = " .YES. " = " .NO. " ";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// Model to get the levels available in the system
	public function get_class_level() {
		$sql = "SELECT * FROM class_level";
		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			// $error = $this -> db -> error();
			// echo "error <br>";
		}
	}

	// Model to get venue from db
	public function get_school_details() {
		$sql = "SELECT *, (CASE WHEN venue_id = (SELECT default_venue FROM application_profile WHERE id = 1) THEN 1 ELSE 2 END) AS default_place
				FROM school_details;";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			// $error = $this -> db -> error();
			// echo "error <br>";
		}
	}

	// Model to get available time slot from db
	public function ajax_slot_capacity($slot_day, $level, $venue_id) {

		$sql = "SELECT
					cs.schedule_id, cs.slot_time, cs.slot_day,
					s.occupied_count, cl.max_capacity,
					(cl.max_capacity - ifnull(s.occupied_count,0)) AS capacityLeft,
					CONCAT(TIME_FORMAT(cs.slot_time, '%h:%i%p') ,' (', (cl.max_capacity - ifnull(s.occupied_count,0)),')') as slot_time_str
				FROM class_schedule cs
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN class_level cl ON cl.level_id= c.level_id
				LEFT JOIN (
					SELECT count(sid) as occupied_count, schedule_id
					FROM student_list
					WHERE IsActive = " .YES. " GROUP BY schedule_id) s ON s.schedule_id = cs.schedule_id
				WHERE slot_day=".$slot_day." AND cl.level_id = ".$level." AND c.venue_id = ".$venue_id."
				ORDER BY slot_time
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

	public function ajax_student_details($std_id) {
		$sql = "SELECT s.*, c.venue_id, c.level_id, cs.slot_time, cs.slot_day
				FROM student_list s
				LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN class_list c ON c.id = cs.class_id
				WHERE s.sid=?";
		if ($query = $this -> db -> query($sql, array($std_id))) {
			return json_encode($query -> row_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function get_student_details($std_id) {
		$sql = "SELECT s.*, c.*, cs.*, e.name as staff_name, z.description as IsActive
                FROM student_list s
                LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN employee_list e ON e.id = c.instructor_id
				LEFT JOIN _status_list z ON s.IsActive = z.status  
				WHERE s.sid = " . $std_id;
		$query = $this -> db -> query($sql);
		//print_r($query -> row_array());
		return $query -> row_array();

	}

	public function get_student_details_class($std_id) {
		$sql = "SELECT b.*, p.term from student_bill b
				LEFT JOIN class_package p ON p.package_id = b.package_id 
				WHERE student_id = " . $std_id . " AND IsValid = 1 order by bill_id DESC LIMIT 1;";

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
				LEFT JOIN class_package p ON p.package_id = b.package_id
				WHERE student_id = " . $std_id . " AND IsValid = 1
				ORDER BY bill_id DESC";

		$query = $this -> db -> query($sql);
		$result = $query -> result_array();

		return $result;

	}

	public function get_student_attendance_history($std_id) {
		$sql = "SELECT *
				FROM student_attendance a
				WHERE student_id = " . $std_id . " AND IsVoid = 2 AND IsTaken=1
				ORDER BY attend_date DESC";

		$query = $this -> db -> query($sql);
		$result = $query -> result_array();

		return $result;

	}

	private function check_field($field){
		if(!isset($field)){
			return false;
		}
		if($field == ""){
			return false;
		}
		return true;
	}

	public function student_new() {

		$student_name = $this -> input -> post('student_name');
		$student_id = $this -> input -> post('student_id');
		$student_dob = $this->input->post('student_dob');
		$student_gender = $this -> input -> post('student_gender');
		$student_contact = $this -> input -> post('student_contact');
		$student_email = $this -> input -> post('student_email');

		$guardian_name = $this -> input -> post('guardian_name');
		$guardian_gender = $this -> input -> post('guardian_contact');
		$guardian_contact = $this -> input -> post('guardian_gender');

		$address_line1 = $this -> input -> post('address_1');
		$address_line2 = $this -> input -> post('address_2');
		$postcode = $this -> input -> post('postcode');
		$city = $this -> input -> post('city');
		$state = $this -> input -> post('state');
		$country = $this -> input -> post('country');

		$lesson_type = $this -> input -> post('lesson_type');
		$lesson_venue = $this -> input -> post('lesson_venue');
		$lesson_day = $this -> input -> post('lesson_day');
		$schedule_id = $this -> input -> post('schedule_id');

		$data['error'] = false;

		// pass the field to function check_field for checking
		$check_field = true;
		$check_field = $check_field && $this->check_field($student_name);
		$check_field = $check_field && $this->check_field($student_id);
		$check_field = $check_field && $this->check_field($student_dob);
		$check_field = $check_field && $this->check_field($student_gender);
		$check_field = $check_field && $this->check_field($student_contact);
		//$check_field = $check_field && $this->check_field($student_email);
		$check_field = $check_field && $this->check_field($schedule_id);


		if (!$check_field) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		// =============================
		// mid-air collision check
		// =============================
		$sql = "SELECT (cl.max_capacity - ifnull(s.occupied_count,0)) AS capacityLeft
				FROM class_schedule cs
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN class_level cl ON cl.level_id= c.level_id
				LEFT JOIN (
					SELECT count(sid) as occupied_count, schedule_id
					FROM student_list
					WHERE IsActive = " .YES. " GROUP BY schedule_id) s ON s.schedule_id = ".$schedule_id;

		if ($query = $this -> db -> query($sql)) {
			$cap_left = $query -> row_array();
			if (is_null($cap_left['capacityLeft'])) {
				$data['error'] = true;
				$data['message'] = "Lesson Not Found In System";
			} else if ($cap_left['capacityLeft'] == 0) {
				//$data['error'] = true;
				//$data['message'] = "Lesson Has been taken, please try again.";
			}
		} else {
			//$error = $this -> db -> error();
			// echo "error <br>";
		}

		if ($data['error'] == false) {
			$values = array(strtoupper($student_name), strtoupper($student_id), $student_dob, $student_gender, $student_contact, $student_email, $guardian_name, $guardian_gender, $guardian_contact, strtoupper($address_line1), strtoupper($address_line2), $postcode, strtoupper($city), strtoupper($state), strtoupper($country), $schedule_id);
			$sqlStr = "INSERT INTO student_list (student_name, student_identity, student_dob, student_gender, student_contact, student_email, guardian_name, guardian_gender, guardian_contact, address_line1, address_line2, postcode, city, state, country, schedule_id) VALUES";
			$sqlStr = $sqlStr . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			if ($this -> db -> query($sqlStr, $values)) {
				$data['error'] = false;
				$data['message'] = 'Student Registration Success!';
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);

	}

	public function student_update() {

		$student_sid = $this -> input -> post('student_sid');

		$student_name = $this -> input -> post('student_name');
		$student_id = $this -> input -> post('student_id');
		$student_dob = $this->input->post('student_dob');
		$student_gender = $this -> input -> post('student_gender');
		$student_contact = $this -> input -> post('student_contact');
		$student_email = $this -> input -> post('student_email');

		$guardian_name = $this -> input -> post('guardian_name');
		$guardian_gender = $this -> input -> post('guardian_contact');
		$guardian_contact = $this -> input -> post('guardian_gender');

		$address_line1 = $this -> input -> post('address_1');
		$address_line2 = $this -> input -> post('address_2');
		$postcode = $this -> input -> post('postcode');
		$city = $this -> input -> post('city');
		$state = $this -> input -> post('state');
		$country = $this -> input -> post('country');

		$lesson_type = $this -> input -> post('lesson_type');
		$lesson_venue = $this -> input -> post('lesson_venue');
		$lesson_day = $this -> input -> post('lesson_day');
		$schedule_id = $this -> input -> post('schedule_id');

		$check_field = true;
		$check_field = $check_field && $this->check_field($student_sid);
		//$check_field = $check_field && $this->check_field($student_name);
		$check_field = $check_field && $this->check_field($student_id);
		$check_field = $check_field && $this->check_field($student_dob);
		//$check_field = $check_field && $this->check_field($student_gender);
		$check_field = $check_field && $this->check_field($student_contact);
		//$check_field = $check_field && $this->check_field($student_email);
		$check_field = $check_field && $this->check_field($schedule_id);

		// Assume there is no error
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (!$check_field) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		// =============================
		// mid-air collision check
		// =============================
		$sql = "SELECT (cl.max_capacity - ifnull(s.occupied_count,0)) AS capacityLeft
				FROM class_schedule cs
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN class_level cl ON cl.level_id= c.level_id
				LEFT JOIN (
					SELECT count(sid) as occupied_count, schedule_id
					FROM student_list
					WHERE IsActive = " .YES. " GROUP BY schedule_id) s ON s.schedule_id = ".$schedule_id;

		if ($query = $this -> db -> query($sql)) {
			$cap_left = $query -> row_array();
			if (is_null($cap_left['capacityLeft'])) {
				$data['error'] = true;
				$data['message'] = "Lesson Not Found In System";
			} else if ($cap_left['capacityLeft'] == 0) {
				// $data['error'] = true;
				// $data['message'] = "Lesson Has been taken, please try again.";
			}
		} else {
			//$error = $this -> db -> error();
			//echo "error <br>";
		}

		if ($data['error'] == false) {
			$values = array($student_dob, $student_contact, $student_email, $guardian_name, $guardian_gender, $guardian_contact, $address_line1, $address_line2, $postcode, $city, $state, $country, $schedule_id, $student_sid);
			$values = array($student_dob, $student_contact, $student_email, $guardian_name, $guardian_gender, $guardian_contact, $address_line1, $address_line2, $postcode, $city, $state, $country, $schedule_id, $student_sid);
			$sqlStr = "UPDATE student_list SET student_dob=?, student_contact=?, student_email=?, guardian_name=?, guardian_gender=?, guardian_contact=?, address_line1=?, address_line2=?, postcode=?, city=?, state=?, country=?, schedule_id=? ";
			$sqlStr = $sqlStr . " WHERE sid=?";
			if ($this -> db -> query($sqlStr, $values)) {
				$data['error'] = false;
				$data['message'] = 'Update info success!';
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
	}

	public function student_deactivate() {
		// Activate member
		$id = $this -> input -> post('activationID');
		$name = $this -> input -> post('activationName');
		$data = array($id, $name);
		$sql = "UPDATE student_list SET IsActive = " .NO. " WHERE sid=? AND student_name=?";

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
		$sql = "UPDATE student_list SET IsActive = " .YES. " WHERE id=? AND std_name=?";

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
		$sql = "SELECT student_identity FROM student_list WHERE student_identity=?";
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
}
