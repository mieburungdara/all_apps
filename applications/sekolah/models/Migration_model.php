<?php

class Migration_model extends Model {

    public function is_table_exists($table_name) {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=:table_name";
        return $this->db->fetch_one($sql, [':table_name' => $table_name]) !== false;
    }

    public function run_migrations() {
        if (!$this->is_table_exists('users')) {
            $this->create_users_table();
        } else {
            // Add api_key column if it doesn't exist
            try {
                $this->db->exec("ALTER TABLE users ADD COLUMN api_key VARCHAR(255) NULL UNIQUE");
            } catch (\Exception $e) {
                // Ignore error if column already exists
            }
        }

        if (!$this->is_table_exists('roles')) {
            $this->create_roles_table();
        }
        if (!$this->is_table_exists('user_roles')) {
            $this->create_user_roles_table();
        }
        // Add other table checks here in the future
    }

    private function create_users_table() {
        $sql = "CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            api_key VARCHAR(255) NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";
        $this->db->exec($sql);
    }

    private function create_roles_table() {
        $sql = "CREATE TABLE roles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            role_name VARCHAR(255) NOT NULL UNIQUE
        );";
        $this->db->exec($sql);
    }

    private function create_user_roles_table() {
        $sql = "CREATE TABLE user_roles (
            user_id INTEGER NOT NULL,
            role_id INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
            PRIMARY KEY (user_id, role_id)
        );";
        $this->db->exec($sql);
    }
}
