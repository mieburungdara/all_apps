<?php

class Model {

    protected $db;

    public function __construct() {
        require_once SYSPATH . 'core/Database.php';
        $this->db = Database::getInstance();
    }
}