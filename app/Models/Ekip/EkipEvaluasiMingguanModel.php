<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipEvaluasiMingguanModel extends Model
{
    protected $table = 'ekip_evaluasi_mingguan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'master_kinerja_id', 'rekomendasi_id', 'minggu_ke', 'bulan', 'tahun', 'realisasi', 'link_bukti', 'catatan_penilai'];
    protected $useTimestamps = true;
}
