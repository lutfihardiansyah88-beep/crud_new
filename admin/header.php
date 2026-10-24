<?php
// admin/header.php
session_start();

// Cek apakah user sudah login, jika belum arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi CBA Sederhana</title>
</head>
<body>

    <!-- Header Judul -->
    <h2>PANEL ADMINISTRATOR - SISTEM CBA</h2>
    <hr>

    <!-- Struktur Layout Tabel: Navbar Kiri dan Konten Kanan (Tanpa CSS Rumit) -->
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
            <!-- Kolom Navigasi Kiri -->
            <td width="220" valign="top">
                <h3>Navigasi</h3>
                <p>Login sebagai: <br><b><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></b> (<?= htmlspecialchars($_SESSION['username']); ?>)</p>
                <hr>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="siswa.php">Data Siswa (JOIN)</a></li>
                    <li><a href="jurusan.php">Data Jurusan</a></li>
                    <br>
                    <li><a href="../auth/logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">Logout</a></li>
                </ul>
            </td>

            <!-- Kolom Konten Kanan -->
            <td valign="top">
