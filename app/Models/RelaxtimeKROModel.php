<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeKROModel extends Model
{
  protected $table = 'relaxtime_kro';
  protected $primaryKey = 'id_relaxtime_kro';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_relaxtime_rkt",
    "digit_kro",
    "detail_digit_kro",
    "kode_kro",
    "deskripsi_kro",
    "created_by",
    "updated_by",
  ];
}
