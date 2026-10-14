<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
          <h3 class="card-title mt-1 font-weight-bold">Daftar Hadir Dinas Luar</h3>
          
          <div class="card-tools d-flex align-items-center" style="gap: 10px;">
            <?php if($superUser): ?>
              <div class="m-0 d-flex flex-wrap align-items-center" style="gap: 10px;">
                <div class="input-group input-group-sm m-0" style="width: auto;">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <input type="checkbox" data-id="tanggalSuperUserAbsen">
                    </span>
                  </div>
                  <input type="text" disabled name="tanggal" class="form-control input-tanggal-view" placeholder="Akses Super User" autocomplete="off" style="width: 200px; flex: none;" />
                </div>

                <button type="button" class="btn btn-primary btn-sm text-nowrap" data-toggle="modal" data-target="#modalExportLaporan">
                  <i class="fas fa-download"></i> Export Laporan
                </button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <div class="card-body dataAbsenHarian">
        <table id="tableWithoutExporting" class="table table-stripped table-sm table-bordered">
          <thead>
            <tr>
              <th style="width: 2%;">No</th>
              <th>Nama</th>
              <th>Status Masuk</th>
              <th style="width: 18%;">Alamat Masuk</th>
              <th>Jam Masuk</th>
              <th style="width: 7%;">Foto Masuk</th>
              <th style="width: 7%;">TTD Masuk</th>
              <th>Status Pulang</th>
              <th style="width: 18%;">Alamat Pulang</th>
              <th>Jam Pulang</th>
              <th style="width: 7%;">Foto Pulang</th>
              <th style="width: 7%;">TTD Pulang</th>
              <th>Jam Kerja</th>
              <th>Rekam Absen</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="addDataAbsenHarian">
            <?php
            $nomor = 1;
            foreach ($absen as $data) : 
            if ($data['status_masuk'] != 'DL' && $data['status_pulang'] != 'DL') continue;

            $konfirmasiAlamatMasuk = false;
            $konfirmasiAlamatPulang = false;
            
            $jamMasuk = $data['jam_masuk'];
            $jamPulang = $data['jam_pulang'];
            
            $aturanJamMasuk = $data["aturan_jam_masuk"];
            $aturanJamPulang = $data["aturan_jam_pulang"];
            $toleransiKeterlambatan = $data["toleransi_keterlambatan"];
            
            $statusAbsensi = statusAbsensi($jamMasuk,$jamPulang,$aturanJamMasuk,$aturanJamPulang,$toleransiKeterlambatan);
        
            if ($data['status_pulang'] == null) {
                $statusAbsen = "";
            } elseif ($data['status_pulang'] != $data['status_masuk']) {
                $statusAbsen = 'style="background-color: #f5c6cb !important;"';
            } else {
                $statusAbsen = "";
            }
            ?>
            
              <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $data['nama']; ?></td>
                <td><?= $data['status_masuk']; ?></td>
                <td <?= $statusAbsen; ?> class="class-absen-alamat-masuk" data-validation="<?= $data['validation_masuk']; ?>" data-jam="<?= $data['jam_masuk']; ?>" data-alamat="<?= $data['alamat_masuk']; ?>" data-longitude="<?= $data['longitude_masuk']; ?>" data-latitude="<?= $data['latitude_masuk']; ?>">
                    <?php if($data['jam_masuk'] != null): ?>
                        <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
                    <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?> class="
                <?php
                if($jamMasuk == null) {
                    echo "table-danger";
                } elseif($statusAbsensi['masuk'] == 'merah') {
                    echo "table-danger";
                } elseif($statusAbsensi['masuk'] == 'kuning') {
                    echo "table-warning";
                } elseif($statusAbsensi['masuk'] == 'hijau') {
                    echo "table-success";
                }
                ?>
                ">
                  <?= $data['jam_masuk'];
                  ?>
                </td>
        
                <td <?= $statusAbsen; ?> class="text-center">
                    
                  <?php if ($data['jam_masuk'] != null && $data['jam_masuk'] != '') : ?>
                    <?php if($data['keterangan'] == ""): ?>
                        <img src="/dist/img/boxed-bg.jpg" data-waktu="masuk" data-src="<?= $data['id_absen']; ?>" data-idabsen="<?= $data['id_absen']; ?>" data-ket="masuk" class="lazy galleryAction" alt="Foto Masuk" style="width: 100%; aspect-ratio: 9 / 16; object-fit: contain;" />
                    <?php else: ?>
                        <h4><?= $data['keterangan']; ?></h4>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?> class="text-center">
                  <?php if ($data['ttd_masuk'] != null && $data['ttd_masuk'] != '') : ?>
                    <img src="<?= $data['ttd_masuk']; ?>" alt="TTD Masuk" style="width: 100%; object-fit: contain;" />
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?>><?= $data['status_pulang']; ?></td>
                <td <?= $statusAbsen; ?> class="class-absen-alamat-pulang" data-validation="<?= $data['validation_pulang']; ?>" data-jam="<?= $data['jam_pulang']; ?>" data-alamat="<?= $data['alamat_pulang']; ?>" data-longitude="<?= $data['longitude_pulang']; ?>" data-latitude="<?= $data['latitude_pulang']; ?>">
                    <?php if($data['jam_pulang'] != null): ?>
                        <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
                    <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?> class="
                <?php
                if($jamPulang == null) {
                    echo "table-danger";
                } elseif($statusAbsensi['pulang'] == 'merah') {
                    echo "table-danger";
                } elseif($statusAbsensi['pulang'] == 'kuning') {
                    echo "table-warning";
                } elseif($statusAbsensi['pulang'] == 'hijau') {
                    echo "table-success";
                }
                ?>
                ">
                  <?= $data['jam_pulang']; 
                  ?>
                </td>
        
                <td <?= $statusAbsen; ?> class="text-center">
                  <?php if ($data['jam_pulang'] != null && $data['jam_pulang'] != '') : ?>
                    <?php if($data['keterangan'] == ""): ?>
                        <img src="/dist/img/boxed-bg.jpg" data-waktu="pulang" data-src="<?= $data['id_absen']; ?>" data-idabsen="<?= $data['id_absen']; ?>" data-ket="pulang" class="lazy galleryAction" alt="Foto Pulang" style="width: 100%; aspect-ratio: 9 / 16; object-fit: contain;" />
                    <?php else: ?>
                        <h4><?= $data['keterangan']; ?></h4>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?> class="text-center">
                  <?php if ($data['ttd_pulang'] != null && $data['ttd_pulang'] != '') : ?>
                    <img src="<?= $data['ttd_pulang']; ?>" alt="TTD Pulang" style="width: 100%; object-fit: contain;" />
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?>>
                  <?= selisi_waktu($data['jam_masuk'], $data['jam_pulang']); ?>
                </td>
                <td>
                    <?php if($data['id_absen'] && $data['jumlah_array'] > 0): ?>
                        <button class="btn btn-sm btn-info showRekamAbsen" data-id="<?= $data['id_absen']; ?>"> Lihat </button> <br />
                        <span><?= $data['jumlah_array']; ?> rekaman tersedia!</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($data['id_absen'] && (session()->get('role') == 'Admin' || (isset($aksesEditDel) && $aksesEditDel))): ?>
                        <div class="btn-group mb-2" role="group">
                            <button type="button" data-id="<?= $data['id_absen']; ?>" data-idpegawai="<?= $data['dp_id_data_pegawai']; ?>" class="btn btn-sm btn-warning btn-edit-absen"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-danger btn-delete-absen"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    <?php endif; ?>
                    
                    <h6 class="m-0 btn-verifikasi-masuk d-none">Verifikasi Masuk</h6>
                    <div class="btn-group btn-verifikasi-masuk d-none" role="group" aria-label="Basic example">
                      <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-success btn-absen-validasi-masuk"><i class="fas fa-map-marker-alt"></i> Validasi</button>
                      <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-masuk"><i class="fas fa-map-marker"></i> Invalidasi</button>
                    </div>
                    <h6 class="m-0 mt-1 btn-verifikasi-pulang d-none">Verifikasi Pulang</h6>
                    <div class="btn-group btn-verifikasi-pulang d-none" role="group" aria-label="Basic example">
                      <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-success btn-absen-validasi-pulang"><i class="fas fa-map-marker-alt"></i> Validasi</button>
                      <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-pulang"><i class="fas fa-map-marker"></i> Invalidasi</button>
                    </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Modal Export Laporan -->
<div class="modal fade" id="modalExportLaporan" tabindex="-1" role="dialog" aria-labelledby="modalExportLaporanLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalExportLaporanLabel">Export Laporan Dinas Luar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formExportLaporan" action="/absen/export/rekamAbsenDL" method="post" target="_blank">
        <div class="modal-body">
          <div class="form-group">
            <label>Filter Tanggal</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
              </div>
              <input type="text" id="tanggalExport" name="tanggal" class="form-control" placeholder="Pilih Rentang Tanggal" autocomplete="off">
            </div>
            <small class="form-text text-muted">Kosongkan jika ingin export hari berjalan.</small>
          </div>
          <div class="form-group">
            <label>Filter Alamat (Kata Kunci)</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
              </div>
              <input type="text" name="filter_lokasi" class="form-control" placeholder="Contoh: Indramayu, Cirebon, Majalengka" autocomplete="off">
            </div>
            <small class="form-text text-muted">Mencari berdasarkan kata kunci pada alamat masuk/pulang. Pisahkan dengan koma (,) untuk mencari lebih dari satu kata kunci.</small>
          </div>
          <div class="form-group">
            <label>Format File</label>
            <select id="formatExport" class="form-control">
              <option value="/absen/export/rekamAbsenDL">Excel (.xlsx)</option>
              <option value="/absen/export/rekamAbsenDLPdf">PDF (.pdf)</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Export</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
$('input[data-id="tanggalSuperUserAbsen"]').on('change', function() {
    var isChecked = $(this).is(':checked');

    if (isChecked) {
        $('.input-tanggal-view').attr('disabled', false);
        $('.input-tanggal-view').daterangepicker({
            startDate: moment(),
            endDate: moment(),
            locale: {
              format: 'YYYY-MM-DD'
            }
          });
    } else {
        $('.input-tanggal-view').val("");
        $('.input-tanggal-view').attr('disabled', true);
    }
});

var cachedPegawaiOptions = null;

  $(document).on('click', '.absenAddEdit', function() {
    function showModalWithOptions(optionsHtml) {
        var formHtml = `
              <form method="post" action="/absen/addDataAbsen" enctype="multipart/form-data">
                <input type="hidden" name="id_absen_post" id="id_absen_post_input" value="">
                <div class="row">
                  <div class="col-12 text-right">
                    <select required name="id_data_pegawai" class="select2" style="width: 100%;">
                      <option value="">--Pilih Pegawai--</option>
                      ${optionsHtml}
                    </select>                    
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-4">
                    <div class="form-check">
                      <input required class="form-check-input" type="radio" name="keterangan" id="InputJamMasuk" value="masuk">
                      <label class="form-check-label" for="InputJamMasuk">Jam Masuk</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input required class="form-check-input" type="radio" name="keterangan" id="InputJamPulang" value="pulang">
                      <label class="form-check-label" for="InputJamPulang">Jam Pulang</label>
                    </div>
                  </div>
                </div>
                <div class="custom-file mt-3">
                  <input type="file" class="custom-file-input" name="foto" id="customFileImage" accept="image/*">
                  <label class="custom-file-label" for="customFileImage">Pilih Gambar</label>
                </div>
                <div class="bootstrap-timepicker">
                  <div class="form-group mt-3">
                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                      <input required name="jam" type="text" class="form-control datetimepicker-input" data-target="#timepicker"/>
                      <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                    <select class="select2" name="alamat" style="width: 100%;">
                        <option value="">Opsional</option>
                    <?php foreach($alamat as $data): ?>
                        <option value="<?= $data['ket_tempat']; ?>"><?= $data['ket_tempat']; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <input type="text" name="longitude" placeholder="lon" class="form-control" />
                    </div>
                    <div class="col-6">
                        <input type="text" name="latitude" placeholder="lat" class="form-control" />
                    </div>
                </div>
                <button type="submit" class="btn btn-success float-right mt-3">
                  <i class="fas fa-share"></i> Simpan
                </button>
              </form>
            `;

          $("#modal-md").find(".modal-body").html(formHtml);
          $("#modal-md").modal('show');
          $(".select2").select2();

          // Inisialisasi Timepicker setelah elemen ditambahkan ke modal
          $('#timepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm' // Format tanggal dan waktu
          });
    }

    if (cachedPegawaiOptions) {
        showModalWithOptions(cachedPegawaiOptions);
    } else {
        $.ajax({
          url: `/absen/getPegawai`,
          method: 'get',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
          },
          success: function(response) {
            if (response.data) {
              cachedPegawaiOptions = response.data.pegawai.map(function(pegawai) {
                return `<option value="${pegawai.id_data_pegawai}">${pegawai.nama}</option>`;
              }).join('');
              
              showModalWithOptions(cachedPegawaiOptions);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
              text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
            })
          },
          complete: function() {
            $("#overlay").hide();
          }
        });
    }
  });

  $(document).on('click', '.btn-edit-absen', function() {
    var idAbsen = $(this).data('id');
    $.ajax({
      url: '/absen/getAbsenById/' + idAbsen,
      method: 'GET',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data) {
          var data = response.data;
          var formHtml = `
              <form method="post" action="/absen/updateDataAbsenAll" enctype="multipart/form-data">
                <input type="hidden" name="id_absen" value="${data.id_absen}">
                <div class="row">
                  <div class="col-12 text-center mb-3">
                    <h5 class="font-weight-bold">Edit Absensi: ${data.nama}</h5>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6 border-right">
                    <h6 class="text-info font-weight-bold text-center border-bottom pb-2">Data Masuk</h6>
                    <div class="form-group">
                      <label>Status Masuk</label>
                      <select name="status_masuk" class="form-control">
                        <option value="">-- Pilih Status --</option>
                        <option value="WFO" ${data.status_masuk == 'WFO' ? 'selected' : ''}>WFO</option>
                        <option value="WFH" ${data.status_masuk == 'WFH' ? 'selected' : ''}>WFH</option>
                        <option value="DL" ${data.status_masuk == 'DL' ? 'selected' : ''}>DL</option>
                        <option value="TL" ${data.status_masuk == 'TL' ? 'selected' : ''}>TL</option>
                        <option value="Cuti" ${data.status_masuk == 'Cuti' ? 'selected' : ''}>Cuti</option>
                        <option value="Sakit" ${data.status_masuk == 'Sakit' ? 'selected' : ''}>Sakit</option>
                        <option value="Izin" ${data.status_masuk == 'Izin' ? 'selected' : ''}>Izin</option>
                        <option value="Alpa" ${data.status_masuk == 'Alpa' ? 'selected' : ''}>Alpa</option>
                        ${(data.status_masuk && !['WFO', 'WFH', 'DL', 'TL', 'Cuti', 'Sakit', 'Izin', 'Alpa'].includes(data.status_masuk)) ? `<option value="${data.status_masuk}" selected>${data.status_masuk}</option>` : ''}
                      </select>
                    </div>
                    <div class="bootstrap-timepicker">
                      <div class="form-group">
                        <label>Jam Masuk</label>
                        <div class="input-group date" id="timepickerMasuk" data-target-input="nearest">
                          <input name="jam_masuk" type="text" class="form-control datetimepicker-input" data-target="#timepickerMasuk" value="${data.jam_masuk ? data.jam_masuk.substring(0, 16) : ''}"/>
                          <div class="input-group-append" data-target="#timepickerMasuk" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Masuk</label>
                        <input type="text" name="alamat_masuk" class="form-control" value="${data.alamat_masuk || ''}" placeholder="Alamat Masuk">
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label>Longitude</label>
                            <input type="text" name="longitude_masuk" value="${data.longitude_masuk || ''}" placeholder="lon" class="form-control" />
                        </div>
                        <div class="col-6">
                            <label>Latitude</label>
                            <input type="text" name="latitude_masuk" value="${data.latitude_masuk || ''}" placeholder="lat" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                      <label>Foto Masuk (Opsional)</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto_masuk" id="fotoMasukFile" accept="image/*">
                        <label class="custom-file-label" for="fotoMasukFile">Pilih Gambar</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <h6 class="text-danger font-weight-bold text-center border-bottom pb-2">Data Pulang</h6>
                    <div class="form-group">
                      <label>Status Pulang</label>
                      <select name="status_pulang" class="form-control">
                        <option value="">-- Pilih Status --</option>
                        <option value="WFO" ${data.status_pulang == 'WFO' ? 'selected' : ''}>WFO</option>
                        <option value="WFH" ${data.status_pulang == 'WFH' ? 'selected' : ''}>WFH</option>
                        <option value="DL" ${data.status_pulang == 'DL' ? 'selected' : ''}>DL</option>
                        <option value="TL" ${data.status_pulang == 'TL' ? 'selected' : ''}>TL</option>
                        <option value="Cuti" ${data.status_pulang == 'Cuti' ? 'selected' : ''}>Cuti</option>
                        <option value="Sakit" ${data.status_pulang == 'Sakit' ? 'selected' : ''}>Sakit</option>
                        <option value="Izin" ${data.status_pulang == 'Izin' ? 'selected' : ''}>Izin</option>
                        <option value="Alpa" ${data.status_pulang == 'Alpa' ? 'selected' : ''}>Alpa</option>
                        ${(data.status_pulang && !['WFO', 'WFH', 'DL', 'TL', 'Cuti', 'Sakit', 'Izin', 'Alpa'].includes(data.status_pulang)) ? `<option value="${data.status_pulang}" selected>${data.status_pulang}</option>` : ''}
                      </select>
                    </div>
                    <div class="bootstrap-timepicker">
                      <div class="form-group">
                        <label>Jam Pulang</label>
                        <div class="input-group date" id="timepickerPulang" data-target-input="nearest">
                          <input name="jam_pulang" type="text" class="form-control datetimepicker-input" data-target="#timepickerPulang" value="${data.jam_pulang ? data.jam_pulang.substring(0, 16) : ''}"/>
                          <div class="input-group-append" data-target="#timepickerPulang" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="far fa-clock"></i></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat Pulang</label>
                        <input type="text" name="alamat_pulang" class="form-control" value="${data.alamat_pulang || ''}" placeholder="Alamat Pulang">
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <label>Longitude</label>
                            <input type="text" name="longitude_pulang" value="${data.longitude_pulang || ''}" placeholder="lon" class="form-control" />
                        </div>
                        <div class="col-6">
                            <label>Latitude</label>
                            <input type="text" name="latitude_pulang" value="${data.latitude_pulang || ''}" placeholder="lat" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                      <label>Foto Pulang (Opsional)</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto_pulang" id="fotoPulangFile" accept="image/*">
                        <label class="custom-file-label" for="fotoPulangFile">Pilih Gambar</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-3 border-top pt-3">
                  <div class="col-12">
                    <div class="form-group">
                      <label>Keterangan Tambahan</label>
                      <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan (Opsional)">${data.keterangan || ''}</textarea>
                    </div>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-2">
                  <i class="fas fa-save"></i> Simpan Perubahan Lengkap
                </button>
              </form>
            `;

          $("#modal-lg").find(".modal-body").html(formHtml);
          $("#modal-lg").modal('show');
          
          $('#timepickerMasuk').datetimepicker({ format: 'YYYY-MM-DD HH:mm' });
          $('#timepickerPulang').datetimepicker({ format: 'YYYY-MM-DD HH:mm' });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        })
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
</script>
<script>
  $(document).on('click', '.absenAddKeterangan', function() {
    $.ajax({
      url: `/absen/getPegawai`,
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data) {
          var options = response.data.pegawai.map(function(pegawai) {
            return `<option value="${pegawai.id_data_pegawai}">${pegawai.nama}</option>`;
          }).join('');

          var formHtml = `
              <form method="post" action="/absen/addKeterangan">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <input required type="date" class="form-control" name="tanggal_masuk" value="">
                    </div>
                  </div>
                  <div class="col-8 text-right">
                    <select required name="id_data_pegawai" class="select2" style="width: 100%;">
                      <option value="">--Pilih Pegawai--</option>
                      ${options}
                    </select>
                    <button type="button" class="absenCekSinadi btn-sm btn btn-info py-0 px-1">Cek Sinadi</button>
                    <button type="button" class="absenCekCuti btn-sm btn btn-info py-0 px-1">Cek Cuti</button>
                    <button type="button" class="absenCekPerizinan btn-sm btn btn-info py-0 px-1">Cek Perizinan</button>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="ct" value="CT">
                      <label class="form-check-label" for="ct">CT</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="cap" value="CAP">
                      <label class="form-check-label" for="cap">CAP</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="cm" value="CM">
                      <label class="form-check-label" for="cm">CM</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="cs" value="CS">
                      <label class="form-check-label" for="cs">CS</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="i" value="I">
                      <label class="form-check-label" for="i">I</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="tb" value="TB">
                      <label class="form-check-label" for="tb">TB</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="dl" value="DL">
                      <label class="form-check-label" for="dl">DL</label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="keterangan" id="tanpa" value="" checked>
                      <label class="form-check-label" for="tanpa">Tanpa Keterangan</label>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-success float-right mt-3">
                  <i class="fas fa-share"></i> Simpan
                </button>
              </form>
            `;
          $("#modal-md").find(".modal-body").html(formHtml);
          $("#modal-md").modal('show');
          $(".select2").select2();
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        })
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
  
  $(document).on('click', '.absenCekSinadi', function() {
    var id = $('select[name="id_data_pegawai"]').val();
    var tgl_masuk = $('input[name="tanggal_masuk"]').val();

    if (id != '' && tgl_masuk != '') {
      var formData = {
        id_data_pegawai: id,
        tgl_masuk: tgl_masuk
      };
    }

    $.ajax({
      url: '/absen/absenCekSinadi',
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            title: response.nama,
            html: response.pesan
            // text: 'Sedang melakukan perjalanan dinas dari tanggal!'
          })
        } else {
          Toast.fire({
            icon: 'error',
            title: response.nama,
            html: response.pesan
          })
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Toast.fire({
          icon: 'error',
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          // text: 'kesalahan pada kode, hubungi pihak terkait!'
        })
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
  $(document).on('click', '.absenCekCuti', function() {
    var id = $('select[name="id_data_pegawai"]').val();
    var tgl_masuk = $('input[name="tanggal_masuk"]').val();

    if (id != '' && tgl_masuk != '') {
      var formData = {
        id_data_pegawai: id,
        tgl_masuk: tgl_masuk
      };
    }

    $.ajax({
      url: '/absen/absenCekCuti',
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            title: response.nama,
            html: response.pesan
            // text: 'Sedang melakukan perjalanan dinas dari tanggal!'
          })
        } else {
          Toast.fire({
            icon: 'error',
            title: response.nama,
            html: response.pesan
          })
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Toast.fire({
          icon: 'error',
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          // text: 'kesalahan pada kode, hubungi pihak terkait!'
        })
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
  $(document).on('click', '.absenCekPerizinan', function() {
    var id = $('select[name="id_data_pegawai"]').val();
    var tgl_masuk = $('input[name="tanggal_masuk"]').val();

    if (id != '' && tgl_masuk != '') {
      var formData = {
        id_data_pegawai: id,
        tgl_masuk: tgl_masuk
      };
    }

    $.ajax({
      url: '/absen/absenCekPerizinan',
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            title: response.nama,
            html: response.pesan
            // text: 'Sedang melakukan perjalanan dinas dari tanggal!'
          })
        } else {
          Toast.fire({
            icon: 'error',
            title: response.nama,
            html: response.pesan
          })
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Toast.fire({
          icon: 'error',
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          // text: 'kesalahan pada kode, hubungi pihak terkait!'
        })
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
  $(document).ready(function() {
    $('#modalExportLaporan').on('shown.bs.modal', function () {
      if (!$('#tanggalExport').data('daterangepicker')) {
          $('#tanggalExport').daterangepicker({
              autoUpdateInput: false,
              locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
              }
          });

          $('#tanggalExport').on('apply.daterangepicker', function(ev, picker) {
              $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
          });

          $('#tanggalExport').on('cancel.daterangepicker', function(ev, picker) {
              $(this).val('');
          });
      }
    });

    $('#formExportLaporan').on('submit', function(e) {
        var formatUrl = $('#formatExport').val();
        $(this).attr('action', formatUrl);
    });
  });
</script>

<script>
  $(document).on('change', '.input-tanggal-view', function() {
    $(".dataAbsenHarian").html(`<div class="text-center"><i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span></div>`);
    
    var value = $(this).val();
    
    
    setTimeout(() => {
      if (value != "") {
        var formData = {
          tanggal: value
        };
    
        $.ajax({
            url: '/absen/get_absen_dinas_luar',
            data: formData,
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
            $(".dataAbsenHarian").html(`<div class="text-center"><i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span></div>`)
          },
          success: function(response) {
            if (response.data) {
                // console.log(response.data);
              setTimeout(() => {
                $(".dataAbsenHarian").html(response.data)
              }, 1500);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
              text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
            })
          }, complete: function() {
              $("#overlay").hide();
          }
        });
      }
    }, 500);
  });
</script>

<!-- ============================================================================================================================================================================================== -->

<script>
    $(document).on("click", ".showRekamAbsen", function(){
        var id = $(this).data('id');
        var $modal = $("#modal-lg");

        $.ajax({
            url: `/absen/getRekamAbsen/${id}`,
            method: 'get',
            dataType: 'json',
            beforeSend: function() {
                $("#overlay").show();
            },
            success: function(response) {
              if (response.data) {
                  $modal.find(".modal-body").html(response.data);
                  $modal.modal('show');
              }
            },error: function(xhr, ajaxOptions, thrownError) {
              Swal.fire({
                  'text' : xhr.status + "\n" + xhr.responseText + "\n" + thrownError
              })
            },
            complete: function() {
                $("#overlay").hide();
            }
        });
    });

    $(document).on('click', '.setNewRekamAbsen', function() {
      var container = $(this).closest('.media');
        
      var formData = {
        id_absen: container.find(".d-none").text(),
        rekam: container.find(".setAbsenRekam").text(),
        alamat: container.find(".setAbsenAlamat").text(),
        longitude: container.find(".setAbsenLongitude").text(),
        latitude: container.find(".setAbsenLatitude").text(),
        jam: container.find(".setAbsenJam").text(),
        image: container.find(".setAbsenImage").data("src"),
      };
  
      $.ajax({
        url: '/absen/setNewAbsen',
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $("#overlay").show();
        },
        success: function(response) {
          if (response.data) {
              Swal.fire({
                icon: "success",
                title: "Data " + container.find(".setAbsenRekam").text() + " Berhasil Diperbaharui!",
                showConfirmButton: false,
                timer: 1500
              }).then(() => {
                  window.location.reload();
              });
          } else {
              Swal.fire({
                icon: "error",
                title: "Data " + container.find(".setAbsenRekam").text() + " Gagal Diperbaharui!",
                showConfirmButton: false,
                timer: 1500
              });
          }
        },
        complete: function() {
          $("#overlay").hide();
        }
      });
    });

        
    // Data Zona Target (Multiple Polygons)
    var targetPolygons = [];
    var checkTargetPolygons = false;

    getTargetPolygons();

    function getTargetPolygons() {
      $.ajax({
        url: '/getTargetAreaAbsen',
        dataType: 'json',
        success: function(response) {
          if (response.data) {
            // response.data[]['ket_tempat']
            response.data.forEach(function(item) {
              // Menghapus whitespace dan newline dari string poligon
              const cleanedPolygon = item.polygon
                .replace(/\r\n|\r|\n/g, '')
                .replace(/\s+/g, ' ')
                .trim();

              // Mengonversi string poligon menjadi array
              try {
                const polygonArray = JSON.parse(cleanedPolygon);
                targetPolygons.push({
                  polygon: polygonArray,
                  ket_tempat: item.ket_tempat // Simpan ket_tempat bersama dengan poligon
                });
              } catch (e) {
                console.error('Error parsing polygon data:', e);
              }
            });
          }
        }, complete: function() {
            checkTargetPolygons = true;
        }
      });
    }
      
</script>
<script>
    // Fungsi untuk memeriksa apakah titik berada di dalam salah satu poligon
    function getLocationDescription(point, polygons) {
      const x = point[0];
      const y = point[1];

      // Function to check if a point is in a single polygon
      function isInSinglePolygon(polygon) {
        let inside = false;
        for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
          const xi = polygon[i][0],
            yi = polygon[i][1];
          const xj = polygon[j][0],
            yj = polygon[j][1];

          const intersect = ((yi > y) !== (yj > y)) &&
            (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
          if (intersect) inside = !inside;
        }
        return inside;
      }

      // Check if the point is in any of the polygons
      for (let i = 0; i < polygons.length; i++) {
        if (isInSinglePolygon(polygons[i].polygon)) {
          return polygons[i].ket_tempat; // Return the location description
        }
      }
      return 'Anda berada diluar wilayah absen'; // Default message if no match
    }
    
    function lazyLoad() {
        // $('.class-absen-alamat-pulang').each(function() {});
        $('.lazy').each(function() {
            const $img = $(this);
    
            // Check if the image has already been processed
            if ($img.data('loaded')) {
                return; // Skip this image if it has already been loaded
            }
    
            if ($img.offset().top < $(window).scrollTop() + $(window).height()) {
                var formData = {
                    id_absen: $img.data('idabsen')
                };
    
                $.ajax({
                    url: "/absen/getImage",
                    data: formData,
                    method: 'post',
                    dataType: 'json',
                    beforeSend: function() {
                        console.log("Loading!");
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Memuat gambar...',
                            showConfirmButton: false,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        }).then((result) => {
                            // Jika needed, do something after the toast is shown.
                        });
                    },
                    success: function(response) {
                        if (response.data) {
                            console.log("Berhasil!");
                            console.log(response.data);
                            if ($img.data('waktu') == 'pulang') {
                                $img.attr('src', response.data['image_pulang_1']);
                                $img.data('src', response.data['image_pulang_1']);
                            } else {
                                $img.attr('src', response.data['image_masuk_1']);
                                $img.data('src', response.data['image_masuk_1']);
                            }
                            $img.removeClass('lazy');
                            $img.data('loaded', true); // Mark this image as loaded
                        } else {
                            console.log("Gagal!");
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log("Error!");
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal memuat gambar'
                        })
                    },
                    complete: function() {
                        setTimeout(() => {
                          Swal.close();
                        }, 1000);
                    }
                });
            }
        });
      }
    
      // Initial lazy load
      //   lazyLoad();
    
      // Lazy load on scroll
      $('#tableWithoutExporting tbody').on('mouseenter', 'tr', function() {
        // Menangkap semua gambar lazy dalam baris yang dihover
        var $lazyImages = $(this).find('.lazy');
        lazyLoad($lazyImages);
      });
      
      $(document).ready(function() {
        function processElements() {
            $('.class-absen-alamat-masuk').each(function() {
                const $location_masuk = $(this);
                if ($location_masuk.data('loaded')) {
                    return; // Skip if already processed
                }
                if ($location_masuk.offset().top < $(window).scrollTop() + $(window).height()) {
                    var longitude = $location_masuk.data('longitude');
                    var latitude = $location_masuk.data('latitude');
                    var validation = $location_masuk.data('validation');
                    var jam = $location_masuk.data('jam');
                    
                    if(longitude != "" && latitude != "") {
                        const data = [parseFloat(longitude), parseFloat(latitude)];
                        const isDataInside = getLocationDescription(data, targetPolygons);
                        
                        if (isDataInside == "Anda berada diluar wilayah absen") {
                            if(validation == "Validasi") {
                                $location_masuk.html("<strong class='text-success'>Absen Terverifikasi <i class='fas fa-check-circle'></i></strong> <br/>" + $location_masuk.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                                $location_masuk.addClass('table-warning').removeClass('table-success table-danger');
                                $location_masuk.data('loaded', true);
                            } else {
                                $location_masuk.html("<strong>Diluar Zona Absen</strong> <br/>" + $location_masuk.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                                $location_masuk.addClass('table-danger').removeClass('table-success');
                                $location_masuk.data('loaded', true);
                                if(validation == "Default") {
                                    $location_masuk.parent().find('.btn-verifikasi-masuk').removeClass('d-none');
                                }
                            }
                        } else {
                            $location_masuk.html(isDataInside + "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                            $location_masuk.removeClass('table-danger').addClass('table-success');
                            $location_masuk.data('loaded', true);
                            
                        }
                    } else {
                        if(jam != null && jam != "") {
                            $location_masuk.addClass('table-success').removeClass('table-danger');
                            $location_masuk.text("Data Sesuai");
                        } else {
                            $location_masuk.addClass('table-danger').removeClass('table-warning');
                        }
                        $location_masuk.data('loaded', true);
                    }
    
                }
            });
            $('.class-absen-alamat-pulang').each(function() {
              const $location_pulang = $(this);
              if ($location_pulang.data('loaded')) {
                  return; // Skip if already processed
              }
              if ($location_pulang.offset().top < $(window).scrollTop() + $(window).height()) {
                  var longitude = $location_pulang.data('longitude');
                  var latitude = $location_pulang.data('latitude');
                  var validation = $location_pulang.data('validation');
                  var jam = $location_pulang.data('jam');
                  
                  if(longitude != "" && latitude != "") {
                      const data = [parseFloat(longitude), parseFloat(latitude)];
                      const isDataInside = getLocationDescription(data, targetPolygons);
                      
                      if (isDataInside == "Anda berada diluar wilayah absen") {
                          if(validation == "Valid") {
                              $location_pulang.html("<strong class='text-success'>Absen Terverifikasi <i class='fas fa-check-circle'></i></strong> <br/>" + $location_pulang.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                              $location_pulang.addClass('table-danger').removeClass('table-warning');
                              $location_pulang.data('loaded', true);
                          } else {
                              $location_pulang.html("<strong>Diluar Zona Absen</strong> <br/>" + $location_pulang.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                              $location_pulang.addClass('table-danger').removeClass('table-warning');
                              $location_pulang.data('loaded', true);
                              if(validation == "Default") {
                                  $location_pulang.parent().find('.btn-verifikasi-pulang').removeClass('d-none');
                              }
                          }
                      } else {
                          $location_pulang.html(isDataInside + "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                          $location_pulang.removeClass('table-danger').addClass('table-success');
                          $location_pulang.data('loaded', true);
                          
                      }
                  } else {
                    if(jam != null && jam != "") {
                        $location_pulang.addClass('table-success').removeClass('table-danger');
                        $location_pulang.text("Data Sesuai");
                    } else {
                        $location_pulang.addClass('table-danger').removeClass('table-warning');
                    }
                    $location_pulang.data('loaded', true);
                  }
            
              }
          });
        }
        
        
        $(window).on('scroll, mouseenter', function() {
            if(checkTargetPolygons){
                processElements();
            }
        });
        
    });

    
</script>

<script>
  $(document).on("click", ".btn-absen-validasi-masuk", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-masuk');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Validasi alamat masuk?",
      showCancelButton: true,
      confirmButtonText: "Valid",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = {
            keterangan_validasi_masuk: ""
        };
        $.ajax({
          url: '/absen/validasiMasuk/1/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });
              statusElement.removeClass('table-danger');
              statusElement.addClass('table-warning');
              statusElement.find('a').text('Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });

  $(document).on("click", ".btn-absen-invalidasi-masuk", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-masuk');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Invalidasi alamat masuk?",
      input: "text",
      showCancelButton: true,
      confirmButtonText: "Invalid",
      cancelButtonText: "Batal",
      confirmButtonColor: "#d33",
    }).then((result) => {
        var formData = {
            keterangan_validasi_masuk: result.value
        };
      if (result.isConfirmed) {
        $.ajax({
          url: '/absen/validasiMasuk/0/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });
              statusElement.removeClass('table-success');
              statusElement.addClass('table-danger');
              statusElement.find('a').text('Tidak Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });

  $(document).on("click", ".btn-absen-validasi-pulang", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-pulang');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Validasi alamat pulang?",
      showCancelButton: true,
      confirmButtonText: "Valid",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = {
            keterangan_validasi_pulang: ""
        };
        $.ajax({
          url: '/absen/validasiPulang/1/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });
              statusElement.removeClass('table-danger');
              statusElement.addClass('table-warning');
              statusElement.find('a').text('Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });

  $(document).on("click", ".btn-absen-invalidasi-pulang", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-pulang');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Invalidasi alamat pulang?",
      input: "text",
      showCancelButton: true,
      confirmButtonText: "Invalid",
      cancelButtonText: "Batal",
      confirmButtonColor: "#d33",
      showLoaderOnConfirm: true,
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = {
            keterangan_validasi_pulang: result.value
        };
        $.ajax({
          url: '/absen/validasiPulang/0/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });

              statusElement.removeClass('table-success');
              statusElement.addClass('table-danger');
              statusElement.find('a').text('Tidak Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });

  $(document).on('click', '.btn-delete-absen', function() {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Data absen ini akan dihapus permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/absen/deleteAbsen/' + id,
          method: 'DELETE',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
          },
          success: function(response) {
            if (response.data == 'sukses') {
              Swal.fire('Terhapus!', 'Data absen berhasil dihapus.', 'success');
              setTimeout(() => { location.reload(); }, 1000);
            } else {
              Swal.fire('Gagal!', 'Data gagal dihapus.', 'error');
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire('Error!', xhr.responseText, 'error');
          },
          complete: function() {
            $("#overlay").hide();
          }
        });
      }
    });
  });
</script>

<?= $this->endSection(); ?>