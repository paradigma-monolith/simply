<?php

namespace App\Models;

use CodeIgniter\Model;

class InfografisPesawatModel extends Model
{
  protected $table = 'infografis_pesawat';
  protected $primaryKey = 'id_infografis_pesawat';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'created_at', 'unit_kerja', 'pesawat_datang_luar', 'crew_datang_luar', 'penumpang_datang_luar', 'pesawat_datang_negeri', 'crew_datang_negeri', 'penumpang_datang_negeri', 'pesawat_berangkat_luar', 'crew_berangkat_luar', 'penumpang_berangkat_luar', 'pesawat_berangkat_negeri', 'crew_berangkat_negeri', 'penumpang_berangkat_negeri', 'status', 'created_by', 'updated_by', 'updated_pnbp'
  ];
}
