<?php
// config/koneksi.php

$host = "localhost";
$user = "root";
$pass = "";
$db = "db_cba_crud";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>