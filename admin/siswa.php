<?php
// admin/siswa.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/koneksi.php';

// Cek autentikasi login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

// =========================================================================
// 1. PROSES TAMBAH SISWA (MODAL CREATE)
// =========================================================================
if (isset($_POST['btn_tambah_siswa'])) {
    $nis           = trim($_POST['nis']);
    $nama_siswa    = trim($_POST['nama_siswa']);
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : "";
    $id_jurusan    = (int)$_POST['id_jurusan'];
    $alamat        = trim($_POST['alamat']);

    if (empty($nis) || empty($nama_siswa) || empty($jenis_kelamin) || empty($id_jurusan) || empty($alamat)) {
        $_SESSION['flash_pesan'] = "Semua form wajib diisi!";
        $_SESSION['flash_tipe']  = "danger";
    } else {
        $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'");
        if (mysqli_num_rows($cek_nis) > 0) {
            $_SESSION['flash_pesan'] = "NIS '$nis' sudah terdaftar! Gunakan NIS lain.";
            $_SESSION['flash_tipe']  = "danger";
        } else {
            $simpan = mysqli_query($koneksi, "
                INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, id_jurusan, alamat) 
                VALUES ('$nis', '$nama_siswa', '$jenis_kelamin', '$id_jurusan', '$alamat')
            ");

            if ($simpan) {
                $_SESSION['flash_pesan'] = "Data siswa berhasil ditambahkan!";
                $_SESSION['flash_tipe']  = "success";
            } else {
                $_SESSION['flash_pesan'] = "Gagal menambah data: " . mysqli_error($koneksi);
                $_SESSION['flash_tipe']  = "danger";
            }
        }
    }

    header("Location: siswa.php");
    exit;
}

// =========================================================================
// 2. PROSES UPDATE SISWA (MODAL EDIT)
// =========================================================================
if (isset($_POST['btn_edit_siswa'])) {
    $id_siswa      = (int)$_POST['id_siswa'];
    $nis           = trim($_POST['nis']);
    $nama_siswa    = trim($_POST['nama_siswa']);
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : "";
    $id_jurusan    = (int)$_POST['id_jurusan'];
    $alamat        = trim($_POST['alamat']);

    if (empty($nis) || empty($nama_siswa) || empty($jenis_kelamin) || empty($id_jurusan) || empty($alamat)) {
        $_SESSION['flash_pesan'] = "Semua form wajib diisi!";
        $_SESSION['flash_tipe']  = "danger";
    } else {
        $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis' AND id_siswa != '$id_siswa'");
        if (mysqli_num_rows($cek_nis) > 0) {
            $_SESSION['flash_pesan'] = "NIS '$nis' sudah digunakan oleh siswa lain!";
            $_SESSION['flash_tipe']  = "danger";
        } else {
            $update = mysqli_query($koneksi, "
                UPDATE siswa 
                SET nis = '$nis', 
                    nama_siswa = '$nama_siswa', 
                    jenis_kelamin = '$jenis_kelamin', 
                    id_jurusan = '$id_jurusan', 
                    alamat = '$alamat' 
                WHERE id_siswa = '$id_siswa'
            ");

            if ($update) {
                $_SESSION['flash_pesan'] = "Data siswa berhasil diperbarui!";
                $_SESSION['flash_tipe']  = "success";
            } else {
                $_SESSION['flash_pesan'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
                $_SESSION['flash_tipe']  = "danger";
            }
        }
    }

    header("Location: siswa.php");
    exit;
}

// =========================================================================
// 3. PROSES HAPUS SISWA (MODAL DELETE)
// =========================================================================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id_hapus = (int)$_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa = '$id_hapus'");
    if ($hapus) {
        $_SESSION['flash_pesan'] = "Data siswa berhasil dihapus!";
        $_SESSION['flash_tipe']  = "success";
    } else {
        $_SESSION['flash_pesan'] = "Gagal menghapus data: " . mysqli_error($koneksi);
        $_SESSION['flash_tipe']  = "danger";
    }

    header("Location: siswa.php");
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
// 5. QUERY JOIN DATA SISWA & JURUSAN (Semua Data, Tanpa Filter)
// =========================================================================
$sql = "
    SELECT 
        siswa.*, 
        jurusan.nama_jurusan 
    FROM siswa 
    INNER JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan 
    ORDER BY siswa.id_siswa DESC
";
$result_siswa = mysqli_query($koneksi, $sql);

// Simpan data siswa ke array
$siswa_list = [];
while ($row = mysqli_fetch_assoc($result_siswa)) {
    $siswa_list[] = $row;
}

// Ambil data jurusan untuk dropdown pilihan modal
$data_jurusan = mysqli_query($koneksi, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
$jurusan_array = [];
while ($j = mysqli_fetch_assoc($data_jurusan)) {
    $jurusan_array[] = $j;
}

// Panggil header
require_once 'header.php';
?>

<!-- Baris Judul & Tombol Tambah -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Siswa</h4>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah Siswa
    </button>
</div>

<!-- Alert Notifikasi -->
<?php if (!empty($pesan)): ?>
    <div class="alert alert-<?= $tipe_pesan; ?> alert-dismissible fade show" role="alert">
        <?= $pesan; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tabel Data Siswa Polos Bootstrap -->
<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th width="50" class="text-center">No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Jurusan (JOIN)</th>
                <th>Alamat</th>
                <th width="180" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (count($siswa_list) > 0):
                foreach ($siswa_list as $row): 
            ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nis']); ?></td>
                    <td><b><?= htmlspecialchars($row['nama_siswa']); ?></b></td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                    <td><?= htmlspecialchars($row['nama_jurusan']); ?></td>
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm text-white" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalDetail<?= $row['id_siswa']; ?>">
                            Detail
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEdit<?= $row['id_siswa']; ?>">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalHapus<?= $row['id_siswa']; ?>">
                            Hapus
                        </button>
                    </td>
                </tr>
            <?php 
                endforeach;
            else: 
            ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">Belum ada data siswa.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ========================================================================= -->
<!-- MODAL-MODAL DI LUAR TABEL                                                 -->
<!-- ========================================================================= -->

<!-- 1. MODAL TAMBAH SISWA -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="siswa.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Siswa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_siswa" class="form-control" placeholder="Nama Siswa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Jenis Kelamin</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="tambah_jk_l" value="Laki-laki" checked>
                            <label class="form-check-label" for="tambah_jk_l">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="tambah_jk_p" value="Perempuan">
                            <label class="form-check-label" for="tambah_jk_p">Perempuan</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan (Relasi)</label>
                        <select name="id_jurusan" class="form-select" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php foreach ($jurusan_array as $jur): ?>
                                <option value="<?= $jur['id_jurusan']; ?>"><?= htmlspecialchars($jur['nama_jurusan']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap siswa" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="btn_tambah_siswa" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. LOOP MODAL DETAIL, EDIT, HAPUS PER DATA -->
<?php foreach ($siswa_list as $row): ?>

    <!-- MODAL DETAIL -->
    <div class="modal fade" id="modalDetail<?= $row['id_siswa']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="35%" class="bg-light">NIS</th>
                            <td><?= htmlspecialchars($row['nis']); ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nama Siswa</th>
                            <td><b><?= htmlspecialchars($row['nama_siswa']); ?></b></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jenis Kelamin</th>
                            <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jurusan (JOIN)</th>
                            <td><?= htmlspecialchars($row['nama_jurusan']); ?></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Alamat</th>
                            <td><?= nl2br(htmlspecialchars($row['alamat'])); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEdit<?= $row['id_siswa']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="siswa.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_siswa" value="<?= $row['id_siswa']; ?>">

                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" value="<?= htmlspecialchars($row['nis']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" name="nama_siswa" class="form-control" value="<?= htmlspecialchars($row['nama_siswa']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Jenis Kelamin</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="edit_jk_l_<?= $row['id_siswa']; ?>" value="Laki-laki" <?= ($row['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="edit_jk_l_<?= $row['id_siswa']; ?>">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="edit_jk_p_<?= $row['id_siswa']; ?>" value="Perempuan" <?= ($row['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="edit_jk_p_<?= $row['id_siswa']; ?>">Perempuan</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select name="id_jurusan" class="form-select" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <?php foreach ($jurusan_array as $jur): ?>
                                    <option value="<?= $jur['id_jurusan']; ?>" <?= ($row['id_jurusan'] == $jur['id_jurusan']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($jur['nama_jurusan']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($row['alamat']); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="btn_edit_siswa" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS -->
    <div class="modal fade" id="modalHapus<?= $row['id_siswa']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data siswa:</p>
                    <p><b><?= htmlspecialchars($row['nama_siswa']); ?> (NIS: <?= htmlspecialchars($row['nis']); ?>)</b>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="siswa.php?aksi=hapus&id=<?= $row['id_siswa']; ?>" class="btn btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<?php require_once 'footer.php'; ?>