<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwPemkesModel extends Model
{
  protected $table = 'uklw_pemkes';
  protected $primaryKey = 'id_uklw_pemkes';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_pasien',
    'kota',
    'status_pemeriksaan',
    'pemeriksaan_fisik',
    'riwayat_penyakit',
    'wawancara',
    'hygiene',
    'pemeriksaan_fisik_penjamah',
    'created_by',
    'updated_by'
  ];
}
