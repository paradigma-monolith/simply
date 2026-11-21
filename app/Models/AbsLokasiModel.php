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

  private static bool $indexesChecked = false;

  public static function ensureIndexes(): void
  {
    if (self::$indexesChecked) {
      return;
    }
    self::$indexesChecked = true;

    $flagFile = WRITEPATH . 'cache/abs_bmn_indexes_v1.flag';
    if (is_file($flagFile)) {
      return;
    }

    try {
      $db = \Config\Database::connect();
      $existing = [];
      $res = $db->query('SHOW INDEX FROM `abs_lokasi`');
      if ($res) {
        foreach ($res->getResultArray() as $row) {
          $existing[$row['Key_name']] = true;
        }
      }

      $indexesToCreate = [
        'idx_abs_lokasi_tempat'      => 'ALTER TABLE `abs_lokasi` ADD INDEX `idx_abs_lokasi_tempat` (`id_unit_kerja`, `id_abs_lantai`, `id_abs_ruang`)',
        'idx_abs_lokasi_jenis'       => 'ALTER TABLE `abs_lokasi` ADD INDEX `idx_abs_lokasi_jenis` (`id_abs_jenis`)',
        'idx_abs_lokasi_status'      => 'ALTER TABLE `abs_lokasi` ADD INDEX `idx_abs_lokasi_status` (`status_tersedia`)',
        'idx_abs_lokasi_image'       => 'ALTER TABLE `abs_lokasi` ADD INDEX `idx_abs_lokasi_image` (`id_abs_image`)',
        'idx_abs_lokasi_qr_pusat'    => 'ALTER TABLE `abs_lokasi` ADD INDEX `idx_abs_lokasi_qr_pusat` (`kode_qr_code_pusat`)',
        'idx_abs_lokasi_qr_internal' => 'ALTER TABLE `abs_lokasi` ADD INDEX `idx_abs_lokasi_qr_internal` (`kode_qr_code_internal`)',
      ];

      foreach ($indexesToCreate as $name => $sql) {
        if (!isset($existing[$name])) {
          $db->query($sql);
        }
      }

      if (!is_dir(WRITEPATH . 'cache')) {
        @mkdir(WRITEPATH . 'cache', 0775, true);
      }
      @file_put_contents($flagFile, (string) time());
    } catch (\Throwable $e) {
      // Abaikan bila user DB tidak memiliki privilege ALTER
    }
  }

  public static function safeUnserialize(?string $raw): array
  {
    if ($raw === null || $raw === '') {
      return [];
    }
    $val = @unserialize($raw);
    return is_array($val) ? $val : [];
  }
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
