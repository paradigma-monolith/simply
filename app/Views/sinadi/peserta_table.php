<?php $listPeserta = $peserta ?? []; ?>

<style>
  .sn-peserta-wrap {
    background: #f8fafc;
    border-radius: 10px;
    padding: 10px;
    border: 1px solid #e2e8f0;
  }
  .sn-peserta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 10px;
  }
  .sn-peserta-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #0d9488;
    border-radius: 10px;
    padding: 11px 13px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
  }
  .sn-peserta-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
    padding-bottom: 7px;
    margin-bottom: 8px;
    border-bottom: 1px dashed #e2e8f0;
  }
  .sn-peserta-name {
    font-size: 0.83rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.25;
  }
  .sn-peserta-nip {
    font-size: 0.72rem;
    color: #0d9488;
    font-weight: 600;
  }
  .sn-peserta-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px 10px;
    font-size: 0.74rem;
    margin-bottom: 8px;
  }
  .sn-meta-lbl {
    font-size: 0.67rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
  }
  .sn-meta-val {
    color: #1e293b;
    font-weight: 600;
  }
  .sn-cost-bar {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 7px 10px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 6px;
    font-size: 0.71rem;
  }
  .sn-cost-total {
    color: #0f766e;
    font-weight: 800;
  }
  @media (max-width: 575.98px) {
    .sn-peserta-grid {
      grid-template-columns: 1fr;
    }
    .sn-cost-bar {
      grid-template-columns: repeat(2, 1fr);
    }
  }
</style>

<div class="sn-peserta-wrap">
  <div class="d-flex align-items-center justify-content-between mb-2 px-1">
    <span class="small font-weight-bold text-dark">
      <i class="fas fa-users text-info mr-1"></i>Rincian Peserta &amp; Biaya Perjalanan (<?= count($listPeserta); ?> Orang)
    </span>
  </div>

  <div class="sn-peserta-grid">
    <?php foreach ($listPeserta as $data) : ?>
      <?php
      $isDalam = (($data['kota'] ?? '') === ($data['kota_asal'] ?? ''));
      $tglPergi = (string) ($data['tgl_pergi'] ?? '');
      $tglPulang = (string) ($data['tgl_pulang'] ?? '');
      $tglLabel = ($tglPergi === $tglPulang || $tglPulang === '') ? $tglPergi : ($tglPergi . ' s/d ' . $tglPulang);
      ?>
      <div class="sn-peserta-card">
        <div class="sn-peserta-head">
          <div>
            <div class="sn-peserta-name"><?= esc($data['nama_pegawai'] ?? ''); ?></div>
            <div class="sn-peserta-nip">NIP. <?= esc($data['nip_pegawai'] ?? '-'); ?></div>
          </div>
          <?php if (!empty($data['golongan'])) : ?>
            <span class="badge badge-light border text-dark" style="font-size: 0.7rem;">Gol. <?= esc($data['golongan']); ?></span>
          <?php endif; ?>
        </div>

        <div class="sn-peserta-meta">
          <div>
            <div class="sn-meta-lbl">Rute &amp; Tanggal</div>
            <div class="sn-meta-val">
              <?= $isDalam ? esc($data['kota'] ?? '') : esc(($data['kota_asal'] ?? '') . ' → ' . ($data['kota'] ?? '')); ?>
              <div class="text-muted font-weight-normal" style="font-size: 0.69rem;"><?= esc($tglLabel); ?></div>
            </div>
          </div>
          <div>
            <div class="sn-meta-lbl">No. Surat Tugas / SPPD</div>
            <div class="sn-meta-val">
              ST: <?= esc($data['no_surat_tugas'] ?? '-'); ?>
              <?php if (!empty($data['no_sppd']) && $data['no_sppd'] !== '-') : ?>
                <div class="text-muted font-weight-normal" style="font-size: 0.69rem;">SPPD: <?= esc($data['no_sppd']); ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="sn-cost-bar">
          <div>
            <div class="sn-meta-lbl">Transport <?= !empty($data['transport_hari']) ? '(x' . (int) $data['transport_hari'] . ')' : ''; ?></div>
            <div class="sn-meta-val">
              <?= (float) ($data['transport_jumlah'] ?? 0) > 0 ? 'Rp ' . number_format((float) $data['transport_jumlah'], 0, ',', '.') : '-'; ?>
            </div>
          </div>
          <div>
            <div class="sn-meta-lbl">Uang Harian <?= !empty($data['harian_hari']) ? '(x' . (int) $data['harian_hari'] . ')' : ''; ?></div>
            <div class="sn-meta-val">
              <?= (float) ($data['harian_jumlah'] ?? 0) > 0 ? 'Rp ' . number_format((float) $data['harian_jumlah'], 0, ',', '.') : '-'; ?>
            </div>
          </div>
          <div>
            <div class="sn-meta-lbl">Penginapan <?= !empty($data['penginapan_hari']) ? '(x' . (int) $data['penginapan_hari'] . ')' : ''; ?></div>
            <div class="sn-meta-val">
              <?= (float) ($data['penginapan_jumlah'] ?? 0) > 0 ? 'Rp ' . number_format((float) $data['penginapan_jumlah'], 0, ',', '.') : '-'; ?>
            </div>
          </div>
          <div>
            <div class="sn-meta-lbl">Total Transfer</div>
            <div class="sn-cost-total">
              Rp <?= number_format((float) ($data['jumlah_transfer'] ?? 0), 0, ',', '.'); ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
