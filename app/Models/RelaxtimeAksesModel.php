<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeAksesModel extends Model
{
  protected $table = 'relaxtime_akses';
  protected $primaryKey = 'id_relaxtime_akses';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_user",
    "status",
    "created_by",
    "updated_by",
  ];
}
