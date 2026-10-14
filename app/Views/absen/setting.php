<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .map-preview {
        height: 150px;
        width: 100%;
        border-radius: 4px;
        border: 1px solid #ced4da;
        z-index: 1;
    }
</style>
<!-- Main content -->
<section class="content">
  <?php $isData = 'h'; ?>
  <div class="container-fluid row">
    <div class="col-md-12 col-lg-10 col-xl-8">
      <div class="card">
        <div class="card-header">
          <h6>Atur Waktu Jam Absen</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-2 pt-3 border py-1">
                <label>Hari</label>
            </div>
            <div class="col-3 pt-3 border py-1">
                <label>Jam Masuk</label>
            </div>
            <div class="col-3 pt-3 border py-1">
                <label>Jam Pulang</label>
            </div>
            <div class="col-3 pt-3 border py-1">
                <label>Toleransi</label>
            </div>
            <div class="col-1 border py-1">
                <label></label>
            </div>
          </div>    
          <!--Senin-->
          <form class="waktu-jam-absensi">    
            <input type="hidden" name="hari" placeholder="HH:mm:ss" value="<?= $day1['hari']; ?>" />
            <div class="form-group">
              <div class="row">
                  <div class="col-2 pt-3 border py-1">
                      <label>Senin</label>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_masuk" placeholder="HH:mm:ss" value="<?= $day1['aturan_jam_masuk']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_pulang" placeholder="HH:mm:ss" value="<?= $day1['aturan_jam_pulang']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="toleransi_keterlambatan" placeholder="HH:mm:ss" value="<?= $day1['toleransi_keterlambatan']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-1 border py-1">
                      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i></button>
                  </div>
                  <div class="col-12 border pt-1 text-right" style="background-color: #eff2f5;">
                      <strong><i><sup>Terakhir diupdate oleh: <span class="nama-update-jam"><?= $day1['nama']; ?></span> (<span class="waktu-update-jam"><?= $day1['updated_at'] ?></span>)</sup></i></strong>
                  </div>
              </div>
            </div>
          </form>
          <!--Selasa-->
          <form class="waktu-jam-absensi">    
            <input type="hidden" name="hari" placeholder="HH:mm:ss" value="<?= $day2['hari']; ?>" />
            <div class="form-group">
              <div class="row">
                  <div class="col-2 pt-3 border py-1">
                      <label>Selasa</label>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_masuk" placeholder="HH:mm:ss" value="<?= $day2['aturan_jam_masuk']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_pulang" placeholder="HH:mm:ss" value="<?= $day2['aturan_jam_pulang']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="toleransi_keterlambatan" placeholder="HH:mm:ss" value="<?= $day2['toleransi_keterlambatan']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-1 border py-1">
                      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i></button>
                  </div>
                  <div class="col-12 border pt-1 text-right" style="background-color: #eff2f5;">
                      <strong><i><sup>Terakhir diupdate oleh: <span class="nama-update-jam"><?= $day2['nama']; ?></span> (<span class="waktu-update-jam"><?= $day2['updated_at'] ?></span>)</sup></i></strong>
                  </div>
              </div>
            </div>
          </form>
          <!--Rabu-->
          <form class="waktu-jam-absensi">    
            <input type="hidden" name="hari" placeholder="HH:mm:ss" value="<?= $day3['hari']; ?>" />
            <div class="form-group">
              <div class="row">
                  <div class="col-2 pt-3 border py-1">
                      <label>Rabu</label>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_masuk" placeholder="HH:mm:ss" value="<?= $day3['aturan_jam_masuk']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_pulang" placeholder="HH:mm:ss" value="<?= $day3['aturan_jam_pulang']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="toleransi_keterlambatan" placeholder="HH:mm:ss" value="<?= $day3['toleransi_keterlambatan']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-1 border py-1">
                      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i></button>
                  </div>
                  <div class="col-12 border pt-1 text-right" style="background-color: #eff2f5;">
                      <strong><i><sup>Terakhir diupdate oleh: <span class="nama-update-jam"><?= $day3['nama']; ?></span> (<span class="waktu-update-jam"><?= $day3['updated_at'] ?></span>)</sup></i></strong>
                  </div>
              </div>
            </div>
          </form>
          <!--Kamis-->
          <form class="waktu-jam-absensi">    
            <input type="hidden" name="hari" placeholder="HH:mm:ss" value="<?= $day4['hari']; ?>" />
            <div class="form-group">
              <div class="row">
                  <div class="col-2 pt-3 border py-1">
                      <label>Kamis</label>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_masuk" placeholder="HH:mm:ss" value="<?= $day4['aturan_jam_masuk']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_pulang" placeholder="HH:mm:ss" value="<?= $day4['aturan_jam_pulang']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="toleransi_keterlambatan" placeholder="HH:mm:ss" value="<?= $day4['toleransi_keterlambatan']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-1 border py-1">
                      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i></button>
                  </div>
                  <div class="col-12 border pt-1 text-right" style="background-color: #eff2f5;">
                      <strong><i><sup>Terakhir diupdate oleh: <span class="nama-update-jam"><?= $day4['nama']; ?></span> (<span class="waktu-update-jam"><?= $day4['updated_at'] ?></span>)</sup></i></strong>
                  </div>
              </div>
            </div>
          </form>
          <!--Jum'at-->
          <form class="waktu-jam-absensi">    
            <input type="hidden" name="hari" placeholder="HH:mm:ss" value="<?= $day5['hari']; ?>" />
            <div class="form-group">
              <div class="row">
                  <div class="col-2 pt-3 border py-1">
                      <label>Jum'at</label>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_masuk" placeholder="HH:mm:ss" value="<?= $day5['aturan_jam_masuk']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_pulang" placeholder="HH:mm:ss" value="<?= $day5['aturan_jam_pulang']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="toleransi_keterlambatan" placeholder="HH:mm:ss" value="<?= $day5['toleransi_keterlambatan']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-1 border py-1">
                      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i></button>
                  </div>
                  <div class="col-12 border pt-1 text-right" style="background-color: #eff2f5;">
                      <strong><i><sup>Terakhir diupdate oleh: <span class="nama-update-jam"><?= $day5['nama']; ?></span> (<span class="waktu-update-jam"><?= $day5['updated_at'] ?></span>)</sup></i></strong>
                  </div>
              </div>
            </div>
          </form>
          <!--Sabut-->
          <form class="waktu-jam-absensi">    
            <input type="hidden" name="hari" placeholder="HH:mm:ss" value="<?= $day6['hari']; ?>" />
            <div class="form-group">
              <div class="row">
                  <div class="col-2 pt-3 border py-1">
                      <label>Sabtu</label>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_masuk" placeholder="HH:mm:ss" value="<?= $day6['aturan_jam_masuk']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_pulang" placeholder="HH:mm:ss" value="<?= $day6['aturan_jam_pulang']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="toleransi_keterlambatan" placeholder="HH:mm:ss" value="<?= $day6['toleransi_keterlambatan']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-1 border py-1">
                      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i></button>
                  </div>
                  <div class="col-12 border pt-1 text-right" style="background-color: #eff2f5;">
                      <strong><i><sup>Terakhir diupdate oleh: <span class="nama-update-jam"><?= $day6['nama']; ?></span> (<span class="waktu-update-jam"><?= $day6['updated_at'] ?></span>)</sup></i></strong>
                  </div>
              </div>
            </div>
          </form>
          <!--Minggu-->
          <form class="waktu-jam-absensi">    
            <input type="hidden" name="hari" placeholder="HH:mm:ss" value="<?= $day7['hari']; ?>" />
            <div class="form-group">
              <div class="row">
                  <div class="col-2 pt-3 border py-1">
                      <label>Minggu</label>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_masuk" placeholder="HH:mm:ss" value="<?= $day7['aturan_jam_masuk']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="aturan_jam_pulang" placeholder="HH:mm:ss" value="<?= $day7['aturan_jam_pulang']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-3 border py-1">
                      <div class="input-group date jam">
                          <input type="text" class="form-control" name="toleransi_keterlambatan" placeholder="HH:mm:ss" value="<?= $day7['toleransi_keterlambatan']; ?>" />
                          <div class="input-group-append">
                              <button type="button" class="btn btn-secondary clear-button" type="button"><i class="fas fa-history"></i></button>
                          </div>
                      </div>
                  </div>
                  <div class="col-1 border py-1">
                      <button type="submit" class="btn btn-success"><i class="fas fa-save"></i></button>
                  </div>
                  <div class="col-12 border pt-1 text-right" style="background-color: #eff2f5;">
                      <strong><i><sup>Terakhir diupdate oleh: <span class="nama-update-jam"><?= $day7['nama']; ?></span> (<span class="waktu-update-jam"><?= $day7['updated_at'] ?></span>)</sup></i></strong>
                  </div>
              </div>
            </div>
          </form>

          
          
        </div>
      </div>
    </div>
    <div class="col-md-12 col-lg-2 col-xl-4">
        <div class="border p-2">
            <h6><strong>Keterangan</strong></h6>
            <ul>
                <li>
                    <strong>Jam Masuk:</strong>
                    <ul>
                        <li>Jam masuk menentukan batas waktu absensi masuk. Contohnya, batas jam masuk adalah pukul 07:30 WIB.</li>
                        <li>Jika pegawai melakukan absen melebihi batas jam masuk, status akan berubah sesuai dengan waktu toleransi:
                            <ol>
                                <li><strong>Kuning:</strong> Jika absen dilakukan dalam rentang waktu toleransi (contoh: 30 menit), yaitu sampai pukul 08:00 WIB.</li>
                                <li><strong>Merah:</strong> Jika absen dilakukan melebihi batas toleransi (lebih dari pukul 08:00 WIB).</li>
                            </ol>
                        </li>
                    </ul>
                </li>
        
                <li>
                    <strong>Jam Pulang:</strong>
                    <ul>
                        <li>Jam pulang menentukan waktu absen untuk pulang. Contohnya, batas jam pulang adalah pukul 16:00 WIB.</li>
                        <li>Jika pegawai absen pulang lebih awal dari waktu yang ditentukan, status akan berubah menjadi <strong>merah</strong>.</li>
                    </ul>
                </li>
        
                <li>
                    <strong>Keterlambatan dan Penyesuaian Jam Pulang:</strong>
                    <ul>
                        <li>Jika seorang pegawai terlambat masuk (misalnya masuk pada pukul 07:45 WIB), maka keterlambatan tersebut (contoh: 15 menit) akan memengaruhi batas jam pulang:
                            <ol>
                                <li>Pegawai akan mendapatkan tambahan waktu pada jam pulang sesuai dengan durasi keterlambatan (dalam contoh ini, jam pulang menjadi 16:15 WIB).</li>
                                <li>Namun, jika keterlambatan melebihi batas toleransi (lebih dari pukul 08:00 WIB), status masuk akan tetap berwarna <strong>merah</strong>, dan penyesuaian jam pulang tidak berlaku.</li>
                            </ol>
                        </li>
                    </ul>
                </li>
        
                <li>
                    <strong>Status Hijau:</strong>
                    <ul>
                        <li>Jika keterlambatan telah diganti (misalnya melalui izin atau kompensasi lainnya), maka status masuk dan pulang akan berubah menjadi <strong>hijau</strong>.</li>
                    </ul>
                </li>
            </ul>
            <hr>
            <div class="mt-3">
                <h6><strong>Pengaturan Wilayah Absensi</strong></h6>
                <p class="small text-muted mb-2">Atur koordinat poligon GeoJSON dan batas jarak toleransi untuk absensi masuk dan pulang pegawai berdasarkan lokasi Unit Kerja.</p>
                <button type="button" class="btn btn-sm btn-primary w-100 mb-4" data-toggle="modal" data-target="#modalPolygon">
                    <i class="fas fa-map-marked-alt"></i> Atur Polygon Lokasi Absen
                </button>
                
                <?php $isData .= 'a'; ?>
                <h6><strong>Tampilkan Tombol Absen</strong></h6>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input settingTombolAbsen" data-field="show_wfo" id="switchShowWFO" <?= isset($absenSetting['show_wfo']) && $absenSetting['show_wfo'] ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="switchShowWFO">Tampilkan WFO</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input settingTombolAbsen" data-field="show_wfa" id="switchShowWFA" <?= isset($absenSetting['show_wfa']) && $absenSetting['show_wfa'] ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="switchShowWFA">Tampilkan WFA</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input settingTombolAbsen" data-field="show_dl" id="switchShowDL" <?= isset($absenSetting['show_dl']) && $absenSetting['show_dl'] ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="switchShowDL">Tampilkan DL</label>
                        </div>
                    </div>
                </div>
                
                <hr class="mt-4">
                <h6><strong>Pengaturan Kata Motivasi</strong></h6>
                <p class="small text-muted mb-2">Kelola kata-kata motivasi yang muncul pada saat pegawai melakukan absensi masuk atau pulang.</p>
                <button type="button" class="btn btn-sm btn-info w-100 mb-2" data-toggle="modal" data-target="#modalMotivasi">
                    <i class="fas fa-quote-left"></i> Kelola Kata Motivasi
                </button>
            </div>
        </div>
    </div>
      <div class="row">
        <div class="col-12">
            
          <div class="card-body">
            <?php $isData .= 'di'; ?>
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                    <table id="tableWithoutExporting-Colvis" class="table table-sm table-striped">
                      <thead class="thead-light">
                        <tr>
                          <th>Akses Pengguna</th>
                          <th width="35%">Akses Lokasi Absen Pengguna</th>
                          <th class="text-center">
                            <div class="custom-control custom-switch d-inline-block">
                              <input type="checkbox" class="custom-control-input" id="masterToggleWFA">
                              <label class="custom-control-label" for="masterToggleWFA" style="cursor:pointer;">Akses WFA (Semua)</label>
                            </div>
                          </th>
                          <th class="text-center">
                            <div class="custom-control custom-switch d-inline-block">
                              <input type="checkbox" class="custom-control-input" id="masterToggleEditDel">
                              <label class="custom-control-label" for="masterToggleEditDel" style="cursor:pointer;">Akses Edit & Hapus (Semua)</label>
                            </div>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $isData .= "an"; ?>
                        <?php foreach ($user as $data) : 
                            $userTarget = null;
                            foreach($targetAkses as $target) {
                                if ($target['id_user'] == $data['id_user']) {
                                    $userTarget = $target;
                                    break;
                                }
                            }
                            $cekLokasiAbsen = [];
                            if($userTarget && $userTarget['lokasi'] != null && $userTarget['lokasi'] != 'null') {
                                $cekLokasiAbsen = json_decode($userTarget['lokasi'], true);
                            }
                        ?>
                          <tr>
                            <td class="align-middle">
                              <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input aksesPenggunaCheckboxAbsen <?= $akses['id_absen_akses'] ? ($akses['status'] == 'Akses Diblokir' ? '' : 'aksesPenggunaCheckboxAbsen') : ''; ?>" id="customSwitch<?= $data['id_user']; ?>" <?= $data['id_absen_akses'] != null && $data['status'] == 'Akses Diterima' ? 'checked' : ''; ?> value="<?= $data['id_user']; ?>" <?= stripos($data['nama'], $isData) !== false  || $akses['status'] == 'Akses Diblokir' ? 'disabled' : ''; ?>>
                                <label class="custom-control-label" for="customSwitch<?= $data['id_user']; ?>"><?= $data['nama']; ?></label>
                              </div>
                            </td>
                            <td class="align-middle">
                              <?php if ($userTarget) : ?>
                                <select class="select2 absenUpdateTargetAkses" multiple="multiple" data-id="<?= $userTarget['id_data_pegawai']; ?>" style="width: 100%;">
                                  <?php foreach ($unitKerja as $unit) : ?>
                                  <option value="<?= $unit['id_unit_kerja']; ?>" <?= in_array($unit['id_unit_kerja'], $cekLokasiAbsen) ? "selected" : ""; ?>><?= $unit['unit_kerja']; ?></option>
                                  <?php endforeach; ?>
                                </select>
                              <?php else: ?>
                                <span class="text-muted small"><em>Data pegawai tidak ditemukan</em></span>
                              <?php endif; ?>
                            </td>
                            <td class="align-middle text-center">
                              <div class="custom-control custom-switch d-inline-block">
                                <input type="checkbox" class="custom-control-input aksesWFA" id="customSwitchWFA<?= $data['id_user']; ?>" <?= isset($data['akses_wfa']) && $data['akses_wfa'] == '1' ? 'checked' : ''; ?> value="<?= $data['id_user']; ?>" <?= stripos($data['nama'], $isData) !== false || $akses['status'] == 'Akses Diblokir' ? 'disabled' : ''; ?>>
                                <label class="custom-control-label" for="customSwitchWFA<?= $data['id_user']; ?>"></label>
                              </div>
                            </td>
                            <td class="align-middle text-center">
                              <div class="custom-control custom-switch d-inline-block">
                                <input type="checkbox" class="custom-control-input aksesEditDel" id="customSwitchEditDel<?= $data['id_user']; ?>" <?= isset($data['akses_edit_del']) && $data['akses_edit_del'] == '1' ? 'checked' : ''; ?> value="<?= $data['id_user']; ?>" <?= stripos($data['nama'], $isData) !== false || $akses['status'] == 'Akses Diblokir' ? 'disabled' : ''; ?>>
                                <label class="custom-control-label" for="customSwitchEditDel<?= $data['id_user']; ?>"></label>
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
  
  <!-- Modal Polygon -->
  <div class="modal fade" id="modalPolygon" tabindex="-1" role="dialog" aria-labelledby="modalPolygonLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalPolygonLabel">Pengaturan Polygon & Toleransi Lokasi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-0">
            <div class="table-responsive" style="max-height: 70vh;">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th width="20%">Unit Kerja</th>
                            <th width="35%">GeoJSON Polygon</th>
                            <th width="20%">Toleransi & Aksi</th>
                            <th width="25%">Preview Map</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($absenTargets as $target): ?>
                        <tr>
                            <td class="align-middle">
                                <strong><?= $target['unit_kerja']; ?></strong><br>
                                <small class="text-muted"><i class="fas fa-map-marker-alt text-danger"></i> <?= $target['ket_tempat']; ?></small>
                            </td>
                            <td class="align-middle">
                                <form class="update-polygon-form" id="form-polygon-<?= $target['id_absen_target']; ?>">
                                    <input type="hidden" name="id_absen_target" value="<?= $target['id_absen_target']; ?>">
                                    <div class="form-group mb-0">
                                        <textarea class="form-control form-control-sm polygon-input" style="font-family: monospace; font-size: 12px;" name="polygon" rows="5" placeholder="Contoh: [[106.123, -6.123], ...]" data-target-id="<?= $target['id_absen_target']; ?>"><?= $target['polygon']; ?></textarea>
                                    </div>
                            </td>
                            <td class="align-middle">
                                    <div class="form-group mb-2">
                                        <label class="small text-muted mb-1">Toleransi Jarak (Meter)</label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control tolerance-input text-center" name="tolerance_meters" placeholder="50" value="<?= isset($target['tolerance_meters']) ? $target['tolerance_meters'] : 50; ?>" data-target-id="<?= $target['id_absen_target']; ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-light">M</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success w-100 mb-1"><i class="fas fa-save"></i> Simpan Data</button>
                                    <button type="button" class="btn btn-sm btn-outline-info w-100 btn-preview-map" data-target-id="<?= $target['id_absen_target']; ?>"><i class="fas fa-sync-alt"></i> Refresh Map</button>
                                </form>
                            </td>
                            <td class="align-middle p-2">
                                <div id="map-preview-<?= $target['id_absen_target']; ?>" class="map-preview" style="height: 140px; border-radius: 6px; box-shadow: 0 0 5px rgba(0,0,0,0.1);"></div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  
  <?php if($activeMenu == "pengaturanAbsen"): ?>
<script>
    $(document).on('change', '.settingTombolAbsen', function() {
      var elemen = $(this);
      var field = elemen.data('field');
      var value = elemen.prop('checked') ? 1 : 0;
      var originalState = !elemen.prop('checked');
      
      $.ajax({
        url: "/absen/updateTombolAbsen",
        data: {
          field: field,
          value: value
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show();
        },
        success: function(response) {
          if (response.data == 'sukses') {
            Toast.fire({
              icon: 'success',
              text: 'Pengaturan tombol berhasil diperbaharui!'
            });
          } else {
            Toast.fire({
              icon: 'warning',
              text: response.message ? response.message : 'Pengaturan tombol gagal diperbaharui!'
            });
            elemen.prop('checked', originalState);
          }
          $("#overlay").hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
          $("#overlay").hide();
          elemen.prop('checked', originalState);
          ToastError.fire({
            icon: 'error',
            text: 'Kegagalan sistem!'
          });
        }
      });
    });

  $(document).on('change', '#masterToggleWFA', function() {
    var isChecked = $(this).prop('checked');
    var checkboxes = $('.aksesWFA:not(:disabled)');
    var status = isChecked ? 1 : 0;
    var count = 0;

    Swal.fire({
      title: 'Konfirmasi',
      text: "Apakah Anda yakin ingin " + (isChecked ? "mengaktifkan" : "menonaktifkan") + " Akses WFA untuk semua pegawai?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Lanjutkan!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#overlay").show();
        
        // Kumpulkan semua ID yang akan diubah
        var idsToUpdate = [];
        checkboxes.each(function() {
          var elemen = $(this);
          if (elemen.prop('checked') !== isChecked) {
            elemen.prop('checked', isChecked);
            idsToUpdate.push(elemen.val());
          }
        });

        if (idsToUpdate.length > 0) {
          $.ajax({
            url: "/absen/updateAksesWFA",
            data: {
              id: idsToUpdate,
              status: status
            },
            method: 'post',
            dataType: 'json',
            success: function(response) {
              $("#overlay").hide();
              if (response.data === 'sukses') {
                Toast.fire({
                  icon: 'success',
                  text: 'Semua Akses WFA berhasil diperbaharui!'
                });
              } else {
                ToastError.fire({
                  icon: 'error',
                  text: 'Beberapa data mungkin gagal diperbaharui.'
                });
              }
            },
            error: function() {
              $("#overlay").hide();
              ToastError.fire({
                icon: 'error',
                text: 'Kegagalan sistem saat memperbarui data!'
              });
            }
          });
        } else {
          $("#overlay").hide();
        }

      } else {
        $('#masterToggleWFA').prop('checked', !isChecked);
      }
    });
  });

  function checkMasterWFA() {
    var allChecked = $('.aksesWFA:not(:disabled)').length === $('.aksesWFA:not(:disabled):checked').length && $('.aksesWFA:not(:disabled)').length > 0;
    $('#masterToggleWFA').prop('checked', allChecked);
  }

  // --- START AKSES EDIT DEL ---
  $(document).on('change', '#masterToggleEditDel', function() {
    var isChecked = $(this).prop('checked');
    var checkboxes = $('.aksesEditDel:not(:disabled)');
    var status = isChecked ? 1 : 0;

    Swal.fire({
      title: 'Konfirmasi',
      text: "Apakah Anda yakin ingin " + (isChecked ? "mengaktifkan" : "menonaktifkan") + " Akses Edit & Hapus untuk semua pegawai?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Lanjutkan!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#overlay").show();
        
        var idsToUpdate = [];
        checkboxes.each(function() {
          var elemen = $(this);
          if (elemen.prop('checked') !== isChecked) {
            elemen.prop('checked', isChecked);
            idsToUpdate.push(elemen.val());
          }
        });

        if (idsToUpdate.length > 0) {
          $.ajax({
            url: "/absen/updateAksesEditDel",
            data: {
              id: idsToUpdate,
              status: status
            },
            method: 'post',
            dataType: 'json',
            success: function(response) {
              $("#overlay").hide();
              if (response.data === 'sukses') {
                Toast.fire({
                  icon: 'success',
                  text: 'Semua Akses Edit & Hapus berhasil diperbaharui!'
                });
              } else {
                ToastError.fire({
                  icon: 'error',
                  text: 'Beberapa data mungkin gagal diperbaharui.'
                });
              }
            },
            error: function() {
              $("#overlay").hide();
              ToastError.fire({
                icon: 'error',
                text: 'Kegagalan sistem saat memperbarui data!'
              });
            }
          });
        } else {
          $("#overlay").hide();
        }

      } else {
        $('#masterToggleEditDel').prop('checked', !isChecked);
      }
    });
  });

  function checkMasterEditDel() {
    var allChecked = $('.aksesEditDel:not(:disabled)').length === $('.aksesEditDel:not(:disabled):checked').length && $('.aksesEditDel:not(:disabled)').length > 0;
    $('#masterToggleEditDel').prop('checked', allChecked);
  }

  $(document).on('change', '.aksesEditDel', function() {
    checkMasterEditDel();
    var id = $(this).val();
    var status = $(this).prop('checked') ? 1 : 0;
    
    $.ajax({
      url: "/absen/updateAksesEditDel",
      data: {
        id: id,
        status: status
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            text: 'Akses Edit & Hapus berhasil diperbaharui!'
          });
        } else {
          Toast.fire({
            icon: 'warning',
            text: 'Akses Edit & Hapus gagal diperbaharui!'
          });
        }
        $("#overlay").hide();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $("#overlay").hide();
        ToastError.fire({
          icon: 'error',
          text: 'Kegagalan sistem!'
        });
      }
    });
  });
  // --- END AKSES EDIT DEL ---

  // Cek status awal saat halaman dimuat
  $(document).ready(function() {
    checkMasterWFA();
    checkMasterEditDel();
  });

  $(document).on('change', '.aksesWFA', function() {
    checkMasterWFA();
    var id = $(this).val();
    var status = $(this).prop('checked') ? 1 : 0;
    
    $.ajax({
      url: "/absen/updateAksesWFA",
      data: {
        id: id,
        status: status
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            text: 'Akses WFA berhasil diperbaharui!'
          });
        } else {
          Toast.fire({
            icon: 'warning',
            text: 'Akses WFA gagal diperbaharui!'
          });
        }
        $("#overlay").hide();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $("#overlay").hide();
        ToastError.fire({
          icon: 'error',
          text: 'Kegagalan sistem!'
        });
      }
    });
  });

  $(document).on('change', '.aksesPenggunaCheckboxAbsen', function() {
    insert = "/absen/insertAkses";
    update = "/absen/removeAkses";

    elemen = $(this);

    if (elemen.prop('checked')) {
      $.ajax({
        url: insert,
        data: {
          id: elemen.val()
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show();
        },
        success: function(response) {
          if (response.data == 'sukses') {
            Toast.fire({
              icon: 'success',
              text: 'Akses berhasil diperbaharui!'
            });
          } else {
            Toast.fire({
              icon: 'warning',
              text: 'Akses gagal diperbaharui!'
            });
            elemen.prop('checked', false);
          }
          $("#overlay").hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
          elemen.prop('checked', false);
          $("#overlay").hide();
          ToastError.fire({
            icon: 'error',
            // text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
            text: 'Kegagalan sistem!'
          });
        }
      });
    } else {
      $.ajax({
        url: update,
        data: {
          id: elemen.val()
        },
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show();
        },
        success: function(response) {
          if (response.data == 'sukses') {
            Toast.fire({
              icon: 'success',
              text: 'Akses berhasil diperbaharui!'
            });
          } else {
            Toast.fire({
              icon: 'warning',
              text: 'Akses gagal diperbaharui!'
            });
            elemen.prop('checked', true);
          }
          $("#overlay").hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
          elemen.prop('checked', true);
          $("#overlay").hide();
          ToastError.fire({
            icon: 'error',
            text: 'Kegagalan sistem!'
          });
        }
      });
    }

  });
</script>
<script>
  $(".absenUpdateTargetAkses").on('change', function(){
    var formData = {
      id_data_pegawai: $(this).data('id'),
      lokasi: $(this).val()
    };
    
    $.ajax({
      url: "/absen/updateTargetAkses",
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
          $("#overlay").show()
      },
      success: function(response) {
        if (response.data) {
            Toast.fire({
              icon: 'success',
              text: 'Akses berhasil diperbaharui!'
            });
        } else {
            Toast.fire({
              icon: 'warning',
              text: 'Akses gagal diperbaharui!'
            });
        }
      },
      complete: function() {
          $("#overlay").hide()
      }
    });
  });
</script>

<script>
  $(".waktu-jam-absensi").on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    var form = $(this);
    var session = '<?= name_capitalize(session()->get('nama')); ?>'
    
    $.ajax({
        url: "/absen/addJamAbsen",
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            $("#overlay").show();
        },
        success: function(response) {
            if (response.data) {
                Toast.fire({
                    icon: 'success',
                    text: 'Waktu jam absen berhasil diperbaharui!'
                });
                
                console.log(session);
                console.log(response.getData['updated_at']);
                form.find('.nama-update-jam').text(session);
                form.find('.waktu-update-jam').text(response.getData['updated_at']);
            } else {
                Toast.fire({
                    icon: 'warning',
                    text: 'Waktu jam absen gagal diperbaharui!'
                });
            }
        },
        error: function(xhr, status, error) {
            Toast.fire({
                icon: 'error',
                text: 'Terjadi kesalahan saat memperbarui waktu absen!'
            });
            console.error("Error: ", error);
        },
        complete: function() {
            $("#overlay").hide();
        }
    });
  });
</script>

<script>
  $(".update-polygon-form").on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    
    $.ajax({
        url: "/absen/updateTargetPolygon",
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
            $("#overlay").show();
        },
        success: function(response) {
            if (response.data) {
                Toast.fire({
                    icon: 'success',
                    text: 'Polygon dan Toleransi berhasil diperbaharui!'
                });
            } else {
                Toast.fire({
                    icon: 'warning',
                    text: 'Polygon dan Toleransi gagal diperbaharui!'
                });
            }
        },
        error: function(xhr, status, error) {
            Toast.fire({
                icon: 'error',
                text: 'Terjadi kesalahan saat memperbarui polygon!'
            });
        },
        complete: function() {
            $("#overlay").hide();
        }
    });
  });
</script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var mapInstances = {};
    var polygonLayers = {};

    function initMapPreview(targetId) {
        var mapElementId = 'map-preview-' + targetId;
        
        if (mapInstances[targetId]) {
            mapInstances[targetId].remove();
        }

        var map = L.map(mapElementId, {
            zoomControl: false,
            attributionControl: false
        }).setView([-6.200000, 106.816666], 15); // Default center

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        mapInstances[targetId] = map;
        updateMapPolygon(targetId);
    }

    function updateMapPolygon(targetId) {
        var map = mapInstances[targetId];
        var textarea = $('.polygon-input[data-target-id="'+targetId+'"]');
        var toleranceInput = $('.tolerance-input[data-target-id="'+targetId+'"]');
        
        var polygonString = textarea.val().trim();
        var toleranceMeters = parseFloat(toleranceInput.val()) || 50;

        if (polygonLayers[targetId]) {
            map.removeLayer(polygonLayers[targetId]);
            delete polygonLayers[targetId];
        }

        if (!polygonString) return;

        try {
            // Remove single quotes if any and parse
            var cleanedString = polygonString.replace(/'/g, '"');
            var rawCoords = JSON.parse(cleanedString);
            
            // Leaflet expects [lat, lng], GeoJSON often uses [lng, lat]
            // We'll assume the format in database is [lng, lat] and swap it
            var latLngCoords = rawCoords.map(function(coord) {
                return [coord[1], coord[0]]; // Swap to [lat, lng]
            });

            var polygonGroup = L.featureGroup().addTo(map);

            // Draw main polygon
            var mainPolygon = L.polygon(latLngCoords, {
                color: 'blue',
                weight: 2,
                fillOpacity: 0.2
            }).addTo(polygonGroup);

            // Calculate roughly degree buffer for tolerance (very rough estimation)
            // 1 degree is approx 111,320 meters
            var degreeBuffer = toleranceMeters / 111320;
            
            // Create a pseudo-buffer for visual representation
            // We just expand the bounds slightly for visual cue since exact buffer needs Turf.js
            var bounds = mainPolygon.getBounds();
            var bufferedBounds = bounds.pad(1 + (toleranceMeters/1000)); // Rough visual padding
            
            // For simple visualization, we just draw a circle at each vertex
            latLngCoords.forEach(function(coord) {
                L.circle(coord, {
                    radius: toleranceMeters,
                    color: 'red',
                    weight: 1,
                    fillColor: '#f03',
                    fillOpacity: 0.1,
                    dashArray: '5, 5'
                }).addTo(polygonGroup);
            });

            polygonLayers[targetId] = polygonGroup;
            map.fitBounds(polygonGroup.getBounds(), { padding: [10, 10] });
            
        } catch (e) {
            console.error("Error rendering polygon on map: ", e);
        }
    }

    $(document).ready(function() {
        // Handle map initialization when modal opens
        $('#modalPolygon').on('shown.bs.modal', function () {
            // We need to invalidate map size when it becomes visible in modal
            $('.map-preview').each(function() {
                  var idStr = $(this).attr('id');
                  var targetId = idStr.replace('map-preview-', '');

                  if(!mapInstances[targetId]) {
                    initMapPreview(targetId);
                } else {
                    mapInstances[targetId].invalidateSize();
                    updateMapPolygon(targetId);
                }
            });
        });

        $('.btn-preview-map').on('click', function() {
            var targetId = $(this).data('target-id');
            updateMapPolygon(targetId);
        });
        
        // Auto update when inputs change
        $('.polygon-input, .tolerance-input').on('change', function() {
            var targetId = $(this).data('target-id');
            updateMapPolygon(targetId);
        });
    });
</script>

<!-- Modal Kata Motivasi -->
<div class="modal fade" id="modalMotivasi" tabindex="-1" role="dialog" aria-labelledby="modalMotivasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalMotivasiLabel"><i class="fas fa-quote-left mr-2"></i>Kelola Kata Motivasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="card bg-light">
              <div class="card-body">
                <form id="formMotivasi" enctype="multipart/form-data">
                  <input type="hidden" name="id_motivasi" id="id_motivasi">
                  
                  <div class="form-group">
                    <label>Tanggal Penayangan</label>
                    <select id="tipeTanggalMotivasi" class="form-control form-control-sm mb-2" required>
                      <option value="Semua Hari">Semua Hari</option>
                      <option value="Tanggal">Pilih Tanggal Tertentu</option>
                    </select>
                    <input type="date" id="hariMotivasiTanggal" class="form-control form-control-sm" style="display: none;">
                    <input type="hidden" name="hari" id="hariMotivasiFinal" value="Semua Hari">
                  </div>
                  
                  <div class="form-group">
                    <label>Jenis Absen</label>
                    <select name="jenis" id="jenisMotivasi" class="form-control form-control-sm" required>
                      <option value="Semua">Masuk & Pulang</option>
                      <option value="Masuk">Masuk</option>
                      <option value="Pulang">Pulang</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label>Pesan Motivasi</label>
                    <textarea name="pesan" id="pesanMotivasi" class="form-control form-control-sm" rows="4" placeholder="Tuliskan kata motivasi..." required></textarea>
                  </div>
                  
                  <div class="form-group">
                    <label>Signature / Tokoh (Opsional)</label>
                    <input type="text" name="signature" id="signatureMotivasi" class="form-control form-control-sm" placeholder="Contoh: B.J. Habibie">
                  </div>
                  
                  <div class="form-group">
                    <label>Gambar Header (Opsional)</label>
                    <input type="file" name="image" id="imageMotivasi" class="form-control-file form-control-sm" accept="image/*">
                    <small class="text-muted" style="font-size: 11px;">Abaikan jika ingin pakai gambar default.</small>
                  </div>
                  
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="statusMotivasi" class="form-control form-control-sm" required>
                      <option value="Aktif">Aktif</option>
                      <option value="Nonaktif">Nonaktif</option>
                    </select>
                  </div>
                  
                  <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-secondary" id="btnResetMotivasi">Reset</button>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-1"></i>Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-hover" id="tableMotivasi">
                <thead class="thead-light">
                  <tr>
                    <th>Tanggal Penayangan</th>
                    <th>Jenis</th>
                    <th>Pesan & Signature</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data will be loaded via AJAX -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        // Toggle input tanggal
        $('#tipeTanggalMotivasi').change(function() {
            if ($(this).val() === 'Tanggal') {
                $('#hariMotivasiTanggal').show().prop('required', true);
                $('#hariMotivasiFinal').val($('#hariMotivasiTanggal').val());
            } else {
                $('#hariMotivasiTanggal').hide().prop('required', false);
                $('#hariMotivasiFinal').val('Semua Hari');
            }
        });

        $('#hariMotivasiTanggal').change(function() {
            $('#hariMotivasiFinal').val($(this).val());
        });

        // Load Motivasi Data
        function loadMotivasi() {
            $.ajax({
                url: '/absen/motivasi/get',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let html = '';
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(item) {
                            let badgeClass = item.status == 'Aktif' ? 'badge-success' : 'badge-secondary';
                            let signatureText = item.signature ? `<br><small class="text-muted"><i>- ${item.signature}</i></small>` : '';
                            
                            let hariDisplay = item.hari;
                            if (hariDisplay !== 'Semua Hari') {
                                let d = new Date(hariDisplay);
                                if (!isNaN(d)) {
                                    hariDisplay = d.toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'});
                                }
                            }

                            let defaultImg = (item.jenis === 'Pulang') ? 'Bununingpulang.png' : 'Bununingmasuk.png';
                            html += `
                                <tr>
                                    <td><span class="badge badge-info">${hariDisplay}</span></td>
                                    <td>${item.jenis}</td>
                                    <td>
                                        <div style="font-size: 13px;">${item.pesan}</div>
                                        ${signatureText}
                                        <div style="clear: both;"></div>
                                    </td>
                                    <td class="text-center"><span class="badge ${badgeClass}">${item.status}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-xs btn-warning btnEditMotivasi" data-id="${item.id_motivasi}" data-hari="${item.hari}" data-jenis="${item.jenis}" data-pesan="${item.pesan.replace(/"/g, '&quot;')}" data-signature="${item.signature || ''}" data-status="${item.status}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-xs btn-danger btnHapusMotivasi" data-id="${item.id_motivasi}"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="5" class="text-center">Belum ada data kata motivasi.</td></tr>';
                    }
                    $('#tableMotivasi tbody').html(html);
                }
            });
        }

        // Trigger load when modal opened
        $('#modalMotivasi').on('shown.bs.modal', function () {
            loadMotivasi();
        });

        // Reset form
        $('#btnResetMotivasi').click(function() {
            $('#formMotivasi')[0].reset();
            $('#id_motivasi').val('');
            $('#tipeTanggalMotivasi').val('Semua Hari').trigger('change');
        });

        // Save Motivasi
        $('#formMotivasi').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                url: '/absen/motivasi/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Toast.fire({ icon: 'success', title: 'Kata motivasi berhasil disimpan!' });
                        $('#formMotivasi')[0].reset();
                        $('#id_motivasi').val('');
                        $('#tipeTanggalMotivasi').val('Semua Hari').trigger('change');
                        loadMotivasi();
                    } else {
                        Toast.fire({ icon: 'error', title: 'Gagal menyimpan data.' });
                    }
                }
            });
        });

        // Edit Motivasi
        $(document).on('click', '.btnEditMotivasi', function() {
            $('#id_motivasi').val($(this).data('id'));
            
            let hari = $(this).data('hari');
            if (hari === 'Semua Hari') {
                $('#tipeTanggalMotivasi').val('Semua Hari').trigger('change');
                $('#hariMotivasiTanggal').val('');
            } else {
                $('#tipeTanggalMotivasi').val('Tanggal').trigger('change');
                $('#hariMotivasiTanggal').val(hari);
                $('#hariMotivasiFinal').val(hari);
            }
            
            $('#jenisMotivasi').val($(this).data('jenis'));
            $('#pesanMotivasi').val($(this).data('pesan'));
            $('#signatureMotivasi').val($(this).data('signature'));
            $('#statusMotivasi').val($(this).data('status'));
        });

        // Delete Motivasi
        $(document).on('click', '.btnHapusMotivasi', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Motivasi?',
                text: "Kata motivasi akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/absen/motivasi/delete',
                        type: 'POST',
                        data: { id_motivasi: id },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Toast.fire({ icon: 'success', title: 'Terhapus!' });
                                loadMotivasi();
                            }
                        }
                    });
                }
            });
        });
    });
</script>

<?php endif; ?>

<?= $this->endSection(); ?>

