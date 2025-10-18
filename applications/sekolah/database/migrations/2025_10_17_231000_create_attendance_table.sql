CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    check_in_time DATETIME,
    check_out_time DATETIME,
    date DATE NOT NULL
);