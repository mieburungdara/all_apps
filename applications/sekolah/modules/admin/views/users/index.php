<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">User Management</h3>
            <div class="block-options">
                <a href="/sekolah/admin/add_user" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> Add User
                </a>
            </div>
        </div>
        <div class="block-content">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <th class="text-center" scope="row"><?= $user['id'] ?></th>
                        <td><a href="/sekolah/admin/view_user/<?= $user['id'] ?>"><?= $user['nama'] ?></a></td>
                        <td><?= $user['email'] ?></td>
                        <td>
                            <?php foreach($user['roles'] as $role): ?>
                                <span class="badge bg-primary"><?= $role ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="/sekolah/admin/edit_user/<?= $user['id'] ?>" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <a href="/sekolah/admin/delete_user/<?= $user['id'] ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to delete this user?')" data-bs-toggle="tooltip" title="Delete">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
