<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsRuangModel extends Model
{
  protected $table = 'abs_ruang';
  protected $primaryKey = 'id_abs_ruang';
  protected $useTimestamps = true;
  protected $allowedFields = ['ruang', 'created_by', 'updated_by'];
}
