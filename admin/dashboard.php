<?php
require_once './config/koneksi.php';
require_once 'header.php';

// Hitung total data untuk statistik sederhana
$total_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$total_jurusan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jurusan"));
$total_users = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));
?>

<h3>Selamat Datang di Dashboard!</h3>
<p>Halo <b><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></b>, Anda berhasil login ke sistem manajemen data siswa.
</p>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr bgcolor="#f0f0f0">
            <th>Menu Data</th>
            <th>Jumlah Data</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data Siswa (Tabel Relasi)</td>
            <td align="center"><b><?= $total_siswa; ?></b> Siswa</td>
            <td><a href="siswa.php">Buka Data Siswa</a></td>
        </tr>
        <tr>
            <td>Data Jurusan (Tabel Induk)</td>
            <td align="center"><b><?= $total_jurusan; ?></b> Jurusan</td>
            <td><a href="jurusan.php">Buka Data Jurusan</a></td>
        </tr>
        <tr>
            <td>User Admin</td>
            <td align="center"><b><?= $total_users; ?></b> Akun</td>
            <td>-</td>
        </tr>
    </tbody>
</table>

<br>
<h4>Petunjuk Singkat Pengujian CBA:</h4>
<ol>
    <li>Data <b>Siswa</b> mengambil relasi nama jurusan dari tabel <b>Jurusan</b> menggunakan query
        <code>INNER JOIN</code>.</li>
    <li>Semua form telah dilengkapi validasi: tidak boleh kosong, cek duplikasi NIS, dan konfirmasi hapus data.</li>
</ol>

<?php require_once 'footer.php'; ?>