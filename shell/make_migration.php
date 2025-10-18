<?php

if ($argc < 2) {
    echo "Usage: php shell/make_migration.php <migration_name>\n";
    exit(1);
}

$migrationName = $argv[1];
$timestamp = date('Y_m_d_His');
$filename = $timestamp . '_' . $migrationName . '.php';
$filepath = 'migrations/' . $filename;

if (!is_dir('migrations')) {
    mkdir('migrations');
}

$content = "<?php\n\nclass " . ucfirst($migrationName) . " {\n    public function up() {\n        // your migration logic here\n    }\n\n    public function down() {\n        // your rollback logic here\n    }\n}\n";

file_put_contents($filepath, $content);

echo "Migration created: $filepath\n";

