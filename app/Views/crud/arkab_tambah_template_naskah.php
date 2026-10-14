<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <form id="simpanTemplateNaskah" action="/tata_naskah_dinas/template_naskah/simpan_naskah<?= isset($getTemplateNaskah['id_arsip_template_naskah']) ? '/' . $getTemplateNaskah['id_arsip_template_naskah'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getTemplateNaskah['id_arsip_template_naskah']) ? 'Ubah Template Naskah' : 'Tambah Template Naskah'; ?></h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Jenis Naskah</label>
                <div class="col-sm-10">
                  <input type="text" name="jenis_naskah" class="form-control" id="jenis_naskah" value="<?= isset($getTemplateNaskah['jenis_naskah']) ? $getTemplateNaskah['jenis_naskah'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Nama File</label>
                <div class="col-sm-10">
                  <input type="text" name="nama_file" class="form-control" id="nama_file" value="<?= isset($getTemplateNaskah['nama_file']) ? $getTemplateNaskah['nama_file'] : ''; ?>">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Dokumen</label>
                <div class="col-sm-10">
                  <?php if (isset($getTemplateNaskah)) : ?>
                    <p class="text-danger">Aktifkan switch berikut jika akan mengubah file. Abaikan jika tidak akan mengubah file.</p>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-1">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input inputFile" for="unggah_naskah" id="customSwitch1" <?= isset($getTemplateNaskah) ? '' : 'checked disabled'; ?>>
                        <label class="custom-control-label" for="customSwitch1">&nbsp;</label>
                      </div>
                    </div>
                    <div class="col-sm-11">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="unggah_naskah" id="unggah_naskah">
                        <label class="custom-file-label" for="unggah_naskah">Pilih File</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer text-right">
              <button type="submit" class="btn btn-success"><i class="fas fa-share"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection(); ?>