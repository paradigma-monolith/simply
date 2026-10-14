<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeROModel extends Model
{
  protected $table = 'relaxtime_ro';
  protected $primaryKey = 'id_relaxtime_ro';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_relaxtime_kro",
    "kode_relaxtime_ro",
    "deskripsi_ro",
    "vol_ro",
    "sat_ro",
    "tahun",
    "created_by",
    "updated_by",
  ];
}
