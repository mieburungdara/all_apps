<?php
$errors = $this->session->get_flash('errors') ?? [];
$old_input = $this->session->get_flash('old_input') ?? [];

function old($key, $data, $default = '') {
    return $data[$key] ?? $default;
}
?>
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Edit Profile</h3>
                </div>
                <div class="block-content">
                    <form action="/sekolah/users/profile" method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="mb-4">
                            <label class="form-label" for="nama">Name</label>
                            <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= old('nama', $old_input, $user['nama'] ?? '') ?>" required>
                            <?php if (isset($errors['nama'])) : ?>
                                <div class="invalid-feedback"><?= $errors['nama'][0] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email', $old_input, $user['email'] ?? '') ?>" required>
                            <?php if (isset($errors['email'])) : ?>
                                <div class="invalid-feedback"><?= $errors['email'][0] ?></div>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="mb-4">
                            <label class="form-label" for="password">New Password</label>
                            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password">
                            <div class="form-text">Leave blank to keep your current password.</div>
                             <?php if (isset($errors['password'])) : ?>
                                <div class="invalid-feedback"><?= $errors['password'][0] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">API Key</h3>
                </div>
                <div class="block-content">
                    <p class="fs-sm text-muted">
                        Your API key is used for authenticating with the API.
                    </p>
                    <div class="mb-4">
                        <input type="text" class="form-control" value="<?= $user['api_key'] ?? '' ?>" readonly>
                    </div>
                    <form action="/sekolah/users/profile" method="POST">
                         <input type="hidden" name="action" value="regenerate_key">
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to regenerate your API key? Your old key will stop working immediately.')">Regenerate Key</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
