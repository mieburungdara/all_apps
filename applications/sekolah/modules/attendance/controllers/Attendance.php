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
}
