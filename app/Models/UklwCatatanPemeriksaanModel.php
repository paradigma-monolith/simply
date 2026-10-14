<?php

namespace App\Models;

use CodeIgniter\Model;

class UklwCatatanPemeriksaanModel extends Model
{
  protected $table = 'uklw_catatan_pemeriksaan';
  protected $primaryKey = 'id_uklw_catatan_pemeriksaan';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_uklw_pemkes',
    'subjective',
    'assesment',
    'planning',
    'id_data_pegawai_dokter',
    'status_pemberian_obat',
    'created_by',
    'updated_by'
  ];
}
