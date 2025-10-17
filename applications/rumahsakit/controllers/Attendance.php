<?php

class Attendance extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('attendance_model');
    }

    public function index() {
        $data['attendances'] = $this->attendance_model->get_all();
        $this->load->view('attendance/index', $data);
    }

    public function add() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_id', 'User ID', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('attendance/add');
        } else {
            $this->attendance_model->add();
            redirect('attendance');
        }
    }
}
