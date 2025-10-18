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

    public function check_in() {
        $user_id = $this->input->post('user_id');
        if ($user_id) {
            $this->attendance_model->check_in($user_id);
            // Respond with a success message
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success')));
        } else {
            // Respond with an error message
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array('status' => 'error', 'message' => 'User ID is required')));
        }
    }

    public function update_status() {
        $attendance_id = $this->input->post('attendance_id');
        $check_in_time = $this->input->post('check_in_time');
        $check_out_time = $this->input->post('check_out_time');

        if ($attendance_id) {
            $this->attendance_model->update_status($attendance_id, $check_in_time, $check_out_time);
            // Respond with a success message
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success')));
        } else {
            // Respond with an error message
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Attendance ID is required')));
        }
    }

    public function daily_summary() {
        // Assuming the user is logged in and has a class_id
        $class_id = $this->session->userdata('class_id');

        if ($class_id) {
            $this->load->model('student_model');
            $students = $this->student_model->get_students_by_class($class_id);
            $student_ids = array_column($students, 'id');

            $data['attendances'] = $this->attendance_model->get_daily_summary($student_ids);
            $this->load->view('attendance/daily_summary', $data);
        } else {
            // Show an error or redirect
            show_error('You are not assigned to a class.');
        }
    }
}
