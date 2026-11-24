<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiAksesModel extends Model
{
  protected $table = 'sinadi_akses';
  protected $primaryKey = 'id_sinadi_akses';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "id_user",
    "status",
    "akses_tim",
    "akses_monitoring_dl",
    "created_by",
    "updated_by",
  ];

  private static bool $monitoringDlChecked = false;

  /**
   * Pastikan kolom akses_monitoring_dl tersedia pada tabel sinadi_akses (idempoten).
   */
  public static function ensureMonitoringDlColumn(): void
  {
    if (self::$monitoringDlChecked) {
      return;
    }
    self::$monitoringDlChecked = true;

    $cacheKey = 'sinadi_akses_monitoring_dl_col_v1';
    try {
      $cache = \Config\Services::cache();
      if ($cache->get($cacheKey) === true) {
        return;
      }
    } catch (\Throwable $e) {
    }

    try {
      $db = \Config\Database::connect();
      if (!$db->fieldExists('akses_monitoring_dl', 'sinadi_akses')) {
        $db->query('ALTER TABLE sinadi_akses ADD COLUMN akses_monitoring_dl TINYINT(1) NOT NULL DEFAULT 0 AFTER akses_tim');
      }
      try {
        \Config\Services::cache()->save($cacheKey, true, 86400);
      } catch (\Throwable $e) {
      }
    } catch (\Throwable $e) {
      // Abaikan jika user DB tidak memiliki izin DDL
    }
  }
}
