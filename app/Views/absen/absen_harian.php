<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<style>
  .absen-thumb {
    width: auto;
    height: auto;
    max-width: 84px;
    max-height: 84px;
    object-fit: contain;
    border-radius: 4px;
    cursor: pointer;
    background: #f4f6f9;
    transition: transform 0.15s ease;
  }
  .absen-thumb:hover {
    transform: scale(1.04);
  }
</style>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mt-1 font-weight-bold">Daftar Hadir Harian</h3>

        <div class="card-tools d-flex align-items-center" style="gap: 10px;">
          <?php if (!empty($isAdmin)) : ?>
            <button type="button" class="btn btn-warning btn-sm absenAddEdit">
              <i class="fas fa-edit"></i> Perbaharui Data
            </button>
          <?php endif; ?>

          <?php if (!empty($superUser)) : ?>
            <button type="button" class="btn btn-info btn-sm absenAddKeterangan">
              <i class="fas fa-info-circle"></i> Tambahkan Keterangan
            </button>

            <form id="myForm" action="" method="post" class="m-0 d-flex align-items-center" style="gap: 10px;">
              <div class="input-group input-group-sm m-0">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <input type="checkbox" data-id="tanggalSuperUserAbsen">
                  </span>
                </div>
                <input type="text" disabled name="tanggal" class="form-control" placeholder="Akses Super User" autocomplete="off" style="min-width: 200px;" />
              </div>

              <button type="submit" data-url="/absen/export/rekamAbsen" class="btn btn-success btn-sm text-nowrap">
                <i class="fas fa-file-excel"></i> Rekam Absensi
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body dataAbsenHarian">
        <table id="tableWithoutExporting" class="table table-striped table-sm table-bordered">
          <thead>
            <tr>
              <th style="width: 2%;">No</th>
              <th>Nama</th>
              <th>Status Masuk</th>
              <th style="width: 18%;">Alamat Masuk</th>
              <th>Jam Masuk</th>
              <th style="width: 7%;">Foto Masuk</th>
              <th>Status Pulang</th>
              <th style="width: 18%;">Alamat Pulang</th>
              <th>Jam Pulang</th>
              <th style="width: 7%;">Foto Pulang</th>
              <th>Jam Kerja</th>
              <th>Rekam Absen</th>
              <th style="width: 10%;">Aksi</th>
            </tr>
          </thead>
          <tbody class="addDataAbsenHarian">
            <?php
            $nomor = 1;
            $canEditDel = !empty($isAdmin) || !empty($aksesEditDel);
            foreach ($absen as $data) :
              $jamMasuk = $data['jam_masuk'];
              $jamPulang = $data['jam_pulang'];

              $statusAbsensi = statusAbsensi(
                $jamMasuk,
                $jamPulang,
                $data['aturan_jam_masuk'] ?? null,
                $data['aturan_jam_pulang'] ?? null,
                $data['toleransi_keterlambatan'] ?? null
              );

              if ($data['status_pulang'] == null) {
                $statusAbsen = '';
              } elseif ($data['status_pulang'] != $data['status_masuk']) {
                $statusAbsen = 'style="background-color: #f5c6cb !important;"';
              } else {
                $statusAbsen = '';
              }

              $evalMasuk = $data['eval_masuk'] ?? ['cell_class' => 'table-danger', 'html' => '', 'show_verifikasi' => false];
              $evalPulang = $data['eval_pulang'] ?? ['cell_class' => 'table-danger', 'html' => '', 'show_verifikasi' => false];

              $jamMasukClass = 'table-danger';
              if (!empty($jamMasuk)) {
                if ($statusAbsensi['masuk'] === 'kuning') $jamMasukClass = 'table-warning';
                elseif ($statusAbsensi['masuk'] === 'hijau') $jamMasukClass = 'table-success';
              }

              $jamPulangClass = 'table-danger';
              if (!empty($jamPulang)) {
                if ($statusAbsensi['pulang'] === 'kuning') $jamPulangClass = 'table-warning';
                elseif ($statusAbsensi['pulang'] === 'hijau') $jamPulangClass = 'table-success';
              }

              $idAbsen = (int) ($data['id_absen'] ?? 0);
              $ver = !empty($data['updated_at']) ? (int) strtotime((string) $data['updated_at']) : 0;
              $verQ = $ver > 0 ? '?v=' . $ver : '';
            ?>
              <tr>
                <td><?= $nomor++; ?></td>
                <td><?= esc($data['nama']); ?></td>
                <td><?= esc($data['status_masuk'] ?? ''); ?></td>
                <td <?= $statusAbsen; ?>
                    class="class-absen-alamat-masuk <?= $evalMasuk['cell_class']; ?>"
                    data-alamat="<?= esc($data['alamat_masuk'] ?? '', 'attr'); ?>"
                    data-longitude="<?= esc($data['longitude_masuk'] ?? '', 'attr'); ?>"
                    data-latitude="<?= esc($data['latitude_masuk'] ?? '', 'attr'); ?>">
                  <?= $evalMasuk['html']; ?>
                </td>
                <td <?= $statusAbsen; ?> class="<?= $jamMasukClass; ?>">
                  <?= esc($jamMasuk ?? ''); ?>
                </td>
                <td <?= $statusAbsen; ?> class="text-center">
                  <?php if (!empty($jamMasuk)) : ?>
                    <?php if (empty($data['keterangan'])) : ?>
                      <?php if ($idAbsen > 0 && !empty($data['has_foto_masuk'])) : ?>
                        <img src="/absen/foto/<?= $idAbsen; ?>/masuk<?= $verQ; ?>"
                             data-idabsen="<?= $idAbsen; ?>"
                             data-ket="masuk"
                             <?= !empty($data['has_foto_masuk_2']) ? 'data-second-src="/absen/foto/' . $idAbsen . '/masuk2' . $verQ . '"' : ''; ?>
                             loading="lazy"
                             decoding="async"
                             class="galleryAction absen-thumb"
                             alt="Foto Masuk"
                             onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';" />
                      <?php endif; ?>
                    <?php else : ?>
                      <h4><?= esc($data['keterangan']); ?></h4>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?>><?= esc($data['status_pulang'] ?? ''); ?></td>
                <td <?= $statusAbsen; ?>
                    class="class-absen-alamat-pulang <?= $evalPulang['cell_class']; ?>"
                    data-alamat="<?= esc($data['alamat_pulang'] ?? '', 'attr'); ?>"
                    data-longitude="<?= esc($data['longitude_pulang'] ?? '', 'attr'); ?>"
                    data-latitude="<?= esc($data['latitude_pulang'] ?? '', 'attr'); ?>">
                  <?= $evalPulang['html']; ?>
                </td>
                <td <?= $statusAbsen; ?> class="<?= $jamPulangClass; ?>">
                  <?= esc($jamPulang ?? ''); ?>
                </td>
                <td <?= $statusAbsen; ?> class="text-center">
                  <?php if (!empty($jamPulang)) : ?>
                    <?php if (empty($data['keterangan'])) : ?>
                      <?php if ($idAbsen > 0 && !empty($data['has_foto_pulang'])) : ?>
                        <img src="/absen/foto/<?= $idAbsen; ?>/pulang<?= $verQ; ?>"
                             data-idabsen="<?= $idAbsen; ?>"
                             data-ket="pulang"
                             <?= !empty($data['has_foto_pulang_2']) ? 'data-second-src="/absen/foto/' . $idAbsen . '/pulang2' . $verQ . '"' : ''; ?>
                             loading="lazy"
                             decoding="async"
                             class="galleryAction absen-thumb"
                             alt="Foto Pulang"
                             onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';" />
                      <?php endif; ?>
                    <?php else : ?>
                      <h4><?= esc($data['keterangan']); ?></h4>
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
                <td <?= $statusAbsen; ?>>
                  <?= esc($data['jam_kerja_text'] ?? '-'); ?>
                </td>
                <td>
                  <?php if ($idAbsen > 0 && (int) ($data['jumlah_array'] ?? 0) > 0) : ?>
                    <button type="button" class="btn btn-sm btn-info showRekamAbsen" data-id="<?= $idAbsen; ?>"> Lihat </button><br />
                    <span><?= (int) $data['jumlah_array']; ?> rekaman tersedia!</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($idAbsen > 0 && $canEditDel) : ?>
                    <div class="btn-group mb-2" role="group">
                      <button type="button" data-id="<?= $idAbsen; ?>" data-idpegawai="<?= (int) $data['dp_id_data_pegawai']; ?>" class="btn btn-sm btn-warning btn-edit-absen"><i class="fas fa-edit"></i> Edit</button>
                      <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-danger btn-delete-absen"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                  <?php endif; ?>
                  <?php if ($idAbsen > 0 && !empty($evalMasuk['show_verifikasi'])) : ?>
                    <h6 class="m-0 btn-verifikasi-masuk">Verifikasi Masuk</h6>
                    <div class="btn-group btn-verifikasi-masuk" role="group" aria-label="Verifikasi Masuk">
                      <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-success btn-absen-validasi-masuk"><i class="fas fa-map-marker-alt"></i> Validasi</button>
                      <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-masuk"><i class="fas fa-map-marker"></i> Invalidasi</button>
                    </div>
                  <?php endif; ?>
                  <?php if ($idAbsen > 0 && !empty($evalPulang['show_verifikasi'])) : ?>
                    <h6 class="m-0 mt-1 btn-verifikasi-pulang">Verifikasi Pulang</h6>
                    <div class="btn-group btn-verifikasi-pulang" role="group" aria-label="Verifikasi Pulang">
                      <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-success btn-absen-validasi-pulang"><i class="fas fa-map-marker-alt"></i> Validasi</button>
                      <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-pulang"><i class="fas fa-map-marker"></i> Invalidasi</button>
                    </div>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<script>
(function() {
  var cachedPegawaiOptions = null;
  var currentAbsenXhr = null;

  function escapeHtml(str) {
    return String(str == null ? '' : str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function loadPegawaiOptions(onReady) {
    if (cachedPegawaiOptions !== null) {
      onReady(cachedPegawaiOptions);
      return;
    }
    $.ajax({
      url: '/absen/getPegawai',
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data && Array.isArray(response.data.pegawai)) {
          cachedPegawaiOptions = response.data.pegawai.map(function(pegawai) {
            return '<option value="' + escapeHtml(pegawai.id_data_pegawai) + '">' + escapeHtml(pegawai.nama) + '</option>';
          }).join('');
          onReady(cachedPegawaiOptions);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          icon: 'error',
          text: xhr.status + "\n" + (xhr.responseText || thrownError)
        });
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  }

  function initAbsenDataTable() {
    var $tbl = $("#tableWithoutExporting");
    if (!$tbl.length || !$.fn.DataTable) return;
    if ($.fn.DataTable.isDataTable($tbl)) {
      $tbl.DataTable().destroy();
    }
    var responsiveOpt = (typeof DataTable !== 'undefined' && DataTable.Responsive && DataTable.Responsive.renderer)
      ? { details: { renderer: DataTable.Responsive.renderer.listHiddenNodes() } }
      : true;

    $tbl.DataTable({
      deferRender: true,
      lengthChange: true,
      autoWidth: false,
      pageLength: 10,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      responsive: responsiveOpt,
      buttons: ["copy", "colvis"]
    }).buttons().container().appendTo('#tableWithoutExporting_wrapper .col-md-6:eq(0)');
  }

  function fetchAbsenHarian(tanggalVal) {
    tanggalVal = $.trim(tanggalVal || '');
    if (!tanggalVal) return;

    if (currentAbsenXhr && currentAbsenXhr.readyState !== 4) {
      currentAbsenXhr.abort();
    }

    currentAbsenXhr = $.ajax({
      url: '/absen/get_absen_harian',
      data: { tanggal: tanggalVal },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(".dataAbsenHarian").html(
          '<div class="text-center py-4"><i class="fas fa-sync-alt fa-spin mr-1"></i> <span class="text-bold">Memuat data absensi...</span></div>'
        );
      },
      success: function(response) {
        if (response && response.data) {
          $(".dataAbsenHarian").html(response.data);
          initAbsenDataTable();
        }
      },
      error: function(xhr, status, thrownError) {
        if (status === 'abort') return;
        $(".dataAbsenHarian").html(
          '<div class="alert alert-danger mb-0">Gagal memuat data absensi. Silakan coba pilih tanggal kembali.</div>'
        );
        Swal.fire({
          icon: 'error',
          title: 'Gagal Memuat Data',
          text: xhr.status + ' ' + (thrownError || '')
        });
      },
      complete: function() {
        currentAbsenXhr = null;
      }
    });
  }

  // Toggle Super User date range picker
  $(document).on('change', 'input[data-id="tanggalSuperUserAbsen"]', function() {
    var isChecked = $(this).is(':checked');
    var $tglInput = $('input[name="tanggal"]');

    if (isChecked) {
      $tglInput.prop('disabled', false);
      $tglInput.daterangepicker({
        startDate: moment(),
        endDate: moment(),
        locale: {
          format: 'YYYY-MM-DD'
        }
      });
    } else {
      if (currentAbsenXhr && currentAbsenXhr.readyState !== 4) {
        currentAbsenXhr.abort();
      }
      if ($tglInput.data('daterangepicker')) {
        $tglInput.data('daterangepicker').remove();
      }
      $tglInput.val('').prop('disabled', true);
    }
  });

  // Muat data absensi langsung saat rentang tanggal berubah (tanpa delay buatan)
  $(document).on('change', 'input[name="tanggal"]', function() {
    var value = $.trim($(this).val() || '');
    if (value !== '') {
      fetchAbsenHarian(value);
    }
  });

  // Update label file upload di modal
  $(document).on('change', '.custom-file-input', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').text(fileName || 'Pilih Gambar');
  });

  // Modal Perbaharui Data (Admin)
  $(document).on('click', '.absenAddEdit', function() {
    loadPegawaiOptions(function(optionsHtml) {
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
              <?php foreach ($alamat as $al) : ?>
                <option value="<?= esc($al['ket_tempat'], 'attr'); ?>"><?= esc($al['ket_tempat']); ?></option>
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
      $("#modal-md .select2").select2();
      $('#timepicker').datetimepicker({ format: 'YYYY-MM-DD HH:mm' });
    });
  });

  // Modal Tambahkan Keterangan (Super User) — memakai cache pegawai yang sama
  $(document).on('click', '.absenAddKeterangan', function() {
    loadPegawaiOptions(function(optionsHtml) {
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
                ${optionsHtml}
              </select>
              <button type="button" class="absenCekSinadi btn-sm btn btn-info py-0 px-1 mt-1">Cek Sinadi</button>
              <button type="button" class="absenCekCuti btn-sm btn btn-info py-0 px-1 mt-1">Cek Cuti</button>
              <button type="button" class="absenCekPerizinan btn-sm btn btn-info py-0 px-1 mt-1">Cek Perizinan</button>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="ct" value="CT"><label class="form-check-label" for="ct">CT</label></div></div>
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="cap" value="CAP"><label class="form-check-label" for="cap">CAP</label></div></div>
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="cm" value="CM"><label class="form-check-label" for="cm">CM</label></div></div>
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="cs" value="CS"><label class="form-check-label" for="cs">CS</label></div></div>
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="i" value="I"><label class="form-check-label" for="i">I</label></div></div>
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="tb" value="TB"><label class="form-check-label" for="tb">TB</label></div></div>
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="dl" value="DL"><label class="form-check-label" for="dl">DL</label></div></div>
            <div class="col-4"><div class="form-check"><input class="form-check-input" type="radio" name="keterangan" id="tanpa" value="" checked><label class="form-check-label" for="tanpa">Tanpa Keterangan</label></div></div>
          </div>
          <button type="submit" class="btn btn-success float-right mt-3">
            <i class="fas fa-share"></i> Simpan
          </button>
        </form>
      `;

      $("#modal-md").find(".modal-body").html(formHtml);
      $("#modal-md").modal('show');
      $("#modal-md .select2").select2();
    });
  });

  // Helper Cek Sinadi / Cuti / Perizinan
  function runAbsenCek(url) {
    var id = $('#modal-md select[name="id_data_pegawai"]').val();
    var tgl_masuk = $('#modal-md input[name="tanggal_masuk"]').val();

    if (!id || !tgl_masuk) {
      Toast.fire({
        icon: 'warning',
        title: 'Pilih tanggal dan pegawai terlebih dahulu!'
      });
      return;
    }

    $.ajax({
      url: url,
      data: { id_data_pegawai: id, tgl_masuk: tgl_masuk },
      method: 'post',
      dataType: 'json',
      beforeSend: function() { $("#overlay").show(); },
      success: function(response) {
        Toast.fire({
          icon: response.data === 'sukses' ? 'success' : 'error',
          title: response.nama || '',
          html: response.pesan || ''
        });
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Toast.fire({
          icon: 'error',
          text: xhr.status + "\n" + (xhr.responseText || thrownError)
        });
      },
      complete: function() { $("#overlay").hide(); }
    });
  }

  $(document).on('click', '.absenCekSinadi', function() { runAbsenCek('/absen/absenCekSinadi'); });
  $(document).on('click', '.absenCekCuti', function() { runAbsenCek('/absen/absenCekCuti'); });
  $(document).on('click', '.absenCekPerizinan', function() { runAbsenCek('/absen/absenCekPerizinan'); });

  // Edit Absensi lengkap
  $(document).on('click', '.btn-edit-absen', function() {
    var idAbsen = $(this).data('id');
    $.ajax({
      url: '/absen/getAbsenById/' + idAbsen,
      method: 'GET',
      dataType: 'json',
      beforeSend: function() { $("#overlay").show(); },
      success: function(response) {
        if (!response.data) return;
        var data = response.data;
        var stdStatuses = ['WFO', 'WFH', 'DL', 'TL', 'Cuti', 'Sakit', 'Izin', 'Alpa'];
        var statusOpts = function(val) {
          var html = '<option value="">-- Pilih Status --</option>';
          stdStatuses.forEach(function(s) {
            html += '<option value="' + s + '"' + (val === s ? ' selected' : '') + '>' + s + '</option>';
          });
          if (val && stdStatuses.indexOf(val) === -1) {
            html += '<option value="' + escapeHtml(val) + '" selected>' + escapeHtml(val) + '</option>';
          }
          return html;
        };

        var formHtml = `
          <form method="post" action="/absen/updateDataAbsenAll" enctype="multipart/form-data">
            <input type="hidden" name="id_absen" value="${escapeHtml(data.id_absen)}">
            <div class="row">
              <div class="col-12 text-center mb-3">
                <h5 class="font-weight-bold">Edit Absensi: ${escapeHtml(data.nama)}</h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 border-right">
                <h6 class="text-info font-weight-bold text-center border-bottom pb-2">Data Masuk</h6>
                <div class="form-group">
                  <label>Status Masuk</label>
                  <select name="status_masuk" class="form-control">${statusOpts(data.status_masuk)}</select>
                </div>
                <div class="bootstrap-timepicker">
                  <div class="form-group">
                    <label>Jam Masuk</label>
                    <div class="input-group date" id="timepickerMasuk" data-target-input="nearest">
                      <input name="jam_masuk" type="text" class="form-control datetimepicker-input" data-target="#timepickerMasuk" value="${escapeHtml(data.jam_masuk ? data.jam_masuk.substring(0, 16) : '')}"/>
                      <div class="input-group-append" data-target="#timepickerMasuk" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Alamat Masuk</label>
                  <input type="text" name="alamat_masuk" class="form-control" value="${escapeHtml(data.alamat_masuk || '')}" placeholder="Alamat Masuk">
                </div>
                <div class="form-group row">
                  <div class="col-6">
                    <label>Longitude</label>
                    <input type="text" name="longitude_masuk" value="${escapeHtml(data.longitude_masuk || '')}" placeholder="lon" class="form-control" />
                  </div>
                  <div class="col-6">
                    <label>Latitude</label>
                    <input type="text" name="latitude_masuk" value="${escapeHtml(data.latitude_masuk || '')}" placeholder="lat" class="form-control" />
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
                  <select name="status_pulang" class="form-control">${statusOpts(data.status_pulang)}</select>
                </div>
                <div class="bootstrap-timepicker">
                  <div class="form-group">
                    <label>Jam Pulang</label>
                    <div class="input-group date" id="timepickerPulang" data-target-input="nearest">
                      <input name="jam_pulang" type="text" class="form-control datetimepicker-input" data-target="#timepickerPulang" value="${escapeHtml(data.jam_pulang ? data.jam_pulang.substring(0, 16) : '')}"/>
                      <div class="input-group-append" data-target="#timepickerPulang" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Alamat Pulang</label>
                  <input type="text" name="alamat_pulang" class="form-control" value="${escapeHtml(data.alamat_pulang || '')}" placeholder="Alamat Pulang">
                </div>
                <div class="form-group row">
                  <div class="col-6">
                    <label>Longitude</label>
                    <input type="text" name="longitude_pulang" value="${escapeHtml(data.longitude_pulang || '')}" placeholder="lon" class="form-control" />
                  </div>
                  <div class="col-6">
                    <label>Latitude</label>
                    <input type="text" name="latitude_pulang" value="${escapeHtml(data.latitude_pulang || '')}" placeholder="lat" class="form-control" />
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
                  <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan (Opsional)">${escapeHtml(data.keterangan || '')}</textarea>
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
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({ icon: 'error', text: xhr.status + "\n" + (xhr.responseText || thrownError) });
      },
      complete: function() { $("#overlay").hide(); }
    });
  });

  // Hapus Absensi
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
    }).then(function(result) {
      if (!result.isConfirmed) return;
      $.ajax({
        url: '/absen/deleteAbsen/' + id,
        method: 'DELETE',
        dataType: 'json',
        beforeSend: function() { $("#overlay").show(); },
        success: function(response) {
          if (response.data === 'sukses') {
            Swal.fire({ title: 'Terhapus!', text: 'Data absen berhasil dihapus.', icon: 'success', timer: 900, showConfirmButton: false });
            var activeDate = $.trim($('input[name="tanggal"]').val() || '');
            if (activeDate !== '' && !$('input[name="tanggal"]').prop('disabled')) {
              fetchAbsenHarian(activeDate);
            } else {
              setTimeout(function() { location.reload(); }, 900);
            }
          } else {
            Swal.fire('Gagal!', 'Data gagal dihapus.', 'error');
          }
        },
        error: function(xhr) {
          Swal.fire('Error!', xhr.responseText || 'Terjadi kesalahan', 'error');
        },
        complete: function() { $("#overlay").hide(); }
      });
    });
  });

  // Lihat Rekam Absen
  $(document).on("click", ".showRekamAbsen", function() {
    var id = $(this).data('id');
    var $modal = $("#modal-lg");

    $.ajax({
      url: '/absen/getRekamAbsen/' + id,
      method: 'get',
      dataType: 'json',
      beforeSend: function() { $("#overlay").show(); },
      success: function(response) {
        if (response.data) {
          $modal.find(".modal-body").html(response.data);
          $modal.modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({ icon: 'error', text: xhr.status + "\n" + (xhr.responseText || thrownError) });
      },
      complete: function() { $("#overlay").hide(); }
    });
  });

  // Pilih data dari Rekam Absen (hanya terdaftar satu kali di halaman utama)
  $(document).on('click', '.setNewRekamAbsen', function() {
    var container = $(this).closest('.media');
    var rekamText = container.find(".setAbsenRekam").text();

    var formData = {
      id_absen: container.find(".d-none").text(),
      rekam: rekamText,
      alamat: container.find(".setAbsenAlamat").text(),
      longitude: container.find(".setAbsenLongitude").text(),
      latitude: container.find(".setAbsenLatitude").text(),
      jam: container.find(".setAbsenJam").text(),
      image: container.find(".setAbsenImage").data("src")
    };

    $.ajax({
      url: '/absen/setNewAbsen',
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() { $("#overlay").show(); },
      success: function(response) {
        Swal.fire({
          icon: response.data ? "success" : "error",
          title: "Data " + rekamText + (response.data ? " Berhasil Diperbaharui!" : " Gagal Diperbaharui!"),
          showConfirmButton: false,
          timer: 1500
        });
      },
      complete: function() { $("#overlay").hide(); }
    });
  });

  // Galeri foto instan menggunakan URL ter-cache (/absen/foto/{id}/...) tanpa AJAX tambahan
  $(document).on('click', '.galleryAction', function(e) {
    e.preventDefault();
    var $img = $(this);
    var firstSrc = $img.attr('src');
    if (!firstSrc) return false;

    var items = [{ src: firstSrc, type: 'image' }];
    var secondSrc = $img.attr('data-second-src');
    if (secondSrc) {
      items.push({ src: secondSrc, type: 'image' });
    }

    if ($.magnificPopup) {
      $.magnificPopup.open({
        items: items,
        gallery: { enabled: items.length > 1 },
        closeBtnInside: true
      });
    } else {
      // Fallback ke modal Bootstrap jika CDN Magnific Popup tidak termuat
      var imgsHtml = items.map(function(item, idx) {
        return '<div class="' + (items.length > 1 ? 'col-md-6' : 'col-12') + ' text-center mb-2">'
          + '<img src="' + escapeHtml(item.src) + '" class="img-fluid rounded border" style="max-height:70vh;" alt="Foto ' + (idx + 1) + '">'
          + '</div>';
      }).join('');
      $("#modal-lg").find(".modal-body").html('<div class="row">' + imgsHtml + '</div>');
      $("#modal-lg").modal('show');
    }
    return false;
  });

  // Helper update tampilan sel alamat setelah Validasi / Invalidasi
  function getMainRow($btn) {
    var $tr = $btn.closest('tr');
    return $tr.hasClass('child') ? $tr.prev() : $tr;
  }

  function applyValidationUI($btn, tipe, isValid) {
    var $row = getMainRow($btn);
    var $cell = $row.find(tipe === 'masuk' ? '.class-absen-alamat-masuk' : '.class-absen-alamat-pulang');
    var alamat = $cell.attr('data-alamat') || '';
    var lon = $cell.attr('data-longitude') || '';
    var lat = $cell.attr('data-latitude') || '';
    var coordHtml = (lon && lat) ? '<br/><i>lon: ' + escapeHtml(lon) + ', lat: ' + escapeHtml(lat) + '</i>' : '';

    if (isValid) {
      $cell.removeClass('table-danger table-success').addClass('table-warning');
      $cell.html("<strong class='text-success'>Absen Terverifikasi <i class='fas fa-check-circle'></i></strong> <br/>" + escapeHtml(alamat) + coordHtml);
    } else {
      $cell.removeClass('table-success table-warning').addClass('table-danger');
      $cell.html("<strong class='text-danger'>Absen Tidak Valid <i class='fas fa-times-circle'></i></strong> <br/>" + escapeHtml(alamat) + coordHtml);
    }

    var btnClass = tipe === 'masuk' ? '.btn-verifikasi-masuk' : '.btn-verifikasi-pulang';
    $row.find(btnClass).remove();
    $btn.closest('tr').find(btnClass).remove();
  }

  function handleValidasi($btn, tipe, isValid) {
    var id = parseInt($btn.data('id'), 10);
    var url = (tipe === 'masuk' ? '/absen/validasiMasuk/' : '/absen/validasiPulang/') + (isValid ? '1/' : '0/') + id;
    var fieldName = tipe === 'masuk' ? 'keterangan_validasi_masuk' : 'keterangan_validasi_pulang';

    var swalOpts = isValid
      ? {
          title: 'Validasi alamat ' + tipe + '?',
          showCancelButton: true,
          confirmButtonText: 'Valid',
          cancelButtonText: 'Batal'
        }
      : {
          title: 'Invalidasi alamat ' + tipe + '?',
          input: 'text',
          inputPlaceholder: 'Masukkan alasan invalidasi (opsional)',
          showCancelButton: true,
          confirmButtonText: 'Invalid',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#d33'
        };

    Swal.fire(swalOpts).then(function(result) {
      if (!result.isConfirmed) return;
      var formData = {};
      formData[fieldName] = isValid ? '' : (result.value || '');

      $.ajax({
        url: url,
        method: 'post',
        data: formData,
        dataType: 'json',
        success: function(response) {
          if (response && response.data) {
            Swal.fire({
              title: 'Berhasil diperbaharui!',
              icon: 'success',
              timer: 800,
              showConfirmButton: false
            });
            applyValidationUI($btn, tipe, isValid);
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire({ icon: 'error', text: xhr.status + "\n" + (xhr.responseText || thrownError) });
        }
      });
    });
  }

  $(document).on('click', '.btn-absen-validasi-masuk', function() { handleValidasi($(this), 'masuk', true); });
  $(document).on('click', '.btn-absen-invalidasi-masuk', function() { handleValidasi($(this), 'masuk', false); });
  $(document).on('click', '.btn-absen-validasi-pulang', function() { handleValidasi($(this), 'pulang', true); });
  $(document).on('click', '.btn-absen-invalidasi-pulang', function() { handleValidasi($(this), 'pulang', false); });

  $(document).ready(function() {
    $('button[data-url]').on('click', function() {
      var url = $(this).data('url');
      $('#myForm').attr('action', url).submit();
    });
  });
})();
</script>

<?= $this->endSection(); ?>
