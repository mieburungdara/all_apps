<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Role Management</h3>
        </div>
        <div class="block-content">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= ucfirst($role['role_name']) ?></td>
                        <td class="text-center">
                            <?php if (!in_array($role['role_name'], ['superadmin', 'user'])): ?>
                                <a href="/sekolah/roles/edit/<?= $role['id'] ?>" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Edit Permissions">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
