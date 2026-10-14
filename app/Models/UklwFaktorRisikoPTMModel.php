<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwFaktorRisikoPTMModel extends Model
{
  protected $table = 'uklw_faktor_risiko_ptm';
  protected $primaryKey = 'id_uklw_faktor_risiko_ptm';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_pasien',
    'skrining_penyakit_tidak_menular',
    'created_by',
    'updated_by'
  ];
}
