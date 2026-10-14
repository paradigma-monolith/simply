<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwPengambilanObatModel extends Model
{
  protected $table = 'uklw_pengambilan_obat';
  protected $primaryKey = 'id_uklw_pengambilan_obat';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_pasien',
    'id_uklw_catatan_pemeriksaan',
    'kriteria',
    'obat',
    'created_by',
    'updated_by'
  ];
}
