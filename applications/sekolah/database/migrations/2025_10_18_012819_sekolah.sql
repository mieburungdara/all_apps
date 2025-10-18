CREATE TABLE guru_mapel (
    id INT PRIMARY KEY AUTO_INCREMENT,
    guru_id INT NOT NULL,
    mapel_id INT NOT NULL,
    FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
);