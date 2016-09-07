<?php

class Model_common extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public static function check_transaction_error( $success, $positive_msg, $negative_msg ) {
        if ( $success === TRUE ) {
            $data[ 'message' ] = $positive_msg;
            $data[ 'error' ] = false;
        } else {
            $data[ 'error' ] = true;
            $data[ 'message' ] = $negative_msg;
        }

        return $data;
    }

    public static function check_field( $field ) {
        if ( !isset( $field ) ) {
            return false;
        }
        if ( $field == "" ) {
            return false;
        }
        return true;
    }

    public function get_gender_list() {
        $sql = "SELECT * FROM _gender_list";;
        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    public function get_status_list() {
        $sql = "SELECT * FROM _status_list";;
        $query = $this->db->query( $sql );
        return $query->result_array();
    }

    public function get_relationship_list() {
        $sql = "SELECT * FROM _relation_list";;
        $query = $this->db->query( $sql );
        return $query->result_array();
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
}
