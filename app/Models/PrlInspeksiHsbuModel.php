<?php

namespace App\Models;

use CodeIgniter\Model;

class PrlInspeksiHsbuModel extends Model
{
  protected $table = 'prl_inspeksi_hsbu';
  protected $primaryKey = 'id_prl_inspeksi_hsbu';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_wilayah_kerja',
    'tanggal_inspeksi',
    'id_prl_hsbu',
    'id_data_pegawai',
    'penanggung_jawab_hsbu',
    'jml_karyawan_hsbu',
    'lingkungan_luar_halaman',
    'ruang_bangunan',
    'penyehatan_air',
    'penyehatan_ruang_udara',
    'pengelolaan_limbah',
    'pencahayaan',
    'kebisingan_pada_ruang',
    'getaran_di_ruang_kerja',
    'pengendalian_vektor_penyakit',
    'instalasi',
    'pemeliharaan_jamban_dan_kamar_mandi',
    'total',
    'kategori',
    'hasil',
    'saran_rencana_tindakan',
    'catatan',
    'created_by',
    'updated_by'
  ];
}
