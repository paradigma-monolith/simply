<?php

namespace App\Models;

use CodeIgniter\Model;

class statusPegawaiModel extends Model
{
  protected $table = 'status_pegawai';
  protected $primaryKey = 'id_status_pegawai';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'status_pegawai', 'created_by', 'updated_by'
  ];
}
