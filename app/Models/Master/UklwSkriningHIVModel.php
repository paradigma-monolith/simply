<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwSkriningHIVModel extends Model
{
  protected $table = 'uklw_skrining_hiv';
  protected $primaryKey = 'id_uklw_skrining_hiv';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_pasien',
    'skrining_hiv',
    'skrining_penyakit_tidak_menular',
    'created_by',
    'updated_by'
  ];
}
