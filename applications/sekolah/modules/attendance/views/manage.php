<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Filters</h3>
        </div>
        <div class="block-content bg-body-light">
            <form action="/sekolah/attendance/manage" method="GET">
                <div class="row items-push">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="user_name" placeholder="User Name..." value="<?= $filters['user_name'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="start_date" value="<?= $filters['start_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="end_date" value="<?= $filters['end_date'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Manage All Attendance</h3>
        </div>
        <div class="block-content">
            <table class="table table-striped table-vcenter">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Date</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($all_attendance)):
                        foreach ($all_attendance as $record):
                        ?>
                        <tr>
                            <td><?= $record['user_name'] ?></td>
                            <td><?= date('d M Y', strtotime($record['date'])) ?></td>
                            <td><?= $record['check_in_time'] ? date('H:i:s', strtotime($record['check_in_time'])) : '-' ?></td>
                            <td><?= $record['check_out_time'] ? date('H:i:s', strtotime($record['check_out_time'])) : '-' ?></td>
                            <td><span class="badge bg-primary"><?= ucfirst($record['status']) ?></span></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/sekolah/attendance/edit/<?= $record['id'] ?>" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="/sekolah/attendance/delete/<?= $record['id'] ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure?')" data-bs-toggle="tooltip" title="Delete">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                        endforeach;
                    else:
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">No attendance records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <?php $query_string = http_build_query(array_merge($_GET, ['page' => $current_page - 1])); ?>
                    <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="/sekolah/attendance/manage?<?= $query_string ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php $query_string = http_build_query(array_merge($_GET, ['page' => $i])); ?>
                        <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                            <a class="page-link" href="/sekolah/attendance/manage?<?= $query_string ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php $query_string = http_build_query(array_merge($_GET, ['page' => $current_page + 1])); ?>
                    <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="/sekolah/attendance/manage?<?= $query_string ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
