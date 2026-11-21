<?= $this->extend('layouts/adminLTE-3.2.0'); ?>
<?= $this->section('content'); ?>

<?php
$usersList = is_array($user ?? null) ? $user : [];
$isAdminRole = session()->get('role') === 'Admin';
$activeVerifikators = array_filter($usersList, static fn ($u) => !empty($u['id_kendaraan_kantor_verifikator']));
?>

<style>
  .kk-setting .kk-card {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(15, 23, 42, 0.045);
    overflow: hidden;
  }
  .kk-setting .kk-verif-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 14px;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    transition: background-color 0.15s ease;
  }
  .kk-setting .kk-verif-row:hover {
    background-color: #f8fafc;
  }
  .kk-setting .kk-verif-row.is-verifikator {
    background-color: #f0fdfa;
  }
  .kk-setting .kk-pin-input {
    width: 150px !important;
    flex: none !important;
    font-family: 'Consolas', monospace;
    font-weight: 700;
    letter-spacing: 2px;
    text-align: center;
    border-radius: 8px !important;
  }
</style>

<section class="content kk-setting pb-4">
  <div class="container-fluid">
    <div class="row">
      <!-- Daftar Pegawai & Pengaturan PIN Verifikator -->
      <div class="col-lg-7 mb-3">
        <div class="card kk-card">
          <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
              <div>
                <h5 class="mb-0 font-weight-bold" style="color: #0f172a; font-size: 1rem;">
                  <i class="fas fa-user-shield mr-2" style="color: #0d9488;"></i>Pengaturan Verifikator Kendaraan Kantor
                </h5>
                <small class="text-muted">Centang pegawai untuk memberikan hak verifikator dan atur 4 digit PIN akses.</small>
              </div>
              <span class="badge badge-info px-2 py-1" style="background: #0d9488;"><?= count($activeVerifikators); ?> Verifikator Aktif</span>
            </div>
            <div class="mt-3 position-relative">
              <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
              <input type="search" id="kkVerifikatorSearch" class="form-control form-control-sm pl-4" style="border-radius: 9px;" placeholder="Cari nama pegawai...">
            </div>
          </div>

          <div class="card-body p-0" style="max-height: 540px; overflow-y: auto;" id="kkVerifikatorList">
            <?php foreach ($usersList as $data) :
              $isChecked = isset($data['id_kendaraan_kantor_verifikator']) && $data['id_kendaraan_kantor_verifikator'] !== null;
              $isOwnVerif = isset($akses['id_kendaraan_kantor_verifikator']) && $data['id_kendaraan_kantor_verifikator'] == $akses['id_kendaraan_kantor_verifikator'];
              $pinValue = $isAdminRole ? ($data['pin'] ?? '') : ($isOwnVerif ? ($data['pin'] ?? '') : '....');
              $pinReadonly = $isAdminRole ? '' : ($isOwnVerif ? '' : 'readonly');
              $namaFormatted = name_capitalize((string) ($data['nama'] ?? ''));
              $idPeg = (int) $data['id_data_pegawai'];
            ?>
              <div class="kk-verif-row <?= $isChecked ? 'is-verifikator' : ''; ?>" data-name="<?= esc(strtolower($namaFormatted), 'attr'); ?>">
                <div class="d-flex align-items-center" style="gap: 10px; min-width: 0;">
                  <input onclick="checkBoxCheckedCheckKK(event)"
                         <?= $isAdminRole ? '' : 'disabled'; ?>
                         type="checkbox"
                         style="width: 17px; height: 17px;"
                         data-checkboxid="idVerifikatorKendaraan<?= $idPeg; ?>"
                         data-id="<?= $idPeg; ?>"
                         <?= $isChecked ? 'checked' : ''; ?>>
                  <div class="text-truncate">
                    <div class="font-weight-bold text-dark" style="font-size: 0.86rem;"><?= esc($namaFormatted); ?></div>
                    <small class="text-muted"><?= $isChecked ? '<span class="text-teal font-weight-bold" style="color:#0d9488;">Verifikator Aktif</span>' : 'Pegawai'; ?></small>
                  </div>
                </div>
                <div>
                  <input id="idVerifikatorKendaraan<?= $idPeg; ?>"
                         type="text"
                         minlength="4"
                         maxlength="4"
                         <?= $isChecked ? '' : 'disabled'; ?>
                         class="form-control form-control-sm kk-pin-input data_pin_verifikator"
                         placeholder="4 Digit PIN"
                         value="<?= esc((string) $pinValue, 'attr'); ?>"
                         <?= $pinReadonly; ?>>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Ringkasan & Panduan Verifikator -->
      <div class="col-lg-5 mb-3">
        <div class="card kk-card mb-3">
          <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold" style="color: #0f172a;">
              <i class="fas fa-check-circle text-success mr-2"></i>Daftar Verifikator Aktif
            </h6>
          </div>
          <div class="card-body">
            <?php if (!empty($activeVerifikators)): ?>
              <div class="d-flex flex-wrap" style="gap: 8px;">
                <?php foreach ($activeVerifikators as $av): ?>
                  <span class="badge badge-light border px-3 py-2 text-dark" style="border-radius: 999px; font-size: 0.8rem;">
                    <i class="fas fa-user-check text-teal mr-1" style="color: #0d9488;"></i>
                    <?= esc(name_capitalize((string) ($av['nama'] ?? ''))); ?>
                  </span>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p class="text-muted small mb-0 font-italic">Belum ada verifikator kendaraan yang ditunjuk.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="card kk-card">
          <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold" style="color: #0f172a;">
              <i class="fas fa-info-circle text-info mr-2"></i>Panduan Akses Verifikator
            </h6>
          </div>
          <div class="card-body small text-muted" style="line-height: 1.6;">
            <ol class="pl-3 mb-3">
              <li class="mb-1"><strong>Administrator</strong> dapat memilih pegawai yang diberi wewenang sebagai Verifikator Kendaraan dengan mencentang kotak di samping nama pegawai.</li>
              <li class="mb-1">Setiap Verifikator wajib memiliki <strong>4 digit PIN</strong> untuk membuka halaman pemilihan unit kendaraan pada portal <code>/kendaraan_kantor_verifikator</code>.</li>
              <li class="mb-0">Ketik 4 karakter PIN pada kolom input lalu tekan <code>Enter</code> atau klik di luar kolom untuk menyimpan PIN secara otomatis.</li>
            </ol>
            <a href="/kendaraan_kantor_verifikator" target="_blank" class="btn btn-sm btn-outline-info btn-block font-weight-bold" style="border-radius: 9px;">
              <i class="fas fa-external-link-alt mr-1"></i> Buka Portal Verifikator Kendaraan
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    $('#kkVerifikatorSearch').on('input', function() {
      var q = ($(this).val() || '').trim().toLowerCase();
      $('#kkVerifikatorList .kk-verif-row').each(function() {
        var name = $(this).attr('data-name') || '';
        $(this).toggle(q === '' || name.indexOf(q) !== -1);
      });
    });
  });
</script>

<?php if ($activeMenu == "kendaraanKantorPengaturan"): ?>
  <script>
    function checkBoxCheckedCheckKK(event) {
      var checkbox = $(event.target);
      var checkboxID = checkbox.data('checkboxid');
      var id = checkbox.data('id');
      var $row = checkbox.closest('.kk-verif-row');

      if (checkbox.is(':checked')) {
        $("#" + checkboxID).prop('disabled', false);
        $row.addClass('is-verifikator');
        $("#overlay").show();
        insertVerifikatorPegawai(id);
      } else {
        $("#" + checkboxID).prop('disabled', true).val("");
        $row.removeClass('is-verifikator');
        $("#overlay").show();
        deleteVerifikatorPegawai(id);
      }
    }

    function insertVerifikatorPegawai(id) {
      $.ajax({
        url: '/kendaraan_kantor/pengaturan/insert/' + id,
        method: 'get',
        dataType: 'json',
        success: function(response) {
          if (response && response.data) {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: 'Verifikator ditambahkan',
              text: 'Pegawai dapat mengatur 4 digit PIN verifikator.',
              timer: 1800,
              showConfirmButton: false
            });
          }
        },
        complete: function() {
          $("#overlay").hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.status + ' ' + thrownError });
        }
      });
    }

    function deleteVerifikatorPegawai(id) {
      $.ajax({
        url: '/kendaraan_kantor/pengaturan/delete/' + id,
        method: 'get',
        dataType: 'json',
        success: function(response) {
          if (response && response.data) {
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: 'Akses verifikator dihapus',
              timer: 1500,
              showConfirmButton: false
            });
          }
        },
        complete: function() {
          $("#overlay").hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.status + ' ' + thrownError });
        }
      });
    }

    $(document).on("change", '.data_pin_verifikator', function() {
      var value = ($(this).val() || '').trim();
      if (value.length === 4) {
        $.ajax({
          url: '/kendaraan_kantor/pengaturan/update_pin',
          data: { pin: value },
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            $("#overlay").show();
          },
          success: function(response) {
            if (response && response.data) {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "PIN berhasil diperbaharui",
                showConfirmButton: false,
                timer: 1500
              });
            } else {
              Swal.fire({
                position: "top-end",
                icon: "warning",
                title: "PIN hanya dapat diperbaharui oleh pemilik akun!",
                showConfirmButton: false,
                timer: 1800
              });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.status + ' ' + thrownError });
          },
          complete: function() {
            $("#overlay").hide();
          }
        });
      }
    });
  </script>
<?php endif; ?>

<?= $this->endSection(); ?>
