<?php

namespace App\Models;

use CodeIgniter\Model;

class AlatKesehatanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tc_alat_kesehatan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'penerbangan_haji_h', 'penerbangan_haji_m', 'embarkasi', 'nomor_kloter', 'no_kontak',
        'items_p3k', 'items_emergensi', 'crew_nama', 'crew_ttd', 'pelapor_nama', 'pelapor_ttd'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
