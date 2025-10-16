<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Installer</title>
</head>
<body>
    <h1>Welcome to the Installer</h1>
    <p>This wizard will guide you through the installation process.</p>
    <h2>Server Requirements</h2>
    <ul>
        <li>PHP Version: <?php echo $php_version; ?> (7.4+ required)</li>
        <li>PDO SQLite Extension: <?php echo $pdo_enabled ? 'Enabled' : 'Disabled'; ?> (Required)</li>
    </ul>
    <?php if (version_compare($php_version, '7.4.0', '>=') && $pdo_enabled): ?>
        <a href="/sekolah/installer/database">Start Installation</a>
    <?php else: ?>
        <p>Please fix the requirements before proceeding.</p>
    <?php endif; ?>
</body>
</html>