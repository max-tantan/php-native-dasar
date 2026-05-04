<?php 
require_once'config/koneksi.php';
 $query = "SELECT * FROM tb_absensi ORDER BY id DESC";
 $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Absensi</title>
</head>
<body>
    <h2>Daftar Absensi Siswa</h2>
    <a href="tambah.php">Tambah Data Baru</a>
    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Mengecek apakah ada data di dalam tabel
            if ($result->num_rows > 0) {
                // Looping data menggunakan fetch_assoc()
                while($row = $result->fetch_assoc()) { 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama_siswa']; ?></td>
                    <td><?= $row['kelas']; ?></td>
                    <td><?= $row['tanggal']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> | 
                        <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                    </td>
                </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='6' align='center'>Belum ada data absensi.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>