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
                <input type="text" id="tanggalAbsenHarian" disabled name="tanggal" class="form-control" placeholder="Akses Super User" autocomplete="off" style="min-width: 200px;" />
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
                      <?php if ($idAbsen > 0) : ?>
                        <img src="/dist/img/boxed-bg.jpg"
                             data-foto-url="/absen/foto/<?= $idAbsen; ?>/masuk<?= $verQ; ?>"
                             data-idabsen="<?= $idAbsen; ?>"
                             data-ket="masuk"
                             decoding="async"
                             class="galleryAction absen-thumb absen-foto-deferred"
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
                      <?php if ($idAbsen > 0) : ?>
                        <img src="/dist/img/boxed-bg.jpg"
                             data-foto-url="/absen/foto/<?= $idAbsen; ?>/pulang<?= $verQ; ?>"
                             data-idabsen="<?= $idAbsen; ?>"
                             data-ket="pulang"
                             decoding="async"
                             class="galleryAction absen-thumb absen-foto-deferred"
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
                  <?php if ($canEditDel) : ?>
                    <div class="btn-group mb-2" role="group">
                      <button type="button" data-id="<?= $idAbsen; ?>" data-idpegawai="<?= (int) $data['dp_id_data_pegawai']; ?>" data-nama="<?= esc($data['nama'], 'attr'); ?>" class="btn btn-sm btn-warning btn-edit-absen"><i class="fas fa-edit"></i> Edit</button>
                      <?php if ($idAbsen > 0) : ?>
                        <button type="button" data-id="<?= $idAbsen; ?>" class="btn btn-sm btn-danger btn-delete-absen"><i class="fas fa-trash"></i> Delete</button>
                      <?php endif; ?>
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
  var fotoQueue = [];
  var fotoInFlight = 0;
  var fotoQueueEpoch = 0;
  var FOTO_MAX_CONCURRENT = 2;
  var secondFotoCache = {};

  function escapeHtml(str) {
    return String(str == null ? '' : str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  // Muat thumbnail HANYA untuk baris yang tampil di halaman DataTable aktif (maks 2 request paralel agar tidak membebani worker hosting)
  function pumpFotoQueue() {
    while (fotoInFlight < FOTO_MAX_CONCURRENT && fotoQueue.length > 0) {
      var task = fotoQueue.shift();
      if (!task || task.epoch !== fotoQueueEpoch) continue;
      if (!document.body.contains(task.img)) continue;
      if (task.img.getAttribute('data-foto-loaded') === '1') continue;

      fotoInFlight++;
      (function(t) {
        var done = false;
        var finish = function() {
          if (done) return;
          done = true;
          fotoInFlight = Math.max(0, fotoInFlight - 1);
          pumpFotoQueue();
        };
        var loader = new Image();
        loader.onload = function() {
          if (t.epoch === fotoQueueEpoch && document.body.contains(t.img)) {
            t.img.src = t.url;
            t.img.setAttribute('data-foto-loaded', '1');
            t.img.classList.remove('absen-foto-deferred');
          }
          finish();
        };
        loader.onerror = function() {
          if (t.epoch === fotoQueueEpoch && document.body.contains(t.img)) {
            t.img.setAttribute('data-foto-loaded', '1');
            t.img.classList.remove('absen-foto-deferred');
          }
          finish();
        };
        loader.src = t.url;
      })(task);
    }
  }

  function loadVisibleAbsenPhotos() {
    fotoQueueEpoch++;
    fotoQueue = [];
    var epoch = fotoQueueEpoch;
    $('#tableWithoutExporting tbody img.absen-foto-deferred').each(function() {
      var url = this.getAttribute('data-foto-url');
      if (url && this.getAttribute('data-foto-loaded') !== '1') {
        fotoQueue.push({ img: this, url: url, epoch: epoch });
      }
    });
    pumpFotoQueue();
  }

  function bindVisiblePhotoTriggers(attempt) {
    attempt = attempt || 0;
    var $tbl = $('#tableWithoutExporting');
    if (!$tbl.length) return;
    if ($.fn.DataTable && !$.fn.DataTable.isDataTable($tbl) && attempt < 20) {
      setTimeout(function() { bindVisiblePhotoTriggers(attempt + 1); }, 50);
      return;
    }
    $tbl.off('draw.dt.absenFoto responsive-display.dt.absenFoto')
        .on('draw.dt.absenFoto responsive-display.dt.absenFoto', function() {
          loadVisibleAbsenPhotos();
        });
    loadVisibleAbsenPhotos();
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

    bindVisiblePhotoTriggers();
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
    var $tglInput = $('#tanggalAbsenHarian');

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
  $(document).on('change', '#tanggalAbsenHarian', function() {
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

  var absenAlamatDatalist = `
    <datalist id="absenAlamatOptions">
      <?php foreach ($alamat as $al) : ?>
        <option value="<?= esc($al['ket_tempat'], 'attr'); ?>"></option>
      <?php endforeach; ?>
    </datalist>
  `;

  function getSelectedTargetDate() {
    var $tglInput = $('#tanggalAbsenHarian');
    var raw = ($tglInput.length && !$tglInput.prop('disabled')) ? $.trim($tglInput.val() || '') : '';
    if (raw !== '') {
      var first = $.trim(raw.split(' - ')[0] || '');
      if (/^\d{4}-\d{2}-\d{2}$/.test(first)) {
        return first;
      }
    }
    return (typeof moment !== 'undefined') ? moment().format('YYYY-MM-DD') : new Date().toISOString().slice(0, 10);
  }

  function renderEditAbsenModal(data) {
    var stdStatuses = ['WFO', 'WFA', 'DL', 'TL', 'Cuti', 'Sakit', 'Izin', 'Alpa'];
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

    var isNew = !(parseInt(data.id_absen, 10) > 0);
    var targetDate = data.default_date || getSelectedTargetDate();
    var modalTitle = isNew ? 'Input / Edit Absensi: ' : 'Edit Absensi: ';

    var formHtml = `
      <form id="formEditAbsenAll" method="post" action="/absen/updateDataAbsenAll" enctype="multipart/form-data">
        <input type="hidden" name="id_absen" value="${escapeHtml(data.id_absen || '')}">
        <input type="hidden" name="id_data_pegawai" value="${escapeHtml(data.id_data_pegawai || '')}">
        <input type="hidden" name="tanggal_target" value="${escapeHtml(targetDate)}">
        ${absenAlamatDatalist}
        <div class="row">
          <div class="col-12 text-center mb-3">
            <h5 class="font-weight-bold">${modalTitle}${escapeHtml(data.nama || '')}</h5>
            ${isNew ? '<small class="text-muted">Belum ada data absensi pada tanggal ' + escapeHtml(targetDate) + '. Isi data masuk/pulang atau keterangan di bawah ini.</small>' : ''}
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
                  <input name="jam_masuk" type="text" class="form-control datetimepicker-input" data-target="#timepickerMasuk" placeholder="${escapeHtml(targetDate)} 07:30" value="${escapeHtml(data.jam_masuk ? data.jam_masuk.substring(0, 16) : '')}"/>
                  <div class="input-group-append" data-target="#timepickerMasuk" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Alamat Masuk</label>
              <input type="text" name="alamat_masuk" list="absenAlamatOptions" class="form-control" value="${escapeHtml(data.alamat_masuk || '')}" placeholder="Alamat Masuk">
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
                  <input name="jam_pulang" type="text" class="form-control datetimepicker-input" data-target="#timepickerPulang" placeholder="${escapeHtml(targetDate)} 16:00" value="${escapeHtml(data.jam_pulang ? data.jam_pulang.substring(0, 16) : '')}"/>
                  <div class="input-group-append" data-target="#timepickerPulang" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="far fa-clock"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Alamat Pulang</label>
              <input type="text" name="alamat_pulang" list="absenAlamatOptions" class="form-control" value="${escapeHtml(data.alamat_pulang || '')}" placeholder="Alamat Pulang">
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
    var pickerOpts = { format: 'YYYY-MM-DD HH:mm', useCurrent: false };
    if (isNew && typeof moment !== 'undefined' && targetDate) {
      pickerOpts.viewDate = moment(targetDate + ' 07:30', 'YYYY-MM-DD HH:mm');
    }
    $('#timepickerMasuk').datetimepicker(pickerOpts);
    var pickerPulangOpts = { format: 'YYYY-MM-DD HH:mm', useCurrent: false };
    if (isNew && typeof moment !== 'undefined' && targetDate) {
      pickerPulangOpts.viewDate = moment(targetDate + ' 16:00', 'YYYY-MM-DD HH:mm');
    }
    $('#timepickerPulang').datetimepicker(pickerPulangOpts);
  }

  // Edit Absensi lengkap (berfungsi baik untuk baris yang sudah maupun yang belum absen)
  $(document).on('click', '.btn-edit-absen', function() {
    var idAbsen = parseInt($(this).data('id'), 10) || 0;
    var idPegawai = parseInt($(this).data('idpegawai'), 10) || 0;
    var namaPegawai = $(this).attr('data-nama') || '';

    if (idAbsen <= 0) {
      renderEditAbsenModal({
        id_absen: '',
        id_data_pegawai: idPegawai,
        nama: namaPegawai,
        status_masuk: 'WFO',
        jam_masuk: '',
        alamat_masuk: '',
        longitude_masuk: '',
        latitude_masuk: '',
        status_pulang: '',
        jam_pulang: '',
        alamat_pulang: '',
        longitude_pulang: '',
        latitude_pulang: '',
        keterangan: '',
        default_date: getSelectedTargetDate()
      });
      return;
    }

    $.ajax({
      url: '/absen/getAbsenById/' + idAbsen,
      method: 'GET',
      dataType: 'json',
      beforeSend: function() { $("#overlay").show(); },
      success: function(response) {
        if (!response.data) return;
        renderEditAbsenModal(response.data);
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({ icon: 'error', text: xhr.status + "\n" + (xhr.responseText || thrownError) });
      },
      complete: function() { $("#overlay").hide(); }
    });
  });

  // Simpan form Edit / Input Absensi via AJAX agar filter tanggal Super User tetap terjaga
  $(document).on('submit', '#formEditAbsenAll', function(e) {
    e.preventDefault();
    var form = this;
    var formData = new FormData(form);
    var $submitBtn = $(form).find('button[type="submit"]');
    $submitBtn.prop('disabled', true);

    $.ajax({
      url: $(form).attr('action'),
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      beforeSend: function() { $("#overlay").show(); },
      success: function(response) {
        $("#modal-lg").modal('hide');
        Swal.fire({
          title: (response && response.pesan) ? response.pesan : 'Data berhasil disimpan!',
          icon: 'success',
          timer: 900,
          showConfirmButton: false
        });
        var $tglInput = $('#tanggalAbsenHarian');
        var activeDate = $tglInput.length ? $.trim($tglInput.val() || '') : '';
        if (activeDate !== '' && !$tglInput.prop('disabled') && /^\d{4}-\d{2}-\d{2}(\s*-\s*\d{4}-\d{2}-\d{2})?$/.test(activeDate)) {
          fetchAbsenHarian(activeDate);
        } else {
          setTimeout(function() { location.reload(); }, 900);
        }
      },
      error: function(xhr) {
        var msg = 'Gagal menyimpan data absensi.';
        if (xhr.responseJSON && xhr.responseJSON.pesan) {
          msg = xhr.responseJSON.pesan;
        }
        Swal.fire({ icon: 'warning', title: 'Perhatian', text: msg });
      },
      complete: function() {
        $("#overlay").hide();
        $submitBtn.prop('disabled', false);
      }
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
            var $tglInput = $('#tanggalAbsenHarian');
            var activeDate = $tglInput.length ? $.trim($tglInput.val() || '') : '';
            if (activeDate !== '' && !$tglInput.prop('disabled') && /^\d{4}-\d{2}-\d{2}(\s*-\s*\d{4}-\d{2}-\d{2})?$/.test(activeDate)) {
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

  // Galeri foto: gunakan URL ter-cache (/absen/foto/{id}/...) + cek foto ke-2 via endpoint ringan (/absen/foto-check) hanya saat diklik
  function openGalleryItems(items) {
    if ($.magnificPopup) {
      $.magnificPopup.open({
        items: items,
        gallery: { enabled: items.length > 1 },
        closeBtnInside: true
      });
    } else {
      var imgsHtml = items.map(function(item, idx) {
        return '<div class="' + (items.length > 1 ? 'col-md-6' : 'col-12') + ' text-center mb-2">'
          + '<img src="' + escapeHtml(item.src) + '" class="img-fluid rounded border" style="max-height:70vh;" alt="Foto ' + (idx + 1) + '">'
          + '</div>';
      }).join('');
      $("#modal-lg").find(".modal-body").html('<div class="row">' + imgsHtml + '</div>');
      $("#modal-lg").modal('show');
    }
  }

  $(document).on('click', '.galleryAction', function(e) {
    e.preventDefault();
    var $img = $(this);
    var idAbsen = $img.data('idabsen');
    var ket = $img.data('ket') || 'masuk';
    var firstSrc = $img.attr('data-foto-url') || $img.attr('src');
    if (!firstSrc) return false;

    var cacheKey = idAbsen + '|' + ket;
    if (Object.prototype.hasOwnProperty.call(secondFotoCache, cacheKey)) {
      var items = [{ src: firstSrc, type: 'image' }];
      if (secondFotoCache[cacheKey]) {
        items.push({ src: secondFotoCache[cacheKey], type: 'image' });
      }
      openGalleryItems(items);
      return false;
    }

    $.ajax({
      url: '/absen/foto-check/' + idAbsen + '/' + ket,
      method: 'GET',
      dataType: 'json',
      timeout: 6000,
      success: function(res) {
        var secondUrl = (res && res.has_second && res.url) ? res.url : null;
        secondFotoCache[cacheKey] = secondUrl;
        var items = [{ src: firstSrc, type: 'image' }];
        if (secondUrl) {
          items.push({ src: secondUrl, type: 'image' });
        }
        openGalleryItems(items);
      },
      error: function() {
        openGalleryItems([{ src: firstSrc, type: 'image' }]);
      }
    });

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

    // Jalankan pemuatan foto halaman aktif SETELAH DataTables di layout selesai memaginasi ke 10 baris
    setTimeout(function() {
      bindVisiblePhotoTriggers();
    }, 0);
  });
})();
</script>

<?= $this->endSection(); ?>
