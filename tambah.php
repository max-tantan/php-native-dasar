<?php
require_once 'config/koneksi.php';

$error          = '';
$status_options = ['Hadir', 'Izin', 'Sakit', 'Alpa'];
$data           = [
    'nama_siswa' => '',
    'kelas'      => '',
    'tanggal'    => '',
    'status'     => 'Hadir',
];

if (isset($_POST['submit'])) {
    $nama_siswa = trim($_POST['nama_siswa']);
    $kelas      = trim($_POST['kelas']);
    $tanggal    = $_POST['tanggal'];
    $status     = $_POST['status'];

    // Simpan input untuk ditampilkan kembali jika gagal
    $data = [
        'nama_siswa' => $nama_siswa,
        'kelas'      => $kelas,
        'tanggal'    => $tanggal,
        'status'     => $status,
    ];

    if ($nama_siswa === '' || $kelas === '' || $tanggal === '' || !in_array($status, $status_options, true)) {
        $error = 'Semua data wajib diisi dengan benar.';
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_absensi (nama_siswa, kelas, tanggal, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $nama_siswa, $kelas, $tanggal, $status);

        if ($stmt->execute()) {
            header("Location: index.php?msg=" . urlencode("Data siswa berhasil ditambahkan"));
            exit();
        } else {
            $error = "Error: Gagal menyimpan data.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Absensi</title>
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
            <span>Tambah Data</span>
        </div>

        <h2>Tambah Absensi Siswa</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="" method="POST" id="mainForm">
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
                        <option value="<?= $option ?>"
                                <?= $data['status'] === $option ? 'selected' : '' ?>>
                            <?= $option ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" name="submit" class="btn" id="submitBtn">
                    Simpan Data
                </button>
                <a href="index.php" class="btn-cancel-link">
                    <button type="button" class="btn btn-secondary">Batal</button>
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('mainForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');

            setTimeout(() => {
                if (!this.checkValidity()) {
                    btn.classList.remove('loading');
                }
            }, 50);
        });
    </script>
</body>
</html>