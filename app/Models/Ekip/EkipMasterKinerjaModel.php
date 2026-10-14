<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipMasterKinerjaModel extends Model
{
    protected $table = 'ekip_master_kinerja';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'tahun', 'jenis', 'bobot', 'rencana_hasil_kerja', 'iki', 'target', 'realisasi', 'link_bukti'];
    protected $useTimestamps = true;
}
