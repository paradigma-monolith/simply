<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeSettingModel extends Model
{
  protected $table = 'relaxtime_setting';
  protected $primaryKey = 'id_relaxtime_setting';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "batas_nilai_bawah",
    "batas_nilai_atas",
    "subjek",
    "isi_pesan",
    "id_users",
    "created_by",
    "updated_by",
  ];
}
