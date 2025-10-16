<?php 
$data['page_title'] = 'Register';
$this->view('templates/auth_header', $data);
?>

<div class="mb-3 text-center">
    <a class="link-fx fw-bold fs-1" href="#">
        <span class="text-dark">Aplikasi</span><span class="text-primary">Sekolah</span>
    </a>
    <p class="text-uppercase fw-bold fs-sm text-muted">Create an Account</p>
</div>

<div class="row g-0 justify-content-center">
    <div class="col-sm-8 col-xl-6">
        <form action="/sekolah/users/register" method="POST">
            <?php csrf_input(); ?>
            <div class="py-3">
                <div class="mb-4">
                    <input type="text" class="form-control form-control-lg form-control-alt <?php echo isset($errors['nama']) ? 'is-invalid' : ''; ?>" id="nama" name="nama" placeholder="Nama Lengkap" value="<?php echo $this->input->post('nama'); ?>">
                    <?php if (isset($errors['nama'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['nama'][0]; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <input type="email" class="form-control form-control-lg form-control-alt <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Email" value="<?php echo $this->input->post('email'); ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['email'][0]; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <input type="password" class="form-control form-control-lg form-control-alt <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Password">
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['password'][0]; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="btn w-100 btn-lg btn-hero btn-primary">
                    <i class="fa fa-fw fa-plus opacity-50 me-1"></i> Register
                </button>
                <p class="mt-3 mb-0 d-lg-flex justify-content-lg-between">
                    <a class="btn btn-sm btn-alt-secondary d-block d-lg-inline-block mb-1" href="/sekolah/users/login">
                        <i class="fa fa-sign-in-alt opacity-50 me-1"></i> Sign In
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php $this->view('templates/auth_footer', $data); ?>
