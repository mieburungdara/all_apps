<h1>Daily Attendance Summary</h1>

<table border="1">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Check-in Time</th>
            <th>Check-out Time</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($attendances as $attendance) : ?>
            <tr>
                <td><?php echo $attendance['user_id']; ?></td>
                <td><?php echo $attendance['check_in_time']; ?></td>
                <td><?php echo $attendance['check_out_time']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
