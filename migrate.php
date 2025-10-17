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
$db_file = $db_config['path'];

echo "Connecting to database: {$db_file}\n";

// 1. Create migrations table if it doesn't exist
system("sqlite3 {$db_file} 'CREATE TABLE IF NOT EXISTS migrations (migration TEXT NOT NULL PRIMARY KEY);'");
echo "Migrations table is ready.\n";

// 2. Get all migrations that have been run
$run_migrations_json = shell_exec("sqlite3 -json {$db_file} 'SELECT migration FROM migrations'");
$run_migrations_data = json_decode($run_migrations_json, true);
$run_migrations = [];
if ($run_migrations_data !== null) {
    $run_migrations = array_map(function($item) { return $item['migration']; }, $run_migrations_data);
}

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
    $handle = popen("sqlite3 {$db_file}", 'w');
    fwrite($handle, $sql);
    pclose($handle);

    // Record the migration
    system("sqlite3 {$db_file} \"INSERT INTO migrations (migration) VALUES ('{$migration}');\"");
    echo "OK\n";
}

echo "Migration completed successfully.\n";