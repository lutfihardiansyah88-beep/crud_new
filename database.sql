-- Buat Database
CREATE DATABASE IF NOT EXISTS db_cba_crud;
USE db_cba_crud;

-- 1. Tabel Users untuk Login & Role Hak Akses
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

-- Masukkan data user awal:
-- 1. Admin   -> Username: admin,   Password: 123, Role: admin
-- 2. user -> Username: user, Password: 123, Role: user
INSERT INTO users (username, password, nama_lengkap, role) VALUES 
('admin', MD5('123'), 'Administrator', 'admin'),
('user', MD5('123'), 'user Data', 'user');

-- Catatan Migrasi: Jika database dan tabel users sudah ada sebelumnya,
-- cukup jalankan perintah ALTER TABLE berikut di phpMyAdmin / SQL console:
-- ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER nama_lengkap;
-- UPDATE users SET role = 'admin' WHERE username = 'admin';
-- INSERT INTO users (username, password, nama_lengkap, role) VALUES ('user', MD5('123'), 'user Data', 'user');

-- 2. Tabel Jurusan (Tabel Induk / Relasi)
CREATE TABLE IF NOT EXISTS jurusan (
    id_jurusan INT AUTO_INCREMENT PRIMARY KEY,
    nama_jurusan VARCHAR(50) NOT NULL
);

-- Data awal jurusan
INSERT INTO jurusan (nama_jurusan) VALUES 
('Rekayasa Perangkat Lunak'),
('Teknik Komputer & Jaringan'),
('Multimedia');

-- 3. Tabel Siswa (Tabel Transaksi / Berelasi dengan Jurusan)
CREATE TABLE IF NOT EXISTS siswa (
    id_siswa INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(20) NOT NULL UNIQUE,
    nama_siswa VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    id_jurusan INT NOT NULL,
    alamat TEXT NOT NULL,
    FOREIGN KEY (id_jurusan) REFERENCES jurusan(id_jurusan) ON DELETE CASCADE
);

-- Data contoh siswa
INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, id_jurusan, alamat) VALUES 
('1001', 'Budi Santoso', 'Laki-laki', 1, 'Jl. Merdeka No. 10'),
('1002', 'Siti Aminah', 'Perempuan', 2, 'Jl. Mawar No. 5');
