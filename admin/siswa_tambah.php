<?php
require_once './config/koneksi.php';
require_once 'header.php';

// Ambil data jurusan untuk pilihan dropdown (Relasi)
$data_jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");

$pesan_error = "";

// Variabel untuk mempertahankan input pengguna jika validasi gagal
$nis = "";
$nama_siswa = "";
$jenis_kelamin = "";
$id_jurusan = "";
$alamat = "";

// PROSES SIMPAN DATA
if (isset($_POST['btn_simpan'])) {
    $nis = trim($_POST['nis']);
    $nama_siswa = trim($_POST['nama_siswa']);
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : "";
    $id_jurusan = trim($_POST['id_jurusan']);
    $alamat = trim($_POST['alamat']);

    // 1. VALIDASI: Cek field tidak boleh kosong
    if (empty($nis) || empty($nama_siswa) || empty($jenis_kelamin) || empty($id_jurusan) || empty($alamat)) {
        $pesan_error = "Semua form wajib diisi, tidak boleh ada yang kosong!";
    } else {
        // 2. VALIDASI: Cek apakah NIS sudah pernah digunakan (Duplikasi)
        $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'");
        if (mysqli_num_rows($cek_nis) > 0) {
            $pesan_error = "NIS '$nis' sudah terdaftar! Gunakan NIS yang lain.";
        } else {
            // 3. Simpan ke Database
            $query_insert = "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, id_jurusan, alamat) 
                             VALUES ('$nis', '$nama_siswa', '$jenis_kelamin', '$id_jurusan', '$alamat')";

            $simpan = mysqli_query($koneksi, $query_insert);

            if ($simpan) {
                // Berhasil, redirect ke daftar siswa
                header("Location: siswa.php?pesan=sukses_tambah");
                exit;
            } else {
                $pesan_error = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<h3>Tambah Data Siswa</h3>
<p><a href="siswa.php">&larr; Kembali ke Daftar Siswa</a></p>

<!-- Tampilkan Notifikasi Error Validasi -->
<?php if (!empty($pesan_error)): ?>
    <p style="color: red;"><b><?= $pesan_error; ?></b></p>
<?php endif; ?>

<form action="" method="POST">
    <table cellpadding="6">
        <tr>
            <td><label for="nis">NIS (Nomor Induk Siswa)</label></td>
            <td>: <input type="text" name="nis" id="nis" value="<?= htmlspecialchars($nis); ?>"
                    placeholder="Contoh: 1003"></td>
        </tr>
        <tr>
            <td><label for="nama_siswa">Nama Lengkap Siswa</label></td>
            <td>: <input type="text" name="nama_siswa" id="nama_siswa" value="<?= htmlspecialchars($nama_siswa); ?>"
                    size="35" placeholder="Contoh: Rian Pratama"></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:
                <label><input type="radio" name="jenis_kelamin" value="Laki-laki" <?= ($jenis_kelamin == 'Laki-laki') ? 'checked' : ''; ?>> Laki-laki</label>
                <label><input type="radio" name="jenis_kelamin" value="Perempuan" <?= ($jenis_kelamin == 'Perempuan') ? 'checked' : ''; ?>> Perempuan</label>
            </td>
        </tr>
        <tr>
            <td><label for="id_jurusan">Jurusan (Tabel Relasi)</label></td>
            <td>:
                <select name="id_jurusan" id="id_jurusan">
                    <option value="">-- Pilih Jurusan --</option>
                    <?php while ($jur = mysqli_fetch_assoc($data_jurusan)): ?>
                        <option value="<?= $jur['id_jurusan']; ?>" <?= ($id_jurusan == $jur['id_jurusan']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($jur['nama_jurusan']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top"><label for="alamat">Alamat Lengkap</label></td>
            <td>:
                <textarea name="alamat" id="alamat" rows="4" cols="35"><?= htmlspecialchars($alamat); ?></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" name="btn_simpan">Simpan Siswa</button>
                <a href="siswa.php"><button type="button">Batal</button></a>
            </td>
        </tr>
    </table>
</form>

<?php require_once 'footer.php'; ?>