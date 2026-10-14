<?php

namespace App\Models;

use CodeIgniter\Model;

class PengukuranKebugaranModel extends Model
{
    protected $table            = 'pengukuran_kebugaran';
    protected $primaryKey       = 'id_pengukuran_kebugaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'tanggal_pengukuran',
        'tekanan_darah_sistolik',
        'tekanan_darah_diastolik',
        'gula_darah_sewaktu',
        'kolesterol_total',
        'asam_urat',
        'lingkar_pinggang',
        'kategori_lingkar_pinggang',
        'berat_badan',
        'tinggi_badan',
        'imt',
        'kategori_imt',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_user' => 'required|integer',
        'tanggal_pengukuran' => 'required|valid_date',
        'tekanan_darah_sistolik' => 'required|numeric',
        'tekanan_darah_diastolik' => 'required|numeric',
        'gula_darah_sewaktu' => 'permit_empty|numeric',
        'kolesterol_total' => 'permit_empty|numeric',
        'asam_urat' => 'permit_empty|numeric',
        'lingkar_pinggang' => 'required|integer',
        'kategori_lingkar_pinggang' => 'permit_empty|string',
        'berat_badan' => 'required|numeric',
        'tinggi_badan' => 'required|numeric',
        'imt' => 'permit_empty|numeric',
        'kategori_imt' => 'permit_empty|string'
    ];
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