<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaUserModel extends Model
{
  protected $table = 'agenda_user';
  protected $primaryKey = 'id_agenda_user';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'topik',
    'deskripsi',
    'peruntukan',
    'waktu_pelaksana_awal',
    'waktu_pelaksana_akhir',
    'tgl_notif_awal',
    'tgl_notif_akhir',
    'warna_agenda',
    'ulangi_bulanan',
    'batas_ulangi_bulanan',
    'created_by',
    'updated_by',
  ];

  private static bool $schemaChecked = false;

  /**
   * Pastikan kolom pengulangan bulanan tersedia di tabel agenda_user (otomatis migrasi ringan jika belum ada).
   */
  public function ensureMonthlySchema(): void
  {
    if (self::$schemaChecked) {
      return;
    }
    self::$schemaChecked = true;

    $cacheKey = 'agenda_user_monthly_schema_v1';
    try {
      $cache = \Config\Services::cache();
      if ($cache->get($cacheKey) === true) {
        return;
      }
    } catch (\Throwable $e) {
      // Abaikan error cache
    }

    try {
      $db = \Config\Database::connect();
      if (!$db->fieldExists('ulangi_bulanan', 'agenda_user')) {
        $db->query("ALTER TABLE agenda_user ADD COLUMN ulangi_bulanan TINYINT(1) NOT NULL DEFAULT 0 AFTER warna_agenda");
      }
      if (!$db->fieldExists('batas_ulangi_bulanan', 'agenda_user')) {
        $db->query("ALTER TABLE agenda_user ADD COLUMN batas_ulangi_bulanan DATE NULL DEFAULT NULL AFTER ulangi_bulanan");
      }
      try {
        \Config\Services::cache()->save($cacheKey, true, 86400);
      } catch (\Throwable $e) {
      }
    } catch (\Throwable $e) {
      // Abaikan jika user DB tidak memiliki izin DDL
    }
  }

  /**
   * Bangkitkan daftar kejadian bulanan di tanggal yang sama untuk sebuah agenda.
   *
   * @param array<string, mixed> $agenda
   * @return array<int, array{startTs:int, endTs:int, notifStart:?string, notifEnd:?string, dayOfMonth:int, ym:string}>
   */
  public static function buildMonthlyOccurrences(array $agenda, int $monthsBack = 6, int $monthsAhead = 18): array
  {
    $startRaw = trim((string) ($agenda['waktu_pelaksana_awal'] ?? ''));
    $endRaw = trim((string) ($agenda['waktu_pelaksana_akhir'] ?? ''));
    if ($startRaw === '') {
      return [];
    }

    $baseStartTs = strtotime($startRaw);
    if ($baseStartTs === false) {
      return [];
    }
    $baseEndTs = $endRaw !== '' ? strtotime($endRaw) : $baseStartTs;
    if ($baseEndTs === false || $baseEndTs < $baseStartTs) {
      $baseEndTs = $baseStartTs;
    }

    $isMonthly = !empty($agenda['ulangi_bulanan']) && (int) $agenda['ulangi_bulanan'] === 1;
    $baseDay = (int) date('j', $baseStartTs);

    if (!$isMonthly) {
      return [[
        'startTs'    => $baseStartTs,
        'endTs'      => $baseEndTs,
        'notifStart' => !empty($agenda['tgl_notif_awal']) ? (string) $agenda['tgl_notif_awal'] : null,
        'notifEnd'   => !empty($agenda['tgl_notif_akhir']) ? (string) $agenda['tgl_notif_akhir'] : null,
        'dayOfMonth' => $baseDay,
        'ym'         => date('Ym', $baseStartTs),
      ]];
    }

    $baseStartDateTs = strtotime(date('Y-m-d', $baseStartTs));
    $baseEndDateTs = strtotime(date('Y-m-d', $baseEndTs));
    $durationDays = max(0, (int) round(($baseEndDateTs - $baseStartDateTs) / 86400));

    $startTime = date('H:i:s', $baseStartTs);
    $endTime = date('H:i:s', $baseEndTs);

    $hasNotif = !empty($agenda['tgl_notif_awal']) && !empty($agenda['tgl_notif_akhir']);
    $notifStartOffset = 0;
    $notifEndOffset = $durationDays;
    if ($hasNotif) {
      $nStartTs = strtotime((string) $agenda['tgl_notif_awal']);
      $nEndTs = strtotime((string) $agenda['tgl_notif_akhir']);
      if ($nStartTs !== false && $nEndTs !== false) {
        $notifStartOffset = (int) round(($nStartTs - $baseStartDateTs) / 86400);
        $notifEndOffset = (int) round(($nEndTs - $baseStartDateTs) / 86400);
      } else {
        $hasNotif = false;
      }
    }

    $batasDate = trim((string) ($agenda['batas_ulangi_bulanan'] ?? ''));
    if ($batasDate === '0000-00-00') {
      $batasDate = '';
    }

    $baseYmIndex = ((int) date('Y', $baseStartTs)) * 12 + ((int) date('n', $baseStartTs) - 1);
    $nowYmIndex = ((int) date('Y')) * 12 + ((int) date('n') - 1);

    $fromYmIndex = max($baseYmIndex, $nowYmIndex - $monthsBack);
    $toYmIndex = max($baseYmIndex, $nowYmIndex + $monthsAhead);

    if ($batasDate !== '' && preg_match('/^(\d{4})-(\d{2})-\d{2}$/', $batasDate, $m)) {
      $batasYmIndex = ((int) $m[1]) * 12 + ((int) $m[2] - 1);
      $toYmIndex = min($toYmIndex, $batasYmIndex);
    }

    $occurrences = [];
    for ($idx = $fromYmIndex; $idx <= $toYmIndex; $idx++) {
      $year = intdiv($idx, 12);
      $month = ($idx % 12) + 1;

      $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
      $occDay = min($baseDay, $daysInMonth);
      $occStartDate = sprintf('%04d-%02d-%02d', $year, $month, $occDay);

      if ($batasDate !== '' && $occStartDate > $batasDate) {
        break;
      }

      $occStartTs = strtotime($occStartDate . ' ' . $startTime);
      $occEndDate = $durationDays > 0
        ? date('Y-m-d', strtotime($occStartDate . ' +' . $durationDays . ' days'))
        : $occStartDate;
      $occEndTs = strtotime($occEndDate . ' ' . $endTime);

      if ($occStartTs === false || $occEndTs === false) {
        continue;
      }

      $occNotifStart = null;
      $occNotifEnd = null;
      if ($hasNotif) {
        $occNotifStart = date('Y-m-d', strtotime($occStartDate . ' ' . ($notifStartOffset >= 0 ? '+' : '') . $notifStartOffset . ' days'));
        $occNotifEnd = date('Y-m-d', strtotime($occStartDate . ' ' . ($notifEndOffset >= 0 ? '+' : '') . $notifEndOffset . ' days'));
      }

      $occurrences[] = [
        'startTs'    => $occStartTs,
        'endTs'      => $occEndTs,
        'notifStart' => $occNotifStart,
        'notifEnd'   => $occNotifEnd,
        'dayOfMonth' => $baseDay,
        'ym'         => sprintf('%04d%02d', $year, $month),
      ];
    }

    return $occurrences;
  }
}
