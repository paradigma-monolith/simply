<?php

namespace App\Models;

use CodeIgniter\Model;

class SiveraLalatModel extends Model
{
    protected $table            = 'prl_sivera_lalat';
    protected $primaryKey       = 'id_lalat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal', 'id_wilayah_kerja', 'lokasi', 'temperatur', 'kelembapan', 
        'kecepatan_angin', 'musim', 't1', 't2', 't3', 't4', 't5', 't6', 't7', 't8', 't9', 't10',
        'titik_1', 'titik_2', 'titik_3', 'titik_4', 'titik_5', 'rata_rata', 'detail_lokasi', 'pengendalian', 'created_by', 'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
