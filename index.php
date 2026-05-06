<?php
require_once 'config/koneksi.php';

// Ambil statistik per status
$stats = [
    'total' => 0,
    'hadir' => 0,
    'izin'  => 0,
    'sakit' => 0,
    'alpa'  => 0,
];

$stat_query = $conn->query("SELECT status, COUNT(*) as count FROM tb_absensi GROUP BY status");

if ($stat_query) {
    while ($row = $stat_query->fetch_assoc()) {
        $stats[strtolower($row['status'])] = $row['count'];
        $stats['total'] += $row['count'];
    }
}

// Ambil semua data absensi
$query  = "SELECT id, nama_siswa, kelas, tanggal, status FROM tb_absensi ORDER BY id DESC";
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

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <span>Beranda</span>
        </div>

        <h2>Daftar Absensi Siswa</h2>

        <!-- Statistik Ringkasan -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-value"><?= $stats['total'] ?></div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-card">
                <div class="stat-value hadir"><?= $stats['hadir'] ?></div>
                <div class="stat-label">Hadir</div>
            </div>
            <div class="stat-card">
                <div class="stat-value izin"><?= $stats['izin'] ?></div>
                <div class="stat-label">Izin</div>
            </div>
            <div class="stat-card">
                <div class="stat-value sakit"><?= $stats['sakit'] ?></div>
                <div class="stat-label">Sakit</div>
            </div>
            <div class="stat-card">
                <div class="stat-value alpa"><?= $stats['alpa'] ?></div>
                <div class="stat-label">Alpa</div>
            </div>
        </div>

        <!-- Search & Aksi -->
        <div class="header-actions">
            <div class="search-container">
                <input type="text" id="searchInput" class="search-input"
                       placeholder="Cari nama atau kelas...">
            </div>
            <a href="tambah.php" class="btn">Tambah Data Baru</a>
        </div>

        <!-- Tabel Data -->
        <table id="dataTable">
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
                <?php $no = 1; ?>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $status_class = strtolower(htmlspecialchars($row['status']));
                    ?>
                        <tr class="data-row">
                            <td><?= $no++; ?></td>
                            <td class="searchable"><?= htmlspecialchars($row['nama_siswa']); ?></td>
                            <td class="searchable"><?= htmlspecialchars($row['kelas']); ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                            <td>
                                <span class="badge badge-<?= $status_class ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td class="action-links">
                                <a href="edit.php?id=<?= $row['id']; ?>">Edit</a>
                                <a href="hapus.php?id=<?= $row['id']; ?>" class="delete">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr id="emptyRow">
                        <td colspan="6" class="empty-state">Belum ada data absensi.</td>
                    </tr>
                <?php endif; ?>

                <tr id="noResultRow" style="display: none;">
                    <td colspan="6" class="empty-state">Data tidak ditemukan.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Toast Notification -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="toast-container" id="toastContainer">
            <div class="toast toast-success" id="successToast">
                <svg class="toast-icon" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span><?= htmlspecialchars($_GET['msg']); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // ---- Pencarian Real-time ----
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const filter   = this.value.toLowerCase();
                const rows     = document.querySelectorAll('.data-row');
                let hasVisible = false;

                rows.forEach(row => {
                    const cells = row.querySelectorAll('.searchable');
                    let text    = '';

                    cells.forEach(cell => {
                        text += cell.textContent.toLowerCase() + ' ';
                    });

                    if (text.includes(filter)) {
                        row.style.display = '';
                        hasVisible = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                const noResultRow = document.getElementById('noResultRow');
                const emptyRow    = document.getElementById('emptyRow');

                if (noResultRow) {
                    noResultRow.style.display = (!hasVisible && filter !== '' && !emptyRow)
                        ? ''
                        : 'none';
                }
            });
        }

        // ---- Toast Auto-hide ----
        const toast = document.getElementById('successToast');

        if (toast) {
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => toast.classList.remove('show'), 3000);

            setTimeout(() => {
                const container = document.getElementById('toastContainer');
                if (container) container.remove();

                // Bersihkan parameter msg dari URL
                const url = new URL(window.location);
                url.searchParams.delete('msg');
                window.history.replaceState({}, document.title, url);
            }, 3400);
        }
    </script>
</body>
</html>