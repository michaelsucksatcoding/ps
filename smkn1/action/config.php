<?php
$servername = "localhost";
$dbname = "penilaian_db";
$username = "root";
$password = "";
$connect = null;

try {
    $connect = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

include_once __DIR__ . '/model.php';
include_once __DIR__ . '/admin.php';
include_once __DIR__ . '/guru.php';
include_once __DIR__ . '/jurusan.php';
include_once __DIR__ . '/kelas.php';
include_once __DIR__ . '/siswa.php';
include_once __DIR__ . '/pelajaran.php';
include_once __DIR__ . '/header_nilai.php';
include_once __DIR__ . '/detail_nilai.php';

$model = new Model($connect);
$admin = new Admin($connect);
$guru = new Guru($connect);
$jurusan = new Jurusan($connect);
$kelas = new Kelas($connect);
$siswa = new Siswa($connect);
$pelajaran = new Pelajaran($connect);
$hnilai = new HeaderNilai($connect);
$dnilai = new DetailNilai($connect);