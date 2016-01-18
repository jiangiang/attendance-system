<?php
class Model_staff extends CI_Model {
	public function __construct() {
		$this->load->database ();
	}
	public function show_staff_active() {
		$sql = "select e.*, t.type_name, suggest_salary from employee_info e
				LEFT JOIN employee_type t ON t.type_id = e.employee_type_id 
				 WHERE e.status='A'";
		if ($query = $this->db->query ( $sql )) {
			
			// print_r($query->result_array());
			return $query->result_array ();
		} else {
			$error = $this->db->error ();
			echo "error <br>";
			// echo $error;
		}
	}
	public function show_staff_inactive() {
		$sql = "select e.*, t.type_name from employee_info e
				LEFT JOIN employee_type t ON t.type_id = e.employee_type_id 
				 WHERE e.status='I'";
		if ($query = $this->db->query ( $sql )) {
			return $query->result_array ();
		} else {
			$error = $this->db->error ();
			echo "error <br>";
		}
	}
	
	// Model to get the levels available in the system
	public function get_staff_type() {
		$sql = "SELECT * FROM employee_type";
		if ($query = $this->db->query ( $sql )) {
			return $query->result_array ();
		} else {
			$error = $this->db->error ();
			echo "error <br>";
		}
	}
	public function get_staff_info($staff_id) {
		$sql = "select e.*, l.login_name, t.type_id from employee_info e
				LEFT JOIN employee_type t ON t.type_id = e.employee_type_id 
				LEFT JOIN employee_login l ON l.id = e.id
				WHERE e.id=?";
		$where_clause = array (
				$staff_id 
		);
		if ($query = $this->db->query ( $sql, $where_clause )) {
			return json_encode ( $query->row_array () );
		} else {
			$error = $this->db->error ();
			echo "error <br>";
		}
	}
	public function staff_new() {
		$staffName = $this->input->post ( 'staffName' );
		$staffShortName = $this->input->post ( 'staffShortName' );
		$staffIdentity = $this->input->post ( 'staffIdentity' );
		$staffGender = $this->input->post ( 'staffGender' );
		$staffContact = $this->input->post ( 'staffContact' );
		$staffEmail = $this->input->post ( 'staffEmail' );
		$addr_building = $this->input->post ( 'staffAddr1' );
		$addr_street = $this->input->post ( 'staffAddr2' );
		$addr_postkod = $this->input->post ( 'Postcode' );
		$addr_city = $this->input->post ( 'City' );
		$addr_state = $this->input->post ( 'State' );
		$addr_country = $this->input->post ( 'Country' );
		$staffType = $this->input->post ( 'staffType' );
		$loginName = $this->input->post ( 'loginName' );
		$loginPwd = $this->input->post ( 'loginPassword' );
		
		// Assume there is no error
		$data ['error'] = false;
		
		// validation check if user use some unexpected method to submit without fulfil these field.
		if (empty ( $staffName ) || empty ( $staffGender ) || empty ( $staffContact )) {
			$data ['error'] = true;
			$data ['message'] = "Please check your fill again.";
			return json_encode ( $data );
		}
		
		$this->db->trans_begin ();
		
		if ($data ['error'] == false) {
			$insertValues = array (
					$staffName,
					$staffShortName,
					$staffIdentity,
					$staffGender,
					$staffContact,
					$staffEmail,
					$addr_building,
					$addr_street,
					$addr_postkod,
					$addr_city,
					$addr_state,
					$addr_country,
					$staffType,
					'A' 
			);
			$sql = "INSERT INTO employee_info (name, short_name, identity, gender, contact, email, addr_building, addr_street, addr_postkod, addr_city, addr_state, addr_country, employee_type_id, status) VALUES";
			$sql = $sql . " (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$this->db->query ( $sql, $insertValues );
			
			if(!empty($loginName) && !empty($loginPwd)){
				$sql = "SELECT id FROM employee_info WHERE identity ='".$staffIdentity."'";
				$query = $this->db->query ( $sql );
				$query_result = $query ->row_array();
				
				$insertValues = array (
						$query_result['id'],
						$loginName,
						$loginPwd
				);
				$sql = "INSERT INTO employee_login (id, login_name, login_password) VALUES (?,?,?)";
				$this->db->query ( $sql, $insertValues );
			}
			
			$this->db->trans_commit ();
			if($this->db->trans_status() === TRUE){
				$data ['message'] = 'Staff Created!';
				$data ['error'] = false;
			}
			else{
				$data ['error'] = true;
				$data ['message'] = 'Something wrong!';
			}
			
		}
		return json_encode ( $data );
		// End of New student registar
	}
	public function staff_update() {
		$staffID = $this->input->post ( 'staffID' );
		$staffName = $this->input->post ( 'staffName' );
		$staffShortName = $this->input->post ( 'staffShortName' );
		$staffIdentity = $this->input->post ( 'staffIdentity' );
		$staffGender = $this->input->post ( 'staffGender' );
		$staffContact = $this->input->post ( 'staffContact' );
		$staffEmail = $this->input->post ( 'staffEmail' );
		$addr_building = $this->input->post ( 'staffAddr1' );
		$addr_street = $this->input->post ( 'staffAddr2' );
		$addr_postkod = $this->input->post ( 'Postcode' );
		$addr_city = $this->input->post ( 'City' );
		$addr_state = $this->input->post ( 'State' );
		$addr_country = $this->input->post ( 'Country' );
		$staffType = $this->input->post ( 'staffType' );
		$loginName = $this->input->post ( 'loginName' );
		$loginPwd = $this->input->post ( 'loginPassword' );
		
		// Assume there is no error
		$data ['error'] = false;
		
		// validation check if user use some unexpected method to submit without fulfil these field.
		if (empty ( $staffID ) || empty ( $staffName ) || empty ( $staffContact )) {
			$data ['error'] = true;
			$data ['message'] = "Please check your fill again.";
			
			return json_encode ( $data );
		}
		
		$this->db->trans_begin ();
		
		if ($data ['error'] == false) {
			$insertValues = array (
					$staffShortName,
					$staffContact,
					$staffEmail,
					$addr_building,
					$addr_street,
					$addr_postkod,
					$addr_city,
					$addr_state,
					$addr_country,
					$staffType,
					$staffID,
					$staffName
			);
			
			$sql = "UPDATE employee_info SET short_name=?, contact=?, email=?, addr_building=?, addr_street=?, addr_postkod=?, addr_city=?, addr_state=?, addr_country=?, employee_type_id=? ";
			$sql = $sql . " WHERE id=? AND name=?";
			$this->db->query ( $sql, $insertValues );
			
			if(!empty($loginName) && !empty($loginPwd)){
				$sql = "SELECT id FROM employee_login WHERE id =". $staffID ;
				$query = $this->db->query ( $sql );
				if($query -> num_rows() > 0 ){
					$insertValues = array (
							$loginPwd,
							$staffID,
					);
					$sql = "UPDATE employee_login SET login_password=? WHERE id=?";
					$this->db->query ( $sql, $insertValues );
				}else{
					$insertValues = array (
							$staffID,
							$loginName,
							$loginPwd
					);
					$sql = "INSERT INTO employee_login (id, login_name, login_password) VALUES (?,?,?)";
					$this->db->query ( $sql, $insertValues );
				}
				
				
			}
			
			
			$this->db->trans_commit ();
			if($this->db->trans_status() === TRUE){
				$data ['message'] = 'Staff Created!';
				$data ['error'] = false;
			}
			else{
				$data ['error'] = true;
				$data ['message'] = 'Something wrong!';
			}
			
		}
		return json_encode ( $data );
		// End of New student registar
	}
	public function staff_deactivate() {
		// Activate member
		$id = $this->input->post ( 'activationID' );
		$name = $this->input->post ( 'activationName' );
		$data = array (
				$id,
				$name 
		);
		$sql = "UPDATE employee_info SET status='I' WHERE id=? AND name=?";
		
		if ($this->db->query ( $sql, $data )) {
			$message ['error'] = false;
			$message ['message'] = "success";
			$message ['debug'] = $name;
		} else {
			$message ['error'] = true;
			$message ['message'] = "Activation Fail";
		}
		return json_encode ( $message );
	}
	public function staff_activate($userid = NULL) {
		// Activate member
		$id = $this->input->post ( 'activationID' );
		$name = $this->input->post ( 'activationName' );
		$data = array (
				$id,
				$name 
		);
		$sql = "UPDATE employee_info SET status='A' WHERE id=? AND name=?";
		
		if ($this->db->query ( $sql, $data )) {
			$message ['error'] = false;
			$message ['message'] = "success";
			// $message['sql']=$this->db->last_query();
		} else {
			$message ['error'] = true;
			$message ['message'] = "Activation Fail";
		}
		return json_encode ( $message );
	}
	public function checkID($ID) {
		$sql = "SELECT identity FROM employee_info WHERE identity=?";
		if ($query = $this->db->query ( $sql, array (
				$ID 
		) )) {
			if ($query->num_rows () > 0) {
				$message ['message'] = "Someone is using the same IC too...";
				$message ['record'] = true;
			} else {
				$message ['record'] = false;
			}
			$message ['error'] = false;
		} else {
			$message ['error'] = true;
			$message ['message'] = "Activation Fail";
		}
		return json_encode ( $message );
	}
}

