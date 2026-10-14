<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipPerilakuBerakhlakModel extends Model
{
    protected $table = 'ekip_perilaku_berakhlak';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'minggu_ke', 'bulan', 'tahun', 'pelayanan', 'akuntabel', 'kompeten', 'harmonis', 'loyal', 'adaptif', 'kolaboratif'];
    protected $useTimestamps = true;
}
