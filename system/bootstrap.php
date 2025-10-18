<?php

// Failsafe error handling for early-stage errors
ini_set('display_errors', 0);
error_reporting(E_ALL);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $log_path = __DIR__ . '/../logs/fatal.log';
        $message = "FATAL ERROR: {$error['message']}\t{$error['file']}\t{$error['line']}";
        error_log($message . "\n", 3, $log_path);
    }
});

set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    $log_path = __DIR__ . '/../logs/errors.log';
    $message = "ERROR: [{$severity}] {$message}\t{$file}\t{$line}";
    error_log($message . "\n", 3, $log_path);
    return true; // Don't execute PHP internal error handler
});

// Redirect to installer if not installed
if (!file_exists(APPPATH . 'installed.lock') && strpos($_SERVER['REQUEST_URI'], '/install') === false) {
    header('Location: /sekolah/installer');
    exit;
}

// Composer's autoloader is loaded by router.php


// Load helper functions
function show_error($message) {
    // In a real app, you'd load a view and log the error.
    // For now, just die.
    die($message);
}

function show_404() {
    http_response_code(404);
    require_once APPPATH . 'modules/errors/controllers/Errors.php';
    $errors = new Errors(APPPATH . 'modules/errors/', 'show_404');
    $errors->show_404();
    exit;
}

// Autoloader
spl_autoload_register(function ($class) {
    // Core classes
    if (file_exists(SYSPATH . 'core/' . $class . '.php')) {
        require_once SYSPATH . 'core/' . $class . '.php';
    }
});

// The autoloader will now handle loading the Router class

// Determine the base path for the current application
$base_path = '';
if (defined('APPPATH')) {
    $app_name = basename(rtrim(APPPATH, '/'));
    $request_uri = $_SERVER['REQUEST_URI'];
    $request_uri_parts = explode('?', $request_uri);
    $path = $request_uri_parts[0];
    if (strpos($path, '/' . $app_name) === 0) {
        $base_path = '/' . $app_name;
    }
}

// Create a new Router instance, passing the base path
$router = new Router($base_path);