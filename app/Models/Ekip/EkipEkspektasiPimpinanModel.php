<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipEkspektasiPimpinanModel extends Model
{
    protected $table = 'ekip_ekspektasi_pimpinan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_user', 'penilai_id', 'minggu_ke', 'bulan', 'tahun', 
        'pelayanan_nilai', 'pelayanan_catatan',
        'akuntabel_nilai', 'akuntabel_catatan',
        'kompeten_nilai', 'kompeten_catatan',
        'harmonis_nilai', 'harmonis_catatan',
        'loyal_nilai', 'loyal_catatan',
        'adaptif_nilai', 'adaptif_catatan',
        'kolaboratif_nilai', 'kolaboratif_catatan',
        'predikat_kinerja', 'predikat_perilaku',
        'feedback_kinerja', 'tanggapan_kinerja', 'status_feedback_kinerja',
        'feedback_perilaku', 'tanggapan_perilaku', 'status_feedback_perilaku'
    ];
    protected $useTimestamps = true;
}
