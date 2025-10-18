<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Jabatan Management</h3>
        </div>
        <div class="block-content">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th>Jabatan Name</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jabatan as $jabatan): ?>
                    <tr>
                        <td><?= ucfirst($jabatan['nama_jabatan']) ?></td>
                        <td class="text-center">
                            <?php if (!in_array($jabatan['nama_jabatan'], ['superadmin', 'user'])): ?>
                                <a href="/sekolah/jabatan/edit/<?= $jabatan['id'] ?>" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Edit Permissions">
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
