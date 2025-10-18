<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Manage Student Attendance</h3>
        </div>
        <div class="block-content bg-body-light">
            <form action="/sekolah/attendance/teacher_manage_attendance" method="GET">
                <div class="row items-push">
                    <div class="col-md-5">
                        <label for="mapel_id" class="form-label">Mata Pelajaran</label>
                        <select class="form-select" id="mapel_id" name="mapel_id">
                            <option value="">Select Mata Pelajaran</option>
                            <?php foreach ($mata_pelajaran_taught as $mapel): ?>
                                <option value="<?= $mapel['id'] ?>" <?= ($mapel['id'] == $selected_mapel_id) ? 'selected' : '' ?>><?= $mapel['nama_mapel'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= $selected_date ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="block-content">
            <?php if ($selected_mapel_id): ?>
                <form action="/sekolah/attendance/update_student_attendance_status" method="POST">
                    <input type="hidden" name="mapel_id" value="<?= $selected_mapel_id ?>">
                    <input type="hidden" name="date" value="<?= $selected_date ?>">
                    <table class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($students_attendance)): ?>
                                <?php foreach ($students_attendance as $student): ?>
                                    <tr>
                                        <td><?= $student['student_nama'] ?></td>
                                        <td>
                                            <select class="form-select" name="status[<?= $student['student_id'] ?>]">
                                                <option value="present" <?= ($student['attendance_status'] == 'present') ? 'selected' : '' ?>>Present</option>
                                                <option value="absent" <?= ($student['attendance_status'] == 'absent') ? 'selected' : '' ?>>Absent</option>
                                                <option value="late" <?= ($student['attendance_status'] == 'late') ? 'selected' : '' ?>>Late</option>
                                                <option value="on_leave" <?= ($student['attendance_status'] == 'on_leave') ? 'selected' : '' ?>>On Leave</option>
                                            </select>
                                            <input type="hidden" name="attendance_id[<?= $student['student_id'] ?>]" value="<?= $student['attendance_id'] ?>">
                                            <input type="hidden" name="user_id[<?= $student['student_id'] ?>]" value="<?= $student['student_id'] ?>">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-primary" name="update_single" value="<?= $student['student_id'] ?>">Update</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No students found for this subject.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary">Save All Changes</button>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center">Please select a Mata Pelajaran and Date to manage attendance.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
