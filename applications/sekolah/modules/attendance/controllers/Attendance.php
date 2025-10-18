<?php

class Attendance extends Controller {

    public function __construct($module_path, $called_method) {
        parent::__construct($module_path, $called_method);
        $this->_auth_check();

        // For viewing/managing all records, we check inside the method.
        // For performing own attendance, we check here.
        if (!in_array($called_method, ['manage', 'reports', 'edit', 'update', 'delete'])) {
            $this->_authorize('attendance.perform');
        }

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
        $this->_authorize('attendance.manage');

        $filters = [
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'user_name' => $this->input->get('user_name')
        ];

        $page = (int)($this->input->get('page', 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $data['all_attendance'] = $this->Attendance_model->get_all_attendance_with_users($limit, $offset, $filters);
        $total_records = $this->Attendance_model->get_total_all_attendance_count($filters);
        
        $data['title'] = 'Manage Attendance';
        $data['filters'] = $filters;
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

        // Trigger notification if marked absent or late
        if ($data['status'] === 'absent' || $data['status'] === 'late') {
            $this->load->model('Student_Parent_model');
            $this->load->model('Notification_model');
            $this->load->model('Users_model');

            $student = $this->Users_model->get_user_by_id($data['user_id']);
            $parents = $this->Student_Parent_model->get_parents_for_student($data['user_id']);

            foreach ($parents as $parent) {
                $message = "Your child, " . $student['nama'] . ", was marked " . $data['status'] . " on " . date('d M Y', strtotime($data['date'])) . ".";
                $this->Notification_model->create_notification($parent['id'], $message);
            }
        }

        $this->session->set_flash('success', 'Attendance record updated successfully!');
        $this->response->redirect('/sekolah/attendance/manage');
    }

    public function delete($id) {
        $this->_authorize(['admin']);
        $this->Attendance_model->delete_attendance($id);
        $this->session->set_flash('success', 'Attendance record deleted successfully!');
        $this->response->redirect('/sekolah/attendance/manage');
    }

    public function reports() {
        $this->_authorize('attendance.manage'); // Reuse the same permission for now

        $year = $this->input->get('year', date('Y'));
        $month = $this->input->get('month', date('m'));

        $data['title'] = 'Attendance Reports';
        $data['year'] = $year;
        $data['month'] = $month;
        $data['report_data'] = $this->Attendance_model->get_attendance_summary_report($year, $month);

        $this->load->view('attendance/reports', $data);
    }

    public function teacher_manage_attendance() {
        $this->_authorize('attendance.teacher_manage');
        $this->load->model('Auth_model');
        $this->load->model('Users_model');
        $this->load->model('Mata_pelajaran_model');

        $teacher_id = $this->session->get('user_id');
        $data['mata_pelajaran_taught'] = $this->Mata_pelajaran_model->get_mata_pelajaran_by_guru_id($teacher_id);

        $selected_mapel_id = $this->input->get('mapel_id');
        $selected_date = $this->input->get('date', date('Y-m-d'));

        $data['selected_mapel_id'] = $selected_mapel_id;
        $data['selected_date'] = $selected_date;

        $data['students_attendance'] = [];
        if ($selected_mapel_id) {
            $students = $this->Mata_pelajaran_model->get_students_by_mapel_id($selected_mapel_id);
            foreach ($students as $student) {
                $attendance = $this->Attendance_model->get_attendance_by_date_and_user($selected_date, $student['id']);
                $data['students_attendance'][] = [
                    'student_id' => $student['id'],
                    'student_nama' => $student['nama'],
                    'attendance_status' => $attendance['status'] ?? 'absent', // Default to absent
                    'attendance_id' => $attendance['id'] ?? null
                ];
            }
        }

        $data['title'] = 'Manage Student Attendance';
        $this->load->view('attendance/teacher_manage_attendance', $data);
    }

    public function update_student_attendance_status() {
        $this->_authorize('attendance.teacher_manage');
        $this->load->model('Attendance_model');

        $attendance_id = $this->input->post('attendance_id');
        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $status = $this->input->post('status');

        $this->Attendance_model->update_student_attendance($attendance_id, $user_id, $date, $status);

        $this->session->set_flash('success', 'Student attendance updated successfully!');
        $this->response->redirect('/sekolah/attendance/teacher_manage_attendance?mapel_id=' . $this->input->post('mapel_id') . '&date=' . $date);
    }
}
