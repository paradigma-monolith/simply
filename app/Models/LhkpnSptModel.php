<?php

namespace App\Models;

use CodeIgniter\Model;

class LhkpnSptModel extends Model
{
  protected $table = 'lhkpn_spt';
  protected $primaryKey = 'id_lhkpn_spt';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_data_pegawai', 'tanggal_dokumen', 'dokumen', 'jenis_dokumen', 'created_by', 'updated_by'];
}
