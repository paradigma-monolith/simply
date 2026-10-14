<?php

namespace App\Models;

use CodeIgniter\Model;

class WbkPokjaModel extends Model
{
  protected $table = 'wbk_pokja';
  protected $primaryKey = 'id_wbk_pokja';
  protected $useTimestamps = true;
  protected $allowedFields = ['nama_pokja', 'keterangan', 'created_by', 'updated_by'];
}
