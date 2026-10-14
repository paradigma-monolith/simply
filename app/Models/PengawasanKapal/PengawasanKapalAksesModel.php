<?php

namespace App\Models\PengawasanKapal;

use CodeIgniter\Model;

class PengawasanKapalAksesModel extends Model
{
  protected $table = 'pengawasan_kapal_akses';
  protected $primaryKey = 'id_pengawasan_kapal_akses';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_user', 'status', 'created_by', 'updated_by'];
}
