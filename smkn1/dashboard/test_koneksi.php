<?php
// File: dashboard/test_koneksi.php

// Ganti ke database yang benar
$db_name = "penilaian.db";

$conn = @mysqli_connect("localhost", "root", "", $db_name);

if (!$conn) {
    echo " Gagal connect ke database '$db_name'<br>";
    echo "Error: " . mysqli_connect_error() . "<br>";
} else {
    echo " Berhasil connect ke database '$db_name'<br><br>";
    
    // Lihat tabel yang ada
    $tables = mysqli_query($conn, "SHOW TABLES");
    echo "<h3>Tabel yang tersedia:</h3>";
    echo "<ul>";
    while ($row = mysqli_fetch_array($tables)) {
        echo "<li><b>" . $row[0] . "</b>";
        
        // Lihat struktur tiap tabel
        $columns = mysqli_query($conn, "SHOW COLUMNS FROM `" . $row[0] . "`");
        echo "<ul>";
        while ($col = mysqli_fetch_assoc($columns)) {
            echo "<li>" . $col['Field'] . " - " . $col['Type'] . "</li>";
        }
        echo "</ul>";
        echo "</li>";
    }
    echo "</ul>";
    
    mysqli_close($conn);
}
?>