<?php
// dashboard/model.php
// Disesuaikan dengan struktur database penilaian_db

$db_host = "localhost";
$db_user = "root";
$db_pass = "";  
$db_name = "penilaian_db";

// Koneksi ke database
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ============================================
// FUNGSI-FUNGSI DASHBOARD
// ============================================

/**
 * Get statistik untuk dashboard
 */
function getStatistik() {
    global $koneksi;
    
    $stats = [
        'total_siswa' => 0,
        'rata_rata' => 0,
        'per_kelas' => []
    ];
    
    // 1. Total siswa
    $query = "SELECT COUNT(*) as total FROM siswa";
    $result = mysqli_query($koneksi, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stats['total_siswa'] = (int)$row['total'];
    }
    
    // 2. Rata-rata nilai (dari detail_nilai)
    $query = "SELECT ROUND(AVG(nilai), 2) as rata FROM detail_nilai";
    $result = mysqli_query($koneksi, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $stats['rata_rata'] = $row['rata'] ?? 0;
    }
    
    // 3. Jumlah siswa per kelas
    $query = "SELECT k.kelas, COUNT(*) as jumlah 
              FROM siswa s 
              JOIN kelas k ON s.kode_kelas = k.kode_kelas 
              GROUP BY k.kelas";
    $result = mysqli_query($koneksi, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $stats['per_kelas'][] = $row;
        }
    }
    
    return $stats;
}

/**
 * Get semua data siswa
 */
function getSemuaSiswa() {
    global $koneksi;
    
    $query = "SELECT s.nis, 
                     CONCAT(s.nama_depan, ' ', s.nama_belakang) as nama_siswa,
                     k.kelas,
                     j.jurusan,
                     s.jenis_kelamin,
                     s.alamat
              FROM siswa s
              LEFT JOIN kelas k ON s.kode_kelas = k.kode_kelas
              LEFT JOIN jurusan j ON s.kode_jurusan = j.kode_jurusan
              ORDER BY k.kelas, nama_siswa";
    
    $result = mysqli_query($koneksi, $query);
    
    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Get detail nilai per siswa
 */
function getNilaiBySiswa($nis) {
    global $koneksi;
    $nis = mysqli_real_escape_string($koneksi, $nis);
    
    $query = "SELECT p.pelajaran as nama_mapel, 
                     p.kkm,
                     dn.nilai,
                     hn.semester,
                     hn.tahun_ajaran
              FROM detail_nilai dn
              JOIN header_nilai hn ON dn.id_nilai = hn.id_nilai
              JOIN pelajaran p ON hn.kode_pelajaran = p.kode_pelajaran
              WHERE dn.nis = '$nis'
              ORDER BY p.pelajaran";
              
    $result = mysqli_query($koneksi, $query);
    
    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Get rekapan nilai semua siswa
 */
function getRekapNilai() {
    global $koneksi;
    
    $query = "SELECT s.nis,
                     CONCAT(s.nama_depan, ' ', s.nama_belakang) as nama_siswa,
                     k.kelas,
                     j.jurusan,
                     COUNT(dn.id_nilai) as jumlah_mapel,
                     ROUND(AVG(dn.nilai), 2) as rata_rata,
                     SUM(CASE WHEN dn.nilai >= p.kkm THEN 1 ELSE 0 END) as tuntas,
                     SUM(CASE WHEN dn.nilai < p.kkm THEN 1 ELSE 0 END) as belum_tuntas
              FROM siswa s
              LEFT JOIN detail_nilai dn ON s.nis = dn.nis
              LEFT JOIN header_nilai hn ON dn.id_nilai = hn.id_nilai
              LEFT JOIN pelajaran p ON hn.kode_pelajaran = p.kode_pelajaran
              LEFT JOIN kelas k ON s.kode_kelas = k.kode_kelas
              LEFT JOIN jurusan j ON s.kode_jurusan = j.kode_jurusan
              GROUP BY s.nis
              ORDER BY k.kelas, nama_siswa";
    
    $result = mysqli_query($koneksi, $query);
    
    $data = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Get data siswa by NIS
 */
function getSiswaByNis($nis) {
    global $koneksi;
    $nis = mysqli_real_escape_string($koneksi, $nis);
    
    $query = "SELECT s.*, 
                     CONCAT(s.nama_depan, ' ', s.nama_belakang) as nama_lengkap,
                     k.kelas as nama_kelas,
                     j.jurusan as nama_jurusan
              FROM siswa s
              LEFT JOIN kelas k ON s.kode_kelas = k.kode_kelas
              LEFT JOIN jurusan j ON s.kode_jurusan = j.kode_jurusan
              WHERE s.nis = '$nis'";
              
    $result = mysqli_query($koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getTotalGuru($koneksi) {
    $query = "SELECT COUNT(*) as total FROM guru";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    return $data['total'];
}
function getTotalSiswa($koneksi) {
    $query = "SELECT COUNT(*) as total FROM siswa";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    return $data['total'];
}
function getTotalMapel($koneksi) {
    $query = "SELECT COUNT(*) as total FROM pelajaran";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    return $data['total'];
}

?>