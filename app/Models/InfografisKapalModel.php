<?php

namespace App\Models;

use CodeIgniter\Model;

class InfografisKapalModel extends Model
{
  protected $table = 'infografis_kapal';
  protected $primaryKey = 'id_infografis_kapal';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'created_at', 'unit_kerja', 'kapal_datang_luar', 'abk_datang_luar', 'penumpang_datang_luar', 'kapal_datang_negeri', 'abk_datang_negeri', 'penumpang_datang_negeri', 'kapal_berangkat_luar', 'abk_berangkat_luar', 'penumpang_berangkat_luar', 'kapal_berangkat_negeri', 'abk_berangkat_negeri', 'penumpang_berangkat_negeri', 'status', 'created_by', 'updated_by', 'updated_pnbp'
  ];
}
