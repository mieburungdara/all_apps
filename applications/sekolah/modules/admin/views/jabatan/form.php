<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit Permissions for: <span class="text-primary"><?= ucfirst($jabatan['nama_jabatan']) ?></span></h3>
        </div>
        <div class="block-content">
            <form action="/sekolah/jabatan/update" method="POST">
                <input type="hidden" name="jabatan_id" value="<?= $jabatan['id'] ?>">
                <div class="mb-4">
                    <label class="form-label">Permissions</label>
                    <div class="space-y-2">
                        <?php foreach ($permissions as $permission): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $permission['id'] ?>" id="perm-<?= $permission['id'] ?>" name="permissions[]" <?= in_array($permission['id'], $jabatan_permissions) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="perm-<?= $permission['id'] ?>"><?= $permission['permission_name'] ?></label>
                                <p class="fs-sm text-muted"><?= $permission['description'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                    <a href="/sekolah/jabatan" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
