<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeRealisasiFileModel extends Model
{
  protected $table = 'relaxtime_realisasi_file';
  protected $primaryKey = 'id_relaxtime_realisasi_file';
  protected $useTimestamps = true;
  protected $allowedFields = ['jenis_naskah', 'nama_file', 'file', 'dokumen', 'created_by', 'updated_by'];
}
