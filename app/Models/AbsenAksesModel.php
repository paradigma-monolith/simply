<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenAksesModel extends Model
{
  protected $table = 'absen_akses';
  protected $primaryKey = 'id_absen_akses';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_user",
    "status",
    "akses_wfa",
    "akses_edit_del",
    "created_by",
    "updated_by",
  ];
}
