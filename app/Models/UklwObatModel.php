<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwObatModel extends Model
{
  protected $table = 'uklw_obat';
  protected $primaryKey = 'id_uklw_obat';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'golongan',
    'nama',
    'kategori',
    'satuan',
    'penyimpanan',
    'created_by',
    'updated_by'
  ];
}
