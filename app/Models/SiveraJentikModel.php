<?php

namespace App\Models;

use CodeIgniter\Model;

class SiveraJentikModel extends Model
{
    protected $table            = 'prl_sivera_jentik';
    protected $primaryKey       = 'id_jentik';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_wilayah_kerja', 'lokasi', 'bulan_pelaksanaan', 'tanggal_mulai', 
        'tanggal_selesai', 'kondisi_musim', 'wilayah', 'jml_rumah_diperiksa', 
        'jml_kontainer_diperiksa', 'jml_rumah_positif', 'jml_kontainer_positif', 
        'jenis_kontainer', 'pengendalian', 'created_by', 'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
