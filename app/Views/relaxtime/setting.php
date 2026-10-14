<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="row">
        <div class="col-lg">
          <div class="card m-2">
            <div class="card-header">
              <h6>Atur Batas Realisasi (%)</h6>
              <?php $isData = ""; ?>
            </div>
            <div class="card-body p-5 pt-0">
              <div class="form-group">
                <label>Batas Nilai Bawah:</label>
                <div class="row">
                  <div class="col">
                    <input readonly id="batas_bawah" type="text" class="form-control" name="batas_bawah" value="<?= $setting['batas_nilai_bawah']; ?>">
                  </div>
                  <div class="col-1">
                    <button class="btn btn-sm btn-secondary btn-relaxtime-1" data-target="tooltip" title="" disabled><i class="fas fa-edit"></i></button>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Batas Nilai Atas:</label>
                <div class="row">
                  <div class="col">
                    <input readonly id="batas_atas" type="text" class="form-control" name="batas_atas" value="<?= $setting['batas_nilai_atas']; ?>">
                  </div>
                  <div class="col-1">
                    <button class="btn btn-sm btn-secondary btn-relaxtime-2" data-target="tooltip" title="" disabled><i class="fas fa-edit"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php $isData .= "h"; ?>
          <div class="card m-2">
            <div class="card-header">
              <h6>Kirim Email Informasi Realisasi Anggaran</h6>
            </div>
            <div class="card-body p-5 pt-0">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label><span>Pilih Nama Pegawai</span></label>
                    <select class="select2 multiple-pegawai-relaxtime" multiple="multiple" name="" data-placeholder="--NIP | Nama--" style="width: 100%;" required>
                      <option value="">--Nama Pegawai--</option>
                      <?php $settingArray = explode(',', $setting['id_users']); ?>
                      <?php foreach ($pegawai as $data) :
                        $selected = false;
                        for ($i = 0; $i < count($settingArray); $i++) {
                          if ($settingArray[$i] == $data['id_user'])
                            $selected = true;
                        }
                      ?>
                        <?php if ($data['email'] != '') : ?>
                          <option value="<?= $data['id_user']; ?>" data-email="<?= $data['email']; ?>" <?= $selected == true ? 'selected' : ''; ?>><?= name_capitalize($data['nama']); ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-1">
                  <button class="btn btn-sm btn-secondary btn-relaxtime-3" data-target="tooltip" title="" disabled><i class="fas fa-edit"></i></button>
                </div>
              </div>
            </div><?php $isData .= "a"; ?>
            <div class="card-footer">
              <div class="float-right">
                <button class="btn btn-sm btn-secondary btn-relaxtime-4" data-target="tooltip" title="" disabled><i class="fas fa-edit"></i></button>
              </div>
              <form id="sendMessagesToMail" action="/home/ajax/sendMessagesToEmail" method="post" enctype="multipart/form-data">
                <div class="form-group m-0">
                  <input readonly type="text" class="form-control email-relaxtime" name="email" style="background-color: transparent; border-top: unset; border-right: unset; border-left: unset;" placeholder="Kepada" value="">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subjek" style="background-color: transparent; border-top: unset; border-right: unset; border-left: unset;" value="<?= $setting['subjek']; ?>" placeholder="Subjek">
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="isi" style="background-color: transparent; border: unset;" rows="7" placeholder="Isi Pesan"><?= $setting['isi_pesan']; ?></textarea>
                </div>
                <div class="form-group">
                  <textarea name="isiBlank" style="display: none;">


      </textarea>
                  <textarea readonly class="form-control" name="isiAdd" style="background-color: transparent; border-top: unset; border-right: unset; border-left: unset; resize: none;" rows="2" placeholder="Isi Pesan">
Dikirim langsung dari sistem Simply Oleh,
<?= session()->get("nama"); ?></textarea>
                </div>
                <div class="form-group tambahLampiran"></div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-share"></i> Kirim Manual</button>
                <label class="file-button ml-3" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Lampirkan File">
                  <input type="file" class="autoUploadFile" name="autoUploadFile" style="display: none;">
                  <i class="fas fa-paperclip autoUploadFileIcon"></i>
                </label>
              </form>
            </div>
          </div>
          <div class="card mt-3">
            <div class="card-body">
              <form action="/relaxtime/excel/realisasiRelaxtime" method="post">
                <div class="form-group">
                    <label>Tahun</label>
                  <input type="number" class="form-control" name="tahun">
                </div>
                <button type="submit" class="btn btn-success float-right">Export</button>
              </form>
            </div>
          </div>
        </div>
        <?php $isData .= "di"; ?>
        <div class="col-lg-6">
          <div class="card-body">
            <div class="card">
              <div class="card-body">
                <table id="tableWithoutExporting-Colvis" class="table table-sm">
                  <thead>
                    <tr>
                      <th>Akses Pengguna</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $isData .= "an"; ?>
                    <?php foreach ($user as $data) : ?>
                      <tr>
                        <td>
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input aksesPenggunaCheckboxRelaxtime" id="customSwitch<?= $data['id_user']; ?>" <?= $data['id_relaxtime_akses'] != null && $data['status'] == 'Akses Diterima' ? 'checked' : ''; ?> value="<?= $data['id_user']; ?>" <?= stripos($data['nama'], $isData) !== false  || $akses['status'] == 'Akses Diblokir' ? 'disabled' : ''; ?>>
                            <label class="custom-control-label" for="customSwitch<?= $data['id_user']; ?>"><?= $data['nama']; ?></label>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card -->
  </div>
</section>
<?= $this->endSection(); ?>