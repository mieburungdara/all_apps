<?php

class Migration_model extends Model {

    public function is_table_exists($table_name) {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name=:table_name";
        return $this->db->fetch_one($sql, [':table_name' => $table_name]) !== false;
    }

    public function run_migrations() {
        if (!$this->is_table_exists('users')) {
            $this->create_users_table();
        }
        // Add other table checks here in the future
    }

    private function create_users_table() {
        $sql = "CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";
        $this->db->exec($sql);
    }
}
