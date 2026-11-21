<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$rows = is_array($getData ?? null) ? $getData : [];
$pegawaiMap = is_array($pegawaiMap ?? null) ? $pegawaiMap : [];
$isAdminRole = session()->get('role') === 'Admin';
$currentUsername = (string) session()->get('username');

// Hitung ringkasan status peminjaman dalam satu iterasi
$totalCount = count($rows);
$disetujuiCount = 0;
$menungguCount = 0;
$umumCount = 0;

foreach ($rows as $r) {
  $jenisKendaraan = (string) ($r['kendaraan'] ?? 'Kendaraan Kantor');
  $namaMobil = trim((string) ($r['nama_kendaraan'] ?? ''));
  if ($jenisKendaraan !== 'Kendaraan Kantor' || (int) ($r['id_kendaraan_kantor_daftar'] ?? 0) === -1) {
    $umumCount++;
  } elseif ($namaMobil !== '') {
    $disetujuiCount++;
  } else {
    $menungguCount++;
  }
}
?>

<style>
  .kk-history {
    --kk-primary: #0d9488;
    --kk-ink: #0f172a;
    --kk-muted: #64748b;
    --kk-border: rgba(15, 23, 42, 0.08);
  }
  .kk-history .kk-kpi-card {
    background: #ffffff;
    border: 1px solid var(--kk-border);
    border-radius: 14px;
    padding: 15px 18px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    display: flex;
    align-items: center;
    gap: 14px;
    height: 100%;
    cursor: pointer;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
  }
  .kk-history .kk-kpi-card:hover,
  .kk-history .kk-kpi-card.is-active {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
    border-color: rgba(13, 148, 136, 0.45);
  }
  .kk-history .kk-kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
  }
  .kk-history .kk-kpi-num {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--kk-ink);
    line-height: 1.1;
  }
  .kk-history .kk-kpi-label {
    font-size: 0.76rem;
    font-weight: 600;
    color: var(--kk-muted);
    margin-top: 2px;
  }
  .kk-history .kk-surface-card {
    background: #ffffff;
    border: 1px solid var(--kk-border);
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    overflow: hidden;
  }
  .kk-history .kk-vehicle-box {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 4px 0;
  }
  .kk-history .kk-vehicle-thumb {
    width: 84px !important;
    height: 56px !important;
    border-radius: 8px;
    object-fit: cover !important;
    background: #f1f5f9;
    border: 1px solid rgba(15, 23, 42, 0.08);
    flex-shrink: 0;
  }
  .kk-history .kk-plate-mini {
    display: inline-block;
    background: #0f172a;
    color: #ffffff;
    font-family: 'Consolas', monospace;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 5px;
    letter-spacing: 0.5px;
  }
  .kk-history .kk-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 999px;
  }
  .kk-history .kk-status-pill.st-approved { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
  .kk-history .kk-status-pill.st-pending  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
  .kk-history .kk-status-pill.st-public   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
  .kk-history .kk-passenger-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .kk-history .kk-passenger-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.76rem;
    font-weight: 600;
    color: #334155;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 3px 10px;
  }
  .kk-history .kk-passenger-pill i {
    color: #0d9488;
    font-size: 0.7rem;
  }
  .kk-history .table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom-width: 1px;
    vertical-align: middle;
  }
  .kk-history .table tbody td {
    vertical-align: middle !important;
  }
</style>

<section class="content kk-history pb-4">
  <div class="container-fluid">

    <!-- Ringkasan Status & Filter Cepat -->
    <div class="row mb-3" id="kkStatusFilterCards">
      <div class="col-lg-3 col-sm-6 mb-2 mb-lg-0">
        <div class="kk-kpi-card is-active" data-filter="">
          <div class="kk-kpi-icon" style="background: #f0fdfa; color: #0d9488;">
            <i class="fas fa-clipboard-list"></i>
          </div>
          <div>
            <div class="kk-kpi-num"><?= $totalCount; ?></div>
            <div class="kk-kpi-label">Semua Riwayat</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 mb-2 mb-lg-0">
        <div class="kk-kpi-card" data-filter="Disetujui">
          <div class="kk-kpi-icon" style="background: #ecfdf5; color: #059669;">
            <i class="fas fa-car-side"></i>
          </div>
          <div>
            <div class="kk-kpi-num"><?= $disetujuiCount; ?></div>
            <div class="kk-kpi-label">Kendaraan Disetujui</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 mb-2 mb-sm-0">
        <div class="kk-kpi-card" data-filter="Menunggu Verifikasi">
          <div class="kk-kpi-icon" style="background: #fffbeb; color: #d97706;">
            <i class="fas fa-clock"></i>
          </div>
          <div>
            <div class="kk-kpi-num"><?= $menungguCount; ?></div>
            <div class="kk-kpi-label">Menunggu Verifikasi</div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="kk-kpi-card" data-filter="Kendaraan Umum">
          <div class="kk-kpi-icon" style="background: #fef2f2; color: #dc2626;">
            <i class="fas fa-bus"></i>
          </div>
          <div>
            <div class="kk-kpi-num"><?= $umumCount; ?></div>
            <div class="kk-kpi-label">Kendaraan Umum</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card kk-surface-card">
      <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between py-3" style="gap: 10px;">
        <div>
          <h5 class="mb-0 font-weight-bold" style="color: #0f172a; font-size: 1rem;">
            <i class="fas fa-calendar-check mr-2" style="color: #0d9488;"></i>Riwayat Peminjaman Kendaraan Kantor
          </h5>
        </div>
        <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
          <a href="/kendaraan_kantor" target="_blank" class="btn btn-sm btn-info font-weight-bold px-3" style="border-radius: 8px; background: #0d9488; border-color: #0d9488;">
            <i class="fas fa-plus-circle mr-1"></i> Portal Pengajuan
          </a>
          <a href="/kendaraan_kantor/daftar_kendaraan" class="btn btn-sm btn-outline-secondary px-3" style="border-radius: 8px;">
            <i class="fas fa-car mr-1"></i> Daftar Kendaraan
          </a>
        </div>
      </div>
      <div class="card-body">
        <table id="tableWithoutExporting" class="table table-hover table-bordered mb-0">
          <thead>
            <tr class="text-center">
              <th style="width: 42px;">No</th>
              <th class="text-left" style="min-width: 250px;">Kendaraan &amp; Jadwal</th>
              <th class="text-left" style="min-width: 220px;">Tujuan &amp; Kegiatan</th>
              <th class="text-left" style="min-width: 220px;">Penumpang</th>
              <th style="width: 110px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $nomor = 1;
            foreach ($rows as $data):
              $tanggal = json_decode((string) ($data['tanggal'] ?? '[]'), true);
              if (!is_array($tanggal) || empty($tanggal)) {
                $tanggal = [date('Y-m-d')];
              }
              $penumpangIds = json_decode((string) ($data['penumpang'] ?? '[]'), true);
              if (!is_array($penumpangIds)) {
                $penumpangIds = [];
              }

              $jenisKendaraan = (string) ($data['kendaraan'] ?? 'Kendaraan Kantor');
              $namaMobil = trim((string) ($data['nama_kendaraan'] ?? ''));
              $platNomor = trim((string) ($data['plat_nomor'] ?? ''));
              $imgFile = trim((string) ($data['gambar'] ?? ''));
              $imgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
              $peminjamName = name_capitalize((string) ($data['peminjam'] ?? '-'));
              $jmlHari = count($tanggal);
              $tglRangeText = $jmlHari > 1
                ? shortdate_indo3($tanggal[0]) . ' — ' . shortdate_indo3($tanggal[$jmlHari - 1])
                : shortdate_indo3($tanggal[0]);
            ?>
              <tr>
                <td class="text-center font-weight-bold text-muted"><?= $nomor++; ?></td>
                <td>
                  <?php if ($jenisKendaraan === 'Kendaraan Kantor' && (int) ($data['id_kendaraan_kantor_daftar'] ?? 0) !== -1): ?>
                    <?php if ($namaMobil !== ''): ?>
                      <div class="kk-vehicle-box">
                        <img src="<?= $imgUrl; ?>"
                             width="84"
                             height="56"
                             style="width:84px;height:56px;object-fit:cover;"
                             loading="lazy"
                             decoding="async"
                             onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
                             class="kk-vehicle-thumb"
                             alt="<?= esc($namaMobil, 'attr'); ?>">
                        <div style="min-width: 0;">
                          <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 6px;">
                            <span class="kk-status-pill st-approved"><i class="fas fa-check-circle"></i> Disetujui</span>
                            <span class="badge badge-light border text-muted"><?= $jmlHari; ?> hari</span>
                          </div>
                          <div class="font-weight-bold" style="color: #0f172a; font-size: 0.9rem;">
                            <?= esc($namaMobil); ?>
                            <?php if ($platNomor !== ''): ?>
                              <span class="kk-plate-mini ml-1"><?= esc($platNomor); ?></span>
                            <?php endif; ?>
                          </div>
                          <div class="small text-muted mt-1">
                            <i class="far fa-user text-success mr-1"></i>Peminjam: <strong class="text-success"><?= esc($peminjamName); ?></strong>
                          </div>
                          <div class="small text-muted">
                            <i class="far fa-calendar-alt mr-1"></i><?= esc($tglRangeText); ?>
                          </div>
                        </div>
                      </div>
                    <?php else: ?>
                      <div>
                        <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 6px;">
                          <span class="kk-status-pill st-pending"><i class="fas fa-clock"></i> Menunggu Verifikasi</span>
                          <span class="badge badge-light border text-muted"><?= $jmlHari; ?> hari</span>
                        </div>
                        <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">Kendaraan Belum Ditentukan</div>
                        <div class="small text-muted mt-1">
                          <i class="far fa-user text-success mr-1"></i>Peminjam: <strong class="text-success"><?= esc($peminjamName); ?></strong>
                        </div>
                        <div class="small text-muted">
                          <i class="far fa-calendar-alt mr-1"></i><?= esc($tglRangeText); ?>
                        </div>
                      </div>
                    <?php endif; ?>
                  <?php else: ?>
                    <div>
                      <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 6px;">
                        <span class="kk-status-pill st-public"><i class="fas fa-bus"></i> Kendaraan Umum</span>
                        <span class="badge badge-light border text-muted"><?= $jmlHari; ?> hari</span>
                      </div>
                      <div class="font-weight-bold text-danger" style="font-size: 0.88rem;">Disarankan Menggunakan Kendaraan Umum</div>
                      <?php if (!empty($data['keterangan_verifikator'])): ?>
                        <div class="small text-muted font-italic">"<?= esc($data['keterangan_verifikator']); ?>"</div>
                      <?php endif; ?>
                      <div class="small text-muted mt-1">
                        <i class="far fa-user text-success mr-1"></i>Peminjam: <strong class="text-success"><?= esc($peminjamName); ?></strong>
                      </div>
                      <div class="small text-muted">
                        <i class="far fa-calendar-alt mr-1"></i><?= esc($tglRangeText); ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </td>

                <td>
                  <div class="mb-1">
                    <span class="text-muted small font-weight-bold text-uppercase d-block" style="font-size: 0.7rem;">Tujuan</span>
                    <span class="font-weight-bold" style="color: #0f172a; font-size: 0.86rem;"><?= esc($data['tujuan'] ?? '-'); ?></span>
                  </div>
                  <div>
                    <span class="text-muted small font-weight-bold text-uppercase d-block" style="font-size: 0.7rem;">Kegiatan</span>
                    <span class="text-secondary" style="font-size: 0.83rem;"><?= esc($data['keterangan'] ?? '-'); ?></span>
                  </div>
                </td>

                <td>
                  <?php if (!empty($penumpangIds)): ?>
                    <ul class="kk-passenger-pills">
                      <?php foreach ($penumpangIds as $pid):
                        $pidInt = (int) $pid;
                        $pName = $pegawaiMap[$pidInt] ?? ('Pegawai #' . $pidInt);
                      ?>
                        <li class="kk-passenger-pill">
                          <i class="fas fa-user"></i>
                          <span><?= esc($pName); ?></span>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  <?php else: ?>
                    <span class="text-muted small font-italic">-</span>
                  <?php endif; ?>
                </td>

                <td class="text-center">
                  <?php if ($isAdminRole || ($data['username'] ?? '') === $currentUsername): ?>
                    <div class="d-inline-flex align-items-center" style="gap: 6px;">
                      <a href="/kendaraan_kantor/update_riwayat/<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>"
                         class="btn btn-sm btn-warning text-dark font-weight-bold"
                         style="border-radius: 8px;"
                         title="Edit Peminjaman">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="/kendaraan_kantor/dlt_riwayat/<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>" method="post" class="d-inline formHapusData m-0">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;" title="Hapus Peminjaman">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  <?php else: ?>
                    <span class="badge badge-light border text-muted px-2 py-1" title="Hanya peminjam dan admin yang dapat mengubah">
                      <i class="fas fa-lock mr-1"></i> Terkunci
                    </span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<script>
  $(document).ready(function() {
    $('#kkStatusFilterCards').on('click', '.kk-kpi-card', function() {
      $('#kkStatusFilterCards .kk-kpi-card').removeClass('is-active');
      $(this).addClass('is-active');
      var filterTerm = $(this).data('filter') || '';
      if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tableWithoutExporting')) {
        $('#tableWithoutExporting').DataTable().search(filterTerm).draw();
      }
    });
  });
</script>

<?= $this->endSection(); ?>
