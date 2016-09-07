<?php
date_default_timezone_set( 'Asia/Kuala_Lumpur' );

class Student extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if ( $this->session->userdata( 'logged_in' ) ) {
            $this->load->model( 'Student/Student_model' );
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function index() {

        $time = $this->input->post( 'searchTime' );
        $day = $this->input->post( 'searchDay' );
        $name = $this->input->post( 'searchName' );

        $data[ 'day' ] = "";
        $data[ 'name' ] = "";
        $data[ 'time' ] = "";

        if ( isset( $time ) && $time != "" ) {
            $data[ 'time' ] = $time;
        }
        if ( isset( $day ) && $day != "" ) {
            $data[ 'day' ] = $day;
        }
        if ( isset( $name ) && $name != "" ) {
            $data[ 'name' ] = $name;
        }

        $data[ 'title' ] = "Student Summary";
        $data[ 'std_active_rows' ] = $this->Student_model->get_student_lists();
        $data[ 'std_inactive_rows' ] = $this->Student_model->show_student_inactive();
        $data[ 'course_levels' ] = $this->Student_model->get_class_level();
        $data[ 'school_details' ] = $this->Student_model->get_school_details();

        $data[ 'content' ] = "student/student_home";
        $this->load->view( 'templates/main', $data );
    }



    public function details( $uid ) {
        if ( $this->session->userdata( 'logged_in' ) ) {

            $data[ 'title' ] = "Student Summary";
            $data[ 'student_details' ] = $this->Student_model->get_student_details( $uid );
            $data[ 'student_details_payment' ] = $this->Student_model->get_student_details_class( $uid );

            $data[ 'content' ] = "student/student_details";
            $this->load->view( 'templates/main_lite', $data );

        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function payment_history( $uid ) {
        if ( $this->session->userdata( 'logged_in' ) ) {

            $data[ 'title' ] = "Payment History";
            $data[ 'payment_history' ] = $this->Student_model->get_student_payment_details( $uid );

            $data[ 'content' ] = "student/payment_history";
            $this->load->view( 'templates/main_lite', $data );
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function attendance_history( $uid ) {
        if ( $this->session->userdata( 'logged_in' ) ) {

            $data[ 'title' ] = "Attendance History";
            $data[ 'payment_history' ] = $this->Student_model->get_student_attendance_history( $uid );

            $data[ 'content' ] = "student/attendance_history";
            $this->load->view( 'templates/main_lite', $data );

        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function ajax_slot_capacity( $slot_day, $level, $venue_id ) {
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->ajax_slot_capacity( $slot_day, $level, $venue_id );
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function ajax_student_details( $std_id ) {
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->ajax_student_details( $std_id );
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function stdNew() {
        // Create New student
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->student_new();
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function stdUpdate() {
        // Update student Info
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->student_update();
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function stdDeactivate() {
        // Deactivate Student
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->student_deactivate();
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function stdActivate() {
        // Deactivate Student
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->student_activate();
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function checkID( $ID ) {
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->checkID( $ID );
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    // ===========================
    // Student Log
    // ===========================
    public function student_log() {
        if ( $this->session->userdata( 'logged_in' ) ) {

            $name = $this->input->post( 'searchName' );

            $data[ 'name' ] = "";

            if ( isset( $name ) && $name != "" ) {
                $data[ 'name' ] = $name;
            }

            $data[ 'title' ] = "Student Log";
            $data[ 'student_logs' ] = $this->Student_model->list_student_log();

            $data[ 'content' ] = "student/student_log";
            $this->load->view( 'templates/main', $data );

        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function searchName( $searchText ) {
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->search_name( $searchText );
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function student_log_new() {
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->student_log_new();
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function student_log_void() {
        if ( $this->session->userdata( 'logged_in' ) ) {
            echo $this->Student_model->student_log_void();
        } else {
            redirect( 'login', 'refresh' );
        }
    }

}
