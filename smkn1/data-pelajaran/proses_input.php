<?php
if (isset($_POST['input-pelajaran'])) {
    
    if ($pelajaran->inputData([
        'kode_pelajaran' => $_POST['kode_pelajaran'],
        'pelajaran' => $_POST['pelajaran'],
        'kkm' => $_POST['kkm']
    ])) {
        
        echo "<script>alert('Input Data Pelajaran Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $pelajaran->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "";</script>

        <?php

    }
    
}