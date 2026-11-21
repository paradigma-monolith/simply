<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$pemindahanRows   = $pemindahan ?? [];
$pemeliharaanRows = $pemeliharaan ?? [];
$isAdmin          = (session()->get('role') === 'Admin');
?>

<style>
  .abs-req-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }
  .abs-req-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding: 12px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
  }
  .abs-req-tab {
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 7px 14px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    text-decoration: none !important;
    transition: all 0.15s ease;
  }
  .abs-req-tab:hover {
    border-color: #0d9488;
    color: #0f766e;
  }
  .abs-req-tab.active {
    background: #0d9488;
    border-color: #0d9488;
    color: #ffffff !important;
  }
  .abs-req-cnt {
    background: rgba(15, 23, 42, 0.08);
    padding: 1px 7px;
    border-radius: 999px;
    font-size: 0.71rem;
  }
  .abs-req-tab.active .abs-req-cnt {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
  }
  .abs-req-body {
    padding: 16px;
  }
  .abs-move-item {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 10px;
    background: #ffffff;
    transition: all 0.15s ease;
  }
  .abs-move-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
  }
  .abs-move-head {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 8px;
  }
  .abs-move-table th {
    background: #f8fafc;
    font-size: 0.72rem;
    text-transform: uppercase;
    color: #475569;
  }
  .abs-move-table td {
    font-size: 0.79rem;
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="abs-req-card">
      <div class="abs-req-tabs nav" role="tablist">
        <a class="abs-req-tab active" id="tab-pemindahan-link" data-toggle="pill" href="#tab-pemindahan" role="tab">
          <i class="fas fa-exchange-alt"></i> Riwayat Pemindahan Inventaris
          <span class="abs-req-cnt"><?= count($pemindahanRows); ?></span>
        </a>
        <a class="abs-req-tab <?= $isAdmin ? '' : 'disabled text-muted'; ?>"
           id="tab-pemeliharaan-link"
           <?= $isAdmin ? 'data-toggle="pill" href="#tab-pemeliharaan"' : 'href="javascript:void(0)"'; ?>
           role="tab">
          <i class="fas fa-tools"></i> Permintaan Pemeliharaan Inventaris
          <span class="abs-req-cnt"><?= count($pemeliharaanRows); ?></span>
          <?php if (!$isAdmin) : ?>
            <i class="fas fa-lock text-danger ml-1" title="Khusus Administrator"></i>
          <?php endif; ?>
        </a>
      </div>

      <div class="abs-req-body">
        <div class="tab-content">
          <!-- Tab 1: Pemindahan Inventaris -->
          <div class="tab-pane fade show active" id="tab-pemindahan" role="tabpanel">
            <?php if (empty($pemindahanRows)) : ?>
              <div class="text-center text-muted py-5">
                <i class="fas fa-exchange-alt fa-2x mb-2" style="opacity:0.4;"></i>
                <div>Belum ada riwayat permintaan pemindahan inventaris.</div>
              </div>
            <?php else : ?>
              <?php foreach ($pemindahanRows as $data) : ?>
                <?php
                $isDenied = (($data['status'] ?? '') === 'Ditolak');
                $array = \App\Models\AbsLokasiModel::safeUnserialize($data['data_barang'] ?? '');
                $dataAsal = \App\Models\AbsLokasiModel::safeUnserialize($data['data_asal'] ?? '');
                $dataTujuan = \App\Models\AbsLokasiModel::safeUnserialize($data['data_tujuan'] ?? '');
                $createdAt = (string) ($data['created_at'] ?? '');
                ?>
                <div class="abs-move-item" style="border-left: 4px solid <?= $isDenied ? '#ef4444' : '#10b981'; ?>;">
                  <div class="abs-move-head">
                    <div>
                      <?php if ($isDenied) : ?>
                        <span class="badge badge-danger mr-1"><i class="fas fa-ban mr-1"></i> Ditolak</span>
                        <strong class="text-dark">Percobaan permintaan pemindahan barang tidak terautorisasi</strong>
                      <?php else : ?>
                        <span class="badge badge-success mr-1"><i class="fas fa-check-circle mr-1"></i> Disetujui Sistem</span>
                        <strong class="text-dark"><?= esc(name_capitalize((string) ($data['nama'] ?? 'Pegawai'))); ?></strong>
                        <span class="text-muted">memindahkan lokasi barang inventaris</span>
                      <?php endif; ?>
                    </div>
                    <small class="text-muted">
                      <i class="far fa-clock mr-1"></i>
                      <?= esc($createdAt); ?>
                      <?php if ($createdAt !== '') : ?>
                        (<?= esc(selisi_waktu($createdAt)); ?> lalu)
                      <?php endif; ?>
                    </small>
                  </div>

                  <?php if (!$isDenied) : ?>
                    <div class="row">
                      <div class="col-md-5 mb-2 mb-md-0">
                        <div class="p-2 rounded bg-light border" style="font-size: 0.78rem;">
                          <?php
                          $dataLabel = ['Nama Barang', 'Kode Barang', 'NUP', 'Merek', 'Kode Satker'];
                          foreach ($dataLabel as $k) :
                            if (!empty($array[$k])) :
                          ?>
                            <div><span class="text-muted"><?= esc($k); ?>:</span> <strong><?= esc((string) $array[$k]); ?></strong></div>
                          <?php
                            endif;
                          endforeach;
                          ?>
                        </div>
                      </div>
                      <div class="col-md-7">
                        <table class="table table-sm table-bordered abs-move-table mb-0">
                          <thead>
                            <tr>
                              <th width="50%">Lokasi Asal</th>
                              <th width="50%">Lokasi Tujuan</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($dataAsal as $idx => $valAsal) : ?>
                              <tr>
                                <td><?= esc((string) $valAsal); ?></td>
                                <td class="font-weight-bold text-success">
                                  <i class="fas fa-arrow-right mr-1 text-muted"></i>
                                  <?= esc((string) ($dataTujuan[$idx] ?? '-')); ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <!-- Tab 2: Pemeliharaan Inventaris -->
          <div class="tab-pane fade" id="tab-pemeliharaan" role="tabpanel">
            <?php if ($isAdmin) : ?>
              <div class="table-responsive">
                <table id="tableWithoutExporting" class="table table-sm table-bordered table-hover mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th width="5%" class="text-center">No</th>
                      <th width="34%">Data Inventaris</th>
                      <th width="16%" class="text-center">Status Pemeliharaan</th>
                      <th>Catatan Validasi</th>
                      <th width="9%" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $nomor = 1; ?>
                    <?php foreach ($pemeliharaanRows as $data) : ?>
                      <?php
                      $array = \App\Models\AbsLokasiModel::safeUnserialize($data['data_barang'] ?? '');
                      $st = strtolower(trim((string) ($data['status'] ?? 'pending')));
                      $badgeCls = 'badge-secondary';
                      if ($st === 'selesai diperbaiki') $badgeCls = 'badge-success';
                      elseif ($st === 'proses pemeliharaan') $badgeCls = 'badge-info';
                      elseif ($st === 'rusak') $badgeCls = 'badge-danger';
                      elseif ($st === 'pending') $badgeCls = 'badge-warning text-dark';
                      ?>
                      <tr>
                        <td class="text-center font-weight-bold align-middle"><?= $nomor++; ?></td>
                        <td class="align-middle" style="font-size: 0.79rem;">
                          <div class="font-weight-bold text-dark"><?= esc((string) ($array['Nama Barang'] ?? '-')); ?></div>
                          <div class="text-muted">
                            Kode: <strong><?= esc((string) ($array['Kode Barang'] ?? '-')); ?></strong> &bull;
                            NUP: <strong><?= esc((string) ($array['NUP'] ?? '-')); ?></strong> &bull;
                            Merek: <strong><?= esc((string) ($array['Merek'] ?? '-')); ?></strong>
                          </div>
                        </td>
                        <td class="text-center align-middle">
                          <span class="badge <?= $badgeCls; ?> px-2 py-1" style="font-size:0.74rem;">
                            <?= esc(ucwords((string) ($data['status'] ?? 'pending'))); ?>
                          </span>
                        </td>
                        <td class="align-middle" style="font-size: 0.8rem;">
                          <?= !empty($data['validasi']) ? $data['validasi'] : '<span class="text-muted font-italic">Belum divalidasi</span>'; ?>
                        </td>
                        <td class="text-center align-middle">
                          <a href="/abs/pengajuan_pemeliharaan/<?= (int) $data['id_abs_pengajuan_pemeliharaan']; ?>"
                             class="btn btn-xs btn-info font-weight-bold px-2 py-1"
                             title="Proses Validasi Pemeliharaan">
                            <i class="fas fa-check-square mr-1"></i> Proses
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>
