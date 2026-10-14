<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- SELECT2 EXAMPLE -->
    <div class="row">
      <div class="col-md-12">

        <div class="card card-default">
          <!-- form start -->
          <form id="tambahVaksin" action="/infografis/update_vaksin" method="post">
            <input type="hidden" name="id_infografis_vaksin" value="<?= $infografis['id_infografis_vaksin']; ?>">
            <div class="card-header bg-primary">
              <h3 class="card-title">Rekapitulasi Data Harian Kegiatan Kekarantinaan Kesehatan Bandung Tahun <?= date('Y'); ?></h3>

              <div class="card-tools">
                <label class="has-float-label col-12">
                  <div class="input-group date" id="date" data-target-input="nearest">
                    <input value="<?= $infografis['created_at']; ?>" type="text" name="tanggal" readonly disabled class="form-control datetimepicker-input" data-target="#date" data-toggle="datetimepicker" />
                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </label>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="phqc" class="form-control" id="phqc" placeholder="Jumlah" value="<?= $infografis['phqc']; ?>">
                    <span>Data PHQC</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="cop" class="form-control" id="cop" placeholder="Jumlah" value="<?= $infografis['cop']; ?>">
                    <span>Data COP</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="sscec" class="form-control" id="sscec" placeholder="Jumlah" value="<?= $infografis['sscec']; ?>">
                    <span>Data SSCEC</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="sscc" class="form-control" id="sscc" placeholder="Jumlah" value="<?= $infografis['sscc']; ?>">
                    <span>Data SSCC</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="p3k" class="form-control" id="p3k" placeholder="Jumlah" value="<?= $infografis['p3k']; ?>">
                    <span>Data P3K</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="icv" class="form-control" id="icv" placeholder="Jumlah" value="<?= $infografis['icv']; ?>">
                    <span>Data ICV</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="sklt" class="form-control" id="sklt" placeholder="Jumlah" value="<?= $infografis['sklt']; ?>">
                    <span>Data SKLT</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="siaos" class="form-control" id="siaos" placeholder="Jumlah" value="<?= $infografis['siaos']; ?>">
                    <span>Data SIAOS</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="siaj" class="form-control" id="siaj" placeholder="Jumlah" value="<?= $infografis['siaj']; ?>">
                    <span>Data Surat Izin Angkut Jenazah</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="kir" class="form-control" id="kir" placeholder="Jumlah" value="<?= $infografis['kir']; ?>">
                    <span>Data Pengujian Kesehatan (KIR)</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="vaksin_mm" class="form-control" id="vaksin_mm" placeholder="Jumlah" value="<?= $infografis['vaksin_mm']; ?>">
                    <span>Data Vaksin MM</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="vaksin_yf" class="form-control" id="vaksin_yf" placeholder="Jumlah" value="<?= $infografis['vaksin_yf']; ?>">
                    <span>Data Vaksin YF</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="vaksin_covid" class="form-control" id="vaksin_covid" placeholder="Jumlah" value="<?= $infografis['vaksin_covid']; ?>">
                    <span>Data Vaksin Covid</span>
                  </label>
                </div>
                
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="vaksin_influenza" class="form-control" id="vaksin_influenza" placeholder="Jumlah" value="<?= $infografis['vaksin_influenza']; ?>">
                    <span>Data Vaksin Influenza</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="vaksin_tifoid" class="form-control" id="vaksin_tifoid" placeholder="Jumlah" value="<?= $infografis['vaksin_tifoid']; ?>">
                    <span>Data Vaksin Tifoid</span>
                  </label>
                </div>
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="vaksin_polio" class="form-control" id="vaksin_polio" placeholder="Jumlah" value="<?= $infografis['vaksin_polio']; ?>">
                    <span>Data Vaksin Polio</span>
                  </label>
                </div>
                
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="bukes" class="form-control" id="bukes" placeholder="Jumlah" value="<?= $infografis['bukes']; ?>">
                    <span>Buku Kesehatan</span>
                  </label>
                </div>
                
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="kontraindikasi" class="form-control" id="kontraindikasi" placeholder="Jumlah" value="<?= $infografis['kontraindikasi']; ?>">
                    <span>Kartu kontraindikasi</span>
                  </label>
                </div>
                
                <div class="form-group col-4">
                  <label class="has-float-label">
                    <input type="text" name="e_icv" class="form-control" id="e_icv" placeholder="Jumlah" value="<?= $infografis['e_icv']; ?>">
                    <span>Elektronik ICV</span>
                  </label>
                </div>
              </div>

            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection(); ?>