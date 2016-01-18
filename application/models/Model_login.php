<?php
class Model_login extends CI_Model {
	public function __construct() {
		$this->load->database ();
	}
	function login($username, $password) {
		$sql = "SELECT * FROM employee_info e
 			LEFT JOIN employee_login l ON e.id = l.id
 			WHERE login_name = ? AND login_password = ? AND e.status='A'";
		
		$whereClause = array (
				$username,
				$password 
		);
   
 	$query = $this->db->query ( $sql, $whereClause );
 	
   // $this -> db -> where('password', MD5($password));

   if ($query->num_rows () == 1) {
			return $query->result ();
		} else {
			return false;
		}
	}
}