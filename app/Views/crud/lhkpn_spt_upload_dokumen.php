<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-12">

        <div class="card card-default">
          <form id="simpanDokumenLhkpnSpt" action="/lhkpn_spt/simpan_dokumen<?= isset($getLhkpnSpt['id_lhkpn_spt']) ? '/' . $getLhkpnSpt['id_lhkpn_spt'] : ''; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="card-header bg-primary">
              <h3 class="card-title"><?= isset($getLhkpnSpt['id_lhkpn_spt']) ? "Ubah Dokumen" : 'Unggah Dokumen Baru'; ?></h3>
            </div>

            <div class="card-body">
              <div class="form-group row">
                <label class="col-sm-2">Data Pegawai</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-10">
                      <select class="form-control select2" name="pegawai" id="pegawai" style="width:100%;" readonly>
                        <option value="">-- Pilih Data Pegawai --</option>
                        <?php if (isset($getLhkpnSpt['nama'])) : ?>
                          <?php foreach ($pegawai as $data_pegawai) : ?>
                            <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= $data_pegawai['nip'] == $getLhkpnSpt['nip'] ? 'selected="true"' : '' ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                          <?php endforeach; ?>
                        <?php else : ?>
                          <?php foreach ($pegawai as $data_pegawai) : ?>
                            <option value="<?= $data_pegawai['id_data_pegawai']; ?>" <?= isset($by) ? ($data_pegawai['nip'] == $by['nip'] ? 'selected="true"' : '') : '' ?>> <?= $data_pegawai['nip']; ?> | <?= $data_pegawai['nama']; ?> </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>
                    <div class="col-2">
                      <div class="form-check text-center">
                        <input class="form-check-input" type="checkbox" id="selectPegawai" <?= session()->get('role') == "Admin" ? '' : 'disabled'; ?>>
                        <label class="form-check-label" for="selectPegawai">
                          Administrator
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Tahun</label>
                <div class="col-sm-10">
                  <select name="tanggal_dokumen" class="form-control">
                     <option value="">-- Pilih Tahun --</option>
                     <?php for($tahun = 2024; $tahun <= date('Y'); $tahun++): ?>
                       <option value="<?= $tahun; ?>" <?= (isset($getLhkpnSpt['tanggal_dokumen']) && date('Y', strtotime($getLhkpnSpt['tanggal_dokumen'])) == $tahun) ? 'selected' : ''; ?>><?= $tahun; ?></option>
                     <?php endfor; ?>
                   </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Jenis Dokumen</label>
                <div class="col-sm-10">
                  <select name="jenis_dokumen" class="form-control">
                    <option value="">-- Pilih Jenis Dokumen --</option>
                    <option value="SPT">SPT</option>
                    <option value="LKHPN">LKHPN</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2">Unggah Berkas LHKPN/SPT</label>
                <div class="col-sm-10">
                  <?php if (isset($getLhkpnSpt)) : ?>
                    <p class="text-danger">Aktifkan switch berikut jika akan mengubah file. Abaikan jika tidak akan mengubah file.</p>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-sm-1">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input inputFile" for="unggah_dokumen" id="customSwitch1" <?= isset($getLhkpnSpt) ? '' : 'checked disabled'; ?>>
                        <label class="custom-control-label" for="customSwitch1">&nbsp;</label>
                      </div>
                    </div>
                    <div class="col-sm-11">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="unggah_dokumen" id="unggah_dokumen" accept=".pdf,.jpg,.jpeg,.png,.gif">
                        <label class="custom-file-label" for="unggah_dokumen">Pilih File PDF/Gambar</label>
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