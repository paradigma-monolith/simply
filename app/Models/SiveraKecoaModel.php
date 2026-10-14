<?php

namespace App\Models;

use CodeIgniter\Model;

class SiveraKecoaModel extends Model
{
    protected $table            = 'prl_sivera_kecoa';
    protected $primaryKey       = 'id_kecoa';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal', 'id_wilayah_kerja', 'lokasi', 'nama_ruangan', 
        'perangkap_terpasang', 'jml_kecoa_tertangkap', 'spesies_tertangkap', 
        'rata_rata', 'pengendalian', 'created_by', 'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
