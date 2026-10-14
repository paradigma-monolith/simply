<?php

namespace App\Models\Abs;

use CodeIgniter\Model;

class RawatKendaraanModel extends Model
{
    protected $table = 'abs_rawat_kendaraan';
    protected $primaryKey = 'id_rawat';
    protected $allowedFields = [
        'id_kendaraan_kantor_daftar',
        'tgl_pengajuan',
        'keluhan',
        'estimasi_biaya',
        'bukti_foto',
        'status',
        'verifikator_1_id',
        'verifikator_1_tgl',
        'verifikator_1_catatan',
        'verifikator_2_id',
        'verifikator_2_tgl',
        'verifikator_2_catatan',
        'created_by',
        'updated_by',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
