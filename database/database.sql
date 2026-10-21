CREATE DATABASE IF NOT EXISTS student_events_db;
USE student_events_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2)
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

INSERT INTO products (name, description, price) VALUES ('Livre de Sécurité Web', 'Guide complet OWASP', 25.00);
INSERT INTO products (name, description, price) VALUES ('Badge Étudiant', 'Badge officiel USTHB', 5.00);

INSERT INTO events (title, description, location, date) VALUES ('Soirée Étudiante', 'Soirée de rentrée universitaire', 'Alger Centre', '2026-11-10');
INSERT INTO events (title, description, location, date) VALUES ('Workshop Cybersecurity', 'Atelier pratique de sécurité web', 'USTHB', '2026-11-12');
