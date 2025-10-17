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
}
