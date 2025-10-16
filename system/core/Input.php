<?php

class Input {

    private static $instance = null;

    private function __construct() {}

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
}
