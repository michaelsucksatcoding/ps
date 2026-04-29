<?php
if (isset($_POST['hapus-pelajaran'])) {
    
    foreach ($_POST['kode_pelajaran'] as $key => $value) {
        
        if($pelajaran->deleteData(['kode_pelajaran'],[$value])){

            echo "<script>alert('Hapus Data Pelajaran Berhasil');window.location = 'index.php';</script>";

        }else{

            $error = $pelajaran->getLastError();

            ?>

            <script>alert("<?php echo $error?>");window.location="";</script>

            <?php
        }
    }
}