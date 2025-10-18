<?php

// Simple Migration Runner

if ($argc < 2) {
    echo "Usage: php migrate.php <application_name>\n";
    exit(1);
}

$app_name = $argv[1];
$app_path = __DIR__ . '/applications/' . $app_name . '/';

if (!is_dir($app_path)) {
    echo "Error: Application '{$app_name}' not found.\n";
    exit(1);
}

// Define a minimal APPPATH for the config file
define('APPPATH', $app_path);

$db_config_path = $app_path . 'config/database.php';
$migrations_path = $app_path . 'database/migrations/';

if (!file_exists($db_config_path)) {
    echo "Error: Database configuration not found for application '{$app_name}'.\n";
    exit(1);
}

$db_config = require $db_config_path;

$host = $db_config['host'];
$db_name = $db_config['database'];
$user = $db_config['username'];
$pass = $db_config['password'];

echo "Connecting to database: {$db_name} on {$host}\n";

// Connect to the database
$mysqli = new mysqli($host, $user, $pass, $db_name);

if ($mysqli->connect_error) {
    echo "Connection failed: " . $mysqli->connect_error . "\n";
    exit(1);
}

// 1. Create migrations table if it doesn't exist
$mysqli->query("CREATE TABLE IF NOT EXISTS migrations (migration VARCHAR(255) NOT NULL PRIMARY KEY);");
echo "Migrations table is ready.\n";

// 2. Get all migrations that have been run
$result = $mysqli->query("SELECT migration FROM migrations");
$run_migrations = $result->fetch_all(MYSQLI_ASSOC);
$run_migrations = array_column($run_migrations, 'migration');

// 3. Get all available migration files
$all_files = scandir($migrations_path);
$migration_files = array_filter($all_files, function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
});
sort($migration_files);

// 4. Determine and run new migrations
$new_migrations = array_diff($migration_files, $run_migrations);

if (empty($new_migrations)) {
    echo "Database is already up to date.\n";
    exit(0);
}

foreach ($new_migrations as $migration) {
    echo "Migrating: {$migration}... ";
    $sql = file_get_contents($migrations_path . $migration);
    $mysqli->multi_query($sql);

    // Record the migration
    $stmt = $mysqli->prepare("INSERT INTO migrations (migration) VALUES (?)");
    $stmt->bind_param('s', $migration);
    $stmt->execute();
    echo "OK\n";
}

$mysqli->close();

echo "Migration completed successfully.\n";