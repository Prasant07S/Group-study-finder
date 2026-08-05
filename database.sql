-- ============================================================
-- Group Study Finder - Database Schema
-- BCA 4th Semester Project | Tribhuvan University
-- Database: group_study_finder
-- ============================================================

CREATE DATABASE IF NOT EXISTS group_study_finder
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE group_study_finder;

-- ------------------------------------------------------------
-- 1. users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('student', 'admin') NOT NULL DEFAULT 'student',
  semester   TINYINT UNSIGNED DEFAULT NULL,
  phone      VARCHAR(20) DEFAULT NULL,
  bio        TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. subjects
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS subjects (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subject_name VARCHAR(100) NOT NULL,
  semester     TINYINT UNSIGNED DEFAULT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. study_groups
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS study_groups (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  group_name  VARCHAR(150) NOT NULL,
  subject_id  INT UNSIGNED NOT NULL,
  description TEXT DEFAULT NULL,
  created_by  INT UNSIGNED NOT NULL,
  location    VARCHAR(200) DEFAULT NULL,
  study_type  ENUM('online', 'offline') NOT NULL DEFAULT 'offline',
  max_members INT UNSIGNED NOT NULL DEFAULT 10,
  status      ENUM('open', 'closed', 'full') NOT NULL DEFAULT 'open',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_groups_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE RESTRICT,
  CONSTRAINT fk_groups_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. group_members
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS group_members (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  group_id  INT UNSIGNED NOT NULL,
  user_id   INT UNSIGNED NOT NULL,
  role      ENUM('creator', 'member') NOT NULL DEFAULT 'member',
  joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_group_user (group_id, user_id),
  CONSTRAINT fk_members_group FOREIGN KEY (group_id) REFERENCES study_groups(id) ON DELETE CASCADE,
  CONSTRAINT fk_members_user  FOREIGN KEY (user_id)  REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. study_sessions
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS study_sessions (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  group_id       INT UNSIGNED NOT NULL,
  session_date   DATE NOT NULL,
  session_time   TIME NOT NULL,
  duration_hours TINYINT UNSIGNED NOT NULL DEFAULT 2,
  topic          VARCHAR(200) NOT NULL,
  location       VARCHAR(200) DEFAULT NULL,
  notes          TEXT DEFAULT NULL,
  created_by     INT UNSIGNED NOT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_sessions_group   FOREIGN KEY (group_id)   REFERENCES study_groups(id) ON DELETE CASCADE,
  CONSTRAINT fk_sessions_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. reviews
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reviews (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  group_id   INT UNSIGNED NOT NULL,
  user_id    INT UNSIGNED NOT NULL,
  rating     TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment    TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_review_user_group (group_id, user_id),
  CONSTRAINT fk_reviews_group FOREIGN KEY (group_id) REFERENCES study_groups(id) ON DELETE CASCADE,
  CONSTRAINT fk_reviews_user  FOREIGN KEY (user_id)  REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Seed data
-- Password for all demo accounts: admin123
-- (bcrypt hash generated with password_hash('admin123', PASSWORD_DEFAULT))
-- ============================================================

-- Password for ALL accounts below: admin123
INSERT INTO users (name, email, password, role, semester, phone, bio) VALUES
('System Admin', 'admin@gsf.com', '$2y$10$dxc2FbstA/LGbohGMvjhKOZw5EBVz4/.5kwciP0SeCKbbhAro17fW', 'admin', NULL, NULL, 'System administrator'),
('Prasant Kafle', 'prasant@gmail.com', '$2y$10$dxc2FbstA/LGbohGMvjhKOZw5EBVz4/.5kwciP0SeCKbbhAro17fW', 'student', 4, '9800000001', 'BCA student interested in web development'),
('Ram Sharma', 'ram@gmail.com', '$2y$10$dxc2FbstA/LGbohGMvjhKOZw5EBVz4/.5kwciP0SeCKbbhAro17fW', 'student', 4, '9800000002', 'Loves database and networking'),
('Sita Thapa', 'sita@gmail.com', '$2y$10$dxc2FbstA/LGbohGMvjhKOZw5EBVz4/.5kwciP0SeCKbbhAro17fW', 'student', 3, '9800000003', 'Focus on programming fundamentals'),
('Hari Poudel', 'hari@gmail.com', '$2y$10$dxc2FbstA/LGbohGMvjhKOZw5EBVz4/.5kwciP0SeCKbbhAro17fW', 'student', 5, '9800000004', 'Interested in software engineering'),
('Gita Adhikari', 'gita@gmail.com', '$2y$10$dxc2FbstA/LGbohGMvjhKOZw5EBVz4/.5kwciP0SeCKbbhAro17fW', 'student', 4, '9800000005', 'Passionate about UI/UX and frontend');

INSERT INTO subjects (subject_name, semester) VALUES
('Web Technology', 4),
('Database Management System', 4),
('Operating System', 4),
('Software Engineering', 4),
('Numerical Methods', 4),
('Scripting Language', 4),
('C Programming', 1),
('Digital Logic', 1),
('Mathematics I', 1),
('Object Oriented Programming', 3),
('Data Structure & Algorithm', 3),
('System Analysis & Design', 3),
('Computer Networks', 5),
('Java Programming', 5),
('Computer Graphics', 5);

-- Sample group (created by Prasant, user id 2)
INSERT INTO study_groups (group_name, subject_id, description, created_by, location, study_type, max_members, status) VALUES
('PHP & MySQL Study Circle', 1, 'Weekly practice on PHP backend and MySQL queries for BCA project.', 2, 'Library Room 2', 'offline', 8, 'open'),
('DBMS Concept Group', 2, 'ER diagrams, normalization, and SQL practice sessions.', 3, 'Zoom', 'online', 10, 'open'),
('DSA Problem Solving', 11, 'LeetCode-style problems and algorithm discussion.', 2, 'Computer Lab', 'offline', 6, 'open');

INSERT INTO group_members (group_id, user_id, role) VALUES
(1, 2, 'creator'),
(1, 3, 'member'),
(1, 6, 'member'),
(2, 3, 'creator'),
(2, 2, 'member'),
(3, 2, 'creator'),
(3, 5, 'member');

INSERT INTO study_sessions (group_id, session_date, session_time, duration_hours, topic, location, notes, created_by) VALUES
(1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', 2, 'PHP Sessions & Cookies', 'Library Room 2', 'Bring laptop', 2),
(1, DATE_ADD(CURDATE(), INTERVAL 7 DAY), '10:00:00', 3, 'MySQL Joins & Subqueries', 'Library Room 2', NULL, 2),
(2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '16:00:00', 2, 'Normalization (1NF-3NF)', 'Zoom Meeting', 'Link shared in group', 3);

-- ============================================================
-- After importing this file in phpMyAdmin (SQL tab):
-- 1. Login as admin:   admin@gsf.com / admin123
--    or student:       prasant@gmail.com / admin123
-- 2. Delete or protect reset.php in production
-- ============================================================
