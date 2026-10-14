<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Verifikasi Lokasi</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <div class="form-group">
              <label class="">Wilayah Kerja</label>
              <select name="unit_kerja" class="form-control select2" style="width: 100%;">
                <option value="">--Pilih Wilayah Kerja--</option>
                <?php foreach ($unit_kerja as $data) : ?>
                  <option value="<?= $data['id_unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="">Lantai</label>
              <select name="lantai" class="form-control select2" style="width: 100%;">
                <option value="">--Pilih Lantai--</option>
                <?php foreach ($lantai as $data) : ?>
                  <option value="<?= $data['id_abs_lantai']; ?>"><?= $data['lantai']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="">Ruang</label>
              <select name="ruang" class="form-control select2" style="width: 100%;">
                <option value="">--Pilih Ruang--</option>
                <?php foreach ($ruang as $data) : ?>
                  <option value="<?= $data['id_abs_ruang']; ?>"><?= $data['ruang']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="">Kategori Barang</label>
              <select name="jenis" class="form-control select2" style="width: 100%;">
                <option value="">--Pilih Kategori--</option>
                <?php foreach ($jenis as $data) : ?>
                  <option value="<?= $data['id_abs_jenis']; ?>"><?= $data['jenis']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="text-right">
              <button type="button" class="btn btn-info searchABS"><i class="fas fa-search"></i> Temukan data</button>
            </div>
          </div>

          <div class="card-footer">
          </div>
        </div>
      </div>
      <div class="col-lg-8 viewABSDataInventaris">

      </div>
    </div>
  </div>
</section>

<script>
  $(document).on('click', '.searchABS', function() {
    $("#absScanQRCode").modal("show");
    $("#myFocusInputQRABS").focus();
  })

  function keepFocus() {
    document.getElementById('myFocusInputQRABS').focus();
  }

  $(document).on('submit', '#formulirInputQRABS', function(e) {
    e.preventDefault();

    getDataAbsVerifikasiLokasi();
  })

  function getDataAbsVerifikasiLokasi() {
    var formData = $('#formulirInputQRABS').serialize();

    $.ajax({
      url: $('#formulirInputQRABS').attr('action'),
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $('#overlay').show()
      },
      success: function(response) {
        if (response.data) {
          var unit_kerja = $('select[name="unit_kerja"]').val();
          var lantai = $('select[name="lantai"]').val();
          var ruang = $('select[name="ruang"]').val();
          var jenis = $('select[name="jenis"]').val();
          if (unit_kerja == '') unit_kerja = 0;
          if (lantai == '') lantai = 0;
          if (ruang == '') ruang = 0;
          if (jenis == '') jenis = 0;

          $(".viewABSDataInventaris").html(response.data)

          $("#abs_status_verifikasi_lokasi").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`);

          var id_unit_kerja = response.getData.id_unit_kerja;
          var id_abs_lantai = response.getData.id_abs_lantai;
          var id_abs_ruang = response.getData.id_abs_ruang;
          var id_abs_jenis = response.getData.id_abs_jenis;
          setTimeout(function() {
            if (unit_kerja == id_unit_kerja && lantai == id_abs_lantai && ruang == id_abs_ruang && jenis == id_abs_jenis) {
              $("#abs_status_verifikasi_lokasi").html('<span class="badge badge-success">Valid</span>')
            } else {
              $("#abs_status_verifikasi_lokasi").html('<span class="badge badge-danger">Tidak Valid</span>')
            }
          }, 500);
        }
        $('#overlay').hide()
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
    });
  }

  function absMoveVerifikationLocation(id) {
    var unit_kerja = $('select[name="unit_kerja"]').val();
    var lantai = $('select[name="lantai"]').val();
    var ruang = $('select[name="ruang"]').val();
    var jenis = $('select[name="jenis"]').val();


    if (unit_kerja != "" && lantai != "" && ruang != "" && jenis != "") {
      var formData = {
        id_abs_bangunan: id,
        id_unit_kerja: unit_kerja,
        id_abs_lantai: lantai,
        id_abs_ruang: ruang,
        id_abs_jenis: jenis
      };

      var button = $("#abs_btn_verifikasi_lokasi");
      var elementButton = $("#abs_btn_verifikasi_lokasi").html();

      $.ajax({
        url: '/abs/konfirmasiDataInventaris',
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $(button).attr('disabled', true);
          $(button).html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
        },
        success: function(response) {
          if (response.data) {
            Swal.fire({
              icon: 'success',
              title: 'Pembaharuan data!',
              html: 'Pembaharuan <b>Data Inventaris</b> berhasil...'
            })
            getDataAbsVerifikasiLokasi()
          }
        }
      });
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'Tidak dapat diperbaharui!',
        html: 'Mohon isi pembaharuan data pada <b>Formulir Verifikasi Lokasi</b> untuk pembaharuan data inventaris'
      })
    }
  }
</script>

<?= $this->endSection(); ?>