<?php

class Model {

    protected $db;

    public function __construct() {
        // Load the database configuration
        if (file_exists(APPPATH . 'config/database.php')) {
            $db_config = require APPPATH . 'config/database.php';
        } else {
            die("Database configuration file not found!");
        }

        // Set up the DSN (Data Source Name)
        $dsn = 'mysql:host=' . $db_config['hostname'] . ';dbname=' . $db_config['database'];

        // Set up PDO options
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Create the PDO instance
            $this->db = new PDO($dsn, $db_config['username'], $db_config['password'], $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
