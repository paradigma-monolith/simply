<?php
$rows = is_array($getData ?? null) ? $getData : [];
$pegawaiMap = is_array($pegawaiMap ?? null) ? $pegawaiMap : [];
?>

<style>
  .kk-portal-card {
    background: rgba(255, 255, 255, 0.97);
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    overflow: hidden;
    margin-bottom: 12px;
  }
  .kk-portal-head {
    background: linear-gradient(135deg, #0b7285, #0d9488);
    color: #ffffff;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 700;
    font-size: 0.82rem;
  }
  .kk-portal-body {
    padding: 12px 14px;
  }
  .kk-portal-thumb {
    width: 86px !important;
    height: 58px !important;
    object-fit: cover !important;
    border-radius: 9px;
    border: 1px solid rgba(15, 23, 42, 0.1);
    background: #f1f5f9;
    flex-shrink: 0;
  }
  .kk-portal-plate {
    display: inline-block;
    background: #0f172a;
    color: #ffffff;
    font-family: 'Consolas', monospace;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 5px;
  }
  .kk-portal-meta-line {
    font-size: 0.78rem;
    color: #475569;
    line-height: 1.35;
    margin-bottom: 2px;
  }
  .kk-portal-pax {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 5px;
  }
  .kk-portal-pax-badge {
    font-size: 0.7rem;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 1px 8px;
    font-weight: 600;
  }
  .kk-portal-foot {
    padding: 8px 14px;
    background: #f8fafc;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
  }
  @media (max-width: 575.98px) {
    .kk-portal-card {
      border-radius: 12px;
      margin-bottom: 10px;
    }
    .kk-portal-head {
      padding: 7px 11px;
      font-size: 0.76rem;
    }
    .kk-portal-body {
      padding: 10px 11px;
    }
    .kk-portal-thumb {
      width: 68px !important;
      height: 48px !important;
      border-radius: 7px;
    }
    .kk-portal-meta-line {
      font-size: 0.74rem;
    }
    .kk-portal-pax-badge {
      font-size: 0.66rem;
      padding: 1px 7px;
    }
    .kk-portal-foot {
      padding: 6px 11px;
    }
  }
</style>

<div class="container px-1 px-sm-3">
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
              <span><i class="far fa-calendar-alt me-1"></i><?= esc($tglLabel); ?></span>
              <?php if ($isPublicTransport): ?>
                <span class="badge bg-danger" style="font-size: 0.68rem;">Kendaraan Umum</span>
              <?php elseif ($hasVehicle): ?>
                <span class="badge bg-success" style="font-size: 0.68rem;">Disetujui</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark" style="font-size: 0.68rem;">Menunggu Atasan</span>
              <?php endif; ?>
            </div>
            <div class="kk-portal-body">
              <div class="d-flex align-items-start gap-2 mb-1">
                <?php if ($hasVehicle): ?>
                  <img src="<?= $imgUrl; ?>"
                       width="86"
                       height="58"
                       loading="lazy"
                       decoding="async"
                       onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
                       class="kk-portal-thumb"
                       alt="Kendaraan">
                <?php endif; ?>
                <div class="flex-grow-1" style="min-width: 0;">
                  <?php if ($hasVehicle): ?>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-1">
                      <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.88rem;"><?= esc($data['nama_kendaraan']); ?></h6>
                      <span class="kk-portal-plate"><?= esc($data['plat_nomor'] ?? '-'); ?></span>
                    </div>
                  <?php elseif ($isPublicTransport): ?>
                    <div class="alert alert-danger py-1 px-2 mb-2" style="font-size: 0.74rem;">
                      <strong><i class="fas fa-bus me-1"></i> Disarankan Kendaraan Umum</strong>
                      <?php if (!empty($data['keterangan_verifikator'])): ?>
                        <div>Alasan: <em>"<?= esc($data['keterangan_verifikator']); ?>"</em></div>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <div class="alert alert-warning py-1 px-2 mb-2" style="font-size: 0.74rem;">
                      <i class="fas fa-clock me-1"></i> <strong>Menunggu pemilihan kendaraan oleh Atasan.</strong>
                    </div>
                  <?php endif; ?>

                  <div class="kk-portal-meta-line"><strong>Tujuan:</strong> <?= esc($data['tujuan'] ?? '-'); ?></div>
                  <div class="kk-portal-meta-line"><strong>Kegiatan:</strong> <?= esc($data['keterangan'] ?? '-'); ?></div>
                </div>
              </div>

              <?php if (!empty($penumpangIds)): ?>
                <div class="border-top pt-2 mt-2">
                  <div class="kk-portal-pax">
                    <?php foreach ($penumpangIds as $pid):
                      $pName = $pegawaiMap[(int) $pid] ?? ('Pegawai #' . (int) $pid);
                    ?>
                      <span class="kk-portal-pax-badge"><i class="fas fa-user me-1 text-secondary" style="font-size: 0.6rem;"></i><?= esc($pName); ?></span>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="kk-portal-foot">
              <span class="small text-success fw-bold text-truncate" style="font-size: 0.74rem;">
                <i class="fas fa-user-check me-1"></i><?= esc(name_capitalize((string) ($data['nama'] ?? ''))); ?>
              </span>
              <?php if (($nip ?? '') == ($data['nip'] ?? '')): ?>
                <button class="btn btn-sm btn-danger py-1 px-2 batalkan-peminjaman-kendaraan" style="font-size: 0.74rem; border-radius: 7px;" data-id="<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>">
                  <i class="far fa-window-close me-1"></i> Batalkan
                </button>
              <?php else: ?>
                <span class="badge bg-secondary" style="font-size: 0.68rem;">Pegawai Lain</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-center py-4">
      <div class="p-3 bg-white rounded-4 shadow-sm d-inline-block">
        <i class="fas fa-clipboard-check fa-2x text-secondary mb-2 d-block"></i>
        <h6 class="fw-bold text-dark mb-1">Belum Ada Pengajuan</h6>
        <p class="text-muted small mb-0" style="font-size: 0.78rem;">Anda belum memiliki pengajuan peminjaman kendaraan aktif saat ini.</p>
      </div>
    </div>
  <?php endif; ?>
</div>
