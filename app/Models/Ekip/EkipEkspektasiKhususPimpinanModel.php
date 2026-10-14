<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipEkspektasiKhususPimpinanModel extends Model
{
    protected $table      = 'ekip_ekspektasi_khusus_pimpinan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'dinilai_id', 'penilai_id', 'tahun', 'bulan', 'minggu_ke',
        'berorientasi_pelayanan', 'akuntabel', 'kompeten', 
        'harmonis', 'loyal', 'adaptif', 'kolaboratif', 
        'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
