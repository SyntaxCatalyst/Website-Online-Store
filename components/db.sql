CREATE DATABASE IF NOT EXISTS `zizshop`; -- Pastikan nama database-nya 'zizshop'

USE `zizshop`;

-- Jika tabel 'users' sudah ada, ALTER TABLE
-- HATI-HATI! Jika kamu sudah punya data, pastikan ini tidak menghapus data yang ada.
-- Jalankan ini jika tabel users sudah ada tapi belum ada kolom 'role'
/*ALTER TABLE `users`

ADD COLUMN `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user' AFTER `password`;

-- Jika tabel 'users' belum ada, gunakan ini untuk membuat tabel dari awal:
-- (HAPUS ATAU KOMENTARI ALTER TABLE DI ATAS JIKA MENGGUNAKAN INI)
*/
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
