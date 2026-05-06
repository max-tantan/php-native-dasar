<?php 
require_once'config/koneksi.php';
 $query = "SELECT * FROM tb_absensi ORDER BY id DESC";
 $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Absensi Siswa</h2>
        
        <div class="header-actions">
            <a href="tambah.php" class="btn">Tambah Data Baru</a>
        </div>

        <table>
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
                        <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                        <td><?= $row['status']; ?></td>
                        <td class="action-links">
                            <a href="edit.php?id=<?= $row['id']; ?>">Edit</a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="delete" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                        </td>
                    </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='6' class='empty-state'>Belum ada data absensi.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>