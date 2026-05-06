<?php 
require_once 'config/koneksi.php';

  if(isset($_POST['submit'])) {
    $nama_siswa = $conn->real_escape_string($_POST['nama_siswa']);
    $kelas = $conn->real_escape_string($_POST['kelas']);
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];

    $query = "INSERT INTO tb_absensi (nama_siswa, kelas, tanggal, status) VALUES ('$nama_siswa', '$kelas', '$tanggal', '$status')";
    if ($conn->query($query) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
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
        <h2>Tambah Absensi Siswa</h2>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Siswa</label>
                <input type="text" name="nama_siswa" required>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" required>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Hadir">Hadir</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Alpa">Alpa</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" name="submit" class="btn">Simpan Data</button>
                <a href="index.php" style="flex:1; display:flex;">
                    <button type="button" class="btn btn-secondary" style="width:100%;">Batal</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>