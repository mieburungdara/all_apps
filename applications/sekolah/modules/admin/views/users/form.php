<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"><?= isset($user) ? 'Edit User' : 'Add User' ?></h3>
        </div>
        <div class="block-content">
            <form action="/sekolah/admin/save_user" method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?? '' ?>">
                <div class="mb-4">
                    <label class="form-label" for="nama">Name</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $user['nama'] ?? '' ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?? '' ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <?php if (isset($user)): ?>
                        <div class="form-text">Leave blank to keep the current password.</div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label class="form-label">Roles</label>
                    <div>
                        <?php foreach ($all_roles as $role): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="<?= $role['id'] ?>" id="role-<?= $role['id'] ?>" name="roles[]" <?= in_array($role['role_name'], $user_roles ?? []) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="role-<?= $role['id'] ?>"><?= $role['role_name'] ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">Save User</button>
                    <a href="/sekolah/admin/users" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
