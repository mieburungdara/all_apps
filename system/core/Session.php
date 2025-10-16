<?php

class Session {

    private static $instance = null;

    private function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public function destroy() {
        session_unset();
        session_destroy();
    }

    public function set_flash($key, $message) {
        $_SESSION['__flash'][$key] = $message;
    }

    public function has_flash($key) {
        return isset($_SESSION['__flash'][$key]);
    }

    public function get_flash($key) {
        if ($this->has_flash($key)) {
            $message = $_SESSION['__flash'][$key];
            unset($_SESSION['__flash'][$key]);
            return $message;
        }
        return null;
    }
}
