<?php
if (isset($_POST['input-guru'])) {
    
    if ($guru->inputData([
        'kode_guru' => $_POST['kode_guru'],
        'nuptk' => $_POST['nuptk'],
        'nama_depan' => $_POST['nama_depan'],
        'nama_belakang' => $_POST['nama_belakang'],
        'tempat' => $_POST['tempat'],
        'tgl_lahir' => $_POST['tgl_lahir'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'alamat' => $_POST['alamat'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    ])) {
        
        echo "<script>alert('Input Data Guru Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $guru->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "";</script>

        <?php

    }
    
}