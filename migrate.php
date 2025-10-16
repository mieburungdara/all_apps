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

try {
    // Connect to the database
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Create migrations table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (migration TEXT NOT NULL PRIMARY KEY);");
    echo "Migrations table is ready.\n";

    // 2. Get all migrations that have been run
    $stmt = $pdo->query("SELECT migration FROM migrations");
    $run_migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

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
        $pdo->exec($sql);

        // Record the migration
        $insert_stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
        $insert_stmt->execute([$migration]);
        echo "OK\n";
    }

    echo "Migration completed successfully.\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}

