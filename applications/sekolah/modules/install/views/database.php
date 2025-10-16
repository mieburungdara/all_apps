<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
</head>
<body>
    <h1>Database Setup</h1>
    <?php if ($this->session->has_flash('error')): ?>
        <p style="color:red;"><?php echo $this->session->get_flash('error'); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="db_name">Database Name:</label>
        <input type="text" id="db_name" name="db_name" value="app.sqlite" required>
        <p>This will create a new SQLite database file in `applications/sekolah/database/`.</p>
        <button type="submit">Create Database</button>
    </form>
</body>
</html>