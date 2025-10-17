<?php

class Student_Parent_model extends Model {

    public function get_children_for_parent($parent_id) {
        $sql = "SELECT u.* FROM users u JOIN student_parent_relations spr ON u.id = spr.student_id WHERE spr.parent_id = :parent_id";
        return $this->db->query($sql, [':parent_id' => $parent_id]);
    }

    public function get_child_ids_for_parent($parent_id) {
        $results = $this->get_children_for_parent($parent_id);
        return array_column($results, 'id');
    }

    public function is_child_of_parent($student_id, $parent_id) {
        $count = $this->db->count('student_parent_relations', [
            ['student_id', '=', $student_id],
            ['parent_id', '=', $parent_id]
        ]);
        return $count > 0;
    }

    public function get_parents_for_student($student_id) {
        $sql = "SELECT u.* FROM users u JOIN student_parent_relations spr ON u.id = spr.parent_id WHERE spr.student_id = :student_id";
        return $this->db->query($sql, [':student_id' => $student_id]);
    }

    public function update_children_for_parent($parent_id, $child_ids) {
        // First, delete existing relationships for the parent
        $this->db->delete('student_parent_relations', [['parent_id', '=', $parent_id]]);

        // Then, assign the new relationships
        foreach ($child_ids as $child_id) {
            $this->db->insert('student_parent_relations', ['parent_id' => $parent_id, 'student_id' => $child_id]);
        }
    }
}
