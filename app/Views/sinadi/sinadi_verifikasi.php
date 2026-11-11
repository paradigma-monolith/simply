<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->

<section class="content">
  <div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-5">
            <div class="card card-primary card-outline shadow-sm">
              <div class="card-header bg-light">
                <h3 class="card-title"><i class="fas fa-user-check mr-2 text-primary"></i>Selamat Datang, <?= name_capitalize(session()->get('nama')); ?></h3>
              </div>
              <div class="card-body">
                <p class="mb-2 text-muted"><i class="fas fa-info-circle mr-1"></i> Anda memiliki akses sebagai:</p>
                <ul class="pl-3 list-group list-group-flush">
                  <?php foreach ($verifikator as $data): ?>
                    <li class="list-group-item border-0 pl-2 py-1"><i class="fas fa-check-circle text-success mr-2"></i><strong><?= $data['status']; ?></strong></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card card-info card-outline shadow-sm">
              <div class="card-header bg-light">
                <h3 class="card-title"><i class="fas fa-users mr-2 text-info"></i>Daftar Verifikator <?= ($tipe=='APBN' ? 'APBN' : ($tipe=='RPL' ? 'RPL' : '')); ?></h3>
              </div>
              <div class="card-body">
                <div class="row">
                <div class="col-md-6">
                  <?php
                  $i = 1;
                  foreach ($daftarVerifikator as $item): ?>
                      <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2"><i class="fas fa-user-shield mr-1 text-secondary"></i> <?= esc($item['verifikator']) ?> <?= $item['verifikator'] == "Verifikator 1" || $item['verifikator'] == "Verifikator Upload Bukti Pembayaran" ? "<small class='text-muted'>(salah satunya)</small>" : "" ; ?></h6>
                        <?php if ($item['anggota']): ?>
                            <ul class="mb-3 list-unstyled pl-3">
                                <?php foreach ($item['anggota'] as $p): ?>
                                    <li class="mb-1"><i class="fas fa-angle-right text-secondary mr-1"></i> <?= esc($p['nama']) ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-3 pl-3"><i class="fas fa-exclamation-circle mr-1"></i> Belum ditentukan</p>
                        <?php endif ?>
                    <?php if($i++ == 3): ?>
                    </div>
                    <div class="col-md-6">
                    <?php endif; ?>
                <?php endforeach ?>
                </div>
                </div>
              </div>
            </div>
        </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="d-flex align-items-center mb-3 justify-content-between">
      <div class="btn-group" role="group" aria-label="Submenu Validasi">
        <a href="/sinadi/verifikasiSurat?tipe=APBN" class="btn btn-sm <?= ($tipe=='APBN'?'btn-primary':'btn-outline-primary'); ?>">
          <i class="fas fa-coins mr-1"></i> APBN
          <span class="badge badge-light ml-1"><?= isset($countAPBN) ? (int) $countAPBN : 0; ?></span>
        </a>
        <a href="/sinadi/verifikasiSurat?tipe=RPL" class="btn btn-sm <?= ($tipe=='RPL'?'btn-success':'btn-outline-success'); ?>">
          <i class="fas fa-hand-holding-usd mr-1"></i> RPL
          <span class="badge badge-light ml-1"><?= isset($countRPL) ? (int) $countRPL : 0; ?></span>
        </a>
      </div>
      <small class="text-muted">Status yang ditampilkan: Diajukan, Dalam Verifikasi, Perlu Revisi.</small>
    </div>

    <?php if ($tipe === 'APBN' || $tipe === 'RPL'): ?>
    <?php
    $tahapCards = [
      [
        'title'      => 'Verifikator Pertama',
        'icon'       => 'fas fa-check-circle text-primary',
        'cardClass'  => 'card-primary',
        'badgeClass' => 'badge-primary',
        'badgeLabel' => 'Tahap 1',
        'tahapNo'    => '1',
        'rows'       => $tahap1Rows ?? [],
      ],
      [
        'title'      => 'Verifikator Kedua',
        'icon'       => 'fas fa-check-double text-info',
        'cardClass'  => 'card-info',
        'badgeClass' => 'badge-info',
        'badgeLabel' => 'Tahap 2',
        'tahapNo'    => '2',
        'rows'       => $tahap2Rows ?? [],
      ],
      [
        'title'      => 'Verifikator Ketiga',
        'icon'       => 'fas fa-clipboard-check text-success',
        'cardClass'  => 'card-success',
        'badgeClass' => 'badge-success',
        'badgeLabel' => 'Tahap 3',
        'tahapNo'    => '3',
        'rows'       => $tahap3Rows ?? [],
      ],
    ];
    ?>
    <style>
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
        font-size: 0.78rem;
      }
    </style>
    <div class="row">
      <?php foreach ($tahapCards as $card): ?>
      <?php $canVerifyStage = in_array($card['tahapNo'], $urut_verif, true); ?>
      <div class="col-lg-6">
        <div class="card <?= $card['cardClass']; ?> card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="<?= $card['icon']; ?> mr-2"></i><?= $card['title']; ?></h3>
            <div class="d-flex align-items-center">
              <input type="text" class="form-control form-control-sm mr-2 search-table" placeholder="Cari..." style="width: 150px;">
              <span class="badge <?= $card['badgeClass']; ?>"><?= $card['badgeLabel']; ?></span>
            </div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 400px;">
            <table class="table table-sm table-bordered table-hover">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="35%">Kegiatan</th>
                  <th width="25%">Tanggal</th>
                  <th width="25%">Tujuan</th>
                  <th width="10%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($card['rows'])): ?>
                  <?php $nomor = 1; ?>
                  <?php foreach ($card['rows'] as $data): ?>
                    <tr>
                      <td class="text-center align-middle"><?= $nomor++; ?></td>
                      <td class="align-middle">
                        <?= esc($data['nama_kegiatan']); ?>
                        <br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> <?= !empty($data['mata_anggaran']) ? esc($data['mata_anggaran']) : '-'; ?></small>
                      </td>
                      <td class="align-middle">
                        <i class="far fa-calendar-alt mr-1 text-muted"></i> <?= $data['tgl_pelaksanaan'] == $data['tgl_pelaksanaan_akhir'] ? mediumdate_indo($data['tgl_pelaksanaan']) : mediumdate_indo($data['tgl_pelaksanaan']) . ' s.d. ' . mediumdate_indo($data['tgl_pelaksanaan_akhir']); ?>
                      </td>
                      <td class="align-middle">
                        <i class="fas fa-map-marker-alt mr-1 text-muted"></i> <?= esc($data['tempat_perjalanan']); ?>
                      </td>
                      <td class="text-center align-middle">
                        <a href="<?= $canVerifyStage ? '/sinadi/verifikasi_data/' . $card['tahapNo'] . '/' . $data['id_perjalanan'] : 'javascript:void(0)'; ?>" class="btn btn-xs btn-warning <?= $canVerifyStage ? '' : 'disabled'; ?>" style="<?= $canVerifyStage ? '' : 'cursor: not-allowed; pointer-events: all;'; ?>" title="Verifikasi" data-toggle="tooltip">
                          <i class="fas fa-tasks"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-1"></i> Tidak ada data</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php endforeach; ?>

      <div class="col-lg-6">
        <div class="card card-warning card-outline shadow-sm">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-money-check-alt mr-2 text-warning"></i>Verifikator Upload Bukti Pembayaran</h3>
            <div class="d-flex align-items-center">
              <input type="text" id="searchBuktiPembayaran" class="form-control form-control-sm mr-2" placeholder="Cari..." style="width: 150px;">
              <span class="badge badge-warning text-white">Bukti Pembayaran</span>
            </div>
          </div>
          <div class="card-body p-0" style="height: 400px; overflow: hidden;">
            <table id="tableBuktiPembayaran" class="table table-sm table-bordered table-hover mb-0 w-100">
              <thead class="thead-light">
                <tr class="text-center">
                  <th width="5%">No</th>
                  <th width="25%">Kegiatan</th>
                  <th width="20%">Tanggal</th>
                  <th width="20%">Tujuan</th>
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

<?php if ($tipe === 'APBN' || $tipe === 'RPL'): ?>
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
        'Perlu Revisi': { cls: 'badge-warning', icon: 'fas fa-edit' },
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
            className: 'text-center align-middle'
          },
          {
            data: 'nama_kegiatan',
            className: 'align-middle',
            render: function (val, type, row) {
              var mak = row.mata_anggaran ? escHtml(row.mata_anggaran) : '-';
              return escHtml(val) + '<br><small class="text-muted"><i class="fas fa-tag mr-1"></i><strong>MAK:</strong> ' + mak + '</small>';
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
              return '<i class="fas fa-map-marker-alt mr-1 text-muted"></i> ' + escHtml(val);
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
              var titleAttr = hasFile ? 'Bukti Terupload' : 'Upload Bukti';
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
        }, 300);
      });

      // Delegasi tooltip khusus baris AJAX agar tidak memicu binding ribuan elemen di onload
      $('#tableBuktiPembayaran').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger: 'hover'
      });

      $('#tableBuktiPembayaran').on('preXhr.dt', function () {
        $('#tableBuktiPembayaran [data-toggle="tooltip"]').tooltip('hide');
      });

      $('.search-table').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $(this).closest('.card').find('tbody tr').filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
      });
    });
  </script>
<?php endif; ?>

<?php if ($activeMenu != "sinadiBlockir"): ?>
  <script>
    $(function () {
      $(document).on('click', '.sinadi-set-pembayaran', function () {
        var elementId = $(this).data('id');
        var modalBody = $('#modal-md .modal-body');

        modalBody.empty();

        var form = $('<form>', {
          action: '/sinadi/setUploadBuktiPembayaran',
          method: 'POST',
          enctype: 'multipart/form-data'
        });

        var inputId = $('<input>', {
          type: 'hidden',
          name: 'id',
          value: elementId
        });

        var inputFile = $('<div>').append(
          $('<label>', {
            class: 'font-weight-bold mb-2'
          }).text('Unggah Bukti Pembayaran'),
          $('<input>', {
            type: 'file',
            name: 'bukti_pembayaran',
            required: true,
            class: 'form-control'
          })
        );

        var submitButton = $('<button>', {
          type: 'submit',
          class: 'btn btn-success mt-3 float-right'
        }).html('<i class="fas fa-save"></i> Simpan');

        form.append(inputId, inputFile, submitButton);
        modalBody.append(form);
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
