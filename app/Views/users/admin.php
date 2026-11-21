<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$accessMenu = session()->get('accessMenu');
if (!is_array($accessMenu)) {
  $accessMenu = [];
}

$namaUser = trim((string) (session()->get('nama') ?: ($user['nama'] ?? 'Pengguna')));
$jabatanUser = trim((string) ($user['jabatan'] ?? ($pegawaiInfo['jabatan'] ?? '')));
$unitKerjaUser = trim((string) ($user['unit_kerja'] ?? ''));
$timKerjaUser = trim((string) ($user['nama_tim_kerja'] ?? ($pegawaiInfo['nama_tim_kerja'] ?? '')));
$golonganUser = trim((string) ($user['golongan'] ?? ($pegawaiInfo['golongan'] ?? '')));
$catatanPribadi = (string) ($by['catatan_pribadi'] ?? '');
$agendaHariIni = (int) ($ringkasanHome['agenda_hari_ini'] ?? 0);
$tanggalIndo = (string) ($ringkasanHome['tanggal_indo'] ?? date('d M Y'));

// Status absensi hari ini
$absenStatusLabel = 'Belum Absen Hari Ini';
$absenStatusTone = 'warning';
$absenDetailText = 'Silakan lakukan absensi masuk melalui aplikasi atau browser.';
if (!empty($absenHariIni)) {
  $jamMasukStr = !empty($absenHariIni['jam_masuk']) ? date('H:i', strtotime((string) $absenHariIni['jam_masuk'])) : null;
  $jamPulangStr = !empty($absenHariIni['jam_pulang']) ? date('H:i', strtotime((string) $absenHariIni['jam_pulang'])) : null;
  $statusMasuk = trim((string) ($absenHariIni['status_masuk'] ?? ''));
  $statusPulang = trim((string) ($absenHariIni['status_pulang'] ?? ''));

  if ($jamMasukStr && $jamPulangStr) {
    $absenStatusLabel = 'Absensi Lengkap';
    $absenStatusTone = 'success';
    $absenDetailText = 'Masuk ' . $jamMasukStr . ($statusMasuk ? ' (' . $statusMasuk . ')' : '')
      . ' • Pulang ' . $jamPulangStr . ($statusPulang ? ' (' . $statusPulang . ')' : '');
  } elseif ($jamMasukStr) {
    $absenStatusLabel = 'Sudah Absen Masuk';
    $absenStatusTone = 'info';
    $absenDetailText = 'Masuk pukul ' . $jamMasukStr . ($statusMasuk ? ' • ' . $statusMasuk : '') . ' • Belum absen pulang';
  } elseif ($statusMasuk !== '') {
    $absenStatusLabel = 'Status: ' . $statusMasuk;
    $absenStatusTone = 'info';
    $absenDetailText = !empty($absenHariIni['keterangan']) ? (string) $absenHariIni['keterangan'] : 'Tercatat pada absensi hari ini.';
  }
}
?>

<style>
  .simply-home {
    --sh-primary: #0d9488;
    --sh-primary-dark: #0f766e;
    --sh-primary-deep: #03778c;
    --sh-primary-soft: rgba(13, 148, 136, 0.1);
    --sh-surface: #ffffff;
    --sh-bg: #f4f7fa;
    --sh-border: rgba(15, 23, 42, 0.08);
    --sh-text: #0f172a;
    --sh-muted: #64748b;
    --sh-radius: 14px;
    --sh-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    --sh-shadow-hover: 0 10px 24px rgba(15, 23, 42, 0.09);
  }

  .content-wrapper {
    background-color: #f4f7fa;
  }

  .simply-home .sh-env-banner {
    background: linear-gradient(90deg, #fef2f2, #fffbeb);
    border: 1px solid #fecaca;
    border-left: 4px solid #dc2626;
    border-radius: 12px;
    padding: 12px 18px;
    margin-bottom: 16px;
    color: #991b1b;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  /* Hero Header */
  .simply-home .sh-hero {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    background: linear-gradient(135deg, #0b7285 0%, #0d9488 52%, #14b8a6 100%);
    color: #ffffff;
    padding: 24px 28px;
    margin-bottom: 18px;
    box-shadow: 0 10px 28px rgba(13, 148, 136, 0.22);
  }
  .simply-home .sh-hero::before {
    content: "";
    position: absolute;
    top: -65px;
    right: -45px;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
    pointer-events: none;
  }
  .simply-home .sh-hero::after {
    content: "";
    position: absolute;
    bottom: -90px;
    left: 38%;
    width: 280px;
    height: 280px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
    pointer-events: none;
  }
  .simply-home .sh-hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
  }
  .simply-home .sh-greeting-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    background: rgba(255, 255, 255, 0.18);
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 10px;
    backdrop-filter: blur(4px);
  }
  .simply-home .sh-hero-title {
    font-size: 1.55rem;
    font-weight: 700;
    margin: 0 0 8px;
    line-height: 1.25;
    color: #ffffff;
  }
  .simply-home .sh-meta-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }
  .simply-home .sh-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.94);
    background: rgba(15, 23, 42, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.16);
    padding: 4px 11px;
    border-radius: 8px;
  }
  .simply-home .sh-hero-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
  }
  .simply-home .sh-clock-box {
    text-align: right;
    background: rgba(15, 23, 42, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 10px 16px;
    min-width: 195px;
    backdrop-filter: blur(4px);
  }
  .simply-home .sh-clock-time {
    font-size: 1.45rem;
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: 0.6px;
    font-variant-numeric: tabular-nums;
  }
  .simply-home .sh-clock-date {
    font-size: 0.78rem;
    opacity: 0.9;
    margin-top: 2px;
  }
  .simply-home .sh-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: flex-end;
  }
  .simply-home .sh-btn-glass {
    background: rgba(255, 255, 255, 0.18);
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.32);
    border-radius: 10px;
    padding: 7px 14px;
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
  }
  .simply-home .sh-btn-glass:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
  }
  .simply-home .sh-btn-white {
    background: #ffffff;
    color: #0f766e !important;
    border: 1px solid #ffffff;
    border-radius: 10px;
    padding: 7px 14px;
    font-size: 0.82rem;
    font-weight: 700;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
  }
  .simply-home .sh-btn-white:hover {
    background: #f0fdfa;
    transform: translateY(-1px);
  }

  /* Summary KPI Strip */
  .simply-home .sh-kpi-card {
    background: var(--sh-surface);
    border: 1px solid var(--sh-border);
    border-radius: var(--sh-radius);
    padding: 16px 18px;
    box-shadow: var(--sh-shadow);
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .simply-home .sh-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--sh-shadow-hover);
  }
  .simply-home .sh-kpi-left {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
  }
  .simply-home .sh-kpi-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
  }
  .simply-home .sh-kpi-icon.tone-success { background: #ecfdf5; color: #059669; }
  .simply-home .sh-kpi-icon.tone-info    { background: #eff6ff; color: #2563eb; }
  .simply-home .sh-kpi-icon.tone-warning { background: #fffbeb; color: #d97706; }
  .simply-home .sh-kpi-icon.tone-teal    { background: #f0fdfa; color: #0d9488; }
  .simply-home .sh-kpi-icon.tone-indigo  { background: #eef2ff; color: #4f46e5; }
  .simply-home .sh-kpi-label {
    font-size: 0.74rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--sh-muted);
    margin-bottom: 2px;
  }
  .simply-home .sh-kpi-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--sh-text);
    line-height: 1.25;
  }
  .simply-home .sh-kpi-sub {
    font-size: 0.78rem;
    color: var(--sh-muted);
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .simply-home .sh-kpi-cta {
    flex-shrink: 0;
    border-radius: 9px;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 6px 12px;
    white-space: nowrap;
  }

  /* Surface Cards */
  .simply-home .sh-card {
    background: var(--sh-surface);
    border: 1px solid var(--sh-border);
    border-radius: var(--sh-radius);
    box-shadow: var(--sh-shadow);
    margin-bottom: 16px;
    overflow: hidden;
  }
  .simply-home .sh-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid var(--sh-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #ffffff;
  }
  .simply-home .sh-card-title {
    font-size: 0.94rem;
    font-weight: 700;
    color: var(--sh-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .simply-home .sh-card-title i {
    color: var(--sh-primary);
  }
  .simply-home .sh-card-body {
    padding: 16px 18px;
  }

  /* Jadwal Shalat Grid */
  .simply-home .sh-prayer-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 8px;
  }
  @media (min-width: 1440px) {
    .simply-home .sh-prayer-grid {
      grid-template-columns: repeat(6, minmax(0, 1fr));
    }
  }
  .simply-home .sh-prayer-tile {
    background: #f8fafc;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 10px;
    padding: 9px 6px;
    text-align: center;
    transition: all 0.2s ease;
  }
  .simply-home .sh-prayer-tile.is-next {
    background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
    border-color: #14b8a6;
    box-shadow: 0 3px 10px rgba(20, 184, 166, 0.16);
  }
  .simply-home .sh-prayer-name {
    display: block;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--sh-muted);
    margin-bottom: 3px;
  }
  .simply-home .sh-prayer-tile.is-next .sh-prayer-name {
    color: #0f766e;
  }
  .simply-home .sh-prayer-time {
    display: block;
    font-size: 1rem;
    font-weight: 700;
    color: var(--sh-text);
    font-variant-numeric: tabular-nums;
  }
  .simply-home .sh-prayer-tile.is-next .sh-prayer-time {
    color: #0b7285;
  }

  /* Quick Actions Stack */
  .simply-home .sh-quick-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 12px;
  }
  .simply-home .sh-quick-tile {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid var(--sh-border);
    background: #f8fafc;
    color: var(--sh-text) !important;
    text-decoration: none !important;
    transition: all 0.2s ease;
  }
  .simply-home .sh-quick-tile:hover {
    background: #ffffff;
    border-color: rgba(13, 148, 136, 0.35);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.07);
  }
  .simply-home .sh-quick-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    margin-bottom: 10px;
  }
  .simply-home .sh-quick-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--sh-text);
    margin-bottom: 2px;
  }
  .simply-home .sh-quick-desc {
    font-size: 0.74rem;
    color: var(--sh-muted);
    line-height: 1.35;
  }
  .simply-home #btn-link-absen {
    border-radius: 10px;
    padding: 10px 14px;
    font-weight: 600;
    font-size: 0.85rem;
    background: #fef2f2;
    color: #b91c1c !important;
    border: 1px solid #fecaca;
    transition: all 0.2s ease;
    text-decoration: none !important;
  }
  .simply-home #btn-link-absen:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: #ffffff !important;
    box-shadow: 0 6px 14px rgba(220, 38, 38, 0.22);
  }
  .simply-home .sh-tip-box {
    background: #f8fafc;
    border: 1px dashed rgba(100, 116, 139, 0.3);
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 0.77rem;
    color: var(--sh-muted);
    line-height: 1.45;
    margin-top: 10px;
  }

  /* Catatan Pribadi */
  .simply-home #catatan_pribadi {
    border-radius: 10px;
    border: 1px solid rgba(15, 23, 42, 0.12);
    background: #fffdf5;
    font-size: 0.86rem;
    line-height: 1.5;
    resize: vertical;
    min-height: 92px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .simply-home #catatan_pribadi:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
    background: #ffffff;
  }

  /* Skeleton Loader for Calendar */
  .simply-home .sh-skeleton-card {
    background: #ffffff;
    border: 1px solid var(--sh-border);
    border-radius: var(--sh-radius);
    box-shadow: var(--sh-shadow);
    padding: 18px;
    min-height: 520px;
  }
  .simply-home .sh-skel {
    background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
    background-size: 200% 100%;
    animation: shShimmer 1.3s ease-in-out infinite;
    border-radius: 8px;
  }
  @keyframes shShimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }

  /* Module Launcher Directory */
  .simply-home .sh-directory-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin: 22px 0 14px;
  }
  .simply-home .sh-directory-title {
    font-size: 1.08rem;
    font-weight: 700;
    color: var(--sh-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .simply-home .sh-search-wrap {
    position: relative;
    min-width: 240px;
    flex: 0 1 320px;
  }
  .simply-home .sh-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.85rem;
  }
  .simply-home .sh-search-input {
    width: 100%;
    padding: 8px 14px 8px 34px;
    border-radius: 10px;
    border: 1px solid var(--sh-border);
    background: #ffffff;
    font-size: 0.84rem;
    color: var(--sh-text);
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .simply-home .sh-search-input:focus {
    outline: none;
    border-color: var(--sh-primary);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.14);
  }
  .simply-home .sh-filter-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 16px;
  }
  .simply-home .sh-filter-pill {
    border: 1px solid var(--sh-border);
    background: #ffffff;
    color: var(--sh-muted);
    font-size: 0.78rem;
    font-weight: 600;
    padding: 5px 13px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.18s ease;
  }
  .simply-home .sh-filter-pill:hover {
    border-color: rgba(13, 148, 136, 0.4);
    color: var(--sh-primary-dark);
  }
  .simply-home .sh-filter-pill.active {
    background: var(--sh-primary);
    border-color: var(--sh-primary);
    color: #ffffff;
    box-shadow: 0 3px 10px rgba(13, 148, 136, 0.24);
  }
  .simply-home .sh-section-label {
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: var(--sh-muted);
    margin: 14px 0 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .simply-home .sh-section-label::after {
    content: "";
    flex: 1;
    height: 1px;
    background: rgba(15, 23, 42, 0.08);
  }

  /* Module Card */
  .simply-home .sh-module-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    background: #ffffff;
    border: 1px solid var(--sh-border);
    border-radius: var(--sh-radius);
    padding: 16px 18px;
    height: 100%;
    text-decoration: none !important;
    color: var(--sh-text) !important;
    box-shadow: var(--sh-shadow);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    cursor: pointer;
    position: relative;
  }
  .simply-home .sh-module-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--sh-shadow-hover);
    border-color: rgba(13, 148, 136, 0.35);
  }
  .simply-home .sh-module-card.is-locked {
    opacity: 0.68;
    cursor: not-allowed;
    background: #f8fafc;
  }
  .simply-home .sh-module-card.is-locked:hover {
    transform: none;
    box-shadow: var(--sh-shadow);
    border-color: var(--sh-border);
  }
  .simply-home .sh-module-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
  }
  .simply-home .sh-module-body {
    flex: 1;
    min-width: 0;
  }
  .simply-home .sh-module-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 4px;
  }
  .simply-home .sh-module-name {
    font-size: 0.96rem;
    font-weight: 700;
    color: var(--sh-text);
    margin: 0;
  }
  .simply-home .sh-module-arrow {
    color: #cbd5e1;
    font-size: 0.8rem;
    transition: transform 0.2s ease, color 0.2s ease;
  }
  .simply-home .sh-module-card:hover .sh-module-arrow {
    color: var(--sh-primary);
    transform: translateX(3px);
  }
  .simply-home .sh-module-desc {
    font-size: 0.8rem;
    color: var(--sh-muted);
    margin: 0;
    line-height: 1.45;
  }

  /* Icon badge themes */
  .sh-accent-teal    { background: #f0fdfa; color: #0d9488; }
  .sh-accent-sky     { background: #f0f9ff; color: #0284c7; }
  .sh-accent-indigo  { background: #eef2ff; color: #4f46e5; }
  .sh-accent-violet  { background: #f5f3ff; color: #7c3aed; }
  .sh-accent-emerald { background: #ecfdf5; color: #059669; }
  .sh-accent-amber   { background: #fffbeb; color: #d97706; }
  .sh-accent-rose    { background: #fff1f2; color: #e11d48; }
  .sh-accent-cyan    { background: #ecfeff; color: #0891b2; }
  .sh-accent-slate   { background: #f1f5f9; color: #475569; }

  /* SweetAlert Agenda Popup Polish */
  .swal2-html-container {
    text-align: left;
  }
  .swal2-popup.swal2-modal.swal2-icon-info.swal2-show {
    background-color: #ffffff;
    border-radius: 16px;
  }
  .sh-agenda-swal-card {
    border: 1px solid #e2e8f0;
    border-left: 4px solid #0d9488;
    border-radius: 10px;
    margin-bottom: 10px;
    background: #f8fafc;
    overflow: hidden;
  }
  .sh-agenda-swal-head {
    padding: 9px 12px;
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 700;
    font-size: 0.9rem;
    color: #0f172a;
  }
  .sh-agenda-swal-time {
    font-size: 0.76rem;
    font-weight: 600;
    color: #0d9488;
    margin-top: 2px;
  }
  .sh-agenda-swal-body {
    padding: 10px 12px;
    font-size: 0.84rem;
    color: #334155;
    line-height: 1.5;
  }

  @media (max-width: 767.98px) {
    .simply-home .sh-hero {
      padding: 18px;
    }
    .simply-home .sh-hero-title {
      font-size: 1.25rem;
    }
    .simply-home .sh-hero-right {
      align-items: flex-start;
      width: 100%;
    }
    .simply-home .sh-clock-box {
      text-align: left;
      width: 100%;
    }
    .simply-home .sh-hero-actions {
      justify-content: flex-start;
    }
  }
</style>

<section class="content simply-home pb-4">
  <div class="container-fluid">

    <?php if (!empty($environment)): ?>
      <div class="sh-env-banner">
        <div class="d-flex align-items-center">
          <i class="fas fa-exclamation-triangle mr-2" style="font-size: 1.15rem;"></i>
          <div>
            <strong>Mode Development Aktif:</strong> Jangan lupa mengubah environment dari <code>development</code> menjadi <code>production</code> saat deploy ke server produksi.
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Hero Welcome Banner -->
    <div class="sh-hero">
      <div class="sh-hero-inner">
        <div>
          <div class="sh-greeting-eyebrow">
            <i class="fas fa-sun" id="shGreetingIcon"></i>
            <span id="shGreetingLabel">Selamat Datang</span>
          </div>
          <h2 class="sh-hero-title"><?= esc($namaUser); ?></h2>
          <div class="sh-meta-pills">
            <?php if ($jabatanUser !== ''): ?>
              <span class="sh-meta-pill"><i class="fas fa-id-badge"></i> <?= esc($jabatanUser); ?></span>
            <?php endif; ?>
            <?php if ($unitKerjaUser !== ''): ?>
              <span class="sh-meta-pill"><i class="fas fa-building"></i> <?= esc($unitKerjaUser); ?></span>
            <?php endif; ?>
            <?php if ($timKerjaUser !== ''): ?>
              <span class="sh-meta-pill"><i class="fas fa-users"></i> <?= esc($timKerjaUser); ?></span>
            <?php endif; ?>
            <?php if ($golonganUser !== ''): ?>
              <span class="sh-meta-pill"><i class="fas fa-award"></i> Gol. <?= esc($golonganUser); ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="sh-hero-right">
          <div class="sh-clock-box">
            <div class="sh-clock-time" id="shLiveClock">--:--:-- WIB</div>
            <div class="sh-clock-date" id="shLiveDate"><?= esc($tanggalIndo); ?></div>
          </div>
          <div class="sh-hero-actions">
            <button type="button" class="sh-btn-white new-fitur">
              <i class="fas fa-bullhorn"></i> Apa Ada Yang Baru?
            </button>
            <a href="/home/profil" class="sh-btn-glass">
              <i class="fas fa-user-circle"></i> Profil Saya
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Live Status KPI Strip -->
    <div class="row mb-3">
      <div class="col-md-4 mb-2 mb-md-0">
        <div class="sh-kpi-card">
          <div class="sh-kpi-left">
            <div class="sh-kpi-icon tone-<?= esc($absenStatusTone, 'attr'); ?>">
              <i class="fas <?= $absenStatusTone === 'success' ? 'fa-check-circle' : ($absenStatusTone === 'info' ? 'fa-user-clock' : 'fa-fingerprint'); ?>"></i>
            </div>
            <div style="min-width: 0;">
              <div class="sh-kpi-label">Absensi Hari Ini</div>
              <div class="sh-kpi-value"><?= esc($absenStatusLabel); ?></div>
              <div class="sh-kpi-sub" title="<?= esc($absenDetailText, 'attr'); ?>"><?= esc($absenDetailText); ?></div>
            </div>
          </div>
          <a href="/absensi" class="btn btn-sm btn-outline-primary sh-kpi-cta">
            Absen <i class="fas fa-arrow-right ml-1"></i>
          </a>
        </div>
      </div>

      <div class="col-md-4 mb-2 mb-md-0">
        <div class="sh-kpi-card">
          <div class="sh-kpi-left">
            <div class="sh-kpi-icon tone-indigo">
              <i class="fas fa-calendar-check"></i>
            </div>
            <div style="min-width: 0;">
              <div class="sh-kpi-label">Agenda Hari Ini</div>
              <div class="sh-kpi-value">
                <?= $agendaHariIni > 0 ? $agendaHariIni . ' Agenda Aktif' : 'Tidak Ada Agenda'; ?>
              </div>
              <div class="sh-kpi-sub">
                <?= $agendaHariIni > 0 ? 'Cek detail jadwal pada kalender di bawah' : 'Jadwal Anda hari ini sedang luang'; ?>
              </div>
            </div>
          </div>
          <a href="/home/agenda" class="btn btn-sm btn-outline-secondary sh-kpi-cta">
            Kelola
          </a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="sh-kpi-card">
          <div class="sh-kpi-left">
            <div class="sh-kpi-icon tone-teal">
              <i class="fas fa-mosque"></i>
            </div>
            <div style="min-width: 0;">
              <div class="sh-kpi-label">Waktu Shalat Berikutnya</div>
              <div class="sh-kpi-value" id="shNextPrayerTitle">Memuat...</div>
              <div class="sh-kpi-sub" id="shNextPrayerSub">Kota Bandung • WIB</div>
            </div>
          </div>
          <span class="badge badge-light border text-teal px-2 py-1" id="shNextPrayerCountdown" style="font-size: 0.76rem; color: #0f766e; font-variant-numeric: tabular-nums;">--:--</span>
        </div>
      </div>
    </div>

    <!-- Main Workspace Row: Utilities (Left) + Agenda Calendar (Right) -->
    <div class="row">
      <div class="col-xl-4 col-lg-5">
        <!-- Widget Jadwal Shalat -->
        <div class="sh-card">
          <div class="sh-card-header">
            <h6 class="sh-card-title"><i class="fas fa-mosque"></i> Jadwal Shalat • Kota Bandung</h6>
            <span class="badge badge-light text-muted border" style="font-size: 0.7rem;" id="shPrayerSourceBadge">Hari Ini</span>
          </div>
          <div class="sh-card-body">
            <div id="jadwal-shalat-container">
              <div class="sh-prayer-grid">
                <?php for ($i = 0; $i < 6; $i++): ?>
                  <div class="sh-prayer-tile">
                    <div class="sh-skel mx-auto mb-2" style="width: 46px; height: 10px;"></div>
                    <div class="sh-skel mx-auto" style="width: 52px; height: 18px;"></div>
                  </div>
                <?php endfor; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Akses Cepat & Aplikasi -->
        <div class="sh-card">
          <div class="sh-card-header">
            <h6 class="sh-card-title"><i class="fas fa-bolt"></i> Akses Cepat &amp; Perangkat</h6>
          </div>
          <div class="sh-card-body">
            <div class="sh-quick-grid">
              <a href="/manualBooks" target="_blank" rel="noopener noreferrer" class="sh-quick-tile">
                <div class="sh-quick-icon sh-accent-indigo">
                  <i class="fas fa-book-open"></i>
                </div>
                <div class="sh-quick-title">Manual Books</div>
                <div class="sh-quick-desc">Panduan lengkap penggunaan aplikasi</div>
              </a>
              <a href="/apk/simply.apk" class="sh-quick-tile">
                <div class="sh-quick-icon sh-accent-emerald">
                  <i class="fab fa-android"></i>
                </div>
                <div class="sh-quick-title">Aplikasi Android</div>
                <div class="sh-quick-desc">Download APK khusus absensi</div>
              </a>
            </div>

            <a href="/absensi" id="btn-link-absen" class="btn btn-block d-flex align-items-center justify-content-center">
              <i class="fas fa-map-marker-alt mr-2"></i>
              <span>Link Alternatif Absen Melalui Browser</span>
            </a>

            <div class="sh-tip-box">
              <i class="fas fa-info-circle text-info mr-1"></i>
              Aplikasi Android dikhususkan untuk absensi. Untuk pengelolaan fitur dan data yang lebih kompleks, gunakan komputer/laptop agar tampilan lebih maksimal.
            </div>
          </div>
        </div>

        <!-- Catatan Pribadi -->
        <div class="sh-card">
          <div class="sh-card-header">
            <h6 class="sh-card-title"><i class="fas fa-sticky-note" style="color: #d97706;"></i> Catatan Pribadi</h6>
            <span id="shNoteStatus" class="badge badge-light text-muted border" style="font-size: 0.7rem;">Privat</span>
          </div>
          <div class="sh-card-body">
            <form id="formCatatanPribadi">
              <div class="form-group mb-2">
                <textarea class="form-control" id="catatan_pribadi" name="catatan_pribadi" rows="4" placeholder="Tulis catatan pribadi Anda di sini (hanya Anda yang dapat melihatnya)... Tekan Ctrl+S untuk menyimpan cepat."><?= esc($catatanPribadi); ?></textarea>
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <small class="text-muted" id="shNoteCharCount"><?= strlen($catatanPribadi); ?> karakter</small>
                <button type="button" id="btnSimpanCatatan" class="btn btn-sm btn-warning text-dark font-weight-bold px-3" style="border-radius: 8px;">
                  <i class="fas fa-save mr-1"></i> Simpan Catatan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Kalender Agenda -->
      <div class="col-xl-8 col-lg-7 getCallendarAgenda">
        <div class="sh-skeleton-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="sh-skel" style="width: 180px; height: 22px;"></div>
            <div class="sh-skel" style="width: 130px; height: 28px;"></div>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <div class="sh-skel" style="width: 110px; height: 30px;"></div>
            <div class="sh-skel" style="width: 150px; height: 30px;"></div>
            <div class="sh-skel" style="width: 150px; height: 30px;"></div>
          </div>
          <div class="row">
            <?php for ($k = 0; $k < 21; $k++): ?>
              <div class="col-4 col-sm-2 mb-2">
                <div class="sh-skel" style="height: 62px;"></div>
              </div>
            <?php endfor; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Direktori Aplikasi & Modul -->
    <div class="sh-directory-header">
      <h4 class="sh-directory-title">
        <i class="fas fa-th-large" style="color: var(--sh-primary);"></i>
        Direktori Aplikasi &amp; Modul
      </h4>
      <div class="sh-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" id="homeModuleSearch" class="sh-search-input" placeholder="Cari modul (Dashboard, SINADI, SIPEDIT, SOP...)" autocomplete="off">
      </div>
    </div>

    <div class="sh-filter-pills" id="homeModuleFilters">
      <button type="button" class="sh-filter-pill active" data-filter="all">Semua Modul</button>
      <?php if (in_array('Dashboard', $accessMenu)) : ?>
        <button type="button" class="sh-filter-pill" data-filter="utama">Main Menu</button>
      <?php endif; ?>
      <button type="button" class="sh-filter-pill" data-filter="pengelolaan">Pengelolaan</button>
      <button type="button" class="sh-filter-pill" data-filter="arsip">Arsip</button>
      <button type="button" class="sh-filter-pill" data-filter="pengaturan">Pengaturan</button>
    </div>

    <div id="homeModuleEmpty" class="alert alert-light border text-center text-muted py-4 d-none" style="border-radius: 14px;">
      <i class="fas fa-search mb-2 d-block" style="font-size: 1.5rem; opacity: 0.5;"></i>
      Tidak ditemukan modul yang cocok dengan pencarian Anda.
    </div>

    <!-- Main Menu -->
    <?php if (in_array('Dashboard', $accessMenu)) : ?>
      <div class="sh-module-group" data-group="utama">
        <div class="sh-section-label"><i class="fas fa-compass"></i> Main Menu</div>
        <div class="row">
          <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="utama" data-keywords="dashboard pemantauan kinerja statistik grafik">
            <a href="/home/dashboard" class="sh-module-card" onclick="menuOpen('#dashboard')">
              <div class="sh-module-icon sh-accent-teal">
                <i class="fas fa-tachometer-alt"></i>
              </div>
              <div class="sh-module-body">
                <div class="sh-module-head">
                  <h5 class="sh-module-name">Dashboard</h5>
                  <i class="fas fa-chevron-right sh-module-arrow"></i>
                </div>
                <p class="sh-module-desc">Pemantauan secara langsung kinerja dari sistem untuk memperoleh informasi yang dibutuhkan.</p>
              </div>
            </a>
          </div>
          <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="utama" data-keywords="agenda jadwal kalender kegiatan">
            <a href="/home/agenda" class="sh-module-card">
              <div class="sh-module-icon sh-accent-indigo">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="sh-module-body">
                <div class="sh-module-head">
                  <h5 class="sh-module-name">Agenda Pegawai</h5>
                  <i class="fas fa-chevron-right sh-module-arrow"></i>
                </div>
                <p class="sh-module-desc">Kelola jadwal kegiatan, rapat, serta pengingat agenda harian Anda dan tim kerja.</p>
              </div>
            </a>
          </div>
          <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="utama" data-keywords="absen harian dinas luar kehadiran pegawai">
            <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#Absen')">
              <div class="sh-module-icon sh-accent-emerald">
                <i class="fas fa-business-time"></i>
              </div>
              <div class="sh-module-body">
                <div class="sh-module-head">
                  <h5 class="sh-module-name">Absen Pegawai</h5>
                  <i class="fas fa-chevron-right sh-module-arrow"></i>
                </div>
                <p class="sh-module-desc">Pantau rekap absen harian, absen dinas luar, zona lokasi, dan pengaturan absensi.</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Kategori Pengelolaan -->
    <div class="sh-module-group" data-group="pengelolaan">
      <div class="sh-section-label"><i class="fas fa-layer-group"></i> Kategori Pengelolaan</div>
      <div class="row">
        <?php if (in_array('Infografis', $accessMenu)) : ?>
          <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengelolaan" data-keywords="infografis kapal pesawat dokumen sertifikat vaksinasi">
            <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#infografis')">
              <div class="sh-module-icon sh-accent-sky">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="sh-module-body">
                <div class="sh-module-head">
                  <h5 class="sh-module-name">Infografis</h5>
                  <i class="fas fa-chevron-right sh-module-arrow"></i>
                </div>
                <p class="sh-module-desc">Pengelolaan informasi kedatangan dan keberangkatan kapal dan pesawat, dokumen, sertifikat, serta vaksinasi.</p>
              </div>
            </a>
          </div>
        <?php endif; ?>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengelolaan" data-keywords="sicute cuti perizinan pegawai pns pppk ppnpn">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#sicuti')">
            <div class="sh-module-icon sh-accent-violet">
              <i class="fas fa-laptop-house"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">SICUTE</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Permohonan pengajuan surat Cuti dan Perizinan pegawai khusus PNS, PPPK, dan PPNPN.</p>
            </div>
          </a>
        </div>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengelolaan" data-keywords="sinadi perjalanan dinas nominator kuitansi sppd nota dinas">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#sinadi')">
            <div class="sh-module-icon sh-accent-teal">
              <i class="fas fa-shuttle-van"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">SINADI</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Sistem Informasi Perjalanan Dinas untuk nominator, kuitansi, pengeluaran riil, dan SPPD dalam/luar kota.</p>
            </div>
          </a>
        </div>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengelolaan" data-keywords="relaxtime rencana realisasi keuangan anggaran realtime">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#relaxtime')">
            <div class="sh-module-icon sh-accent-emerald">
              <i class="fas fa-money-check-alt"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">RELAXTIME</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Rencana Realisasi Keuangan Realtime untuk pemantauan anggaran dan capaian bulanan.</p>
            </div>
          </a>
        </div>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengelolaan" data-keywords="simandja monitoring anggaran kinerja dja monev">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#simandja')">
            <div class="sh-module-icon sh-accent-amber">
              <i class="fas fa-file-signature"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">SIMANDJA</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Sistem Informasi Monitoring Anggaran dan Kinerja untuk evaluasi Direktorat Jenderal Anggaran (DJA).</p>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Kategori Arsip -->
    <div class="sh-module-group" data-group="arsip">
      <div class="sh-section-label"><i class="fas fa-folder-open"></i> Kategori Arsip</div>
      <div class="row">
        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="arsip" data-keywords="sipedit sertifikat pelatihan seminar webinar workshop jpl">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#sipedit')">
            <div class="sh-module-icon sh-accent-indigo">
              <i class="fas fa-book"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">SIPEDIT</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Penyimpanan sertifikat Pelatihan, Seminar, Webinar, dan Workshop untuk pemenuhan 20 Jam Pelajaran per tahun.</p>
            </div>
          </a>
        </div>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="arsip" data-keywords="sop sipepes standar operasional prosedur dokumen">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#sop')">
            <div class="sh-module-icon sh-accent-cyan">
              <i class="fas fa-copy"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">SOP</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Penyimpanan dan pengelolaan dokumen Standar Operasional Prosedur (SOP).</p>
            </div>
          </a>
        </div>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="arsip" data-keywords="wbk storage dokumen zona integritas">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#wbk')">
            <div class="sh-module-icon sh-accent-amber">
              <i class="fab fa-google-drive"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">WBK STORAGE</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Penyimpanan dokumen dan rencana kerja Wilayah Bebas dari Korupsi (WBK).</p>
            </div>
          </a>
        </div>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="arsip" data-keywords="arkab surat klasifikasi arsiparis tata naskah dinas">
          <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#arkab')">
            <div class="sh-module-icon sh-accent-slate">
              <i class="fas fa-archive"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">ARKAB</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Pengarsipan digital surat berdasarkan kode klasifikasi dan template tata naskah dinas.</p>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Kategori Pengaturan -->
    <div class="sh-module-group" data-group="pengaturan">
      <div class="sh-section-label"><i class="fas fa-sliders-h"></i> Kategori Pengaturan</div>
      <div class="row">
        <?php if (in_array('Admin', $accessMenu)) : ?>
          <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengaturan" data-keywords="admin administrator user pegawai kantor master">
            <a href="javascript:void(0)" class="sh-module-card" onclick="menuOpen('#admin')">
              <div class="sh-module-icon sh-accent-rose">
                <i class="fas fa-user-shield"></i>
              </div>
              <div class="sh-module-body">
                <div class="sh-module-head">
                  <h5 class="sh-module-name">Administrator</h5>
                  <i class="fas fa-chevron-right sh-module-arrow"></i>
                </div>
                <p class="sh-module-desc">Pengaturan lanjutan untuk pengelolaan data User, Pegawai, Kantor, dan fitur administrasi lainnya.</p>
              </div>
            </a>
          </div>
        <?php else : ?>
          <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengaturan" data-keywords="admin administrator">
            <div class="sh-module-card is-locked" title="Hanya tersedia untuk Administrator">
              <div class="sh-module-icon sh-accent-slate">
                <i class="fas fa-lock"></i>
              </div>
              <div class="sh-module-body">
                <div class="sh-module-head">
                  <h5 class="sh-module-name">Admin</h5>
                  <span class="badge badge-danger" style="font-size: 0.68rem;">Terbatas</span>
                </div>
                <p class="sh-module-desc">Pengaturan Administrator untuk pengelolaan data User, Pegawai, Kantor, dan fitur lainnya.</p>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <div class="col-xl-4 col-md-6 mb-3 sh-module-item" data-category="pengaturan" data-keywords="profil profile akun password pengaturan">
          <a href="/home/profil" class="sh-module-card">
            <div class="sh-module-icon sh-accent-emerald">
              <i class="fas fa-user-cog"></i>
            </div>
            <div class="sh-module-body">
              <div class="sh-module-head">
                <h5 class="sh-module-name">Pengaturan Profile</h5>
                <i class="fas fa-chevron-right sh-module-arrow"></i>
              </div>
              <p class="sh-module-desc">Kelola profil kepegawaian, foto, kontak, serta keamanan akun Anda.</p>
            </div>
          </a>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
  (function() {
    const HARI_ID = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const BULAN_ID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    let todayPrayerSchedule = null;

    function updateClockAndGreeting() {
      const now = new Date();
      const h = now.getHours();
      const pad = n => String(n).padStart(2, '0');

      const clockEl = document.getElementById('shLiveClock');
      if (clockEl) {
        clockEl.textContent = `${pad(h)}:${pad(now.getMinutes())}:${pad(now.getSeconds())} WIB`;
      }

      const dateEl = document.getElementById('shLiveDate');
      if (dateEl) {
        dateEl.textContent = `${HARI_ID[now.getDay()]}, ${now.getDate()} ${BULAN_ID[now.getMonth()]} ${now.getFullYear()}`;
      }

      const greetEl = document.getElementById('shGreetingLabel');
      const greetIconEl = document.getElementById('shGreetingIcon');
      if (greetEl) {
        let greet = 'Selamat Malam';
        let iconClass = 'fas fa-moon';
        if (h >= 4 && h < 11) { greet = 'Selamat Pagi'; iconClass = 'fas fa-sun'; }
        else if (h >= 11 && h < 15) { greet = 'Selamat Siang'; iconClass = 'fas fa-sun'; }
        else if (h >= 15 && h < 18) { greet = 'Selamat Sore'; iconClass = 'fas fa-cloud-sun'; }
        greetEl.textContent = greet;
        if (greetIconEl) greetIconEl.className = iconClass;
      }

      if (todayPrayerSchedule) {
        updateNextPrayerIndicator(todayPrayerSchedule, now);
      }
    }

    function parsePrayerMinutes(hhmm) {
      if (!hhmm || typeof hhmm !== 'string') return null;
      const parts = hhmm.trim().split(':');
      if (parts.length < 2) return null;
      const hh = parseInt(parts[0], 10);
      const mm = parseInt(parts[1], 10);
      if (isNaN(hh) || isNaN(mm)) return null;
      return hh * 60 + mm;
    }

    function updateNextPrayerIndicator(schedule, now) {
      const items = [
        { key: 'imsak',   label: 'Imsak',   time: schedule.imsak },
        { key: 'subuh',   label: 'Subuh',   time: schedule.subuh },
        { key: 'dzuhur',  label: 'Dzuhur',  time: schedule.dzuhur },
        { key: 'ashar',   label: 'Ashar',   time: schedule.ashar },
        { key: 'maghrib', label: 'Maghrib', time: schedule.maghrib },
        { key: 'isya',    label: 'Isya',    time: schedule.isya }
      ];

      const currentMinutes = now.getHours() * 60 + now.getMinutes();
      let nextItem = null;
      let diffMinutes = 0;

      for (let i = 0; i < items.length; i++) {
        const mins = parsePrayerMinutes(items[i].time);
        if (mins !== null && mins > currentMinutes) {
          nextItem = items[i];
          diffMinutes = mins - currentMinutes;
          break;
        }
      }

      if (!nextItem) {
        nextItem = items[0];
        const imsakMins = parsePrayerMinutes(items[0].time) || 260;
        diffMinutes = (24 * 60 - currentMinutes) + imsakMins;
      }

      const titleEl = document.getElementById('shNextPrayerTitle');
      const subEl = document.getElementById('shNextPrayerSub');
      const cdEl = document.getElementById('shNextPrayerCountdown');

      if (titleEl) titleEl.textContent = `${nextItem.label} • ${nextItem.time}`;
      if (subEl) subEl.textContent = 'Jadwal Shalat Kota Bandung';
      if (cdEl) {
        const hrs = Math.floor(diffMinutes / 60);
        const mins = diffMinutes % 60;
        cdEl.textContent = hrs > 0 ? `-${hrs}j ${mins}m` : `-${mins}m`;
      }

      document.querySelectorAll('.sh-prayer-tile[data-prayer]').forEach(function(tile) {
        tile.classList.toggle('is-next', tile.getAttribute('data-prayer') === nextItem.key);
      });
    }

    function renderPrayerSchedule(scheduleToday, fromCache) {
      todayPrayerSchedule = scheduleToday;
      const items = [
        { key: 'imsak',   label: 'Imsak',   time: scheduleToday.imsak },
        { key: 'subuh',   label: 'Subuh',   time: scheduleToday.subuh },
        { key: 'dzuhur',  label: 'Dzuhur',  time: scheduleToday.dzuhur },
        { key: 'ashar',   label: 'Ashar',   time: scheduleToday.ashar },
        { key: 'maghrib', label: 'Maghrib', time: scheduleToday.maghrib },
        { key: 'isya',    label: 'Isya',    time: scheduleToday.isya }
      ];

      let html = '<div class="sh-prayer-grid">';
      items.forEach(function(it) {
        html += `
          <div class="sh-prayer-tile" data-prayer="${it.key}">
            <span class="sh-prayer-name">${it.label}</span>
            <span class="sh-prayer-time">${it.time || '--:--'}</span>
          </div>
        `;
      });
      html += '</div>';

      const container = document.getElementById('jadwal-shalat-container');
      if (container) container.innerHTML = html;

      const badge = document.getElementById('shPrayerSourceBadge');
      if (badge) badge.textContent = fromCache ? 'Tersimpan Lokal' : 'Diperbarui';

      updateNextPrayerIndicator(scheduleToday, new Date());
    }

    function loadPrayerTimes() {
      const now = new Date();
      const monthKey = `simply_shalat_bandung_${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
      const todayDate = now.getDate();

      try {
        const cachedRaw = localStorage.getItem(monthKey);
        if (cachedRaw) {
          const cachedList = JSON.parse(cachedRaw);
          if (Array.isArray(cachedList)) {
            const scheduleToday = cachedList.find(j => Number(j.tanggal) === todayDate);
            if (scheduleToday) {
              renderPrayerSchedule(scheduleToday, true);
              return;
            }
          }
        }
      } catch (e) {}

      const controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
      const timer = controller ? setTimeout(() => controller.abort(), 6000) : null;

      fetch("https://equran.id/api/v2/shalat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ provinsi: "Jawa Barat", kabkota: "Kota Bandung" }),
        signal: controller ? controller.signal : undefined
      })
      .then(response => response.json())
      .then(result => {
        if (timer) clearTimeout(timer);
        if (result && result.code === 200 && result.data && Array.isArray(result.data.jadwal)) {
          try {
            localStorage.setItem(monthKey, JSON.stringify(result.data.jadwal));
          } catch (e) {}
          const scheduleToday = result.data.jadwal.find(j => Number(j.tanggal) === todayDate);
          if (scheduleToday) {
            renderPrayerSchedule(scheduleToday, false);
            return;
          }
        }
        const c = document.getElementById('jadwal-shalat-container');
        if (c) c.innerHTML = '<div class="text-center text-muted py-2 small">Jadwal shalat hari ini tidak tersedia.</div>';
        const t = document.getElementById('shNextPrayerTitle');
        if (t) t.textContent = 'Tidak Tersedia';
      })
      .catch(function() {
        if (timer) clearTimeout(timer);
        const c = document.getElementById('jadwal-shalat-container');
        if (c) c.innerHTML = '<div class="text-center text-muted py-2 small"><i class="fas fa-wifi mr-1"></i> Jadwal shalat belum dapat dimuat (koneksi offline).</div>';
        const t = document.getElementById('shNextPrayerTitle');
        if (t) t.textContent = 'Offline';
      });
    }

    function escapeHtml(str) {
      return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function convertUrlsToLinksForAlert(text) {
      if (!text) return '';
      const safeText = escapeHtml(text);
      const urlRegex = /((?:https?:\/\/|www\.)(?:[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&//=]*)))|([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}(?:\/[-a-zA-Z0-9()@:%_\+.~#?&//=]*)?)/g;
      return safeText.replace(urlRegex, function(url) {
        let href = url;
        if (!/^https?:\/\//i.test(href)) {
          href = 'https://' + href;
        }
        return `<button type="button" class="btn btn-xs btn-primary link-button-alert mx-1" data-url="${escapeHtml(href)}"><i class="fas fa-external-link-alt mr-1"></i>Buka Link</button>`;
      });
    }

    function getCallendarAgenda() {
      $.ajax({
        url: '/home/getCallendarAgenda',
        method: 'get',
        dataType: 'json',
        success: function(response) {
          if (response && response.data) {
            $(".getCallendarAgenda").html(response.data);
          }
        },
        error: function() {
          $(".getCallendarAgenda").html(
            '<div class="sh-card"><div class="sh-card-body text-center py-5 text-muted">'
            + '<i class="fas fa-calendar-times fa-2x mb-2 text-danger"></i>'
            + '<p class="mb-2">Gagal memuat kalender agenda.</p>'
            + '<button type="button" class="btn btn-sm btn-outline-primary" id="btnRetryAgendaCal"><i class="fas fa-sync-alt mr-1"></i> Coba Lagi</button>'
            + '</div></div>'
          );
          $('#btnRetryAgendaCal').on('click', getCallendarAgenda);
        }
      });
    }

    function getInformationAgenda() {
      window.agendaAlertWillShow = true;
      $.ajax({
        url: '/home/ajax/getAgenda',
        method: 'get',
        dataType: 'json',
        success: function(response) {
          if (response && Array.isArray(response.data) && response.data.length > 0) {
            let cardsHtml = '<p class="text-muted small mb-3">Jangan lewatkan beberapa agenda dan informasi penting berikut:</p><div style="max-height: 360px; overflow-y: auto; padding-right: 4px;">';
            response.data.forEach(function(cardData) {
              const title = escapeHtml(cardData.topik || 'Agenda');
              const range = `${escapeHtml(cardData.waktu_pelaksana_awal || '')} — ${escapeHtml(cardData.waktu_pelaksana_akhir || '')}`;
              const desc = convertUrlsToLinksForAlert(cardData.deskripsi || 'Tidak ada deskripsi.');
              cardsHtml += `
                <div class="sh-agenda-swal-card">
                  <div class="sh-agenda-swal-head">
                    <div>${title}</div>
                    <div class="sh-agenda-swal-time"><i class="far fa-clock mr-1"></i>${range}</div>
                  </div>
                  <div class="sh-agenda-swal-body">${desc}</div>
                </div>
              `;
            });
            cardsHtml += '</div>';

            Swal.fire({
              title: '<strong>Informasi untuk Anda</strong>',
              icon: 'info',
              html: cardsHtml,
              showCloseButton: true,
              showCancelButton: false,
              focusConfirm: false,
              allowOutsideClick: false,
              confirmButtonColor: '#0d9488',
              confirmButtonText: '<i class="fa fa-thumbs-up mr-1"></i> Dimengerti',
              didOpen: () => {
                document.querySelectorAll('.swal2-html-container .link-button-alert').forEach(function(button) {
                  button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const url = this.getAttribute('data-url');
                    if (url) window.open(url, '_blank', 'noopener,noreferrer');
                  });
                });
              }
            }).then(function() {
              window.agendaAlertWillShow = false;
              if (typeof window.showBirthdayPopup === 'function') {
                window.showBirthdayPopup();
              }
            });
          } else {
            window.agendaAlertWillShow = false;
          }
        },
        error: function() {
          window.agendaAlertWillShow = false;
        }
      });
    }

    function initModuleFilter() {
      let activeCategory = 'all';
      const $search = $('#homeModuleSearch');
      const $items = $('.sh-module-item');
      const $groups = $('.sh-module-group');
      const $empty = $('#homeModuleEmpty');

      function applyFilter() {
        const q = ($search.val() || '').trim().toLowerCase();
        let visibleTotal = 0;

        $items.each(function() {
          const $item = $(this);
          const cat = $item.data('category');
          const text = ($item.text() + ' ' + ($item.data('keywords') || '')).toLowerCase();
          const matchCat = (activeCategory === 'all' || cat === activeCategory);
          const matchText = (q === '' || text.indexOf(q) !== -1);
          const show = matchCat && matchText;
          $item.toggle(show);
          if (show) visibleTotal++;
        });

        $groups.each(function() {
          const $grp = $(this);
          const hasVisible = $grp.find('.sh-module-item:visible').length > 0;
          $grp.toggle(hasVisible);
        });

        $empty.toggleClass('d-none', visibleTotal > 0);
      }

      $search.on('input', applyFilter);
      $('#homeModuleFilters').on('click', '.sh-filter-pill', function() {
        $('#homeModuleFilters .sh-filter-pill').removeClass('active');
        $(this).addClass('active');
        activeCategory = $(this).data('filter') || 'all';
        applyFilter();
      });
    }

    function savePersonalNote() {
      const btn = $('#btnSimpanCatatan');
      const text = $('#catatan_pribadi').val();
      const statusBadge = $('#shNoteStatus');

      $.ajax({
        url: '/home/simpanCatatanPribadi',
        method: 'post',
        data: { catatan: text },
        dataType: 'json',
        beforeSend: function() {
          btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...').prop('disabled', true);
        },
        success: function(response) {
          if (response && response.status === 'success') {
            statusBadge.removeClass('badge-warning text-dark').addClass('badge-success text-white')
              .text(response.saved_at ? 'Tersimpan ' + response.saved_at : 'Tersimpan');
            if (typeof Toast !== 'undefined') {
              Toast.fire({ icon: 'success', title: 'Catatan pribadi berhasil disimpan!' });
            } else {
              Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Catatan pribadi berhasil disimpan!', timer: 1400, showConfirmButton: false });
            }
          } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menyimpan catatan.' });
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire({ icon: 'error', title: 'Error', text: xhr.status + ' ' + thrownError });
        },
        complete: function() {
          btn.html('<i class="fas fa-save mr-1"></i> Simpan Catatan').prop('disabled', false);
        }
      });
    }

    $(document).ready(function() {
      updateClockAndGreeting();
      setInterval(updateClockAndGreeting, 1000);

      loadPrayerTimes();
      getCallendarAgenda();
      initModuleFilter();

      <?php if (!session()->getFlashdata('gantiPassword')) : ?>
        getInformationAgenda();
      <?php endif; ?>

      $('.new-fitur').on('click', function() {
        $.ajax({
          url: '/home/new-fitur',
          method: 'get',
          dataType: 'json',
          beforeSend: function() {
            $('#overlay').show();
          },
          success: function(response) {
            if (response && response.data) {
              $('#modal-lg').find('.modal-header').html(
                '<h5 class="modal-title font-weight-bold"><i class="fas fa-bullhorn text-info mr-2"></i>Pembaruan &amp; Fitur Baru</h5>'
                + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
              );
              $('#modal-lg').find('.modal-body').html(response.data);
              $('#modal-lg').modal('show');
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({ icon: 'error', title: 'Gagal Memuat', text: xhr.status + ' ' + thrownError });
          },
          complete: function() {
            $('#overlay').hide();
          }
        });
      });

      $('#btnSimpanCatatan').on('click', savePersonalNote);

      $('#catatan_pribadi').on('input', function() {
        $('#shNoteCharCount').text($(this).val().length + ' karakter');
        $('#shNoteStatus').removeClass('badge-success text-white badge-light text-muted').addClass('badge-warning text-dark').text('Belum disimpan');
      }).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
          e.preventDefault();
          savePersonalNote();
        }
      });
    });
  })();
</script>

<?= $this->endSection(); ?>
