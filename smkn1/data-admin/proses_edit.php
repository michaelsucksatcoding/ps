<?php
if (isset($_POST['edit-admin'])) {

    if ($admin->updateData([
        $_POST['id_admin'],
        $_POST['nama_depan'],
        $_POST['nama_belakang'],
        $_POST['tempat'],
        $_POST['tgl_lahir'],
        $_POST['jenis_kelamin'],
        $_POST['alamat'],
        $_POST['password'],
        $_POST['level_admin']
    ],[
        'id_admin'
    ],[
        $_GET['id_admin']
    ])) {
        
        echo "<script>alert('Edit Data Admin Berhasil');window.location = 'index.php';</script>";
    }else{

        $error = $admin->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "?id_admin=<?php echo $_GET['id_admin']?>";</script>
        
        <?php
    }
}