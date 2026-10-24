<?php
// auth/logout.php
session_start();

// Hapus semua data session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect kembali ke halaman login
header("Location: login.php");
exit;
?>
