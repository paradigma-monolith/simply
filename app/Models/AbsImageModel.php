<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsImageModel extends Model
{
  protected $table = 'abs_image';
  protected $primaryKey = 'id_abs_image';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'image',
    'created_by',
    'updated_by'
  ];
}
