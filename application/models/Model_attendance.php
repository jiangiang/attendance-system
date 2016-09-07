<?php

class Model_attendance extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    //------------------------------------------------
    //------------Student Attendance------------------
    //------------------------------------------------
    public function std_attendance_list($slot_time, $slot_day, $slot_date)
    {

        // 1st Union - list with payment
        // 2nd Union - list without payment
        // 2nd Union - list all student at that particular time
        // 4th Union - list with IsReplacement

        $slot_day_sql = $slot_day;
        if($slot_day_sql == 7){
            // in sql, sunday is represented as 0
            $slot_day_sql = 0;
        }

        $sql = "SELECT * FROM (
				SELECT
					s.sid AS student_id, s.student_name, lesson_left, lesson_overdue, slot_day, slot_time,
					l.log, e.short_name AS instructor_name, cl.level_name, issue_date,
					a.IsTaken, a.IsReplacement, a.id AS attendance_id,
					ccl.extend_minute, cel.ce_id
				FROM student_list s
				LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN (SELECT student_id, max(issue_date) issue_date FROM student_bill
							GROUP BY student_id ORDER BY issue_date DESC ) b ON b.student_id = s.sid
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN class_level cl ON cl.level_id = c.level_id
				LEFT JOIN employee_list e ON e.id = c.instructor_id
				LEFT JOIN student_attendance a ON a.student_id = s.sid AND a.attend_date = '" . $slot_date . "' AND a.IsVoid = 4
				LEFT JOIN student_log l ON l.student_id = s.sid
				LEFT JOIN ( SELECT student_id, COUNT(id) AS lesson_left FROM student_attendance
							WHERE IsTaken=4 AND IsVoid=4 GROUP BY student_id ) al ON al.student_id = s.sid
				LEFT JOIN ( SELECT student_id, COUNT(id) AS lesson_overdue FROM student_attendance
							WHERE bill_id IS NULL AND IsVoid=4 GROUP BY student_id ) av ON av.student_id = s.sid 
				LEFT JOIN class_replacement_log cel ON cel.student_id = s.sid AND cel.IsTaken = 4
				LEFT JOIN class_cancellation_log ccl ON cel.cancellation_id = ccl.cl_id
				WHERE cs.slot_day = '" . $slot_day . "' AND cs.slot_time = '" . $slot_time . "' AND s.IsActive = 1 
				UNION ALL
				SELECT
						s.sid AS student_id, s.student_name, lesson_left, lesson_overdue, slot_day, slot_time,
						l.log, e.short_name AS instructor_name, cl.level_name, issue_date,
						a.IsTaken, a.IsReplacement, a.id AS attendance_id,
						ccl.extend_minute, cel.ce_id
				FROM student_attendance a
				LEFT JOIN  student_list s ON s.sid = a.student_id
				LEFT JOIN (SELECT student_id, max(issue_date) issue_date FROM student_bill
							GROUP BY student_id ORDER BY issue_date DESC ) b ON b.student_id = s.sid
				LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN class_level cl ON cl.level_id = c.level_id
				LEFT JOIN employee_list e ON e.id = c.instructor_id
				LEFT JOIN student_log l ON l.student_id = s.sid
				LEFT JOIN class_replacement_log cel ON cel.student_id = s.sid AND cel.IsTaken = 4
				LEFT JOIN class_cancellation_log ccl ON cel.cancellation_id = ccl.cl_id
				LEFT JOIN ( SELECT student_id, COUNT(id) AS lesson_left FROM student_attendance
							WHERE IsTaken = 4 AND IsVoid = 4 GROUP BY student_id ) al ON al.student_id = s.sid
				LEFT JOIN ( SELECT student_id, COUNT(id) AS lesson_overdue FROM student_attendance
							WHERE bill_id IS NULL AND IsVoid = 4 GROUP BY student_id ) av ON av.student_id = s.sid
				WHERE a.IsTaken = 3 AND a.attend_time = '" . $slot_time . "' AND a.attend_date = '" . $slot_date . "'AND IsReplacement = 3 AND a.IsVoid = 4
				) a ORDER BY student_name ";

        if ($query = $this->db->query($sql)) {
            // echo $this->db->last_query();
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function search_name($searchText)
    {
        $searchText = urldecode($searchText);

        $sql = "SELECT
					s.sid, s.student_name, student_identity, cs.slot_day, cs.slot_time,
					l.level_name, e.name AS instructor, IFNULL(a.expiry_date, '-') AS expiry_date, IFNULL(a.bill_id, '-') AS bill_id,
					IFNULL(a.last_attend, '-') AS last_attend,
				    IFNULL(attend_times,0) AS attend_times, p.term,
				    CASE WHEN p.term IS NULL THEN (-1*IFNULL(attend_times, 0)) ELSE (p.term - IFNULL(attend_times*1,0)) END AS lesson_left,
				    log.*
				FROM student_list s
				LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
				LEFT JOIN class_list c ON c.id = cs.class_id
				LEFT JOIN class_level l ON l.level_id = c.level_id
				LEFT JOIN employee_list e ON e.id = c.instructor_id
				LEFT JOIN (
					SELECT 
						count(id) AS attend_times, attend_date AS last_attend, student_id, a.bill_id, expiry_date, package_id
					FROM 
						student_attendance a
					LEFT JOIN
						student_bill b ON b.bill_id = a.bill_id   
					WHERE
						IsTaken=3 AND IsVoid=4
					GROUP BY 
						student_id
					ORDER BY
						attend_date DESC
				    ) a ON a.student_id = s.sid
				LEFT JOIN class_package p ON p.package_id = a.package_id 
				LEFT JOIN (SELECT * FROM student_log WHERE IsVoid=4 GROUP BY student_id ORDER BY timestamp DESC LIMIT 1) log ON log.student_id = s.sid 
				WHERE 
					(s.sid LIKE '%" . $searchText . "%' OR s.student_name LIKE '%" . $searchText . "%' OR s.student_identity LIKE '%" . $searchText . "%')
					 AND s.IsActive=1
				GROUP BY s.sid;";

        if ($query = $this->db->query($sql)) {
            return json_encode($query->result_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function std_attendanceUpdate()
    {
        $ticked_attendances = $this->input->post('std_attend');
        $sessionDate = $this->input->post('sessionDate');

        $this->db->trans_begin();
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
                        $sql = "UPDATE student_attendance SET IsTaken = 3 WHERE id =" . $attendance_id;
                        $this->db->query($sql);
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

                    $sql = "SELECT * FROM student_attendance WHERE IsTaken = 4 AND student_id =" . $student_id . " AND IsVoid=4 ORDER BY attend_date LIMIT 1";
                    $query = $this->db->query($sql);
                    if ($query->num_rows() > 0) {
                        $result = $query->row_array();
                        $sql = "UPDATE student_attendance SET IsReplacement = 4, attend_time='" . $sessionTime . "', attend_date = '" . $sessionDate . "', IsTaken=3, timestamp = " . time() . " WHERE id = " . $result['id'];
                        $this->db->query($sql);
                    } else {
                        $sql = "INSERT INTO student_attendance (attend_date, attend_time, student_id, timestamp, IsReplacement, IsTaken) VALUES (?,?,?,?,?,?) ";
                        $insertValues = array($sessionDate, $sessionTime, $student_id, time(), 4, 3);
                        $this->db->query($sql, $insertValues);
                    }
                }

            }
        }
        $this->db->trans_commit();
        if ($this->db->trans_status() === TRUE) {
            $data['message'] = 'IsReplacement recorded!';
            $data['error'] = false;
        } else {
            $data['error'] = true;
            $data['message'] = 'Something wrong!';
        }
        return json_encode($data);
    }

    public function std_attendanceReplaceUpdate()
    {
        $student_id = $this->input->post('id');
        $sessionDate = $this->input->post('sessionDate');
        $sessionTime = $this->input->post('sessionTime');

        // IsReplacement & overdue Attendance
        // If there is still classes have used, it will update the earliest record
        // else insert the attendance record with bill_id = null

        $this->db->trans_begin();

        $sql = "SELECT * FROM student_attendance WHERE IsTaken = 4 AND student_id =" . $student_id . " AND IsVoid=4 ORDER BY attend_date LIMIT 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $sql = "UPDATE student_attendance SET IsReplacement = 3, attend_time='" . $sessionTime . "', attend_date = '" . $sessionDate . "', IsTaken=3, timestamp = " . time() . " WHERE id = " . $result['id'];
            $this->db->query($sql);
        } else {
            $sql = "INSERT INTO student_attendance (attend_date, attend_time, student_id, timestamp, IsReplacement, IsTaken) VALUES (?,?,?,?,?,?) ";
            $insertValues = array($sessionDate, $sessionTime, $student_id, time(), 3, 3);
            $this->db->query($sql, $insertValues);
        }

        $this->db->trans_commit();
        if ($this->db->trans_status() === TRUE) {
            $data['message'] = 'Bill recorded!';
            $data['error'] = false;
        } else {
            $data['error'] = true;
            $data['message'] = 'Something wrong!';
        }

        return json_encode($data);
    }

    public function get_slot_time($slot_day)
    {
        $sql = "SELECT
					schedule_id, slot_time, date_format(slot_time, '%r') AS slot_time_12
				FROM class_list c
				LEFT JOIN class_schedule cs ON cs.class_id = c.id
				WHERE slot_day = ? AND c.IsActive = 1 
				GROUP BY slot_time ORDER BY slot_time ; ";
        $whereClause = array($slot_day);
        if ($query = $this->db->query($sql, $whereClause)) {
            // echo $this->db->last_query();
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function get_next_slot_time($slot_day, $curr_time, $schedule_id)
    {
        if (is_null($schedule_id)) {
            $sql = "SELECT
					schedule_id, slot_time
				FROM 
					class_list c
				LEFT JOIN 
					class_schedule cs ON cs.class_id = c.id
				WHERE 
					slot_day = ? AND slot_time >= ? AND c.IsActive=1
				GROUP BY slot_time 
				ORDER BY slot_time LIMIT 1";
            $whereClause = array($slot_day, $curr_time);
        } else {
            $sql = "SELECT
					schedule_id, slot_time
				FROM 
					class_list c
				LEFT JOIN 
					class_schedule cs ON cs.class_id = c.id
				WHERE 
					schedule_id = ? AND c.IsActive=1
				GROUP BY slot_time 
				ORDER BY slot_time LIMIT 1";
            $whereClause = array($schedule_id);
        }

        if ($query = $this->db->query($sql, $whereClause)) {
            // echo $this->db->last_query();
            return $query->row_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }
    public function class_cancellation_check($current_date, $current_time){
        $check_result = false;

        $sql = "SELECT * FROM class_cancellation_log WHERE cancellation_date = ? AND cancellation_time = ? GROUP BY cancellation_date, cancellation_time";
        $query = $this->db->query($sql, array($current_date, $current_time));
        if($query->num_rows() > 0){
            $check_result = true;
        }

        return $check_result;
    }

    public function class_cancellation(){
        $cancellation_type = $this->input->post('cancellation_type');
        $cancellation_date = $this->input->post('cancellation_date');
        $cancellation_time = $this->input->post('cancellation_time');
        $cancellation_log = $this->input->post('cancellation_log');

        $extend_minute = $this->input->post('extend_minute');

        $session_data = $this->session->userdata('logged_in');
        $pic = $session_data['uid'];

        $this->db->trans_begin();

        $sql = "INSERT INTO class_cancellation_log (cancellation_date, cancellation_time, cancellation_type, cancellation_log, extend_minute, pic, timestamp)VALUES (?,?,?,?,?,?,?)";
        $values = array($cancellation_date, $cancellation_time, $cancellation_type, $cancellation_log, $extend_minute , $pic, time());
        $this->db->query($sql, $values);

        $last_id = $this->db->insert_id();

        // Cancellation 1 -> Extend class during next class
        // Cancellation 2 -> Cancel the current class, attendance not counted
        if ($cancellation_type == 1) {
            $sql = "INSERT INTO class_replacement_log (student_id, cancellation_id, created_time, modify_time)
                    SELECT
                        student_id, ".$last_id.",'".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'
                    FROM
                        student_attendance a
                    WHERE a.attend_time = '" . $cancellation_time . "' AND a.attend_date= '" . $cancellation_date . "' AND IsTaken=3";

            $query = $this->db->query($sql);

        } else if ($cancellation_type == 2) {
            $sql = "UPDATE student_attendance SET IsTaken = 4, timestamp = " . time() . ", pic = '" . $pic . "' WHERE attend_date='" . $cancellation_date . "' AND attend_time='" . $cancellation_time . "' AND bill_id IS NOT NULL";
            $this->db->query($sql);

            $sql = "UPDATE student_attendance SET IsVoid = 3, timestamp = " . time() . ", pic = '" . $pic . "' WHERE attend_date='" . $cancellation_date . "' AND attend_time='" . $cancellation_time . "' AND bill_id IS NULL";
            $this->db->query($sql);
        }

        $this->db->trans_commit();
        if ($this->db->trans_status() === TRUE) {
            $data['message'] = 'Bill recorded!';
            $data['error'] = false;
        } else {
            $data['error'] = true;
            $data['message'] = 'Something wrong!';
        }

        return $data;
    }

    public function class_extension_clear(){
        $sid = $this->input->post('ce_id');

        $this->db->trans_begin();

        $sql = "UPDATE class_replacement_log set IsTaken = 3, modify_time = '".date('Y-m-d H:i:s')."' WHERE ce_id = ".$sid;
        $this->db->query($sql);

        $this->db->trans_commit();
        if ($this->db->trans_status() === TRUE) {
            $data['message'] = 'Bill recorded!';
            $data['error'] = false;
        } else {
            $data['error'] = true;
            $data['message'] = 'Something wrong!';
        }

        return $data;
    }

    //------------------------------------------------
    //------------Staff Attendance------------------
    //------------------------------------------------
    public function staffAttendance_getYear()
    {
        $sql = "SELECT DATE_FORMAT(session_date, '%Y') AS year
				FROM employee_attendance
				GROUP BY YEAR(session_date);";

        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function staffAttendanceDashboard()
    {

        $month = $this->input->post('month');
        $name = $this->input->post('name');
        $payoutStatus = $this->input->post('payoutStatus');
        $year = $this->input->post('searchYear');

        $whereClause = "";
        if (isset($name) && $name != "") {
            $whereClause = "name like '" . $name . "' AND ";
        }
        if (!isset($month) || $month == "") {
            $month = date(4);
        }
        if (!isset($year) || $year == "") {
            $year = date(3);
        }
        if (isset($payoutStatus) && $payoutStatus != "") {
            $payoutStatus = "AND payout_status = '" . $payoutStatus . "'";
        } else {
            $payoutStatus = "";
        }

        $sql = "SELECT name, identity, a.*
				FROM employee_attendance a
				JOIN employee_list e ON e.id = a.employee_id 
				WHERE " . $whereClause . " session_date >= '" . $year . "-" . $month . "-01' AND session_date < '" . $year . "-" . $month . "-31' " . $payoutStatus . " AND IsTaken=1";

        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function staffAttendanceNew()
    {
        $staffID = $this->input->post('staff_uid');
        $session_date = $this->input->post('sessionDate');
        $session_count = $this->input->post('sessionCount');

        if (!isset($staffID) || !isset($session_date) || !isset($session_count)) {
            $data['debug'] = $session_count;
            $data['message'] = 'Make sure you have filled all the details!';
            $data['error'] = true;
            return json_encode($data);
        }

        $sql = "SELECT * FROM employee_attendance WHERE employee_id =" . $staffID . " AND session_date='" . $session_date . "'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data['message'] = 'Duplicate record found!';
            $data['error'] = true;
            return json_encode($data);
        }

        $this->db->trans_begin();

        $sql = "INSERT INTO employee_attendance (employee_id, session_date, session_count) VALUES (" . $staffID . ", '" . $session_date . "', " . $session_count . ")";
        $this->db->query($sql);

        $this->db->trans_commit();
        if ($this->db->trans_status() === TRUE) {
            $data['message'] = 'IsReplacement recorded!';
            $data['error'] = false;
        } else {
            $data['error'] = true;
            $data['message'] = 'Something wrong!';
        }
        return json_encode($data);

    }

    public function staffAttendanceUpdate()
    {

    }

    public function staffAttendanceIsVoid()
    {
        $attendance_id = $this->input->post('activationID');

        $sql = "UPDATE employee_attendance SET IsTaken =2 WHERE attendance_id =" . $attendance_id;
        if ($this->db->query($sql)) {
            $message['error'] = false;
            $message['message'] = "success";
        } else {
            $message['error'] = true;
            $message['message'] = "Activation Fail";
        }
        return json_encode($message);
    }

    public function staffNameSearch($searchText)
    {
        $searchText = urldecode($searchText);

        $sql = "SELECT e.*
				FROM employee_list e
				WHERE (e.name LIKE '%" . $searchText . "%' OR e.identity LIKE '%" . $searchText . "%') AND e.IsActive = 1;";

        if ($query = $this->db->query($sql)) {
            return json_encode($query->result_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

}
