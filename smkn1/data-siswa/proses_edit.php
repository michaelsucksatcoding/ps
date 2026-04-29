<?php
if (isset($_POST['edit-siswa'])) {

    if ($siswa->updateData([
        $_POST['nis'],
        $_POST['nama_depan'],
        $_POST['nama_belakang'],
        $_POST['tempat'],
        $_POST['tgl_lahir'],
        $_POST['nama_ortu'],
        $_POST['jenis_kelamin'],
        $_POST['agama'],
        $_POST['alamat'],
        $_POST['password'],
        $_POST['kode_kelas'],
        $_POST['kode_jurusan'],
        $_POST['tahun_ajaran']
    ],[
        'nis'
    ],[
        $_GET['nis']
    ])) {
        
        echo "<script>alert('Edit Data Siswa Berhasil');window.location = 'index.php';</script>";
    }else{

        $error = $siswa->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "?nis=<?php echo $_GET['nis']?>";</script>
        
        <?php
    }
}