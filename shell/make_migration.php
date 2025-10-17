<?php

if ($argc < 3) {
    echo "Usage: php shell/make_migration.php <application_name> <migration_name>\n";
    exit(1);
}

$appName = $argv[1];
$migrationName = $argv[2];
$timestamp = date('Y_m_d_His');
$filename = $timestamp . '_' . $migrationName . '.sql';
$filepath = 'applications/' . $appName . '/database/migrations/' . $filename;

if (!is_dir(dirname($filepath))) {
    mkdir(dirname($filepath), 0755, true);
}

$content = "-- SQL migration for " . $migrationName . "\n";

file_put_contents($filepath, $content);

echo "Migration created: $filepath\n";