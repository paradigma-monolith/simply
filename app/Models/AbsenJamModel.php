<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenJamModel extends Model
{
  protected $table = 'absen_jam';
  protected $primaryKey = 'id_absen_jam';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'hari',
    'aturan_jam_masuk',
    'aturan_jam_pulang',
    'toleransi_keterlambatan',
    'created_by',
    'updated_by',
  ];
}
