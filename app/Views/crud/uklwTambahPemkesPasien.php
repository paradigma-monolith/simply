<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>

</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form id="uklwTambahPemkes" action="/uklw/tambah_pemkes" method="post" autocomplete="off">
        <input type="hidden" name="id_uklw_pemkes" value="">
        <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
        <div class="card card-default bg-transparent">
          <div class="card-body p-0">
            <div class="bs-stepper">
              <div class="bs-stepper-header" role="tablist">
                <!-- your steps here -->
                <div class="step" data-target="#part1">
                  <button type="button" class="step-trigger" role="tab" aria-controls="part1" id="part1-trigger">
                    <span class="bs-stepper-circle"><i class="fas fa-hand-holding-medical"></i></span>
                    <span class="bs-stepper-label">Pemeriksaan Fisik & Riwayat Penyakit</span>
                  </button>
                </div>
                <hr>
                <div class="form-group mr-4">
                  <select name="kota" class="form-control select2" required>
                    <option value="">--Kota/Kab.--</option>
                    <?php foreach ($kota as $data) : ?>
                      <option value="<?= $data['kota_kabupaten']; ?>"><?= $data['kota_kabupaten']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="bs-stepper-content">
                <!-- your steps content here -->
                <div id="part1" class="content" role="tabpanel" aria-labelledby="part1-trigger">
                  <table class="table table-info table-hover table-striped table-bordered table-sm align-middle">
                    <thead>
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 30%;">DATA PASIEN</th>
                        <th></th>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle"><?= $getData['identitas']; ?></th>
                        <td class="p-0 px-3">
                          <?= $getData['nomor_identitas']; ?>
                        </td>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle">NAMA SESUAI IDENTITAS</th>
                        <td class="p-0 px-3">
                          <?= $getData['nama']; ?>
                        </td>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle">TANGGAL LAHIR</th>
                        <td class="p-0 px-3">
                          <?= mediumdate_indo($getData['tgl_lahir']); ?>
                        </td>
                      </tr>
                      <tr>
                        <th class="pl-4 align-middle">STATUS PEMERIKSAAN</th>
                        <td class="p-0">
                          <div class="row text-center">
                            <div class="col">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" required name="status_pemeriksaan" id="radio1status_pemeriksaan" <?= old('status_pemeriksaan') ? (old('status_pemeriksaan') == 'Penumpang' ? 'checked' : '') : ''; ?> value="Penumpang">
                                <label class="form-check-label" for="radio1status_pemeriksaan">
                                  Penumpang
                                </label>
                              </div>
                            </div>
                            <div class="col">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" required name="status_pemeriksaan" id="radio2status_pemeriksaan" <?= old('status_pemeriksaan') ? (old('status_pemeriksaan') == 'Awak/Kru' ? 'checked' : '') : ''; ?> value="Awak/Kru">
                                <label class="form-check-label" for="radio2status_pemeriksaan">
                                  Awak/Kru
                                </label>
                              </div>
                            </div>
                            <div class="col">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" required name="status_pemeriksaan" id="radio3status_pemeriksaan" <?= old('status_pemeriksaan') ? (old('status_pemeriksaan') == 'Petugas' ? 'checked' : '') : ''; ?> value="Petugas">
                                <label class="form-check-label" for="radio3status_pemeriksaan">
                                  Petugas
                                </label>
                              </div>
                            </div>
                            <div class="col">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" required name="status_pemeriksaan" id="radio4status_pemeriksaan" <?= old('status_pemeriksaan') ? (old('status_pemeriksaan') == 'Penjamah Makanan' ? 'checked' : '') : ''; ?> value="Penjamah Makanan">
                                <label class="form-check-label" for="radio4status_pemeriksaan">
                                  Penjamah Makanan
                                </label>
                              </div>
                            </div>
                            <div class="col">
                              <div class="form-check">
                                <input class="form-check-input" type="radio" required name="status_pemeriksaan" id="radio5status_pemeriksaan" <?= old('status_pemeriksaan') ? (old('status_pemeriksaan') == 'Masyarakat Umum' ? 'checked' : '') : ''; ?> value="Masyarakat Umum">
                                <label class="form-check-label" for="radio5status_pemeriksaan">
                                  Masyarakat Umum
                                </label>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <!-- ================================================================================================================ -->
                      <tr>
                        <td colspan="2">
                          <div class="penjamahMakanan2">
                            <?= $this->include('/uklw/data_form/wawancara_penjamah_makanan'); ?>
                          </div>
                        </td>
                      </tr>
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 30%;">PEMERIKSAAN FISIK</th>
                        <th></th>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Berat Badan" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="Kg" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Tinggi Badan" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="Cm" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Indeks Massa Tubuh" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="IMT" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Tekanan Darah" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="mmHg" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Heart Rate" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="Per Menit" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Suhu" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="*C" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Saturasi Oksigen" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="%" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Respiration Rate" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="Per Menit" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Lingkar Perut" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 input-number form-control" placeholder="0" value="" name="hasil_pemeriksaan_fisik[]">
                            <div class="input-group-append">
                              <input type="text" class="input-group-text" value="Cm" readonly name="satuan_pemeriksaan_fisik[]" style="width: 80px;">
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <div class="penjamahMakanan1">
                            <?= $this->include('/uklw/data_form/hygiene_penjamah_makanan'); ?>
                          </div>
                        </td>
                      </tr>
                      <!-- ================================================================================================================ -->
                      <tr class="bg-primary">
                        <th class="pl-4" style="width: 30%;">RIWAYAT PENYAKIT</th>
                        <th class="text-right"></th>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Riwayat Kesehatan Dahulu" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0 d-flex align-items-center">
                          <select required multiple="multiple" name="hasil_riwayat_penyakit[]" class="d-block m-0 form-control select2 select-riwayat-penyakit" style="background-color: rgba(255,255,255,.5);">
                            <option value=""> --Pilih Riwayat Penyakit--</option>
                            <?php foreach ($getRiwayatPenyakit as $data) : ?>
                              <option value="<?= $data['istilah_penyakit']; ?>"><?= $data['istilah_penyakit']; ?></option>
                            <?php endforeach; ?>
                          </select>
                          <button type="button" class="btn btn-sm btn-primary uklw-add-riwayat-penyakit" data-select="0"><i class="fas fa-plus"></i></button>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Riwayat Penyakit Keluarga" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0 d-flex align-items-center">
                          <select required multiple="multiple" name="hasil_riwayat_penyakit[]" class="d-block m-0 form-control select2 select-riwayat-penyakit" style="background-color: rgba(255,255,255,.5);">
                            <option value=""> --Pilih Riwayat Penyakit--</option>
                            <?php foreach ($getRiwayatPenyakit as $data) : ?>
                              <option value="<?= $data['istilah_penyakit']; ?>"><?= $data['istilah_penyakit']; ?></option>
                            <?php endforeach; ?>
                          </select>
                          <button type="button" class="btn btn-sm btn-primary uklw-add-riwayat-penyakit" data-select="1"><i class="fas fa-plus"></i></button>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Riwayat Kesehatan Sekarang" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0 d-flex align-items-center">
                          <select required multiple="multiple" name="hasil_riwayat_penyakit[]" class="d-block m-0 form-control select2 select-riwayat-penyakit" style="background-color: rgba(255,255,255,.5);">
                            <option value=""> --Pilih Riwayat Penyakit--</option>
                            <?php foreach ($getRiwayatPenyakit as $data) : ?>
                              <option value="<?= $data['istilah_penyakit']; ?>"><?= $data['istilah_penyakit']; ?></option>
                            <?php endforeach; ?>
                          </select>
                          <button type="button" class="btn btn-sm btn-primary uklw-add-riwayat-penyakit" data-select="2"><i class="fas fa-plus"></i></button>
                        </td>
                      </tr>
                      <tr class="d-none">
                        <td></td>
                        <td>
                          <input type="hidden" name="hasil_riwayat_penyakit_value[]">
                          <input type="hidden" name="hasil_riwayat_penyakit_value[]">
                          <input type="hidden" name="hasil_riwayat_penyakit_value[]">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="Obat-obatan yang rutin diminum" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <div class="input-group">
                            <input type="text" class="d-block m-0 form-control" placeholder="......" value="" name="hasil_riwayat_penyakit_value[]">
                          </div>
                        </td>
                      </tr>
                    </thead>
                  </table>
                  <button type="submit" class="btn btn-success float-right mx-2"><i class="fas fa-share"></i> Simpan</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer"></div>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
$(".uklw-add-riwayat-penyakit").click(function () {
  // Ambil nilai data-select dari tombol yang diklik
  let selectValue = $(this).data('select');

  Swal.fire({
    title: "Masukkan nama penyakit baru",
    input: "text",
    showCancelButton: true,
    cancelButtonText: "Batal",
    confirmButtonText: "Simpan",
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      let istilahPenyakit = result.value;

      $.ajax({
        url: "/uklw/add/riwayat_penyakit",
        method: "POST",
        data: {
          istilah_penyakit: istilahPenyakit
        },
        dataType: "json",
        beforeSend: function () {
          Swal.fire({
            title: "Loading...",
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
        },
        success: function (response) {
          Toast.fire({
            icon: response.icon,
            text: response.text
          });

          if (response.icon == "success") {
            // Targetkan elemen <select> berdasarkan nilai selectValue
            let targetSelect = $(".select-riwayat-penyakit").eq(selectValue);

            // Cek apakah opsi sudah ada di dalam select
            let isExist = targetSelect.find("option[value='" + istilahPenyakit + "']").length > 0;
            if (!isExist) {
              // Tambahkan opsi baru ke dalam select yang ditargetkan
              let newOption = new Option(istilahPenyakit, istilahPenyakit, true, true);
              targetSelect.append(newOption).trigger("change");
            } else {
              Toast.fire({
                text: "Nama penyakit ini sudah ada dalam daftar.",
                icon: "warning",
              });
            }
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          Toast.fire({
            icon: 'error',
            text: 'Terjadi kesalahan saat menyimpan data!'
          });
        },
        complete: function () {
        }
      });
    }
  });
});
</script>

<script>
  $('input[name="hasil_pemeriksaan_fisik[]"]:eq(0), input[name="hasil_pemeriksaan_fisik[]"]:eq(1)').on('input', function() {
    var kg = parseFloat($('input[name="hasil_pemeriksaan_fisik[]"]').eq(0).val()) || 0;
    var cm = parseFloat($('input[name="hasil_pemeriksaan_fisik[]"]').eq(1).val()) || 0;
    var imtInput = $('input[name="hasil_pemeriksaan_fisik[]"]').eq(2);

    if (kg !== 0 && cm !== 0) {
      var hasil = kg / ((cm / 100) * (cm / 100));
      imtInput.attr('placeholder', hasil.toFixed(2));
      imtInput.attr('value', hasil.toFixed(2));
    } else {
      imtInput.attr('placeholder', '');
      imtInput.attr('value', '');
    }
  });


  document.querySelectorAll('input[name="hasil_pemeriksaan_fisik[]"]').forEach((input, index, inputs) => {
    input.addEventListener('keydown', (event) => {
      if (event.key === 'Tab') {
        if (index < inputs.length - 1) {
          event.preventDefault();
          inputs[index + 1].focus();
        } else {
          event.preventDefault();
          const firstSelect = document.querySelector('select[name="hasil_riwayat_penyakit[]"]');
          if (firstSelect) firstSelect.focus();
        }
      }
    });
  });



  var penjamahMakanan1 = $('.penjamahMakanan1').html()
  var penjamahMakanan2 = $('.penjamahMakanan2').html()
  let intervalID;

  $(document).ready(function() {
    getFormulirPenjamah()
  });

  $(document).on('change', 'input[name="status_pemeriksaan"]', function() {
    getFormulirPenjamah()
  })

  function getFormulirPenjamah() {
    var status_pemeriksaan = $('input[name="status_pemeriksaan"]:checked').val();
    if (status_pemeriksaan) {
      if (status_pemeriksaan == 'Penjamah Makanan') {
        $('.penjamahMakanan1').html(penjamahMakanan1)
        $('.penjamahMakanan2').html(penjamahMakanan2)
      } else {
        $('.penjamahMakanan1').html('')
        $('.penjamahMakanan2').html('')
      }
    } else {
      $('.penjamahMakanan1').html('')
      $('.penjamahMakanan2').html('')
    }
  }


  $(document).on('change', 'select[name="hasil_riwayat_penyakit[]"]:eq(0)', function() {
    let $inputField = $(this);
    var value = $inputField.val()

    $('input[name="hasil_riwayat_penyakit_value[]"]').eq(0).val(value);
  });
  $(document).on('change', 'select[name="hasil_riwayat_penyakit[]"]:eq(1)', function() {
    let $inputField = $(this);
    var value = $inputField.val()

    $('input[name="hasil_riwayat_penyakit_value[]"]').eq(1).val(value);
  });

  $(document).on('change', 'select[name="hasil_riwayat_penyakit[]"]:eq(2)', function() {
    let $inputField = $(this);
    var value = $inputField.val()

    $('input[name="hasil_riwayat_penyakit_value[]"]').eq(2).val(value);
  });
</script>


<!-- /.content -->
<?= $this->endSection(); ?>