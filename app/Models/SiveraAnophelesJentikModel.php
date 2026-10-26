<?php

namespace App\Models;

use CodeIgniter\Model;

class SiveraAnophelesJentikModel extends Model
{
    protected $table            = 'prl_sivera_anopheles_jentik';
    protected $primaryKey       = 'id_anopheles_jentik';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'id_wilayah_kerja',
        'lokasi',
        'jenis_habitat',
        'jenis_habitat_lainnya',
        'kondisi_pencahayaan',
        'kondisi_vegetasi',
        'kondisi_air',
        'keterangan',
        'jml_habitat_disurvei',
        'jml_habitat_positif',
        'indeks_habitat',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
