<?php
$firstPeserta = is_array($getPeserta ?? null) && !empty($getPeserta) ? $getPeserta[0] : [];
$srikandiMeta = [
    'tgl_pergi'  => (string) ($firstPeserta['tgl_pergi'] ?? ($getData['tgl_pelaksanaan'] ?? '')),
    'tgl_pulang' => (string) ($firstPeserta['tgl_pulang'] ?? ($getData['tgl_pelaksanaan_akhir'] ?? '')),
    'kota'       => (string) ($firstPeserta['kota'] ?? ($getData['tempat_perjalanan'] ?? '')),
    'kota_asal'  => (string) ($firstPeserta['kota_asal'] ?? ''),
];
$pegawaiOptions = is_array($all_pegawai ?? null) ? $all_pegawai : [];
if (empty($pegawaiOptions)) {
    try {
        $pegawaiModel = new \App\Models\Master\DataPegawaiModel();
        $pegawaiOptions = $pegawaiModel->select('nama, nip')->orderBy('nama', 'ASC')->findAll();
    } catch (\Throwable $e) {
        $pegawaiOptions = [];
    }
}
?>
<!-- Modal SPPD Srikandi -->
<style>
  #modalSppdSrikandi {
    overflow-x: hidden;
    overflow-y: auto;
  }
  #modalSppdSrikandi .modal-dialog-scrollable {
    max-height: calc(100% - 1rem);
  }
  @media (min-width: 576px) {
    #modalSppdSrikandi .modal-dialog-scrollable {
      max-height: calc(100% - 3.5rem);
    }
  }
  #modalSppdSrikandi .modal-dialog-scrollable .modal-content {
    max-height: calc(100vh - 1rem);
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  @media (min-width: 576px) {
    #modalSppdSrikandi .modal-dialog-scrollable .modal-content {
      max-height: calc(100vh - 3.5rem);
    }
  }
  #modalSppdSrikandi .modal-dialog-scrollable .modal-header,
  #modalSppdSrikandi .modal-dialog-scrollable .modal-footer {
    flex-shrink: 0;
  }
  #modalSppdSrikandi .modal-dialog-scrollable .modal-body {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    flex: 1 1 auto;
    min-height: 0;
    max-height: calc(100vh - 150px);
  }
  @media (min-width: 576px) {
    #modalSppdSrikandi .modal-dialog-scrollable .modal-body {
      max-height: calc(100vh - 190px);
    }
  }
  #modalSppdSrikandi > .select2-container--open {
    z-index: 1060;
  }
  #modalSppdSrikandi .select2-container {
    width: 100% !important;
  }
  #modalSppdSrikandi .select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px 8px;
  }
  #modalSppdSrikandi .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
  }
</style>
<div class="modal fade" id="modalSppdSrikandi" role="dialog" aria-labelledby="modalSppdSrikandiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <form id="formSppdSrikandi" class="modal-content" onsubmit="event.preventDefault();">
      <div class="modal-header bg-success">
        <h5 class="modal-title" id="modalSppdSrikandiLabel">Cetak SPPD Srikandi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="id_perjalanan_srikandi" value="<?= (int) ($getData['id_perjalanan'] ?? 0); ?>">
      <div class="modal-body">
          <div class="alert alert-info mb-3" style="font-size: 13px;">
            <i class="fas fa-info-circle"></i> <strong>Catatan:</strong> Kosongkan form pejabat dan langsung klik <b>Cetak SPPD Srikandi</b> jika belum ada Pejabat Tujuan. Anda dapat menambahkan hingga <strong>3 Pejabat Tujuan</strong> (Baris <strong>II</strong>, <strong>III</strong>, dan <strong>IV</strong>).
          </div>

          <div class="card card-outline card-success mb-3">
            <div class="card-body py-2 px-3">
              <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                <div>
                  <div class="font-weight-bold mb-1" style="font-size: 13.5px;">Pengaturan Tanggal (Tiba / Berangkat Baris II–IV)</div>
                  <div class="d-flex flex-wrap align-items-center" style="gap: 16px;">
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="srikandiModeAuto" name="srikandi_mode_tanggal" value="auto" checked>
                      <label class="custom-control-label" for="srikandiModeAuto">Otomatis (berurut sesuai lamanya)</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input class="custom-control-input" type="radio" id="srikandiModeManual" name="srikandi_mode_tanggal" value="manual">
                      <label class="custom-control-label" for="srikandiModeManual">Manual (isi tanggal sendiri)</label>
                    </div>
                  </div>
                </div>
                <?php if ($srikandiMeta['tgl_pergi'] !== '' && $srikandiMeta['tgl_pulang'] !== '') : ?>
                  <span class="badge badge-light border px-2 py-1" style="font-size: 12px;">
                    Jadwal: <?= esc($srikandiMeta['tgl_pergi']); ?> s.d. <?= esc($srikandiMeta['tgl_pulang']); ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div id="srikandiPejabatContainer"></div>

          <div class="d-flex justify-content-between align-items-center mt-2">
            <button type="button" class="btn btn-outline-success btn-sm" id="btnTambahPejabatSrikandi">
              <i class="fas fa-plus mr-1"></i> Tambah Pejabat Tujuan (Maks. 3: Baris II–IV)
            </button>
            <small class="text-muted" id="srikandiRowCounterInfo">1 dari 3 baris aktif</small>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" onclick="cetakSppdSrikandi()">Cetak SPPD Srikandi</button>
      </div>
    </form>
  </div>
</div>

<select id="srikandiPegawaiOptionsSource" style="display: none;" aria-hidden="true">
  <option value="">-- Pilih Pegawai --</option>
  <option value="manual">&gt;&gt; Ketik Manual &lt;&lt;</option>
  <?php foreach ($pegawaiOptions as $peg) : ?>
    <?php
    $pegNama = trim((string) ($peg['nama'] ?? ''));
    $pegNip  = trim((string) ($peg['nip'] ?? ''));
    if ($pegNama === '' && $pegNip === '') continue;
    ?>
    <option value="<?= esc($pegNama . '|' . $pegNip, 'attr'); ?>">
      <?= esc($pegNama); ?><?= $pegNip !== '' ? (' - ' . esc($pegNip)) : ''; ?>
    </option>
  <?php endforeach; ?>
</select>

<template id="srikandiPegawaiOptionsTemplate">
  <option value="">-- Pilih Pegawai --</option>
  <option value="manual">&gt;&gt; Ketik Manual &lt;&lt;</option>
  <?php foreach ($pegawaiOptions as $peg) : ?>
    <?php
    $pegNama = trim((string) ($peg['nama'] ?? ''));
    $pegNip  = trim((string) ($peg['nip'] ?? ''));
    if ($pegNama === '' && $pegNip === '') continue;
    ?>
    <option value="<?= esc($pegNama . '|' . $pegNip, 'attr'); ?>">
      <?= esc($pegNama); ?><?= $pegNip !== '' ? (' - ' . esc($pegNip)) : ''; ?>
    </option>
  <?php endforeach; ?>
</template>

<script>
(function() {
  var MAX_PEJABAT = 3;
  var ROMAN_LABELS = ['II', 'III', 'IV'];
  var META = <?= json_encode($srikandiMeta, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
  var BULAN_INDO = [
    '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  function parseYmd(ymd) {
    if (!ymd || !/^\d{4}-\d{2}-\d{2}$/.test(ymd)) return null;
    var parts = ymd.split('-');
    return new Date(Date.UTC(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10)));
  }

  function formatYmd(dt) {
    if (!dt) return '';
    var y = dt.getUTCFullYear();
    var m = String(dt.getUTCMonth() + 1).padStart(2, '0');
    var d = String(dt.getUTCDate()).padStart(2, '0');
    return y + '-' + m + '-' + d;
  }

  function addDays(dt, days) {
    return new Date(dt.getTime() + days * 86400000);
  }

  function formatIndoDate(ymd) {
    if (!ymd || !/^\d{4}-\d{2}-\d{2}$/.test(ymd)) return '-';
    var parts = ymd.split('-');
    var m = parseInt(parts[1], 10);
    return parts[2] + ' ' + (BULAN_INDO[m] || parts[1]) + ' ' + parts[0];
  }

  function getKotaList() {
    if (!META.kota) return [];
    return String(META.kota).split(',').map(function(s) {
      return s.trim();
    }).filter(function(s) {
      return s !== '';
    });
  }

  function computeAutoDatesClient(stopCount) {
    var result = [];
    if (stopCount <= 0) return result;
    var start = parseYmd(META.tgl_pergi);
    var end = parseYmd(META.tgl_pulang);
    if (!start || !end) {
      for (var k = 0; k < stopCount; k++) result.push({ tiba: '', berangkat: '' });
      return result;
    }
    if (end.getTime() < start.getTime()) end = start;
    var totalDays = Math.floor((end.getTime() - start.getTime()) / 86400000) + 1;

    if (stopCount === 1) {
      result.push({ tiba: formatYmd(start), berangkat: formatYmd(end) });
      return result;
    }

    if (totalDays >= stopCount) {
      var base = Math.floor(totalDays / stopCount);
      var rem = totalDays % stopCount;
      var offset = 0;
      for (var i = 0; i < stopCount; i++) {
        var len = base + (i < rem ? 1 : 0);
        var tiba = formatYmd(addDays(start, offset));
        var berangkat = formatYmd(addDays(start, offset + len - 1));
        result.push({ tiba: tiba, berangkat: berangkat });
        offset += len;
      }
      return result;
    }

    for (var j = 0; j < stopCount; j++) {
      var dayOffset = Math.min(totalDays - 1, Math.floor((j * totalDays) / stopCount));
      var dStr = formatYmd(addDays(start, dayOffset));
      result.push({ tiba: dStr, berangkat: dStr });
    }
    return result;
  }

  function getDateMode() {
    return $('input[name="srikandi_mode_tanggal"]:checked').val() === 'manual' ? 'manual' : 'auto';
  }

  function populateSelectOptions($select) {
    var curVal = $select.val();
    $select.empty();
    var $source = $('#srikandiPegawaiOptionsSource');
    if ($source.length && $source.children('option').length > 0) {
      $select.append($source.children('option').clone());
    } else {
      var sourceHtml = $source.html() || $('#srikandiPegawaiOptionsTemplate').html() || '';
      if (sourceHtml) {
        $select.html(sourceHtml);
      }
    }
    if (curVal) {
      $select.val(curVal);
    }
  }

  function initSelect2ForElement($select) {
    if (!$select || !$select.length) return;

    var $formGroup = $select.closest('.form-group');
    var $existingContainers = $formGroup.find('.select2-container');

    // Jika sudah diinisialisasi dan tepat ada 1 container, jangan buat ulang
    if ($select.hasClass('select2-hidden-accessible') && $existingContainers.length === 1) {
      return;
    }

    // Bersihkan container lama jika ada duplikasi
    if ($existingContainers.length > 0) {
      try {
        $select.select2('destroy');
      } catch (e) {}
      $formGroup.find('.select2-container').remove();
      $select.removeClass('select2-hidden-accessible');
    }

    $select.select2({
      dropdownParent: $('#modalSppdSrikandi'),
      width: '100%'
    });
  }

  function createRowCard(idx) {
    var kotaList = getKotaList();
    var defaultKota = kotaList[idx] || (kotaList.length ? kotaList[kotaList.length - 1] : '');
    var kotaPlaceholder = defaultKota ? ('Otomatis: ' + defaultKota) : 'Contoh: Kab. Sukabumi';

    var $card = $(
      '<div class="card border mb-3 srikandi-pejabat-row" data-index="' + idx + '">' +
        '<div class="card-header py-2 px-3 bg-light d-flex justify-content-between align-items-center">' +
          '<span class="font-weight-bold srikandi-row-title">Baris ' + (ROMAN_LABELS[idx] || '') + ' &mdash; Pejabat Tujuan ' + (idx + 1) + '</span>' +
          '<button type="button" class="btn btn-xs btn-outline-danger srikandi-btn-remove" title="Hapus Baris"><i class="fas fa-trash-alt mr-1"></i>Hapus</button>' +
        '</div>' +
        '<div class="card-body py-2 px-3">' +
          '<div class="row">' +
            '<div class="col-md-6 form-group mb-2">' +
              '<label class="mb-1 small font-weight-bold">Lokasi / Kota Tujuan (Opsional)</label>' +
              '<input type="text" class="form-control form-control-sm srikandi-lokasi" placeholder="' + kotaPlaceholder.replace(/"/g, '&quot;') + '">' +
            '</div>' +
            '<div class="col-md-6 form-group mb-2">' +
              '<label class="mb-1 small font-weight-bold">Jabatan Tujuan</label>' +
              '<input type="text" class="form-control form-control-sm srikandi-jabatan" placeholder="Contoh: Kepala Dinas Kesehatan...">' +
            '</div>' +
          '</div>' +
          '<div class="srikandi-auto-date-preview alert alert-light border py-1 px-2 mb-2 small text-muted"></div>' +
          '<div class="srikandi-manual-date-wrapper row" style="display: none;">' +
            '<div class="col-md-6 form-group mb-2">' +
              '<label class="mb-1 small font-weight-bold">Pada Tanggal (Tiba di)</label>' +
              '<input type="date" class="form-control form-control-sm srikandi-tgl-tiba">' +
            '</div>' +
            '<div class="col-md-6 form-group mb-2">' +
              '<label class="mb-1 small font-weight-bold">Pada Tanggal (Berangkat dari)</label>' +
              '<input type="date" class="form-control form-control-sm srikandi-tgl-berangkat">' +
            '</div>' +
          '</div>' +
          '<div class="form-group mb-2">' +
            '<label class="mb-1 small font-weight-bold">Pilih Pegawai dari Database (Nama &amp; NIP)</label>' +
            '<select class="form-control srikandi-pegawai-select" style="width: 100%;"></select>' +
          '</div>' +
          '<div class="srikandi-manual-pegawai-wrapper row" style="display: none;">' +
            '<div class="col-md-6 form-group mb-2">' +
              '<label class="mb-1 small font-weight-bold">Nama Pegawai (Manual)</label>' +
              '<input type="text" class="form-control form-control-sm srikandi-nama-manual" placeholder="Contoh: Dr. Budi Santoso">' +
            '</div>' +
            '<div class="col-md-6 form-group mb-2">' +
              '<label class="mb-1 small font-weight-bold">NIP Pegawai (Manual)</label>' +
              '<input type="text" class="form-control form-control-sm srikandi-nip-manual" placeholder="Contoh: 198001012005011001">' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</div>'
    );

    var $select = $card.find('.srikandi-pegawai-select');
    populateSelectOptions($select);

    $select.on('change', function() {
      var isManualPeg = $(this).val() === 'manual';
      $card.find('.srikandi-manual-pegawai-wrapper').toggle(isManualPeg);
    });

    $card.find('.srikandi-btn-remove').on('click', function() {
      if ($select.data('select2')) {
        $select.select2('destroy');
      }
      $card.remove();
      refreshRowsUI(false);
      $('#modalSppdSrikandi').modal('handleUpdate');
    });

    $('#srikandiPejabatContainer').append($card);
    initSelect2ForElement($select);
  }

  function refreshRowsUI(fillEmptyManualDates) {
    var $rows = $('#srikandiPejabatContainer .srikandi-pejabat-row');
    var count = $rows.length;
    var mode = getDateMode();
    var kotaList = getKotaList();
    var effectiveStops = Math.max(count, Math.min(3, kotaList.length));
    var autoDates = computeAutoDatesClient(effectiveStops);

    $rows.each(function(idx) {
      var $card = $(this);
      $card.attr('data-index', idx);
      $card.find('.srikandi-row-title').html('Baris ' + (ROMAN_LABELS[idx] || '') + ' &mdash; Pejabat Tujuan ' + (idx + 1));
      $card.find('.srikandi-btn-remove').toggle(idx > 0);

      var defKota = kotaList[idx] || (kotaList.length ? kotaList[kotaList.length - 1] : '');
      $card.find('.srikandi-lokasi').attr('placeholder', defKota ? ('Otomatis: ' + defKota) : 'Contoh: Kab. Sukabumi');

      var dObj = autoDates[idx] || { tiba: '', berangkat: '' };
      $card.find('.srikandi-auto-date-preview').html(
        '<i class="far fa-calendar-alt mr-1"></i> Tanggal otomatis: <strong>Tiba:</strong> ' +
        formatIndoDate(dObj.tiba) + ' &nbsp;|&nbsp; <strong>Berangkat:</strong> ' + formatIndoDate(dObj.berangkat)
      );

      if (mode === 'manual') {
        $card.find('.srikandi-auto-date-preview').hide();
        $card.find('.srikandi-manual-date-wrapper').show();
        var $tibaInput = $card.find('.srikandi-tgl-tiba');
        var $berangkatInput = $card.find('.srikandi-tgl-berangkat');
        if (fillEmptyManualDates || (!$tibaInput.val() && !$berangkatInput.val())) {
          if (!$tibaInput.val() && dObj.tiba) $tibaInput.val(dObj.tiba);
          if (!$berangkatInput.val() && dObj.berangkat) $berangkatInput.val(dObj.berangkat);
        }
      } else {
        $card.find('.srikandi-manual-date-wrapper').hide();
        $card.find('.srikandi-auto-date-preview').show();
      }
    });

    $('#srikandiRowCounterInfo').text(count + ' dari ' + MAX_PEJABAT + ' baris aktif (Baris II–IV)');
    $('#btnTambahPejabatSrikandi').prop('disabled', count >= MAX_PEJABAT).toggle(count < MAX_PEJABAT);
  }

  $(document).ready(function() {
    var $modal = $('#modalSppdSrikandi');
    if ($modal.length && !$modal.parent().is('body')) {
      $('body > #modalSppdSrikandi').not($modal).remove();
      $modal.appendTo('body');
    }

    if ($('#srikandiPejabatContainer .srikandi-pejabat-row').length === 0) {
      createRowCard(0);
    }
    refreshRowsUI(false);

    $modal.find('.modal-body').on('scroll', function() {
      if ($modal.children('.select2-container--open').length) {
        $modal.find('.srikandi-pegawai-select.select2-hidden-accessible').select2('close');
      }
    });

    $modal.on('shown.bs.modal', function() {
      var $rows = $('#srikandiPejabatContainer .srikandi-pejabat-row');
      if ($rows.length === 0) {
        createRowCard(0);
        refreshRowsUI(false);
      } else {
        $rows.each(function() {
          var $card = $(this);
          var $sel = $card.find('.srikandi-pegawai-select');

          if ($sel.children('option').length === 0) {
            populateSelectOptions($sel);
          }
          initSelect2ForElement($sel);
        });
      }
      $modal.modal('handleUpdate');
    });

    $('#btnTambahPejabatSrikandi').on('click', function() {
      var currentCount = $('#srikandiPejabatContainer .srikandi-pejabat-row').length;
      if (currentCount < MAX_PEJABAT) {
        createRowCard(currentCount);
        refreshRowsUI(getDateMode() === 'manual');
        var $modalBody = $modal.find('.modal-body');
        if ($modalBody.length) {
          $modalBody.stop(true).animate({ scrollTop: $modalBody[0].scrollHeight }, 200);
        }
        $modal.modal('handleUpdate');
      }
    });

    $('input[name="srikandi_mode_tanggal"]').on('change', function() {
      refreshRowsUI(getDateMode() === 'manual');
      $modal.modal('handleUpdate');
    });
  });

  window.cetakSppdSrikandi = function() {
    var id = $('#id_perjalanan_srikandi').val();
    var mode = getDateMode();
    var $rows = $('#srikandiPejabatContainer .srikandi-pejabat-row');
    var params = new URLSearchParams();

    params.append('mode_tanggal', mode);
    params.append('jumlah_baris', String($rows.length));

    $rows.each(function(idx) {
      var $card = $(this);
      var lokasi = String($card.find('.srikandi-lokasi').val() || '').trim();
      var jabatan = String($card.find('.srikandi-jabatan').val() || '').trim();
      var pegawai = String($card.find('.srikandi-pegawai-select').val() || '');

      params.append('lokasi_tujuan[' + idx + ']', lokasi);
      params.append('jabatan_tujuan[' + idx + ']', jabatan);
      params.append('pegawai_tujuan[' + idx + ']', pegawai);

      if (pegawai === 'manual') {
        params.append('nama_tujuan_manual[' + idx + ']', String($card.find('.srikandi-nama-manual').val() || '').trim());
        params.append('nip_tujuan_manual[' + idx + ']', String($card.find('.srikandi-nip-manual').val() || '').trim());
      }

      if (mode === 'manual') {
        params.append('tgl_tiba[' + idx + ']', String($card.find('.srikandi-tgl-tiba').val() || ''));
        params.append('tgl_berangkat[' + idx + ']', String($card.find('.srikandi-tgl-berangkat').val() || ''));
      }
    });

    var url = '<?= base_url('sinadi/sppd') ?>/' + encodeURIComponent(id) + '/borang?' + params.toString();
    window.open(url, '_blank');
    $('#modalSppdSrikandi').modal('hide');
  };
})();
</script>
