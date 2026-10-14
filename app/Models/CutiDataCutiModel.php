<?php

namespace App\Models;

use CodeIgniter\Model;

class CutiDataCutiModel extends Model
{
  protected $table = 'cuti_data_cuti';
  protected $primaryKey = 'id_cuti_data_cuti';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_data_pegawai',
    'id_jabatan',
    'id_skema_titimangsa',
    'id_unit_kerja',
    'id_cuti_status_kepegawaian',
    'id_cuti_status_atasan',
    'id_cuti_status_pejabat',
    'jenis_cuti',
    'alasan_cuti',
    'tanggal_awal',
    'tanggal_akhir',
    'tanggal_pengajuan',
    'total_hari',
    'alamat_cuti',
    'no_telp',
    'ket_cuti_validation_kepegawaian',
    'ket_cuti_validation_atasan',
    'ket_cuti_validation_pejabat',
    'ttd_data_pegawai',
    'ttd_kepegawaian',
    'ttd_atasan',
    'ttd_pejabat',
    'koordinator_tempat',
    'koordinator_status',
    'bukti_lampiran',
    'osdm_nama',
    'osdm_nip',
    'created_by',
    'updated_by'
  ];
}
