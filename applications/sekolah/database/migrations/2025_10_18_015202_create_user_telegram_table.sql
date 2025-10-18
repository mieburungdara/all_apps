CREATE TABLE user_telegram (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    telegram_chat_id VARCHAR(255) UNIQUE,
    verification_token VARCHAR(255) UNIQUE,
    is_verified BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);