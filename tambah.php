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
    <title>Tambah Data Absensi</title>
</head>
<body>
    <h2>Tambah Absensi Siswa</h2>
    
    <form action="" method="POST">
        <label>Nama Siswa:</label><br>
        <input type="text" name="nama_siswa" required><br><br>

        <label>Kelas:</label><br>
        <input type="text" name="kelas" required><br><br>

        <label>Tanggal:</label><br>
        <input type="date" name="tanggal" required><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Alpa">Alpa</option>
        </select><br><br>

        <button type="submit" name="submit">Simpan Data</button>
        <a href="index.php">
            <button type="button">Batal</button>
        </a>
    </form>
</body>
</html>