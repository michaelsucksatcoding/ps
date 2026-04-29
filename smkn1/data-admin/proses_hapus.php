<?php
if (isset($_POST['hapus-admin'])) {
    
    foreach ($_POST['id_admin'] as $key => $value) {
        
        if($admin->deleteData(['id_admin'],[$value])){

            echo "<script>alert('Hapus Data Admin Berhasil');window.location = 'index.php';</script>";

        }else{

            $error = $admin->getLastError();

            ?>

            <script>alert("<?php echo $error?>");window.location="";</script>

            <?php
        }
    }
}