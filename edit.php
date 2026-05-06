<?php
require_once 'config/koneksi.php';

$id             = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$error          = '';
$data           = null;
$status_options = ['Hadir', 'Izin', 'Sakit', 'Alpa'];

if (!$id) {
    $error = 'ID data absensi tidak valid.';
} elseif (isset($_POST['submit'])) {
    $nama_siswa = trim($_POST['nama_siswa']);
    $kelas      = trim($_POST['kelas']);
    $tanggal    = $_POST['tanggal'];
    $status     = $_POST['status'];

    if ($nama_siswa === '' || $kelas === '' || $tanggal === '' || !in_array($status, $status_options, true)) {
        $error = 'Semua data wajib diisi dengan benar.';
        $data  = [
            'nama_siswa' => $nama_siswa,
            'kelas'      => $kelas,
            'tanggal'    => $tanggal,
            'status'     => $status,
        ];
    } else {
        $stmt = $conn->prepare('UPDATE tb_absensi SET nama_siswa = ?, kelas = ?, tanggal = ?, status = ? WHERE id = ?');
        $stmt->bind_param('ssssi', $nama_siswa, $kelas, $tanggal, $status, $id);

        if ($stmt->execute()) {
            header('Location: index.php?msg=' . urlencode('Data berhasil diperbarui'));
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Absensi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Beranda</a>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
            <span>Edit Data</span>
        </div>

        <h2>Edit Absensi Siswa</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($data): ?>
            <form action="edit.php?id=<?= $id; ?>" method="POST" id="mainForm">
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa"
                           value="<?= htmlspecialchars($data['nama_siswa']); ?>"
                           placeholder="Masukkan nama siswa" required>
                    <span class="error-hint">Nama siswa tidak boleh kosong</span>
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas"
                           value="<?= htmlspecialchars($data['kelas']); ?>"
                           placeholder="Contoh: XII IPA 1" required>
                    <span class="error-hint">Kelas tidak boleh kosong</span>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal"
                           value="<?= htmlspecialchars($data['tanggal']); ?>" required>
                    <span class="error-hint">Pilih tanggal yang valid</span>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <?php foreach ($status_options as $option): ?>
                            <option value="<?= $option; ?>"
                                    <?= $data['status'] === $option ? 'selected' : ''; ?>>
                                <?= $option; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit" class="btn" id="submitBtn">
                        Update Data
                    </button>
                    <a href="index.php" class="btn-cancel-link">
                        <button type="button" class="btn btn-secondary">Batal</button>
                    </a>
                </div>
            </form>
        <?php else: ?>
            <div class="form-actions">
                <a href="index.php" class="btn-cancel-link">
                    <button type="button" class="btn btn-secondary">Kembali</button>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const form = document.getElementById('mainForm');

        if (form) {
            form.addEventListener('submit', function () {
                const btn = document.getElementById('submitBtn');
                btn.classList.add('loading');

                setTimeout(() => {
                    if (!this.checkValidity()) {
                        btn.classList.remove('loading');
                    }
                }, 50);
            });
        }
    </script>
</body>
</html>
