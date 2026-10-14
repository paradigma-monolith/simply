<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeDetailUraianModel extends Model
{
  protected $table = 'relaxtime_detail_uraian';
  protected $primaryKey = 'id_relaxtime_detail_uraian';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_relaxtime_uraian",
    "id_relaxtime_komponen",
    "deskripsi_detail_uraian",
    "vol_detail_uraian",
    "sat_detail_uraian",
    "harga_detail_uraian",
    "jumlah_detail_uraian",
    "tahun",
    "created_by",
    "updated_by"
  ];
}
