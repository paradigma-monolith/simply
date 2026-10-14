<?php

namespace App\Models;

use CodeIgniter\Model;

class PrlSabModel extends Model
{
  protected $table = 'prl_sab';
  protected $primaryKey = 'id_prl_sab';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_wilayah_kerja', 'nama_sab', 'penanggung_jawab', 'penanggung_jawab_no_hp','sumber_air', 'kapasitas_penampungan', 'created_by', 'updated_by'];
}
