CREATE TABLE user_jabatan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    jabatan_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (jabatan_id) REFERENCES jabatan(id) ON DELETE CASCADE
);