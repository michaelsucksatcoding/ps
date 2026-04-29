<?php
if (isset($_POST['edit-guru'])) {

    if ($guru->updateData([
        $_POST['kode_guru'],
        $_POST['nuptk'],
        $_POST['nama_depan'],
        $_POST['nama_belakang'],
        $_POST['tempat'],
        $_POST['tgl_lahir'],
        $_POST['jenis_kelamin'],
        $_POST['alamat'],
        $_POST['password'],
    ],[
        'kode_guru'
    ],[
        $_GET['kode_guru']
    ])) {
        
        echo "<script>alert('Edit Data Guru Berhasil');window.location = 'index.php';</script>";
    }else{

        $error = $guru->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "?kode_guru=<?php echo $_GET['kode_guru']?>";</script>
        
        <?php
    }
}