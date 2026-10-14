<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeRealisasiModel extends Model
{
  protected $table = 'relaxtime_realisasi';
  protected $primaryKey = 'id_relaxtime_realisasi';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_relaxtime_detail_uraian",
    "realisasi",
    "bulan",
    "tahun",
    "created_by",
    "updated_by"
  ];
}
