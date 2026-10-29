<?php

namespace App\Models;

use CodeIgniter\Model;

class AircraftSanitationModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tc_aircraft_sanitation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pemilik', 'tanda_kebangsaan', 'tanggal', 'waktu', 
        'kabin_penumpang', 'kompartemen_toilet', 'galley', 'dokumen_kesehatan', 
        'pembuangan_kotoran', 'penanganan_air', 'keadaan_makanan', 'binatang_pengganggu', 
        'surat_sanitasi', 'kesimpulan', 'rekomendasi', 'tindakan', 
        'petugas_kabin_nama', 'petugas_kabin_ttd', 'petugas_bkk_nama', 'petugas_bkk_ttd',
        'foto_geotagging'
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
