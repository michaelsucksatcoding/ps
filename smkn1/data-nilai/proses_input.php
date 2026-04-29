<?php
if (isset($_POST['input-nilai'])) {
    
    if ($hnilai->inputData([
        'id_nilai' => $_POST['id_nilai'],
        'kode_kelas' => $_POST['kode_kelas'],
        'kode_jurusan' => $_POST['kode_jurusan'],
        'kode_guru' => $_POST['kode_guru'],
        'kode_pelajaran' => $_POST['kode_pelajaran'],
        'keterangan_nilai' => $_POST['keterangan_nilai'],
        'semester' => $_POST['semester'],
        'tahun_ajaran' => $_POST['tahun_ajaran']
    ])) { 
        
        for ($i=0; $i < count($_POST['nis']) ; $i++) { 
            
            $inputNilai = $dnilai->inputData([
                'id_nilai' => $_POST['id_nilai'],
                'nis' => $_POST['nis'][$i],
                'nilai' => $_POST['nilai'][$i]
            ]);
        }
        
        if ($inputNilai) {
            
            echo "<script>alert('Berhasil Input Nilai');</script>";

        }else{

            $error = $dnilai->getLastError();

            ?>

            <script>alert("<?php echo $error?>");window.location="";</script>

            <?php
        }

    }else{

        $error = $hnilai->getLastError();

        ?>

        <script>alert("<?php echo $error?>");window.location = "";</script>

        <?php

    }
    
}