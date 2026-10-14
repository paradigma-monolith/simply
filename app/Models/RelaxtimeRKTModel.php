<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeRKTModel extends Model
{
  protected $table = 'relaxtime_rkt';
  protected $primaryKey = 'id_relaxtime_rkt';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "kode_rkt",
    "deskripsi_rkt",
    "created_by",
    "updated_by",
  ];
}
