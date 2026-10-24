<?php
require_once './config/koneksi.php';
require_once 'header.php';

// Notifikasi dari aksi lain (tambah, edit, hapus)
$pesan = "";
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'sukses_tambah') {
        $pesan = "Data siswa berhasil ditambahkan!";
    } elseif ($_GET['pesan'] == 'sukses_edit') {
        $pesan = "Data siswa berhasil diperbarui!";
    } elseif ($_GET['pesan'] == 'sukses_hapus') {
        $pesan = "Data siswa berhasil dihapus!";
    }
}

// QUERY JOIN: Menggabungkan tabel siswa dan tabel jurusan berdasarkan id_jurusan
$query = "SELECT siswa.*, jurusan.nama_jurusan 
          FROM siswa 
          INNER JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan 
          ORDER BY siswa.id_siswa DESC";

$result = mysqli_query($koneksi, $query);
?>

<h3>Data Siswa (Relasi / JOIN Tabel)</h3>

<?php if (!empty($pesan)): ?>
    <p style="color: green;"><b><?= $pesan; ?></b></p>
<?php endif; ?>

<p>
    <a href="siswa_tambah.php"><b>[ + ] Tambah Siswa Baru</b></a>
</p>

<!-- Tabel Data Siswa -->
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead>
        <tr bgcolor="#f0f0f0">
            <th width="40">No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Jenis Kelamin</th>
            <th>Jurusan (Hasil JOIN)</th>
            <th>Alamat</th>
            <th width="120">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td align="center"><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nis']); ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                    <td><b><?= htmlspecialchars($row['nama_jurusan']); ?></b></td>
                    <td><?= nl2br(htmlspecialchars($row['alamat'])); ?></td>
                    <td align="center">
                        <a href="siswa_edit.php?id=<?= $row['id_siswa']; ?>">Edit</a> |
                        <a href="siswa_hapus.php?id=<?= $row['id_siswa']; ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php
            endwhile;
        else:
            ?>
            <tr>
                <td colspan="7" align="center">Belum ada data siswa. Silakan klik tombol tambah.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once 'footer.php'; ?>