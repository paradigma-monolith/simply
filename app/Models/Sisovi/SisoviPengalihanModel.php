<?php

namespace App\Models\Sisovi;

use CodeIgniter\Model;

class SisoviPengalihanModel extends Model
{
  protected $DBGroup = 'db_sisovi';
  protected $table = 'pengalihan';
  protected $primaryKey = 'id';
  protected $useTimestamps = false;
  protected $allowedFields = ['pengalihan'];
}
