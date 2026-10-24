<?php
// admin/siswa_hapus.php
session_start();
require_once './config/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Cek apakah parameter id tersedia
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_siswa = (int) $_GET['id'];

    // Hapus data siswa berdasarkan id_siswa
    $hapus = mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa = '$id_siswa'");

    if ($hapus) {
        header("Location: siswa.php?pesan=sukses_hapus");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
        echo "<br><a href='siswa.php'>Kembali</a>";
    }
} else {
    header("Location: siswa.php");
    exit;
}
?>