<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraanKantorRiwayatModel extends Model
{
  protected $table = 'kendaraan_kantor_riwayat';
  protected $primaryKey = 'id_kendaraan_kantor_riwayat';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "tanggal",
    "penumpang",
    "tujuan",
    "keterangan",
    "id_kendaraan_kantor_daftar",
    "kendaraan",
    "keterangan_verifikator",
    "created_by",
    "updated_by",
  ];
}
