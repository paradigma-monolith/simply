<?php

namespace App\Models;

use CodeIgniter\Model;

class SiveraTikusModel extends Model
{
    protected $table            = 'prl_sivera_tikus';
    protected $primaryKey       = 'id_tikus';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal', 'id_wilayah_kerja', 'lokasi', 'lokasi_pemasangan', 
        'jenis_umpan', 'spesies', 'jml_perangkap_dipasang', 'jml_perangkap_hilang', 
        'jml_tikus_tertangkap', 'success_trap', 'created_by', 'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
