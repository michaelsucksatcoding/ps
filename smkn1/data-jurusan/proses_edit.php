<?php
if (isset($_POST['edit-jurusan'])) {

    if ($jurusan->updateData([
        $_POST['kode_jurusan'],
        $_POST['jurusan']
    ],[
        'kode_jurusan'
    ],[
        $_GET['kode_jurusan']
    ])) {
        
        echo "<script>alert('Edit Data Jurusan Berhasil');window.location = 'index.php';</script>";
    }else{

        $error = $jurusan->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "?kode_jurusan=<?php echo $_GET['kode_jurusan']?>";</script>
        
        <?php
    }
}