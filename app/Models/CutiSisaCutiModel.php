<?php

namespace App\Models;

use CodeIgniter\Model;

class CutiSisaCutiModel extends Model
{
  protected $table = 'cuti_sisa_cuti';
  protected $primaryKey = 'id_cuti_sisa_cuti';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_data_pegawai',
    'sisa_cuti_tahunan',
    'created_by',
    'updated_by'
  ];
}
