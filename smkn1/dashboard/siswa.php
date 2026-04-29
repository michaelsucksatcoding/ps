<?php
require_once 'model.php';

class Siswa extends Model
{
    
    protected $fields = [
        'nis',
        'nama_depan',
        'nama_belakang',
        'tempat',
        'tgl_lahir',
        'nama_ortu',
        'jenis_kelamin',
        'agama',
        'alamat',
        'password',
        'kode_kelas',
        'kode_jurusan',
        'tahun_ajaran'
    ];
    protected $table = 'siswa';

}
