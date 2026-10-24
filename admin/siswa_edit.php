<?php
require_once './config/koneksi.php';
require_once 'header.php';

// Cek apakah parameter ID ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: siswa.php");
    exit;
}

$id_siswa = (int) $_GET['id'];

// Ambil data siswa yang akan diedit
$query_siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'");
if (mysqli_num_rows($query_siswa) === 0) {
    header("Location: siswa.php");
    exit;
}

$siswa = mysqli_fetch_assoc($query_siswa);

// Ambil data jurusan untuk dropdown pilihan
$data_jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");

$pesan_error = "";

// PROSES UPDATE DATA
if (isset($_POST['btn_update'])) {
    $nis = trim($_POST['nis']);
    $nama_siswa = trim($_POST['nama_siswa']);
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : "";
    $id_jurusan = trim($_POST['id_jurusan']);
    $alamat = trim($_POST['alamat']);

    // 1. VALIDASI: Cek tidak boleh ada field yang kosong
    if (empty($nis) || empty($nama_siswa) || empty($jenis_kelamin) || empty($id_jurusan) || empty($alamat)) {
        $pesan_error = "Semua form wajib diisi, tidak boleh ada yang kosong!";
    } else {
        // 2. VALIDASI: Cek apakah NIS bentrok dengan siswa lain
        $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis' AND id_siswa != '$id_siswa'");
        if (mysqli_num_rows($cek_nis) > 0) {
            $pesan_error = "NIS '$nis' sudah dipakai siswa lain! Gunakan NIS yang berbeda.";
        } else {
            // 3. Update data ke database
            $query_update = "UPDATE siswa SET 
                                nis = '$nis',
                                nama_siswa = '$nama_siswa',
                                jenis_kelamin = '$jenis_kelamin',
                                id_jurusan = '$id_jurusan',
                                alamat = '$alamat'
                             WHERE id_siswa = '$id_siswa'";

            $update = mysqli_query($koneksi, $query_update);

            if ($update) {
                header("Location: siswa.php?pesan=sukses_edit");
                exit;
            } else {
                $pesan_error = "Gagal mengubah data: " . mysqli_error($koneksi);
            }
        }
    }
} else {
    // Isi awal form dari database
    $nis = $siswa['nis'];
    $nama_siswa = $siswa['nama_siswa'];
    $jenis_kelamin = $siswa['jenis_kelamin'];
    $id_jurusan = $siswa['id_jurusan'];
    $alamat = $siswa['alamat'];
}
?>

<h3>Edit Data Siswa</h3>
<p><a href="siswa.php">&larr; Kembali ke Daftar Siswa</a></p>

<!-- Tampilkan Notifikasi Error Validasi -->
<?php if (!empty($pesan_error)): ?>
    <p style="color: red;"><b><?= $pesan_error; ?></b></p>
<?php endif; ?>

<form action="" method="POST">
    <table cellpadding="6">
        <tr>
            <td><label for="nis">NIS (Nomor Induk Siswa)</label></td>
            <td>: <input type="text" name="nis" id="nis" value="<?= htmlspecialchars($nis); ?>" required></td>
        </tr>
        <tr>
            <td><label for="nama_siswa">Nama Lengkap Siswa</label></td>
            <td>: <input type="text" name="nama_siswa" id="nama_siswa" value="<?= htmlspecialchars($nama_siswa); ?>"
                    size="35" required></td>
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
                <select name="id_jurusan" id="id_jurusan" required>
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
                <textarea name="alamat" id="alamat" rows="4" cols="35"
                    required><?= htmlspecialchars($alamat); ?></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" name="btn_update">Simpan Perubahan</button>
                <a href="siswa.php"><button type="button">Batal</button></a>
            </td>
        </tr>
    </table>
</form>

<?php require_once 'footer.php'; ?>