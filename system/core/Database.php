<?php

class Database {

    private static $instance = null;
    private $db;

    private function __construct() {
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

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function get($table, $where = []) {
        $sql = "SELECT * FROM {$table}";
        $params = [];
        if (!empty($where)) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($where as $index => $condition) {
                $column = $condition[0];
                $operator = $condition[1];
                $value = $condition[2];
                $placeholder = ":where_{$column}_{$index}";
                $conditions[] = "{$column} {$operator} {$placeholder}";
                $params[$placeholder] = $value;
            }
            $sql .= implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
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
        $params = [];
        foreach ($data as $key => $value) {
            $placeholder = ":data_{$key}";
            $data_fields[] = "{$key} = {$placeholder}";
            $params[$placeholder] = $value;
        }
        $data_sql = implode(', ', $data_fields);

        $where_conditions = [];
        if (!empty($where)) {
            foreach ($where as $index => $condition) {
                $column = $condition[0];
                $operator = $condition[1];
                $value = $condition[2];
                $placeholder = ":where_{$column}_{$index}";
                $where_conditions[] = "{$column} {$operator} {$placeholder}";
                $params[$placeholder] = $value;
            }
        }
        $where_sql = implode(' AND ', $where_conditions);

        $sql = "UPDATE {$table} SET {$data_sql} WHERE {$where_sql}";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($table, $where = []) {
        $sql = "DELETE FROM {$table}";
        $params = [];
        if (!empty($where)) {
            $sql .= " WHERE ";
            $conditions = [];
            foreach ($where as $index => $condition) {
                $column = $condition[0];
                $operator = $condition[1];
                $value = $condition[2];
                $placeholder = ":where_{$column}_{$index}";
                $conditions[] = "{$column} {$operator} {$placeholder}";
                $params[$placeholder] = $value;
            }
            $sql .= implode(' AND ', $conditions);
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
