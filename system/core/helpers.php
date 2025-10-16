<?php

if (!function_exists('base_url')) {
    function base_url($uri = '') {
        $config = Config::getInstance();
        $app_config = $config->load('app');
        $base_url = $app_config['base_url'];
        return rtrim($base_url, '/') . '/' . ltrim($uri, '/');
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path = '') {
        return '/' . 'assets/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_input')) {
    function csrf_input() {
        $session = Session::getInstance();
        $token = $session->generate_csrf_token();
        echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}

if (!function_exists('_resolve_app_path')) {
    function _resolve_app_path() {
        if (defined('APPPATH')) {
            return true;
        }

        $uri_segments = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $app_name = $uri_segments[0] ?? '';

        $potential_app_path = realpath(__DIR__ . '/../../applications/' . $app_name);

        if ($app_name && $potential_app_path) {
            define('APPPATH', $potential_app_path . '/');
            return true;
        }

        return false;
    }
}

if (!function_exists('show_error')) {
    function show_error($message) {
        if (_resolve_app_path()) {
            require_once SYSPATH . 'core/Controller.php';
            require_once SYSPATH . 'core/Loader.php';
            require_once SYSPATH . 'core/Error_Controller.php';
            $error = new Error_Controller();
            $error->show_general($message);
        } else {
            http_response_code(500);
            echo "A critical error occurred outside of a valid application context: " . htmlspecialchars($message);
        }
        exit();
    }
}

if (!function_exists('show_404')) {
    function show_404() {
        if (_resolve_app_path()) {
            require_once SYSPATH . 'core/Controller.php';
            require_once SYSPATH . 'core/Loader.php';
            require_once SYSPATH . 'core/Error_Controller.php';
            $error = new Error_Controller();
            $error->show_404();
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        exit();
    }
}
