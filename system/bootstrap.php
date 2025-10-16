<?php

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
