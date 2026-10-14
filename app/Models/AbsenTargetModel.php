<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenTargetModel extends Model
{
  protected $table = 'absen_target';
  protected $primaryKey = 'id_absen_target';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_unit_kerja',
    'ket_tempat',
    'polygon',
    'polygon_akses',
    'tolerance_meters',
  ];
}
