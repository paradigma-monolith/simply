<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-default">
          <form id="tambahDataSop" action="/sipepes/tambah_dokumen_sop" method="post" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title">Tambah Data Dokumen SOP</h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Nomor Dokumen</label>
                <div class="col-sm-10">
                  <input type="text" name="no_dokumen" class="form-control" id="no_dokumen">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Judul</label>
                <div class="col-sm-10">
                  <input type="text" name="judul" class="form-control" id="judul">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Divisi</label>
                <div class="col-sm-10">
                  <select class="form-control select2" name="unit_kerja" id="unit_kerja" style="width:100%;">
                    <option value=""> -- Pilih Divisi -- </option>
                    <?php foreach($divisi as $data): ?>
                    <option value="<?= $data['tim']; ?>"><?= $data['tim']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jenis Dokumen</label>
                <div class="col-sm-10">
                  <input type="text" name="jenis_dokumen" class="form-control" id="jenis_dokumen" readonly>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Berkas SOP</label>
                <div class="col-sm-10">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="unggah_berkas_sop" id="unggah_berkas_sop">
                    <label class="custom-file-label" for="unggah_berkas_sop">Choose file</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Status Pengesahan</label>
                <div class="col-sm-10">
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" value="Proses Pengesahan">Proses Pengesahan
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" value="Sudah Pengesahan">Sudah Pengesahan
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Pembuatan</label>
                <div class="col-sm-10">
                  <div class="input-group date-today" id="tanggalpembuatan" data-target-input="nearest">
                    <input type="text" id="tanggal_pembuatan" name="tanggal_pembuatan" class="form-control datetimepicker-input" data-target="#tanggalpembuatan" data-toggle="datetimepicker" />
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
                    <input type="text" id="tanggal_revisi" name="tanggal_revisi" class="form-control datetimepicker-input" data-target="#tanggalrevisi" data-toggle="datetimepicker" />
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
                    <input class="form-control" type="number" id="revisi" name="revisi" min="0" max="10" value="0">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tanggal Efektif</label>
                <div class="col-sm-10">
                  <div class="input-group date-today" id="tanggalefektif" data-target-input="nearest">
                    <input type="text" id="tanggal_efektif" name="tanggal_efektif" class="form-control datetimepicker-input" data-target="#tanggalefektif" data-toggle="datetimepicker" />
                    <div class="input-group-append" data-target="#tanggalefektif" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>