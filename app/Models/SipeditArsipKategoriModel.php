<?php

namespace App\Models;

use CodeIgniter\Model;

class SipeditArsipKategoriModel extends Model
{
  protected $table = 'sipedit_kategori';
  protected $primaryKey = 'id_sipedit_kategori';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'kategori_nama',
    'kategori_keterangan',
  ];
}
