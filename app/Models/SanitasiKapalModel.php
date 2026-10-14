<?php

namespace App\Models;

use CodeIgniter\Model;

class SanitasiKapalModel extends Model
{
    protected $table            = 'tc_sanitasi_kapal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pelabuhan',
        'tanggal',
        'sertifikat_mencatat',
        'imo_no',
        'jenis_kapal',
        'detail_jenis_kapal',
        'nama_kapal',
        'bendera',
        'berat_gt',
        'kondisi_palka',
        'nama_pemeriksa',
        'alamat_pemeriksa',
        'area_diperiksa',
        'pejabat_penerbit',
        'keterangan',
        'tanda_tangan',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
