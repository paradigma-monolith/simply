<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>

</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form id="uklwTambahPemkes" action="/uklw/tambah_pemkes" method="post">
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
                                <input class="form-check-input" type="radio" required name="status_pemeriksaan" id="radio2status_pemeriksaan" <?= old('status_pemeriksaan') ? (old('status_pemeriksaan') == 'Jemaah Haji' ? 'checked' : '') : ''; ?> value="Jemaah Haji">
                                <label class="form-check-label" for="radio2status_pemeriksaan">
                                  Jemaah Haji
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
                          <input type="text" value="BERAT BADAN (KG)" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="TINGGI BADAN (CM)" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="INDEKS MASSA TUBUH (IMT)" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="TEKANAN DARAH (mmHg)" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="HEART RATE (Per Menit)" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="SUHU (*C)" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="SATURASI OKSIGEN" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="RESPIRATION RATE (Per Menit)" name="pemeriksaan_fisik[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <input type="text" value="" name="hasil_pemeriksaan_fisik[]" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);" placeholder="gunakan tanda (.) titik untuk desimal">
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
                        <th></th>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="RIWAYAT KESEHATAN DAHULU" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <select required multiple="multiple" name="hasil_riwayat_penyakit[]" class="d-block m-0 form-control select2" style="background-color: rgba(255,255,255,.5);">
                            <option value=""> --Pilih Riwayat Penyakit--</option>
                            <?php foreach ($getRiwayatPenyakit as $data) : ?>
                              <option value="<?= $data['istilah_penyakit']; ?>"><?= $data['istilah_penyakit']; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="RIWAYAT PENYAKIT KELUARGA" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <select required multiple="multiple" name="hasil_riwayat_penyakit[]" class="d-block m-0 form-control select2" style="background-color: rgba(255,255,255,.5);">
                            <option value=""> --Pilih Riwayat Penyakit--</option>
                            <?php foreach ($getRiwayatPenyakit as $data) : ?>
                              <option value="<?= $data['istilah_penyakit']; ?>"><?= $data['istilah_penyakit']; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <th class="p-0 align-middle">
                          <input type="text" value="RIWAYAT KESEHATAN SEKARANG" name="riwayat_penyakit[]" readonly required class="bg-transparent d-block pl-4 m-0 form-control" style="color: #000; font-weight: 700;">
                        </th>
                        <td class="p-0">
                          <select required multiple="multiple" name="hasil_riwayat_penyakit[]" class="d-block m-0 form-control select2" style="background-color: rgba(255,255,255,.5);">
                            <option value=""> --Pilih Riwayat Penyakit--</option>
                            <?php foreach ($getRiwayatPenyakit as $data) : ?>
                              <option value="<?= $data['istilah_penyakit']; ?>"><?= $data['istilah_penyakit']; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>
                          <input type="hidden" name="hasil_riwayat_penyakit_value[]">
                          <input type="hidden" name="hasil_riwayat_penyakit_value[]">
                          <input type="hidden" name="hasil_riwayat_penyakit_value[]">
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
  $('input[name="hasil_pemeriksaan_fisik[]"]:eq(0), input[name="hasil_pemeriksaan_fisik[]"]:eq(1), input[name="hasil_pemeriksaan_fisik[]"]:eq(2)').on('input', function() {
    var kg = parseFloat($('input[name="hasil_pemeriksaan_fisik[]"]').eq(0).val()) || 0;
    var cm = parseFloat($('input[name="hasil_pemeriksaan_fisik[]"]').eq(1).val()) || 0;
    var imtInput = $('input[name="hasil_pemeriksaan_fisik[]"]').eq(2);

    if (kg !== 0 && cm !== 0) {
      var hasil = kg / (cm / 100);
      imtInput.attr('placeholder', hasil.toFixed(2));
    } else {
      imtInput.attr('placeholder', ''); // Reset placeholder if either kg or cm is empty
    }
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

  // ==================================================================================================================================

  // $(document).ready(function() {
  //   var nilai = null
  //   setTimeout(() => {
  //     $.ajax({
  //       url: '/uklw/ajax/searchData',
  //       data: {
  //         nilai: nilai
  //       },
  //       dataType: 'json',
  //       beforeSend: function() {
  //         $("#overlay").show();
  //       },
  //       success: function(response) {
  //         if (response.data) {
  //           // Hapus opsi sebelum menambahkan yang baru
  //           var $selectElement = $('select[name="hasil_riwayat_penyakit[]"]');
  //           $selectElement.html('');

  //           // Tambahkan opsi ke elemen select
  //           $.each(response.data, function(index, item) {
  //             $selectElement.append('<option value="' + item.value + '">' + item.label + '</option>');
  //           });
  //           $("#overlay").hide();
  //         }
  //       },
  //       error: function(xhr, ajaxOptions, thrownError) {
  //         alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
  //       }
  //     });
  //   }, 200);
  // });

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

  // ==================================================================================================================================

  // let timeoutId;
  // var index = 0;
  // var text = '';
  // var typingInterval;
  // var nilaiSebelumnya = '';

  // $(document).on('input', '.select2-search__field', function() {
  //   let $inputField = $(this);

  //   clearTimeout(timeoutId);

  //   timeoutId = setTimeout(function() {
  //     var $selectElement = $('select[name="hasil_riwayat_penyakit[]"]');
  //     var selectedOptions = $selectElement.find('option:selected');
  //     var selectedOptionsArray = selectedOptions.toArray();
  //     console.log(selectedOptionsArray);

  //     $selectElement.val('');

  //     getDataICDS($inputField.val(), $inputField, selectedOptionsArray);
  //   }, 2000);
  // });

  // function getDataICDS(nilai, $inputField, selectedValue) {
  //   if (nilai.length > 1 && nilaiSebelumnya !== nilai) {
  //     nilaiSebelumnya = nilai;
  //     $.ajax({
  //       url: '/uklw/ajax/searchData',
  //       data: {
  //         nilai: nilai
  //       },
  //       dataType: 'json',
  //       beforeSend: function() {
  //         $("#overlay").show();
  //       },
  //       success: function(response) {
  //         if (response.data) {
  //           // Jalankan fungsi untuk mengetikkan teks
  //           index = 0; // Setel ulang indeks
  //           text = nilai;
  //           $inputField.val("");
  //           typingInterval = setInterval(function() {
  //             typeText($inputField);
  //           }, 10);

  //           // Hapus opsi sebelum menambahkan yang baru
  //           var $selectElement = $('select[name="hasil_riwayat_penyakit[]"]');
  //           $selectElement.html('');

  //           // Tambahkan opsi ke elemen select
  //           $.each(response.data, function(index, item) {
  //             $selectElement.append(selectedValue);
  //             $selectElement.append('<option value="' + item.value + '">' + item.label + '</option>');
  //           });
  //         }
  //         $("#overlay").hide();
  //       },
  //       error: function(xhr, ajaxOptions, thrownError) {
  //         alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
  //       }
  //     });
  //   }
  // }

  // function typeText($inputField) {
  //   if (index < text.length) {
  //     var currentText = $inputField.val();
  //     var newText = currentText + text.charAt(index);
  //     $inputField.val(newText);
  //     $inputField.trigger('input');
  //     index++;
  //   } else {
  //     clearInterval(typingInterval);
  //   }
  // }
</script>

<!-- /.content -->
<?= $this->endSection(); ?>