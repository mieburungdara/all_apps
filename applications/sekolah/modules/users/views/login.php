<div class="p-4">
    <div class="mb-4">
        <h1 class="fs-2 fw-bold mb-1">Sign In</h1>
        <p class="fw-medium text-muted">
            Welcome, please login or <a href="/sekolah/users/register">sign up</a> for a new account.
        </p>
    </div>
    <?php if ($this->session->has_flash('error')): ?>
        <div class="alert alert-danger"><?= $this->session->get_flash('error') ?></div>
    <?php endif; ?>
    <?php if ($this->session->has_flash('success')): ?>
        <div class="alert alert-success"><?= $this->session->get_flash('success') ?></div>
    <?php endif; ?>
    <form action="/sekolah/users/login" method="POST">
        <div class="mb-4">
            <input type="email" class="form-control form-control-lg" name="email" placeholder="Email">
        </div>
        <div class="mb-4">
            <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a class="text-muted fs-sm fw-medium d-block d-lg-inline-block mb-1" href="#">
                    Forgot Password?
                </a>
            </div>
            <div>
                <button type="submit" class="btn btn-lg btn-alt-primary">
                    <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Sign In
                </button>
            </div>
        </div>
    </form>
</div>
