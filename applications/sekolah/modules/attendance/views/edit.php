<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit Attendance Record</h3>
        </div>
        <div class="block-content">
            <form action="/sekolah/attendance/update" method="POST">
                <input type="hidden" name="id" value="<?= $attendance['id'] ?>">
                <div class="mb-4">
                    <label class="form-label" for="user_id">User</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <?php foreach ($all_users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= ($user['id'] == $attendance['user_id']) ? 'selected' : '' ?>><?= $user['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= $attendance['date'] ?>">
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label" for="check_in_time">Check-in Time</label>
                        <input type="time" class="form-control" id="check_in_time" name="check_in_time" value="<?= $attendance['check_in_time'] ? date('H:i', strtotime($attendance['check_in_time'])) : '' ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="check_out_time">Check-out Time</label>
                        <input type="time" class="form-control" id="check_out_time" name="check_out_time" value="<?= $attendance['check_out_time'] ? date('H:i', strtotime($attendance['check_out_time'])) : '' ?>">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status">
                        <?php 
                        $statuses = ['present', 'absent', 'late', 'on_leave'];
                        foreach ($statuses as $status): ?>
                            <option value="<?= $status ?>" <?= ($status == $attendance['status']) ? 'selected' : '' ?>><?= ucfirst($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/sekolah/attendance/manage" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
