<?php

class Attendance_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('attendance')->result_array();
    }

    public function add() {
        $data = array(
            'user_id' => $this->input->post('user_id'),
            'check_in_time' => $this->input->post('check_in_time'),
            'check_out_time' => $this->input->post('check_out_time'),
            'date' => $this->input->post('date')
        );

        return $this->db->insert('attendance', $data);
    }

    public function check_in($user_id) {
        $data = array(
            'user_id' => $user_id,
            'check_in_time' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d')
        );

        return $this->db->insert('attendance', $data);
    }

    public function update_status($attendance_id, $check_in_time, $check_out_time) {
        $data = array();
        if ($check_in_time) {
            $data['check_in_time'] = $check_in_time;
        }
        if ($check_out_time) {
            $data['check_out_time'] = $check_out_time;
        }

        if (!empty($data)) {
            $this->db->where('id', $attendance_id);
            return $this->db->update('attendance', $data);
        }

        return false;
    }

    public function get_daily_summary($student_ids) {
        if (empty($student_ids)) {
            return array();
        }

        $this->db->where('date', date('Y-m-d'));
        $this->db->where_in('user_id', $student_ids);
        return $this->db->get('attendance')->result_array();
    }
}
