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

    public function get_all_attendance_with_users($limit = 20, $offset = 0, $filters = []) {
        $sql = "SELECT a.*, u.nama as user_name FROM attendance a JOIN users u ON a.user_id = u.id";
        $params = [];
        $where = [];

        if (!empty($filters['start_date'])) {
            $where[] = "a.date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $where[] = "a.date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }
        if (!empty($filters['user_name'])) {
            $where[] = "u.nama LIKE :user_name";
            $params[':user_name'] = '%' . $filters['user_name'] . '%';
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY a.date DESC, a.check_in_time DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        return $this->db->query($sql, $params);
    }

    public function get_total_all_attendance_count($filters = []) {
        $sql = "SELECT COUNT(a.id) as count FROM attendance a JOIN users u ON a.user_id = u.id";
        $params = [];
        $where = [];

        if (!empty($filters['start_date'])) {
            $where[] = "a.date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $where[] = "a.date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }
        if (!empty($filters['user_name'])) {
            $where[] = "u.nama LIKE :user_name";
            $params[':user_name'] = '%' . $filters['user_name'] . '%';
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $result = $this->db->fetch_one($sql, $params);
        return (int)($result['count'] ?? 0);
    }

    public function get_attendance_summary_report($year, $month) {
        $date_filter = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        $sql = "SELECT 
                    u.id as user_id,
                    u.nama as user_name,
                    COUNT(a.id) as total_days,
                    SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as total_present,
                    SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) as total_late,
                    SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as total_absent,
                    SUM(CASE WHEN a.status = 'on_leave' THEN 1 ELSE 0 END) as total_on_leave
                FROM users u
                LEFT JOIN attendance a ON u.id = a.user_id AND DATE_FORMAT(a.date, '%Y-%m') = :date_filter
                GROUP BY u.id, u.nama
                ORDER BY u.nama ASC";
        
        return $this->db->query($sql, [':date_filter' => $date_filter]);
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
