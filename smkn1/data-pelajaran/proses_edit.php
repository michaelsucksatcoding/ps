<?php
if (isset($_POST['edit-pelajaran'])) {

    if ($pelajaran->updateData([
        $_POST['kode_pelajaran'],
        $_POST['pelajaran'],
        $_POST['kkm']
    ],[
        'kode_pelajaran'
    ],[
        $_GET['kode_pelajaran']
    ])) {   
            
        echo "<script>alert('Edit Data Pelajaran Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $pelajaran->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "?kode_pelajaran=<?php echo $_GET['kode_pelajaran']?>";</script>
        
        <?php
    }
}