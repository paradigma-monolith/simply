<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$lantaiList = $lantai ?? [];
$ruangList  = $ruang ?? [];
$jenisList  = $jenis ?? [];
?>

<style>
  .abs-bmn-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
  }
  .abs-bmn-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  .abs-bmn-card-head {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #f8fafc;
  }
  .abs-bmn-head-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .abs-bmn-head-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
  }
  .abs-bmn-head-title {
    font-size: 0.9rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    line-height: 1.2;
  }
  .abs-bmn-head-sub {
    font-size: 0.71rem;
    color: #64748b;
    margin: 1px 0 0;
  }
  .abs-bmn-cnt-badge {
    background: #e2e8f0;
    color: #334155;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 999px;
  }
  .abs-bmn-card-body {
    padding: 14px 16px;
    flex: 1 1 auto;
  }
  .abs-bmn-form-inline {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
  }
  .abs-bmn-form-inline .form-control {
    height: 35px;
    border-radius: 9px;
    border: 1px solid #cbd5e1;
    font-size: 0.81rem;
  }
  .abs-bmn-form-inline .form-control:focus {
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }
  .abs-bmn-btn-add {
    height: 35px;
    padding: 0 13px;
    border-radius: 9px;
    font-size: 0.78rem;
    font-weight: 700;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #0d9488;
    border-color: #0d9488;
    color: #ffffff;
  }
  .abs-bmn-btn-add:hover {
    background: #0f766e;
    border-color: #0f766e;
    color: #ffffff;
  }
  .abs-master-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 2px solid #e2e8f0;
    padding: 7px 10px;
  }
  .abs-master-table tbody td {
    font-size: 0.8rem;
    vertical-align: middle;
    padding: 7px 10px;
    color: #1e293b;
  }
  .abs-item-usage {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 0.69rem;
    font-weight: 700;
    background: #f0fdfa;
    color: #0f766e;
    border: 1px solid #ccfbf1;
  }

  @media (max-width: 1199.98px) {
    .abs-bmn-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="abs-bmn-grid">
      <!-- Kolom 1: Data Lantai -->
      <div class="abs-bmn-card">
        <div class="abs-bmn-card-head">
          <div class="abs-bmn-head-left">
            <div class="abs-bmn-head-icon" style="background:#f0fdfa;color:#0d9488;">
              <i class="fas fa-layer-group"></i>
            </div>
            <div>
              <h3 class="abs-bmn-head-title">Data Lantai</h3>
              <p class="abs-bmn-head-sub">Master lantai gedung &amp; bangunan</p>
            </div>
          </div>
          <span class="abs-bmn-cnt-badge"><?= count($lantaiList); ?> lantai</span>
        </div>
        <div class="abs-bmn-card-body">
          <form id="absSimpanDataLantai" action="/abs/simpan_data_lantai" method="post" autocomplete="off">
            <div class="abs-bmn-form-inline">
              <input type="text" class="form-control" name="nama_lantai" id="nama_lantai" placeholder="Tambah nama lantai baru..." required>
              <button type="submit" class="btn abs-bmn-btn-add"><i class="fas fa-plus"></i> Simpan</button>
            </div>
          </form>
          <div id="absLantai" data-prerendered="1">
            <?= view('abs/table-lantai', ['lantai' => $lantaiList]); ?>
          </div>
        </div>
      </div>

      <!-- Kolom 2: Data Ruang -->
      <div class="abs-bmn-card">
        <div class="abs-bmn-card-head">
          <div class="abs-bmn-head-left">
            <div class="abs-bmn-head-icon" style="background:#eff6ff;color:#2563eb;">
              <i class="fas fa-door-open"></i>
            </div>
            <div>
              <h3 class="abs-bmn-head-title">Data Ruang</h3>
              <p class="abs-bmn-head-sub">Master ruangan penempatan BMN</p>
            </div>
          </div>
          <span class="abs-bmn-cnt-badge"><?= count($ruangList); ?> ruang</span>
        </div>
        <div class="abs-bmn-card-body">
          <form id="absSimpanDataRuang" action="/abs/simpan_data_ruang" method="post" autocomplete="off">
            <div class="abs-bmn-form-inline">
              <input type="text" class="form-control" name="nama_ruang" id="nama_ruang" placeholder="Tambah nama ruangan baru..." required>
              <button type="submit" class="btn abs-bmn-btn-add"><i class="fas fa-plus"></i> Simpan</button>
            </div>
          </form>
          <div id="absRuang" data-prerendered="1">
            <?= view('abs/table-ruang', ['ruang' => $ruangList]); ?>
          </div>
        </div>
      </div>

      <!-- Kolom 3: Kategori Kelompok -->
      <div class="abs-bmn-card">
        <div class="abs-bmn-card-head">
          <div class="abs-bmn-head-left">
            <div class="abs-bmn-head-icon" style="background:#fef3c7;color:#d97706;">
              <i class="fas fa-tags"></i>
            </div>
            <div>
              <h3 class="abs-bmn-head-title">Kategori Kelompok</h3>
              <p class="abs-bmn-head-sub">Klasifikasi kelompok barang BMN</p>
            </div>
          </div>
          <span class="abs-bmn-cnt-badge"><?= count($jenisList); ?> kategori</span>
        </div>
        <div class="abs-bmn-card-body">
          <form id="absSimpanDataJenis" action="/abs/simpan_data_jenis" method="post" autocomplete="off">
            <div class="abs-bmn-form-inline">
              <input type="text" class="form-control" name="nama_jenis" id="nama_jenis" placeholder="Tambah kategori kelompok baru..." required>
              <button type="submit" class="btn abs-bmn-btn-add"><i class="fas fa-plus"></i> Simpan</button>
            </div>
          </form>
          <div id="absJenisBarang" data-prerendered="1">
            <?= view('abs/table-jenis-barang', ['jenis' => $jenisList]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>
