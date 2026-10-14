<?php

namespace App\Models;

use CodeIgniter\Model;

class PrlHsbuModel extends Model
{
  protected $table = 'prl_hsbu';
  protected $primaryKey = 'id_prl_hsbu';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_wilayah_kerja', 'nama_gedung', 'penanggung_jawab', 'jml_karyawan', 'created_by', 'updated_by'];
}
