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

    public function get_role_by_id($role_id) {
        return $this->db->get('roles', [['id', '=', $role_id]], true);
    }

    public function get_all_permissions() {
        return $this->db->query("SELECT * FROM permissions ORDER BY permission_name");
    }

    public function get_permissions_for_role($role_id) {
        $sql = "SELECT p.id FROM role_permissions rp JOIN permissions p ON rp.permission_id = p.id WHERE rp.role_id = :role_id";
        $results = $this->db->query($sql, [':role_id' => $role_id]);
        return array_column($results, 'id');
    }

    public function update_role_permissions($role_id, $permission_ids) {
        // Superadmin and user roles cannot be modified
        $role = $this->get_role_by_id($role_id);
        if (in_array($role['role_name'], ['superadmin', 'user'])) {
            return;
        }

        // Delete existing permissions for the role
        $this->db->delete('role_permissions', [['role_id', '=', $role_id]]);

        // Assign new permissions
        foreach ($permission_ids as $permission_id) {
            $this->assign_permission_to_role($permission_id, $role_id);
        }
    }

    public function create_permission($name, $description = '') {
        // Avoid creating duplicate permissions
        $existing = $this->db->get('permissions', [['permission_name', '=', $name]], true);
        if ($existing) {
            return $existing['id'];
        }
        $this->db->insert('permissions', ['permission_name' => $name, 'description' => $description]);
        return $this->db->last_insert_id();
    }

    public function assign_permission_to_role($permission_id, $role_id) {
        // Avoid creating duplicate assignments
        $existing = $this->db->get('role_permissions', [
            ['role_id', '=', $role_id],
            ['permission_id', '=', $permission_id]
        ], true);
        if (!$existing) {
            $this->db->insert('role_permissions', ['role_id' => $role_id, 'permission_id' => $permission_id]);
        }
    }

    public function has_permission($user_id, $permission_name) {
        // First, check if the user is a superadmin
        if ($this->user_has_role($user_id, 'superadmin')) {
            return true;
        }

        // If not superadmin, check for the specific permission through their roles
        $sql = "SELECT COUNT(*) as count 
                FROM user_roles ur
                JOIN role_permissions rp ON ur.role_id = rp.role_id
                JOIN permissions p ON rp.permission_id = p.id
                WHERE ur.user_id = :user_id AND p.permission_name = :permission_name";
        
        $result = $this->db->fetch_one($sql, [
            ':user_id' => $user_id,
            ':permission_name' => $permission_name
        ]);

        return $result && $result['count'] > 0;
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
