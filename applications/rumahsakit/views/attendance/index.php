<h1>Attendance Records</h1>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Check-in Time</th>
            <th>Check-out Time</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($attendances as $attendance) : ?>
            <tr>
                <td><?php echo $attendance['id']; ?></td>
                <td><?php echo $attendance['user_id']; ?></td>
                <td><?php echo $attendance['check_in_time']; ?></td>
                <td><?php echo $attendance['check_out_time']; ?></td>
                <td><?php echo $attendance['date']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
