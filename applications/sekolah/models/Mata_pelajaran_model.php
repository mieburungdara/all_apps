<?php

class Mata_pelajaran_model extends Model {

    public function get_all_mata_pelajaran() {
        return $this->db->get('mata_pelajaran');
    }

    public function get_mata_pelajaran_by_id($id) {
        return $this->db->get('mata_pelajaran', [['id', '=', $id]], true);
    }

    public function get_mata_pelajaran_by_guru_id($guru_id) {
        $sql = "SELECT mp.* FROM mata_pelajaran mp JOIN guru_mapel gm ON mp.id = gm.mapel_id WHERE gm.guru_id = :guru_id";
        return $this->db->query($sql, [':guru_id' => $guru_id]);
    }

    public function get_students_by_mapel_id($mapel_id) {
        // This assumes a many-to-many relationship between students and mata_pelajaran
        // For now, let's assume students are linked to classes, and classes are linked to mata_pelajaran
        // This will require a more complex join or a dedicated student_mapel table.
        // For simplicity, let's assume all users with 'student' role are potential students for any subject.
        // In a real system, you'd have a student_class table and class_mapel table.
        $sql = "SELECT u.id, u.nama, u.email FROM users u JOIN user_jabatan uj ON u.id = uj.user_id JOIN jabatan j ON uj.jabatan_id = j.id WHERE j.nama_jabatan = 'student'";
        return $this->db->query($sql);
    }
}
