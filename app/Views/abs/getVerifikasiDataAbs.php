<?php if (!empty($label)) : ?>
  <?php
  $array = \App\Models\AbsLokasiModel::safeUnserialize($label['data_barang'] ?? '');
  $imgFile = !empty($label['image']) ? $label['image'] : 'default.png';
  ?>
  <div class="abs-verif-card">
    <div class="abs-verif-head justify-content-between flex-wrap" style="gap: 10px;">
      <div class="d-flex align-items-center" style="gap: 10px;">
        <div class="abs-verif-icon"><i class="fas fa-box-open"></i></div>
        <div>
          <h3 class="abs-verif-title"><?= esc((string) ($array['Nama Barang'] ?? 'Detail Inventaris BMN')); ?></h3>
          <p class="abs-verif-sub">QR SIMAN: <code><?= esc((string) ($label['kode_qr_code_pusat'] ?? '-')); ?></code></p>
        </div>
      </div>
      <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
        <button type="button" class="btn btn-sm btn-warning font-weight-bold" id="abs_btn_verifikasi_lokasi" onclick="absMoveVerifikationLocation('<?= (int) $label['id_abs_bangunan']; ?>')">
          <i class="fas fa-exchange-alt mr-1"></i> Pindahkan Sesuai Lokasi Target
        </button>
        <a class="btn btn-sm btn-outline-secondary font-weight-bold" href="/abs/editDataBarang/<?= (int) $label['id_abs_bangunan']; ?>">
          <i class="fas fa-edit mr-1"></i> Edit Data
        </a>
      </div>
    </div>

    <div class="abs-verif-body">
      <div class="row align-items-center mb-3">
        <div class="col-md-8">
          <table class="table table-sm table-bordered mb-0">
            <tr>
              <th width="34%" class="bg-light">Status Verifikasi Lokasi</th>
              <td>
                <div id="abs_status_verifikasi_lokasi">
                  <span class="badge badge-success">Valid</span>
                </div>
              </td>
            </tr>
            <tr>
              <th class="bg-light">Wilayah Kerja Saat Ini</th>
              <td class="font-weight-bold"><?= !empty($label['unit_kerja']) ? esc($label['unit_kerja']) : '<span class="text-danger">Tidak diketahui</span>'; ?></td>
            </tr>
            <tr>
              <th class="bg-light">Lantai / Ruang Saat Ini</th>
              <td>
                <span class="<?= !empty($label['lantai']) ? 'font-weight-bold' : 'text-danger'; ?>"><?= !empty($label['lantai']) ? esc($label['lantai']) : 'Tidak diketahui'; ?></span>
                &bull;
                <span class="<?= !empty($label['ruang']) ? 'font-weight-bold' : 'text-danger'; ?>"><?= !empty($label['ruang']) ? esc($label['ruang']) : 'Tidak diketahui'; ?></span>
              </td>
            </tr>
            <tr>
              <th class="bg-light">Kategori Kelompok</th>
              <td><?= !empty($label['jenis']) ? esc($label['jenis']) : '<span class="text-danger">Tanpa kategori</span>'; ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-4 text-center mt-3 mt-md-0">
          <img src="/dist/img/abs/<?= esc($imgFile, 'attr'); ?>"
               onerror="this.onerror=null;this.src='/dist/img/abs/default.png';"
               class="img-thumbnail"
               style="max-height: 155px; object-fit: cover;"
               alt="Gambar Barang">
        </div>
      </div>

      <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.82rem;">
        <i class="fas fa-info-circle text-info mr-1"></i> Rincian Atribut Barang BMN
      </h6>
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped mb-0">
          <tbody>
            <?php foreach ($array as $attrKey => $attrVal) : ?>
              <tr>
                <th width="34%" style="font-size: 0.78rem;"><?= esc((string) $attrKey); ?></th>
                <td style="font-size: 0.8rem;"><?= esc((string) $attrVal); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php else : ?>
  <div class="abs-empty-state" style="border-color: #fecaca; background: #fef2f2; color: #991b1b;">
    <i class="fas fa-search-minus fa-2x mb-2 text-danger"></i>
    <h6 class="font-weight-bold mb-1">Data Inventaris Tidak Ditemukan</h6>
    <p class="small mb-0">Kode QR yang dimasukkan tidak terdaftar pada database ABS BMN.</p>
  </div>
<?php endif; ?>
