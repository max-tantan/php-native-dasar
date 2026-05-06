<?php
require_once 'config/koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$error = '';
$data = null;
$status_options = ['Hadir', 'Izin', 'Sakit', 'Alpa'];

if (!$id) {
    $error = 'ID data absensi tidak valid.';
} elseif (isset($_POST['submit'])) {
    $nama_siswa = trim($_POST['nama_siswa']);
    $kelas = trim($_POST['kelas']);
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];

    if ($nama_siswa === '' || $kelas === '' || $tanggal === '' || !in_array($status, $status_options, true)) {
        $error = 'Semua data wajib diisi dengan benar.';
        $data = [
            'nama_siswa' => $nama_siswa,
            'kelas' => $kelas,
            'tanggal' => $tanggal,
            'status' => $status,
        ];
    } else {
        $stmt = $conn->prepare('UPDATE tb_absensi SET nama_siswa = ?, kelas = ?, tanggal = ?, status = ? WHERE id = ?');
        $stmt->bind_param('ssssi', $nama_siswa, $kelas, $tanggal, $status, $id);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        }

        $error = 'Data gagal diperbarui: ' . $conn->error;
        $stmt->close();
    }
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
    <title>Edit Data Absensi</title>
</head>
<body>
    <h2>Edit Absensi Siswa</h2>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($data): ?>
        <form action="edit.php?id=<?= $id; ?>" method="POST">
            <label>Nama Siswa:</label><br>
            <input type="text" name="nama_siswa" value="<?= htmlspecialchars($data['nama_siswa']); ?>" required><br><br>

            <label>Kelas:</label><br>
            <input type="text" name="kelas" value="<?= htmlspecialchars($data['kelas']); ?>" required><br><br>

            <label>Tanggal:</label><br>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']); ?>" required><br><br>

            <label>Status:</label><br>
            <select name="status" required>
                <?php foreach ($status_options as $option): ?>
                    <option value="<?= $option; ?>" <?= $data['status'] === $option ? 'selected' : ''; ?>>
                        <?= $option; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit" name="submit">Update Data</button>
            <a href="index.php">
                <button type="button">Batal</button>
            </a>
        </form>
    <?php else: ?>
        <a href="index.php">
            <button type="button">Kembali</button>
        </a>
    <?php endif; ?>
</body>
</html>
