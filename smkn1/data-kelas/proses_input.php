<?php
if (isset($_POST['input-kelas'])) {
    
    if ($kelas->inputData([
        'kode_kelas' => strtoupper($_POST['kode_kelas']),
        'tingkat' => strtoupper($_POST['tingkat']),
        'kelas' => strtoupper($_POST['kelas']),
        'kode_guru' => $_POST['kode_guru']
    ])) {
        
        echo "<script>alert('Input Data Kelas Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $kelas->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "";</script>

        <?php

    }
    
}