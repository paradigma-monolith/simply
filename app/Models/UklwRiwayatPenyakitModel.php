<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwRiwayatPenyakitModel extends Model
{
  protected $table = 'uklw_riwayat_penyakit';
  protected $primaryKey = 'id_uklw_riwayat_penyakit';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'istilah_penyakit',
    'ket_penyakit',
    'created_by',
    'updated_by'
  ];
}
