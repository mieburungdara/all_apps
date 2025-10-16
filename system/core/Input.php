<?php

class Input {

    private static $instance = null;
    private $validator;

    private function __construct() {
        require_once SYSPATH . 'core/Validator.php';
        $this->validator = new Validator();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Input();
        }
        return self::$instance;
    }

    public function post($key = null, $default = null) {
        if ($key === null) {
            return filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        }
        return filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING) ?? $default;
    }

    public function get($key = null, $default = null) {
        if ($key === null) {
            return filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        }
        return filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING) ?? $default;
    }

    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function csrf_token() {
        return $this->post('csrf_token');
    }

    public function validate($rules) {
        $data = $this->post(); // Get all POST data
        return $this->validator->validate($data, $rules);
    }

    public function get_errors() {
        return $this->validator->get_errors();
    }

    public function old($key) {
        return Session::getInstance()->get_old_input($key);
    }
}