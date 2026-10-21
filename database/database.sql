CREATE DATABASE IF NOT EXISTS student_events;
USE student_events;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    date DATE
);

INSERT INTO users (username, password, email, role) VALUES ('admin', 'admin123', 'admin@univ.dz', 'admin');
INSERT INTO users (username, password, email, role) VALUES ('maria', 'password123', 'maria@univ.dz', 'user');

INSERT INTO events (title, description, location, date) VALUES ('Soirée Étudiante', 'Soirée de rentrée universitaire', 'Alger Centre', '2026-11-10');
INSERT INTO events (title, description, location, date) VALUES ('Workshop Cybersecurity', 'Atelier pratique de sécurité web', 'USTHB', '2026-11-12');
