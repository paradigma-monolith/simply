<?php
$rows = $getData ?? [];
$totalCount = count($rows);
$monthlyCount = 0;
$upcomingCount = 0;
$notifCount = 0;
$todayDate = date('Y-m-d');

foreach ($rows as $r) {
  $isM = !empty($r['ulangi_bulanan']) && (int) $r['ulangi_bulanan'] === 1;
  if ($isM) {
    $monthlyCount++;
  }
  $endD = !empty($r['waktu_pelaksana_akhir']) ? substr((string) $r['waktu_pelaksana_akhir'], 0, 10) : '';
  if ($isM || ($endD !== '' && $endD >= $todayDate)) {
    $upcomingCount++;
  }
  if (!empty($r['tgl_notif_akhir']) && ($isM || (string) $r['tgl_notif_akhir'] >= $todayDate)) {
    $notifCount++;
  }
}
?>

<style>
  .ag-my-toolbar {
    padding: 6px 8px 10px;
    border-bottom: 1px solid #f1f5f9;
  }
  .ag-my-search {
    position: relative;
    margin-bottom: 8px;
  }
  .ag-my-search i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.78rem;
  }
  .ag-my-search input {
    width: 100%;
    padding: 6px 10px 6px 30px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 0.79rem;
    background: #f8fafc;
    color: #0f172a;
    transition: all 0.18s ease;
  }
  .ag-my-search input:focus {
    outline: none;
    background: #ffffff;
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }
  .ag-my-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
  }
  .ag-my-filter-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    font-size: 0.71rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .ag-my-filter-btn.active {
    background: #0d9488;
    border-color: #0d9488;
    color: #ffffff;
  }

  .ag-list-scroll {
    max-height: 530px;
    overflow-y: auto;
    padding: 6px;
    scrollbar-width: thin;
  }
  .ag-item-card {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #0d9488;
    border-radius: 10px;
    background: #ffffff;
    margin-bottom: 8px;
    transition: box-shadow 0.16s ease, border-color 0.16s ease;
  }
  .ag-item-card:hover {
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.06);
  }
  .ag-item-main {
    min-width: 0;
    flex: 1 1 auto;
  }
  .ag-item-title {
    font-size: 0.84rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
    margin-bottom: 4px;
    word-break: break-word;
  }
  .ag-item-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    color: #64748b;
  }
  .ag-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 7px;
    border-radius: 6px;
    font-size: 0.68rem;
    font-weight: 600;
  }
  .ag-pill-monthly {
    background: #eef2ff;
    color: #4338ca;
    border: 1px solid #c7d2fe;
  }
  .ag-pill-users {
    background: #f1f5f9;
    color: #475569;
  }
  .ag-pill-notif {
    background: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
  }
  .ag-item-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
  }
  .ag-action-btn {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .ag-action-edit {
    background: #fffbeb;
    color: #d97706;
    border-color: #fde68a;
  }
  .ag-action-edit:hover {
    background: #f59e0b;
    color: #ffffff;
  }
  .ag-action-del {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
  }
  .ag-action-del:hover {
    background: #dc2626;
    color: #ffffff;
  }
</style>

<div class="ag-my-toolbar">
  <div class="ag-my-search">
    <i class="fas fa-search"></i>
    <input type="text" id="agMySearchInput" placeholder="Cari topik agenda saya...">
  </div>
  <div class="ag-my-filters" id="agMyFilterBar">
    <button type="button" class="ag-my-filter-btn active" data-filter="all">Semua (<?= $totalCount; ?>)</button>
    <button type="button" class="ag-my-filter-btn" data-filter="monthly">
      <i class="fas fa-redo-alt mr-1"></i>Rutin Bulanan (<?= $monthlyCount; ?>)
    </button>
    <button type="button" class="ag-my-filter-btn" data-filter="once">Sekali Jalan (<?= max(0, $totalCount - $monthlyCount); ?>)</button>
  </div>
</div>

<div class="ag-list-scroll" id="agMyListContainer">
  <?php if (empty($rows)) : ?>
    <div class="text-center text-muted py-5 px-3">
      <i class="far fa-calendar-plus fa-2x mb-2 d-block" style="opacity: 0.45;"></i>
      <div class="font-weight-bold small">Belum Ada Agenda</div>
      <small>Gunakan formulir di sebelah kiri untuk membuat agenda baru.</small>
    </div>
  <?php else : ?>
    <?php foreach ($rows as $data) : ?>
      <?php
      $idAgenda = (int) ($data['id_agenda_user'] ?? 0);
      $warna = !empty($data['warna_agenda']) ? trim((string) $data['warna_agenda']) : '#0d9488';
      $isMonthly = !empty($data['ulangi_bulanan']) && (int) $data['ulangi_bulanan'] === 1;
      $startTs = !empty($data['waktu_pelaksana_awal']) ? strtotime((string) $data['waktu_pelaksana_awal']) : false;
      $endTs = !empty($data['waktu_pelaksana_akhir']) ? strtotime((string) $data['waktu_pelaksana_akhir']) : false;
      $dayNum = $startTs ? (int) date('j', $startTs) : 1;
      $endDayNum = $endTs ? (int) date('j', $endTs) : $dayNum;

      $peruntukanArr = @unserialize((string) ($data['peruntukan'] ?? ''), ['allowed_classes' => false]);
      $recipientCount = is_array($peruntukanArr) ? count($peruntukanArr) : 1;

      $batasRaw = trim((string) ($data['batas_ulangi_bulanan'] ?? ''));
      $hasBatas = ($batasRaw !== '' && $batasRaw !== '0000-00-00');
      $hasNotif = !empty($data['tgl_notif_akhir']);
      ?>
      <div class="ag-item-card"
           data-type="<?= $isMonthly ? 'monthly' : 'once'; ?>"
           data-title="<?= esc(strtolower((string) ($data['topik'] ?? '')), 'attr'); ?>"
           style="border-left-color: <?= esc($warna, 'attr'); ?>;">
        <div class="ag-item-main">
          <div class="ag-item-title"><?= esc($data['topik'] ?? ''); ?></div>

          <div class="ag-item-meta mb-1">
            <?php if ($startTs && $endTs) : ?>
              <span>
                <i class="far fa-calendar-alt text-info mr-1"></i>
                <?php if (date('Y-m-d', $startTs) === date('Y-m-d', $endTs)) : ?>
                  <?= date('d/m/Y', $startTs); ?> • <?= date('H:i', $startTs); ?>–<?= date('H:i', $endTs); ?>
                <?php else : ?>
                  <?= date('d/m/Y H:i', $startTs); ?> s/d <?= date('d/m/Y H:i', $endTs); ?>
                <?php endif; ?>
              </span>
            <?php else : ?>
              <span class="text-muted">Waktu belum ditentukan</span>
            <?php endif; ?>
          </div>

          <div class="ag-item-meta">
            <?php if ($isMonthly) : ?>
              <span class="ag-pill ag-pill-monthly" title="Agenda ini berulang otomatis setiap bulan pada tanggal yang sama">
                <i class="fas fa-redo-alt"></i>
                <?php if ($startTs && $endTs && date('Y-m-d', $startTs) !== date('Y-m-d', $endTs)) : ?>
                  Tiap Bulan • Tgl <?= $dayNum; ?>–<?= $endDayNum; ?>
                <?php else : ?>
                  Tiap Bulan • Tgl <?= $dayNum; ?>
                <?php endif; ?>
                <?= $hasBatas ? ' (s/d ' . date('d/m/y', strtotime($batasRaw)) . ')' : ''; ?>
              </span>
            <?php endif; ?>

            <span class="ag-pill ag-pill-users">
              <i class="fas <?= $recipientCount > 1 ? 'fa-users' : 'fa-user'; ?>"></i>
              <?= $recipientCount > 1 ? $recipientCount . ' pegawai' : 'Pribadi'; ?>
            </span>

            <?php if ($hasNotif) : ?>
              <span class="ag-pill ag-pill-notif" title="Notifikasi pop-up aktif">
                <i class="far fa-bell"></i> Notif
              </span>
            <?php endif; ?>
          </div>
        </div>

        <div class="ag-item-actions">
          <button type="button"
                  class="ag-action-btn ag-action-edit"
                  onclick="EditAgendaSaya('<?= $idAgenda; ?>')"
                  title="Edit Agenda">
            <i class="fas fa-pen"></i>
          </button>
          <button type="button"
                  class="ag-action-btn ag-action-del"
                  onclick="DeleteAgendaSaya('<?= $idAgenda; ?>')"
                  title="Hapus Agenda">
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
      </div>
    <?php endforeach; ?>

    <div id="agMyEmptyFilter" class="text-center text-muted py-4 d-none">
      <i class="fas fa-search mb-1 d-block"></i>
      <small>Tidak ada agenda yang cocok dengan pencarian/filter.</small>
    </div>
  <?php endif; ?>
</div>

<script>
  (function() {
    // Sinkronisasi angka KPI di hero header jika ada
    if ($('#agKpiTotal').length) {
      $('#agKpiTotal').text('<?= $totalCount; ?>');
      $('#agKpiMonthly').html('<i class="fas fa-redo-alt mr-1" style="font-size: 0.8rem;"></i><?= $monthlyCount; ?>');
      $('#agKpiUpcoming').text('<?= $upcomingCount; ?>');
      $('#agKpiNotif').text('<?= $notifCount; ?>');
    }

    var currentFilter = 'all';
    function applyMyAgendaFilter() {
      var q = ($('#agMySearchInput').val() || '').trim().toLowerCase();
      var visible = 0;
      $('#agMyListContainer .ag-item-card').each(function() {
        var $card = $(this);
        var type = $card.data('type');
        var title = String($card.data('title') || '');
        var matchType = (currentFilter === 'all' || type === currentFilter);
        var matchQuery = (q === '' || title.indexOf(q) !== -1);
        var show = matchType && matchQuery;
        $card.toggle(show);
        if (show) visible++;
      });
      $('#agMyEmptyFilter').toggleClass('d-none', visible > 0);
    }

    $('#agMySearchInput').off('input').on('input', applyMyAgendaFilter);
    $('#agMyFilterBar').off('click', '.ag-my-filter-btn').on('click', '.ag-my-filter-btn', function() {
      $('#agMyFilterBar .ag-my-filter-btn').removeClass('active');
      $(this).addClass('active');
      currentFilter = $(this).data('filter') || 'all';
      applyMyAgendaFilter();
    });
  })();

  function EditAgendaSaya(id) {
    $('#modal-lg').find('.modal-body').html('<div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat data agenda...</div>');
    $('#modal-lg').find('.modal-header').html(
      '<h5 class="modal-title font-weight-bold"><i class="fas fa-calendar-alt text-info mr-2"></i>Edit Agenda</h5>' +
      '<button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>'
    );
    $('#modal-lg').modal('show');

    $.ajax({
      url: '/home/agenda/ajax/get_agenda_saya/' + id,
      method: 'get',
      dataType: 'json',
      success: function(response) {
        if (response && response.data) {
          $('#modal-lg').find('.modal-header').html(
            '<h5 class="modal-title font-weight-bold" style="color: #0f172a;"><i class="fas fa-edit text-info mr-2"></i>Edit Agenda</h5>' +
            '<div class="d-flex align-items-center" style="gap: 8px;">' +
              '<button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" onclick="$(\'#modal-lg\').modal(\'hide\'); DeleteAgendaSaya(\'' + id + '\');">' +
                '<i class="fas fa-trash-alt mr-1"></i>Hapus' +
              '</button>' +
              '<button type="button" class="btn btn-xs btn-light border px-2 py-1" onclick="$(\'#modal-lg\').modal(\'hide\')">Tutup</button>' +
            '</div>'
          );
          $('#modal-lg').find('.modal-body').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $('#modal-lg').modal('hide');
        Swal.fire({
          icon: 'error',
          title: 'Gagal Memuat Agenda',
          text: xhr.status + ' ' + thrownError
        });
      }
    });
  }

  function DeleteAgendaSaya(id) {
    Swal.fire({
      title: 'Hapus Agenda?',
      text: 'Agenda yang dihapus akan hilang dari daftar dan kalender penerima.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then(function(result) {
      if (result.isConfirmed) {
        $.ajax({
          url: '/home/dlt_agenda/' + id,
          method: 'POST',
          data: {
            '_method': 'DELETE',
            '<?= csrf_token(); ?>': '<?= csrf_hash(); ?>'
          },
          success: function() {
            Toast.fire({
              icon: 'success',
              title: 'Agenda berhasil dihapus.'
            });
            AgendaSaya();
            getCallendarAgenda();
          },
          error: function(xhr) {
            Swal.fire({
              title: 'Error!',
              text: 'Gagal menghapus agenda: ' + xhr.responseText,
              icon: 'error'
            });
          }
        });
      }
    });
  }
</script>
