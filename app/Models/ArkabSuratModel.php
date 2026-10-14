<?php

namespace App\Models;

use CodeIgniter\Model;

class ArkabSuratModel extends Model
{
  protected $table = 'arkab_surat';
  protected $primaryKey = 'id_arkab_surat';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_substansi', 'id_data_pegawai', 'nomor_surat', 'dokumen', 'id_jenis_surat', 'id_kode_klasifikasi', 'created_by', 'updated_by'];
}
