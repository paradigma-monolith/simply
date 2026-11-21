<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$myAgendaList = $myAgenda ?? [];
$totalMyAgenda = count($myAgendaList);
$totalMonthlyAgenda = 0;
$totalUpcomingAgenda = 0;
$totalNotifActive = 0;
$todayStr = date('Y-m-d');

foreach ($myAgendaList as $agItem) {
  $isMonthlyItem = !empty($agItem['ulangi_bulanan']) && (int) $agItem['ulangi_bulanan'] === 1;
  if ($isMonthlyItem) {
    $totalMonthlyAgenda++;
  }
  $endDateStr = !empty($agItem['waktu_pelaksana_akhir']) ? substr((string) $agItem['waktu_pelaksana_akhir'], 0, 10) : '';
  if ($isMonthlyItem || ($endDateStr !== '' && $endDateStr >= $todayStr)) {
    $totalUpcomingAgenda++;
  }
  if (!empty($agItem['tgl_notif_akhir']) && ($isMonthlyItem || (string) $agItem['tgl_notif_akhir'] >= $todayStr)) {
    $totalNotifActive++;
  }
}
?>

<style>
  .ag-page {
    --ag-primary: #0d9488;
    --ag-primary-dark: #0f766e;
    --ag-primary-soft: #f0fdfa;
    --ag-indigo: #4f46e5;
    --ag-indigo-soft: #eef2ff;
    --ag-border: rgba(15, 23, 42, 0.08);
    --ag-text: #0f172a;
    --ag-muted: #64748b;
    --ag-radius: 14px;
    --ag-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    padding-bottom: 24px;
  }

  /* Hero Header */
  .ag-hero {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    border-radius: var(--ag-radius);
    padding: 18px 22px;
    color: #ffffff;
    box-shadow: 0 8px 22px rgba(4, 120, 121, 0.18);
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
  }
  .ag-hero-title {
    font-size: 1.2rem;
    font-weight: 800;
    margin: 0 0 3px;
    letter-spacing: -0.2px;
  }
  .ag-hero-sub {
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.86);
    margin: 0;
  }
  .ag-kpi-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }
  .ag-kpi-pill {
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.22);
    border-radius: 11px;
    padding: 7px 13px;
    min-width: 115px;
    backdrop-filter: blur(6px);
  }
  .ag-kpi-pill .ag-kpi-val {
    font-size: 1.05rem;
    font-weight: 800;
    line-height: 1.1;
    color: #ffffff;
  }
  .ag-kpi-pill .ag-kpi-lbl {
    font-size: 0.68rem;
    color: rgba(255, 255, 255, 0.82);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-weight: 600;
  }

  /* Modern Card */
  .ag-card {
    background: #ffffff;
    border: 1px solid var(--ag-border);
    border-radius: var(--ag-radius);
    box-shadow: var(--ag-shadow);
    margin-bottom: 16px;
    overflow: hidden;
  }
  .ag-card-header {
    padding: 13px 18px;
    border-bottom: 1px solid var(--ag-border);
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .ag-card-title {
    font-size: 0.96rem;
    font-weight: 700;
    color: var(--ag-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .ag-card-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: var(--ag-primary-soft);
    color: var(--ag-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.88rem;
  }
  .ag-card-body {
    padding: 16px 18px;
  }

  /* Form Controls */
  .ag-form-label {
    font-size: 0.78rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .ag-schedule-panel {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
  }
  .ag-repeat-box {
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 11px;
    padding: 11px 13px;
    margin-bottom: 12px;
    transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
  }
  .ag-repeat-box.is-active {
    background: var(--ag-indigo-soft);
    border-color: #818cf8;
    box-shadow: 0 2px 10px rgba(79, 70, 229, 0.08);
  }
  .ag-repeat-toggle {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    cursor: pointer;
    margin: 0;
    user-select: none;
  }
  .ag-repeat-toggle input[type="checkbox"] {
    width: 17px;
    height: 17px;
    margin-top: 2px;
    accent-color: var(--ag-indigo);
    cursor: pointer;
    flex-shrink: 0;
  }
  .ag-repeat-title {
    font-size: 0.82rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.3;
  }
  .ag-repeat-preview {
    font-size: 0.73rem;
    color: #4f46e5;
    font-weight: 600;
    margin-top: 3px;
  }
  .ag-repeat-options {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed rgba(79, 70, 229, 0.25);
  }

  /* Color Preset Buttons */
  .color-preset-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }
  .color-btn,
  .color-btn-edit {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: 2px solid transparent;
    padding: 0;
    transition: transform 0.16s ease, box-shadow 0.16s ease;
    cursor: pointer;
  }
  .color-btn.active,
  .color-btn-edit.active {
    border: 2px solid #ffffff !important;
    box-shadow: 0 0 0 2.5px #0f172a !important;
    transform: scale(1.12);
  }
  .color-btn:hover,
  .color-btn-edit:hover {
    transform: scale(1.08);
  }

  /* Quick Action Mini Buttons */
  .ag-mini-btn {
    font-size: 0.71rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .ag-mini-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
  }

  @media (max-width: 767.98px) {
    .ag-hero {
      padding: 14px 16px;
    }
    .ag-hero-title {
      font-size: 1.02rem;
    }
    .ag-kpi-grid {
      width: 100%;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
    }
    .ag-card-header,
    .ag-card-body {
      padding: 12px 14px;
    }
    .ag-schedule-panel {
      padding: 12px;
      margin-top: 10px;
    }
  }
</style>

<section class="content ag-page">
  <div class="container-fluid">

    <!-- Hero Header & Stats -->
    <div class="ag-hero">
      <div>
        <h4 class="ag-hero-title"><i class="fas fa-calendar-check mr-2"></i>Kelola Agenda &amp; Jadwal Kegiatan</h4>
        <p class="ag-hero-sub">Buat agenda kegiatan sekali jalan maupun berulang otomatis di tanggal yang sama setiap bulannya</p>
      </div>
      <div class="ag-kpi-grid">
        <div class="ag-kpi-pill">
          <div class="ag-kpi-val" id="agKpiTotal"><?= $totalMyAgenda; ?></div>
          <div class="ag-kpi-lbl">Agenda Saya</div>
        </div>
        <div class="ag-kpi-pill">
          <div class="ag-kpi-val" id="agKpiMonthly"><i class="fas fa-redo-alt mr-1" style="font-size: 0.8rem;"></i><?= $totalMonthlyAgenda; ?></div>
          <div class="ag-kpi-lbl">Rutin Bulanan</div>
        </div>
        <div class="ag-kpi-pill">
          <div class="ag-kpi-val" id="agKpiUpcoming"><?= $totalUpcomingAgenda; ?></div>
          <div class="ag-kpi-lbl">Jadwal Aktif</div>
        </div>
        <div class="ag-kpi-pill">
          <div class="ag-kpi-val" id="agKpiNotif"><?= $totalNotifActive; ?></div>
          <div class="ag-kpi-lbl">Notif Beranda</div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Kolom Kiri: Form Buat Agenda Baru -->
      <div class="col-lg-7">
        <div class="ag-card">
          <div class="ag-card-header">
            <h5 class="ag-card-title">
              <span class="ag-card-icon"><i class="fas fa-calendar-plus"></i></span>
              Buat Agenda Baru
            </h5>
            <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.72rem;">
              <i class="fas fa-sync-alt text-info mr-1"></i>Mendukung Pengulangan Bulanan
            </span>
          </div>
          <div class="ag-card-body">
            <form id="formUpdateAgenda" action="/home/ajax/agenda" method="post">
              <div class="row">
                <!-- Detail Topik, Deskripsi & Penerima -->
                <div class="col-md-7">
                  <div class="form-group mb-3">
                    <label class="ag-form-label" for="agCreateTopik">
                      <span>Topik Agenda <span class="text-danger">*</span></span>
                    </label>
                    <input type="text"
                           name="topik"
                           id="agCreateTopik"
                           class="form-control"
                           required
                           maxlength="128"
                           placeholder="Contoh: Rapat Evaluasi Bulanan / Apel Pagi"
                           style="border-radius: 9px;">
                  </div>

                  <div class="form-group mb-3">
                    <label class="ag-form-label">
                      <span>Deskripsi / Detail Kegiatan</span>
                    </label>
                    <textarea name="deskripsi" id="agCreateDeskripsi" class="summernote_no_media"></textarea>
                  </div>

                  <div class="form-group mb-2">
                    <div class="ag-form-label">
                      <span>Sebarkan Kepada <span class="badge badge-info ml-1" id="agCreateRecipientCount">1 dipilih</span></span>
                      <span class="d-inline-flex" style="gap: 4px;">
                        <button type="button" class="ag-mini-btn" id="allUserAgenda" title="Pilih seluruh pegawai">
                          <i class="fas fa-users mr-1"></i>Pilih Semua
                        </button>
                        <button type="button" class="ag-mini-btn" id="removeUserAgenda" title="Hanya untuk saya sendiri">
                          <i class="fas fa-undo mr-1"></i>Hanya Saya
                        </button>
                      </span>
                    </div>
                    <select multiple="multiple" name="peruntukan[]" id="agCreatePeruntukan" class="select2" style="width: 100%;">
                      <?php foreach ($user as $data) : ?>
                        <?php $isSelf = ($data['username'] === session()->get('username')); ?>
                        <option value="<?= (int) $data['id_user']; ?>" <?= $isSelf ? 'selected disabled="disabled" data-self="1"' : ''; ?>>
                          <?= esc($data['nama']); ?><?= $isSelf ? ' (Anda)' : ''; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <!-- Pengaturan Waktu, Pengulangan Bulanan, Notifikasi & Warna -->
                <div class="col-md-5">
                  <div class="ag-schedule-panel">
                    <!-- Waktu Pelaksanaan -->
                    <div class="form-group mb-3">
                      <label class="ag-form-label" for="agCreateWaktuPelaksanaan">
                        <span><i class="far fa-clock text-info mr-1"></i>Waktu Pelaksanaan</span>
                      </label>
                      <input type="text"
                             name="waktu_pelaksanaan"
                             id="agCreateWaktuPelaksanaan"
                             class="form-control form-control-sm datetime"
                             style="border-radius: 8px; font-weight: 600;" />
                    </div>

                    <!-- Fitur Pengulangan di Tanggal yang Sama Setiap Bulannya -->
                    <div class="ag-repeat-box" id="agCreateRepeatBox">
                      <label class="ag-repeat-toggle" for="agCreateUlangiBulanan">
                        <input type="checkbox" name="ulangi_bulanan" id="agCreateUlangiBulanan" value="1">
                        <div>
                          <div class="ag-repeat-title">
                            <i class="fas fa-redo-alt text-primary mr-1"></i>Ulangi di tanggal yang sama setiap bulan
                          </div>
                          <div class="ag-repeat-preview" id="agCreateRepeatPreview">
                            Aktifkan agar jadwal berulang otomatis tiap bulan
                          </div>
                        </div>
                      </label>

                      <div class="ag-repeat-options d-none" id="agCreateRepeatOptions">
                        <label class="small font-weight-bold text-dark mb-1 d-block">Batas Pengulangan Bulanan:</label>
                        <select name="mode_batas_bulanan" id="agCreateModeBatas" class="form-control form-control-sm mb-2" style="border-radius: 8px;">
                          <option value="forever" selected>Setiap Bulan (Tanpa Batas Waktu)</option>
                          <option value="3_months">Selama 3 Bulan ke Depan</option>
                          <option value="6_months">Selama 6 Bulan ke Depan</option>
                          <option value="12_months">Selama 12 Bulan (1 Tahun)</option>
                          <option value="end_of_year">Sampai Akhir Tahun Ini</option>
                          <option value="custom">Pilih Tanggal Batas Akhir...</option>
                        </select>
                        <div id="agCreateCustomBatasWrap" class="d-none">
                          <input type="date"
                                 name="batas_ulangi_bulanan"
                                 id="agCreateBatasDate"
                                 class="form-control form-control-sm"
                                 min="<?= date('Y-m-d'); ?>"
                                 style="border-radius: 8px;">
                          <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Agenda berhenti berulang setelah melewati tanggal ini.</small>
                        </div>
                      </div>
                    </div>

                    <!-- Tanggal Notifikasi Beranda -->
                    <div class="form-group mb-3">
                      <label class="ag-form-label" for="agCreateTglNotif">
                        <span><i class="far fa-bell text-warning mr-1"></i>Tanggal Notifikasi Pop-up</span>
                      </label>
                      <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                          <span class="input-group-text" title="Aktifkan/nonaktifkan notifikasi pop-up di beranda">
                            <input type="checkbox" name="notif" id="myCheckbox" checked />
                          </span>
                        </div>
                        <input type="text"
                               name="tgl_notif"
                               id="agCreateTglNotif"
                               class="form-control range-date"
                               style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;" />
                      </div>
                      <small class="text-muted d-block mt-1" style="font-size: 0.71rem; line-height: 1.35;">
                        Jika pengulangan bulanan aktif, notifikasi juga otomatis tampil di tanggal yang sama setiap bulan.
                      </small>
                    </div>

                    <!-- Warna Agenda -->
                    <div class="form-group mb-0">
                      <label class="ag-form-label" for="colorPicker">
                        <span><i class="fas fa-palette text-teal mr-1"></i>Warna Label Kalender</span>
                      </label>
                      <div class="input-group input-group-sm mb-2">
                        <input type="color"
                               name="warna_agenda"
                               id="colorPicker"
                               class="form-control"
                               value="#0d9488"
                               style="height: 32px; padding: 2px 4px; border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                        <div class="input-group-append">
                          <span class="input-group-text" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                            <i class="fas fa-eye-dropper"></i>
                          </span>
                        </div>
                      </div>
                      <div class="color-preset-buttons mb-1">
                        <button type="button" class="btn color-btn active" data-color="#0d9488" style="background-color: #0d9488;" title="Teal"></button>
                        <button type="button" class="btn color-btn" data-color="#0073b7" style="background-color: #0073b7;" title="Biru"></button>
                        <button type="button" class="btn color-btn" data-color="#4f46e5" style="background-color: #4f46e5;" title="Indigo"></button>
                        <button type="button" class="btn color-btn" data-color="#dc3545" style="background-color: #dc3545;" title="Merah"></button>
                        <button type="button" class="btn color-btn" data-color="#28a745" style="background-color: #28a745;" title="Hijau"></button>
                        <button type="button" class="btn color-btn" data-color="#fd7e14" style="background-color: #fd7e14;" title="Oranye"></button>
                        <button type="button" class="btn color-btn" data-color="#d97706" style="background-color: #d97706;" title="Amber"></button>
                        <button type="button" class="btn color-btn" data-color="#6f42c1" style="background-color: #6f42c1;" title="Ungu"></button>
                        <button type="button" class="btn color-btn" data-color="#e83e8c" style="background-color: #e83e8c;" title="Pink"></button>
                        <button type="button" class="btn color-btn" data-color="#334155" style="background-color: #334155;" title="Slate"></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-end pt-3 mt-2 border-top" style="gap: 8px;">
                <button type="submit" class="btn btn-success btn-sm font-weight-bold px-4 py-2" style="border-radius: 9px; background: #0d9488; border-color: #0d9488;">
                  <i class="fas fa-save mr-1"></i> Simpan Agenda
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Daftar Agenda Saya (Pre-rendered langsung saat halaman dimuat) -->
      <div class="col-lg-5">
        <div class="ag-card">
          <div class="ag-card-header">
            <h5 class="ag-card-title">
              <span class="ag-card-icon"><i class="fas fa-list-ul"></i></span>
              Daftar Agenda Saya
            </h5>
            <button type="button" class="ag-mini-btn" onclick="AgendaSaya()" title="Muat ulang daftar agenda">
              <i class="fas fa-sync-alt mr-1"></i>Segarkan
            </button>
          </div>
          <div class="ag-card-body p-2 agenda_saya">
            <?= view('home/agenda_saya', ['getData' => $myAgendaList]); ?>
          </div>
        </div>
      </div>

      <!-- Kalender Agenda Full Width -->
      <div class="col-12 getCallendarAgenda">
        <div class="ag-card p-4 text-center text-muted">
          <i class="fas fa-spinner fa-spin mr-2"></i>Memuat kalender agenda...
        </div>
      </div>
    </div>

  </div>
</section>

<script>
  /**
   * Hitung teks pratinjau tanggal pengulangan bulanan dari input waktu pelaksanaan
   */
  window.updateAgendaMonthlyPreview = function(waktuVal, isChecked, $previewEl, $boxEl, $optionsEl) {
    if ($boxEl && $boxEl.length) {
      $boxEl.toggleClass('is-active', !!isChecked);
    }
    if ($optionsEl && $optionsEl.length) {
      $optionsEl.toggleClass('d-none', !isChecked);
    }
    if (!$previewEl || !$previewEl.length) return;

    if (!isChecked) {
      $previewEl.text('Aktifkan agar jadwal berulang otomatis tiap bulan');
      return;
    }

    var raw = String(waktuVal || '').trim();
    var parts = raw.split(' - ');
    var startPart = (parts[0] || '').trim().substring(0, 10);
    var endPart = (parts[1] || startPart).trim().substring(0, 10);

    var startDay = parseInt(startPart.split('-')[2], 10);
    var endDay = parseInt(endPart.split('-')[2], 10);

    if (isNaN(startDay) || startDay < 1 || startDay > 31) {
      $previewEl.html('<i class="fas fa-check-circle mr-1"></i>Berulang otomatis di tanggal yang sama setiap bulan');
      return;
    }

    if (!isNaN(endDay) && endDay !== startDay && startPart !== endPart) {
      $previewEl.html('<i class="fas fa-calendar-day mr-1"></i>Berulang otomatis setiap <strong>Tanggal ' + startDay + ' s/d ' + endDay + '</strong> tiap bulan');
    } else {
      $previewEl.html('<i class="fas fa-calendar-day mr-1"></i>Berulang otomatis setiap <strong>Tanggal ' + startDay + '</strong> tiap bulan');
    }
  };

  $(document).ready(function() {
    // Pastikan DateRangePicker pada form utama memiliki TimePicker 24 jam yang nyaman
    var $createWaktu = $('#agCreateWaktuPelaksanaan');
    var $createNotif = $('#agCreateTglNotif');

    if ($createWaktu.length && typeof $.fn.daterangepicker !== 'undefined') {
      $createWaktu.daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(2, 'hours'),
        locale: {
          format: 'YYYY-MM-DD HH:mm:ss',
          applyLabel: 'Terapkan',
          cancelLabel: 'Batal'
        }
      });

      $createWaktu.on('apply.daterangepicker change', function(ev, picker) {
        if (picker && $createNotif.length && $createNotif.data('daterangepicker')) {
          var nStart = picker.startDate.clone().startOf('day');
          var nEnd = picker.endDate.clone().startOf('day');
          $createNotif.data('daterangepicker').setStartDate(nStart);
          $createNotif.data('daterangepicker').setEndDate(nEnd);
          $createNotif.val(nStart.format('YYYY-MM-DD') + ' - ' + nEnd.format('YYYY-MM-DD'));
        }
        window.updateAgendaMonthlyPreview(
          $createWaktu.val(),
          $('#agCreateUlangiBulanan').is(':checked'),
          $('#agCreateRepeatPreview'),
          $('#agCreateRepeatBox'),
          $('#agCreateRepeatOptions')
        );
      });
    }

    // Toggle pengulangan bulanan pada form utama
    $('#agCreateUlangiBulanan').on('change', function() {
      window.updateAgendaMonthlyPreview(
        $createWaktu.val(),
        $(this).is(':checked'),
        $('#agCreateRepeatPreview'),
        $('#agCreateRepeatBox'),
        $('#agCreateRepeatOptions')
      );
    });

    $('#agCreateModeBatas').on('change', function() {
      var isCustom = $(this).val() === 'custom';
      $('#agCreateCustomBatasWrap').toggleClass('d-none', !isCustom);
      if (!isCustom) {
        $('#agCreateBatasDate').val('');
      }
    });

    // Toggle checkbox notifikasi pada form utama
    $('#myCheckbox').on('change', function() {
      $('#agCreateTglNotif').prop('disabled', !$(this).is(':checked'));
    });

    // Update counter penerima Select2
    function updateCreateRecipientCount() {
      var $sel = $('#agCreatePeruntukan');
      var count = $sel.find('option:selected').length || 1;
      $('#agCreateRecipientCount').text(count + ' dipilih');
    }

    $('#agCreatePeruntukan').on('change', updateCreateRecipientCount);

    // Pilih Semua & Hanya Saya tanpa destroy Select2 (10x lebih cepat)
    $('#allUserAgenda').on('click', function() {
      var $select = $('#agCreatePeruntukan');
      $select.find('option').prop('selected', true);
      $select.trigger('change');
    });

    $('#removeUserAgenda').on('click', function() {
      var $select = $('#agCreatePeruntukan');
      $select.find('option').prop('selected', false);
      $select.find('option[data-self="1"], option:disabled').prop('selected', true);
      $select.trigger('change');
    });

    // Pilih warna preset pada form utama
    $('.color-btn').on('click', function() {
      var selectedColor = $(this).data('color');
      $('#colorPicker').val(selectedColor);
      $('.color-btn').removeClass('active');
      $(this).addClass('active');
    });

    $('#colorPicker').on('input change', function() {
      var val = ($(this).val() || '').toLowerCase();
      $('.color-btn').removeClass('active').each(function() {
        if (String($(this).data('color')).toLowerCase() === val) {
          $(this).addClass('active');
        }
      });
    });

    // Muat kalender agenda (Daftar Agenda Saya sudah di-prerender dari server)
    getCallendarAgenda();
  });

  // Submit handler untuk Form Buat Agenda & Form Edit Agenda
  $(document).on('submit', '#formUpdateAgenda, #formUpdateAgenda-Edit', function(e) {
    e.preventDefault();
    var $form = $(this);
    var isEditModal = $form.attr('id') === 'formUpdateAgenda-Edit';

    var formData = $form.serialize();
    var $button = $form.find('button[type="submit"]');
    if (!$button.length) {
      $button = $form.find('button').last();
    }
    var originalBtnHtml = $button.html();

    $.ajax({
      url: $form.attr('action'),
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $button.prop('disabled', true);
        $button.html('<i class="fas fa-sync-alt fa-spin mr-1"></i> Menyimpan...');
      },
      success: function(response) {
        if (response && response.dataAgenda) {
          if (isEditModal) {
            $('#modal-lg').modal('hide');
          } else {
            $('#agCreateTopik').val('');
            if ($('#agCreateDeskripsi').next('.note-editor').length) {
              $('#agCreateDeskripsi').summernote('reset');
            }
          }
          AgendaSaya();
          getCallendarAgenda();
          Toast.fire({
            icon: 'success',
            title: isEditModal ? 'Agenda berhasil diperbarui!' : 'Agenda baru berhasil disimpan!'
          });
        } else if (response && response.pesanAgenda) {
          Toast.fire({
            icon: 'warning',
            title: 'Data agenda dengan topik dan waktu tersebut sudah ada.'
          });
        } else {
          Toast.fire({
            icon: 'error',
            title: 'Data agenda gagal disimpan.'
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal Menyimpan Agenda',
          text: xhr.status + ' ' + thrownError
        });
      },
      complete: function() {
        $button.prop('disabled', false);
        $button.html(originalBtnHtml);
      }
    });
  });

  function getCallendarAgenda() {
    $.ajax({
      url: '/home/getCallendarAgenda',
      method: 'get',
      dataType: 'json',
      success: function(response) {
        if (response && response.data) {
          $('.getCallendarAgenda').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $('.getCallendarAgenda').html(
          '<div class="alert alert-danger">Gagal memuat kalender agenda: ' + thrownError + '</div>'
        );
      }
    });
  }

  function AgendaSaya() {
    $.ajax({
      url: '/home/agenda/ajax/agenda_saya',
      method: 'get',
      dataType: 'json',
      success: function(response) {
        if (response && response.data) {
          $('.agenda_saya').html(response.data);
        }
      }
    });
  }
</script>

<?= $this->endSection(); ?>
