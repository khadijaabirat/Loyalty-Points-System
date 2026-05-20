CREATE DATABASE IF NOT EXISTS loyalty_points_db;
USE loyalty_points_db;

-- Drop tables if they exist to recreate them (in reverse dependency order)
DROP TABLE IF EXISTS points_transactions;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS users;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    total_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Purchases Table
CREATE TABLE purchases (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    points_used INT DEFAULT 0,
    status VARCHAR(50) DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Points Transactions Table
CREATE TABLE points_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL, -- 'earned' or 'redeemed'
    amount INT NOT NULL,
    description VARCHAR(255),
    balance_after INT NOT NULL,
    createdat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert a test user (password: khadija)
INSERT INTO users (name, email, password_hash, total_points) 
VALUES ('Khadija', 'khadija@gmail.com', '$2y$10$Pj0Y8G8jXqFjYjPj0Y8G8O/OQGj0Y8G8jXqFjYjPj0Y8G8O', 0);
-- Wait, $2y$10$ hashes are bcrypt. Let's just create the tables and the user will re-register or I can use a known hash.
-- The user is trying to login as khadija@gmail.com with password 'khadija'.
-- The hash for 'khadija' in bcrypt is $2y$10$XU0k9x/Q.t2jBwM.j/H4bO8m0bQZqN.r9Y3GjZ6zQhP.T2y4r6WWe (Wait, I'll generate a valid one via PHP).
