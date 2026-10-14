<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraankantorVerifikatorModel extends Model
{
  protected $table = 'kendaraan_kantor_verifikator';
  protected $primaryKey = 'id_kendaraan_kantor_verifikator';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_data_pegawai",
    "pin",
    "created_by",
    "updated_by",
  ];
}
