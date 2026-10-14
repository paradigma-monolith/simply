<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwLaboratoriumModel extends Model
{
  protected $table = 'uklw_laboratorium';
  protected $primaryKey = 'id_uklw_laboratorium';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_pasien',
    'laboratorium_kimia_klinik',
    'laboratorium_hematologi',
    'laboratorium_kualitatif',
    'created_by',
    'updated_by'
  ];
}
