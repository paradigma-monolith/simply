<?php

namespace App\Models;

use CodeIgniter\Model;

class SipeditArsipModel extends Model
{
  protected $table = 'sipedit_arsip';
  protected $primaryKey = 'id_sipedit_arsip';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_data_pegawai',
    'id_sipedit_kategori',
    'id_sipedit_sub_kategori',
    'arsip_waktu_upload',
    'arsip_kode',
    'arsip_nama',
    'arsip_tanggal',
    'arsip_selesai',
    'arsip_jpl',
    'arsip_file',
    'created_by',
    'updated_by'
  ];
}
