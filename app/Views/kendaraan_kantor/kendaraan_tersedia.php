<?php
$availableCars = is_array($getData ?? null) ? $getData : [];
$usedCars = is_array($notData ?? null) ? $notData : [];
?>

<style>
  .kk-avail-card {
    background: rgba(255, 255, 255, 0.96);
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    height: 100%;
    position: relative;
  }
  .kk-avail-card.is-ready:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 26px rgba(13, 148, 136, 0.22);
    border-color: #0d9488;
  }
  .kk-avail-card.is-busy {
    opacity: 0.58;
    filter: grayscale(40%);
    cursor: not-allowed;
  }
  .kk-avail-thumb {
    width: 100% !important;
    height: 150px !important;
    object-fit: cover !important;
    background: #f1f5f9;
  }
  .kk-avail-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  }
  .kk-avail-plate {
    display: inline-block;
    background: #0f172a;
    color: #ffffff;
    font-family: 'Consolas', monospace;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    letter-spacing: 0.8px;
  }
</style>

<div class="row">
  <?php foreach ($availableCars as $data):
    $imgFile = trim((string) ($data['gambar'] ?? ''));
    $imgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
  ?>
    <div class="col-lg-3 col-md-4 col-6 mb-4">
      <a href="javascript:void(0)"
         class="no-style getTheCar d-block h-100"
         data-id="<?= (int) $data['id_kendaraan_kantor_daftar']; ?>"
         data-kendaraan="<?= esc($data['nama_kendaraan'] ?? '', 'attr'); ?>"
         data-plat="<?= esc($data['plat_nomor'] ?? '', 'attr'); ?>">
        <div class="kk-avail-card is-ready">
          <span class="kk-avail-badge bg-success text-white"><i class="fas fa-check-circle me-1"></i>Tersedia</span>
          <img src="<?= $imgUrl; ?>"
               height="150"
               style="width:100%;height:150px;object-fit:cover;"
               loading="lazy"
               decoding="async"
               onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
               class="kk-avail-thumb"
               alt="<?= esc($data['nama_kendaraan'] ?? 'Kendaraan', 'attr'); ?>">
          <div class="p-3 text-center">
            <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.92rem;"><?= esc($data['nama_kendaraan'] ?? '-'); ?></h6>
            <span class="kk-avail-plate"><?= esc($data['plat_nomor'] ?? '-'); ?></span>
          </div>
        </div>
      </a>
    </div>
  <?php endforeach; ?>

  <?php foreach ($usedCars as $data):
    $imgFile = trim((string) ($data['gambar'] ?? ''));
    $imgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
  ?>
    <div class="col-lg-3 col-md-4 col-6 mb-4 block-cursor">
      <div class="kk-avail-card is-busy">
        <span class="kk-avail-badge bg-secondary text-white"><i class="fas fa-ban me-1"></i>Terpakai</span>
        <img src="<?= $imgUrl; ?>"
             height="150"
             style="width:100%;height:150px;object-fit:cover;"
             loading="lazy"
             decoding="async"
             onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
             class="kk-avail-thumb"
             alt="<?= esc($data['nama_kendaraan'] ?? 'Kendaraan', 'attr'); ?>">
        <div class="p-3 text-center">
          <h6 class="fw-bold mb-2 text-muted" style="font-size: 0.92rem;"><?= esc($data['nama_kendaraan'] ?? '-'); ?></h6>
          <span class="kk-avail-plate" style="background: #64748b;"><?= esc($data['plat_nomor'] ?? '-'); ?></span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
