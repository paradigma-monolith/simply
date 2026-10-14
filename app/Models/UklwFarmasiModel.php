<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwFarmasiModel extends Model
{
  protected $table = 'uklw_farmasi';
  protected $primaryKey = 'id_uklw_farmasi';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_catatan_pemeriksaan',
    'obat',
    'jumlah',
    'dosis_pemakaian',
    'waktu',
    'keterangan',
    'created_by',
    'updated_by'
  ];
}
