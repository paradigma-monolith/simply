<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsLokasiModel extends Model
{
  protected $table = 'abs_lokasi';
  protected $primaryKey = 'id_abs_bangunan';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_abs_image',
    'id_unit_kerja',
    'id_abs_lantai',
    'id_abs_ruang',
    'id_abs_jenis',
    'data_label',
    'data_barang',
    'kode_qr_code_pusat',
    'kode_qr_code_internal',
    'status_tersedia',
    'created_by',
    'updated_by'
  ];
  public function findDuplicateDataBarang()
  {
    return $this->whereIn('data_barang', function ($subquery) {
      $subquery->select('abs_lokasi.data_barang')
        ->from('abs_lokasi')
        ->groupBy('abs_lokasi.data_barang')
        ->having('COUNT(*) >', 1);
    })
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
      ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
      ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
      ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left')
      ->join('abs_image', 'abs_image.id_abs_image=abs_lokasi.id_abs_image', 'left')
      ->findAll();
  }
}
