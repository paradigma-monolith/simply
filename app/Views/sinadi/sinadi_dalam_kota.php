<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$isDalamKota = ($activeMenu === 'dinasDalamKota');
$pageHeading = $isDalamKota ? 'Perjalanan Dinas Dalam Kota' : 'Perjalanan Dinas Luar Kota';
$pageSubtitle = $isDalamKota
  ? 'Kelola pengajuan, tanda tangan digital, dan dokumen perjalanan dinas dalam wilayah kota'
  : 'Kelola pengajuan, SPPD, tanda tangan digital, dan dokumen perjalanan dinas luar kota';
$formRoute = $isDalamKota ? '/sinadi/formulirPerjalananDinas' : '/sinadi/formulirPerjalananDinasAntarKota';
$initStart = $start ?? date('Y-m-d', strtotime('-2 months'));
$initEnd = $end ?? date('Y-m-d', strtotime('+1 months'));
?>

<style>
  .sn-page {
    --sn-primary: #0d9488;
    --sn-primary-dark: #0f766e;
    --sn-primary-soft: #f0fdfa;
    --sn-border: rgba(15, 23, 42, 0.08);
    --sn-text: #0f172a;
    --sn-muted: #64748b;
    --sn-radius: 14px;
    --sn-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    padding-bottom: 24px;
  }

  /* Hero Header */
  .sn-hero {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    border-radius: var(--sn-radius);
    padding: 18px 22px;
    color: #ffffff;
    box-shadow: 0 8px 22px rgba(4, 120, 121, 0.18);
    margin-bottom: 14px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
  }
  .sn-hero-title {
    font-size: 1.18rem;
    font-weight: 800;
    margin: 0 0 3px;
    letter-spacing: -0.2px;
  }
  .sn-hero-sub {
    font-size: 0.81rem;
    color: rgba(255, 255, 255, 0.86);
    margin: 0;
  }
  .sn-hero-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
  }
  .sn-switch-group {
    display: inline-flex;
    background: rgba(0, 0, 0, 0.18);
    padding: 3px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.18);
  }
  .sn-switch-link {
    padding: 6px 12px;
    border-radius: 7px;
    font-size: 0.77rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.85) !important;
    text-decoration: none !important;
    transition: all 0.16s ease;
  }
  .sn-switch-link.active {
    background: #ffffff;
    color: #0f766e !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.14);
  }
  .sn-btn-add {
    background: #ffffff;
    color: #0f766e !important;
    font-weight: 700;
    font-size: 0.81rem;
    padding: 8px 15px;
    border-radius: 10px;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.14);
    transition: transform 0.16s ease, box-shadow 0.16s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .sn-btn-add:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    background: #f0fdfa;
  }

  /* Filter Toolbar Card */
  .sn-toolbar-card {
    background: #ffffff;
    border: 1px solid var(--sn-border);
    border-radius: var(--sn-radius);
    box-shadow: var(--sn-shadow);
    padding: 12px 16px;
    margin-bottom: 14px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }
  .sn-date-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    flex: 1 1 320px;
  }
  .sn-date-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #334155;
    margin: 0;
    white-space: nowrap;
  }
  .sn-date-input-group {
    max-width: 260px;
    flex: 1 1 220px;
  }
  .sn-date-input-group .form-control {
    border-top-left-radius: 9px;
    border-bottom-left-radius: 9px;
    font-size: 0.81rem;
    font-weight: 600;
    color: #0f172a;
    height: 34px;
  }
  .sn-date-input-group .input-group-text {
    border-top-right-radius: 9px;
    border-bottom-right-radius: 9px;
    background: #f8fafc;
    color: #0d9488;
  }
  .sn-preset-pills {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
  }
  .sn-preset-btn {
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    font-size: 0.73rem;
    font-weight: 600;
    padding: 5px 11px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .sn-preset-btn:hover,
  .sn-preset-btn.active {
    background: var(--sn-primary-soft);
    border-color: #0d9488;
    color: #0f766e;
  }

  /* Main Table Card */
  .sn-main-card {
    background: #ffffff;
    border: 1px solid var(--sn-border);
    border-radius: var(--sn-radius);
    box-shadow: var(--sn-shadow);
    overflow: hidden;
  }
  .sn-main-body {
    padding: 14px 16px;
  }

  /* Download Modal Grid */
  .sn-dl-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    margin-top: 10px;
  }
  .sn-dl-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 9px 12px;
    border-radius: 9px;
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #b91c1c !important;
    font-size: 0.79rem;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.16s ease;
  }
  .sn-dl-item:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: #ffffff !important;
  }

  @media (max-width: 767.98px) {
    .sn-hero {
      padding: 14px 16px;
    }
    .sn-hero-title {
      font-size: 1.02rem;
    }
    .sn-hero-actions {
      width: 100%;
      justify-content: space-between;
    }
    .sn-toolbar-card {
      padding: 10px 12px;
    }
    .sn-date-input-group {
      max-width: 100%;
    }
    .sn-main-body {
      padding: 10px 12px;
    }
    .sn-dl-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<section class="content sn-page">
  <div class="container-fluid">

    <!-- Hero Banner -->
    <div class="sn-hero">
      <div>
        <h4 class="sn-hero-title">
          <i class="fas <?= $isDalamKota ? 'fa-city' : 'fa-route'; ?> mr-2"></i><?= $pageHeading; ?>
        </h4>
        <p class="sn-hero-sub"><?= $pageSubtitle; ?></p>
      </div>
      <div class="sn-hero-actions">
        <div class="sn-switch-group">
          <a href="/sinadi/dinasDalamKota" class="sn-switch-link <?= $isDalamKota ? 'active' : ''; ?>">
            <i class="fas fa-building mr-1"></i>Dalam Kota
          </a>
          <a href="/sinadi/dinasLuarKota" class="sn-switch-link <?= !$isDalamKota ? 'active' : ''; ?>">
            <i class="fas fa-plane-departure mr-1"></i>Luar Kota
          </a>
        </div>
        <a href="<?= $formRoute; ?>" class="sn-btn-add">
          <i class="fas fa-plus-circle"></i> Tambah Data
        </a>
        <button type="button" id="btnExportSinadiExcel" class="sn-btn-add" style="background: rgba(255,255,255,0.16); color: #ffffff !important; border: 1px solid rgba(255,255,255,0.35);">
          <i class="fas fa-file-excel"></i> Export Excel
        </button>
      </div>
    </div>

    <!-- Date Filter & Quick Period Toolbar -->
    <div class="sn-toolbar-card">
      <div class="sn-date-wrap">
        <label class="sn-date-label" for="sinadiAdmin">
          <i class="far fa-calendar-alt text-info mr-1"></i>Periode Pelaksanaan:
        </label>
        <div class="input-group sn-date-input-group">
          <input type="text"
                 name="tanggal"
                 id="sinadiAdmin"
                 autocomplete="off"
                 class="form-control"
                 value="<?= esc($initStart . ' - ' . $initEnd, 'attr'); ?>">
          <div class="input-group-append">
            <span class="input-group-text">
              <i class="far fa-calendar-alt"></i>
            </span>
          </div>
        </div>
      </div>

      <div class="sn-preset-pills">
        <button type="button" class="sn-preset-btn" data-preset="this_month">Bulan Ini</button>
        <button type="button" class="sn-preset-btn active" data-preset="default_3m">3 Bulan (Default)</button>
        <button type="button" class="sn-preset-btn" data-preset="last_6m">6 Bulan</button>
        <button type="button" class="sn-preset-btn" data-preset="this_year">Tahun <?= date('Y'); ?></button>
      </div>
    </div>

    <!-- Main Content Card (Pre-rendered dari server saat pertama dimuat!) -->
    <div class="sn-main-card">
      <div class="sn-main-body">
        <input type="hidden" id="activeMenuInput" value="<?= esc($activeMenu, 'attr'); ?>">
        <div class="sinadi_dalam_kota_view">
          <?= view('sinadi/sinadi_dalam_kota_view', [
            'perjalanan' => $perjalanan ?? [],
            'akses'      => $akses ?? false,
            'activeMenu' => $activeMenu,
            'start'      => $initStart,
            'end'        => $initEnd,
          ]); ?>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
  window.__sinadiPesertaCache = window.__sinadiPesertaCache || {};
  var lastAjaxRequestSinadi = null;

  function getDataSinadi(id) {
    return new Promise(function(resolve, reject) {
      if (window.__sinadiPesertaCache[id]) {
        resolve(window.__sinadiPesertaCache[id]);
        return;
      }

      if (lastAjaxRequestSinadi && lastAjaxRequestSinadi.readyState !== 4) {
        lastAjaxRequestSinadi.abort();
      }

      lastAjaxRequestSinadi = $.ajax({
        url: '/sinadi/getPeserta/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          window.__sinadiPesertaCache[id] = response;
          resolve(response);
        },
        error: function(xhr, ajaxOptions, thrownError) {
          if (thrownError !== 'abort') {
            Swal.fire('Gagal Memuat Peserta', 'Error: ' + xhr.status + ' ' + thrownError, 'error');
          }
          reject(thrownError);
        }
      });
    });
  }

  function sinadi_dalam_kota_view(start, end) {
    var activeMenu = $('#activeMenuInput').val() || 'dinasDalamKota';
    var ajaxUrl = (activeMenu === 'dinasDalamKota') ? '/sinadi/dinasDalamKotaView' : '/sinadi/dinasLuarKotaView';

    $('.sinadi_dalam_kota_view').css('opacity', '0.55');

    $.ajax({
      url: ajaxUrl,
      method: 'post',
      dataType: 'json',
      data: {
        start: start || '',
        end: end || '',
        activeMenu: activeMenu
      },
      success: function(response) {
        $('.sinadi_dalam_kota_view').css('opacity', '1');
        if (response && response.data !== undefined) {
          $('.sinadi_dalam_kota_view').html(response.data);
        }
        var $pickerInput = $('#sinadiAdmin');
        if (response && response.start && response.end && $pickerInput.data('daterangepicker')) {
          $pickerInput.data('daterangepicker').setStartDate(response.start);
          $pickerInput.data('daterangepicker').setEndDate(response.end);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $('.sinadi_dalam_kota_view').css('opacity', '1');
        Swal.fire('Terjadi kesalahan!', 'Error: ' + xhr.status + ' ' + thrownError, 'error');
      }
    });
  }

  function buildSinadiDownloadModalHtml(id, nama, bukti_pembayaran, isNewVersion) {
    var allFn = isNewVersion ? 'downloadAllFilesSinadiNew' : 'downloadAllFilesSinadi';
    var noSppdFn = isNewVersion ? 'downloadAllFilesSinadiNewWithoutSPPD' : 'downloadAllFilesSinadiWithoutSPPD';
    var safeBukti = (bukti_pembayaran && bukti_pembayaran !== 'null') ? bukti_pembayaran : '';

    var html = '<div class="text-left">'
      + '<div class="p-2 mb-3 rounded bg-light border small text-muted">'
      + '<i class="fas fa-user-edit text-info mr-1"></i> Penanggung Jawab / Akses Edit: <strong class="text-dark">' + nama + '</strong>'
      + '</div>'
      + '<button type="button" onclick="' + allFn + '(\'' + id + '\', \'' + nama + '\', \'' + safeBukti + '\')" class="btn btn-success btn-sm btn-block font-weight-bold py-2 mb-2" style="border-radius:9px;">'
      + '<i class="fas fa-download mr-1"></i> Unduh Semua Dokumen Sekaligus'
      + '</button>'
      + '<div class="row mx-n1 mb-2">'
      + '<div class="col-6 px-1">'
      + '<button type="button" onclick="' + noSppdFn + '(\'' + id + '\', \'' + nama + '\', \'' + safeBukti + '\')" class="btn btn-info btn-xs btn-block py-2 font-weight-bold" style="border-radius:8px;">'
      + '<i class="fas fa-file-download mr-1"></i> Semua Tanpa SPPD'
      + '</button>'
      + '</div>'
      + '<div class="col-6 px-1">'
      + '<a href="/sinadi/download_nota_dinas_zip/' + id + '" class="btn btn-warning btn-xs btn-block py-2 font-weight-bold text-dark" style="border-radius:8px;">'
      + '<i class="fas fa-file-archive mr-1"></i> ZIP Nota Dinas'
      + '</a>'
      + '</div>'
      + '</div>'
      + '<div class="small font-weight-bold text-muted text-uppercase mt-3 mb-1">Unduh Satuan (PDF):</div>'
      + '<div class="sn-dl-grid">'
      + '<a href="/sinadi/nominatif/' + id + '" class="sn-dl-item"><i class="fas fa-file-pdf"></i> Nominatif</a>';

    if (isNewVersion) {
      html += '<a href="/sinadi/kuitansi-riil/' + id + '" class="sn-dl-item"><i class="fas fa-file-pdf"></i> Kuitansi &amp; Riil</a>';
    } else {
      html += '<a href="/sinadi/kuitansi/' + id + '" class="sn-dl-item"><i class="fas fa-file-pdf"></i> Kuitansi</a>'
        + '<a href="/sinadi/riil/' + id + '" class="sn-dl-item"><i class="fas fa-file-pdf"></i> Pengeluaran Riil</a>';
    }

    html += '<a href="/sinadi/sppd/' + id + '" class="sn-dl-item"><i class="fas fa-file-pdf"></i> SPPD</a>'
      + '<a href="/sinadi/export/lembar_verifikator/pdf/' + id + '" class="sn-dl-item"><i class="fas fa-file-pdf"></i> Lembar Verifikator</a>';

    if (safeBukti !== '') {
      html += '<a href="/sinadi/previewBuktiBerkas/' + encodeURIComponent(safeBukti) + '" class="sn-dl-item" download><i class="fas fa-receipt"></i> Bukti Pembayaran</a>';
    }

    html += '</div></div>';
    return html;
  }

  function downloadFileSinadi(id, nama, bukti_pembayaran) {
    Swal.fire({
      title: 'Unduh Dokumen Perjalanan',
      html: buildSinadiDownloadModalHtml(id, nama, bukti_pembayaran || '', false),
      showCloseButton: true,
      showCancelButton: true,
      showConfirmButton: false,
      cancelButtonText: 'Tutup'
    });
  }

  function downloadFileSinadiNew(id, nama, bukti_pembayaran) {
    Swal.fire({
      title: 'Unduh Dokumen Perjalanan',
      html: buildSinadiDownloadModalHtml(id, nama, bukti_pembayaran || '', true),
      showCloseButton: true,
      showCancelButton: true,
      showConfirmButton: false,
      cancelButtonText: 'Tutup'
    });
  }

  function triggerSequentialDownloads(urls, titleText) {
    Swal.fire({
      title: titleText,
      text: 'Dokumen sedang diunduh secara berurutan...',
      icon: 'info',
      showConfirmButton: false,
      allowOutsideClick: false,
      timer: 2000
    });
    urls.forEach(function(url, index) {
      setTimeout(function() {
        var link = document.createElement('a');
        link.href = url;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }, index * 500);
    });
  }

  function downloadAllFilesSinadi(id, nama, bukti_pembayaran) {
    var urls = [
      '/sinadi/nominatif/' + id,
      '/sinadi/kuitansi/' + id,
      '/sinadi/riil/' + id,
      '/sinadi/sppd/' + id,
      '/sinadi/export/lembar_verifikator/pdf/' + id
    ];
    if (bukti_pembayaran && bukti_pembayaran !== 'null') {
      urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
    }
    triggerSequentialDownloads(urls, 'Mengunduh Semua Dokumen');
  }

  function downloadAllFilesSinadiNew(id, nama, bukti_pembayaran) {
    var urls = [
      '/sinadi/nominatif/' + id,
      '/sinadi/kuitansi-riil/' + id,
      '/sinadi/sppd/' + id,
      '/sinadi/export/lembar_verifikator/pdf/' + id
    ];
    if (bukti_pembayaran && bukti_pembayaran !== 'null') {
      urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
    }
    triggerSequentialDownloads(urls, 'Mengunduh Semua Dokumen');
  }

  function downloadAllFilesSinadiWithoutSPPD(id, nama, bukti_pembayaran) {
    var urls = [
      '/sinadi/nominatif/' + id,
      '/sinadi/kuitansi/' + id,
      '/sinadi/riil/' + id,
      '/sinadi/export/lembar_verifikator/pdf/' + id
    ];
    if (bukti_pembayaran && bukti_pembayaran !== 'null') {
      urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
    }
    triggerSequentialDownloads(urls, 'Mengunduh Dokumen Tanpa SPPD');
  }

  function downloadAllFilesSinadiNewWithoutSPPD(id, nama, bukti_pembayaran) {
    var urls = [
      '/sinadi/nominatif/' + id,
      '/sinadi/kuitansi-riil/' + id,
      '/sinadi/export/lembar_verifikator/pdf/' + id
    ];
    if (bukti_pembayaran && bukti_pembayaran !== 'null') {
      urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
    }
    triggerSequentialDownloads(urls, 'Mengunduh Dokumen Tanpa SPPD');
  }

  $(document).ready(function() {
    var $input = $('#sinadiAdmin');
    if ($input.length && typeof $.fn.daterangepicker !== 'undefined') {
      $input.daterangepicker({
        startDate: '<?= esc($initStart, 'js'); ?>',
        endDate: '<?= esc($initEnd, 'js'); ?>',
        locale: {
          format: 'YYYY-MM-DD',
          applyLabel: 'Terapkan',
          cancelLabel: 'Batal'
        }
      });

      $input.on('apply.daterangepicker', function(ev, picker) {
        $('.sn-preset-btn').removeClass('active');
        sinadi_dalam_kota_view(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
      });
    }

    // Tombol preset rentang waktu cepat
    $('.sn-preset-btn').on('click', function() {
      $('.sn-preset-btn').removeClass('active');
      $(this).addClass('active');

      var preset = $(this).data('preset');
      var s = moment().subtract(2, 'months');
      var e = moment().add(1, 'months');

      if (preset === 'this_month') {
        s = moment().startOf('month');
        e = moment().endOf('month');
      } else if (preset === 'last_6m') {
        s = moment().subtract(5, 'months').startOf('month');
        e = moment().add(1, 'months').endOf('month');
      } else if (preset === 'this_year') {
        s = moment().startOf('year');
        e = moment().endOf('year');
      }

      var sStr = s.format('YYYY-MM-DD');
      var eStr = e.format('YYYY-MM-DD');
      if ($input.data('daterangepicker')) {
        $input.data('daterangepicker').setStartDate(sStr);
        $input.data('daterangepicker').setEndDate(eStr);
      }
      sinadi_dalam_kota_view(sStr, eStr);
    });

    $('#btnExportSinadiExcel').on('click', function() {
      var activeMenu = $('#activeMenuInput').val() || 'dinasDalamKota';
      var jenis = (activeMenu === 'dinasLuarKota') ? 'luar_kota' : 'dalam_kota';
      var startVal = '<?= esc($initStart, 'js'); ?>';
      var endVal = '<?= esc($initEnd, 'js'); ?>';

      var picker = $input.data('daterangepicker');
      if (picker && picker.startDate && picker.endDate) {
        startVal = picker.startDate.format('YYYY-MM-DD');
        endVal = picker.endDate.format('YYYY-MM-DD');
      } else if ($input.val() && $input.val().indexOf(' - ') !== -1) {
        var parts = $input.val().split(' - ');
        if (parts[0] && parts[1]) {
          startVal = parts[0].trim();
          endVal = parts[1].trim();
        }
      }

      var statusVal = String($('#snStatusFilterBar .sn-status-pill.active').attr('data-status') || '').trim();
      var searchVal = String($('#snTableSearch').val() || '').trim();

      var params = new URLSearchParams();
      params.set('jenis', jenis);
      params.set('start', startVal);
      params.set('end', endVal);
      if (statusVal) params.set('status', statusVal);
      if (searchVal) params.set('q', searchVal);

      window.location.href = '/sinadi/export-perjalanan?' + params.toString();
    });
  });
</script>

<?= $this->endSection(); ?>
