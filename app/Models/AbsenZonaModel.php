<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenZonaModel extends Model
{
  protected $table = 'absen_zona';
  protected $primaryKey = 'id_absen_zona';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'keterangan_tempat',
    'alamat',
    'latitude',
    'longitude',
    'status',
    'created_by',
    'updated_by',
  ];
}
