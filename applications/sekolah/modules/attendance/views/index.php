<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Today's Attendance</h3>
                </div>
                <div class="block-content text-center">
                    <?php 
                    $checked_in = ($todays_attendance && $todays_attendance['check_in_time']);
                    $checked_out = ($todays_attendance && $todays_attendance['check_out_time']);
                    ?>

                    <?php if ($checked_in): ?>
                        <p><strong>Check-in Time:</strong> <?= date('H:i:s', strtotime($todays_attendance['check_in_time'])) ?></p>
                    <?php endif; ?>

                    <?php if ($checked_out): ?>
                        <p><strong>Check-out Time:</strong> <?= date('H:i:s', strtotime($todays_attendance['check_out_time'])) ?></p>
                    <?php endif; ?>

                    <form action="/sekolah/attendance/log" method="POST">
                        <?php if (!$checked_in): ?>
                            <button type="submit" class="btn btn-lg btn-success">Check In</button>
                        <?php elseif (!$checked_out): ?>
                            <button type="submit" class="btn btn-lg btn-warning">Check Out</button>
                        <?php else: ?>
                            <p class="text-success">You have completed your attendance for today. Thank you!</p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
