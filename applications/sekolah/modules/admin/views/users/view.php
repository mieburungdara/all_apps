<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">User Profile: <span class="text-primary"><?= $user['nama'] ?></span></h3>
        </div>
        <div class="block-content">
            <p><strong>Email:</strong> <?= $user['email'] ?></p>
            <p><strong>Roles:</strong> 
                <?php foreach($user_roles as $role): ?>
                    <span class="badge bg-primary"><?= $role ?></span>
                <?php endforeach; ?>
            </p>
        </div>
    </div>

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Linked Parents</h3>
        </div>
        <div class="block-content">
            <ul>
                <?php if (!empty($parents)): ?>
                    <?php foreach ($parents as $parent): ?>
                        <li><?= $parent['nama'] ?> (<?= $parent['email'] ?>)</li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No parents linked to this student.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Attendance History</h3>
        </div>
        <div class="block-content">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attendance_history)):
                        foreach ($attendance_history as $record):
                        ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($record['date'])) ?></td>
                            <td><?= $record['check_in_time'] ? date('H:i:s', strtotime($record['check_in_time'])) : '-' ?></td>
                            <td><?= $record['check_out_time'] ? date('H:i:s', strtotime($record['check_out_time'])) : '-' ?></td>
                            <td><span class="badge bg-primary"><?= ucfirst($record['status']) ?></span></td>
                        </tr>
                        <?php 
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="4" class="text-center">No attendance history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
