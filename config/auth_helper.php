<?php
// config/auth_helper.php
// (FILE INI SAAT INI DI-DISABLE / HANYA SEBAGAI REFERENSI)
//
// Saat ini seluruh pengecekan role di aplikasi dibuat SUPER SEDERHANA langsung menggunakan:
// if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin')
//
// Jika di masa depan Anda ingin memakai fungsi helper, Anda bisa aktifkan kode di bawah ini:

/*
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function has_role($allowed_roles) {
    if (!isset($_SESSION['role'])) {
        return false;
    }
    if (is_array($allowed_roles)) {
        return in_array($_SESSION['role'], $allowed_roles);
    }
    return $_SESSION['role'] === $allowed_roles;
}

function is_admin() {
    return has_role('admin');
}

function require_role($allowed_roles, $redirect_url = 'dashboard.php') {
    if (!has_role($allowed_roles)) {
        header("Location: " . $redirect_url);
        exit;
    }
}
*/
