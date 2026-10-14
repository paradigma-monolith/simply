<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraanKantorDaftarModel extends Model
{
  protected $table = 'kendaraan_kantor_daftar';
  protected $primaryKey = 'id_kendaraan_kantor_daftar';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "nama_kendaraan",
    "plat_nomor",
    "gambar",
    "created_by",
    "updated_by",
  ];
}
