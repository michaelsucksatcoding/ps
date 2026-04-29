<?php
if (isset($_POST['hapus-kelas'])) {
    
    foreach ($_POST['kode_kelas'] as $key => $value) {
        
        if($kelas->deleteData(['kode_kelas'],[$value])){

            echo "<script>alert('Hapus Data Kelas Berhasil');window.location = 'index.php';</script>";

        }else{

            $error = $kelas->getLastError();

            ?>

            <script>alert("<?php echo $error?>");window.location="";</script>

            <?php
        }
    }
}