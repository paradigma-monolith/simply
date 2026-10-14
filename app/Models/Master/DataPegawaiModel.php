<?php

namespace App\Models\Master;

use App\Models\Crud;

class DataPegawaiModel extends Crud
{
  protected $table = 'data_pegawai';
  protected $primaryKey = 'id_data_pegawai';
  protected $useTimestamps = true;
  protected $allowedFields = [
      'id_user', 
      'id_unit_kerja', 
      'id_substansi', 
      'id_tim_kerja', 
      'id_jabatan', 
      'id_golongan', 
      'id_status_pegawai', 
      'nama', 
      'jabatan', 
      'nip', 
      'golongan', 
      'masa_kerja', 
      'tgl_awal_masa', 
      'status_bekerja',
      'pelatihan_jafung_status',
      'pelatihan_jafung_tahun',
      'pelatihan_jafung_nama',
      'pelatihan_jafung_sertifikat',
      'created_by', 
      'updated_by'];
}
