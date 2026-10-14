<?php

namespace App\Models;

use CodeIgniter\Model;

class WbkAnggotaModel extends Model
{
  protected $table = 'wbk_anggota';
  protected $primaryKey = 'id_wbk_anggota';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_wbk_pokja', 'id_data_pegawai', 'nama_pegawai', 'keterangan', 'created_by', 'updated_by'];
}
