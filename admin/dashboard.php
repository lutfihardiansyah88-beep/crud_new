<?php
// admin/dashboard.php
require_once __DIR__ . '/../config/koneksi.php';
require_once 'header.php';

// Hitung statistik data
$total_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$total_jurusan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jurusan"));
$total_laki = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jenis_kelamin = 'Laki-laki'"));
$total_perempuan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jenis_kelamin = 'Perempuan'"));
?>

<div class="mb-4">
    <h3>Dashboard</h3>
    <p class="text-muted">Halo <b><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></b>, selamat datang di panel administrator sistem CBA.</p>
</div>

<!-- Card Statistik Sederhana -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">Total Siswa</h6>
                <h2 class="fw-bold my-1"><?= $total_siswa; ?></h2>
                <small>Data siswa terdaftar</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">Total Jurusan</h6>
                <h2 class="fw-bold my-1"><?= $total_jurusan; ?></h2>
                <small>Jurusan tersedia</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">Siswa Laki-laki</h6>
                <h2 class="fw-bold my-1"><?= $total_laki; ?></h2>
                <small>Siswa</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6 class="card-title text-uppercase small">Siswa Perempuan</h6>
                <h2 class="fw-bold my-1"><?= $total_perempuan; ?></h2>
                <small>Siswi</small>
            </div>
        </div>
    </div>
</div>

<!-- Navigasi Cepat & Catatan Logika JOIN -->
<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-light fw-bold">Menu Cepat</div>
            <div class="card-body">
                <p class="card-text text-muted">Akses cepat ke pengelolaan data:</p>
                <div class="d-grid gap-2">
                    <a href="siswa.php" class="btn btn-outline-primary text-start">Kelola Data Siswa (JOIN) &rarr;</a>
                    <a href="jurusan.php" class="btn btn-outline-success text-start">Kelola Data Jurusan &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-light fw-bold">Catatan Logika Relasi & Query JOIN</div>
            <div class="card-body">
                <ol class="mb-0 ps-3 text-secondary small">
                    <li class="mb-2">
                        <b>Normalisasi Database:</b> Tabel <code>siswa</code> hanya menyimpan kolom <code>id_jurusan</code> (Foreign Key). Teks nama jurusan tidak disimpan berulang kali di tiap siswa untuk menghindari redundansi data.
                    </li>
                    <li class="mb-2">
                        <b>Kunci Relasi:</b> Menghubungkan Primary Key <code>jurusan.id_jurusan</code> dengan Foreign Key <code>siswa.id_jurusan</code>.
                    </li>
                    <li class="mb-2">
                        <b>Query INNER JOIN:</b>
                        <br><code>SELECT siswa.*, jurusan.nama_jurusan FROM siswa INNER JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan</code>
                        <br>Hanya menampilkan baris jika <code>id_jurusan</code> cocok di kedua tabel.
                    </li>
                    <li>
                        <b>ON DELETE CASCADE:</b> Menghapus jurusan akan secara otomatis menghapus siswa yang terhubung dengan jurusan tersebut.
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>