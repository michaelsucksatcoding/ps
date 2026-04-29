<?php
require_once "model.php";

class DetailNilai extends Model
{
    /** @var string[] */
    protected $fields = [
        'id_nilai',
        'nis',
        'nilai'
    ];
    /** @var string */
    protected $table = 'detail_nilai';
    
}
