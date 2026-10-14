<?php

namespace App\Models;

use CodeIgniter\Model;

class SimandjaTahunAnggaranModel extends Model
{
  protected $table = 'simandja_tahun_anggaran';
  protected $primaryKey = 'id_simandja_tahun_anggaran';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'tahun', 'link', 'keterangan', 'kategori', 'status', 'created_by', 'updated_by'
  ];
}
