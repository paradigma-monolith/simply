<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenModel extends Model
{
  protected $table = 'absen';
  protected $primaryKey = 'id_absen';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_data_pegawai',
    'status_masuk',
    'status_pulang',
    'alamat_masuk',
    'latitude_masuk',
    'longitude_masuk',
    'image_masuk_1',
    'image_masuk_2',
    'jam_masuk',
    'alamat_pulang',
    'latitude_pulang',
    'longitude_pulang',
    'image_pulang_1',
    'image_pulang_2',
    'jam_pulang',
    'keterangan',
    'validation_masuk',
    'validation_pulang',
    'keterangan_validasi_pulang',
    'keterangan_validasi_masuk',
    'posisi_alamat_masuk',
    'posisi_alamat_pulang',
    'rekam_absen',
    'aturan_jam_masuk',
    'aturan_jam_pulang',
    'toleransi_keterlambatan',
    'ttd_masuk',
    'ttd_pulang',
  ];
}
