<?php

class Database {

    private static $instance = null;
    private $db;

    private function __construct() {
        $config = Config::getInstance();
        $db_config = $config->load('database');

        if ($db_config['driver'] === 'mysql') {
            $dsn = 'mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['database'];
            $user = $db_config['username'];
            $pass = $db_config['password'];
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                $this->db = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        } else {
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
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function get($table, $where = [], $single = false, $limit = null, $offset = null) {
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

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int)$limit;
        }

        if ($offset !== null) {
            $sql .= " OFFSET :offset";
            $params[':offset'] = (int)$offset;
        }

        $stmt = $this->query($sql, $params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $single ? ($result[0] ?? null) : $result;
    }

    public function count($table, $where = []) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
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

        $result = $this->fetch_one($sql, $params);
        return (int)($result['count'] ?? 0);
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

    public function exec($sql) {
        return $this->db->exec($sql);
    }

    public function fetch_one($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function last_insert_id() {
        return $this->db->lastInsertId();
    }
}
