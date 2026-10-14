<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeKomponenModel extends Model
{
  protected $table = 'relaxtime_komponen';
  protected $primaryKey = 'id_relaxtime_komponen';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_relaxtime_ro",
    "kode_komponen",
    "kode_sub_komponen",
    "deskripsi_komponen",
    "deskripsi_sub_komponen",
    "created_by",
    "updated_by"
  ];
}
