<?php

namespace App\Models;

use CodeIgniter\Model;

class SiveraAnophelesNyamukModel extends Model
{
    protected $table            = 'prl_sivera_anopheles_nyamuk';
    protected $primaryKey       = 'id_anopheles_nyamuk';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'id_wilayah_kerja',
        'lokasi',
        'jml_penangkap',
        'lama_penangkapan',
        'kondisi_cuaca',
        'suhu',
        'kelembaban',
        'kecepatan_angin',
        'spesies',
        'data_tangkapan',
        'total_dalam',
        'total_luar',
        'total_tertangkap',
        'ada_anopheles',
        'mbr',
        'mhd',
        'interpretasi',
        'keterangan',
        'created_by',
        'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
