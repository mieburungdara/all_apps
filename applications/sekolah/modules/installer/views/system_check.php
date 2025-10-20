<div class="hero-static col-md-6 d-flex align-items-center bg-body-extra-light">
    <div class="p-3 w-100">
        <div class="mb-3 text-center">
            <a class="link-fx fw-bold" href="#">
                <i class="fa fa-fire"></i>
                <span class="fs-4 text-body-color">dash</span><span class="fs-4 text-primary">mix</span>
            </a>
            <p class="text-uppercase fw-bold fs-sm text-muted">System Check (Step 2/3)</p>
        </div>

        <ul class="list-group">
            <?php foreach ($checks as $check): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $check['name'] ?>
                    <span class="badge rounded-pill bg-<?= $check['status'] ? 'success' : 'danger' ?>">
                        <?= $check['status'] ? '✅' : '❌' ?>
                    </span>
                </li>
                <?php if (!$check['status']): ?>
                    <li class="list-group-item list-group-item-warning fs-sm pt-0">
                        <small><?= $check['message'] ?></small>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <div class="mt-4 text-center">
            <?php if ($all_ok): ?>
                <a href="/sekolah/installer?step=3" class="btn btn-lg btn-hero btn-primary">
                    <i class="fa fa-fw fa-arrow-right opacity-50 me-1"></i> Continue to Database Setup
                </a>
            <?php else: ?>
                <button type="button" class="btn btn-lg btn-hero btn-secondary" disabled>Please fix issues to continue</button>
            <?php endif; ?>
        </div>
    </div>
</div>
