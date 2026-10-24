<?php
require_once './config/koneksi.php';
require_once 'header.php';

$pesan = "";
$tipe_pesan = "";

// 1. PROSES TAMBAH JURUSAN
if (isset($_POST['btn_tambah_jurusan'])) {
    $nama_jurusan = trim($_POST['nama_jurusan']);

    // Validasi input
    if (empty($nama_jurusan)) {
        $pesan = "Nama jurusan tidak boleh kosong!";
        $tipe_pesan = "red";
    } else {
        // Cek duplikasi
        $cek = mysqli_query($koneksi, "SELECT * FROM jurusan WHERE nama_jurusan = '$nama_jurusan'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan = "Jurusan tersebut sudah ada!";
            $tipe_pesan = "red";
        } else {
            $query_simpan = mysqli_query($koneksi, "INSERT INTO jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
            if ($query_simpan) {
                $pesan = "Berhasil menambahkan jurusan baru!";
                $tipe_pesan = "green";
            } else {
                $pesan = "Gagal menyimpan: " . mysqli_error($koneksi);
                $tipe_pesan = "red";
            }
        }
    }
}

// 2. PROSES HAPUS JURUSAN
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_jurusan = (int) $_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM jurusan WHERE id_jurusan = '$id_jurusan'");
    if ($hapus) {
        $pesan = "Jurusan berhasil dihapus!";
        $tipe_pesan = "green";
    } else {
        $pesan = "Gagal menghapus jurusan: " . mysqli_error($koneksi);
        $tipe_pesan = "red";
    }
}

// 3. AMBIL SEMUA DATA JURUSAN
$data_jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY id_jurusan ASC");
?>

<h3>Kelola Data Jurusan</h3>

<!-- Notifikasi Pesan -->
<?php if (!empty($pesan)): ?>
    <p style="color: <?= $tipe_pesan; ?>;"><b><?= $pesan; ?></b></p>
<?php endif; ?>

<!-- Form Tambah Jurusan -->
<fieldset>
    <legend><b>Tambah Jurusan Baru</b></legend>
    <form action="jurusan.php" method="POST">
        <label for="nama_jurusan">Nama Jurusan : </label>
        <input type="text" name="nama_jurusan" id="nama_jurusan" placeholder="Contoh: Akuntansi" required>
        <button type="submit" name="btn_tambah_jurusan">Simpan Jurusan</button>
    </form>
</fieldset>

<br>

<!-- Tabel Data Jurusan -->
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr bgcolor="#f0f0f0">
            <th width="50">No</th>
            <th>ID Jurusan</th>
            <th>Nama Jurusan</th>
            <th width="100">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        if (mysqli_num_rows($data_jurusan) > 0):
            while ($row = mysqli_fetch_assoc($data_jurusan)):
                ?>
                <tr>
                    <td align="center"><?= $no++; ?></td>
                    <td align="center"><?= $row['id_jurusan']; ?></td>
                    <td><?= htmlspecialchars($row['nama_jurusan']); ?></td>
                    <td align="center">
                        <a href="jurusan.php?aksi=hapus&id=<?= $row['id_jurusan']; ?>"
                            onclick="return confirm('Peringatan: Menghapus jurusan akan menghapus siswa terkait!')">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php
            endwhile;
        else:
            ?>
            <tr>
                <td colspan="4" align="center">Belum ada data jurusan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>