<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
  .plus {
    cursor: pointer;
    top: 0;
    right: 1px;
    height: 1em;
    width: 1em;
    margin-top: 8px;
    display: block;
    position: absolute;
    color: white;
    border: .15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New", Courier, monospace;
    line-height: 1em;
    content: "+";
    background-color: #0275d8;
  }

  .min {
    cursor: pointer;
    top: 0;
    right: 1px;
    height: 1em;
    width: 1em;
    margin-top: 8px;
    display: block;
    position: absolute;
    color: white;
    border: .15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New", Courier, monospace;
    line-height: 1em;
    content: "-";
    background-color: #d33333;
  }

  .min-disabled {
    cursor: not-allowed;
    top: 0;
    right: 1px;
    height: 1em;
    width: 1em;
    margin-top: 8px;
    display: block;
    position: absolute;
    color: white;
    border: .15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New", Courier, monospace;
    line-height: 1em;
    content: "-";
  }
</style>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card card-default">
          <form id="absSimpanLokasi" action="/abs/simpan_lokasi" method="post" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="id_abs_bangunan" value="" readonly>
            <div class="card-header">
              <h3 class="card-title">Formulir Tambah Data ABS BMN</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Wilayah Kerja</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="wilayah_kerja" id="wilayah_kerja" style="width:100%;">
                    <option value="">-- Pilih Wilayah Kerja --</option>
                    <?php foreach ($wilayahKerja as $dataWilker) : ?>
                      <option value="<?= $dataWilker['id_unit_kerja']; ?>"><?= $dataWilker['unit_kerja']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Lantai</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="lantai" id="lantai" style="width:100%;">
                    <option value="">-- Pilih Lantai --</option>
                    <?php foreach ($lantai as $dataLantai) : ?>
                      <option value="<?= $dataLantai['id_abs_lantai']; ?>"><?= $dataLantai['lantai']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Ruang</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="ruang" id="ruang" style="width:100%;">
                    <option value="">-- Pilih Ruang --</option>
                    <?php foreach ($ruang as $dataRuang) : ?>
                      <option value="<?= $dataRuang['id_abs_ruang']; ?>"><?= $dataRuang['ruang']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Kategori Kelompok</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="jenis_barang" id="jenis_barang" style="width:100%;" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($jenisBarang as $dataJenisBarang) : ?>
                      <option value="<?= $dataJenisBarang['id_abs_jenis']; ?>"><?= $dataJenisBarang['jenis']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col mb-2">
                  <button type="button" class="btn float-right btn-sm btn-warning" onclick="loadLabelABS()"><i class="fas fa-tags"></i> Load label </button>
                </div>
              </div>

              <div id="labelRange">
                <div class="form-group row">
                  <label class="col-sm-2"><span>Data Inventaris</span><span class="plus">+</span></label>
                  <div class="col-sm-5">
                    <input readonly type="text" name="label[]" placeholder="Entri Informasi Barang" value="Nama Barang" class="form-control firstLabelTambahAbs" required>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="isi_label[]" placeholder="...." value="" class="form-control" required>
                  </div>
                </div>
              </div>
              <!-- Tempat untuk menambahkan input baru -->
              <div id="newInputs">
                <div class="form-group row">
                  <label class="col-sm-2"><span class="min-disabled bg-secondary">-</span></label>
                  <div class="col-sm-5">
                    <input readonly type="text" name="label[]" placeholder="Entri Informasi Barang" value="Merek" class="form-control firstLabelTambahAbs" required>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="isi_label[]" placeholder="...." value="" class="form-control" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2"><span class="min-disabled bg-secondary">-</span></label>
                  <div class="col-sm-5">
                    <input readonly type="text" name="label[]" placeholder="Entri Informasi Barang" value="NUP" class="form-control firstLabelTambahAbs" required>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="isi_label[]" placeholder="...." value="" class="form-control" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2"><span class="min-disabled bg-secondary">-</span></label>
                  <div class="col-sm-5">
                    <input readonly type="text" name="label[]" placeholder="Entri Informasi Barang" value="Kode Barang" class="form-control firstLabelTambahAbs" required>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="isi_label[]" placeholder="...." value="" class="form-control" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2"><span class="min-disabled bg-secondary">-</span></label>
                  <div class="col-sm-5">
                    <input readonly type="text" name="label[]" placeholder="Entri Informasi Barang" value="Kode Satker" class="form-control firstLabelTambahAbs" required>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="isi_label[]" readonly placeholder="...." value="024050200415712000KD" class="form-control" required>
                  </div>
                </div>
                
                <div class="form-group row">
                  <label class="col-sm-2"><span class="min-disabled bg-secondary">-</span></label>
                  <div class="col-sm-5">
                    <input readonly type="text" name="label[]" placeholder="Entri Informasi Barang" value="Tgl Perolehan" class="form-control firstLabelTambahAbs" required>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="isi_label[]" placeholder="...." value="" class="form-control" required>
                  </div>
                </div>
              </div>


              <div class="form-group row">
                <label class="col-sm-2">Status Tersedia</label>
                <div class="col-sm-10">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_tersedia" id="tersedia" value="Tersedia" required>
                    <label class="form-check-label" for="tersedia">Barang berada di lokasi</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_tersedia" id="tidak_tersedia" value="Tidak Tersedia" required>
                    <label class="form-check-label" for="tidak_tersedia">Barang tidak ada di lokasi</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Gambar</label>
                <div class="col-sm-10">
                  <div class="d-inline-block" style="width: 200px;">
                    <img src="<?= generate_qr('', 'QR Code SIMAN'); ?>" class="img-thumbnail" style="width: 200px;">
                    <input type="text" class="form-control" name="kode_qr_code_pusat" readonly value="">
                  </div>
                  <div class="d-inline-block" style="width: 200px;">
                    <p class="text-center p-0 m-0"><strong>Gambar Barang</strong></p>
                    <img src="/dist/img/abs/default.png" class="img-thumbnail" style="width: 200px;" id="preview">
                    <div class="btn-group btn-block mb-1" role="group" aria-label="Basic example">
                      <label class="btn btn-secondary" for="absImage">Upload</label>
                      <label type="button" class="btn btn-secondary" onclick="viewGaleryABS()">Galery</label>
                    </div>
                    <input type="file" onchange="previewImage(event)" class="d-none custom-file-input form-control" id="absImage" name="image" accept="image/*">
                    <input type="hidden" name="id_abs_image" value="1">
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  $(document).on('input', 'input[name="isi_label[]"]', function() {
    var index = $('input[name="isi_label[]"]').length;
    var kodeSatker = "";
    var kodeBarang = "";
    var kodeNUP = "";

    for (let i = 0; i < index; i++) {
      if ($('input[name="label[]"]').eq(i).val() == "Kode Satker")
        kodeSatker = $('input[name="isi_label[]"]').eq(i).val()
      if ($('input[name="label[]"]').eq(i).val() == "Kode Barang")
        kodeBarang = $('input[name="isi_label[]"]').eq(i).val()
      if ($('input[name="label[]"]').eq(i).val() == "NUP")
        kodeNUP = $('input[name="isi_label[]"]').eq(i).val()
    }

    var qrCode = kodeSatker + '*' + kodeBarang + '*' + kodeNUP;
    $('input[name="kode_qr_code_pusat"]').val(qrCode);
  })

  function previewImage(event) {
    $('input[name="id_abs_image"]').val('')

    var reader = new FileReader();
    reader.onload = function() {
      var imgElement = document.getElementById('preview');
      imgElement.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }

  function viewGaleryABS() {
    $.ajax({
      url: '/abs/getImage',
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $('#overlay').show()
      },
      success: function(response) {
        if (response.data) {
          $("#absImageGalery").find(".modal-body").html(response.data)
          $("#absImageGalery").modal('show');
        }
        $('#overlay').hide()
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Oops... ERROR SINTAKS',
          text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
          // text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
        })
      }
    });
  }
</script>


<?= $this->endSection(); ?>