<?php
$rows = is_array($getData ?? null) ? $getData : [];
$pegawaiMap = is_array($pegawaiMap ?? null) ? $pegawaiMap : [];
?>

<style>
  .kk-verif-card {
    background: rgba(255, 255, 255, 0.97);
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    overflow: hidden;
    margin-bottom: 12px;
  }
  .kk-verif-head {
    background: linear-gradient(135deg, #0b7285, #0d9488);
    color: #ffffff;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 700;
    font-size: 0.82rem;
  }
  .kk-verif-body {
    padding: 11px 14px;
  }
  .kk-verif-pax-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 2px 8px;
  }
  .kk-verif-actions {
    padding: 8px 14px;
    background: #f8fafc;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
    display: flex;
    gap: 8px;
    justify-content: flex-end;
  }
  @media (max-width: 575.98px) {
    .kk-verif-card {
      border-radius: 12px;
      margin-bottom: 10px;
    }
    .kk-verif-head {
      padding: 7px 11px;
      font-size: 0.76rem;
    }
    .kk-verif-body {
      padding: 9px 11px;
    }
    .kk-verif-pax-badge {
      font-size: 0.66rem;
      padding: 1px 7px;
    }
    .kk-verif-actions {
      padding: 7px 11px;
    }
    .kk-verif-actions .btn {
      flex: 1;
      font-size: 0.73rem;
      padding: 5px 8px;
    }
  }
</style>

<div class="container px-1 px-sm-3 py-2">
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
      $tglLabel = (shortdate_indo2($tanggal[0]) == shortdate_indo2($tanggal[count($tanggal) - 1]))
        ? shortdate_indo3($tanggal[0])
        : shortdate_indo3($tanggal[0]) . ' — ' . shortdate_indo3($tanggal[count($tanggal) - 1]);
      $tanggalRangeAttr = $tanggal[0] . ' - ' . $tanggal[count($tanggal) - 1];
      $peminjamName = name_capitalize((string) ($data['nama'] ?? ''));
    ?>
      <div class="row justify-content-md-center findContainer-1">
        <div class="col-lg-7 col-md-9">
          <div class="kk-verif-card">
            <div class="kk-verif-head">
              <span><i class="far fa-calendar-alt me-1"></i><?= esc($tglLabel); ?></span>
              <span class="badge bg-warning text-dark" style="font-size: 0.68rem;"><?= count($tanggal); ?> Hari</span>
            </div>
            <div class="kk-verif-body">
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-1" style="font-size: 0.78rem;">
                <span><strong>Peminjam:</strong> <span class="text-success fw-bold"><?= esc($peminjamName); ?></span></span>
                <span class="text-muted small"><i class="fas fa-users me-1"></i><?= count($penumpangIds); ?> penumpang</span>
              </div>
              <div style="font-size: 0.78rem; color: #0f172a;" class="mb-1">
                <strong>Tujuan:</strong> <?= esc($data['tujuan'] ?? '-'); ?>
              </div>
              <div style="font-size: 0.76rem; color: #64748b;">
                <strong>Kegiatan:</strong> <?= esc($data['keterangan'] ?? '-'); ?>
              </div>

              <?php if (!empty($penumpangIds)): ?>
                <div class="border-top pt-2 mt-2 d-flex flex-wrap gap-1">
                  <?php foreach ($penumpangIds as $pid):
                    $pName = $pegawaiMap[(int) $pid] ?? ('Pegawai #' . (int) $pid);
                  ?>
                    <span class="kk-verif-pax-badge"><i class="fas fa-user text-teal" style="color:#0d9488;font-size:0.6rem;"></i><?= esc($pName); ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="kk-verif-actions">
              <button class="btn btn-sm btn-outline-danger set-data-form-public-transport"
                      style="border-radius: 8px;"
                      data-id="<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>">
                <i class="fas fa-bus me-1"></i> Kendaraan Umum
              </button>
              <button class="btn btn-sm btn-warning fw-bold set-data-form"
                      style="border-radius: 8px;"
                      data-tanggal="<?= esc($tanggalRangeAttr, 'attr'); ?>"
                      data-penumpang="<?= esc(implode(',', $penumpangIds), 'attr'); ?>"
                      data-tujuan="<?= esc($data['tujuan'] ?? '', 'attr'); ?>"
                      data-keterangan="<?= esc($data['keterangan'] ?? '', 'attr'); ?>"
                      data-peminjam="<?= esc($peminjamName, 'attr'); ?>"
                      data-totalpenumpang="<?= count($penumpangIds); ?>"
                      data-id="<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>">
                <i class="fas fa-car me-1"></i> Pilih Kendaraan
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-center py-4">
      <div class="p-3 bg-white rounded-4 shadow-sm d-inline-block">
        <i class="fas fa-check-double fa-2x text-success mb-2 d-block"></i>
        <h6 class="fw-bold text-dark mb-1">Semua Pengajuan Telah Diverifikasi</h6>
        <p class="text-muted small mb-0" style="font-size: 0.78rem;">Belum ada pengajuan baru yang menunggu pemilihan kendaraan.</p>
      </div>
    </div>
  <?php endif; ?>
</div>
