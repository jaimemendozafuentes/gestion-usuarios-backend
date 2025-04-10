-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar esa base
USE user_management;

-- Eliminar tabla si ya existe (opcional, útil para testing)
DROP TABLE IF EXISTS users;

-- Crear tabla 'users'
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (email, password) VALUES (
  'jaime@example.com',
  '$2y$10$qQ1vHt2UBYHOE6qWwJ9Y/O2UV6Y8D4RDzDnli.XzkK6hF0wD1qMPu'
);

