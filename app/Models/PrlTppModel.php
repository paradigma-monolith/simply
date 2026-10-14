<?php

namespace App\Models;

use CodeIgniter\Model;

class PrlTppModel extends Model
{
  protected $table = 'prl_tpp';
  protected $primaryKey = 'id_prl_tpp';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_wilayah_kerja', 'nama_tpp', 'penanggung_jawab', 'jml_karyawan', 'kapasitas_produksi', 'created_by', 'updated_by'];
}
