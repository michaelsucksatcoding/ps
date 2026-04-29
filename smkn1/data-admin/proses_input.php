<?php
if (isset($_POST['input-admin'])) {
    
    if ($admin->inputData([
        'id_admin' => $_POST['id_admin'],
        'nama_depan' => $_POST['nama_depan'],
        'nama_belakang' => $_POST['nama_belakang'],
        'tempat' => $_POST['tempat'],
        'tgl_lahir' => $_POST['tgl_lahir'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'alamat' => $_POST['alamat'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'level_admin' => $_POST['level_admin']
    ])) {
        
        echo "<script>alert('Input Data Admin Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $admin->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "";</script>

        <?php

    }
    
}