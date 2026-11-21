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
  .absen-ttd-thumb {
    background: #ffffff;
    border: 1px solid #dee2e6;
    padding: 2px;
  }
</style>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mt-1 font-weight-bold">Daftar Hadir Dinas Luar</h3>

        <div class="card-tools d-flex align-items-center" style="gap: 10px;">
          <?php if (!empty($superUser)) : ?>
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
        <?= view('absen/get_absen_dinas_luar', [
          'absen'        => $absen,
          'isAdmin'      => !empty($isAdmin),
          'aksesEditDel' => !empty($aksesEditDel),
        ]); ?>
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
(function() {
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

  // Muat thumbnail Foto & TTD HANYA untuk baris yang tampil di halaman DataTable aktif (maks 2 paralel)
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

  function fetchAbsenDinasLuar(tanggalVal) {
    tanggalVal = $.trim(tanggalVal || '');
    if (!tanggalVal) return;

    if (currentAbsenXhr && currentAbsenXhr.readyState !== 4) {
      currentAbsenXhr.abort();
    }

    currentAbsenXhr = $.ajax({
      url: '/absen/get_absen_dinas_luar',
      data: { tanggal: tanggalVal },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(".dataAbsenHarian").html(
          '<div class="text-center py-4"><i class="fas fa-sync-alt fa-spin mr-1"></i> <span class="text-bold">Memuat data absensi dinas luar...</span></div>'
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
          '<div class="alert alert-danger mb-0">Gagal memuat data absensi dinas luar. Silakan coba pilih tanggal kembali.</div>'
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
    var $tglInput = $('.input-tanggal-view');

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

  // Muat data absensi DL langsung saat rentang tanggal berubah (tanpa delay buatan)
  $(document).on('change', '.input-tanggal-view', function() {
    var value = $.trim($(this).val() || '');
    if (value !== '') {
      fetchAbsenDinasLuar(value);
    }
  });

  // Update label file upload di modal
  $(document).on('change', '.custom-file-input', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').text(fileName || 'Pilih Gambar');
  });

  // Modal Edit Lengkap Absensi
  $(document).on('click', '.btn-edit-absen', function() {
    var idAbsen = $(this).data('id');
    $.ajax({
      url: '/absen/getAbsenById/' + idAbsen,
      method: 'GET',
      dataType: 'json',
      beforeSend: function() { $("#overlay").show(); },
      success: function(response) {
        if (!response || !response.data) return;
        var data = response.data;
        var statuses = ['WFO', 'WFH', 'DL', 'TL', 'Cuti', 'Sakit', 'Izin', 'Alpa'];
        var buildStatusOptions = function(current) {
          var html = '<option value="">-- Pilih Status --</option>';
          statuses.forEach(function(st) {
            html += '<option value="' + st + '"' + (current == st ? ' selected' : '') + '>' + st + '</option>';
          });
          if (current && statuses.indexOf(current) === -1) {
            html += '<option value="' + escapeHtml(current) + '" selected>' + escapeHtml(current) + '</option>';
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
                  <select name="status_masuk" class="form-control">${buildStatusOptions(data.status_masuk)}</select>
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
                  <select name="status_pulang" class="form-control">${buildStatusOptions(data.status_pulang)}</select>
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
            var $tglInput = $('.input-tanggal-view');
            var activeDate = $.trim($tglInput.val() || '');
            if (activeDate !== '' && !$tglInput.prop('disabled')) {
              fetchAbsenDinasLuar(activeDate);
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
        if (response.data) {
          Swal.fire({
            icon: "success",
            title: "Data " + rekamText + " Berhasil Diperbaharui!",
            showConfirmButton: false,
            timer: 1500
          }).then(function() {
            var $tglInput = $('.input-tanggal-view');
            var activeDate = $.trim($tglInput.val() || '');
            if (activeDate !== '' && !$tglInput.prop('disabled')) {
              fetchAbsenDinasLuar(activeDate);
            } else {
              window.location.reload();
            }
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Data " + rekamText + " Gagal Diperbaharui!",
            showConfirmButton: false,
            timer: 1500
          });
        }
      },
      complete: function() { $("#overlay").hide(); }
    });
  });

  // Galeri foto & preview TTD menggunakan URL ter-cache (/absen/foto/{id}/...)
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
          + '<img src="' + escapeHtml(item.src) + '" class="img-fluid rounded border bg-white" style="max-height:70vh;" alt="Gambar ' + (idx + 1) + '">'
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

  $(document).on('click', '.galleryTtdAction', function(e) {
    e.preventDefault();
    var src = $(this).attr('data-foto-url') || $(this).attr('src');
    if (src) {
      openGalleryItems([{ src: src, type: 'image' }]);
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

    $('#formExportLaporan').on('submit', function() {
      var formatUrl = $('#formatExport').val();
      $(this).attr('action', formatUrl);
    });

    // Jalankan pemuatan thumbnail halaman aktif SETELAH DataTables di layout selesai memaginasi ke 10 baris
    setTimeout(function() {
      bindVisiblePhotoTriggers();
    }, 0);
  });
})();
</script>

<?= $this->endSection(); ?>
