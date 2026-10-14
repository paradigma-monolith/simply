<?php

namespace App\Models;

use CodeIgniter\Model;

class WbkKategoriModel extends Model
{
  protected $table = 'wbk_kategori';
  protected $primaryKey = 'id_wbk_kategori';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_wbk_pokja', 'no', 'poin', 'keterangan', 'jenis', 'created_by', 'updated_by'];
}
