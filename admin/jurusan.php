<?php
// admin/jurusan.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/koneksi.php';

// Cek autentikasi login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

/*
 CONTOH PROTEKSI HALAMAN: Hanya Admin yang boleh mengakses Data Jurusan
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}
*/

// =========================================================================
// 1. PROSES TAMBAH JURUSAN (MODAL CREATE)
// =========================================================================
if (isset($_POST['btn_tambah_jurusan'])) {
    $nama_jurusan = trim($_POST['nama_jurusan']);

    if (empty($nama_jurusan)) {
        $_SESSION['flash_pesan'] = "Nama jurusan tidak boleh kosong!";
        $_SESSION['flash_tipe'] = "danger";
    } else {
        $cek = mysqli_query($koneksi, "SELECT * FROM jurusan WHERE nama_jurusan = '$nama_jurusan'");
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['flash_pesan'] = "Jurusan '$nama_jurusan' sudah terdaftar!";
            $_SESSION['flash_tipe'] = "danger";
        } else {
            $simpan = mysqli_query($koneksi, "INSERT INTO jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
            if ($simpan) {
                $_SESSION['flash_pesan'] = "Jurusan baru berhasil ditambahkan!";
                $_SESSION['flash_tipe'] = "success";
            } else {
                $_SESSION['flash_pesan'] = "Gagal menyimpan jurusan: " . mysqli_error($koneksi);
                $_SESSION['flash_tipe'] = "danger";
            }
        }
    }

    header("Location: jurusan.php");
    exit;
}

// =========================================================================
// 2. PROSES UPDATE JURUSAN (MODAL EDIT)
// =========================================================================
if (isset($_POST['btn_edit_jurusan'])) {
    $id_jurusan = (int) $_POST['id_jurusan'];
    $nama_jurusan = trim($_POST['nama_jurusan']);

    if (empty($nama_jurusan)) {
        $_SESSION['flash_pesan'] = "Nama jurusan tidak boleh kosong!";
        $_SESSION['flash_tipe'] = "danger";
    } else {
        $cek = mysqli_query($koneksi, "SELECT * FROM jurusan WHERE nama_jurusan = '$nama_jurusan' AND id_jurusan != '$id_jurusan'");
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['flash_pesan'] = "Nama jurusan '$nama_jurusan' sudah digunakan!";
            $_SESSION['flash_tipe'] = "danger";
        } else {
            $update = mysqli_query($koneksi, "UPDATE jurusan SET nama_jurusan = '$nama_jurusan' WHERE id_jurusan = '$id_jurusan'");
            if ($update) {
                $_SESSION['flash_pesan'] = "Data jurusan berhasil diperbarui!";
                $_SESSION['flash_tipe'] = "success";
            } else {
                $_SESSION['flash_pesan'] = "Gagal memperbarui jurusan: " . mysqli_error($koneksi);
                $_SESSION['flash_tipe'] = "danger";
            }
        }
    }

    header("Location: jurusan.php");
    exit;
}

// =========================================================================
// 3. PROSES HAPUS JURUSAN (MODAL DELETE)
// =========================================================================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id_jurusan = (int) $_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM jurusan WHERE id_jurusan = '$id_jurusan'");
    if ($hapus) {
        $_SESSION['flash_pesan'] = "Jurusan berhasil dihapus!";
        $_SESSION['flash_tipe'] = "success";
    } else {
        $_SESSION['flash_pesan'] = "Gagal menghapus jurusan: " . mysqli_error($koneksi);
        $_SESSION['flash_tipe'] = "danger";
    }

    header("Location: jurusan.php");
    exit;
}

// =========================================================================
// 4. FLASH MESSAGE
// =========================================================================
$pesan = "";
$tipe_pesan = "";
if (isset($_SESSION['flash_pesan'])) {
    $pesan = $_SESSION['flash_pesan'];
    $tipe_pesan = $_SESSION['flash_tipe'];
    unset($_SESSION['flash_pesan']);
    unset($_SESSION['flash_tipe']);
}

// =========================================================================
// 5. AMBIL SEMUA DATA JURUSAN BESERTA HITUNGAN SISWA (LEFT JOIN)
// =========================================================================
$sql = "
    SELECT 
        j.id_jurusan, 
        j.nama_jurusan, 
        COUNT(s.id_siswa) AS total_siswa 
    FROM jurusan j
    LEFT JOIN siswa s ON j.id_jurusan = s.id_jurusan
    GROUP BY j.id_jurusan, j.nama_jurusan
    ORDER BY j.id_jurusan ASC
";
$data_jurusan = mysqli_query($koneksi, $sql);

// Simpan data jurusan ke array
$jurusan_list = [];
while ($row = mysqli_fetch_assoc($data_jurusan)) {
    $jurusan_list[] = $row;
}

// Panggil template header
require_once 'header.php';
?>

<!-- Baris Judul & Tombol Tambah -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Jurusan</h4>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
        + Tambah Jurusan
    </button>
</div>

<!-- Alert Notifikasi -->
<?php if (!empty($pesan)): ?>
    <div class="alert alert-<?= $tipe_pesan; ?> alert-dismissible fade show" role="alert">
        <?= $pesan; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tabel Data Jurusan Polos Bootstrap -->
<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th width="50" class="text-center">No</th>
                <th width="120">ID Jurusan</th>
                <th>Nama Jurusan</th>
                <th>Jumlah Siswa</th>
                <th width="150" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (count($jurusan_list) > 0):
                foreach ($jurusan_list as $row):
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row['id_jurusan']; ?></td>
                        <td><b><?= htmlspecialchars($row['nama_jurusan']); ?></b></td>
                        <td><?= $row['total_siswa']; ?> Siswa</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalEditJurusan<?= $row['id_jurusan']; ?>">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalHapusJurusan<?= $row['id_jurusan']; ?>">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php
                endforeach;
            else:
                ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Belum ada data jurusan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ========================================================================= -->
<!-- MODAL-MODAL DI LUAR TABEL                                                 -->
<!-- ========================================================================= -->

<!-- 1. MODAL TAMBAH JURUSAN -->
<div class="modal fade" id="modalTambahJurusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jurusan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="jurusan.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama_jurusan" class="form-control" placeholder="Nama Jurusan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="btn_tambah_jurusan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. LOOP MODAL EDIT DAN HAPUS PER DATA -->
<?php foreach ($jurusan_list as $row): ?>

    <!-- MODAL EDIT JURUSAN -->
    <div class="modal fade" id="modalEditJurusan<?= $row['id_jurusan']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="jurusan.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_jurusan" value="<?= $row['id_jurusan']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Nama Jurusan</label>
                            <input type="text" name="nama_jurusan" class="form-control"
                                value="<?= htmlspecialchars($row['nama_jurusan']); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="btn_edit_jurusan" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS JURUSAN -->
    <div class="modal fade" id="modalHapusJurusan<?= $row['id_jurusan']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jurusan: <b><?= htmlspecialchars($row['nama_jurusan']); ?></b>?</p>
                    <p class="text-danger small mb-0">Catatan: Menghapus jurusan akan menghapus siswa di jurusan ini
                        (CASCADE).</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="jurusan.php?aksi=hapus&id=<?= $row['id_jurusan']; ?>" class="btn btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<?php require_once 'footer.php'; ?>