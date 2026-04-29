<?php
require_once "model.php";

class Jurusan extends Model
{
    /** @var string[] */
    protected $fields = [
        'kode_jurusan',
        'jurusan'
    ];
    /** @var string */
    protected $table = 'jurusan';
    
}
