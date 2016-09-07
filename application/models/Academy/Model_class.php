<?php

class Model_class extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    /*
     * class related function
     *
     */
    public function get_assignments($venue_id, $instructor_name) {
        /*
        $sql = "SELECT
                    c.*, v.venue_name, l.*, e.name as instructor_name
                FROM class_list c
                LEFT JOIN class_level l ON l.level_id = c.level_id
                LEFT JOIN employee_list e ON e.id =  c.instructor_id
                LEFT JOIN school_details v ON v.venue_id = c.venue_id
                WHERE c.IsActive = 1
                ORDER BY l.level_name;";
        */
        if (is_null($venue_id)) {
            $venue = "";
        } else {
            $venue = " AND venue_id = " . $venue_id;
        }
        if (is_null($instructor_name)) {
            $sql = "SELECT * FROM employee_list WHERE IsActive = " . YES;
        } else {
            $sql = "SELECT * FROM employee_list WHERE IsActive = " . YES . " AND name LIKE '%" . $instructor_name . "%'";
        }

        $query = $this->db->query($sql);
        $result_employee = $query->result_array();

        $return_array = array();

        foreach ($result_employee as $row) {
            $temp_array = array();
            $temp_array['employee_name'] = $row['name'];
            $temp_array['employee_id'] = $row['id'];
            $sql = "SELECT * FROM class_list c
				  	LEFT JOIN class_level cl ON cl.level_id = c.level_id
				  	LEFT JOIN school_details vc ON vc.venue_id = c.venue_id
					WHERE IsActive = " . YES . " AND instructor_id=" . $row['id'] . $venue;
            $query = $this->db->query($sql);
            $result = $query->result_array();
            $temp_array['class'] = $result;
            $return_array[] = $temp_array;
        }
        return $return_array;

    }


    public function show_category() {
        $sql = "SELECT * FROM class_level";
        if ($query = $this->db->query($sql)) {

            // print_r($query->result_array());
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
            // echo $error;
        }
    }

    public function show_venue() {
        $sql = "SELECT * FROM school_details";
        if ($query = $this->db->query($sql)) {

            // print_r($query->result_array());
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
            // echo $error;
        }
    }

    // Model to get the levels available in the system
    public function get_class_level() {
        $sql = "SELECT * FROM class_level";
        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    // get class level info
    public function get_class_level_info($cat_id) {
        $sql = "SELECT * FROM class_level WHERE level_id=" . $cat_id;
        if ($query = $this->db->query($sql)) {
            return json_encode($query->row_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    // get venue info
    public function get_venue_info($id) {
        $sql = "SELECT * FROM school_details WHERE venue_id=" . $id;
        if ($query = $this->db->query($sql)) {
            return json_encode($query->row_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function get_school_details() {
        $sql = "SELECT *, (CASE WHEN venue_id = (SELECT default_venue FROM application_profile WHERE id=1) THEN 1 ELSE 2 END) AS default_place
				FROM school_details;";

        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function get_instructor_list() {
        $sql = "SELECT e.id, e.name FROM employee_list e
				LEFT JOIN employee_type t ON e.employee_type_id = t.type_id
				WHERE t.IsInstructor=1 AND IsActive = " . YES . "
				ORDER BY e.name";

        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function get_schedule($slot_day) {
        $sql = "SELECT cs.schedule_id, DATE_FORMAT(cs.slot_time,'%h:%i %p') slot_time, cs.slot_day FROM class_schedule cs WHERE cs.slot_day = " . $slot_day;

        if ($query = $this->db->query($sql)) {
            return json_encode($query->result_array());
        } else {
            // $error = $this -> db -> error();
            echo "error <br>";
        }

    }

    public function check_instructor($staff_id, $schedule_id) {
        if (isset($staff_id) && isset($schedule_id)) {
            $sql = "SELECT *
					FROM class_list c
					LEFT JOIN employee_list e ON e.id = c.instructor_id 
					LEFT JOIN class_schedule s ON s.schedule_id = c.schedule_id
					WHERE e.id=? AND s.schedule_id=? AND c.IsActive = " . YES;
            $whereClause = array($staff_id, $schedule_id);

            if ($query = $this->db->query($sql, $whereClause)) {
                if ($query->num_rows() > 0) {
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

    public function ajax_get_class_list($class_id) {
        $sql = "SELECT c.*, l.*, v.venue_id FROM class_list c
				LEFT JOIN class_level l ON c.level_id = l.level_id 
				LEFT JOIN school_details v ON c.venue_id = v.venue_id  
				WHERE c.id = ?";
        $where_clause = array($class_id);
        if ($query = $this->db->query($sql, $where_clause)) {
            return json_encode($query->row_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function assignment_create() {
        $lessonVenue = $this->input->post('ClassVenue');
        $classLevel = $this->input->post('ClassLevel');
        $classInstructor = $this->input->post('ClassInstructor');

        // Assume there is no error
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($lessonVenue) || !isset($classLevel)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        //
        //
        //
        $sql = "SELECT * FROM class_list WHERE level_id=" . $classLevel . " AND instructor_id =" . $classInstructor;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data['error'] = true;
            $data['message'] = "Duplicate.";
            return json_encode($data);
        }

        $this->db->trans_begin();

        if ($data['error'] == false) {
            $values = array($lessonVenue, $classInstructor, $classLevel, time());
            $sql = "INSERT INTO class_list (venue_id, instructor_id, level_id, timestamp) VALUES (?,?,?,?)";
            $this->db->query($sql, $values);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
                $data['message'] = 'class Created!';
                $data['error'] = false;
            } else {
                $data['error'] = true;
                $data['message'] = 'Something wrong!';
            }
        }
        return json_encode($data);
        // End of New student registar
    }

    public function assignment_update() {
        $classID = $this->input->post('classID');
        $classInstructor = $this->input->post('classInstructor');

        // Assume there is no error
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($classID) || !isset($classInstructor)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        $this->db->trans_begin();

        if ($data['error'] == false) {
            $insertValues = array($classInstructor, time(), $classID);

            $sql = "UPDATE class_list SET instructor_id=?,timestamp=?";
            $sql = $sql . " WHERE id=? ";
            $this->db->query($sql, $insertValues);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
                $data['message'] = 'class Updated!';
                $data['error'] = false;
            } else {
                $data['error'] = true;
                $data['message'] = 'Something wrong!';
            }
        }
        return json_encode($data);
    }

    public function assignment_deactivate() {
        // Activate member
        $id = $this->input->post('activationID');
        $data = array($id);
        $sql = "UPDATE class_list SET IsActive = ". NO ." WHERE id=?";

        if ($this->db->query($sql, $data)) {
            $message['error'] = false;
            $message['message'] = "success";
        } else {
            $message['error'] = true;
            $message['message'] = "Activation Fail";
        }
        return json_encode($message);
    }

    // -----------------------------------------
    // Schedule Related 
    // -----------------------------------------

    public function list_schedules($day, $venue_id) {
        if (!isset($venue_id) || $venue_id == "" || is_null($venue_id)) {
            $venue = "";
        } else {
            $venue = " AND c.venue_id = " . $venue_id;
        }
        $sql = "SELECT
					c.*, s.*, v.venue_name, l.*, e.name AS instuctor_name
				FROM class_schedule s
				LEFT JOIN class_list c ON c.id = s.class_id
				LEFT JOIN class_level l ON l.level_id = c.level_id
				LEFT JOIN employee_list e ON e.id =  c.instructor_id
				LEFT JOIN school_details v ON v.venue_id = c.venue_id
				WHERE c.IsActive = " . YES. " AND s.slot_day = " . $day . $venue . "
				ORDER BY s.slot_day, s.slot_time, l.level_name";
        if ($query = $this->db->query($sql)) {
            // print_r($query->result_array());
            return $query->result_array();
        } else {
            // $error = $this->db->error();
            // echo $error;
        }
    }

    public function schedule_create() {

        $venue_id = $this->input->post('venue_id');
        $schedule_day = $this->input->post('schedule_day');
        $schedule_hour = $this->input->post('schedule_hour');
        $schedule_minute = $this->input->post('schedule_minute');
        $schedule_duration = $this->input->post('class_duration');
        $class_id = $this->input->post('class_id');
        $instructor_id = $this->input->post('instructor_id');

        $schedule_time = $schedule_hour . ":" . $schedule_minute . ":00";
        $schedule_time_end = date('H:i:s', strtotime($schedule_time . " + " . $schedule_duration . "minutes"));

        // Assume there is no error
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($class_id) || !isset($venue_id) || !isset($schedule_day) || !isset($schedule_hour) || !isset($schedule_minute) || !isset($instructor_id)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        // =============================================================
        // verify the instructor has been arranged for particular slot
        // =============================================================
        $sql = "SELECT c.id
					FROM class_schedule cs
					LEFT JOIN class_list c ON cs.class_id = c.id
					LEFT JOIN class_level cl ON cl.level_id = c.level_id
					WHERE
					  c.instructor_id=? AND
					  (cs.slot_time>=? AND cs.slot_time<TIME( DATE_ADD( concat( '2000-01-01 ', ? ) , INTERVAL cl.duration_minute MINUTE ) )) AND
					  cs.slot_day = ? AND
					  c.venue_id =? AND
					  c.id =? AND
					  c.IsActive = " . YES . " AND
					  c.allow_multi_instructor = 2";
        $whereClause = array($instructor_id, $schedule_time, $schedule_time, $schedule_day, $venue_id, $class_id);

        if ($query = $this->db->query($sql, $whereClause)) {
            if ($query->num_rows() > 0) {
                $data['message'] = "Same instructor has been assigned to this session.";
                $data['error'] = true;
                return json_encode($data);
            }
        }

        $this->db->trans_begin();

        if ($data['error'] == false) {
            $insertValues = array($schedule_day, $schedule_time, $schedule_time_end, $class_id, time());
            $sql = "INSERT INTO class_schedule (slot_day, slot_time, slot_time_end, class_id, timestamp) VALUES (?,?,?,?,?)";
            $this->db->query($sql, $insertValues);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
                $data['message'] = 'Schedule Created!';
                $data['error'] = false;
            } else {
                $data['error'] = true;
                $data['message'] = 'Something wrong!';
            }
        }
        return json_encode($data);
        // End of New student registar
    }

    public function ajax_get_class($instructor_id, $venue_id) {
        if (!isset($venue_id) || $venue_id == "") {
            $venue = "";
        } else {
            $venue = " AND venue_id = " . $venue_id;
        }
        $sql = "SELECT *
				FROM class_list c
				LEFT JOIN class_level cl ON cl.level_id = c.level_id
				WHERE c.IsActive = " . YES . " AND c.instructor_id = " . $instructor_id . $venue;

        if ($query = $this->db->query($sql)) {

            // print_r($query->result_array());
            return json_encode($query->result_array());
        } else {
            // $error = $this->db->error();
            // echo $error;
        }
    }

    // ------------------------------------------
    // Below is class category related function
    // ------------------------------------------
    public function category_create() {
        $catName = $this->input->post('catName');
        $catInfo = $this->input->post('catInfo');
        $private = $this->input->post('private');
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($catName) || !isset($catInfo)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        $this->db->trans_begin();

        if ($data['error'] == false) {
            $insertValues = array($catName, $catInfo, time(), $private);
            $sql = "INSERT INTO class_level (level_name, level_info, timestamp, private_state) VALUES (?,?,?,?)";
            $this->db->query($sql, $insertValues);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
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
        $catID = $this->input->post('catID');
        $catName = $this->input->post('catName');
        $catInfo = $this->input->post('catInfo');
        $private = $this->input->post('private');

        // Assume there is no error
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($catID) || !isset($catName) || !isset($catInfo)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        $this->db->trans_begin();

        if ($data['error'] == false) {
            $insertValues = array($catName, $catInfo, time(), $private, $catID);

            $sql = "UPDATE class_level SET level_name=?, level_info=? ,timestamp=?, private_state=?";
            $sql = $sql . " WHERE level_id=? ";
            $this->db->query($sql, $insertValues);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
                $data['message'] = 'class Updated!';
                $data['error'] = false;
            } else {
                $data['error'] = true;
                $data['message'] = 'Something wrong!';
            }
        }
        return json_encode($data);
    }

    // ------------------------------------------
    // Below is class venue related function
    // ------------------------------------------
    public function venue_create() {
        $name = $this->input->post('venueName');
        $building = $this->input->post('building');
        $street = $this->input->post('street');
        $postkod = $this->input->post('postkod');
        $city = $this->input->post('city');
        $state = $this->input->post('state');
        $country = $this->input->post('country');

        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($name) || !isset($building) || !isset($street) || !isset($postkod) || !isset($city) || !isset($state) || !isset($country)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        $this->db->trans_begin();

        if ($data['error'] == false) {
            $insertValues = array($name, $building, $street, $postkod, $city, $state, $country, date('Y-m-d H:i:s'));
            $sql = "INSERT INTO school_details (venue_name, venue_building, venue_street, venue_postkod, city, state, country, modify_datetime) VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )";
            $this->db->query($sql, $insertValues);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
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
        $venueID = $this->input->post('venueID');
        $name = $this->input->post('venueName');
        $building = $this->input->post('building');
        $street = $this->input->post('street');
        $postkod = $this->input->post('postkod');
        $city = $this->input->post('city');
        $state = $this->input->post('state');
        $country = $this->input->post('country');

        // Assume there is no error
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($venueID) || !isset($building) || !isset($street) || !isset($postkod) || !isset($city) || !isset($state) || !isset($country)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        $this->db->trans_begin();

        if ($data['error'] == false) {
            $insertValues = array($building, $street, $postkod, $city, $state, $country, date('Y-m-d H:i:s'), $venueID);
            $sql = "UPDATE school_details SET venue_building=? ,venue_street=?, venue_postkod=?, city=?, state=?, country=?, modify_datetime=?";
            $sql = $sql . " WHERE venue_id=? ";
            $this->db->query($sql, $insertValues);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
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
     $sql = "UPDATE class_level SET IsActive=2 WHERE id=?";

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
