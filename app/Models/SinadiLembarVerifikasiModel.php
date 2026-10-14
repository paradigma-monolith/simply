<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiLembarVerifikasiModel extends Model
{
  protected $table = 'sinadi_lembar_verifikasi';
  protected $primaryKey = 'id_sinadi_lembar_verifikasi';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_perjalanan',
    'tgl',
    'no_spby',
    'suplier',
    'tagihan',
    'uraian',
    'total_tagihan',
    'jenis_daftar_periksa',
    'daftar_periksa',
    'created_by',
    'updated_by',
  ];
}
