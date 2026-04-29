<?php
if (isset($_POST['hapus-guru'])) {
    
    foreach ($_POST['kode_guru'] as $key => $value) {
        
        if($guru->deleteData(['kode_guru'],[$value])){

            echo "<script>alert('Hapus Data Guru Berhasil');window.location = 'index.php';</script>";

        }else{

            $error = $guru->getLastError();

            ?>

            <script>alert("<?php echo $error?>");window.location="";</script>

            <?php
        }
    }
}