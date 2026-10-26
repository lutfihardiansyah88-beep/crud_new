<?php
// auth/login.php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

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

    // Validasi Input Kosong
    if (empty($username) || empty($password)) {
        $pesan_error = "Username dan Password wajib diisi!";
    } else {
        $password_md5 = md5($password);
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password_md5'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) === 1) {
            $data = mysqli_fetch_assoc($result);

            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem CBA</title>
    <!-- Bootstrap 5 CSS Lokal -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="login-box">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-1">Login Administrator</h4>
                        <p class="text-muted text-center small mb-3">Sistem Pengelolaan Data Siswa</p>

                        <?php if (!empty($pesan_error)): ?>
                            <div class="alert alert-danger py-2 small" role="alert">
                                <?= $pesan_error; ?>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label small">Username</label>
                                <input type="text" name="username" id="username" class="form-control" 
                                       placeholder="Username" 
                                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label small">Password</label>
                                <input type="password" name="password" id="password" class="form-control" 
                                       placeholder="Password" required>
                            </div>

                            <button type="submit" name="btn_login" class="btn btn-primary w-100">
                                Masuk
                            </button>
                        </form>

                        <div class="mt-3 text-center text-muted small border-top pt-2">
                            Default: <b>admin</b> / <b>123</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>