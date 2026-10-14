<?php

namespace App\Models;

use CodeIgniter\Model;

class ArkabJenisSuratModel extends Model
{
  protected $table = 'arkab_jenis_surat';
  protected $primaryKey = 'id_arkab_jenis_surat';
  protected $useTimestamps = true;
  protected $allowedFields = ['jenis_surat', 'created_by', 'updated_by'];
}
