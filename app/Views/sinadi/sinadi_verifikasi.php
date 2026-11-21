<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$cntStage1 = count($tahap1Rows ?? []);
$cntStage2 = count($tahap2Rows ?? []);
$cntStage3 = count($tahap3Rows ?? []);
$totalAntreanTahap = $cntStage1 + $cntStage2 + $cntStage3;
?>

<style>
  .sv-page {
    --sv-primary: #0d9488;
    --sv-primary-dark: #0f766e;
    --sv-primary-soft: #f0fdfa;
    --sv-border: rgba(15, 23, 42, 0.08);
    --sv-text: #0f172a;
    --sv-muted: #64748b;
    --sv-radius: 14px;
    --sv-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    padding-bottom: 24px;
  }

  /* Hero Banner */
  .sv-hero {
    background: linear-gradient(135deg, #047879 0%, #0d9488 55%, #115e59 100%);
    border-radius: var(--sv-radius);
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
  .sv-hero-title {
    font-size: 1.18rem;
    font-weight: 800;
    margin: 0 0 4px;
  }
  .sv-role-badges {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin-top: 6px;
  }
  .sv-role-pill {
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    font-size: 0.73rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }

  /* Segmented Budget Switcher (APBN / RPL) */
  .sv-budget-switcher {
    display: inline-flex;
    background: rgba(0, 0, 0, 0.18);
    padding: 4px;
    border-radius: 11px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    gap: 4px;
  }
  .sv-budget-btn {
    padding: 7px 15px;
    border-radius: 8px;
    font-size: 0.81rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.88) !important;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.16s ease;
  }
  .sv-budget-btn.active {
    background: #ffffff;
    color: #0f766e !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.16);
  }
  .sv-budget-cnt {
    background: rgba(255, 255, 255, 0.22);
    padding: 1px 7px;
    border-radius: 999px;
    font-size: 0.72rem;
  }
  .sv-budget-btn.active .sv-budget-cnt {
    background: #0d9488;
    color: #ffffff;
  }

  /* Verifier Team Accordion Card */
  .sv-team-card {
    background: #ffffff;
    border: 1px solid var(--sv-border);
    border-radius: var(--sv-radius);
    box-shadow: var(--sv-shadow);
    margin-bottom: 14px;
    overflow: hidden;
  }
  .sv-team-header {
    padding: 11px 16px;
    background: #f8fafc;
    border-bottom: 1px solid var(--sv-border);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    cursor: pointer;
  }
  .sv-team-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    padding: 14px 16px;
  }
  .sv-team-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
  }
  .sv-team-role {
    font-size: 0.76rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .sv-member-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
  }
  .sv-member-chip {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 2px 8px;
    font-size: 0.71rem;
    font-weight: 600;
    color: #334155;
  }

  /* Stage Queue Cards */
  .sv-stage-card {
    background: #ffffff;
    border: 1px solid var(--sv-border);
    border-radius: var(--sv-radius);
    box-shadow: var(--sv-shadow);
    margin-bottom: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 450px;
  }
  .sv-stage-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--sv-border);
    background: #ffffff;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
  }
  .sv-stage-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .sv-stage-search {
    width: 155px;
    border-radius: 8px !important;
    font-size: 0.77rem !important;
    height: 30px !important;
  }
  .sv-stage-body {
    flex: 1 1 auto;
    overflow-y: auto;
    scrollbar-width: thin;
  }
  .sv-table {
    margin-bottom: 0;
  }
  .sv-table thead th {
    position: sticky;
    top: 0;
    z-index: 3;
    background: #f8fafc;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-top: none !important;
    border-bottom: 2px solid #e2e8f0 !important;
  }
  .sv-table tbody td {
    font-size: 0.8rem;
    vertical-align: middle !important;
    color: #1e293b;
  }

  #tableBuktiPembayaran_wrapper {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  #tableBuktiPembayaran_wrapper .sinadi-dt-scroll {
    flex: 1 1 auto;
    overflow-y: auto;
  }
  #tableBuktiPembayaran_wrapper .dataTables_info {
    padding-top: 0;
    margin-left: 0.5rem;
  }
  #tableBuktiPembayaran_wrapper .dataTables_length label {
    margin-bottom: 0;
    font-weight: normal;
  }
  #tableBuktiPembayaran_wrapper .pagination {
    margin-bottom: 0;
  }
  #tableBuktiPembayaran_wrapper .pagination .page-link {
    padding: 0.2rem 0.45rem;
    font-size: 0.76rem;
  }

  @media (max-width: 991.98px) {
    .sv-team-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  @media (max-width: 767.98px) {
    .sv-hero {
      padding: 14px 16px;
    }
    .sv-team-grid {
      grid-template-columns: 1fr;
    }
    .sv-stage-card {
      height: 390px;
    }
  }
</style>

<section class="content sv-page">
  <div class="container-fluid">

    <!-- Hero Banner & Segmented APBN/RPL Switcher -->
    <div class="sv-hero">
      <div>
        <h4 class="sv-hero-title">
          <i class="fas fa-clipboard-check mr-2"></i>Validasi Dokumen Perjalanan Dinas (<?= esc($tipe); ?>)
        </h4>
        <div class="small" style="color: rgba(255,255,255,0.86);">
          Selamat datang, <strong><?= esc(name_capitalize((string) session()->get('nama'))); ?></strong> • <?= $totalAntreanTahap; ?> dokumen dalam antrean verifikasi tahap 1–3
        </div>
        <div class="sv-role-badges">
          <?php if (!empty($verifikator)) : ?>
            <?php foreach ($verifikator as $vRole) : ?>
              <span class="sv-role-pill">
                <i class="fas fa-user-check"></i> <?= esc($vRole['status']); ?>
              </span>
            <?php endforeach; ?>
          <?php else : ?>
            <span class="sv-role-pill">
              <i class="fas fa-eye"></i> Mode Pantau (Bukan Verifikator Aktif)
            </span>
          <?php endif; ?>
        </div>
      </div>

      <div class="sv-budget-switcher" role="group" aria-label="Pilih Sumber Anggaran">
        <a href="/sinadi/verifikasiSurat?tipe=APBN" class="sv-budget-btn <?= $tipe === 'APBN' ? 'active' : ''; ?>">
          <i class="fas fa-coins"></i> APBN
          <span class="sv-budget-cnt"><?= isset($countAPBN) ? (int) $countAPBN : 0; ?></span>
        </a>
        <a href="/sinadi/verifikasiSurat?tipe=RPL" class="sv-budget-btn <?= $tipe === 'RPL' ? 'active' : ''; ?>">
          <i class="fas fa-hand-holding-usd"></i> RPL
          <span class="sv-budget-cnt"><?= isset($countRPL) ? (int) $countRPL : 0; ?></span>
        </a>
      </div>
    </div>

    <!-- Susunan Tim Verifikator (Collapsible agar hemat ruang vertikal) -->
    <div class="sv-team-card">
      <div class="sv-team-header" onclick="$('#svTeamBody').slideToggle(180); $('#svTeamChevron').toggleClass('fa-chevron-down fa-chevron-up');">
        <div class="d-flex align-items-center" style="gap: 8px;">
          <span class="badge badge-info px-2 py-1"><i class="fas fa-users-cog mr-1"></i>Tim Verifikator <?= esc($tipe); ?></span>
          <span class="small text-muted">Klik untuk melihat / menyembunyikan daftar pejabat verifikator</span>
        </div>
        <i class="fas fa-chevron-down text-muted" id="svTeamChevron"></i>
      </div>
      <div id="svTeamBody" style="display: none;">
        <div class="sv-team-grid">
          <?php foreach ($daftarVerifikator as $item) : ?>
            <?php
            $isMulti = ($item['verifikator'] === 'Verifikator 1' || $item['verifikator'] === 'Verifikator Upload Bukti Pembayaran');
            ?>
            <div class="sv-team-box">
              <div class="sv-team-role">
                <span><i class="fas fa-user-shield text-info mr-1"></i><?= esc($item['verifikator']); ?></span>
                <?php if ($isMulti) : ?>
                  <span class="badge badge-light border text-muted" style="font-size: 0.65rem;">Salah satu</span>
                <?php endif; ?>
              </div>
              <?php if (!empty($item['anggota'])) : ?>
                <div class="sv-member-chips">
                  <?php foreach ($item['anggota'] as $p) : ?>
                    <span class="sv-member-chip"><?= esc($p['nama']); ?></span>
                  <?php endforeach; ?>
                </div>
              <?php else : ?>
                <small class="text-muted font-italic"><i class="fas fa-exclamation-circle mr-1"></i>Belum ditentukan</small>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <?php if ($tipe === 'APBN' || $tipe === 'RPL') : ?>
      <?php
      $tahapCards = [
        [
          'title'      => 'Verifikator Pertama',
          'icon'       => 'fas fa-check-circle text-primary',
          'badgeClass' => 'badge-primary',
          'badgeLabel' => 'Tahap 1',
          'tahapNo'    => '1',
          'rows'       => $tahap1Rows ?? [],
        ],
        [
          'title'      => 'Verifikator Kedua',
          'icon'       => 'fas fa-check-double text-info',
          'badgeClass' => 'badge-info',
          'badgeLabel' => 'Tahap 2',
          'tahapNo'    => '2',
          'rows'       => $tahap2Rows ?? [],
        ],
        [
          'title'      => 'Verifikator Ketiga',
          'icon'       => 'fas fa-clipboard-check text-success',
          'badgeClass' => 'badge-success',
          'badgeLabel' => 'Tahap 3',
          'tahapNo'    => '3',
          'rows'       => $tahap3Rows ?? [],
        ],
      ];
      ?>
      <div class="row">
        <?php foreach ($tahapCards as $card) : ?>
          <?php
          $canVerifyStage = in_array($card['tahapNo'], $urut_verif, true);
          $stageCount = count($card['rows']);
          ?>
          <div class="col-lg-6">
            <div class="sv-stage-card">
              <div class="sv-stage-header">
                <h5 class="sv-stage-title">
                  <i class="<?= $card['icon']; ?>"></i>
                  <span><?= esc($card['title']); ?></span>
                  <span class="badge <?= $card['badgeClass']; ?> badge-pill"><?= $stageCount; ?></span>
                </h5>
                <div class="d-flex align-items-center" style="gap: 6px;">
                  <input type="text" class="form-control form-control-sm sv-stage-search search-table" placeholder="Cari kegiatan/MAK...">
                  <span class="badge badge-light border text-muted"><?= esc($card['badgeLabel']); ?></span>
                </div>
              </div>

              <div class="sv-stage-body">
                <table class="table table-sm table-bordered table-hover sv-table">
                  <thead>
                    <tr class="text-center">
                      <th width="6%">No</th>
                      <th width="38%">Kegiatan &amp; MAK</th>
                      <th width="24%">Tanggal</th>
                      <th width="20%">Tujuan</th>
                      <th width="12%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($card['rows'])) : ?>
                      <?php $nomor = 1; ?>
                      <?php foreach ($card['rows'] as $data) : ?>
                        <tr>
                          <td class="text-center font-weight-bold"><?= $nomor++; ?></td>
                          <td>
                            <div class="font-weight-bold text-dark" style="line-height: 1.3;"><?= esc($data['nama_kegiatan']); ?></div>
                            <small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? esc($data['mata_anggaran']) : '-'; ?></small>
                          </td>
                          <td>
                            <i class="far fa-calendar-alt mr-1 text-muted"></i>
                            <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir']
                              ? mediumdate_indo($data['tgl_pelaksanaan'])
                              : mediumdate_indo($data['tgl_pelaksanaan']) . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                          </td>
                          <td>
                            <i class="fas fa-map-marker-alt mr-1 text-danger" style="opacity:0.7;"></i>
                            <?= esc($data['tempat_perjalanan']); ?>
                          </td>
                          <td class="text-center">
                            <a href="<?= $canVerifyStage ? '/sinadi/verifikasi_data/' . $card['tahapNo'] . '/' . $data['id_perjalanan'] : 'javascript:void(0)'; ?>"
                               class="btn btn-xs <?= $canVerifyStage ? 'btn-warning font-weight-bold' : 'btn-light border text-muted disabled'; ?> px-2 py-1"
                               style="border-radius: 7px; <?= $canVerifyStage ? '' : 'cursor: not-allowed; pointer-events: all;'; ?>"
                               title="<?= $canVerifyStage ? 'Verifikasi Dokumen' : 'Anda bukan verifikator tahap ini'; ?>"
                               data-toggle="tooltip">
                              <i class="fas fa-tasks mr-1"></i>Periksa
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                          <i class="fas fa-check-circle fa-2x mb-2 d-block text-success" style="opacity: 0.45;"></i>
                          Tidak ada antrean dokumen pada <?= esc($card['badgeLabel']); ?>
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <!-- Kartu Ke-4: Verifikator Upload Bukti Pembayaran (Server-side DataTables) -->
        <div class="col-lg-6">
          <div class="sv-stage-card">
            <div class="sv-stage-header">
              <h5 class="sv-stage-title">
                <i class="fas fa-money-check-alt text-warning"></i>
                <span>Upload Bukti Pembayaran &amp; Lembar</span>
              </h5>
              <div class="d-flex align-items-center" style="gap: 6px;">
                <input type="text" id="searchBuktiPembayaran" class="form-control form-control-sm sv-stage-search" placeholder="Cari kegiatan/status...">
                <span class="badge badge-warning text-dark">Pembayaran</span>
              </div>
            </div>
            <div class="sv-stage-body p-0" style="overflow: hidden;">
              <table id="tableBuktiPembayaran" class="table table-sm table-bordered table-hover sv-table mb-0 w-100">
                <thead>
                  <tr class="text-center">
                    <th width="5%">No</th>
                    <th width="27%">Kegiatan &amp; MAK</th>
                    <th width="20%">Tanggal</th>
                    <th width="18%">Tujuan</th>
                    <th width="10%">Status</th>
                    <th width="10%">Bukti</th>
                    <th width="10%">Lembar</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php if ($tipe === 'APBN' || $tipe === 'RPL') : ?>
  <script>
    $(function () {
      function escHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }

      var statusBadgeMap = {
        'Draft': { cls: 'badge-secondary', icon: 'fas fa-pencil-alt' },
        'Diajukan': { cls: 'badge-primary', icon: 'fas fa-paper-plane' },
        'Dalam Verifikasi': { cls: 'badge-info', icon: 'fas fa-spinner' },
        'Perlu Revisi': { cls: 'badge-warning text-dark', icon: 'fas fa-edit' },
        'Disetujui': { cls: 'badge-success', icon: 'fas fa-check' },
        'Ditolak': { cls: 'badge-danger', icon: 'fas fa-times' }
      };

      var tableBukti = $('#tableBuktiPembayaran').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: false,
        autoWidth: false,
        deferRender: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: "<'table-responsive sinadi-dt-scroll'tr><'border-top bg-light px-2 py-1 d-flex flex-wrap justify-content-between align-items-center'<'d-flex align-items-center small text-muted'li><''p>>",
        ajax: {
          url: '/sinadi/verifikasiBuktiPembayaran',
          type: 'POST',
          data: function (d) {
            d.tipe = '<?= esc($tipe, 'js'); ?>';
          }
        },
        language: {
          processing: 'Memuat data...',
          zeroRecords: '<div class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</div>',
          emptyTable: '<div class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</div>',
          info: '_START_-_END_ / _TOTAL_',
          infoEmpty: '0 data',
          infoFiltered: '(dari _MAX_)',
          lengthMenu: '_MENU_',
          paginate: {
            previous: '&laquo;',
            next: '&raquo;'
          }
        },
        columns: [
          {
            data: 'no',
            className: 'text-center align-middle font-weight-bold'
          },
          {
            data: 'nama_kegiatan',
            className: 'align-middle',
            render: function (val, type, row) {
              var mak = row.mata_anggaran ? escHtml(row.mata_anggaran) : '-';
              return '<div class="font-weight-bold text-dark">' + escHtml(val) + '</div><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> ' + mak + '</small>';
            }
          },
          {
            data: 'tanggal',
            className: 'align-middle',
            render: function (val) {
              return '<i class="far fa-calendar-alt mr-1 text-muted"></i> ' + escHtml(val);
            }
          },
          {
            data: 'tempat_perjalanan',
            className: 'align-middle',
            render: function (val) {
              return '<i class="fas fa-map-marker-alt mr-1 text-danger" style="opacity:0.7;"></i> ' + escHtml(val);
            }
          },
          {
            data: 'status_surat',
            className: 'text-center align-middle',
            render: function (status) {
              var cfg = statusBadgeMap[status];
              if (!cfg) return escHtml(status);
              return '<span class="badge ' + cfg.cls + '"><i class="' + cfg.icon + ' mr-1"></i> ' + escHtml(status) + '</span>';
            }
          },
          {
            data: null,
            className: 'text-center align-middle',
            render: function (_, __, row) {
              var hasFile = !!row.bukti_pembayaran;
              var btnClass = hasFile ? 'btn-success' : 'btn-warning';
              var actionClass = row.can_upload ? 'sinadi-set-pembayaran' : 'disabled';
              var styleAttr = row.can_upload ? '' : ' style="cursor: not-allowed; pointer-events: all;"';
              var titleAttr = hasFile ? 'Bukti Terupload (Klik untuk ganti)' : 'Upload Bukti Pembayaran';
              var iconHtml = hasFile ? '<i class="fas fa-check"></i>' : '<i class="fas fa-upload"></i>';

              var html = '<div class="btn-group">' +
                '<a data-id="' + row.id_perjalanan + '" href="javascript:void(0)" class="btn btn-xs ' + btnClass + ' ' + actionClass + '"' + styleAttr + ' title="' + titleAttr + '" data-toggle="tooltip">' +
                iconHtml +
                '</a>';

              if (hasFile) {
                var fileUrl = '/sinadi/previewBuktiBerkas/' + encodeURIComponent(row.bukti_pembayaran);
                html += '<a href="javascript:void(0)" data-file="' + escHtml(fileUrl) + '" class="fileSinadi btn btn-xs btn-info" title="Lihat Bukti" data-toggle="tooltip">' +
                  '<i class="fas fa-eye"></i>' +
                  '</a>';
              }

              html += '</div>';
              return html;
            }
          },
          {
            data: null,
            className: 'text-center align-middle',
            render: function (_, __, row) {
              var disabledCls = row.status_surat === 'Diajukan' ? ' disabled' : '';
              return '<div class="btn-group">' +
                '<a href="/sinadi/export/lembar_verifikator/' + row.id_perjalanan + '" class="btn btn-xs btn-success' + disabledCls + '" title="Download Excel" data-toggle="tooltip">' +
                '<i class="far fa-file-excel"></i>' +
                '</a>' +
                '<a href="/sinadi/export/lembar_verifikator/pdf/' + row.id_perjalanan + '" class="btn btn-xs btn-danger' + disabledCls + '" title="Download PDF" data-toggle="tooltip">' +
                '<i class="far fa-file-pdf"></i>' +
                '</a>' +
                '</div>';
            }
          }
        ]
      });

      var searchTimer = null;
      $('#searchBuktiPembayaran').on('keyup input', function () {
        var value = this.value;
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
          if (tableBukti.search() !== value) {
            tableBukti.search(value).draw();
          }
        }, 250);
      });

      $('#tableBuktiPembayaran').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger: 'hover'
      });

      $('#tableBuktiPembayaran').on('preXhr.dt', function () {
        $('#tableBuktiPembayaran [data-toggle="tooltip"]').tooltip('hide');
      });

      $('.search-table').on('keyup input', function () {
        var value = $(this).val().toLowerCase();
        $(this).closest('.sv-stage-card').find('tbody tr').filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });
    });
  </script>
<?php endif; ?>

<?php if ($activeMenu != "sinadiBlockir") : ?>
  <script>
    $(function () {
      $(document).on('click', '.sinadi-set-pembayaran', function () {
        var elementId = $(this).data('id');
        $('#modal-md .modal-header').html(
          '<h5 class="modal-title font-weight-bold"><i class="fas fa-receipt text-warning mr-2"></i>Unggah Bukti Pembayaran</h5>' +
          '<button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>'
        );
        var modalBody = $('#modal-md .modal-body');
        modalBody.html(
          '<form action="/sinadi/setUploadBuktiPembayaran" method="POST" enctype="multipart/form-data">' +
            '<input type="hidden" name="id" value="' + elementId + '">' +
            '<div class="form-group mb-3">' +
              '<label class="font-weight-bold small text-dark">Pilih File Bukti Pembayaran (PDF / Gambar)</label>' +
              '<input type="file" name="bukti_pembayaran" required class="form-control" style="height:auto;padding:6px;">' +
            '</div>' +
            '<div class="d-flex justify-content-end" style="gap:8px;">' +
              '<button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Batal</button>' +
              '<button type="submit" class="btn btn-sm btn-success font-weight-bold px-3"><i class="fas fa-save mr-1"></i> Simpan Bukti</button>' +
            '</div>' +
          '</form>'
        );
        $('#modal-md').modal('show');
      });

      $(document).on('click', '.fileSinadi', function () {
        var file = $(this).data('file');
        if (file && file !== '#') {
          var normalizedPath = file.charAt(0) === '/' ? file : '/' + file;
          var fullUrl = '<?= rtrim(base_url(), "/"); ?>' + normalizedPath;
          $('#modal-xl').find('.modal-body').html(
            '<iframe src="' + fullUrl + '" frameborder="0" style="width: 100%; height: 80vh;"></iframe>'
          );
          $('#modal-xl').modal('show');
        }
      });
    });
  </script>
<?php endif; ?>

<?= $this->endSection(); ?>
