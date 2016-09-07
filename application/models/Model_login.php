<?php

class Model_login extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    function login($username, $password_string) {


        $sql = "SELECT * FROM employee_list e
 				LEFT JOIN application_login l ON e.id = l.id
 				WHERE login_name = ? AND e.IsActive = ". YES ;

        $query = $this->db->query($sql, $username);
        $result = $query->row_array();
        
        $hashed_password = $result['login_password'];

        //use to hash the password
        //$hash = password_hash($password_string, PASSWORD_DEFAULT);

        if (password_verify($password_string, $hashed_password)) {
            return $query->result();
        } else {
            return false;
        }
    }
}