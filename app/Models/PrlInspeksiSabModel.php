<?php

namespace App\Models;

use CodeIgniter\Model;

class PrlInspeksiSabModel extends Model
{
  protected $table = 'prl_inspeksi_sab';
  protected $primaryKey = 'id_prl_inspeksi_sab';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_wilayah_kerja',
    'tanggal_inspeksi',
    'id_prl_sab',
    'id_data_pegawai',
    'penanggung_jawab_sab',
    'penanggung_jawab_no_hp_sab',
    'sumber_air_sab',
    'kapasitas_penampungan_sab',
    'sec_b_satu',
    'sec_b_dua',
    'sec_b_tiga',
    'sec_b_empat',
    'sec_c_satu',
    'sec_c_dua',
    'sec_c_tiga',
    'sec_c_empat',
    'sec_c_lima',
    'sec_c_enam',
    'sec_c_tujuh',
    'sec_c_delapan',
    'sec_c_sembilan',
    'sec_c_sepuluh',
    'hasil',
    'saran_rencana_tindakan',
    'created_by',
    'updated_by'
  ];
}
