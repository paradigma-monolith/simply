<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$totalAll       = (int) ($getBarang['count'] ?? 0);
$totalTersedia  = (int) ($getTersedia['count'] ?? 0);
$totalTidak     = (int) ($getTidakTersedia['count'] ?? 0);
$lokasiCounts   = $getLokasiCount ?? [];
$kategoriCounts = $getKategoriBarang ?? [];
$defaultWilker  = $defaultWilker ?? null;
?>

<style>
  .abs-inv-toolbar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 13px 16px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    margin-bottom: 12px;
  }
  .abs-inv-top-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-bottom: 11px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 11px;
  }
  .abs-inv-pills {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
  }
  .abs-inv-pill {
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #334155;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 5px 11px;
    border-radius: 999px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
  }
  .abs-inv-pill:hover {
    border-color: #0d9488;
    color: #0f766e;
    background: #f0fdfa;
  }
  .abs-inv-pill.active {
    background: #0d9488;
    border-color: #0d9488;
    color: #ffffff;
  }
  .abs-inv-pill-cnt {
    background: rgba(15, 23, 42, 0.08);
    padding: 1px 7px;
    border-radius: 999px;
    font-size: 0.69rem;
  }
  .abs-inv-pill.active .abs-inv-pill-cnt {
    background: rgba(255, 255, 255, 0.24);
    color: #ffffff;
  }
  .abs-inv-actions {
    display: flex;
    align-items: center;
    gap: 7px;
  }
  .abs-inv-btn {
    height: 33px;
    padding: 0 12px;
    border-radius: 9px;
    font-size: 0.77rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }
  .abs-filter-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr) auto;
    gap: 8px;
    align-items: end;
  }
  .abs-filter-field label {
    display: block;
    font-size: 0.69rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: #64748b;
    margin-bottom: 3px;
  }
  .abs-filter-field .form-control {
    height: 33px;
    font-size: 0.78rem;
    border-radius: 8px;
    border-color: #cbd5e1;
    padding: 3px 8px;
  }
  .abs-filter-field .form-control:focus {
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }

  /* Table Card Styles */
  .abs-inv-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }
  .abs-inv-table-head {
    padding: 11px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
  .abs-inv-result-badge {
    background: #f0fdfa;
    color: #0f766e;
    border: 1px solid #99f6e4;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 999px;
  }
  .abs-inv-search-wrap {
    position: relative;
    min-width: 240px;
    max-width: 310px;
    flex: 1 1 240px;
  }
  .abs-inv-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.76rem;
    pointer-events: none;
  }
  .abs-inv-search-input {
    width: 100%;
    height: 31px;
    padding: 4px 12px 4px 30px;
    border: 1px solid #cbd5e1;
    border-radius: 999px;
    font-size: 0.77rem;
    color: #1e293b;
    background: #ffffff;
    outline: none;
    transition: all 0.15s ease;
  }
  .abs-inv-search-input:focus {
    border-color: #0d9488;
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
  }
  #tableDataABS thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.73rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: 2px solid #e2e8f0;
    vertical-align: middle;
  }
  #tableDataABS tbody td {
    font-size: 0.8rem;
    vertical-align: middle;
    color: #1e293b;
  }
  .abs-meta-tag {
    display: inline-flex;
    align-items: center;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 1px 7px;
    font-size: 0.7rem;
    font-weight: 600;
  }
  .abs-missing-pill {
    display: inline-flex;
    align-items: center;
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 2px 7px;
    font-size: 0.7rem;
    font-weight: 700;
  }
  .abs-jenis-pill {
    display: inline-block;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    padding: 2px 7px;
    font-size: 0.71rem;
    font-weight: 700;
  }
  .abs-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 999px;
    font-size: 0.71rem;
    font-weight: 700;
    white-space: nowrap;
  }
  .abs-status-badge.is-available {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
  }
  .abs-status-badge.is-unavailable {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
  }
  .abs-act-group {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }
  .abs-act-btn {
    width: 27px;
    height: 27px;
    border-radius: 7px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.74rem;
  }

  @media (max-width: 991.98px) {
    .abs-filter-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  @media (max-width: 575.98px) {
    .abs-filter-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="abs-inv-toolbar">
      <!-- Baris Atas: Quick Filter Pills & Tombol Tambah/Import -->
      <div class="abs-inv-top-row">
        <div class="abs-inv-pills" id="absQuickPills">
          <button type="button" class="abs-inv-pill <?= $defaultWilker === null ? 'active' : ''; ?>" data-quick="all" onclick="absQuickFilter(this, 'all')">
            <i class="fas fa-boxes"></i> Semua Barang
            <span class="abs-inv-pill-cnt"><?= number_format($totalAll, 0, ',', '.'); ?></span>
          </button>
          <button type="button" class="abs-inv-pill" data-quick="tersedia" onclick="absQuickFilter(this, 'status_tersedia', 'Tersedia')">
            <i class="fas fa-check-circle text-success"></i> Lokasi Diketahui
            <span class="abs-inv-pill-cnt"><?= number_format($totalTersedia, 0, ',', '.'); ?></span>
          </button>
          <button type="button" class="abs-inv-pill" data-quick="tidak_tersedia" onclick="absQuickFilter(this, 'status_tersedia', 'Tidak Tersedia')">
            <i class="fas fa-exclamation-circle text-danger"></i> Tidak Diketahui
            <span class="abs-inv-pill-cnt"><?= number_format($totalTidak, 0, ',', '.'); ?></span>
          </button>
          <?php foreach ($lokasiCounts as $lc) : ?>
            <?php
            $ukName = $lc['unit_kerja'];
            $ukCnt  = (int) ($lc['count'] ?? 0);
            $isActiveWilker = ($defaultWilker !== null && $ukName === $defaultWilker);
            ?>
            <?php if (!empty($ukName)) : ?>
              <button type="button"
                      class="abs-inv-pill <?= $isActiveWilker ? 'active' : ''; ?>"
                      data-quick="wilker"
                      data-wilker="<?= esc($ukName, 'attr'); ?>"
                      onclick="absQuickFilter(this, 'unit_kerja', '<?= esc($ukName, 'js'); ?>')">
                <i class="fas fa-building"></i> <?= esc($ukName); ?>
                <span class="abs-inv-pill-cnt"><?= number_format($ukCnt, 0, ',', '.'); ?></span>
              </button>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <div class="abs-inv-actions">
          <a href="/abs/upload-file" target="_blank" class="btn btn-outline-success abs-inv-btn" title="Import Data Inventaris dari Excel">
            <i class="fas fa-file-import"></i> Import Excel
          </a>
          <a href="/abs/lokasi" class="btn btn-primary abs-inv-btn" style="background:#0d9488;border-color:#0d9488;">
            <i class="fas fa-plus"></i> Tambah Data
          </a>
        </div>
      </div>

      <!-- Baris Bawah: Multi-Filter Spesifik (Wilayah Kerja, Lantai, Ruang, Kategori, Status) -->
      <div class="abs-filter-grid">
        <div class="abs-filter-field">
          <label for="absFilterUnitKerja">Wilayah Kerja</label>
          <select id="absFilterUnitKerja" class="form-control">
            <option value="__ALL__">-- Semua Wilayah Kerja --</option>
            <?php foreach (($unit_kerja_list ?? []) as $uk) : ?>
              <option value="<?= esc($uk['unit_kerja'], 'attr'); ?>" <?= ($defaultWilker !== null && $uk['unit_kerja'] === $defaultWilker) ? 'selected' : ''; ?>>
                <?= esc($uk['unit_kerja']); ?>
              </option>
            <?php endforeach; ?>
            <option value="__NULL__">Tidak diketahui (Kosong)</option>
          </select>
        </div>

        <div class="abs-filter-field">
          <label for="absFilterLantai">Lantai</label>
          <select id="absFilterLantai" class="form-control">
            <option value="__ALL__">-- Semua Lantai --</option>
            <?php foreach (($lantai_list ?? []) as $lt) : ?>
              <option value="<?= esc($lt['lantai'], 'attr'); ?>"><?= esc($lt['lantai']); ?></option>
            <?php endforeach; ?>
            <option value="__NULL__">Tidak diketahui (Kosong)</option>
          </select>
        </div>

        <div class="abs-filter-field">
          <label for="absFilterRuang">Ruang</label>
          <select id="absFilterRuang" class="form-control">
            <option value="__ALL__">-- Semua Ruang --</option>
            <?php foreach (($ruang_list ?? []) as $rg) : ?>
              <option value="<?= esc($rg['ruang'], 'attr'); ?>"><?= esc($rg['ruang']); ?></option>
            <?php endforeach; ?>
            <option value="__NULL__">Tidak diketahui (Kosong)</option>
          </select>
        </div>

        <div class="abs-filter-field">
          <label for="absFilterJenis">Kategori Kelompok</label>
          <select id="absFilterJenis" class="form-control">
            <option value="__ALL__">-- Semua Kategori --</option>
            <?php foreach (($jenis_list ?? []) as $jn) : ?>
              <option value="<?= esc($jn['jenis'], 'attr'); ?>"><?= esc($jn['jenis']); ?></option>
            <?php endforeach; ?>
            <option value="__NULL__">Tanpa Kategori (Kosong)</option>
          </select>
        </div>

        <div class="abs-filter-field">
          <label for="absFilterStatus">Status Lokasi</label>
          <select id="absFilterStatus" class="form-control">
            <option value="__ALL__">-- Semua Status --</option>
            <option value="Tersedia">Tersedia (Diketahui)</option>
            <option value="Tidak Tersedia">Tidak Tersedia</option>
          </select>
        </div>

        <div class="d-flex align-items-center" style="gap: 6px;">
          <button type="button" class="btn btn-info abs-inv-btn" id="btnApplyAbsFilter" style="background:#0d9488;border-color:#0d9488;">
            <i class="fas fa-filter"></i> Filter
          </button>
          <button type="button" class="btn btn-light border abs-inv-btn" id="btnResetAbsFilter" title="Reset Filter">
            <i class="fas fa-undo"></i>
          </button>
        </div>
      </div>
    </div>

    <div id="contentABS">
      <?= view('abs/all_data_barang', ['getData' => $initialGetData ?? []]); ?>
    </div>
  </div>
</section>

<script>
  function absQuickFilter(btnEl, label, nilai) {
    $('#absQuickPills .abs-inv-pill').removeClass('active');
    if (btnEl) {
      $(btnEl).addClass('active');
    }

    // Sinkronkan dropdown multi-filter
    $('#absFilterLantai').val('__ALL__');
    $('#absFilterRuang').val('__ALL__');
    $('#absFilterJenis').val('__ALL__');

    if (label === 'all') {
      $('#absFilterUnitKerja').val('__ALL__');
      $('#absFilterStatus').val('__ALL__');
    } else if (label === 'status_tersedia') {
      $('#absFilterUnitKerja').val('__ALL__');
      $('#absFilterStatus').val(nilai || '__ALL__');
    } else if (label === 'unit_kerja') {
      $('#absFilterUnitKerja').val(nilai || '__NULL__');
      $('#absFilterStatus').val('__ALL__');
    }

    getDataBarangABS(label, nilai);
  }

  function applyAbsMultiFilter() {
    $('#absQuickPills .abs-inv-pill').removeClass('active');
    var payload = {
      label: 'multi',
      f_unit_kerja: $('#absFilterUnitKerja').val(),
      f_lantai: $('#absFilterLantai').val(),
      f_ruang: $('#absFilterRuang').val(),
      f_jenis: $('#absFilterJenis').val(),
      f_status: $('#absFilterStatus').val()
    };

    $.ajax({
      url: '/abs/getDataBarang',
      data: payload,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $('#contentABS').html(
          '<div class="abs-inv-table-card p-5 text-center text-muted">' +
          '<i class="fas fa-circle-notch fa-spin fa-2x mb-2" style="color:#0d9488;"></i>' +
          '<div class="font-weight-bold">Memuat data inventaris...</div>' +
          '</div>'
        );
      },
      success: function(response) {
        if (response && response.data) {
          $('#contentABS').html(response.data);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal memuat data',
          text: xhr.status + ' ' + thrownError
        });
      }
    });
  }

  $(function() {
    $('#btnApplyAbsFilter').on('click', function() {
      applyAbsMultiFilter();
    });

    $('#absFilterUnitKerja, #absFilterLantai, #absFilterRuang, #absFilterJenis, #absFilterStatus').on('change', function() {
      applyAbsMultiFilter();
    });

    $('#btnResetAbsFilter').on('click', function() {
      $('#absFilterUnitKerja').val('<?= esc($defaultWilker ?? '__ALL__', 'js'); ?>');
      $('#absFilterLantai').val('__ALL__');
      $('#absFilterRuang').val('__ALL__');
      $('#absFilterJenis').val('__ALL__');
      $('#absFilterStatus').val('__ALL__');
      applyAbsMultiFilter();
    });
  });
</script>

<?= $this->endSection(); ?>
