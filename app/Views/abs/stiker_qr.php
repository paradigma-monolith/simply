<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Generate File</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form action="/abs/manajemen_qr_code" id="formlulirManagemenQR" method="post">
              <div class="form-group">
                <select name="kertas" class="form-control">
                  <option value="A4"> Kertas A4 </option>
                  <option value="1m"> Kertas 1 Meter</option>
                </select>
              </div>
              <button class="btn btn-primary float-right export_label_abs">
                <i class="fas fa-qrcode"></i> Generate
              </button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Export File</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form action="/abs/export_file_qr_code" method="post" target="_blank">

              <div class="form-group">
                <label class="">Wilayah Kerja</label>
                <select required name="unit_kerja" class="form-control select2" style="width: 100%;">
                  <option value="">--Pilih Wilayah Kerja--</option>
                  <?php foreach ($unit_kerja as $data) : ?>
                    <option value="<?= $data['id_unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="">Lantai</label>
                <select required name="lantai" class="form-control select2" style="width: 100%;">
                  <option value="">--Pilih Lantai--</option>
                  <?php foreach ($lantai as $data) : ?>
                    <option value="<?= $data['id_abs_lantai']; ?>"><?= $data['lantai']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="">Ruang</label>
                <select name="ruang" class="form-control select2" style="width: 100%;">
                  <option value="">--Pilih Semua Ruang--</option>
                  <?php foreach ($ruang as $data) : ?>
                    <option value="<?= $data['id_abs_ruang']; ?>"><?= $data['ruang']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="">Kategori Barang</label>
                <select name="jenis" class="form-control select2" style="width: 100%;">
                  <option value="">--Pilih Semua Kategori--</option>
                  <?php foreach ($jenis as $data) : ?>
                    <option value="<?= $data['id_abs_jenis']; ?>"><?= $data['jenis']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="">Ukuran Kertas</label>
                <select required name="kertas" class="form-control">
                  <option value="A4"> Kertas A4 </option>
                  <option value="1m"> Kertas 1 Meter</option>
                </select>
              </div>
              <button class="btn btn-danger float-right">
                <i class="fas fa-file-pdf"></i> Export
              </button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-8">
        <div class="card">
          <div class="card-header">
            <h2>Penting: Batasan Proses Export/Download PDF dengan Request QR Code</h2>
            <p>Mohon diperhatikan bahwa proses export/download PDF dengan request QR code memiliki batasan untuk mencegah overload pada halaman ini. Mohon untuk tidak melakukan spam atau proses berkala secara berlebihan yang dapat menyebabkan beban berlebih pada sistem.</p>
            <p>Jika Anda memerlukan export/download PDF dengan request QR code dalam jumlah besar, harap lakukan dengan bijak dan sesuai kebutuhan. Kasih jeda beberapa menit setelah melakukan proses.</p>

          </div>
          <div class="card-body file_abs">

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    getExportFile()
  });


  $(".export_label_abs").click(function(e) {
    e.preventDefault()
    var formData = $('#formlulirManagemenQR').serialize();


    $.ajax({
      url: `/abs/ajax/countLabel`,
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show()
      },
      success: function(response) {
        if (response) {
          console.log(response);
          let loop = response.length;
          console.log(loop)
          getExportFile()
          $("#overlay").hide()

          // for (let z = 0; z < loop; z++) {
          //   exportPDF(response[0]['id_awal'], response[0]['id_akhir'], function(result) {
          //     if (result == 1) {
          //       console.log("Berhasil");
          //       if (z === (loop - 1)) {
          //         $("#overlay").hide();
          //         getExportFile()
          //         Swal.fire({
          //           position: 'top-end',
          //           icon: 'success',
          //           title: 'Berhasil',
          //           text: 'Exporting ' + (z + 1) + 'dari' + loop,
          //           showConfirmButton: true,
          //           allowOutsideClick: false,
          //         })
          //       } else {
          //         Swal.fire({
          //           position: 'top-end',
          //           icon: 'success',
          //           title: 'Berhasil',
          //           text: 'Exporting ' + (z + 1) + ' dari ' + loop,
          //           showConfirmButton: false,
          //           allowOutsideClick: false,
          //         })
          //       }
          //       z++;
          //     } else {
          //       exportPDF(id_awal, id_akhir, callback); // Melakukan request kembali
          //     }
          //   });
          // }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
      // error: function(xhr, ajaxOptions, thrownError) {
      //   alert()
      //   Swal.fire({
      //     text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
      //   })
      // }
    });
  });

  // function exportPDF(id_awal, id_akhir, callback) {

  //   var formData = {
  //     id_awal: id_awal,
  //     id_akhir: id_akhir,
  //     kertas: $('select[name="kertas"]').val()
  //   };

  //   $.ajax({
  //     url: '/abs/manajemen_qr_code',
  //     data: formData,
  //     method: 'POST',
  //     success: function(response) {
  //       callback(1);
  //     },
  //     error: function() {
  //       callback(0);
  //       console.log('Error exporting PDF');
  //     }
  //   });
  // }

  function getExportFile() {
    $.ajax({
      url: '/abs/ajax/getFile',
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(".file_abs").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
      },
      success: function(response) {
        if (response.data) {
          $(".file_abs").html(response.data)
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
    });
  }
</script>

<?= $this->endSection(); ?>