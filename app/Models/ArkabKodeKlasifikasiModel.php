<?php

namespace App\Models;

use CodeIgniter\Model;

class ArkabKodeKlasifikasiModel extends Model
{
  protected $table = 'arkab_kode_klasifikasi';
  protected $primaryKey = 'id_arkab_kode_klasifikasi';
  protected $useTimestamps = true;
  protected $allowedFields = ['kode', 'keterangan', 'created_by', 'updated_by'];
}
