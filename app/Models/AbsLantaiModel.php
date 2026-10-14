<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsLantaiModel extends Model
{
  protected $table = 'abs_lantai';
  protected $primaryKey = 'id_abs_lantai';
  protected $useTimestamps = true;
  protected $allowedFields = ['lantai', 'created_by', 'updated_by'];
}
