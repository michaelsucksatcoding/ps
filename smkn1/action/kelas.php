<?php
require_once "model.php";

class Kelas extends Model
{
    /** @var string[] */
    protected $fields = [
        'kode_kelas',
        'tingkat',
        'kelas',
        'kode_guru'
    ];
    /** @var string */
    protected $table = 'kelas';
    
}
