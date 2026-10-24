<?php
session_start();
require_once './config/koneksi.php';

// Jika sudah login, lempar ke dashboard admin
if (isset($_SESSION['login'])) {
    header("Location: ../admin/dashboard.php");
    exit;
}

$pesan_error = "";

// Cek apakah tombol submit ditekan
if (isset($_POST['btn_login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 1. Validasi Input Kosong
    if (empty($username) || empty($password)) {
        $pesan_error = "Username dan Password wajib diisi!";
    } else {
        // Enkripsi password dengan MD5 sesuai database
        $password_md5 = md5($password);

        // Cari user di database
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password_md5'";
        $result = mysqli_query($koneksi, $query);

        // 2. Cek apakah user ditemukan
        if (mysqli_num_rows($result) === 1) {
            $data = mysqli_fetch_assoc($result);

            // Set Session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];

            // Redirect ke dashboard admin
            header("Location: ../admin/dashboard.php");
            exit;
        } else {
            $pesan_error = "Username atau Password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Halaman Login</title>
</head>

<body>

    <h2>Form Login</h2>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($pesan_error)): ?>
        <p style="color: red;"><?= $pesan_error; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <table>
            <tr>
                <td><label for="username">Username</label></td>
                <td>: <input type="text" name="username" id="username"
                        value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td>: <input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" name="btn_login">Login</button>
                </td>
            </tr>
        </table>
    </form>

    <p><i>Default akun: username: <b>admin</b> | password: <b>123</b></i></p>

</body>

</html>