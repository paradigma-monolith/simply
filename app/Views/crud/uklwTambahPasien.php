<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="jumbotron">
      <form action="/uklw/tambah_pasien" method="post">
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
                      <input class="form-check-input" type="radio" name="identitas" id="radio1identitas" <?= old('identitas') ? (old('identitas') == 'NIK' ? 'checked' : '') : ''; ?> value="NIK">
                      <label class="form-check-label" for="radio1identitas">
                        NIK
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="identitas" id="radio2identitas" <?= old('identitas') ? (old('identitas') == 'Paspor' ? 'checked' : '') : ''; ?> value="Paspor">
                      <label class="form-check-label" for="radio2identitas">
                        Paspor
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="identitas" id="radio3identitas" <?= old('identitas') ? (old('identitas') == 'No Porsi' ? 'checked' : '') : ''; ?> value="No Porsi">
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
                <input type="text" name="nomor_identitas" value="<?= old('nomor_identitas') ? old('nomor_identitas') : ''; ?>" required class="d-block m-0 form-control number" style="background-color: rgba(255,255,255,.5);">
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NAMA SESUAI IDENTITAS</th>
              <td class="p-0">
                <input type="text" name="nama" value="<?= old('nama') ? old('nama') : ''; ?>" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">JENIS KELAMIN</th>
              <td class="p-0 align-middle">
                <div class="row text-center">
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jk" id="radio1" <?= old('jk') ? (old('jk') == 'Laki-laki' ? 'checked' : '') : ''; ?> value="Laki-laki">
                      <label class="form-check-label" for="radio1">
                        Laki-laki
                      </label>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="jk" id="radio2" <?= old('jk') ? (old('jk') == 'Perempuan' ? 'checked' : '') : ''; ?> value="Perempuan">
                      <label class="form-check-label" for="radio2">
                        Perempuan
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
                  <input type="text" name="tgl_lahir" id="tanggal" value="<?= old('tgl_lahir') ? old('tgl_lahir') : ''; ?>" class="form-control datetimepicker-input" data-target="#reservationdate" data-toggle="datetimepicker" style="background-color: rgba(255,255,255,.5);" />
                  <p id="tanggalHasil"></p>
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-top">ALAMAT</th>
              <td class="p-0">
                <textarea name="alamat" rows="3" required class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"><?= old('alamat') ? old('alamat') : ''; ?></textarea>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NO. HP</th>
              <td class="p-0">
                <input type="text" value="<?= old('no_hp') ? old('no_hp') : ''; ?>" name="no_hp" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">PENDIDIKAN</th>
              <td class="p-0">
                <select name="pendidikan" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                  <option value="">--Pilih Pendidikan--</option>
                  <option value="Tidak Tamat" <?= old('pendidikan') == "Tidak Tamat" ? 'selected' : ''; ?>>Tidak Tamat</option>
                  <option value="SD" <?= old('pendidikan') == "SD" ? 'selected' : ''; ?>>SD</option>
                  <option value="SMP" <?= old('pendidikan') == "SMP" ? 'selected' : ''; ?>>SMP</option>
                  <option value="SMA" <?= old('pendidikan') == "SMA" ? 'selected' : ''; ?>>SMA</option>
                  <option value="D1-D3" <?= old('pendidikan') == "D1-D3" ? 'selected' : ''; ?>>D1-D3</option>
                  <option value="S1/D4" <?= old('pendidikan') == "S1/D4" ? 'selected' : ''; ?>>S1/D4</option>
                  <option value="S2" <?= old('pendidikan') == "S2" ? 'selected' : ''; ?>>S2</option>
                  <option value="S3" <?= old('pendidikan') == "S3" ? 'selected' : ''; ?>>S3</option>
                </select>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">PEKERJAAN</th>
              <td class="p-0">
                <div class="row">
                  <div class="col-8">
                    <select name="pekerjaan" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                      <option value="">--Pilih Pekerjaan--</option>
                      <option value="ASN" <?= old('pekerjaan') == "ASN" ? 'selected' : ''; ?>>ASN</option>
                      <option value="TNI/POLRI" <?= old('pekerjaan') == "TNI/POLRI" ? 'selected' : ''; ?>>TNI/POLRI</option>
                      <option value="BUMN" <?= old('pekerjaan') == "BUMN" ? 'selected' : ''; ?>>BUMN</option>
                      <option value="SWASTA" <?= old('pekerjaan') == "SWASTA" ? 'selected' : ''; ?>>SWASTA</option>
                      <option value="WIRASWASTA" <?= old('pekerjaan') == "WIRASWASTA" ? 'selected' : ''; ?>>WIRASWASTA</option>
                      <option value="MENGURUS RUMAH TANGGA" <?= old('pekerjaan') == "MENGURUS RUMAH TANGGA" ? 'selected' : ''; ?>>MENGURUS RUMAH TANGGA</option>
                      <option value="TIDAK BEKERJA" <?= old('pekerjaan') == "TIDAK BEKERJA" ? 'selected' : ''; ?>>TIDAK BEKERJA</option>
                      <option value="LAINNYA" <?= old('pekerjaan') == "LAINNYA" ? 'selected' : ''; ?>>LAINNYA</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <input type="text" required name="pekerjaan" disabled class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.1);" value="<?= old('pekerjaan') ? old('pekerjaan') : ''; ?>" placeholder="..........">
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">STATUS PERKAWINAN</th>
              <td class="p-0">
                <select name="status" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                  <option value="">--Pilih Status Perkawinan--</option>
                  <option value="BELUM MENIKAH" <?= old('status') == "BELUM MENIKAH" ? 'selected' : ''; ?>>BELUM MENIKAH</option>
                  <option value="MENIKAH" <?= old('status') == "MENIKAH" ? 'selected' : ''; ?>>MENIKAH</option>
                  <option value="JANDA/DUDA" <?= old('status') == "JANDA/DUDA" ? 'selected' : ''; ?>>JANDA/DUDA</option>
                </select>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NAMA KELUARGA</th>
              <td class="p-0"><input type="text" value="<?= old('nama_keluarga') ? old('nama_keluarga') : ''; ?>" name="nama_keluarga" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"></td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">HUBUNGAN KELUARGA</th>
              <td class="p-0">
                <div class="row">
                  <div class="col-8">
                    <select name="hubungan_keluarga" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);">
                      <option value="">--Pilih hubungan Keluarga--</option>
                      <option value="PASANGAN" <?= old('hubungan_keluarga') == "PASANGAN" ? 'selected' : ''; ?>>PASANGAN</option>
                      <option value="AYAH/IBU" <?= old('hubungan_keluarga') == "AYAH/IBU" ? 'selected' : ''; ?>>AYAH/IBU</option>
                      <option value="SAUDARA KANDUNG" <?= old('hubungan_keluarga') == "SAUDARA KANDUNG" ? 'selected' : ''; ?>>SAUDARA KANDUNG</option>
                      <option value="LAINNYA" <?= old('hubungan_keluarga') == "LAINNYA" ? 'selected' : ''; ?>>LAINNYA</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <input type="text" name="hubungan_keluarga" required disabled class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.1);" value="<?= old('hubungan_keluarga') ? old('hubungan_keluarga') : ''; ?>" placeholder="..........">
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th class="pl-4 align-middle">NO. HP KELUARGA</th>
              <td class="p-0"><input type="text" value="<?= old('no_hp_keluarga') ? old('no_hp_keluarga') : ''; ?>" name="no_hp_keluarga" class="d-block m-0 form-control" style="background-color: rgba(255,255,255,.5);"></td>
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