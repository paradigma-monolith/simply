<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<style>
  .abs-verif-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }
  .abs-verif-head {
    padding: 13px 16px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .abs-verif-icon {
    width: 35px;
    height: 35px;
    border-radius: 9px;
    background: #f0fdfa;
    color: #0d9488;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
  }
  .abs-verif-title {
    font-size: 0.92rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
  }
  .abs-verif-sub {
    font-size: 0.72rem;
    color: #64748b;
    margin: 1px 0 0;
  }
  .abs-verif-body {
    padding: 16px;
  }
  .abs-verif-body .form-group label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 4px;
  }
  .abs-empty-state {
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 14px;
    padding: 48px 24px;
    text-align: center;
    color: #64748b;
  }
</style>

<section class="content pt-2 pb-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-4 mb-3">
        <div class="abs-verif-card">
          <div class="abs-verif-head">
            <div class="abs-verif-icon"><i class="fas fa-qrcode"></i></div>
            <div>
              <h3 class="abs-verif-title">Verifikasi Lokasi Inventaris</h3>
              <p class="abs-verif-sub">Tentukan lokasi target lalu pindai / pilih QR SIMAN</p>
            </div>
          </div>

          <div class="abs-verif-body">
            <div class="form-group">
              <label>Wilayah Kerja Target</label>
              <select name="unit_kerja" class="form-control select2" style="width: 100%;">
                <option value="">-- Pilih Wilayah Kerja --</option>
                <?php foreach (($unit_kerja ?? []) as $data) : ?>
                  <option value="<?= $data['id_unit_kerja']; ?>"><?= esc($data['unit_kerja']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Lantai Target</label>
              <select name="lantai" class="form-control select2" style="width: 100%;">
                <option value="">-- Pilih Lantai --</option>
                <?php foreach (($lantai ?? []) as $data) : ?>
                  <option value="<?= $data['id_abs_lantai']; ?>"><?= esc($data['lantai']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Ruang Target</label>
              <select name="ruang" class="form-control select2" style="width: 100%;">
                <option value="">-- Pilih Ruang --</option>
                <?php foreach (($ruang ?? []) as $data) : ?>
                  <option value="<?= $data['id_abs_ruang']; ?>"><?= esc($data['ruang']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Kategori Barang Target</label>
              <select name="jenis" class="form-control select2" style="width: 100%;">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach (($jenis ?? []) as $data) : ?>
                  <option value="<?= $data['id_abs_jenis']; ?>"><?= esc($data['jenis']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <hr>

            <!-- Input langsung QR Code tanpa harus buka modal -->
            <div class="form-group mb-2">
              <label>Pindai / Ketik Kode QR (SIMAN atau Internal)</label>
              <div class="input-group">
                <input type="text"
                       id="directQrInputAbs"
                       class="form-control"
                       placeholder="Scan atau tempel kode QR..."
                       autocomplete="off">
                <div class="input-group-append">
                  <button type="button" class="btn btn-primary" id="btnDirectSearchAbs" style="background:#0d9488;border-color:#0d9488;">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              <small class="text-muted">Tekan Enter setelah scan atau pilih dari daftar melalui tombol di bawah.</small>
            </div>

            <div class="mt-3">
              <button type="button" class="btn btn-outline-info btn-block font-weight-bold searchABS">
                <i class="fas fa-list-ul mr-1"></i> Pilih dari Daftar QR SIMAN
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-8 viewABSDataInventaris">
        <div class="abs-empty-state">
          <i class="fas fa-qrcode fa-3x mb-3" style="color: #94a3b8; opacity: 0.6;"></i>
          <h5 class="font-weight-bold text-dark mb-1">Belum Ada Barang yang Dipindai</h5>
          <p class="small mb-0">Pilih lokasi target di panel kiri, lalu pindai kode QR atau klik <strong>Pilih dari Daftar QR SIMAN</strong> untuk memeriksa kesesuaian lokasi inventaris.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).off('click.absVerifSearch', '.searchABS').on('click.absVerifSearch', '.searchABS', function() {
    $("#absScanQRCode").modal("show");
    $("#myFocusInputQRABS").focus();
  });

  $('#directQrInputAbs').on('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      var code = $.trim($(this).val());
      if (code !== '') {
        getDataAbsVerifikasiLokasi(code);
      }
    }
  });

  $('#btnDirectSearchAbs').on('click', function() {
    var code = $.trim($('#directQrInputAbs').val());
    if (code !== '') {
      getDataAbsVerifikasiLokasi(code);
    } else {
      $("#absScanQRCode").modal("show");
    }
  });

  $(document).off('submit.absVerifForm', '#formulirInputQRABS').on('submit.absVerifForm', '#formulirInputQRABS', function(e) {
    e.preventDefault();
    $("#absScanQRCode").modal("hide");
    getDataAbsVerifikasiLokasi();
  });

  function evaluateAbsLocationMatch(itemData) {
    if (!itemData) return;
    var unit_kerja = $('select[name="unit_kerja"]').val() || 0;
    var lantai = $('select[name="lantai"]').val() || 0;
    var ruang = $('select[name="ruang"]').val() || 0;
    var jenis = $('select[name="jenis"]').val() || 0;

    if (
      String(unit_kerja) === String(itemData.id_unit_kerja || 0) &&
      String(lantai) === String(itemData.id_abs_lantai || 0) &&
      String(ruang) === String(itemData.id_abs_ruang || 0) &&
      String(jenis) === String(itemData.id_abs_jenis || 0)
    ) {
      $("#abs_status_verifikasi_lokasi").html('<span class="badge badge-success px-3 py-2" style="font-size:0.82rem;"><i class="fas fa-check-circle mr-1"></i> Lokasi &amp; Kategori Sesuai (Valid)</span>');
    } else {
      $("#abs_status_verifikasi_lokasi").html('<span class="badge badge-danger px-3 py-2" style="font-size:0.82rem;"><i class="fas fa-exclamation-triangle mr-1"></i> Lokasi Berbeda dengan Filter Target (Tidak Valid)</span>');
    }
  }

  // Re-evaluasi status validasi saat dropdown lokasi target diubah
  $(document).on('change', 'select[name="unit_kerja"], select[name="lantai"], select[name="ruang"], select[name="jenis"]', function() {
    if (window._lastVerifiedAbsItem) {
      evaluateAbsLocationMatch(window._lastVerifiedAbsItem);
    }
  });

  function getDataAbsVerifikasiLokasi(overrideQr) {
    var formData = overrideQr ? { qrCode: overrideQr } : $('#formulirInputQRABS').serialize();

    $.ajax({
      url: $('#formulirInputQRABS').attr('action') || '/abs/search/dataABS',
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $('#overlay').show();
      },
      success: function(response) {
        $('#overlay').hide();
        if (response && response.data) {
          $(".viewABSDataInventaris").html(response.data);
          window._lastVerifiedAbsItem = response.getData || null;
          if (response.getData) {
            evaluateAbsLocationMatch(response.getData);
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        $('#overlay').hide();
        Swal.fire({
          icon: 'error',
          title: 'Gagal memuat data',
          text: xhr.status + ' ' + thrownError
        });
      }
    });
  }

  function absMoveVerifikationLocation(id) {
    var unit_kerja = $('select[name="unit_kerja"]').val();
    var lantai = $('select[name="lantai"]').val();
    var ruang = $('select[name="ruang"]').val();
    var jenis = $('select[name="jenis"]').val();

    if (unit_kerja != "" && lantai != "" && ruang != "" && jenis != "") {
      var formData = {
        id_abs_bangunan: id,
        id_unit_kerja: unit_kerja,
        id_abs_lantai: lantai,
        id_abs_ruang: ruang,
        id_abs_jenis: jenis
      };

      var button = $("#abs_btn_verifikasi_lokasi");

      $.ajax({
        url: '/abs/konfirmasiDataInventaris',
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $(button).attr('disabled', true);
          $(button).html('<i class="fas fa-sync-alt fa-spin mr-1"></i> Memindahkan...');
        },
        success: function(response) {
          if (response && response.data) {
            Swal.fire({
              icon: 'success',
              title: 'Pembaruan Berhasil!',
              html: 'Lokasi &amp; kategori <b>Data Inventaris</b> telah diperbarui.'
            });
            var currentQr = $('#directQrInputAbs').val();
            getDataAbsVerifikasiLokasi(currentQr || undefined);
          }
        }
      });
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'Lokasi Target Belum Lengkap',
        html: 'Mohon pilih <b>Wilayah Kerja, Lantai, Ruang, dan Kategori</b> pada panel kiri terlebih dahulu.'
      });
    }
  }
</script>

<?= $this->endSection(); ?>
