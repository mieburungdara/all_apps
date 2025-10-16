<?php

// Failsafe error handling for early-stage errors
ini_set('display_errors', 0);
error_reporting(E_ALL);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $log_path = __DIR__ . '/../logs/fatal.log';
        $message = "FATAL ERROR: {$error['message']}	{$error['file']}	{$error['line']}";
        error_log($message . "\n", 3, $log_path);
    }
});

set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    $log_path = __DIR__ . '/../logs/errors.log';
    $message = "ERROR: [{$severity}] {$message}	{$file}	{$line}";
    error_log($message . "\n", 3, $log_path);
    return true; // Don't execute PHP internal error handler
});


// Load helper functions
require_once __DIR__ . '/core/helpers.php';

// Autoloader
spl_autoload_register(function ($class) {
    // Core classes
    if (file_exists(SYSPATH . 'core/' . $class . '.php')) {
        require_once SYSPATH . 'core/' . $class . '.php';
    }
});

// The autoloader will now handle loading the Router class

// Create a new Router instance
$router = new Router();