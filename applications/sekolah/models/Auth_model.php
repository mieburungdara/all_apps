<?php

class Auth_model extends Model {

    public function get_role_by_name($role_name) {
        return $this->db->fetch_one("SELECT * FROM roles WHERE role_name = :role_name", [':role_name' => $role_name]);
    }

    public function create_role($role_name) {
        if (!$this->get_role_by_name($role_name)) {
            $this->db->insert('roles', ['role_name' => $role_name]);
            return $this->db->last_insert_id();
        }
        $role = $this->get_role_by_name($role_name);
        return $role['id'];
    }

    public function assign_role($user_id, $role_id) {
        $this->db->insert('user_roles', ['user_id' => $user_id, 'role_id' => $role_id]);
    }

    public function user_has_role($user_id, $role_name) {
        $sql = "SELECT COUNT(*) as count FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :user_id AND r.role_name = :role_name";
        $result = $this->db->fetch_one($sql, [':user_id' => $user_id, ':role_name' => $role_name]);
        return $result['count'] > 0;
    }
    
    public function get_user_roles($user_id) {
        $sql = "SELECT r.role_name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :user_id";
        $results = $this->db->query($sql, [':user_id' => $user_id]);
        return array_column($results, 'role_name');
    }

    public function get_all_roles() {
        return $this->db->query("SELECT * FROM roles");
    }

    public function update_user_roles($user_id, $role_ids) {
        // First, delete existing roles for the user
        $this->db->delete('user_roles', [['user_id', '=', $user_id]]);

        // Then, assign the new roles
        foreach ($role_ids as $role_id) {
            $this->assign_role($user_id, $role_id);
        }
    }
}
