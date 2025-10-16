<div class="p-4">
    <div class="mb-4">
        <h1 class="fs-2 fw-bold mb-1">Database Setup</h1>
        <p class="fw-medium text-muted">
            Configure your database connection.
        </p>
    </div>
    <?php if ($this->session->has_flash('error')): ?>
        <div class="alert alert-danger"><?= $this->session->get_flash('error') ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-4">
            <label class="form-label" for="db_name">Database Name</label>
            <input type="text" class="form-control form-control-lg" id="db_name" name="db_name" value="app.sqlite" required>
            <div class="form-text">This will create a new SQLite database file in <code>applications/sekolah/database/</code>.</div>
        </div>
        <div class="mb-4">
            <button type="submit" class="btn btn-lg btn-alt-primary w-100">Create and Continue</button>
        </div>
    </form>
</div>
