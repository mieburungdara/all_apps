<?php

class Attendance_model extends Model {

    public function get_todays_attendance($user_id) {
        $today = date('Y-m-d');
        return $this->db->get('attendance', [
            ['user_id', '=', $user_id],
            ['date', '=', $today]
        ], true);
    }

    public function check_in($user_id) {
        $data = [
            'user_id' => $user_id,
            'check_in_time' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d'),
            'status' => 'present' // Or you can have logic for 'late'
        ];
        return $this->db->insert('attendance', $data);
    }

    public function check_out($attendance_id) {
        $data = [
            'check_out_time' => date('Y-m-d H:i:s')
        ];
        return $this->db->update('attendance', $data, [['id', '=', $attendance_id]]);
    }

    public function get_user_attendance_history($user_id, $limit = 20, $offset = 0) {
        // This is a simplified version. A real one might need JOINs.
        return $this->db->get('attendance', [['user_id', '=', $user_id]], false, $limit, $offset);
    }

    public function get_all_attendance_with_users($limit = 20, $offset = 0) {
        $sql = "SELECT a.*, u.nama as user_name FROM attendance a JOIN users u ON a.user_id = u.id ORDER BY a.date DESC, a.check_in_time DESC LIMIT :limit OFFSET :offset";
        return $this->db->query($sql, [':limit' => $limit, ':offset' => $offset]);
    }

    public function get_total_all_attendance_count() {
        return $this->db->count('attendance');
    }

    public function get_attendance_by_id($id) {
        return $this->db->get('attendance', [['id', '=', $id]], true);
    }

    public function update_attendance($id, $data) {
        return $this->db->update('attendance', $data, [['id', '=', $id]]);
    }

    public function delete_attendance($id) {
        return $this->db->delete('attendance', [['id', '=', $id]]);
    }
}
