<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Data Lantai</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="absSimpanDataLantai" action="/abs/simpan_data_lantai" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="form-group">
                <label class="">Nama Lantai</label>
                <input type="text" class="form-control" name="nama_lantai" id="nama_lantai">
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
              </div>
            </form>

            <hr>
            <div id="absLantai"></div>
          </div>

          <div class="card-footer">
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Data Ruang</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="absSimpanDataRuang" action="/abs/simpan_data_ruang" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="form-group">
                <label class="">Nama Ruang</label>
                <input type="text" class="form-control" name="nama_ruang" id="nama_ruang">
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
              </div>
            </form>

            <hr>

            <div id="absRuang"></div>
          </div>

          <div class="card-footer">
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Kategori Kelompok</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <form id="absSimpanDataJenis" action="/abs/simpan_data_jenis" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="form-group">
                <label class="">Kategori</label>
                <input type="text" class="form-control" name="nama_jenis" id="nama_jenis">
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
              </div>
            </form>

            <hr>

            <div id="absJenisBarang"></div>

          </div>

          <div class="card-footer">
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>
</section>

<?= $this->endSection(); ?>