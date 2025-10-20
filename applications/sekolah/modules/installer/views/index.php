<div class="hero-static col-md-6 d-flex align-items-center bg-body-extra-light">
    <div class="p-3 w-100">
        <div class="mb-3 text-center">
            <a class="link-fx fw-bold" href="index.html">
                <i class="fa fa-fire"></i>
                <span class="fs-4 text-body-color">dash</span><span class="fs-4 text-primary">mix</span>
            </a>
            <p class="text-uppercase fw-bold fs-sm text-muted">Installer</p>
        </div>
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-6">
                <form class="js-validation-signin" action="" method="POST">
                    <div class="py-3">
                        <div class="row">
                            <div class="col-md-9 mb-4">
                                <input type="text" class="form-control form-control-lg form-control-alt" id="db_host" name="db_host" placeholder="Database Host" value="<?= $db_config['hostname'] ?? '127.0.0.1' ?>">
                            </div>
                            <div class="col-md-3 mb-4">
                                <input type="text" class="form-control form-control-lg form-control-alt" id="db_port" name="db_port" placeholder="Port" value="<?= $db_config['port'] ?? '3306' ?>">
                            </div>
                        </div>
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-lg form-control-alt" id="db_name" name="db_name" placeholder="Database Name" value="<?= $db_config['database'] ?? '' ?>">
                        </div>
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-lg form-control-alt" id="db_user" name="db_user" placeholder="Database User" value="<?= $db_config['username'] ?? '' ?>">
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control form-control-lg form-control-alt" id="db_pass" name="db_pass" placeholder="Database Password" value="<?= $db_config['password'] ?? '' ?>">
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="1" id="create_db" name="create_db">
                            <label class="form-check-label" for="create_db">Create database if it doesn't exist</label>
                        </div>
                        <div class="mb-4 text-center">
                            <button type="button" id="test_db_connection" class="btn btn-sm btn-secondary">Test Connection</button>
                            <div id="connection_status" class="mt-2 fs-sm"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" id="install_button" class="btn w-100 btn-lg btn-hero btn-primary">
                            <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Install
                        </button>
                    </div>

<script>
document.getElementById('test_db_connection').addEventListener('click', function() {
    const db_host = document.getElementById('db_host').value;
    const db_port = document.getElementById('db_port').value;
    const db_name = document.getElementById('db_name').value;
    const db_user = document.getElementById('db_user').value;
    const db_pass = document.getElementById('db_pass').value;
    const connection_status = document.getElementById('connection_status');

    connection_status.innerHTML = '<i class="fa fa-spinner fa-spin text-info"></i> Testing...';

    const formData = new FormData();
    formData.append('db_host', db_host);
    formData.append('db_port', db_port);
    formData.append('db_name', db_name);
    formData.append('db_user', db_user);
    formData.append('db_pass', db_pass);

    fetch('/sekolah/installer/check_db_connection', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            connection_status.innerHTML = '<span class="text-success"><i class="fa fa-check-circle"></i> ' + data.message + '</span>';
            document.getElementById('install_button').disabled = false;
        } else {
            connection_status.innerHTML = '<span class="text-danger"><i class="fa fa-times-circle"></i> ' + data.message + '</span>';
            document.getElementById('install_button').disabled = true;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        connection_status.innerHTML = '<span class="text-danger"><i class="fa fa-times-circle"></i> Error during test. Check console.</span>';
    });
});
// Disable install button by default
document.getElementById('install_button').disabled = true;
</script>
                </form>
            </div>
        </div>
    </div>
</div>