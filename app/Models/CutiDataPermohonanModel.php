<?php

namespace App\Models;

use CodeIgniter\Model;

class CutiDataPermohonanModel extends Model
{
  protected $table = 'cuti_data_permohonan';
  protected $primaryKey = 'id_cuti_data_permohonan';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_data_pegawai',
    'id_jabatan',
    'id_golongan',
    'id_skema_titimangsa',
    'id_unit_kerja',
    'id_cuti_status_kepegawaian',
    'id_cuti_status_atasan',
    'jenis_permohonan',
    'alasan_permohonan',
    'tanggal',
    'ket_permohonan_validation_kepegawaian',
    'ket_permohonan_validation_atasan',
    'ttd_data_pegawai',
    'ttd_kepegawaian',
    'ttd_atasan',
    'created_by',
    'updated_by'
  ];
}
