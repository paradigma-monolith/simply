<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenTargetAksesModel extends Model
{
  protected $table = 'absen_target_akses';
  protected $primaryKey = 'id_absen_target_akses';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_data_pegawai',
    'lokasi',
    'created_by',
    'updated_by',
  ];
}
