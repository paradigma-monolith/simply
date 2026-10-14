<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipRekomendasiKebijakanModel extends Model
{
    protected $table            = 'ekip_rekomendasi_kebijakan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'dinilai_id',
        'penilai_id',
        'tahun',
        'bulan',
        'minggu_ke',
        'rencana_hasil_kerja',
        'iki',
        'target'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}