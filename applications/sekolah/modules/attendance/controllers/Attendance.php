<?php

class Attendance extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_auth_check();
        $this->load->model('Attendance_model');
    }

    public function index() {
        $user_id = $this->session->get('user_id');
        $data['todays_attendance'] = $this->Attendance_model->get_todays_attendance($user_id);
        $data['attendance_history'] = $this->Attendance_model->get_user_attendance_history($user_id, 10);
        $data['title'] = 'My Attendance';
        $this->load->view('attendance/index', $data);
    }

    public function log() {
        $user_id = $this->session->get('user_id');
        $todays_attendance = $this->Attendance_model->get_todays_attendance($user_id);

        if (!$todays_attendance) {
            // User has not checked in today, so check them in.
            $this->Attendance_model->check_in($user_id);
            $this->session->set_flash('success', 'You have successfully checked in!');
        } else if ($todays_attendance && !$todays_attendance['check_out_time']) {
            // User has checked in but not checked out, so check them out.
            $this->Attendance_model->check_out($todays_attendance['id']);
            $this->session->set_flash('success', 'You have successfully checked out!');
        } else {
            // User has already checked in and out for the day.
            $this->session->set_flash('warning', 'You have already completed your attendance for today.');
        }

        $this->response->redirect('/sekolah/attendance');
    }

    public function manage() {
        $this->_authorize(['admin']);

        $page = (int)($this->input->get('page', 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $data['all_attendance'] = $this->Attendance_model->get_all_attendance_with_users($limit, $offset);
        $total_records = $this->Attendance_model->get_total_all_attendance_count();
        
        $data['title'] = 'Manage Attendance';
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($total_records / $limit);

        $this->load->view('attendance/manage', $data);
    }
}
