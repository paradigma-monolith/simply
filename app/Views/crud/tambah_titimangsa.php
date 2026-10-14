<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4">
        <!-- /.card-header -->
        <!-- jquery validation -->
        <div class="card card-primary">
          <div class="card-header">
            <!-- <h3 class="card-title">Quick Example <span>jQuery Validation</span></h3> -->
          </div>
          <!-- /.card-header -->
          <form id="tambahTitimangsa" action="/admin/tambah_titimangsa" method="post">
            <div class="card-body">
              <div class="form-group">
                <label>Surat</label>
                <select class="form-control select2" name="surat" id="surat" style="width: 100%;">
                  <option value=""> --Pilih Surat-- </option>
                  <option value="cuti"> Cuti </option>
                  <option value="surat_sakti"> Surat Sakti </option>
                </select>
              </div>
              <div class="form-group">
                <label>Unit Kerja</label>
                <select class="form-control select2" disabled name="unit_kerja" id="unit_kerja" style="width: 100%;">
                  <option value=""> --Pilih Unit Kerja-- </option>
                  <?php foreach ($unit_kerja as $data) : ?>
                    <option value="<?= $data['unit_kerja']; ?>"><?= $data['unit_kerja']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Data Kepegawaian</label>
                <select class="form-control select2" disabled name="kepegawaian" id="kepegawaian" style="width: 100%;">
                  <option value=""> --NIP/Nama-- </option>
                </select>
              </div>
              <div class="form-group">
                <label>Data Pejabat</label>
                <select class="form-control select2" disabled name="pejabat" id="pejabat" style="width: 100%;">
                  <option value=""> --NIP/Nama-- </option>
                </select>
              </div>
              <div class="form-group">
                <label>Data Atasan</label>
                <select class="form-control select2" disabled name="atasan" id="atasan" style="width: 100%;">
                  <option value=""> --NIP/Nama-- </option>
                </select>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
      <div class="col"></div>
      <div class="col-md-7 row">
        <div class="col-12 text-center">
          <h3>Gambaran tampil bagian bawah dalam surat</h3>
        </div>
        <div class="col-md-6 text-center border bg-white p-3" id="atasanView">
          <p class="mb-5"><span id="jabatanAtasan">Jabatan</span></p>
          <div class="mb-3">&nbsp;</div>
          <p class="mt-5"><span id="namaAtasan">Nama</span> <br /><em>NIP : <span id="nipAtasan">xxx</span></em></p>
        </div>
        <div class="col-md-6 text-center border bg-white p-3" id="pejabatView">
          <p class="mb-5"><span id="jabatanPejabat">Jabatan</span></p>
          <div class="mb-3">&nbsp;</div>
          <p class="mt-5"><span id="namaPejabat">Nama</span> <br /><em>NIP : <span id="nipPejabat">xxx</span></em></p>
        </div>
        <div class="col-12">&nbsp;</div>
        <div class="col-12">&nbsp;</div>
        <div class="col-12">&nbsp;</div>
      </div>
      <div class="col"></div>
    </div>
  </div>
</section>
<!-- /.content -->
<?= $this->endSection(); ?>