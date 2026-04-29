<?php
if (isset($_POST['input-jurusan'])) {
    
    if ($jurusan->inputData([
        'kode_jurusan' => $_POST['kode_jurusan'],
        'jurusan' => $_POST['jurusan']
    ])) {
        
        echo "<script>alert('Input Data Jurusan Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $jurusan->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "";</script>

        <?php

    }
    
}