<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-default">
          <form id="" action="/sipepes/ubah_dokumen_sop/<?= $ubah_dokumen_sop['dokumen_id']; ?>" method="post" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title">Ubah Data Dokumen SOP</h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Nomor Dokumen</label>
                <div class="col-sm-10">
                  <input required type="text" name="no_dokumen" class="form-control" id="no_dokumen" value="<?= $ubah_dokumen_sop['no_dokumen']; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Judul</label>
                <div class="col-sm-10">
                  <input required type="text" name="judul" class="form-control" id="judul" value="<?= $ubah_dokumen_sop['judul']; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Divisi</label>
                <div class="col-sm-10">
                  <select required class="form-control select2" name="unit_kerja" id="unit_kerja" style="width:100%;">
                    <option value=""> -- Pilih Divisi -- </option>
                    <?php 
                    $perubahan = true;
                    foreach($divisi as $data): 
                    if ($ubah_dokumen_sop['unit_kerja'] == $data['tim']) {
                        $perubahan = false;
                    }
                    ?>
                        <option value="<?= $data['tim']; ?>"  <?= $ubah_dokumen_sop['unit_kerja'] == $data['tim'] ? 'selected' : ''; ?>><?= $data['tim']; ?></option>
                    <?php endforeach; ?>
                    <?php if($perubahan) { ?>
                        <option value="<?= $ubah_dokumen_sop['unit_kerja']; ?>" selected><?= $ubah_dokumen_sop['unit_kerja']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jenis Dokumen</label>
                <div class="col-sm-10">
                  <input required type="text" name="jenis_dokumen" class="form-control" id="jenis_dokumen" value="<?= $ubah_dokumen_sop['jenis_dokumen']; ?>" readonly>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Berkas SOP</label>
                <div class="col-sm-10">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="unggah_berkas_sop" id="">
                    <label class="custom-file-label" for="unggah_berkas_sop">Kosongkan jika tidak ada dokumen yang akan diubah!</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Status Pengesahan</label>
                <div class="col-sm-10">
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input required type="radio" class="form-check-input" name="status" value="Proses Pengesahan" <?= $ubah_dokumen_sop['status_pengesahan'] == 'Proses Pengesahan' ? 'checked' : ''; ?>>Proses Pengesahan
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input required type="radio" class="form-check-input" name="status" value="Sudah Pengesahan" <?= $ubah_dokumen_sop['status_pengesahan'] == 'Sudah Pengesahan' ? 'checked' : ''; ?>>Sudah Pengesahan
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Pembuatan</label>
                <div class="col-sm-10">
                  <div class="input-group date-today" id="tanggalpembuatan" data-target-input="nearest">
                    <input required type="text" id="tanggal_pembuatan" name="tanggal_pembuatan" class="form-control datetimepicker-input" data-target="#tanggalpembuatan" data-toggle="datetimepicker" value="<?= $ubah_dokumen_sop['tanggal_pembuatan']; ?>" />
                    <div class="input-group-append" data-target="#tanggalpembuatan" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Revisi</label>
                <div class="col-sm-10">
                  <div class="input-group date-today" id="tanggalrevisi" data-target-input="nearest">
                    <input required type="text" id="tanggal_revisi" name="tanggal_revisi" class="form-control datetimepicker-input" data-target="#tanggalrevisi" data-toggle="datetimepicker" value="<?= $ubah_dokumen_sop['tanggal_revisi']; ?>" />
                    <div class="input-group-append" data-target="#tanggalrevisi" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Revisi ke-</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <input required class="form-control" type="number" id="revisi" name="revisi" min="0" max="10" value="<?= $ubah_dokumen_sop['revisi_ke']; ?>">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Efektif</label>
                <div class="col-sm-10">
                  <div class="input-group date-today" id="tanggalefektif" data-target-input="nearest">
                    <input required type="text" id="tanggal_efektif" name="tanggal_efektif" class="form-control datetimepicker-input" data-target="#tanggalefektif" data-toggle="datetimepicker" value="<?= $ubah_dokumen_sop['tanggal_efektif']; ?>" />
                    <div class="input-group-append" data-target="#tanggalefektif" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer text-right">
              <?php
                if ($ubah_dokumen_sop['jenis_dokumen'] == "SOP Utama") {
                ?>
                  <a href="../utama" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php
                } elseif ($ubah_dokumen_sop['jenis_dokumen'] == "SOP Pendukung") {
                ?>
                  <a href="../pendukung" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php
                } else {
                ?>
                  <a href="../lainnya" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php
                }
                ?>
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Perbarui Data</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>