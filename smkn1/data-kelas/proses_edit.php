<?php
if (isset($_POST['edit-kelas'])) {

    $currentYear = date('Y');
    $nextYear = $currentYear + 1;

    if ($kelas->updateData([
        $_POST['kode_kelas'],
        $_POST['tingkat'],
        $_POST['kelas'],
        $_POST['kode_guru']
    ],[
        'kode_kelas'
    ],[
        $_GET['kode_kelas']
    ])) {   
            
        echo "<script>alert('Edit Data Kelas Berhasil');window.location = 'index.php';</script>";

    }else{

        $error = $kelas->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "?kode_kelas=<?php echo $_GET['kode_kelas']?>";</script>
        
        <?php
    }
}