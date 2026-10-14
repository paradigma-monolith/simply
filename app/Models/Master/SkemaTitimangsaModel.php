<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class SkemaTitimangsaModel extends Model
{
  protected $table = 'skema_titimangsa';
  protected $primaryKey = 'id_skema_titimangsa';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_unit_kerja', 'jenis_surat', 'kepegawaian', 'pejabat', 'atasan', 'created_by', 'updated_by'];
}
