<?php

namespace App\Models\Master;

use App\Models\Crud;

class TimKerjaModel extends Crud
{
  protected $table = 'tim_kerja';
  protected $primaryKey = 'id_tim_kerja';
  protected $useTimestamps = true;
  protected $allowedFields = ['nama_tim_kerja', 'keterangan_substansi'];
}