<?php
class Model_course extends CI_Model {
	public function __construct() {
		$this -> load -> database();
	}

	public function show_course_active($day) {
		$sql = "SELECT 
					c.*, s.*, v.venue_name, l.*, e.name as instuctor_name 
				FROM course_info c
				LEFT JOIN course_level l ON l.level_id = c.level_id
				LEFT JOIN employee_info e ON e.id =  c.instructor_id
				LEFT JOIN course_schedule s ON s.schedule_id = c.schedule_id
				LEFT JOIN venue_code v ON v.venue_id = c.venue_id
				WHERE c.course_status = 'A' AND s.slot_day = " . $day . "
				ORDER BY s.slot_day, s.slot_time, l.level_name;";
		if ($query = $this -> db -> query($sql)) {

			// print_r($query->result_array());
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
			// echo $error;
		}
	}

	public function show_category() {
		$sql = "SELECT * FROM course_level";
		if ($query = $this -> db -> query($sql)) {

			// print_r($query->result_array());
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
			// echo $error;
		}
	}

	public function show_venue() {
		$sql = "SELECT * FROM venue_code";
		if ($query = $this -> db -> query($sql)) {

			// print_r($query->result_array());
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
			// echo $error;
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

	// get course level info
	public function get_course_level_info($cat_id) {
		$sql = "SELECT * FROM course_level WHERE level_id=" . $cat_id;
		if ($query = $this -> db -> query($sql)) {
			return json_encode($query -> row_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	// get venue info
	public function get_venue_info($id) {
		$sql = "SELECT * FROM venue_code WHERE venue_id=" . $id;
		if ($query = $this -> db -> query($sql)) {
			return json_encode($query -> row_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

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

	public function get_instructor_list() {
		$sql = "SELECT e.id, e.name FROM employee_info e
				LEFT JOIN employee_type t ON e.employee_type_id = t.type_id
				WHERE t.is_instructor='Y' AND status='A'
				ORDER BY e.name";

		if ($query = $this -> db -> query($sql)) {
			return $query -> result_array();
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function get_schedule($slot_day) {
		$sql = "SELECT cs.schedule_id, DATE_FORMAT(cs.slot_time,'%h:%i %p') slot_time, cs.slot_day FROM course_schedule cs WHERE cs.slot_day = " . $slot_day;

		if ($query = $this -> db -> query($sql)) {
			return json_encode($query -> result_array());
		} else {
			// $error = $this -> db -> error();
			echo "error <br>";
		}

	}

	public function check_instructor($staff_id, $schedule_id) {
		if (isset($staff_id) && isset($schedule_id)) {
			$sql = "SELECT * 
					FROM course_info c
					LEFT JOIN employee_info e ON e.id = c.instructor_id 
					LEFT JOIN course_schedule s ON s.schedule_id = c.schedule_id
					WHERE e.id=? AND s.schedule_id=? AND c.course_status='A'";
			$whereClause = array($staff_id, $schedule_id);

			if ($query = $this -> db -> query($sql, $whereClause)) {
				if ($query -> num_rows() > 0) {
					$message['message'] = "Same instructor has been assigned to this session.";
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

	public function get_course_info($course_id) {
		$sql = "SELECT c.*, s.*, l.*, v.venue_id FROM course_info c
				LEFT JOIN course_schedule s ON c.schedule_id = s.schedule_id
				LEFT JOIN course_level l ON c.level_id = l.level_id 
				LEFT JOIN venue_code v ON c.venue_id = v.venue_id  
				WHERE c.id=?";
		$where_clause = array($course_id);
		if ($query = $this -> db -> query($sql, $where_clause)) {
			return json_encode($query -> row_array());
		} else {
			$error = $this -> db -> error();
			echo "error <br>";
		}
	}

	public function course_new() {
		$lessonVenue = $this -> input -> post('lessonVenue');
		$courseLevel = $this -> input -> post('courseLevel');
		// $courseCapacity = $this -> input -> post('courseCapacity');
		$courseDay = $this -> input -> post('courseDay');
		$schedule_id = $this -> input -> post('course_schedule');
		$courseInstructor = $this -> input -> post('courseInstructor');

		// Null has been set as Precomp/ Comp's day
		if ($courseDay == 'NULL')
			$courseDay = null;

		// Assume there is no error
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (!isset($lessonVenue) || !isset($courseLevel) ||  !isset($courseDay) || !isset($schedule_id) || !isset($courseInstructor)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}
		
		// =============================================================
		// verify the instructor has been arranged for particular slot
		// =============================================================
		$sql = "SELECT *
					FROM course_info c
					LEFT JOIN employee_info e ON e.id = c.instructor_id
					WHERE e.id=? AND c.schedule_id =? AND c.venue_id =? AND c.course_status='A'";
		$whereClause = array($courseInstructor, $schedule_id, $lessonVenue);

		if ($query = $this -> db -> query($sql, $whereClause)) {
			if ($query -> num_rows() > 0) {
				$data['message'] = "Same instructor has been assigned to this session.";
				$data['error'] = true;
				return json_encode($data);
			}
		}

		$this -> db -> trans_begin();

		if ($data['error'] == false) {
			$insertValues = array($schedule_id, $lessonVenue, $courseInstructor, $courseLevel, time());
			$sql = "INSERT INTO course_info (schedule_id, venue_id, instructor_id, level_id, timestamp) VALUES (?,?,?,?,?)";
			$this -> db -> query($sql, $insertValues);

			$this -> db -> trans_commit();
			if ($this -> db -> trans_status() === TRUE) {
				$data['message'] = 'Course Created!';
				$data['error'] = false;
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
		// End of New student registar
	}

	public function course_update() {
		$courseID = $this -> input -> post('courseID');
		$courseInstructor = $this -> input -> post('courseInstructor');

		// Assume there is no error
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (!isset($courseID) || !isset($courseInstructor)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		$this -> db -> trans_begin();

		if ($data['error'] == false) {
			$insertValues = array($courseInstructor, time(), $courseID);

			$sql = "UPDATE course_info SET instructor_id=?,timestamp=?";
			$sql = $sql . " WHERE id=? ";
			$this -> db -> query($sql, $insertValues);

			$this -> db -> trans_commit();
			if ($this -> db -> trans_status() === TRUE) {
				$data['message'] = 'Course Updated!';
				$data['error'] = false;
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
	}

	public function course_deactivate() {
		// Activate member
		$id = $this -> input -> post('activationID');
		$data = array($id);
		$sql = "UPDATE course_info SET course_status='I' WHERE id=?";

		if ($this -> db -> query($sql, $data)) {
			$message['error'] = false;
			$message['message'] = "success";
		} else {
			$message['error'] = true;
			$message['message'] = "Activation Fail";
		}
		return json_encode($message);
	}

	// ------------------------------------------
	// Below is course category related function
	// ------------------------------------------
	public function category_create() {
		$catName = $this -> input -> post('catName');
		$catInfo = $this -> input -> post('catInfo');
		$private = $this -> input -> post('private');
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (!isset($catName) || !isset($catInfo)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		$this -> db -> trans_begin();

		if ($data['error'] == false) {
			$insertValues = array($catName, $catInfo, time(), $private);
			$sql = "INSERT INTO course_level (level_name, level_info, timestamp, private_state) VALUES";
			$sql = $sql . " (?,?,?,?)";
			$this -> db -> query($sql, $insertValues);

			$this -> db -> trans_commit();
			if ($this -> db -> trans_status() === TRUE) {
				$data['message'] = 'New Category Created!';
				$data['error'] = false;
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
		// End of New student registar
	}

	public function category_update() {
		$catID = $this -> input -> post('catID');
		$catName = $this -> input -> post('catName');
		$catInfo = $this -> input -> post('catInfo');
		$private = $this -> input -> post('private');

		// Assume there is no error
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (!isset($catID) || !isset($catName) || !isset($catInfo)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		$this -> db -> trans_begin();

		if ($data['error'] == false) {
			$insertValues = array($catName, $catInfo, time(), $private, $catID);

			$sql = "UPDATE course_level SET level_name=?, level_info=? ,timestamp=?, private_state=?";
			$sql = $sql . " WHERE level_id=? ";
			$this -> db -> query($sql, $insertValues);

			$this -> db -> trans_commit();
			if ($this -> db -> trans_status() === TRUE) {
				$data['message'] = 'Course Updated!';
				$data['error'] = false;
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
	}

	// ------------------------------------------
	// Below is course venue related function
	// ------------------------------------------
	public function venue_create() {
		$name = $this -> input -> post('venueName');
		$building = $this -> input -> post('building');
		$street = $this -> input -> post('street');
		$postkod = $this -> input -> post('postkod');
		$city = $this -> input -> post('city');
		$state = $this -> input -> post('state');
		$country = $this -> input -> post('country');

		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (!isset($name) || !isset($building) || !isset($street) || !isset($postkod) || !isset($city) || !isset($state) || !isset($country)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		$this -> db -> trans_begin();

		if ($data['error'] == false) {
			$insertValues = array($name, $building, $street, $postkod, $city, $state, $country, date('Y-m-d H:i:s'));
			$sql = "INSERT INTO venue_code (venue_name, venue_building, venue_street, venue_postkod, city, state, country, modify_datetime) VALUES";
			$sql = $sql . " (?,?,?,?,?,?,?,?)";
			$this -> db -> query($sql, $insertValues);

			$this -> db -> trans_commit();
			if ($this -> db -> trans_status() === TRUE) {
				$data['message'] = 'New Venue Created!';
				$data['error'] = false;
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
	}

	public function venue_update() {
		$venueID = $this -> input -> post('venueID');
		$name = $this -> input -> post('venueName');
		$building = $this -> input -> post('building');
		$street = $this -> input -> post('street');
		$postkod = $this -> input -> post('postkod');
		$city = $this -> input -> post('city');
		$state = $this -> input -> post('state');
		$country = $this -> input -> post('country');

		// Assume there is no error
		$data['error'] = false;

		// validation check if user use some unexpected method to submit without fulfil these field.
		if (!isset($venueID) || !isset($building) || !isset($street) || !isset($postkod) || !isset($city) || !isset($state) || !isset($country)) {
			$data['error'] = true;
			$data['message'] = "Please check your fill again.";
			return json_encode($data);
		}

		$this -> db -> trans_begin();

		if ($data['error'] == false) {
			$insertValues = array($building, $street, $postkod, $city, $state, $country, date('Y-m-d H:i:s'), $venueID);
			$sql = "UPDATE venue_code SET venue_building=? ,venue_street=?, venue_postkod=?, city=?, state=?, country=?, modify_datetime=?";
			$sql = $sql . " WHERE venue_id=? ";
			$this -> db -> query($sql, $insertValues);

			$this -> db -> trans_commit();
			if ($this -> db -> trans_status() === TRUE) {
				$data['message'] = 'Venue Updated!';
				$data['error'] = false;
			} else {
				$data['error'] = true;
				$data['message'] = 'Something wrong!';
			}
		}
		return json_encode($data);
	}

	/*
	 public function category_deactivate() {
	 // Activate member
	 $id = $this -> input -> post('activationID');
	 $data = array($id);
	 $sql = "UPDATE course_level SET course_status='I' WHERE id=?";

	 if ($this -> db -> query($sql, $data)) {
	 $message['error'] = false;
	 $message['message'] = "success";
	 } else {
	 $message['error'] = true;
	 $message['message'] = "Activation Fail";
	 }
	 return json_encode($message);
	 }
	 */

}
