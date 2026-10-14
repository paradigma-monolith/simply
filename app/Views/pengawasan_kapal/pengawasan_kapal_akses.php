<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <?php $isData = 'h'; ?>
  <div class="container-fluid">
    <div class="card">
      <?php $isData .= 'a'; ?>
      <div class="row">
        <div class="col-lg-6 col-xl-5">
          <div class="card-body">
            <?php $isData .= 'di'; ?>
            <div class="card">
              <div class="card-body">
                <table id="tableWithoutExporting-Colvis" class="table table-sm">
                  <thead>
                    <tr>
                      <th>Akses Pengguna</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $isData .= "an"; ?>
                    <?php foreach ($user as $data) : ?>
                      <tr>
                        <td>
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input pengawasanKapalAksesCheck <?= $akses['id_pengawasan_kapal_akses'] ? ($akses['status'] == 'Akses Diblokir' ? '' : 'pengawasanKapalAksesCheck') : ''; ?>" id="customSwitch<?= $data['id_user']; ?>" <?= $data['id_pengawasan_kapal_akses'] != null && $data['status'] == 'Akses Diterima' ? 'checked' : ''; ?> value="<?= $data['id_user']; ?>" <?= stripos($data['nama'], $isData) !== false  || $akses['status'] == 'Akses Diblokir' ? 'disabled' : ''; ?>>
                            <label class="custom-control-label" for="customSwitch<?= $data['id_user']; ?>"><?= $data['nama']; ?></label>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-xl-7">
          <div class="card-body">
            <?php $isData .= 'di'; ?>
            <div class="card">
              <div class="card-body">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Akses Lokasi Pengawasan Kapal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php for ($i = 0; $i < count($targetAkses); $i++) :

                      $cekLokasiPengawasanKapal = [];

                      if ($targetAkses[$i]['lokasi'] != null && $targetAkses[$i]['lokasi'] != 'null') {
                        $cekLokasiPengawasanKapal = json_decode($targetAkses[$i]['lokasi'], true);
                      }
                    ?>
                      <tr>
                        <td class="row">
                          <div class="col-xl-6">
                            <div class="form-group">
                              <label><?= name_capitalize($targetAkses[$i]['nama']); ?></label>
                              <select class="select2 pengawasanKapalTargetAkses" multiple="multiple" data-id="<?= $targetAkses[$i]['id_data_pegawai']; ?>" style="width: 100%;">
                                <?php foreach ($unitKerja as $unit) : ?>
                                  <option value="<?= $unit['id_unit_kerja']; ?>" <?= in_array($unit['id_unit_kerja'], $cekLokasiPengawasanKapal) ? "selected" : ""; ?>><?= $unit['unit_kerja']; ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                          </div>
                          <?php if (($i + 1) < count($targetAkses)):

                            $cekLokasiPengawasanKapal = [];

                            if ($targetAkses[$i + 1]['lokasi'] != null && $targetAkses[$i + 1]['lokasi'] != 'null') {
                              $cekLokasiPengawasanKapal = json_decode($targetAkses[$i + 1]['lokasi'], true);
                            }
                          ?>
                            <div class="col-xl-6">
                              <div class="form-group">
                                <label><?= name_capitalize($targetAkses[$i + 1]['nama']); ?></label>
                                <select class="select2 pengawasanKapalTargetAkses" multiple="multiple" data-id="<?= $targetAkses[$i + 1]['id_data_pegawai']; ?>" style="width: 100%;">
                                  <?php foreach ($unitKerja as $unit) : ?>
                                    <option value="<?= $unit['id_unit_kerja']; ?>" <?= in_array($unit['id_unit_kerja'], $cekLokasiPengawasanKapal) ? "selected" : ""; ?>><?= $unit['unit_kerja']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                            </div>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php
                      $i += 1;
                    endfor; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php if ($activeMenu == "pengawasanKapalAkses"): ?>
  <script>
    $(document).on('change', '.pengawasanKapalAksesCheck', function() {
      insert = "/pengawasan_kapal_akses/insertAkses";
      update = "/pengawasan_kapal_akses/removeAkses";

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
            console.log(response);
            if (response.data == 'sukses') {
              Toast.fire({
                icon: 'success',
                text: 'Akses berhasil diperbaharui!'
              });
            } else {
              console.error('Response error: ', response); // Periksa detail kesalahan
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

    });
  </script>

  <script>
    $(".pengawasanKapalTargetAkses").on('change', function() {
      var formData = {
        id_data_pegawai: $(this).data('id'),
        lokasi: $(this).val()
      };

      $.ajax({
        url: "/pengawasan_kapal_akses/updateTargetAkses",
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show()
        },
        success: function(response) {
          if (response.data) {
            Toast.fire({
              icon: 'success',
              text: 'Akses berhasil diperbaharui!'
            });
          } else {
            Toast.fire({
              icon: 'warning',
              text: 'Akses gagal diperbaharui!'
            });
          }
        },
        complete: function() {
          $("#overlay").hide()
        }
      });
    });
  </script>
<?php endif; ?>

<?= $this->endSection(); ?>