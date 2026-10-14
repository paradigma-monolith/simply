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
                      <th>Verifikator Kendaraan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $isData .= "an"; ?>
                    <?php foreach ($user as $data) : ?>
                      <tr>
                        <td>
                          <div class="input-group my-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">
                                <input onclick="checkBoxCheckedCheckKK(event)" <?= session()->get('role') == 'Admin' ? '' : 'disabled'; ?> type="checkbox" data-checkboxid="idVerifikatorKendaraan<?= $data['id_data_pegawai']; ?>" data-id="<?= $data['id_data_pegawai']; ?>" <?= isset($data['id_kendaraan_kantor_verifikator']) && $data['id_kendaraan_kantor_verifikator'] !== null ? "checked" : ""; ?>>
                              </div>
                            </div>
                            <input id="idVerifikatorKendaraan<?= $data['id_data_pegawai']; ?>" type="text" minlength="4" maxlength="4" <?= isset($data['id_kendaraan_kantor_verifikator']) && $data['id_kendaraan_kantor_verifikator'] !== null ? "" : "disabled"; ?> class="form-control data_pin_verifikator" placeholder="Masukkan empat angka PIN" value="<?= session()->get('role') == "Admin" ? $data['pin'] : ($data['id_kendaraan_kantor_verifikator'] == $akses['id_kendaraan_kantor_verifikator'] ? $data['pin'] : "...."); ?>" <?= session()->get('role') == 'Admin' ? '' : ($data['id_kendaraan_kantor_verifikator'] == $akses['id_kendaraan_kantor_verifikator'] ? "" : "readonly"); ?>>
                            <div class="input-group-append">
                              <span class="input-group-text"><?= name_capitalize($data['nama']); ?></span>
                            </div>
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

        </div>
      </div>
    </div>
    <!-- /.card -->
  </div>
</section>
<?php if ($activeMenu == "kendaraanKantorPengaturan"): ?>
  <script>
    function checkBoxCheckedCheckKK(event) {
      var checkbox = $(event.target);
      var checkboxID = $(event.target).data('checkboxid');
      var id = $(event.target).data('id');


      if (checkbox.is(':checked')) {
        $("#" + checkboxID).attr('disabled', false);
        $("#overflow").show()
        insertVerifikatorPegawai(id);
      } else {
        $("#" + checkboxID).attr('disabled', true);
        $("#" + checkboxID).val("")
        $("#overflow").show()
        deleteVerifikatorPegawai(id);
      }
    }

    function insertVerifikatorPegawai(id) {
      $.ajax({
        url: '/kendaraan_kantor/pengaturan/insert/' + id,
        method: 'get',
        dataType: 'json',
        beforeSend: function() {
          Swal.fire({
            icon: "info",
            html: "<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>",
            allowOutsideClick: false,
            showConfirmButton: false
          });
        },
        success: function(response) {
          if (response.data) {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: 'Berhasil ditambahkan',
              text: 'mereka harus memberikan PIN nya sendiri!',
            });
          }
        },
        complete: function() {
          $("#overflow").hide()
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
      });
    }

    function deleteVerifikatorPegawai(id) {
      $.ajax({
        url: '/kendaraan_kantor/pengaturan/delete/' + id,
        method: 'get',
        dataType: 'json',
        beforeSend: function() {
          Swal.fire({
            icon: "info",
            html: "<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>",
            allowOutsideClick: false,
            showConfirmButton: false
          });
        },
        success: function(response) {
          if (response.data) {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: 'Berhasil dihapus',
            });
          }
        },
        complete: function() {
          $("#overflow").hide()
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
      });
    }

    $(document).on('change', 'input')
  </script>

  <script>
    $(document).on("change", '.data_pin_verifikator', function() {
      var value = $(this).val();
      if (value.length === 4) {
        var formData = {
          pin: value
        };

        $.ajax({
          url: '/kendaraan_kantor/pengaturan/update_pin',
          data: formData,
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show()
          },
          success: function(response) {
            if (response.data) {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "PIN berhasil diperbaharui",
                showConfirmButton: false,
                timer: 1500
              });
            } else {
              Swal.fire({
                position: "top-end",
                icon: "warning",
                title: "PIN diperbaharui oleh pengguna!",
                showConfirmButton: false,
                timer: 1500
              });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          },
          complete: function() {
            $("#overlay").hide()
          }
        });
      }
    });
  </script>
<?php endif; ?>

<?= $this->endSection(); ?>