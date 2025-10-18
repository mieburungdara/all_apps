<?php

class Create_attendance_table {
    public function up() {
        $db = new PDO('mysql:host=localhost;dbname=test', 'user', 'password');
        $sql = "CREATE TABLE attendance (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            check_in_time DATETIME,
            check_out_time DATETIME,
            date DATE NOT NULL
        );";
        $db->exec($sql);
    }

    public function down() {
        $db = new PDO('mysql:host=localhost;dbname=test', 'user', 'password');
        $sql = "DROP TABLE attendance;";
        $db->exec($sql);
    }
}
