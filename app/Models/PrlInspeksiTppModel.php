<?php

namespace App\Models;

use CodeIgniter\Model;

class PrlInspeksiTppModel extends Model
{
  protected $table = 'prl_inspeksi_tpp';
  protected $primaryKey = 'id_prl_inspeksi_tpp';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_wilayah_kerja',
    'tanggal_inspeksi',
    'id_prl_tpp',
    'id_data_pegawai',
    'penanggung_jawab_tpp',
    'jml_karyawan_tpp',
    'kapasitas_produksi_tpp',
    'sec_a_satu',
    'sec_a_dua',
    'sec_a_tiga',
    'sec_a_skor',
    'sec_b_satu',
    'sec_b_dua',
    'sec_b_tiga',
    'sec_b_skor',
    'sec_c_satu',
    'sec_c_dua',
    'sec_c_tiga',
    'sec_c_skor',
    'sec_c_keterangan',
    'sec_d_nilai_inspeksi_sebelumnya',
    'sec_d_skor',
    'kesimpulan',
    'hasil',
    'saran_rencana_tindakan',
    'created_by',
    'updated_by'
  ];
}
