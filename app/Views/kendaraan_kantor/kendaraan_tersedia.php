<?php
$availableCars = is_array($getData ?? null) ? $getData : [];
$usedCars = is_array($notData ?? null) ? $notData : [];
?>

<style>
  .kk-avail-grid {
    margin-left: -6px;
    margin-right: -6px;
  }
  .kk-avail-grid > [class*="col-"] {
    padding-left: 6px;
    padding-right: 6px;
  }
  .kk-avail-card {
    background: rgba(255, 255, 255, 0.97);
    border: 1px solid rgba(15, 23, 42, 0.1);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    height: 100%;
    position: relative;
    display: flex;
    flex-direction: column;
  }
  .kk-avail-card.is-ready:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(13, 148, 136, 0.22);
    border-color: #0d9488;
  }
  .kk-avail-card.is-busy {
    opacity: 0.58;
    filter: grayscale(40%);
    cursor: not-allowed;
  }
  .kk-avail-thumb {
    width: 100% !important;
    height: 136px !important;
    object-fit: cover !important;
    background: #f1f5f9;
  }
  .kk-avail-body {
    padding: 10px 10px 12px;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
  }
  .kk-avail-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 6px;
    line-height: 1.25;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .kk-avail-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    font-size: 0.66rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.16);
  }
  .kk-avail-plate {
    display: inline-block;
    background: #0f172a;
    color: #ffffff;
    font-family: 'Consolas', monospace;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 6px;
    letter-spacing: 0.6px;
  }
  @media (max-width: 575.98px) {
    .kk-avail-card {
      border-radius: 11px;
    }
    .kk-avail-thumb {
      height: 92px !important;
    }
    .kk-avail-body {
      padding: 8px 8px 9px;
    }
    .kk-avail-title {
      font-size: 0.78rem;
      margin-bottom: 5px;
    }
    .kk-avail-plate {
      font-size: 0.68rem;
      padding: 2px 7px;
    }
    .kk-avail-badge {
      top: 6px;
      right: 6px;
      font-size: 0.6rem;
      padding: 2px 6px;
    }
  }
</style>

<div class="row kk-avail-grid">
  <?php foreach ($availableCars as $data):
    $imgFile = trim((string) ($data['gambar'] ?? ''));
    $imgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
  ?>
    <div class="col-lg-3 col-md-4 col-6 mb-3">
      <a href="javascript:void(0)"
         class="no-style getTheCar d-block h-100"
         data-id="<?= (int) $data['id_kendaraan_kantor_daftar']; ?>"
         data-kendaraan="<?= esc($data['nama_kendaraan'] ?? '', 'attr'); ?>"
         data-plat="<?= esc($data['plat_nomor'] ?? '', 'attr'); ?>">
        <div class="kk-avail-card is-ready">
          <span class="kk-avail-badge bg-success text-white"><i class="fas fa-check-circle me-1"></i>Tersedia</span>
          <img src="<?= $imgUrl; ?>"
               loading="lazy"
               decoding="async"
               onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
               class="kk-avail-thumb"
               alt="<?= esc($data['nama_kendaraan'] ?? 'Kendaraan', 'attr'); ?>">
          <div class="kk-avail-body">
            <h6 class="kk-avail-title"><?= esc($data['nama_kendaraan'] ?? '-'); ?></h6>
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
    <div class="col-lg-3 col-md-4 col-6 mb-3 block-cursor">
      <div class="kk-avail-card is-busy">
        <span class="kk-avail-badge bg-secondary text-white"><i class="fas fa-ban me-1"></i>Terpakai</span>
        <img src="<?= $imgUrl; ?>"
             loading="lazy"
             decoding="async"
             onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
             class="kk-avail-thumb"
             alt="<?= esc($data['nama_kendaraan'] ?? 'Kendaraan', 'attr'); ?>">
        <div class="kk-avail-body">
          <h6 class="kk-avail-title text-muted"><?= esc($data['nama_kendaraan'] ?? '-'); ?></h6>
          <span class="kk-avail-plate" style="background: #64748b;"><?= esc($data['plat_nomor'] ?? '-'); ?></span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
