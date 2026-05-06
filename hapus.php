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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Data Absensi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .confirmation-text {
            text-align: center;
            margin-bottom: 20px;
            color: var(--text-main);
            font-size: 16px;
        }
        .detail-table {
            margin: 0 auto 24px auto;
            max-width: 500px;
        }
        .detail-table th {
            width: 30%;
            background-color: var(--bg-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hapus Data Absensi</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error); ?></div>
            <div class="form-actions" style="max-width: 500px; margin: 0 auto;">
                <a href="index.php" style="flex:1; display:flex;">
                    <button type="button" class="btn btn-secondary" style="width:100%;">Kembali</button>
                </a>
            </div>
        <?php elseif ($data): ?>
            <p class="confirmation-text">Apakah Anda yakin ingin menghapus data absensi berikut?</p>

            <table class="detail-table">
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

            <form action="hapus.php?id=<?= $id; ?>" method="POST">
                <div class="form-actions">
                    <button type="submit" name="hapus" class="btn btn-danger">Ya, Hapus</button>
                    <a href="index.php" style="flex:1; display:flex;">
                        <button type="button" class="btn btn-secondary" style="width:100%;">Batal</button>
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
