-- Create a database named 'puzzle_game'
CREATE DATABASE pets;

-- Use the created database
USE pets;

-- Create the 'users' table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each user
    username VARCHAR(50) NOT NULL,    -- Username of the user
    solve_time INT DEFAULT NULL       -- Time taken to solve the puzzle in seconds
);

SELECT * FROM users
ORDER BY solve_time ASC;