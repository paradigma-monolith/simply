<?php

namespace App\Models\Timeline;

use CodeIgniter\Model;

class LinimasaModel extends Model
{
  protected $table = 'linimasa';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'icon',
    'id_table',
    'table',
    'id_user',
    'aktivitas',
    'keterangan',
  ];
}
