<?php
require_once "model.php";

class Pelajaran extends Model
{
    /** @var string[] */
    protected $fields = [
        'kode_pelajaran',
        'pelajaran',
        'kkm'
    ];
    /** @var string */
    protected $table = 'pelajaran';

}
