<?php

class Model {

    protected $db;

    public function __construct() {
        // Load the database configuration using the new Config class
        $config = Config::getInstance();
        $db_config = $config->load('database');

        $dsn = 'sqlite:' . $db_config['path'];
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->db = new PDO($dsn, null, null, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function get($table, $where = []) {
        $sql = "SELECT * FROM {$table}";
        if (!empty($where)) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($where as $key => $value) {
                $conditions[] = "{$key} = :{$key}";
            }
            $sql .= implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($where);
        return $stmt->fetchAll();
    }

    public function insert($table, $data) {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);
        
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($table, $data, $where) {
        $data_fields = [];
        foreach ($data as $key => $value) {
            $data_fields[] = "{$key} = :data_{$key}";
        }
        $data_sql = implode(', ', $data_fields);

        $where_fields = [];
        foreach ($where as $key => $value) {
            $where_fields[] = "{$key} = :where_{$key}";
        }
        $where_sql = implode(' AND ', $where_fields);

        $sql = "UPDATE {$table} SET {$data_sql} WHERE {$where_sql}";

        $stmt = $this->db->prepare($sql);

        // Bind data parameters
        foreach ($data as $key => $value) {
            $stmt->bindValue(":data_{$key}", $value);
        }

        // Bind where parameters
        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_{$key}", $value);
        }

        return $stmt->execute();
    }

    public function delete($table, $where) {
        $sql = "DELETE FROM {$table} WHERE ";
        $conditions = [];
        foreach ($where as $key => $value) {
            $conditions[] = "{$key} = :{$key}";
        }
        $sql .= implode(' AND ', $conditions);

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($where);
    }
}