<?php 
$data['page_title'] = 'Login';
$this->view('templates/auth_header', $data);
?>

<div class="mb-3 text-center">
    <a class="link-fx fw-bold fs-1" href="#">
        <span class="text-dark">Aplikasi</span><span class="text-primary">Sekolah</span>
    </a>
    <p class="text-uppercase fw-bold fs-sm text-muted">Sign In</p>
</div>

<div class="row g-0 justify-content-center">
    <div class="col-sm-8 col-xl-6">
        <form action="/sekolah/users/login" method="POST">
            <?php csrf_input(); ?>
            <div class="py-3">
                <div class="mb-4">
                    <input type="email" class="form-control form-control-lg form-control-alt" id="email" name="email" placeholder="Email">
                </div>
                <div class="mb-4">
                    <input type="password" class="form-control form-control-lg form-control-alt" id="password" name="password" placeholder="Password">
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="btn w-100 btn-lg btn-hero btn-primary">
                    <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Sign In
                </button>
                <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                    <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1" href="#">
                        <i class="fa fa-exclamation-triangle opacity-50 me-1"></i> Lupa password
                    </a>
                    <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1" href="/sekolah/users/register">
                        <i class="fa fa-plus opacity-50 me-1"></i> Akun Baru
                    </a>
                </p>
            </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-fw fa-sign-in-alt me-1"></i> Login
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="text-center my-3">
                                <a href="/sekolah/users/auth/login_google" class="btn btn-danger w-100">
                                    <i class="fab fa-google me-1"></i> Login with Google
                                </a>
                            </div>
    </div>
</div>

<?php $this->view('templates/auth_footer', $data); ?>