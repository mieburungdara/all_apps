<?php

class Model {

    protected $db;

    public function __construct() {
        // We will use a singleton pattern to ensure only one database connection is made.
        $this->db = Database::getInstance();
    }
}