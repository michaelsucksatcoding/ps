<?php
if (isset($_POST['hapus-jurusan'])) {
    
    foreach ($_POST['kode_jurusan'] as $key => $value) {
        
        if($jurusan->deleteData(['kode_jurusan'],[$value])){

            echo "<script>alert('Hapus Data Jurusan Berhasil');window.location = 'index.php';</script>";

        }else{

            $error = $jurusan->getLastError();

            ?>

            <script>alert("<?php echo $error?>");window.location="";</script>

            <?php
        }
    }
}