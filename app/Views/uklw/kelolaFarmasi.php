<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<?php
$array = false;
if ($getData && isset($getData['planning']))
  $array = unserialize($getData['planning']);
?>
<form action="/uklw/pengambilan_obat" method="post">
  <input type="hidden" name="id_uklw_pasien" value="<?= $getData['id_uklw_pasien']; ?>">
  <section class="content">
    <div class="row">
      <div class="col-sm-8">
        <div class="card">
          <div class="card-body box-profile">
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>No. Identitas</b> <a class="float-right"><?= $getData['nomor_identitas']; ?> (<?= $getData['identitas']; ?>)</a>
              </li>
              <li class="list-group-item">
                <b>Nama</b> <a class="float-right"><?= $getData['nama']; ?></a>
              </li>
              <li class="list-group-item">
                <b>Kriteria</b> <a class="float-right">
                  <?php if (isset($getData['status_pemeriksaan'])): ?>
                    <?= $getData['status_pemeriksaan']; ?>
                    <input type="hidden" name="kriteria" value="<?= $getData['status_pemeriksaan']; ?>">
                  <?php else: ?>
                    <input type="text" class="form-control" placeholder="Keterangan kriteria.." required name="kriteria" value="">
                  <?php endif; ?>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="card">
          <div class="card-body">
            <small>
              <?= ($getData && isset($getData['planning'])) ? 'Data pemeriksaan' : 'Tanggal sekarang'; ?> </small>
            <h2><?= mediumdate_indo(date('Y-m-d')); ?></h2>
          </div>
        </div>
        <!-- <div class="card p-0">
        <div class="card-body p-0">
          <ul class="list-group list-group-unbordered mb-3">
            <li class="" style="cursor: not-allowed;">
              <a href="#" class="list-group-item text-center text-dark disabled">
                <h4><strong>Cari dari Resep Dokter</strong></h4>
              </a>
            </li>
          </ul>
        </div>
      </div> -->
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <input type="hidden" name="id_uklw_catatan_pemeriksaan" value="<?= isset($getData['id_uklw_catatan_pemeriksaan']) ? $getData['id_uklw_catatan_pemeriksaan'] : ''; ?>">
        <div class="card-header">
          <h5><strong>Pengambilan Obat</strong></h5>
        </div>
        <div class="card-body">
          <div class="info-box" style="min-height: unset;">
            <button onclick="cloneInputPengambilanObat()" type="button" class="btn btn-primary p-2"><i class="fas fa-2x fa-plus-square"></i></button>
            <div class="list-obat info-box-content">
              <div class="input-group row">
                <div class="col-sm-4">
                  <select name="obat[]" class="select2-obat" style="width: 100%;" required>
                    <option value="">--Pilih Obat--</option>
                    <?php
                    $groupOption = false;
                    $groupLabel = "";
                    foreach ($obat as $data): ?>
                      <?php if ($groupOption == false) :
                        $groupOption = true;
                        $groupLabel = $data['satuan'];
                      ?>
                        <optgroup label="<?= $data['satuan']; ?>">
                        <?php elseif ($groupLabel == true && $groupLabel != $data['satuan']) :
                        $groupLabel = $data['satuan'];
                        ?>
                        </optgroup>
                        <optgroup label="<?= $data['satuan']; ?>">
                        <?php endif; ?>

                        <option <?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat'] == ($data['golongan'] . ' - ' . $data['nama']) ? 'selected' : ''; ?> value="<?= $data['golongan'] . ' - ' . $data['nama']; ?>"><?= $data['golongan'] . ' - ' . $data['nama']; ?> | <?= $data['kategori']; ?></option>

                      <?php endforeach; ?>
                        </optgroup>
                  </select>
                </div>
                <div class="col-sm-1">
                  <input type="number" class="form-control" required value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_jumlah'] ? $array['planning_obat'][0]['planning_obat_jumlah'] : ""; ?>" name="obat_jumlah[]" placeholder="Jumlah">
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control" required value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_sehari'] ? $array['planning_obat'][0]['planning_obat_sehari'] : ""; ?>" name="obat_sehari[]" placeholder="Sehari">
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control" required value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_waktu'] ? $array['planning_obat'][0]['planning_obat_waktu'] : ""; ?>" name="obat_waktu[]" placeholder="Waktu">
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" required value="<?= $array && $array['planning_obat'] && $array['planning_obat'][0]['planning_obat_keterangan'] ? $array['planning_obat'][0]['planning_obat_keterangan'] : ""; ?>" name="obat_keterangan[]" placeholder="Keterangan">
                </div>
              </div>
            </div>
            <!-- /.info-box-content -->
          </div>
          <div class="more-obat">
            <?php if ($array): ?>
              <?php foreach ($array['planning_obat'] as $row => $dataObat): ?>
                <?php if ($row > 0): ?>
                  <div class="info-box" style="min-height: unset;">
                    <button type="button" class="min btn btn-danger p-2"><i class="fas fa-2x fa-minus-square"></i></button>
                    <div class="info-box-content">
                      <div class="input-group row">
                        <div class="col-sm-4">
                          <select name="obat[]" class="select2-obat" style="width: 100%;" required>
                            <option value="">--Pilih Obat--</option>
                            <?php
                            $groupOption = false;
                            $groupLabel = "";
                            foreach ($obat as $data): ?>
                              <?php if ($groupOption == false) :
                                $groupOption = true;
                                $groupLabel = $data['satuan'];
                              ?>
                                <optgroup label="<?= $data['satuan']; ?>">
                                <?php elseif ($groupLabel == true && $groupLabel != $data['satuan']) :
                                $groupLabel = $data['satuan'];
                                ?>
                                </optgroup>
                                <optgroup label="<?= $data['satuan']; ?>">
                                <?php endif; ?>

                                <option <?= $dataObat['planning_obat'] == ($data['golongan'] . ' - ' . $data['nama']) ? 'selected' : ''; ?> value="<?= $data['golongan'] . ' - ' . $data['nama']; ?>"><?= $data['golongan'] . ' - ' . $data['nama']; ?> | <?= $data['kategori']; ?></option>

                              <?php endforeach; ?>
                                </optgroup>
                          </select>
                        </div>
                        <div class="col-sm-1">
                          <input type="number" class="form-control" required value="<?= $dataObat['planning_obat_jumlah']; ?>" name="obat_jumlah[]" placeholder="Jumlah">
                        </div>
                        <div class="col-sm-2">
                          <input type="text" class="form-control" required value="<?= $dataObat['planning_obat_sehari']; ?>" name="obat_sehari[]" placeholder="Sehari">
                        </div>
                        <div class="col-sm-2">
                          <input type="text" class="form-control" required value="<?= $dataObat['planning_obat_waktu']; ?>" name="obat_waktu[]" placeholder="Waktu">
                        </div>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" required value="<?= $dataObat['planning_obat_keterangan']; ?>" name="obat_keterangan[]" placeholder="Keterangan">
                        </div>
                      </div>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <!-- /.info-box -->
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
          <button type="button" class="btn btn-info disabled" style="cursor: not-allowed;"><i class="fas fa-print"></i> Cetak</button>
        </div>
      </div>
    </div>
  </section>
</form>

<script>
  $(document).ready(function() {
    setTimeout(function() {
      $(".select2-obat").select2();
    }, 200);
  });

  function cloneInputPengambilanObat() {
    $("#overlay").show();
    $(".select2-obat").select2("destroy");
    let listObat = $(".list-obat").clone();

    $(".more-obat").append(
      '<div class="info-box" style="min-height: unset;">' +
      '<button type="button" class="min btn btn-danger p-2"><i class="fas fa-2x fa-minus-square"></i></button>' +
      '<div class="info-box-content">' +
      listObat.html() +
      '</div></div>'
    );

    $(".more-obat").find('.info-box').last().find('input').val("");
    $(".more-obat").find('.info-box').last().find('select').val("");

    setTimeout(function() {
      $("#overlay").hide();
      $(".select2-obat").select2();
    }, 200);
  }

  function checkSelectValuesObat(activeSelect) {
    let selectedValues = [];
    $(".select2-obat").each(function() {
      let value = $(this).val();
      if (value) {
        if (selectedValues.includes(value)) {
          Swal.fire({
            icon: 'warning',
            title: 'Obat sudah terpilih!'
          });
          $(activeSelect).val("").trigger('change');
        } else {
          selectedValues.push(value);
        }
      }
    });
  }

  $(document).on('change', '.select2-obat', function() {
    checkSelectValuesObat(this);
  });

  $(document).on('click', '.min', function() {
    $(this).closest('.info-box').remove();
  });
</script>
<?= $this->endSection(); ?>