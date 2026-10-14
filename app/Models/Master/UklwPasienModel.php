<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwPasienModel extends Model
{
  protected $table = 'uklw_pasien';
  protected $primaryKey = 'id_uklw_pasien';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'identitas',
    'nomor_identitas',
    'nama',
    'jk',
    'tgl_lahir',
    'alamat',
    'no_hp',
    'pendidikan',
    'pekerjaan',
    'status',
    'nama_keluarga',
    'hubungan_keluarga',
    'no_hp_keluarga',
    'created_by',
    'updated_by'
  ];
}
