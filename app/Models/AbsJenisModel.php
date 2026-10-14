<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsJenisModel extends Model
{
  protected $table = 'abs_jenis';
  protected $primaryKey = 'id_abs_jenis';
  protected $useTimestamps = true;
  protected $allowedFields = ['jenis', 'created_by', 'updated_by'];
}
