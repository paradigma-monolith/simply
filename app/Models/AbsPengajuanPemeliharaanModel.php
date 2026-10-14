<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsPengajuanPemeliharaanModel extends Model
{
  protected $table = 'abs_pengajuan_pemeliharaan';
  protected $primaryKey = 'id_abs_pengajuan_pemeliharaan';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_user',
    'id_abs_bangunan',
    'keterangan',
    'status',
    'validasi',
    'created_by',
    'updated_by'
  ];
}
