<?php
// router.php
echo "Router hit!"; // Debugging line

// Serve static files directly if they exist
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|ico)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}

// Get the request URI and remove query string
$request_uri = $_SERVER['REQUEST_URI'];
$request_uri_parts = explode('?', $request_uri);
$path = $request_uri_parts[0];

// Define the base directory for applications
$app_base_dir = __DIR__ . '/applications/';

// Determine which application is being requested
$app_name = null;
if (strpos($path, '/sekolah') === 0) {
    $app_name = 'sekolah';
} elseif (strpos($path, '/rumahsakit') === 0) {
    $app_name = 'rumahsakit';
}

if ($app_name) {
    // Define application-specific paths
    define('APPPATH', $app_base_dir . $app_name . '/');
    define('SYSPATH', __DIR__ . '/system/'); // System path is always relative to the project root

    // Load Composer's autoloader
    require_once __DIR__ . '/vendor/autoload.php';

    // Load the bootstrap file for the specific application
    require_once SYSPATH . 'bootstrap.php';
} else {
    // If no specific application is requested, load the main portal index.php
    require_once __DIR__ . '/index.php';
}
