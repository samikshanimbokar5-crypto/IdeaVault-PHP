CREATE DATABASE IF NOT EXISTS ideavault_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ideavault_db;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    avatar_url VARCHAR(500) NULL,
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
('Campus Skill Exchange', 'EdTech', 'A peer-to-peer board where students trade short lessons and practical skills.', 'PHP, MySQL, JavaScript', 'Intermediate', 'Planning'),
('Plant Health Lens', 'AgriTech', 'A lightweight image-assisted guide for identifying common plant stress signals.', 'Python, Flask, OpenCV', 'Advanced', 'Idea'),
('Quiet Study Finder', 'Productivity', 'A campus map that helps students discover available low-noise study spaces.', 'Java, JDBC, MySQL', 'Beginner', 'In progress'),
('Budget Buddy', 'FinTech', 'A simple spending journal that turns monthly transactions into useful student habits.', 'HTML, CSS, PHP', 'Beginner', 'Completed');