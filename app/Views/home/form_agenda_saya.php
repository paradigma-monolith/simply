<?php
$peruntukan = @unserialize((string) ($getData['peruntukan'] ?? ''), ['allowed_classes' => false]);
if (!is_array($peruntukan)) {
  $peruntukan = [];
}
$isMonthlyEdit = !empty($getData['ulangi_bulanan']) && (int) $getData['ulangi_bulanan'] === 1;
$batasRawEdit = trim((string) ($getData['batas_ulangi_bulanan'] ?? ''));
if ($batasRawEdit === '0000-00-00') {
  $batasRawEdit = '';
}
$currentColorEdit = !empty($getData['warna_agenda']) ? trim((string) $getData['warna_agenda']) : '#0d9488';
?>

<form id="formUpdateAgenda-Edit" action="/home/ajax/agenda" method="post">
  <input type="hidden" name="id_agenda_user" value="<?= (int) ($getData['id_agenda_user'] ?? 0); ?>">
  <div class="row">
    <div class="col-lg-7">
      <div class="form-group mb-3">
        <label class="ag-form-label" for="agEditTopik">
          <span>Topik Agenda <span class="text-danger">*</span></span>
        </label>
        <input type="text"
               name="topik"
               id="agEditTopik"
               class="form-control"
               required
               maxlength="128"
               value="<?= esc($getData['topik'] ?? '', 'attr'); ?>"
               style="border-radius: 9px;">
      </div>

      <div class="form-group mb-3">
        <label class="ag-form-label">Deskripsi / Detail Kegiatan</label>
        <textarea name="deskripsi" id="agEditSummernote" class="summernote-edit"><?= $getData['deskripsi'] ?? ''; ?></textarea>
      </div>

      <div class="form-group mb-2">
        <div class="ag-form-label">
          <span>Sebarkan Kepada</span>
          <span class="d-inline-flex" style="gap: 4px;">
            <button type="button" class="ag-mini-btn" id="btnEditAllUserAgenda">
              <i class="fas fa-users mr-1"></i>Pilih Semua
            </button>
            <button type="button" class="ag-mini-btn" id="btnEditRemoveUserAgenda">
              <i class="fas fa-undo mr-1"></i>Hanya Saya
            </button>
          </span>
        </div>
        <select multiple="multiple" name="peruntukan[]" id="agEditPeruntukan" class="select2-edit" style="width: 100%;">
          <?php foreach ($user as $data) : ?>
            <?php
            $isSelf = ($data['username'] === session()->get('username'));
            $isSelected = $isSelf || in_array($data['id_user'], $peruntukan);
            ?>
            <option value="<?= (int) $data['id_user']; ?>"
                    <?= $isSelected ? 'selected' : ''; ?>
                    <?= $isSelf ? 'disabled="disabled" data-self="1"' : ''; ?>>
              <?= esc($data['nama']); ?><?= $isSelf ? ' (Anda)' : ''; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="ag-schedule-panel">
        <!-- Waktu Pelaksanaan -->
        <div class="form-group mb-3">
          <label class="ag-form-label" for="agEditWaktuPelaksanaan">
            <span><i class="far fa-clock text-info mr-1"></i>Waktu Pelaksanaan</span>
          </label>
          <input type="text"
                 name="waktu_pelaksanaan"
                 id="agEditWaktuPelaksanaan"
                 class="form-control form-control-sm"
                 style="border-radius: 8px; font-weight: 600;" />
        </div>

        <!-- Pengulangan di Tanggal yang Sama Setiap Bulan -->
        <div class="ag-repeat-box <?= $isMonthlyEdit ? 'is-active' : ''; ?>" id="agEditRepeatBox">
          <label class="ag-repeat-toggle" for="agEditUlangiBulanan">
            <input type="checkbox"
                   name="ulangi_bulanan"
                   id="agEditUlangiBulanan"
                   value="1"
                   <?= $isMonthlyEdit ? 'checked' : ''; ?>>
            <div>
              <div class="ag-repeat-title">
                <i class="fas fa-redo-alt text-primary mr-1"></i>Ulangi di tanggal yang sama setiap bulan
              </div>
              <div class="ag-repeat-preview" id="agEditRepeatPreview">
                <?= $isMonthlyEdit ? 'Berulang otomatis setiap bulan' : 'Aktifkan agar jadwal berulang otomatis tiap bulan'; ?>
              </div>
            </div>
          </label>

          <div class="ag-repeat-options <?= $isMonthlyEdit ? '' : 'd-none'; ?>" id="agEditRepeatOptions">
            <label class="small font-weight-bold text-dark mb-1 d-block">Batas Pengulangan Bulanan:</label>
            <select name="mode_batas_bulanan" id="agEditModeBatas" class="form-control form-control-sm mb-2" style="border-radius: 8px;">
              <option value="forever" <?= $batasRawEdit === '' ? 'selected' : ''; ?>>Setiap Bulan (Tanpa Batas Waktu)</option>
              <option value="3_months">Selama 3 Bulan dari Tanggal Awal</option>
              <option value="6_months">Selama 6 Bulan dari Tanggal Awal</option>
              <option value="12_months">Selama 12 Bulan (1 Tahun)</option>
              <option value="end_of_year">Sampai Akhir Tahun</option>
              <option value="custom" <?= $batasRawEdit !== '' ? 'selected' : ''; ?>>Pilih Tanggal Batas Akhir...</option>
            </select>
            <div id="agEditCustomBatasWrap" class="<?= $batasRawEdit !== '' ? '' : 'd-none'; ?>">
              <input type="date"
                     name="batas_ulangi_bulanan"
                     id="agEditBatasDate"
                     class="form-control form-control-sm"
                     value="<?= esc($batasRawEdit, 'attr'); ?>"
                     style="border-radius: 8px;">
              <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Agenda berhenti berulang setelah melewati tanggal ini.</small>
            </div>
          </div>
        </div>

        <!-- Tanggal Notif -->
        <div class="form-group mb-3">
          <label class="ag-form-label" for="agEditTglNotif">
            <span><i class="far fa-bell text-warning mr-1"></i>Tanggal Notifikasi Pop-up</span>
          </label>
          <div class="input-group input-group-sm">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <input type="checkbox" name="notif" id="agEditNotifCheckbox" <?= !empty($getData['tgl_notif_akhir']) ? 'checked' : ''; ?> />
              </span>
            </div>
            <input type="text"
                   name="tgl_notif"
                   id="agEditTglNotif"
                   class="form-control"
                   <?= empty($getData['tgl_notif_akhir']) ? 'disabled' : ''; ?>
                   style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;" />
          </div>
        </div>

        <!-- Warna Agenda -->
        <div class="form-group mb-0">
          <label class="ag-form-label" for="colorPickerEdit">
            <span><i class="fas fa-palette text-teal mr-1"></i>Warna Label Kalender</span>
          </label>
          <div class="input-group input-group-sm mb-2">
            <input type="color"
                   name="warna_agenda"
                   id="colorPickerEdit"
                   class="form-control"
                   value="<?= esc($currentColorEdit, 'attr'); ?>"
                   style="height: 32px; padding: 2px 4px;">
            <div class="input-group-append">
              <span class="input-group-text">
                <i class="fas fa-eye-dropper"></i>
              </span>
            </div>
          </div>
          <div class="color-preset-buttons mb-1">
            <button type="button" class="btn color-btn-edit" data-color="#0d9488" style="background-color: #0d9488;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#0073b7" style="background-color: #0073b7;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#4f46e5" style="background-color: #4f46e5;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#dc3545" style="background-color: #dc3545;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#28a745" style="background-color: #28a745;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#fd7e14" style="background-color: #fd7e14;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#d97706" style="background-color: #d97706;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#6f42c1" style="background-color: #6f42c1;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#e83e8c" style="background-color: #e83e8c;"></button>
            <button type="button" class="btn color-btn-edit" data-color="#334155" style="background-color: #334155;"></button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-end pt-3 mt-3 border-top" style="gap: 8px;">
    <button type="button" class="btn btn-light border btn-sm px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
    <button type="submit" class="btn btn-success btn-sm font-weight-bold px-4" style="border-radius: 8px; background: #0d9488; border-color: #0d9488;">
      <i class="fas fa-save mr-1"></i> Simpan Perubahan
    </button>
  </div>
</form>

<script>
  $(function() {
    var $editForm = $('#formUpdateAgenda-Edit');
    var $editWaktu = $('#agEditWaktuPelaksanaan');
    var $editNotif = $('#agEditTglNotif');

    $('#agEditSummernote').summernote({
      height: 220,
      toolbar: [
        ['style', ['bold', 'italic', 'underline', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']]
      ]
    });

    $editWaktu.daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerIncrement: 5,
      startDate: '<?= esc($getData['waktu_pelaksana_awal'] ?? date('Y-m-d 08:00:00'), 'js'); ?>',
      endDate: '<?= esc($getData['waktu_pelaksana_akhir'] ?? date('Y-m-d 16:00:00'), 'js'); ?>',
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss',
        applyLabel: 'Terapkan',
        cancelLabel: 'Batal'
      }
    });

    <?php if (!empty($getData['tgl_notif_akhir'])) : ?>
      var startNotifEdit = '<?= esc($getData['tgl_notif_awal'], 'js'); ?>';
      var endNotifEdit = '<?= esc($getData['tgl_notif_akhir'], 'js'); ?>';
    <?php else : ?>
      var startNotifEdit = moment('<?= esc(substr((string) ($getData['waktu_pelaksana_awal'] ?? date('Y-m-d')), 0, 10), 'js'); ?>');
      var endNotifEdit = moment('<?= esc(substr((string) ($getData['waktu_pelaksana_akhir'] ?? date('Y-m-d')), 0, 10), 'js'); ?>');
    <?php endif; ?>

    $editNotif.daterangepicker({
      startDate: startNotifEdit,
      endDate: endNotifEdit,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    $editWaktu.on('apply.daterangepicker change', function(ev, picker) {
      if (picker && $editNotif.data('daterangepicker')) {
        var nStart = picker.startDate.clone().startOf('day');
        var nEnd = picker.endDate.clone().startOf('day');
        $editNotif.data('daterangepicker').setStartDate(nStart);
        $editNotif.data('daterangepicker').setEndDate(nEnd);
        $editNotif.val(nStart.format('YYYY-MM-DD') + ' - ' + nEnd.format('YYYY-MM-DD'));
      }
      if (typeof window.updateAgendaMonthlyPreview === 'function') {
        window.updateAgendaMonthlyPreview(
          $editWaktu.val(),
          $('#agEditUlangiBulanan').is(':checked'),
          $('#agEditRepeatPreview'),
          $('#agEditRepeatBox'),
          $('#agEditRepeatOptions')
        );
      }
    });

    // Inisialisasi pratinjau pengulangan bulanan saat modal terbuka
    if (typeof window.updateAgendaMonthlyPreview === 'function') {
      window.updateAgendaMonthlyPreview(
        $editWaktu.val(),
        $('#agEditUlangiBulanan').is(':checked'),
        $('#agEditRepeatPreview'),
        $('#agEditRepeatBox'),
        $('#agEditRepeatOptions')
      );
    }

    $('#agEditUlangiBulanan').on('change', function() {
      if (typeof window.updateAgendaMonthlyPreview === 'function') {
        window.updateAgendaMonthlyPreview(
          $editWaktu.val(),
          $(this).is(':checked'),
          $('#agEditRepeatPreview'),
          $('#agEditRepeatBox'),
          $('#agEditRepeatOptions')
        );
      }
    });

    $('#agEditModeBatas').on('change', function() {
      var isCustom = $(this).val() === 'custom';
      $('#agEditCustomBatasWrap').toggleClass('d-none', !isCustom);
      if (!isCustom) {
        $('#agEditBatasDate').val('');
      }
    });

    $('#agEditNotifCheckbox').on('change', function() {
      $editNotif.prop('disabled', !$(this).is(':checked'));
    });

    $('#agEditPeruntukan').select2({
      dropdownParent: $('#modal-lg')
    });

    $('#btnEditAllUserAgenda').on('click', function() {
      var $select = $('#agEditPeruntukan');
      $select.find('option').prop('selected', true);
      $select.trigger('change');
    });

    $('#btnEditRemoveUserAgenda').on('click', function() {
      var $select = $('#agEditPeruntukan');
      $select.find('option').prop('selected', false);
      $select.find('option[data-self="1"], option:disabled').prop('selected', true);
      $select.trigger('change');
    });

    var currentColor = ($('#colorPickerEdit').val() || '').toLowerCase();
    $editForm.find('.color-btn-edit').each(function() {
      if (String($(this).data('color')).toLowerCase() === currentColor) {
        $(this).addClass('active');
      }
    });

    $editForm.find('.color-btn-edit').on('click', function() {
      var selectedColor = $(this).data('color');
      $('#colorPickerEdit').val(selectedColor);
      $editForm.find('.color-btn-edit').removeClass('active');
      $(this).addClass('active');
    });
  });
</script>
