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

    public function regenerate_id() {
        // This helps prevent session fixation attacks.
        session_regenerate_id(true);
    }

    public function set_flash($key, $message = '') {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $_SESSION['__flash'][$k] = $v;
            }
        } else {
            $_SESSION['__flash'][$key] = $message;
        }
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

    public function generate_csrf_token() {
        if (empty($this->get('csrf_token'))) {
            $token = bin2hex(random_bytes(32));
            $this->set('csrf_token', $token);
        }
        return $this->get('csrf_token');
    }

    public function validate_csrf_token($token) {
        $session_token = $this->get('csrf_token');
        $this->set('csrf_token', null);
        return hash_equals($session_token, $token);
    }

    public function flash_input($data) {
        $this->set('__old_input', $data);
    }

    public function get_old_input($key) {
        $old_input = $this->get('__old_input');
        if (isset($old_input[$key])) {
            $value = $old_input[$key];
            unset($_SESSION['__old_input'][$key]);
            return $value;
        }
        return null;
    }
}