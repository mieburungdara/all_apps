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
            <label class="form-label" for="db_host">Database Host</label>
            <input type="text" class="form-control form-control-lg" id="db_host" name="db_host" value="localhost" required>
        </div>
        <div class="mb-4">
            <label class="form-label" for="db_port">Database Port</label>
            <input type="text" class="form-control form-control-lg" id="db_port" name="db_port" value="3306" required>
        </div>
        <div class="mb-4">
            <label class="form-label" for="db_name">Database Name</label>
            <input type="text" class="form-control form-control-lg" id="db_name" name="db_name" required>
        </div>
        <div class="mb-4">
            <label class="form-label" for="db_username">Database Username</label>
            <input type="text" class="form-control form-control-lg" id="db_username" name="db_username" required>
        </div>
        <div class="mb-4">
            <label class="form-label" for="db_password">Database Password</label>
            <input type="password" class="form-control form-control-lg" id="db_password" name="db_password">
        </div>
        <div class="mb-4">
            <button type="submit" class="btn btn-lg btn-alt-primary w-100">Connect and Continue</button>
        </div>
    </form>
</div>
