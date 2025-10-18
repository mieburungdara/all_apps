<?php

class Student_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_students_by_class($class_id) {
        // Assuming a 'students' table with a 'class_id' column
        return $this->db->get_where('students', array('class_id' => $class_id))->result_array();
    }
}
