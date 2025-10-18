<?php

class Auth_model extends Model {

    public function get_jabatan_by_name($jabatan_name) {
        return $this->db->fetch_one("SELECT * FROM jabatan WHERE nama_jabatan = :jabatan_name", [':jabatan_name' => $jabatan_name]);
    }

    public function create_jabatan($jabatan_name) {
        if (!$this->get_jabatan_by_name($jabatan_name)) {
            $this->db->insert('jabatan', ['nama_jabatan' => $jabatan_name]);
        }
        $jabatan = $this->get_jabatan_by_name($jabatan_name);
        return $jabatan['id'];
    }

    public function assign_jabatan($user_id, $jabatan_id) {
        $this->db->insert('user_jabatan', ['user_id' => $user_id, 'jabatan_id' => $jabatan_id]);
    }

    public function user_has_jabatan($user_id, $jabatan_name) {
        $sql = "SELECT COUNT(*) as count FROM user_jabatan uj JOIN jabatan j ON uj.jabatan_id = j.id WHERE uj.user_id = :user_id AND j.nama_jabatan = :jabatan_name";
        $result = $this->db->fetch_one($sql, [':user_id' => $user_id, ':jabatan_name' => $jabatan_name]);
        return (int)($result['count'] ?? 0) > 0;
    }
    
    public function get_user_jabatan($user_id) {
        $sql = "SELECT j.nama_jabatan FROM user_jabatan uj JOIN jabatan j ON uj.jabatan_id = j.id WHERE uj.user_id = :user_id";
        $results = $this->db->query($sql, [':user_id' => $user_id]);
        return array_column($results, 'nama_jabatan');
    }

    public function get_all_jabatan() {
        return $this->db->query("SELECT * FROM jabatan");
    }

    public function get_jabatan_by_id($jabatan_id) {
        return $this->db->get('jabatan', [['id', '=', $jabatan_id]], true);
    }

    public function get_all_permissions() {
        return $this->db->query("SELECT * FROM permissions ORDER BY permission_name");
    }

    public function get_permissions_for_jabatan($jabatan_id) {
        $sql = "SELECT p.id FROM jabatan_permissions jp JOIN permissions p ON jp.permission_id = p.id WHERE jp.jabatan_id = :jabatan_id";
        $results = $this->db->query($sql, [':jabatan_id' => $jabatan_id]);
        if (empty($results)) return [];
        return array_column($results, 'id');
    }

    public function update_jabatan_permissions($jabatan_id, $permission_ids) {
        // Superadmin and user roles cannot be modified
        $jabatan = $this->get_jabatan_by_id($jabatan_id);
        if (in_array($jabatan['nama_jabatan'], ['superadmin', 'user'])) {
            return; // Or throw an exception
        }

        // Delete existing permissions for the role
        $this->db->delete('jabatan_permissions', [['jabatan_id', '=', $jabatan_id]]);

        // Assign new permissions
        foreach ($permission_ids as $permission_id) {
            $this->assign_permission_to_jabatan($permission_id, $jabatan_id);
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

    public function assign_permission_to_jabatan($permission_id, $jabatan_id) {
        // Check if the permission is already assigned to the role to avoid duplicates
        $existing = $this->db->get('jabatan_permissions', [
            ['jabatan_id', '=', $jabatan_id],
            ['permission_id', '=', $permission_id]
        ], true);

        if (!$existing) {
            $this->db->insert('jabatan_permissions', ['jabatan_id' => $jabatan_id, 'permission_id' => $permission_id]);
        }
    }

    public function user_can($user_id, $permission_name) {
        // Superadmin can do anything
        if ($this->user_has_jabatan($user_id, 'superadmin')) {
            return true;
        }

        // If not superadmin, check for the specific permission through their roles
        $sql = "SELECT COUNT(*) as count
                FROM user_jabatan uj
                JOIN jabatan_permissions jp ON uj.jabatan_id = jp.jabatan_id
                JOIN permissions p ON jp.permission_id = p.id
                WHERE uj.user_id = :user_id AND p.permission_name = :permission_name";

        $result = $this->db->fetch_one($sql, [
            ':user_id' => $user_id,
            ':permission_name' => $permission_name
        ]);

        return (int)($result['count'] ?? 0) > 0;
    }

    public function update_user_jabatan($user_id, $jabatan_ids) {
        // First, delete existing roles for the user
        $this->db->delete('user_jabatan', [['user_id', '=', $user_id]]);

        // Then, assign the new roles
        foreach ($jabatan_ids as $jabatan_id) {
            $this->assign_jabatan($user_id, $jabatan_id);
        }
    }
}
