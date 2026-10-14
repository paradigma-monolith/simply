<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeUraianModel extends Model
{
  protected $table = 'relaxtime_uraian';
  protected $primaryKey = 'id_relaxtime_uraian';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "kode_uraian",
    "deskripsi_uraian",
    "created_by",
    "updated_by"
  ];
}
