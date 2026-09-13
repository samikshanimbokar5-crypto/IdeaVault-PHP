CREATE DATABASE IF NOT EXISTS ideavault_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ideavault_db;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    avatar_url VARCHAR(500) NULL,
    password_hash VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS project_ideas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    domain VARCHAR(80) NOT NULL,
    description TEXT NOT NULL,
    technologies VARCHAR(255) NOT NULL,
    difficulty ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    status ENUM('Idea', 'Planning', 'In progress', 'Completed') NOT NULL DEFAULT 'Idea',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO project_ideas (title, domain, description, technologies, difficulty, status) VALUES
('Local Events Finder', 'Community', 'A platform that lists student clubs, events, workshops, and local community meetups.', 'JavaScript, PHP, MySQL', 'Beginner', 'Planning'),
('Food Waste Monitor', 'FoodTech', 'A portal where students and cafeterias can log surplus food and reduce waste.', 'PHP, MySQL, Chart.js', 'Intermediate', 'Idea'),
('Green Campus Tracker', 'Green Campus Tracker', 'A dashboard for tracking campus energy use, recycling habits, and eco-friendly events.', 'Python, Power BI, MySQL', 'Advanced', 'Planning'),
('Smart Study Planner', 'Education', 'A simple app that helps students report and find lost items around campus.', 'PHP, MySQL, HTML', 'Intermediate', 'In progress'),
('Campusfind ai', 'Tech', 'found lost items in a campus', 'javascript,react,css', 'Intermediate', 'Completed');