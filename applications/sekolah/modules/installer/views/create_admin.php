<div class="hero-static col-md-6 d-flex align-items-center bg-body-extra-light">
    <div class="p-3 w-100">
        <div class="mb-3 text-center">
            <a class="link-fx fw-bold" href="#">
                <i class="fa fa-fire"></i>
                <span class="fs-4 text-body-color">dash</span><span class="fs-4 text-primary">mix</span>
            </a>
            <p class="text-uppercase fw-bold fs-sm text-muted">Create Admin Account (Step 5/5)</p>
        </div>
        <div class="row g-0 justify-content-center">
            <div class="col-sm-8 col-xl-6">
                <form action="/sekolah/installer?step=5" method="POST">
                    <div class="py-3">
                        <div class="mb-4">
                            <input type="text" class="form-control form-control-lg form-control-alt" name="nama" placeholder="Full Name" required>
                        </div>
                        <div class="mb-4">
                            <input type="email" class="form-control form-control-lg form-control-alt" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control form-control-lg form-control-alt" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-4">
                            <select class="form-select form-control-lg form-control-alt" name="role_id">
                                <option value="" disabled selected>Select Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= ucfirst($role['nama_jabatan']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="btn w-100 btn-lg btn-hero btn-primary">
                            <i class="fa fa-fw fa-check-circle opacity-50 me-1"></i> Finish Installation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
