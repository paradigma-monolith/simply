<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiTracingTbModel extends Model
{
    protected $table = 'sinadi_tracing_tb';
    protected $primaryKey = 'id_tracing_tb';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'lokus',
        'instansi',
        'tanggal_kegiatan',
        'kode_kegiatan',
        'nama_kegiatan',
        'non_radiografer',
        'rincian_anggaran',
        'total_jasa_profesi',
        'total_perjalanan_dinas',
        'total_belanja_bahan',
        'total_anggaran',
        'catatan_pengaju',
        'status_pengajuan',
        'status_verif_bkk',
        'tgl_verif_bkk',
        'nama_verif_bkk',
        'no_hp_verif_bkk',
        'paraf_verif_bkk',
        'catatan_verif_bkk',
        'id_verif_bkk',
        'status_verif_pusat',
        'tgl_verif_pusat',
        'nama_verif_pusat',
        'paraf_verif_pusat',
        'catatan_verif_pusat',
        'id_verif_pusat',
        'created_by',
        'updated_by',
    ];
}
