<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiAksesModel extends Model
{
  protected $table = 'sinadi_akses';
  protected $primaryKey = 'id_sinadi_akses';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_user",
    "status",
    "akses_tim",
    "created_by",
    "updated_by",
  ];
}
