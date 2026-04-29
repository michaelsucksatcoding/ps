<?php
require_once "model.php";

class Admin extends Model
{
    /** @var string[] */
    protected $fields = [
        'id_admin',
        'nama_depan',
        'nama_belakang',
        'tempat',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'password',
        'level_admin'
    ];
    /** @var string */
    protected $table = 'admin';

}
