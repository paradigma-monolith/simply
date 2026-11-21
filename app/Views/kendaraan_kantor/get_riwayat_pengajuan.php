<?php
$rows = is_array($getData ?? null) ? $getData : [];
$pegawaiMap = is_array($pegawaiMap ?? null) ? $pegawaiMap : [];
?>

<style>
  .kk-portal-card {
    background: rgba(255, 255, 255, 0.96);
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
    overflow: hidden;
    margin-bottom: 16px;
  }
  .kk-portal-head {
    background: linear-gradient(135deg, #0b7285, #0d9488);
    color: #ffffff;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 700;
    font-size: 0.9rem;
  }
  .kk-portal-thumb {
    width: 100px !important;
    height: 68px !important;
    object-fit: cover !important;
    border-radius: 10px;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: #f1f5f9;
    flex-shrink: 0;
  }
  .kk-portal-plate {
    display: inline-block;
    background: #0f172a;
    color: #ffffff;
    font-family: 'Consolas', monospace;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 6px;
  }
  .kk-portal-pax {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-top: 6px;
  }
  .kk-portal-pax-badge {
    font-size: 0.73rem;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 2px 9px;
    font-weight: 600;
  }
</style>

<div class="container">
  <?php if (!empty($rows)): ?>
    <?php foreach ($rows as $data):
      $tanggal = json_decode((string) ($data['tanggal'] ?? '[]'), true);
      if (!is_array($tanggal) || empty($tanggal)) {
        $tanggal = [date('Y-m-d')];
      }
      $penumpangIds = json_decode((string) ($data['penumpang'] ?? '[]'), true);
      if (!is_array($penumpangIds)) {
        $penumpangIds = [];
      }
      $imgFile = trim((string) ($data['gambar'] ?? ''));
      $imgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
      $tglLabel = (shortdate_indo2($tanggal[0]) == shortdate_indo2($tanggal[count($tanggal) - 1]))
        ? shortdate_indo3($tanggal[0])
        : shortdate_indo3($tanggal[0]) . ' — ' . shortdate_indo3($tanggal[count($tanggal) - 1]);
      $hasVehicle = !empty($data['nama_kendaraan']);
      $isPublicTransport = ($data['kendaraan'] ?? '') === 'Kendaraan Umum';
    ?>
      <div class="row justify-content-md-center">
        <div class="col-lg-7 col-md-9">
          <div class="kk-portal-card">
            <div class="kk-portal-head">
              <span><i class="far fa-calendar-alt me-2"></i><?= esc($tglLabel); ?></span>
              <?php if ($isPublicTransport): ?>
                <span class="badge bg-danger">Kendaraan Umum</span>
              <?php elseif ($hasVehicle): ?>
                <span class="badge bg-success">Disetujui</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark">Menunggu Atasan</span>
              <?php endif; ?>
            </div>
            <div class="p-3">
              <div class="d-flex align-items-start gap-3 mb-2">
                <?php if ($hasVehicle): ?>
                  <img src="<?= $imgUrl; ?>"
                       width="100"
                       height="68"
                       style="width:100px;height:68px;object-fit:cover;"
                       loading="lazy"
                       decoding="async"
                       onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
                       class="kk-portal-thumb"
                       alt="Kendaraan">
                <?php endif; ?>
                <div class="flex-grow-1">
                  <?php if ($hasVehicle): ?>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                      <h6 class="fw-bold mb-0 text-dark"><?= esc($data['nama_kendaraan']); ?></h6>
                      <span class="kk-portal-plate"><?= esc($data['plat_nomor'] ?? '-'); ?></span>
                    </div>
                  <?php elseif ($isPublicTransport): ?>
                    <div class="alert alert-danger py-2 px-3 mb-2 small">
                      <strong><i class="fas fa-bus me-1"></i> Disarankan Menggunakan Kendaraan Umum</strong>
                      <?php if (!empty($data['keterangan_verifikator'])): ?>
                        <div class="mt-1">Alasan: <em>"<?= esc($data['keterangan_verifikator']); ?>"</em></div>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <div class="alert alert-warning py-2 px-3 mb-2 small">
                      <i class="fas fa-clock me-1"></i> <strong>Unit kendaraan belum dipilih oleh Verifikator / Atasan.</strong>
                    </div>
                  <?php endif; ?>

                  <div class="small text-muted"><strong>Tujuan:</strong> <?= esc($data['tujuan'] ?? '-'); ?></div>
                  <div class="small text-muted"><strong>Kegiatan:</strong> <?= esc($data['keterangan'] ?? '-'); ?></div>
                  <div class="small text-success fw-bold mt-1">
                    <i class="fas fa-user-check me-1"></i>Peminjam: <?= esc(name_capitalize((string) ($data['nama'] ?? ''))); ?>
                  </div>
                </div>
              </div>

              <?php if (!empty($penumpangIds)): ?>
                <div class="border-top pt-2 mt-2">
                  <small class="text-muted fw-bold d-block">Penumpang (<?= count($penumpangIds); ?> orang):</small>
                  <div class="kk-portal-pax">
                    <?php foreach ($penumpangIds as $pid):
                      $pName = $pegawaiMap[(int) $pid] ?? ('Pegawai #' . (int) $pid);
                    ?>
                      <span class="kk-portal-pax-badge"><i class="fas fa-user me-1 text-secondary"></i><?= esc($pName); ?></span>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="px-3 py-2 bg-light border-top d-flex justify-content-end">
              <?php if (($nip ?? '') == ($data['nip'] ?? '')): ?>
                <button class="btn btn-sm btn-danger batalkan-peminjaman-kendaraan" data-id="<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>">
                  <i class="far fa-window-close me-1"></i> Batalkan Peminjaman
                </button>
              <?php else: ?>
                <span class="badge bg-secondary py-2 px-3"><i class="fas fa-info-circle me-1"></i> Diajukan oleh pegawai lain</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-center py-5">
      <div class="p-4 bg-white rounded-4 shadow-sm d-inline-block">
        <i class="fas fa-clipboard-check fa-3x text-secondary mb-3 d-block"></i>
        <h5 class="fw-bold text-dark mb-1">Belum Ada Pengajuan</h5>
        <p class="text-muted small mb-0">Anda belum memiliki pengajuan peminjaman kendaraan aktif saat ini.</p>
      </div>
    </div>
  <?php endif; ?>
</div>
