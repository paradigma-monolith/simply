<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwSkriningTBCModel extends Model
{
  protected $table = 'uklw_skrining_tbc';
  protected $primaryKey = 'id_uklw_skrining_tbc';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_pasien',
    'skrining_tbc_gejala',
    'created_by',
    'updated_by'
  ];
}
