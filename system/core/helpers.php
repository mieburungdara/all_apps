<?php

if (!function_exists('base_url')) {
    function base_url($uri = '') {
        // Get the config instance
        $config = Config::getInstance();
        $app_config = $config->load('app');
        
        $base_url = $app_config['base_url'];
        
        return rtrim($base_url, '/') . '/' . ltrim($uri, '/');
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path = '') {
        // This function assumes assets are always at the root level's public folder
        // We can make this more dynamic later if needed.
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

if (!function_exists('show_error')) {
    function show_error($message) {
        require_once SYSPATH . 'core/Error_Controller.php';
        $error = new Error_Controller();
        $error->show_general($message);
        exit();
    }
}

if (!function_exists('show_404')) {
    function show_404() {
        require_once SYSPATH . 'core/Error_Controller.php';
        $error = new Error_Controller();
        $error->show_404();
        exit();
    }
}
