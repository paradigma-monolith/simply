<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <?php $isData = 'h'; ?>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Note Akses Super User dan Verifikator</h3>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <!-- Akses Halaman -->
          <li class="list-group-item list-group-item-disabled-hover py-0">
            Halaman ini hanya dapat diakses oleh <span class="badge badge-danger">Super User</span>.
          </li>
          <li class="list-group-item list-group-item-disabled-hover py-0">
            Akses <span class="badge badge-danger">Super User</span> dapat diberikan oleh <span class="badge badge-danger">Super User</span> lainnya.
            <small class="text-muted">(<i>Admin tidak dapat memberikan hak akses Super User kepada seseorang</i>)</small>.
          </li>

          <!-- Pengelolaan Data Perjalanan Dinas -->
          <li class="list-group-item list-group-item-disabled-hover py-0">
            <span class="badge badge-danger">Super User</span> dapat mengelola semua data perjalanan dinas, termasuk perjalanan dinas yang dibuat oleh pengguna lain.
          </li>
          <li class="list-group-item list-group-item-disabled-hover py-0">
            Data perjalanan dinas yang sudah diperbarui oleh <span class="badge badge-danger">Super User</span> tidak dapat diperbarui kembali oleh pengguna biasa.
            <small class="text-muted">(Pengelolaan terakhir ada pada Super User)</small>.
          </li>

          <!-- Pengaturan Verifikator -->
          <li class="list-group-item list-group-item-disabled-hover py-0">
            Hanya <span class="badge badge-danger">Super User</span> yang dapat menentukan siapa saja yang termasuk <span class="badge badge-info">Verifikator</span>.
          </li>
          <li class="list-group-item list-group-item-disabled-hover py-0">
            <span class="badge badge-info">Verifikator</span> terbagi menjadi 5:
            <ul class="pl-3">
              <li><strong>3 Verifikator Utama</strong>: Wajib diisi.</li>
              <li><strong>2 Verifikator Tambahan</strong>: Opsional (dapat dikosongkan).</li>
            </ul>
          </li>

          <!-- Pengunggah Bukti Pembayaran -->
          <li class="list-group-item list-group-item-disabled-hover py-0">
            Pengguna yang mengupload bukti pembayaran dipilih oleh <span class="badge badge-danger">Super User</span> secara langsung di halaman ini.
            <small class="text-muted">(Bukti pembayaran dapat diupload setelah data perjalanan dinas terverifikasi)</small>.
          </li>
        </ul>
      </div>
    </div>
    <!-- /.card -->

    <div class="card card-info collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Akses Super User</h3>

        <div class="card-tools">
          <?php $isData .= 'a'; ?>
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
      <?php $isData .= 'di'; ?>
      <div class="card-body">
        <?php $isData .= "an"; ?>
        <div class="row">
          <?php foreach ($user as $data) : ?>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="custom-control custom-switch border">
                <input type="checkbox" class="custom-control-input aksesPenggunaCheckboxSinadi <?= $akses['id_sinadi_akses'] ? ($akses['status'] == 'Akses Diblokir' ? '' : 'aksesPenggunaCheckboxSinadi') : ''; ?>" id="customSwitch<?= $data['id_user']; ?>" <?= $data['id_sinadi_akses'] != null && $data['status'] == 'Akses Diterima' ? 'checked' : ''; ?> value="<?= $data['id_user']; ?>" <?= stripos($data['nama'], $isData) !== false  || $akses['status'] == 'Akses Diblokir' ? 'disabled' : ''; ?>>
                <label class="custom-control-label" for="customSwitch<?= $data['id_user']; ?>">
                  <?= (strlen(name_capitalize($data['nama'])) > 16) ? substr(name_capitalize($data['nama']), 0, 16) . '...' : name_capitalize($data['nama']); ?>
                </label>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- /.card -->

    <div class="card card-primary collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Akses Tim Kerja</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <?php foreach ($user as $data) : ?>
            <div class="col-xl-3 col-lg-4 col-sm-6">
              <div class="custom-control custom-switch border">
                <input type="checkbox" class="custom-control-input aksesTimCheckboxSinadi" id="customSwitchTim<?= $data['id_user']; ?>" <?= $data['id_sinadi_akses'] != null && $data['akses_tim'] == 1 ? 'checked' : ''; ?> value="<?= $data['id_user']; ?>" <?= stripos($data['nama'], $isData) !== false  || $akses['status'] == 'Akses Diblokir' ? 'disabled' : ''; ?>>
                <label class="custom-control-label" for="customSwitchTim<?= $data['id_user']; ?>">
                  <?= (strlen(name_capitalize($data['nama'])) > 16) ? substr(name_capitalize($data['nama']), 0, 16) . '...' : name_capitalize($data['nama']); ?>
                </label>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <!-- /.card -->

    <div class="card card-warning collapsed-card">
      <div class="card-header">
        <h3 class="card-title">Setting Verifikator</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="card-body table-warning">
        <div class="row table-warning">
          <div class="col-md-12">
            <div class="p-4 border">
              <h5 class="mb-3"><i class="fas fa-coins text-primary mr-1"></i> Verifikator APBN</h5>
              <?php 
                $verApbn = is_array($verifikatorApbn ?? null) ? $verifikatorApbn : [];
                $arrayApbn1 = (isset($verApbn['verifikator_1']) && $verApbn['verifikator_1'] != null) ? unserialize($verApbn['verifikator_1']) : [];
                $arrayApbn6 = (isset($verApbn['verifikator_6']) && $verApbn['verifikator_6'] != null) ? unserialize($verApbn['verifikator_6']) : [];
              ?>
              <!-- APBN Verifikator -->
              <div class="row border-bottom mb-3 table-primary">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 1</label>
                    <select name="verifikator_1[]" multiple="multiple" class="form-control select2 sinadi-select-verifikator" data-verifikator="1" data-tipe="APBN" style="width: 100%;">
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= in_array($data['id_data_pegawai'], $arrayApbn1) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Wajib terisi!</small>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="card mt-4">
                      <div class="card-header bg-primary text-white">
                        Verifikator Pertama (APBN)
                      </div>
                      <div class="card-body">
                        <p class="card-text">
                          Tahap verifikator pertama untuk dokumen sumber anggaran APBN.
                        </p>
                      </div>
                    </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-success">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 2</label>
                    <select name="verifikator_2" class="form-control select2 sinadi-select-verifikator" data-verifikator="2" data-tipe="APBN" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verApbn['verifikator_2']) && ($data['id_data_pegawai'] == $verApbn['verifikator_2']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Wajib terisi!</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card my-4">
                    <div class="card-header bg-success text-white">
                      Verifikator Kedua (APBN)
                    </div>
                    <div class="card-body">
                      <p class="card-text">Tahap verifikator kedua untuk APBN.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-info">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 3</label>
                    <select name="verifikator_3" class="form-control select2 sinadi-select-verifikator" data-verifikator="3" data-tipe="APBN" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verApbn['verifikator_3']) && ($data['id_data_pegawai'] == $verApbn['verifikator_3']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Wajib terisi!</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card my-4">
                    <div class="card-header bg-info text-white">
                      Verifikator Ketiga (APBN)
                    </div>
                    <div class="card-body">
                      <p class="card-text">Tahap verifikator ketiga untuk APBN.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-secondary">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 4</label>
                    <select name="verifikator_4" disabled class="form-control disabled sinadi-select-verifikator" data-verifikator="4" data-tipe="APBN" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verApbn['verifikator_4']) && ($data['id_data_pegawai'] == $verApbn['verifikator_4']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Isian Opsional!</small>
                  </div>
                  <div class="form-group">
                    <label>Verifikator 5</label>
                    <select name="verifikator_5" disabled class="form-control disabled sinadi-select-verifikator" data-verifikator="5" data-tipe="APBN" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verApbn['verifikator_5']) && ($data['id_data_pegawai'] == $verApbn['verifikator_5']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Isian Opsional!</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card my-4">
                    <div class="card-header bg-secondary text-white">
                      Verifikator Tambahan (APBN)
                    </div>
                    <div class="card-body">
                      <p class="card-text">Verifikator tambahan untuk kasus APBN bila diperlukan.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-dark">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Upload Bukti Pembayaran</label>
                    <select name="verifikator_6[]" multiple="multiple" class="form-control select2 sinadi-select-verifikator" data-verifikator="6" data-tipe="APBN" style="width: 100%;">
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= in_array($data['id_data_pegawai'], $arrayApbn6) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Wajib terisi!</small>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="card my-4">
                      <div class="card-header bg-dark text-white">
                        Unggah Bukti (APBN)
                      </div>
                      <div class="card-body text-dark">
                        <p class="card-text">Konfigurasi petugas unggah bukti pembayaran untuk APBN.</p>
                      </div>
                    </div>
                </div>
              </div>

              <hr class="my-4" />
              <h5 class="mb-3"><i class="fas fa-hand-holding-usd text-success mr-1"></i> Verifikator RPL</h5>
              <?php 
                $verRpl = is_array($verifikatorRpl ?? null) ? $verifikatorRpl : [];
                $arrayRpl1 = (isset($verRpl['verifikator_1']) && $verRpl['verifikator_1'] != null) ? unserialize($verRpl['verifikator_1']) : [];
                $arrayRpl6 = (isset($verRpl['verifikator_6']) && $verRpl['verifikator_6'] != null) ? unserialize($verRpl['verifikator_6']) : [];
              ?>
              <!-- RPL Verifikator -->
              <div class="row border-bottom mb-3 table-primary">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 1</label>
                    <select name="verifikator_1[]" multiple="multiple" class="form-control select2 sinadi-select-verifikator" data-verifikator="1" data-tipe="RPL" style="width: 100%;">
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= in_array($data['id_data_pegawai'], $arrayRpl1) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Wajib terisi!</small>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="card mt-4">
                      <div class="card-header bg-primary text-white">
                        Verifikator Pertama (RPL)
                      </div>
                      <div class="card-body">
                        <p class="card-text">Tahap verifikator pertama untuk dokumen sumber anggaran RPL.</p>
                      </div>
                    </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-success">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 2</label>
                    <select name="verifikator_2" class="form-control select2 sinadi-select-verifikator" data-verifikator="2" data-tipe="RPL" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verRpl['verifikator_2']) && ($data['id_data_pegawai'] == $verRpl['verifikator_2']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Wajib terisi!</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card my-4">
                    <div class="card-header bg-success text-white">
                      Verifikator Kedua (RPL)
                    </div>
                    <div class="card-body">
                      <p class="card-text">Tahap verifikator kedua untuk RPL.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-info">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 3</label>
                    <select name="verifikator_3" class="form-control select2 sinadi-select-verifikator" data-verifikator="3" data-tipe="RPL" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verRpl['verifikator_3']) && ($data['id_data_pegawai'] == $verRpl['verifikator_3']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Isian Opsional!</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card my-4">
                    <div class="card-header bg-info text-white">
                      Verifikator Ketiga (RPL)
                    </div>
                    <div class="card-body">
                      <p class="card-text">Verifikator ketiga bersifat opsional untuk RPL.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-secondary">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Verifikator 4</label>
                    <select name="verifikator_4" class="form-control sinadi-select-verifikator" data-verifikator="4" data-tipe="RPL" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verRpl['verifikator_4']) && ($data['id_data_pegawai'] == $verRpl['verifikator_4']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Isian Opsional!</small>
                  </div>
                  <div class="form-group">
                    <label>Verifikator 5</label>
                    <select name="verifikator_5" class="form-control sinadi-select-verifikator" data-verifikator="5" data-tipe="RPL" style="width: 100%;">
                      <option value="">--Pilih Verifikator--</option>
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= isset($verRpl['verifikator_5']) && ($data['id_data_pegawai'] == $verRpl['verifikator_5']) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Isian Opsional!</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card my-4">
                    <div class="card-header bg-secondary text-white">
                      Verifikator Tambahan (RPL)
                    </div>
                    <div class="card-body">
                      <p class="card-text">Untuk RPL, hanya 2 verifikator wajib (1 & 2). Tiga verifikator tambahan (3, 4, 5) bersifat opsional bila diperlukan.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row border-bottom mb-3 table-dark">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Upload Bukti Pembayaran</label>
                    <select name="verifikator_6[]" multiple="multiple" class="form-control select2 sinadi-select-verifikator" data-verifikator="6" data-tipe="RPL" style="width: 100%;">
                      <?php foreach ($pegawai as $data): ?>
                        <option value="<?= $data['id_data_pegawai']; ?>" <?= in_array($data['id_data_pegawai'], $arrayRpl6) ? "selected" : ""; ?>><?= $data['nip']; ?> | <?= $data['nama']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted text-right pr-2">Wajib terisi!</small>
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="card my-4">
                      <div class="card-header bg-dark text-white">
                        Unggah Bukti (RPL)
                      </div>
                      <div class="card-body text-dark">
                        <p class="card-text">Konfigurasi petugas unggah bukti pembayaran untuk RPL.</p>
                      </div>
                    </div>
                </div>
              </div>
              <!-- ============================================================= -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card -->

  </div>
</section>
<?php if ($activeMenu != "sinadiBlockir"): ?>
  <script>
    $(document).on('change', '.aksesPenggunaCheckboxSinadi', function() {
      insert = "/sinadi/insertAkses";
      update = "/sinadi/removeAkses";

      elemen = $(this);

      if (elemen.prop('checked')) {
        $.ajax({
          url: insert,
          data: {
            id: elemen.val()
          },
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
          },
          success: function(response) {
            if (response.data == 'sukses') {
              Toast.fire({
                icon: 'success',
                text: 'Akses berhasil diperbaharui!'
              });
            } else {
              Toast.fire({
                icon: 'warning',
                text: 'Akses gagal diperbaharui!'
              });
              elemen.prop('checked', false);
            }
            $("#overlay").hide();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            elemen.prop('checked', false);
            $("#overlay").hide();
            ToastError.fire({
              icon: 'error',
              // text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
              text: 'Kegagalan sistem!'
            });
          }
        });
      } else {
        $.ajax({
          url: update,
          data: {
            id: elemen.val()
          },
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
          },
          success: function(response) {
            if (response.data == 'sukses') {
              Toast.fire({
                icon: 'success',
                text: 'Akses berhasil diperbaharui!'
              });
            } else {
              Toast.fire({
                icon: 'warning',
                text: 'Akses gagal diperbaharui!'
              });
              elemen.prop('checked', true);
            }
            $("#overlay").hide();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            elemen.prop('checked', true);
            $("#overlay").hide();
            ToastError.fire({
              icon: 'error',
              text: 'Kegagalan sistem!'
            });
          }
        });
      }

    })
    $(document).on('change', '.aksesTimCheckboxSinadi', function() {
      insert = "/sinadi/insertAksesTim";
      update = "/sinadi/removeAksesTim";

      elemen = $(this);

      if (elemen.prop('checked')) {
        $.ajax({
          url: insert,
          data: {
            id: elemen.val()
          },
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
          },
          success: function(response) {
            if (response.data == 'sukses') {
              Toast.fire({
                icon: 'success',
                text: 'Akses Tim Kerja berhasil diberikan!'
              });
            } else {
              Toast.fire({
                icon: 'warning',
                text: 'Akses gagal diperbaharui!'
              });
              elemen.prop('checked', false);
            }
            $("#overlay").hide();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            elemen.prop('checked', false);
            $("#overlay").hide();
            ToastError.fire({
              icon: 'error',
              text: 'Kegagalan sistem!'
            });
          }
        });
      } else {
        $.ajax({
          url: update,
          data: {
            id: elemen.val()
          },
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
          },
          success: function(response) {
            if (response.data == 'sukses') {
              Toast.fire({
                icon: 'success',
                text: 'Akses Tim Kerja berhasil dicabut!'
              });
            } else {
              Toast.fire({
                icon: 'warning',
                text: 'Akses gagal diperbaharui!'
              });
              elemen.prop('checked', true);
            }
            $("#overlay").hide();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            elemen.prop('checked', true);
            $("#overlay").hide();
            ToastError.fire({
              icon: 'error',
              text: 'Kegagalan sistem!'
            });
          }
        });
      }
    });

  </script>
  <script>
    $('.sinadi-select-verifikator').change(function() {
      let select = $(this);
      let verifikator = $(this).data('verifikator');
      let tipe = ($(this).data('tipe') || '').toString().toUpperCase();

      console.log($(this).val());

      var formData = {
        verifikator: verifikator,
        data: select.val(),
        tipe: tipe
      };

      $.ajax({
        url: "/sinadi/set/verifikator",
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show();
        },
        success: function(response) {
          Toast.fire({
            icon: response.icon,
            text: response.text,
          });
        },
        complete: function() {
          $("#overlay").hide();
        }
      });
    });
  </script>
<?php endif; ?>

<?= $this->endSection(); ?>