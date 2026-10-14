<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsPengajuanPemindahanModel extends Model
{
  protected $table = 'abs_pengajuan_pemindahan';
  protected $primaryKey = 'id_abs_pengajuan_pemindahan';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_user',
    'id_abs_bangunan',
    'data_asal',
    'data_tujuan',
    'status'
  ];
}
