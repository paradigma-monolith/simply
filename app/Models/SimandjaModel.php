<?php

namespace App\Models;

use CodeIgniter\Model;

class SimandjaModel extends Model
{
  protected $table = 'simandja';
  protected $primaryKey = 'id_simandja';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'tim_kerja', 'tanggal', 'deskripsi', 'file', 'kategori', 'created_by', 'updated_by'
  ];
}
