<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">My Children</h3>
        </div>
        <div class="block-content">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($children)): ?>
                        <?php foreach ($children as $child): ?>
                        <tr>
                            <td><?= $child['nama'] ?></td>
                            <td><?= $child['email'] ?></td>
                            <td class="text-center">
                                <a href="/sekolah/users/view_child/<?= $child['id'] ?>" class="btn btn-sm btn-secondary">View Report</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No children linked to your account.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
