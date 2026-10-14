<?php

namespace App\Models;

use CodeIgniter\Model;

class ContohModel extends Model
{
  protected $table = 'contoh';
  protected $primaryKey = 'id_contoh';
  protected $useTimestamps = true;
  protected $allowedFields = ['contoh', 'contoh1', 'contoh2'];
}
