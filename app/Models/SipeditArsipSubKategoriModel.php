<?php

namespace App\Models;

use CodeIgniter\Model;

class SipeditArsipSubKategoriModel extends Model
{
  protected $table = 'sipedit_sub_kategori';
  protected $primaryKey = 'id_sipedit_sub_kategori';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_sipedit_kategori',
    'sub_kategori',
  ];
}
