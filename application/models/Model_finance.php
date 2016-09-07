<?php

class Model_finance extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function list_student_bill() {
        $month = $this->input->post('searchMonth');
        $name = $this->input->post('searchName');
        $billType = $this->input->post('billType');

        $whereClause = "";
        if (isset($month) && $month != "") {
            $year = date(1);
            $whereClause = $whereClause . "(issue_date BETWEEN '" . $year . "-" . $month . "-01' AND '" . $year . "-" . $month . "-31') AND ";
        } else {
            $year = date(1);
            $month = date(2);
            $whereClause = $whereClause . "(issue_date BETWEEN '" . $year . "-" . $month . "-01' AND '" . $year . "-" . $month . "-31') AND ";
        }
        if (isset($name) && $name != "") {
            $whereClause = $whereClause . "s.student_name like '%" . strtoupper($name) . "%' AND ";
        }
        if (isset($billType) && $billType != "") {
            if ($billType == 2) {
                $whereClause = $whereClause . "(receipt_no is null OR receipt_no ='') AND ";
            } else {
                $whereClause = $whereClause . "(receipt_no is NOT null AND receipt_no <>'') AND ";
            }
        }

        $sql = "SELECT b.bill_id, b.receipt_no, s.student_name, b.issue_date, b.expiry_date, p.price, p.package_name , p.term
				FROM student_bill b		
				LEFT JOIN student_list s ON s.sid = b.student_id
				LEFT JOIN class_package p ON p.package_id = b.package_id
				WHERE " . $whereClause . " b.IsValid=1 
				ORDER BY bill_id DESC LIMIT 400
				;";

        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function get_package() {
        $sql = "SELECT * FROM class_package ";

        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function search_name($searchText) {
        $searchText = urldecode($searchText);
        $sql = "SELECT sid, student_name, student_identity
				FROM student_list 
				WHERE (sid LIKE '%" . $searchText . "%' OR student_name LIKE '%" . $searchText . "%' OR student_identity LIKE '%" . $searchText . "%') AND student_status = 1 ";

        if ($query = $this->db->query($sql)) {
            return json_encode($query->result_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function student_bill_pay() {
        $studentID = $this->input->post('id');
        $studentName = $this->input->post('stdName');
        $studentIdentity = $this->input->post('stdID');
        $studentReceipt = $this->input->post('stdReceipt');
        $packageID = $this->input->post('billPackage');
        $custom_attend_date = $this->input->post('slot_date');
        $custom_attend_time = $this->input->post('slot_time');
        $payment_date = $this->input->post('payment_date');

        $session_data = $this -> session -> userdata('logged_in');

        // Assume there is no error
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($studentName) || !isset($studentID) || !isset($studentIdentity) || !isset($packageID) || $studentID == "") {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }
        if (!isset($studentReceipt)) {
            $studentReceipt = "";
        }

        if ($data['error'] == false) {

            // =======================================================
            // STEP 1: Insert payment record to table student_bill
            // STEP 2: Get back the bill_id
            // STEP 3: Check if the class allow to custom date or not
            // STEP 4: Insert attendance record to attendance table
            // =======================================================

            $query = $this->db->query('SELECT * FROM class_package WHERE package_id=' . $packageID);
            $result = $query->row_array();

            $issue_date = $payment_date;
            $expiry_date = date('Y-m-d', strtotime(date("Y-m-d", strtotime($issue_date)) . " + " . $result['expiry_month'] . " month"));

            $package_term = $result['term'];
            $allow_custom_date = $result['allow_custom_date'];
            $attendance_required = $result['attendance_required'];

            // unique ID is regenrated by multiplication of random number and timestamp
            $timestamp = time();
            $modify_time = date("Y-m-d H:i:s");
            $unique_id = mt_rand(1001, 999999) * $timestamp % 10000000000;

            // find the overdue classes
            $sqlStr = " SELECT count(id) AS overdue_count FROM student_attendance WHERE bill_id IS NULL AND student_id = " . $studentID;
            $query = $this->db->query($sqlStr);
            $result = $query->row_array();
            $overdue_count = $result['overdue_count'];

            if ($overdue_count > $package_term) {
                $overdue_update_count = $package_term;
            } else {
                $overdue_update_count = $overdue_count;
            }

            $new_attendance_count = $package_term - $overdue_update_count;

            // =======================================
            // put the attend date and time into array
            // =======================================
            if ($allow_custom_date == 4 && $attendance_required == 3) {

                $sql = "SELECT s.*,cs.* FROM student_list s
						LEFT JOIN class_schedule cs ON cs.schedule_id = s.schedule_id
						LEFT JOIN class_list c ON c.id = cs.class_id
						WHERE s.sid = " . $studentID;

                $query = $this->db->query($sql);
                $result = $query->row_array();
                
                if ($query->num_rows() < 1) {
                    $this->db->trans_rollback();
                    $data['error'] = true;
                    $data['message'] = 'Data integrity problem!';
                    return json_encode($data);
                }
                if (is_null($result['slot_time']) || is_null($result['slot_day'])) {
                    $this->db->trans_rollback();
                    $data['error'] = true;
                    $data['message'] = 'Please assign a class to this student.';
                    return json_encode($data);
                }
                $slot_time = $result['slot_time'];

                $slot_day = intval($result['slot_day']) - 1;
                $dayOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                $day = $dayOfWeek[$slot_day];
                $first_attend_date = date('Y-m-d', strtotime('last ' . $day));

                $attend_date = array();
                $attend_time = array();
                for ($i = 1; $i <= $new_attendance_count; $i++) {

                    $attend_date[] = date("Y-m-d", strtotime($first_attend_date . " + " . (7 * $i) . " days"));
                    $attend_time[] = $slot_time;
                }
            } else if ($allow_custom_date == 3 && $attendance_required == 3) {

                for ($i = 0; $i < $new_attendance_count; $i++) {
                    $attend_date[] = $custom_attend_date[$i];
                    $attend_time[] = $custom_attend_time[$i];

                }
            }

            $this->db->trans_begin();

            // Insert into student_bill
            $sqlStr = "INSERT INTO student_bill (issue_date, expiry_date, student_id, package_id, receipt_no, modifytime, unique_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertValues = array($issue_date, $expiry_date, intval($studentID), $packageID, $studentReceipt, $modify_time, $unique_id);
            $this->db->query($sqlStr, $insertValues);

            if ($attendance_required == 3) {

                $bill_id = $this->db->insert_id();
                $sqlStr = " UPDATE student_attendance SET bill_id = " . $bill_id . " 
							WHERE student_id = " . $studentID . " AND bill_id IS NULL
							ORDER BY id LIMIT " . $overdue_update_count;
                $this->db->query($sqlStr);

                for ($i = 0; $i < $new_attendance_count; $i++) {
                    $insertValues = array($attend_date[$i], $attend_time[$i], $studentID, $bill_id, $modify_time, $session_data['uid']);
                    $sqlStr = "INSERT INTO student_attendance (attend_date, attend_time, student_id, bill_id, modifytime, pic) VALUES (?, ?, ?, ?, ?, ?)";
                    $this->db->query($sqlStr, $insertValues);
                }
            }

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
                $data['message'] = 'Bill recorded!';
                $data['error'] = false;
            } else {
                $this->db->trans_rollback();
                $data['error'] = true;
                $data['message'] = 'Something wrong!';
            }
        }
        return json_encode($data);
    }

    public function tuitionFeeUpdate() {
        $billID = $this->input->post('billID');
        $receipt_no = $this->input->post('stdReceipt');

        if (!isset($studentReceipt)) {
            $studentReceipt = "";
        }
        // Assume there is no error
        $data['error'] = false;

        // validation check if user use some unexpected method to submit without fulfil these field.
        if (!isset($billID)) {
            $data['error'] = true;
            $data['message'] = "Please check your fill again.";
            return json_encode($data);
        }

        if ($data['error'] == false) {
            $this->db->trans_begin();
            $sqlStr = " UPDATE student_bill SET receipt_no='" . $receipt_no . "'
						WHERE bill_id = " . $billID;
            $query = $this->db->query($sqlStr);

            $this->db->trans_commit();
            if ($this->db->trans_status() === TRUE) {
                $data['message'] = 'Bill Updated!';
                $data['error'] = false;
            } else {
                $data['error'] = true;
                $data['message'] = 'Something wrong!';
            }
        }
        return json_encode($data);
    }

    public function getBillInfo($billID) {
        $sql = "SELECT * 
				FROM student_bill b
				JOIN student_list s ON b.std_id = s.sid
				WHERE bill_id = " . $billID;
        if ($query = $this->db->query($sql)) {
            return json_encode($query->row_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function voidBill() {
        // Activate member
        $id = $this->input->post('activationID');
        $data = array($id);

        $this->db->trans_begin();

        $sql = "UPDATE student_bill SET IsValid=2 WHERE bill_id=?";
        $this->db->query($sql, $data);

        $sql = "UPDATE student_attendance SET void=1 WHERE bill_id=?";
        $this->db->query($sql, $data);

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

    public function package_check($package_id) {
        $sql = "SELECT * FROM class_package WHERE package_id = " . $package_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    // Employee Payroll
    public function employee_payroll() {

        $year = $this->input->post('searchYear');
        $month = $this->input->post('month');
        $name = $this->input->post('name');
        $payoutStatus = $this->input->post('HasPaid');
        $salaryStatus = $this->input->post('salary_status');

        if (!isset($month) || $month == "") {
            $month = date(2);
        }
        if (!isset($year) || $year == "") {
            $year = date(1);
        }
        $whereClause = "";
        if (isset($name) && $name != "") {
            $whereClause = " AND e.name like '" . $name . "'";
        }
        if (!isset($payoutStatus) || $payoutStatus == "") {
            $payoutStatus = 2;
        }
        if (!isset($salaryStatus) || $salaryStatus == "") {
            $salaryStatus = 2;
        }

        if ($salaryStatus == 2) {

            $sql = "SELECT sum(session_count) as session_count, sum(session_count * suggest_salary) as salary, e.* , '" . $month . "/" . $year . "' as month, 'Unprocessed' as salary_status, 'N/A' as taken
					FROM employee_attendance a
					JOIN employee_list e ON e.id = employee_id
					JOIN employee_type t ON t.type_id = e.employee_type_id
					WHERE session_date >= '" . $year . "-" . $month . "-01' AND session_date < '" . $year . "-" . $month . "-31' AND HasPaid=2 " . $whereClause . " AND IsFulltime=2
					GROUP BY employee_id
					UNION
					SELECT 'N/A' as session_count, suggest_salary, e.*, '" . $month . "/" . $year . "' as month, 'Unprocessed' as HasPaid, 'N/A' as taken
					FROM employee_list e
					LEFT JOIN employee_salary s ON e.id = s.employee_id AND (salary_date >= '" . $year . "-" . $month . "-01' AND salary_date < '" . $year . "-" . $month . "-31')
					LEFT JOIN employee_type t ON t.type_id = e.employee_type_id
					WHERE session_count IS NULL AND IsFulltime=1 " . $whereClause . "
				";
        } else {
            $sql = "SELECT i.id, i.name, i.identity, s.salary_basic, (salary_basic + adjustment) AS salary, taken, session_count, DATE_FORMAT(salary_date, '%m/%Y') AS month, 'Processed' AS salary_status
					FROM employee_salary s
					JOIN employee_list i ON s.employee_id = i.id
					WHERE taken ='" . $payoutStatus . "'
				";
        }
        if ($query = $this->db->query($sql)) {
            return $query->result_array();
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function employee_details($uid, $year, $month) {

        $sql = "SELECT * FROM (
					SELECT session_count, sum(session_count * suggest_salary) AS salary, e.* 
					FROM employee_attendance a
					JOIN employee_list e ON e.id = employee_id
					JOIN employee_type t ON t.type_id = e.employee_type_id
					WHERE session_date >= '" . $year . "-" . $month . "-01' AND session_date < '" . $year . "-" . $month . "-31' AND HasPaid=2 AND IsFulltime=2
					GROUP BY employee_id
					UNION
					SELECT 0 AS session_count, suggest_salary, e.*
					FROM employee_list e
					LEFT JOIN employee_salary s ON e.id = s.employee_id AND (salary_date >= '" . $year . "-" . $month . "-01' AND salary_date < '" . $year . "-" . $month . "-31')
					LEFT JOIN employee_type t ON t.type_id = e.employee_type_id
					WHERE session_count IS NULL AND IsFulltime=1) a
				WHERE id=" . $uid;

        if ($query = $this->db->query($sql)) {
            return json_encode($query->row_array());
        } else {
            $error = $this->db->error();
            echo "error <br>";
        }
    }

    public function employee_payroll_update() {
        $sessionData = $this->session->userdata('logged_in');

        $uid = $this->input->post('uid');
        $session_count = $this->input->post('sessionCount');
        $salary_basic = $this->input->post('defaultSalary');
        $adjustment = $this->input->post('adjustmentSalary');
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        $remark = $this->input->post('remark');

        $date = $year . "-" . $month . "-01";

        $proccessed_by = $sessionData['uid'];
        $approved_by = $sessionData['uid'];

        if (!isset($uid) || !isset($salary_basic) || !isset($year) || !isset($month) || !isset($session_count)) {
            $data['debug'] = $year;
            $data['error'] = true;
            $data['message'] = "please check the fill again";
            return json_encode($data);
        }

        $this->db->trans_begin();

        $sql = "INSERT INTO employee_salary (employee_id, session_count, salary_basic, adjustment, salary_date, remark, processed_by, approved_by)";
        $sql = $sql . " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $values = array($uid, $session_count, $salary_basic, $adjustment, $date, $remark, $proccessed_by, $approved_by);
        $this->db->query($sql, $values);

        $sql = "UPDATE employee_attendance SET HasPaid=1 WHERE employee_id = " . $uid . " AND session_date >= '" . $year . "-" . $month . "-01' AND session_date < '" . $year . "-" . $month . "-31' ";
        $this->db->query($sql);

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

}
