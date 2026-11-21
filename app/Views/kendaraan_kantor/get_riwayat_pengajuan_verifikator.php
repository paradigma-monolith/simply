<?php
$rows = is_array($getData ?? null) ? $getData : [];
$pegawaiMap = is_array($pegawaiMap ?? null) ? $pegawaiMap : [];
?>

<style>
  .kk-verif-card {
    background: rgba(255, 255, 255, 0.96);
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
    overflow: hidden;
    margin-bottom: 16px;
  }
  .kk-verif-head {
    background: linear-gradient(135deg, #0b7285, #0d9488);
    color: #ffffff;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 700;
    font-size: 0.9rem;
  }
  .kk-verif-pax-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.74rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 3px 10px;
  }
</style>

<div class="container py-3">
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
              <span><i class="far fa-calendar-alt me-2"></i><?= esc($tglLabel); ?></span>
              <span class="badge bg-warning text-dark">Menunggu Verifikasi (<?= count($tanggal); ?> Hari)</span>
            </div>
            <div class="p-3">
              <div class="row">
                <div class="col-md-7 mb-2 mb-md-0">
                  <div class="small text-muted mb-1">
                    <strong>Peminjam:</strong> <span class="text-success fw-bold"><?= esc($peminjamName); ?></span>
                  </div>
                  <div class="small text-dark mb-1">
                    <strong>Tujuan:</strong> <?= esc($data['tujuan'] ?? '-'); ?>
                  </div>
                  <div class="small text-secondary">
                    <strong>Kegiatan:</strong> <?= esc($data['keterangan'] ?? '-'); ?>
                  </div>
                </div>
                <div class="col-md-5 border-start">
                  <small class="text-muted fw-bold d-block mb-1">Penumpang (<?= count($penumpangIds); ?> orang):</small>
                  <div class="d-flex flex-wrap gap-1">
                    <?php foreach ($penumpangIds as $pid):
                      $pName = $pegawaiMap[(int) $pid] ?? ('Pegawai #' . (int) $pid);
                    ?>
                      <span class="kk-verif-pax-badge"><i class="fas fa-user text-teal" style="color:#0d9488;font-size:0.68rem;"></i><?= esc($pName); ?></span>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="px-3 py-2 bg-light border-top d-flex flex-wrap justify-content-end gap-2">
              <button class="btn btn-sm btn-outline-danger set-data-form-public-transport"
                      data-id="<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>">
                <i class="fas fa-bus me-1"></i> Gunakan Kendaraan Umum
              </button>
              <button class="btn btn-sm btn-warning fw-bold set-data-form"
                      data-tanggal="<?= esc($tanggalRangeAttr, 'attr'); ?>"
                      data-penumpang="<?= esc(implode(',', $penumpangIds), 'attr'); ?>"
                      data-tujuan="<?= esc($data['tujuan'] ?? '', 'attr'); ?>"
                      data-keterangan="<?= esc($data['keterangan'] ?? '', 'attr'); ?>"
                      data-peminjam="<?= esc($peminjamName, 'attr'); ?>"
                      data-totalpenumpang="<?= count($penumpangIds); ?>"
                      data-id="<?= (int) $data['id_kendaraan_kantor_riwayat']; ?>">
                <i class="fas fa-car me-1"></i> Pilih Kendaraan Kantor
              </button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-center py-5">
      <div class="p-4 bg-white rounded-4 shadow-sm d-inline-block">
        <i class="fas fa-check-double fa-3x text-success mb-3 d-block"></i>
        <h5 class="fw-bold text-dark mb-1">Semua Pengajuan Telah Diverifikasi</h5>
        <p class="text-muted small mb-0">Belum ada pengajuan peminjaman kendaraan baru yang menunggu pemilihan kendaraan.</p>
      </div>
    </div>
  <?php endif; ?>
</div>
