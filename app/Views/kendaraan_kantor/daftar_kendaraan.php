<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<?php
$vehicles = is_array($getData ?? null) ? $getData : [];
$isAdminRole = session()->get('role') === 'Admin';
?>

<style>
  .kk-modern {
    --kk-primary: #0d9488;
    --kk-primary-dark: #0f766e;
    --kk-ink: #0f172a;
    --kk-muted: #64748b;
    --kk-border: rgba(15, 23, 42, 0.08);
  }
  .kk-modern .kk-header-banner {
    background: linear-gradient(135deg, #0b7285 0%, #0d9488 55%, #14b8a6 100%);
    color: #ffffff;
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 18px;
    box-shadow: 0 8px 24px rgba(13, 148, 136, 0.18);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
  }
  .kk-modern .kk-surface-card {
    background: #ffffff;
    border: 1px solid var(--kk-border);
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    overflow: hidden;
  }
  .kk-modern .kk-thumb-wrap {
    width: 112px;
    height: 72px;
    border-radius: 10px;
    overflow: hidden;
    background: #f1f5f9;
    border: 1px solid rgba(15, 23, 42, 0.08);
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .kk-modern .kk-thumb-img {
    width: 112px !important;
    height: 72px !important;
    object-fit: cover !important;
    transition: transform 0.2s ease;
  }
  .kk-modern .kk-thumb-wrap:hover .kk-thumb-img {
    transform: scale(1.06);
  }
  .kk-modern .kk-plate-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #0f172a;
    color: #ffffff;
    font-family: 'Consolas', 'Courier New', monospace;
    font-weight: 700;
    font-size: 0.84rem;
    letter-spacing: 1px;
    padding: 4px 12px;
    border-radius: 7px;
    border: 1.5px solid #334155;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15);
  }
  .kk-modern .table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.76rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom-width: 1px;
    vertical-align: middle;
  }
  .kk-modern .table tbody td {
    vertical-align: middle !important;
  }
</style>

<section class="content kk-modern pb-4">
  <div class="container-fluid">

    <div class="kk-header-banner">
      <div class="d-flex align-items-center" style="gap: 14px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
          <i class="fas fa-car-side"></i>
        </div>
        <div>
          <h4 class="font-weight-bold mb-1">Daftar Armada Kendaraan Kantor</h4>
          <div style="font-size: 0.84rem; opacity: 0.92;">
            Total <strong><?= count($vehicles); ?></strong> unit kendaraan terdaftar pada sistem peminjaman Balai Karkes Bandung.
          </div>
        </div>
      </div>
      <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
        <a href="/kendaraan_kantor/riwayat_peminjaman" class="btn btn-sm btn-outline-light font-weight-bold px-3" style="border-radius: 9px;">
          <i class="fas fa-history mr-1"></i> Riwayat Peminjaman
        </a>
        <?php if ($isAdminRole): ?>
          <a href="/kendaraan_kantor/tambah_daftar" class="btn btn-sm btn-light font-weight-bold px-3" style="border-radius: 9px; color: #0f766e;">
            <i class="fas fa-plus mr-1"></i> Tambah Kendaraan
          </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="card kk-surface-card">
      <div class="card-body">
        <table id="tableWithoutExporting" class="table table-hover table-bordered mb-0">
          <thead>
            <tr class="text-center">
              <th style="width: 48px;">No</th>
              <th style="width: 140px;">Foto Unit</th>
              <th class="text-left">Nama Kendaraan</th>
              <th style="width: 190px;">Plat Nomor</th>
              <th style="width: 130px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $nomor = 1;
            foreach ($vehicles as $data):
              $imgFile = trim((string) ($data['gambar'] ?? ''));
              $imgUrl = $imgFile !== '' ? '/dist/img/' . esc($imgFile, 'attr') : '/dist/img/boxed-bg.jpg';
            ?>
              <tr>
                <td class="text-center font-weight-bold text-muted"><?= $nomor++; ?></td>
                <td class="text-center">
                  <div class="kk-thumb-wrap">
                    <img src="<?= $imgUrl; ?>"
                         width="112"
                         height="72"
                         style="width:112px;height:72px;object-fit:cover;"
                         loading="lazy"
                         decoding="async"
                         onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';"
                         class="kk-thumb-img gallery"
                         data-src="<?= $imgUrl; ?>"
                         alt="<?= esc($data['nama_kendaraan'] ?? 'Kendaraan', 'attr'); ?>">
                  </div>
                </td>
                <td>
                  <div class="font-weight-bold" style="color: #0f172a; font-size: 0.94rem;">
                    <?= esc($data['nama_kendaraan'] ?? '-'); ?>
                  </div>
                  <small class="text-muted"><i class="fas fa-check-circle text-success mr-1"></i>Unit Kendaraan Operasional</small>
                </td>
                <td class="text-center">
                  <span class="kk-plate-badge">
                    <i class="fas fa-car" style="font-size: 0.72rem; opacity: 0.7;"></i>
                    <?= esc($data['plat_nomor'] ?? '-'); ?>
                  </span>
                </td>
                <td class="text-center">
                  <div class="d-inline-flex align-items-center" style="gap: 6px;">
                    <a href="/kendaraan_kantor/update_daftar/<?= (int) $data['id_kendaraan_kantor_daftar']; ?>"
                       class="btn btn-sm btn-warning text-dark font-weight-bold"
                       style="border-radius: 8px;"
                       title="Edit Kendaraan">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <?php if ($isAdminRole): ?>
                      <form action="/kendaraan_kantor/dlt_daftar/<?= (int) $data['id_kendaraan_kantor_daftar']; ?>" method="post" class="d-inline formHapusData m-0">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;" title="Hapus Kendaraan">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<?= $this->endSection(); ?>
