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

    public function edit($id) {
        $this->_authorize(['admin']);
        $this->load->model('Users_model');

        $data['title'] = 'Edit Attendance';
        $data['attendance'] = $this->Attendance_model->get_attendance_by_id($id);
        $data['all_users'] = $this->Users_model->get_all_users();

        if (!$data['attendance']) {
            show_404();
        }

        $this->load->view('attendance/edit', $data);
    }

    public function update() {
        $this->_authorize(['admin']);
        $id = $this->input->post('id');

        // Combine date and time for full DATETIME format
        $check_in = $this->input->post('date') . ' ' . $this->input->post('check_in_time');
        $check_out = $this->input->post('date') . ' ' . $this->input->post('check_out_time');

        $data = [
            'user_id' => $this->input->post('user_id'),
            'date' => $this->input->post('date'),
            'check_in_time' => !empty($this->input->post('check_in_time')) ? date('Y-m-d H:i:s', strtotime($check_in)) : null,
            'check_out_time' => !empty($this->input->post('check_out_time')) ? date('Y-m-d H:i:s', strtotime($check_out)) : null,
            'status' => $this->input->post('status')
        ];

        $this->Attendance_model->update_attendance($id, $data);
        $this->session->set_flash('success', 'Attendance record updated successfully!');
        $this->response->redirect('/sekolah/attendance/manage');
    }

    public function delete($id) {
        $this->_authorize(['admin']);
        $this->Attendance_model->delete_attendance($id);
        $this->session->set_flash('success', 'Attendance record deleted successfully!');
        $this->response->redirect('/sekolah/attendance/manage');
    }
}
