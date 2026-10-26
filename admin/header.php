<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Panggil koneksi database
require_once __DIR__ . '/../config/koneksi.php';

// Cek autentikasi login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem CBA - Manajemen Data</title>
    <!-- Bootstrap 5 CSS Lokal -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body {
            background-color: #fdfdfd;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
        }

        .navbar-brand {
            font-weight: 600;
        }
    </style>
</head>

<body>

    <!-- Navbar Sederhana -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">SISTEM CRUD</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- 1. Menu Dashboard (Bisa diakses semua user) -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active fw-bold' : ''; ?>"
                            href="dashboard.php">Dashboard</a>
                    </li>

                    <!-- 2. Menu Data Siswa -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'siswa.php') ? 'active fw-bold' : ''; ?>"
                            href="siswa.php">Data Siswa</a>
                    </li>

                    <!-- 3. CONTOH MENU KHUSUS ADMIN (Tinggal hapus tanda /* dan */ jika ingin mengaktifkan) -->
                    <?php /* if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): */ ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == 'jurusan.php') ? 'active fw-bold' : ''; ?>"
                            href="jurusan.php">Data Jurusan</a>
                    </li>
                    <?php /* endif; */ ?>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-light small">
                        Login: <b><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'User'); ?></b>
                        <span class="badge bg-secondary text-uppercase ms-1">
                            <?= htmlspecialchars($_SESSION['role'] ?? 'user'); ?>
                        </span>
                    </span>
                    <a href="../auth/logout.php" class="btn btn-outline-light btn-sm"
                        onclick="return confirm('Apakah Anda yakin ingin logout?');">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Kontainer Konten Utama -->
    <main class="container main-content pb-4">