<?php
require_once "model.php";

class HeaderNilai extends Model
{
    
    protected $fields = [
        'id_nilai',
        'kode_kelas',
        'kode_jurusan',
        'kode_guru',
        'kode_pelajaran',
        'keterangan_nilai',
        'semester',
        'tahun_ajaran'
    ];
    protected $table = 'header_nilai';

    public function countDataCustom($fieldname, array $fields, array $record, $like)
    {
        
        try {
            
            $condition = $this->param($fields, $record);

            $countData = $this->db->query("SELECT COUNT(" . $fieldname . ") AS $fieldname FROM " . $this->table . " WHERE " . implode(' AND ', $condition) . " AND " . $fieldname . " LIKE '%" . $like . "%'");

            $countData->execute();

            $data = $countData->fetch();

            return $data;

        } catch (PDOException $e) {
            
            $this->error = $e->getMessage();

            return false;
            
        }
    }
    
}
