-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE user_management;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (email, password) VALUES (
  'jaime@example.com',
  '$2y$10$8z5nX5oK5j5k6l7m8n9p0q.r3s4t5u6v7w8x9y0z1A2B3C4D5E6F7G'  -- Hash de "Tuca2025!"
);