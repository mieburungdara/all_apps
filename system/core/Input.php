<?php

class Input {

    private static $instance = null;
    private $validator;

    private function __construct() {
        require_once SYSPATH . 'core/Validator.php';
        require_once SYSPATH . 'core/security_helper.php';
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
            return xss_clean($_POST);
        }
        return isset($_POST[$key]) ? xss_clean($_POST[$key]) : $default;
    }

    public function get($key = null, $default = null) {
        if ($key === null) {
            return xss_clean($_GET);
        }
        return isset($_GET[$key]) ? xss_clean($_GET[$key]) : $default;
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
