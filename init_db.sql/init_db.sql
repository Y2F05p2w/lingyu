CREATE DATABASE IF NOT EXISTS target_db;
USE target_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

INSERT INTO users (name, email) VALUES ('admin', 'admin@example.com');
INSERT INTO users (name, email) VALUES ('user1', 'user1@example.com');
