<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form action="/uklw/edit_pasien" method="post">
        <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
        <table class="table table-info table-hover table-striped table-bordered table-sm align-middle">
          <thead>
            <tr class="bg-primary">
              <th class="pl-4" style="width: 30%;">DATA</th>
              <th class="pl-4">ISIAN</th>
            </tr>
            <tr>
              <th class="pl-4 align-middle">IDENTITAS</th>
              <td class="p-0">
                <div class="row text-center">
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="identitas" id="radio1identitas" <?= $getData['identitas'] ? ($getData['identitas'] == 'NIK' ? 'checked' : '') : ''; ?> value="NIK">
                      <label class="form-check-label" for="radio1identitas">
                        NIK
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="identitas" id="radio2identitas" <?= $getData['identitas'] ? ($getData['identitas'] == 'Paspor' ? 'checked' : '') : ''; ?> value="Paspor">
                      <label class="form-check-label" for="radio2identitas">
                        Paspor
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="identitas" id="radio3identitas" <?= $getData['identitas'] ? ($getData['identitas'] == 'No Porsi' ? 'checked' : '') : ''; ?> value="No Porsi">
                      <label class="form-check-label" for="radio3identitas">
                        No Porsi
                      </label>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NOMOR IDENTITAS</th>
              <td class="p-0">
                <input type="text" name="nomor_identitas" value="<?= $getData['nomor_identitas']; ?>" required class="d-block m-0 form-control number" style="background-color: rgba(255,255,255,.5);">
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NAMA SESUAI KTP</th>
              <td class="p-0">
                <input type="text" name="nama" value="<?= $getData['nama']; ?>" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">JENIS KELAMIN</th>
              <td class="p-0 align-middle">
                <div class="row text-center">
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jk" id="radio1" <?= $getData['jk'] == 'Laki-laki' ? 'checked' : ''; ?> value="Laki-laki">
                      <label class="form-check-label" for="radio1">
                        Laki-laki
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jk" id="radio2" <?= $getData['jk'] == 'Perempuan' ? 'checked' : ''; ?> value="Perempuan">
                      <label class="form-check-label" for="radio2">
                        Perempuan
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jk" id="radio3" <?= $getData['jk'] == 'Khusus' ? 'checked' : ''; ?> value="Khusus">
                      <label class="form-check-label" for="radio3">
                        Khusus
                      </label>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">TANGGAL LAHIR</th>
              <td class="p-0">
                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                  <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
                  <input type="text" name="tgl_lahir" value="<?= $getData['tgl_lahir']; ?>" class="form-control datetimepicker-input" data-target="#reservationdate" style="background-color: rgba(255,255,255,.5);" />
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4">ALAMAT</th>
              <td class="p-0">
                <textarea name="alamat" rows="3" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"><?= $getData['alamat']; ?></textarea>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NO. HP</th>
              <td class="p-0">
                <input type="text" value="<?= $getData['no_hp']; ?>" name="no_hp" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">PENDIDIKAN</th>
              <td class="p-0">
                <select name="pendidikan" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                  <option value="">--Pilih Pendidikan--</option>
                  <option value="Tidak Tamat" <?= $getData['pendidikan'] == "Tidak Tamat" ? 'selected' : ''; ?>>Tidak Tamat</option>
                  <option value="SD" <?= $getData['pendidikan'] == "SD" ? 'selected' : ''; ?>>SD</option>
                  <option value="SMP" <?= $getData['pendidikan'] == "SMP" ? 'selected' : ''; ?>>SMP</option>
                  <option value="SMA" <?= $getData['pendidikan'] == "SMA" ? 'selected' : ''; ?>>SMA</option>
                  <option value="D1-D3" <?= $getData['pendidikan'] == "D1-D3" ? 'selected' : ''; ?>>D1-D3</option>
                  <option value="S1/D4" <?= $getData['pendidikan'] == "S1/D4" ? 'selected' : ''; ?>>S1/D4</option>
                  <option value="S2" <?= $getData['pendidikan'] == "S2" ? 'selected' : ''; ?>>S2</option>
                  <option value="S3" <?= $getData['pendidikan'] == "S3" ? 'selected' : ''; ?>>S3</option>
                </select>
              </td>
            </tr>
            </tr>
            <tr>
              <th class="pl-4 align-middle">PEKERJAAN</th>
              <td class="p-0">
                <div class="row">
                  <div class="col-8">
                    <?php
                    $data = ['ASN', 'TNI/POLRI', 'BUMN', 'SWASTA', 'WIRASWASTA', 'MENGURUS RUMAH TANGGA', 'TIDAK BEKERJA'];
                    $cek = false;
                    in_array($getData['pekerjaan'], $data) ? $cek = false : $cek = true;
                    ?>
                    <select name="pekerjaan" required class="d-block m-0 form-control" <?= $cek ? 'disabled style="background-color: rgba(255,255,255,.1);"' : 'style="background-color: rgba(255,255,255,.5);"'; ?>>
                      <option value="">--Pilih Pekerjaan--</option>
                      <option value="ASN" <?= $getData['pekerjaan'] == "ASN" ? 'selected' : ''; ?>>ASN</option>
                      <option value="TNI/POLRI" <?= $getData['pekerjaan'] == "TNI/POLRI" ? 'selected' : ''; ?>>TNI/POLRI</option>
                      <option value="BUMN" <?= $getData['pekerjaan'] == "BUMN" ? 'selected' : ''; ?>>BUMN</option>
                      <option value="SWASTA" <?= $getData['pekerjaan'] == "SWASTA" ? 'selected' : ''; ?>>SWASTA</option>
                      <option value="WIRASWASTA" <?= $getData['pekerjaan'] == "WIRASWASTA" ? 'selected' : ''; ?>>WIRASWASTA</option>
                      <option value="MENGURUS RUMAH TANGGA" <?= $getData['pekerjaan'] == "MENGURUS RUMAH TANGGA" ? 'selected' : ''; ?>>MENGURUS RUMAH TANGGA</option>
                      <option value="TIDAK BEKERJA" <?= $getData['pekerjaan'] == "TIDAK BEKERJA" ? 'selected' : ''; ?>>TIDAK BEKERJA</option>
                      <option value="LAINNYA" <?= $cek ? 'selected' : ''; ?>>LAINNYA</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <input type="text" name="pekerjaan" required <?= $cek ? 'style="background-color: rgba(255,255,255,.5);"' : 'disabled style="background-color: rgba(255,255,255,.1);"'; ?> class="d-block m-0 form-control" value="<?= $cek ? $getData['pekerjaan'] : ''; ?>" placeholder="..........">
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">STATUS PERKAWINAN</th>
              <td class="p-0">
                <select name="status" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                  <option value="">--Pilih Status Perkawinan--</option>
                  <option value="BELUM MENIKAH" <?= $getData['status'] == "BELUM MENIKAH" ? 'selected' : ''; ?>>BELUM MENIKAH</option>
                  <option value="MENIKAH" <?= $getData['status'] == "MENIKAH" ? 'selected' : ''; ?>>MENIKAH</option>
                  <option value="JANDA/DUDA" <?= $getData['status'] == "JANDA/DUDA" ? 'selected' : ''; ?>>JANDA/DUDA</option>
                </select>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NAMA KELUARGA</th>
              <td class="p-0"><input type="text" value="<?= $getData['nama_keluarga']; ?>" name="nama_keluarga" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"></td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">HUBUNGAN KELUARGA</th>
              <td class="p-0">
                <div class="row">
                  <div class="col-8">
                    <?php
                    $data = ['PASANGAN', 'AYAH/IBU', 'SAUDARA KANDUNG'];
                    $cek = false;
                    in_array($getData['hubungan_keluarga'], $data) ? $cek = false : $cek = true;
                    ?>
                    <select name="hubungan_keluarga" required class="d-block m-0 form-control" <?= $cek ? 'disabled style="background-color: rgba(255,255,255,.1);"' : 'style="background-color: rgba(255,255,255,.5);"'; ?>>
                      <option value="">--Pilih hubungan Keluarga--</option>
                      <option value="PASANGAN" <?= $getData['hubungan_keluarga'] == "PASANGAN" ? 'selected' : ''; ?>>PASANGAN</option>
                      <option value="AYAH/IBU" <?= $getData['hubungan_keluarga'] == "AYAH/IBU" ? 'selected' : ''; ?>>AYAH/IBU</option>
                      <option value="SAUDARA KANDUNG" <?= $getData['hubungan_keluarga'] == "SAUDARA KANDUNG" ? 'selected' : ''; ?>>SAUDARA KANDUNG</option>
                      <option value="LAINNYA" <?= $cek ? 'selected' : ''; ?>>LAINNYA</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <input type="text" name="hubungan_keluarga" required <?= $cek ? 'style="background-color: rgba(255,255,255,.5);"' : 'disabled style="background-color: rgba(255,255,255,.1);"'; ?> class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.1);" value="<?= $cek ? $getData['hubungan_keluarga'] : ''; ?>" placeholder="..........">
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NO. HP KELUARGA</th>
              <td class="p-0"><input type="text" value="<?= $getData['no_hp_keluarga']; ?>" name="no_hp_keluarga" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"></td>
            </tr>

          </thead>
        </table>
        <div class="row">
          <div class="col"></div>
          <div class="col-md-4">
            <div class="row">
              <div class="col-6">
                <!--<div class="row">-->
                <!--  <div class="col-12"><button type="button" class="btn btn-primary btn-block my-1">Lanjut ke Pemkes</button></div>-->
                <!--  <div class="col-12"><button type="button" class="btn btn-primary btn-block my-1">Lanjut ke Laboratorium</button></div>-->
                <!--</div>-->
              </div>
              <div class="col-6 align-midle">
                <!--py-4-->
                <button type="submit" class="btn btn-success btn-block my-2"><i class="fas fa-share"></i> Simpan</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<script>
  $('select[name="pekerjaan"]').change(function() {
    var nilai = $(this).val()
    if (nilai == 'LAINNYA')
      $('input[name="pekerjaan"]').attr('disabled', false).css('background-color', 'rgba(255,255,255,.5)');
    else
      $('input[name="pekerjaan"]').attr('disabled', true).css('background-color', 'rgba(255,255,255,.1)');
  })

  $('input[name="pekerjaan"]').change(function() {
    var nilai = $(this).val()
    if (nilai == '')
      $('select[name="pekerjaan"]').attr('disabled', false).css('background-color', 'rgba(255,255,255,.5)');
    else
      $('select[name="pekerjaan"]').attr('disabled', true).css('background-color', 'rgba(255,255,255,.1)');
  })

  $('select[name="hubungan_keluarga"]').change(function() {
    var nilai = $(this).val()
    if (nilai == 'LAINNYA')
      $('input[name="hubungan_keluarga"]').attr('disabled', false).css('background-color', 'rgba(255,255,255,.5)');
    else
      $('input[name="hubungan_keluarga"]').attr('disabled', true).css('background-color', 'rgba(255,255,255,.1)');
  })

  $('input[name="hubungan_keluarga"]').change(function() {
    var nilai = $(this).val()
    if (nilai == '')
      $('select[name="hubungan_keluarga"]').attr('disabled', false).css('background-color', 'rgba(255,255,255,.5)');
    else
      $('select[name="hubungan_keluarga"]').attr('disabled', true).css('background-color', 'rgba(255,255,255,.1)');
  })
</script>
<!-- /.content -->
<?= $this->endSection(); ?>