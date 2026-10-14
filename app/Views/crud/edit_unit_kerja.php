<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- /.card-header -->
        <!-- jquery validation -->
        <div class="card card-primary">
          <div class="card-header">
            <!-- <h3 class="card-title">Quick Example <small>jQuery Validation</small></h3> -->
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form id="tambahUnitKerja" action="/admin/editUnitKerja/<?= $unit_kerja['id_unit_kerja']; ?>" method="post">
            <div class="card-body">
              <div class="form-group">
                <label for="unit_kerja">Unit Kerja</label>
                <input type="unit_kerja" name="unit_kerja" class="form-control" id="unit_kerja" placeholder="Contoh: KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG" value="<?= $unit_kerja['unit_kerja']; ?>">
              </div>
              <div class="form-group">
                <label for="lokasi">Lokasi Kota</label>
                <input type="lokasi" name="lokasi" class="form-control" id="lokasi2" placeholder="Contoh: Bandung" value="<?= $unit_kerja['lokasi']; ?>">
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>