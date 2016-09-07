<?php

class Instructor extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if ( $this->session->userdata( 'logged_in' ) ) {
            $this->load->model( 'Academy/instructor_model' );
            $this->load->model( 'Model_common' );
        } else {
            redirect( 'login', 'refresh' );
        }
    }

    public function index() {
        show_404();
    }

    public function assignment() {


        $name = $this->input->post( 'searchName' );
        $venue = $this->input->post( 'venue' );

        $data[ 'venue' ] = "";
        $data[ 'name' ] = "";

        if ( isset( $venue ) && $venue != "" ) {
            $data[ 'venue' ] = $venue;
        } else {
            $data[ 'venue' ] = null;
        }
        if ( isset( $name ) && $name != "" ) {
            $data[ 'name' ] = $name;
        } else {
            $data[ 'name' ] = null;
        }


        $data[ 'title' ] = "Class Assignment";
        $data[ 'assignment_list' ] = $this->instructor_model->get_assignments( $data[ 'venue' ], $data[ 'name' ] );
        $data[ 'classes_level_list' ] = $this->instructor_model->get_class_level();
        $data[ 'venue_list' ] = $this->Model_common->get_school_details();
        $data[ 'instructor_list' ] = $this->Model_common->get_instructor_list();

        $data[ 'content' ] = "Academy/assignment";
        $this->load->view( 'templates/main', $data );


    }

    public function create() {
        // Create New student
        echo $this->Model_class->create();
    }

    public function update() {
        // Update student Info
        echo $this->Model_class->update();
    }

    public function delete() {
        // Deactivate Student
        redirect( 'login', 'refresh' );
    }
}
