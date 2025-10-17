<?php
$errors = $this->session->get_flash('errors') ?? [];
$old_input = $this->session->get_flash('old_input') ?? [];

// Function to get old value
function old($key, $data, $default = '') {
    return $data[$key] ?? $default;
}
?>
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?= isset($user) ? 'Edit User' : 'Add User' ?></h3>
        </div>
        <div class="block-content">
            <form action="/sekolah/admin/save_user" method="POST">
                <input type="hidden" name="id" value="<?= old('id', $old_input, $user['id'] ?? '') ?>">
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
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password">
                    <?php if (isset($user)): ?>
                        <div class="form-text">Leave blank to keep the current password.</div>
                    <?php endif; ?>
                    <?php if (isset($errors['password'])) : ?>
                        <div class="invalid-feedback"><?= $errors['password'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="form-label">Roles</label>
                    <div>
                        <?php 
                        $user_role_ids = array_map(function($role) { return $role['id']; }, $user_roles ?? []);
                        $old_roles = old('roles', $old_input, $user_role_ids);
                        foreach ($all_roles as $role): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="<?= $role['id'] ?>" id="role-<?= $role['id'] ?>" name="roles[]" <?= in_array($role['id'], $old_roles) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="role-<?= $role['id'] ?>"><?= $role['role_name'] ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (isset($user) && in_array('Wali Murid', $user_roles)): ?>
                <hr>
                <div class="mb-4">
                    <label class="form-label">Manage Children</label>
                    <p class="fs-sm text-muted">Link this parent account to one or more student accounts.</p>
                    <select class="form-select" name="children[]" multiple size="8">
                        <?php foreach ($all_students as $student): ?>
                            <?php // Prevent a user from being their own child ?>
                            <?php if ($student['id'] == $user['id']) continue; ?>
                            <option value="<?= $student['id'] ?>" <?= in_array($student['id'], $child_ids) ? 'selected' : '' ?>><?= $student['nama'] ?> (<?= $student['email'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">Save User</button>
                    <a href="/sekolah/admin/users" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>