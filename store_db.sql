CREATE DATABASE IF NOT EXISTS store_db;
USE store_db;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  category VARCHAR(50) NOT NULL DEFAULT "Umum",
  price DECIMAL(12,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data Pengujian Awal
INSERT INTO products (name, category, price, stock) VALUES
('Mouse Wireless', 'Elektronik', 150000.00, 20),
('Keyboard Mechanical', 'Elektronik', 450000.00, 15),
('Buku Catatan A5', 'Alat Tulis', 25000.00, 50);