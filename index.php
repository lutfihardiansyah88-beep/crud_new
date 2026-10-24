<?php
session_start();

// Jika sudah login, langsung ke dashboard. Jika belum, ke login.
if (isset($_SESSION['login'])) {
    header("Location: admin/dashboard.php");
} else {
    header("Location: auth/login.php");
}
exit;
?>
