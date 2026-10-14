<?php

namespace App\Models\PengawasanKapal;

use CodeIgniter\Model;

class PengawasanKapalTargetAksesModel extends Model
{
  protected $table = 'pengawasan_kapal_target_akses';
  protected $primaryKey = 'id_pengawasan_kapal_target_akses';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_data_pegawai', 'lokasi', 'created_by', 'updated_by'];
}
