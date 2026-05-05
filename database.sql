CREATE DATABASE uts_perpustakaan_60324067
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE uts_perpustakaan_60324067;

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    kode_kategori VARCHAR(10) UNIQUE NOT NULL,
    nama_kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi, status) VALUES
('KAT-001', 'Pemrograman', 'Buku pemrograman', 'Aktif'),
('KAT-002', 'Database', 'Buku database', 'Aktif'),
('KAT-003', 'Jaringan', 'Buku jaringan', 'Aktif');