<?php

namespace App\Models\Master;

use App\Models\Crud;

class SubstansiModel extends Crud
{
  protected $table = 'substansi';
  protected $primaryKey = 'id_substansi';
  protected $useTimestamps = true;
  protected $allowedFields = ['nama_substansi', 'keterangan_substansi'];
}
