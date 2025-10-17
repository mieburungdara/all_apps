<h1>Add Attendance Record</h1>

<?php echo validation_errors(); ?>

<?php echo form_open('attendance/add'); ?>

    <label for="user_id">User ID</label>
    <input type="input" name="user_id" /><br />

    <label for="check_in_time">Check-in Time</label>
    <input type="input" name="check_in_time" /><br />

    <label for="check_out_time">Check-out Time</label>
    <input type="input" name="check_out_time" /><br />

    <label for="date">Date</label>
    <input type="input" name="date" /><br />

    <input type="submit" name="submit" value="Add attendance record" />

</form>
