<?php

class Parent_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_parents_by_student($student_id) {
        // Assuming a 'parents' table with a 'student_id' column
        return $this->db->get_where('parents', array('student_id' => $student_id))->result_array();
    }
}
