<?php
$rows = $perjalanan ?? [];
$totalDocs = count($rows);
$statusCounts = [
  'Draft'            => 0,
  'Diajukan'         => 0,
  'Dalam Verifikasi' => 0,
  'Perlu Revisi'     => 0,
  'Disetujui'        => 0,
  'Ditolak'          => 0,
];

foreach ($rows as $r) {
  $st = (string) ($r['status_surat'] ?? 'Draft');
  if (isset($statusCounts[$st])) {
    $statusCounts[$st]++;
  }
}
?>

<style>
  /* Status Filter Pills */
  .sn-status-filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
  }
  .sn-status-pill {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    font-size: 0.74rem;
    font-weight: 600;
    padding: 4px 11px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .sn-status-pill:hover {
    border-color: #0d9488;
    color: #0f766e;
  }
  .sn-status-pill.active {
    background: #0d9488;
    border-color: #0d9488;
    color: #ffffff;
  }
  .sn-status-pill .sn-pill-cnt {
    background: rgba(15, 23, 42, 0.08);
    padding: 1px 6px;
    border-radius: 999px;
    font-size: 0.67rem;
  }
  .sn-status-pill.active .sn-pill-cnt {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
  }
  .sn-search-wrap {
    position: relative;
    min-width: 220px;
    max-width: 290px;
    flex: 1 1 220px;
  }
  .sn-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.76rem;
    pointer-events: none;
  }
  .sn-search-input {
    width: 100%;
    height: 30px;
    padding: 4px 12px 4px 30px;
    border: 1px solid #cbd5e1;
    border-radius: 999px;
    font-size: 0.77rem;
    color: #1e293b;
    background: #f8fafc;
    transition: all 0.15s ease;
    outline: none;
  }
  .sn-search-input:focus {
    background: #ffffff;
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }
  .sn-length-wrap {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .sn-length-label {
    font-size: 0.74rem;
    font-weight: 600;
    color: #64748b;
    margin: 0;
    white-space: nowrap;
  }
  .sn-length-select {
    height: 30px;
    padding: 0 10px;
    border: 1px solid #cbd5e1;
    border-radius: 999px;
    font-size: 0.77rem;
    font-weight: 600;
    color: #1e293b;
    background: #f8fafc;
    outline: none;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .sn-length-select:focus {
    background: #ffffff;
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }
  #example11_wrapper .dataTables_filter {
    display: none !important;
  }

  /* Modern Table Overrides */
  #example11 thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.74rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 2px solid #e2e8f0;
    vertical-align: middle;
    white-space: nowrap;
  }
  #example11 tbody td {
    font-size: 0.81rem;
    vertical-align: middle;
    color: #1e293b;
  }
  #example11 tbody tr.shown {
    background-color: #f0fdfa !important;
  }
  #example11 tbody td.details-control {
    cursor: pointer;
  }
  .sn-act-group {
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;
  }
  .sn-act-btn {
    width: 27px;
    height: 27px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    border: 1px solid transparent;
    transition: all 0.15s ease;
  }
  .sn-badge-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 9px;
    border-radius: 999px;
    font-size: 0.71rem;
    font-weight: 700;
    white-space: nowrap;
  }
  .sn-sumber-pill {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 6px;
    font-size: 0.68rem;
    font-weight: 700;
  }
  .sn-sumber-apbn {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
  }
  .sn-sumber-rpl {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
  }
</style>

<!-- Quick Status Filter Pills -->
<div class="sn-status-filters" id="snStatusFilterBar">
  <button type="button" class="sn-status-pill active" data-status="">
    Semua <span class="sn-pill-cnt"><?= $totalDocs; ?></span>
  </button>
  <button type="button" class="sn-status-pill" data-status="Draft">
    Draft <span class="sn-pill-cnt"><?= $statusCounts['Draft']; ?></span>
  </button>
  <button type="button" class="sn-status-pill" data-status="Diajukan">
    Diajukan <span class="sn-pill-cnt"><?= $statusCounts['Diajukan']; ?></span>
  </button>
  <button type="button" class="sn-status-pill" data-status="Dalam Verifikasi">
    Dalam Verifikasi <span class="sn-pill-cnt"><?= $statusCounts['Dalam Verifikasi']; ?></span>
  </button>
  <button type="button" class="sn-status-pill" data-status="Perlu Revisi">
    Perlu Revisi <span class="sn-pill-cnt"><?= $statusCounts['Perlu Revisi']; ?></span>
  </button>
  <button type="button" class="sn-status-pill" data-status="Disetujui">
    Disetujui <span class="sn-pill-cnt"><?= $statusCounts['Disetujui']; ?></span>
  </button>
  <?php if ($statusCounts['Ditolak'] > 0) : ?>
    <button type="button" class="sn-status-pill" data-status="Ditolak">
      Ditolak <span class="sn-pill-cnt"><?= $statusCounts['Ditolak']; ?></span>
    </button>
  <?php endif; ?>
  <div class="d-flex flex-wrap align-items-center ml-auto" style="gap: 8px;">
    <div class="sn-length-wrap">
      <label for="snPageLength" class="sn-length-label">Tampilkan:</label>
      <select id="snPageLength" class="sn-length-select" aria-label="Jumlah baris per halaman">
        <option value="10">10 baris</option>
        <option value="20" selected>20 baris</option>
        <option value="50">50 baris</option>
        <option value="100">100 baris</option>
        <option value="-1">Semua baris</option>
      </select>
    </div>
    <div class="sn-search-wrap">
      <i class="fas fa-search sn-search-icon"></i>
      <input type="search" id="snTableSearch" class="sn-search-input" placeholder="Cari kegiatan, tujuan, MAK, pembuat..." autocomplete="off">
    </div>
  </div>
</div>

<div class="table-responsive">
  <table id="example11" class="table table-bordered table-hover mb-0" style="width: 100%;">
    <thead>
      <tr>
        <th width="4%">No</th>
        <th>Id</th>
        <th width="11%">Aksi</th>
        <th width="11%">Status Surat</th>
        <th width="13%">Tanggal Pelaksanaan</th>
        <th width="11%">MAK / Sumber</th>
        <th width="22%">Nama Kegiatan</th>
        <th width="14%">Tempat Tujuan</th>
        <th width="7%">Peserta</th>
        <th width="10%">Total Nominal</th>
        <th width="10%">Pembuat</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; ?>
      <?php foreach ($rows as $data) : ?>
        <?php
        $idPerjalanan = (int) ($data['id_perjalanan'] ?? 0);
        $statusSurat = (string) ($data['status_surat'] ?? 'Draft');
        $isOwnerOrAdmin = (session()->get('username') === ($data['username'] ?? '') || !empty($akses));
        $formEditUrl = '/sinadi/' . ($activeMenu === 'dinasDalamKota' ? 'formulirPerjalananDinas' : 'formulirPerjalananDinasAntarKota') . '/' . $idPerjalanan;
        $sumberAnggaran = !empty($data['sumber_anggaran']) ? strtoupper((string) $data['sumber_anggaran']) : 'APBN';
        $tglAwal = (string) ($data['tgl_pelaksanaan'] ?? '');
        $tglAkhir = (string) ($data['tgl_pelaksanaan_akhir'] ?? '');
        $dlFn = ( $tglAkhir !== '' && (int) date('Y', strtotime($tglAkhir)) >= 2025 ) ? 'downloadFileSinadiNew' : 'downloadFileSinadi';
        ?>
        <tr>
          <td class="text-center font-weight-bold"><?= $no++; ?></td>
          <td><?= $idPerjalanan; ?></td>

          <!-- Kolom Aksi -->
          <td class="text-nowrap">
            <div class="sn-act-group">
              <?php if ($statusSurat === 'Draft') : ?>
                <a href="/sinadi/validasi_data/<?= $idPerjalanan; ?>"
                   class="btn btn-xs btn-primary sn-act-btn"
                   title="Tanda Tangan &amp; Kirim Validasi">
                  <i class="fas fa-signature"></i>
                </a>
                <?php if ($isOwnerOrAdmin) : ?>
                  <a href="<?= $formEditUrl; ?>"
                     class="btn btn-xs btn-warning sn-act-btn"
                     title="Ubah Dokumen">
                    <i class="fas fa-edit"></i>
                  </a>
                  <?php if (!empty($akses)) : ?>
                    <form action="/sinadi/dlt_sinadi/<?= $idPerjalanan; ?>" method="post" class="d-inline formHapusData">
                      <?= csrf_field(); ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button type="submit" class="btn btn-xs btn-danger sn-act-btn" title="Hapus Dokumen">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                <?php endif; ?>

              <?php elseif ($statusSurat === 'Diajukan' || $statusSurat === 'Dalam Verifikasi') : ?>
                <a href="/sinadi/validasi_data/view/<?= $idPerjalanan; ?>"
                   class="btn btn-xs btn-info sn-act-btn"
                   title="Pratinjau &amp; Lacak Verifikasi">
                  <i class="fas fa-eye"></i>
                </a>

              <?php elseif ($statusSurat === 'Perlu Revisi') : ?>
                <a href="/sinadi/validasi_data/<?= $idPerjalanan; ?>"
                   class="btn btn-xs btn-primary sn-act-btn"
                   title="Tanda Tangan &amp; Ajukan Ulang">
                  <i class="fas fa-signature"></i>
                </a>
                <?php if ($isOwnerOrAdmin) : ?>
                  <a href="<?= $formEditUrl; ?>"
                     class="btn btn-xs btn-warning sn-act-btn"
                     title="Revisi / Ubah Dokumen">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="/sinadi/dlt_sinadi/<?= $idPerjalanan; ?>" method="post" class="d-inline formHapusData">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-xs btn-danger sn-act-btn" title="Hapus Dokumen">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                <?php endif; ?>
                <a href="/sinadi/validasi_data/view/<?= $idPerjalanan; ?>"
                   class="btn btn-xs btn-info sn-act-btn"
                   title="Lihat Catatan Revisi">
                  <i class="fas fa-eye"></i>
                </a>

              <?php elseif ($statusSurat === 'Disetujui') : ?>
                <button type="button"
                        class="btn btn-xs btn-danger sn-act-btn"
                        onclick="<?= $dlFn; ?>('<?= $idPerjalanan; ?>','<?= esc($data['nama'] ?? '', 'js'); ?>','<?= esc($data['bukti_pembayaran'] ?? '', 'js'); ?>')"
                        title="Unduh Berkas PDF">
                  <i class="fas fa-file-pdf"></i>
                </button>
                <a href="/sinadi/validasi_data/view/<?= $idPerjalanan; ?>"
                   class="btn btn-xs btn-info sn-act-btn"
                   title="Pratinjau Dokumen">
                  <i class="fas fa-eye"></i>
                </a>
                <?php if (!empty($akses)) : ?>
                  <a href="<?= $formEditUrl; ?>"
                     class="btn btn-xs btn-warning sn-act-btn"
                     title="Ubah Dokumen (Super User)">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form action="/sinadi/dlt_sinadi/<?= $idPerjalanan; ?>" method="post" class="d-inline formHapusData">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-xs btn-outline-danger sn-act-btn" title="Hapus Dokumen">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                <?php endif; ?>

              <?php else : ?>
                <span class="badge badge-secondary"><i class="fas fa-ban"></i></span>
              <?php endif; ?>
            </div>
          </td>

          <!-- Status Surat -->
          <td data-search="<?= esc($statusSurat, 'attr'); ?>">
            <?php if ($statusSurat === 'Draft') : ?>
              <span class="sn-badge-status badge-secondary"><i class="fas fa-pencil-alt"></i> Draft</span>
            <?php elseif ($statusSurat === 'Diajukan') : ?>
              <span class="sn-badge-status badge-primary"><i class="fas fa-paper-plane"></i> Diajukan</span>
            <?php elseif ($statusSurat === 'Dalam Verifikasi') : ?>
              <span class="sn-badge-status badge-info"><i class="fas fa-sync-alt"></i> Dalam Verifikasi</span>
            <?php elseif ($statusSurat === 'Perlu Revisi') : ?>
              <span class="sn-badge-status badge-warning text-dark"><i class="fas fa-exclamation-circle"></i> Perlu Revisi</span>
            <?php elseif ($statusSurat === 'Disetujui') : ?>
              <span class="sn-badge-status badge-success"><i class="fas fa-check-circle"></i> Disetujui</span>
            <?php else : ?>
              <span class="sn-badge-status badge-danger"><i class="fas fa-times-circle"></i> <?= esc($statusSurat); ?></span>
            <?php endif; ?>
          </td>

          <!-- Tanggal Pelaksanaan -->
          <td class="text-nowrap">
            <i class="far fa-calendar-alt text-muted mr-1"></i>
            <?php if ($tglAwal === $tglAkhir || $tglAkhir === '') : ?>
              <?= esc($tglAwal); ?>
            <?php else : ?>
              <?= esc($tglAwal); ?><br><small class="text-muted">s/d <?= esc($tglAkhir); ?></small>
            <?php endif; ?>
          </td>

          <!-- MAK & Sumber Anggaran -->
          <td>
            <div class="font-weight-bold text-dark" style="font-size: 0.77rem;"><?= !empty($data['mata_anggaran']) ? esc($data['mata_anggaran']) : '-'; ?></div>
            <span class="sn-sumber-pill <?= $sumberAnggaran === 'RPL' ? 'sn-sumber-rpl' : 'sn-sumber-apbn'; ?>">
              <?= esc($sumberAnggaran); ?>
            </span>
          </td>

          <!-- Nama Kegiatan -->
          <td title="<?= esc($data['nama_kegiatan'] ?? '', 'attr'); ?>">
            <div class="font-weight-bold" style="color: #0f172a; line-height: 1.35;">
              <?= esc(mb_strimwidth((string) ($data['nama_kegiatan'] ?? ''), 0, 64, '...')); ?>
            </div>
          </td>

          <!-- Tempat Perjalanan -->
          <td title="<?= esc($data['tempat_perjalanan'] ?? '', 'attr'); ?>">
            <i class="fas fa-map-marker-alt text-danger mr-1" style="opacity:0.75;"></i>
            <?= esc(mb_strimwidth((string) ($data['tempat_perjalanan'] ?? ''), 0, 40, '...')); ?>
          </td>

          <!-- Jumlah Peserta -->
          <td class="text-center text-nowrap">
            <span class="badge badge-light border px-2 py-1" style="font-size: 0.74rem; color: #0f766e;">
              <i class="fas fa-users mr-1"></i><?= (int) ($data['jumlah_peserta'] ?? 0); ?>
            </span>
          </td>

          <!-- Total Nominal -->
          <td class="text-right font-weight-bold text-nowrap" style="color: #0f172a;">
            Rp <?= number_format((float) ($data['total_perjalanan'] ?? 0), 0, ',', '.'); ?>
          </td>

          <!-- Pembuat / Akses Data -->
          <td>
            <small class="font-weight-bold text-muted"><?= esc(name_capitalize((string) ($data['nama'] ?? ''))); ?></small>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  $(function() {
    if (typeof $.fn.DataTable === 'undefined') {
      return;
    }

    if ($.fn.DataTable.isDataTable('#example11')) {
      $('#example11').DataTable().destroy();
    }

    var allowedLengths = [10, 20, 50, 100, -1];
    var savedLen = 20;
    try {
      var storedLen = parseInt(window.localStorage.getItem('sinadi_page_length') || '', 10);
      if (allowedLengths.indexOf(storedLen) !== -1) {
        savedLen = storedLen;
      }
    } catch (e) {}
    if (window.__sinadiPageLength !== undefined && allowedLengths.indexOf(window.__sinadiPageLength) !== -1) {
      savedLen = window.__sinadiPageLength;
    }
    window.__sinadiPageLength = savedLen;
    $('#snPageLength').val(String(savedLen));

    var table = $('#example11').DataTable({
      dom: "t<'row mt-2 align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      paging: true,
      pageLength: savedLen,
      lengthMenu: [[10, 20, 50, 100, -1], ['10 baris', '20 baris', '50 baris', '100 baris', 'Semua baris']],
      lengthChange: false,
      searching: true,
      info: true,
      autoWidth: false,
      deferRender: true,
      order: [],
      columnDefs: [
        {
          targets: [1],
          visible: false,
          searchable: false
        },
        {
          targets: [0, 3, 4, 5, 6, 7, 8, 9, 10],
          className: 'details-control'
        },
        {
          targets: [2],
          orderable: false
        }
      ],
      language: {
        search: 'Cari:',
        info: 'Menampilkan _START_–_END_ dari _TOTAL_ dokumen',
        infoEmpty: 'Tidak ada dokumen perjalanan dinas',
        infoFiltered: '(disaring dari _MAX_ total dokumen)',
        zeroRecords: 'Tidak ada dokumen perjalanan dinas yang cocok',
        paginate: {
          previous: '&laquo;',
          next: '&raquo;'
        }
      }
    });

    // Input pencarian terintegrasi di bar filter status
    $('#snTableSearch').off('input keyup search').on('input keyup search', function() {
      table.search(this.value).draw();
    });

    // Pemilih jumlah baris yang ditampilkan sekaligus
    $('#snPageLength').off('change').on('change', function() {
      var lenVal = parseInt(this.value, 10);
      if (allowedLengths.indexOf(lenVal) === -1) {
        lenVal = 20;
      }
      window.__sinadiPageLength = lenVal;
      try {
        window.localStorage.setItem('sinadi_page_length', String(lenVal));
      } catch (e) {}
      table.page.len(lenVal).draw();
    });

    // Filter cepat berdasarkan pill status surat
    $('#snStatusFilterBar').off('click', '.sn-status-pill').on('click', '.sn-status-pill', function(e) {
      e.preventDefault();
      $('#snStatusFilterBar .sn-status-pill').removeClass('active');
      $(this).addClass('active');

      var status = String($(this).attr('data-status') || '').trim();
      if (status === '') {
        table.column(3).search('').draw();
      } else {
        var escaped = $.fn.dataTable.util.escapeRegex(status);
        table.column(3).search('^\\s*' + escaped + '\\s*$', true, false).draw();
      }
    });

    // Ekspansi rincian peserta tanpa blocking full-screen SweetAlert
    $('#example11 tbody').off('click', 'td.details-control').on('click', 'td.details-control', function(e) {
      if ($(e.target).closest('a, button, form, input').length) {
        return;
      }
      var tr = $(this).closest('tr');
      var row = table.row(tr);

      if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
      } else {
        var rowData = row.data();
        if (!rowData) return;
        var id = rowData[1];

        row.child('<div class="p-3 text-center text-muted small"><i class="fas fa-spinner fa-spin mr-2 text-info"></i>Memuat rincian peserta perjalanan dinas...</div>').show();
        tr.addClass('shown');

        getDataSinadi(id)
          .then(function(response) {
            if (response && response.data) {
              row.child(response.data).show();
            }
          })
          .catch(function() {
            row.child.hide();
            tr.removeClass('shown');
          });
      }
    });
  });
</script>
