<?php
if (isset($_POST['input-siswa'])) {
    
    if ($siswa->inputData([
        'nis' => $_POST['nis'],
        'nama_depan' => $_POST['nama_depan'],
        'nama_belakang' => $_POST['nama_belakang'],
        'tempat' => $_POST['tempat'],
        'tgl_lahir' => $_POST['tgl_lahir'],
        'nama_ortu' => $_POST['nama_ortu'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'agama' => $_POST['agama'],
        'alamat' => $_POST['alamat'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'kode_kelas' => $_POST['kode_kelas'],
        'kode_jurusan' => $_POST['kode_jurusan'],
        'tahun_ajaran' => $_POST['tahun_ajaran'],
    ])) {
        
        echo "<script>alert('Input Data siswa Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $siswa->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "";</script>

        <?php

    }
    
}