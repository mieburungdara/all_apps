<div class="p-4">
    <div class="mb-4">
        <h1 class="fs-2 fw-bold mb-1">Application Installer</h1>
        <p class="fw-medium text-muted">
            Welcome! This wizard will guide you through the installation.
        </p>
    </div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Server Requirements</h3>
        </div>
        <div class="block-content">
            <ul class="list-group push">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    PHP Version (7.4+ Required)
                    <span class="badge rounded-pill bg-<?= version_compare($php_version, '7.4.0', '>=') ? 'success' : 'danger' ?>"><?= $php_version ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    PDO SQLite Extension
                    <span class="badge rounded-pill bg-<?= $pdo_enabled ? 'success' : 'danger' ?>"><?= $pdo_enabled ? 'Enabled' : 'Disabled' ?></span>
                </li>
            </ul>
            <?php if (version_compare($php_version, '7.4.0', '>=') && $pdo_enabled): ?>
                <a href="/sekolah/installer/database" class="btn btn-lg btn-alt-primary w-100">Start Installation</a>
            <?php else: ?>
                <div class="alert alert-danger">Please fix the requirements before proceeding.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
