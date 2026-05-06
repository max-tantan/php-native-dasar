<?php
require_once 'config/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$error = '';
$data = null;

if (!$id) {
    $error = 'ID data absensi tidak valid.';
} elseif (isset($_POST['hapus'])) {
    $stmt = $conn->prepare('DELETE FROM tb_absensi WHERE id = ?');
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    }

    $error = 'Data gagal dihapus: ' . $conn->error;
    $stmt->close();
} else {
    $stmt = $conn->prepare('SELECT * FROM tb_absensi WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        $error = 'Data absensi tidak ditemukan.';
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Data Absensi</title>
</head>
<body>
    <h2>Hapus Data Absensi</h2>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error); ?></p>
        <a href="index.php">
            <button type="button">Kembali</button>
        </a>
    <?php elseif ($data): ?>
        <p>Apakah Anda yakin ingin menghapus data absensi berikut?</p>

        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Nama Siswa</th>
                <td><?= htmlspecialchars($data['nama_siswa']); ?></td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td><?= htmlspecialchars($data['kelas']); ?></td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td><?= htmlspecialchars($data['tanggal']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= htmlspecialchars($data['status']); ?></td>
            </tr>
        </table>

        <br>

        <form action="hapus.php?id=<?= $id; ?>" method="POST">
            <button type="submit" name="hapus">Ya, Hapus</button>
            <a href="index.php">
                <button type="button">Batal</button>
            </a>
        </form>
    <?php endif; ?>
</body>
</html>
