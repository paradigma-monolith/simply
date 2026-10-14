<table id="tabel_pengawasan_kapal" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Waktu Submit</th>
      <th>Nama Kapal</th>
      <th>Datang Dari</th>
      <th>Ke Pelabuhan</th>
      <th>Negara Asal</th>
      <th>Gross Tonnage</th>
      <th>Kategori</th>
      <th>Skoring</th>
      <th>Kresiko</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $nomor = 1;
    foreach ($pengawasanKapal as $data) :
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td>
          <?php 
          // Coba berbagai kemungkinan nama field timestamp
          $waktuSubmit = '-';
          if (isset($data['created_at'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['created_at']));
          } elseif (isset($data['date_created'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['date_created']));
          } elseif (isset($data['created_date'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['created_date']));
          } elseif (isset($data['date_updated'])) {
            $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['date_updated']));
          }
          echo $waktuSubmit;
          ?>
        </td>
        <td><?= $data['nama']; ?></td>
        <td><?= $data['datang_dari']; ?></td>
        <td><?= ucwords(strtolower($data['pelabuhan'])); ?></td>
        <td><?= isset($data['origin_country']) ? $data['origin_country'] : '-'; ?></td>
        <td>
          <input type="number" 
                 class="form-control form-control-sm gross-tonnage-input" 
                 value="<?= isset($data['gross_tonnage']) ? $data['gross_tonnage'] : ''; ?>" 
                 data-id="<?= $data['id']; ?>"
                 data-original-value="<?= isset($data['gross_tonnage']) ? $data['gross_tonnage'] : ''; ?>"
                 placeholder="0"
                 style="width: 100px; border: 1px solid #ddd;">
        </td>
        <td>
          <?php 
          // Menentukan kategori berdasarkan upload Master's Statement Letter
          // Jika ada upload surat pernyataan nahkoda = Luar Negeri
          // Jika tidak ada upload = Dalam Negeri
          $kategori = 'Dalam Negeri';
          if (isset($data['surat_pernyataan_nahkoda']) && !empty($data['surat_pernyataan_nahkoda'])) {
            $kategori = 'Luar Negeri';
          }
          ?>
          <span class="badge <?= $kategori == 'Dalam Negeri' ? 'badge-primary' : 'badge-warning'; ?>">
            <?= $kategori; ?>
          </span>
        </td>
        <td><?= $data['skoring']; ?></td>
        <td>
          <?php 
          // Hitung faktor resiko berdasarkan skoring jika kosong
          $kresiko = $data['kresiko'];
          if (empty($kresiko) && isset($data['skoring'])) {
            $skoring = (int)$data['skoring'];
            if ($skoring >= 20) {
              $kresiko = 'TINGGI';
            } elseif ($skoring >= 10 && $skoring <= 19) {
              $kresiko = 'SEDANG';
            } else {
              $kresiko = 'RENDAH';
            }
          }
          
          // Styling berdasarkan level resiko
          $badgeClass = 'badge-secondary';
          if ($kresiko == 'TINGGI') {
            $badgeClass = 'badge-danger';
          } elseif ($kresiko == 'SEDANG') {
            $badgeClass = 'badge-warning';
          } elseif ($kresiko == 'RENDAH') {
            $badgeClass = 'badge-success';
          }
          ?>
          <span class="badge <?= $badgeClass; ?>"><?= $kresiko; ?></span>
        </td>
        <td>
          <?php 
          // Cek status verifikasi
          $statusVerifikasi = isset($data['status_verifikasi']) ? $data['status_verifikasi'] : 'pending';
          
          if ($statusVerifikasi == 'pending') {
            // Tampilkan tombol verifikasi jika belum diverifikasi
            ?>
            <button type="button" class="btn btn-warning btn-sm btn-verify" 
                    data-id="<?= $data['id']; ?>" 
                    data-nama="<?= htmlspecialchars($data['nama']); ?>"
                    data-skoring="<?= $data['skoring']; ?>">
              <i class="fas fa-check-circle"></i> Belum Verifikasi
            </button>
            <?php
          } else {
            // Tampilkan status yang sudah diverifikasi
            $hasilStatus = $data['hasilStatus'];
            if (empty($hasilStatus) && isset($data['skoring'])) {
              $skoring = (int)$data['skoring'];
              if ($skoring >= 20) {
                $hasilStatus = 'ZONA KARANTINA';
              } elseif ($skoring >= 10 && $skoring <= 19) {
                $hasilStatus = 'KONFIRMASI';
              } else {
                $hasilStatus = 'SANDAR';
              }
            }
            
            // Styling berdasarkan status
            $statusClass = 'badge-secondary';
            if ($hasilStatus == 'ZONA KARANTINA') {
              $statusClass = 'badge-danger';
            } elseif ($hasilStatus == 'KONFIRMASI') {
              $statusClass = 'badge-warning';
            } elseif ($hasilStatus == 'SANDAR') {
              $statusClass = 'badge-success';
            }
            ?>
            <div>
              <span class="badge <?= $statusClass; ?>"><?= $hasilStatus; ?></span>
              <?php 
              $hasVerificationInfo = (isset($data['catatan_verifikasi']) && !empty($data['catatan_verifikasi'])) || 
                                    (isset($data['verified_by']) && !empty($data['verified_by']));
              if ($hasVerificationInfo): ?>
                <div style="font-size: 11px; color: #6c757d; margin-top: 2px;">
                  <?php if (isset($data['verified_by']) && !empty($data['verified_by'])): ?>
                    Diverifikasi oleh: <?= htmlspecialchars($data['verified_by'], ENT_QUOTES, 'UTF-8'); ?>
                    <?php if (isset($data['verified_at']) && !empty($data['verified_at'])): ?>
                      (<?= date('d/m/Y H:i', strtotime($data['verified_at'])); ?>)
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php if (isset($data['catatan_verifikasi']) && !empty($data['catatan_verifikasi'])): ?>
                    <br>Catatan: <?= htmlspecialchars($data['catatan_verifikasi'], ENT_QUOTES, 'UTF-8'); ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
            <?php
          }
          ?>
        </td>
        <td>
          <div class="btn-group" role="group">
            <a class="btn btn-sm btn-primary" href="/detail_pengawasan_kapal/<?= $data['id']; ?>" title="Lihat Detail">
              <i class="fas fa-eye"></i>
            </a>
            <?php if (!empty($data['surat_pernyataan_nahkoda'])): ?>
              <a class="btn btn-sm btn-success" 
                 href="https://selfassessment.bkkbandung.net/quarantine/download/<?= htmlspecialchars($data['surat_pernyataan_nahkoda'], ENT_QUOTES, 'UTF-8') ?>" 
                 target="_blank" 
                 title="Download Master's Statement Letter">
                <i class="fas fa-download"></i> Surat Pernyataan
              </a>
            <?php endif; ?>
            <button class="btn btn-sm btn-danger" 
                    onclick="deleteData(<?= $data['id']; ?>, '<?= htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?>')" 
                    title="Hapus Data">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
  $("#tabel_pengawasan_kapal").DataTable({
    "responsive": true,
    "responsive": {
      "details": {
        "renderer": DataTable.Responsive.renderer.listHiddenNodes()
      }
    },
    "lengthChange": true,
    "autoWidth": false,
    "pageLength": 25,
    "columnDefs": [
      {
        "targets": -1, // Target kolom terakhir (Aksi)
        "orderable": false, // Tidak bisa diurutkan
        "searchable": false // Tidak ikut dalam pencarian
      }
    ]
  });

  // Fungsi untuk menghapus data
  function deleteData(id, namaKapal) {
    Swal.fire({
      title: 'Konfirmasi Hapus',
      html: `Apakah Anda yakin ingin menghapus data pengawasan kapal:<br><strong>${namaKapal}</strong>?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
      cancelButtonText: '<i class="fas fa-times"></i> Batal',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {
        // Tampilkan loading
        Swal.fire({
          title: 'Menghapus Data...',
          text: 'Mohon tunggu sebentar',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        // Kirim request delete ke server
        $.ajax({
          url: '/pengawasan_kapal/delete_data',
          type: 'POST',
          data: {
            id: id
          },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data pengawasan kapal berhasil dihapus.',
                timer: 2000,
                showConfirmButton: false
              });
              
              // Refresh data tabel
              setTimeout(function() {
                ambil_data($('#datePengawasanKapal').val());
              }, 500);
              
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: response.message || 'Terjadi kesalahan saat menghapus data.'
              });
            }
          },
          error: function(xhr, status, error) {
            console.log('Error:', xhr.responseText);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Terjadi kesalahan koneksi saat menghapus data.'
            });
          }
        });
      }
    });
  }
</script>

<style>
  /* Styling untuk button group di kolom aksi */
  .btn-group .btn {
    margin-right: 2px;
  }
  
  .btn-group .btn:last-child {
    margin-right: 0;
  }
  
  /* Responsive styling untuk mobile */
  @media (max-width: 768px) {
    .btn-group {
      display: flex;
      flex-direction: column;
    }
    
    .btn-group .btn {
      margin-right: 0;
      margin-bottom: 2px;
      font-size: 11px;
    }
    
    .btn-group .btn:last-child {
      margin-bottom: 0;
    }
  }

  /* Styling untuk input Gross Tonnage yang dapat diedit */
  .gross-tonnage-input {
    border: 1px solid #ddd !important;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 13px;
    text-align: center;
    background-color: #fff;
    transition: all 0.3s ease;
    min-width: 80px;
    max-width: 100px;
  }

  .gross-tonnage-input:focus {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
  }

  .gross-tonnage-input:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
  }

  .gross-tonnage-input.updating {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    cursor: wait;
  }

  .gross-tonnage-input.updated {
    background-color: #d4edda;
    border-color: #c3e6cb;
    animation: flash-success 0.5s ease-in-out;
  }

  @keyframes flash-success {
    0% { background-color: #d4edda; }
    50% { background-color: #a3d977; }
    100% { background-color: #d4edda; }
  }

  /* Styling untuk mobile */
  @media (max-width: 768px) {
    .gross-tonnage-input {
      font-size: 12px;
      min-width: 70px;
      max-width: 90px;
    }
  }
</style>

<!-- Modal Verifikasi Status -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalVerifikasiLabel">
          <i class="fas fa-check-circle"></i> Verifikasi Status Kapal
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formVerifikasi">
          <input type="hidden" id="kapal_id" name="kapal_id">
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="font-weight-bold">Nama Kapal:</label>
              <p id="nama_kapal_display" class="text-primary"></p>
            </div>
            <div class="col-md-6">
              <label class="font-weight-bold">Skoring Otomatis:</label>
              <p id="skoring_display" class="text-info"></p>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-12">
              <label class="font-weight-bold">Status Otomatis Berdasarkan Skoring:</label>
              <p id="status_otomatis_display" class="badge badge-lg"></p>
            </div>
          </div>
          
          <hr>
          
          <div class="form-group">
            <label for="status_manual" class="font-weight-bold">
              <i class="fas fa-hand-paper"></i> Pilih Status Manual:
            </label>
            <select class="form-control" id="status_manual" name="status_manual" required>
              <option value="">-- Pilih Status --</option>
              <option value="SANDAR">SANDAR</option>
              <option value="ZONA KARANTINA">ZONA KARANTINA</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="catatan_verifikasi" class="font-weight-bold">
              <i class="fas fa-sticky-note"></i> Catatan Verifikasi:
            </label>
            <textarea class="form-control" id="catatan_verifikasi" name="catatan_verifikasi" 
                      rows="4" placeholder="Masukkan catatan atau alasan pemilihan status manual..."></textarea>
          </div>
          
          <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Informasi:</strong> Status yang dipilih secara manual akan menggantikan status otomatis berdasarkan skoring.
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Batal
        </button>
        <button type="button" class="btn btn-success" id="btnSimpanVerifikasi">
          <i class="fas fa-save"></i> Simpan Verifikasi
        </button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Inisialisasi tooltip Bootstrap
  function initTooltips() {
    $('[data-toggle="tooltip"]').tooltip();
  }
  
  // Panggil inisialisasi tooltip saat halaman dimuat
  initTooltips();
  
  // Event handler untuk tombol verifikasi
  $(document).on('click', '.btn-verify', function() {
    var kapalId = $(this).data('id');
    var namaKapal = $(this).data('nama');
    var skoring = $(this).data('skoring');
    
    // Set data ke modal
    $('#kapal_id').val(kapalId);
    $('#nama_kapal_display').text(namaKapal);
    $('#skoring_display').text(skoring);
    
    // Hitung dan tampilkan status otomatis
    var statusOtomatis = '';
    var badgeClass = '';
    if (skoring >= 20) {
      statusOtomatis = 'ZONA KARANTINA';
      badgeClass = 'badge-danger';
    } else if (skoring >= 10 && skoring <= 19) {
      statusOtomatis = 'KONFIRMASI';
      badgeClass = 'badge-warning';
    } else {
      statusOtomatis = 'SANDAR';
      badgeClass = 'badge-success';
    }
    
    $('#status_otomatis_display').text(statusOtomatis).removeClass().addClass('badge badge-lg ' + badgeClass);
    
    // Set default status manual sama dengan otomatis
    $('#status_manual').val(statusOtomatis);
    
    // Reset form
    $('#catatan_verifikasi').val('');
    
    // Tampilkan modal
    $('#modalVerifikasi').modal('show');
  });
  
  // Event handler untuk simpan verifikasi
  $('#btnSimpanVerifikasi').click(function() {
    var formData = {
      kapal_id: $('#kapal_id').val(),
      status_manual: $('#status_manual').val(),
      catatan_verifikasi: $('#catatan_verifikasi').val()
    };
    
    if (!formData.status_manual) {
      Swal.fire({
        icon: 'warning',
        title: 'Peringatan',
        text: 'Silakan pilih status manual terlebih dahulu!'
      });
      return;
    }
    
    // Tampilkan loading pada tombol
    var $btn = $(this);
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
    
    // Pastikan overlay tidak aktif
    $("#overlay").hide();
    
    // Kirim data ke server
    $.ajax({
      url: '/pengawasan_kapal/verify_status',
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          // Tutup modal terlebih dahulu
          $('#modalVerifikasi').modal('hide');
          
          // Tampilkan pesan sukses
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Status kapal berhasil diverifikasi!',
            timer: 2000,
            showConfirmButton: false
          });
          
          // Refresh data tabel setelah modal tertutup
          setTimeout(function() {
            ambil_data($('#datePengawasanKapal').val());
            // Inisialisasi ulang tooltip setelah refresh
            setTimeout(function() {
              initTooltips();
            }, 100);
          }, 500);
          
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: response.message || 'Terjadi kesalahan saat menyimpan verifikasi!'
          });
        }
      },
      error: function(xhr, status, error) {
        console.log('Error:', xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Terjadi kesalahan koneksi!'
        });
      },
      complete: function() {
        // Reset tombol
        $btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Verifikasi');
        // Pastikan overlay tidak aktif
        $("#overlay").hide();
      }
    });
  });

  // Event handler untuk perubahan Gross Tonnage
  $(document).on('blur', '.gross-tonnage-input', function() {
    var $input = $(this);
    var kapalId = $input.data('id');
    var newValue = $input.val();
    var originalValue = $input.data('original-value');
    
    // Jika nilai tidak berubah, tidak perlu update
    if (newValue == originalValue) {
      return;
    }
    
    // Validasi input
    if (newValue !== '' && (isNaN(newValue) || parseFloat(newValue) < 0)) {
      Swal.fire({
        icon: 'error',
        title: 'Input Tidak Valid',
        text: 'Gross Tonnage harus berupa angka positif!'
      });
      $input.val(originalValue);
      return;
    }
    
    // Tampilkan loading pada input
    $input.prop('disabled', true).addClass('updating');
    
    // Kirim request update ke server
    $.ajax({
      url: '/pengawasan_kapal/update_gross_tonnage',
      type: 'POST',
      data: {
        id: kapalId,
        gross_tonnage: newValue
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          // Update original value
          $input.data('original-value', newValue);
          
          // Tampilkan notifikasi sukses
          $input.removeClass('updating').addClass('updated');
          setTimeout(function() {
            $input.removeClass('updated');
          }, 2000);
          
          // Toast notification
          $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Berhasil',
            subtitle: 'Update',
            body: 'Gross Tonnage berhasil diperbarui',
            autohide: true,
            delay: 3000
          });
          
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal Update',
            text: response.message || 'Terjadi kesalahan saat memperbarui Gross Tonnage!'
          });
          $input.val(originalValue);
        }
      },
      error: function(xhr, status, error) {
        console.log('Error:', xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Terjadi kesalahan koneksi saat memperbarui Gross Tonnage!'
        });
        $input.val(originalValue);
      },
      complete: function() {
        $input.prop('disabled', false).removeClass('updating');
      }
    });
  });

  // Event handler untuk Enter key pada input Gross Tonnage
  $(document).on('keypress', '.gross-tonnage-input', function(e) {
    if (e.which == 13) { // Enter key
      $(this).blur();
    }
  });
});
</script>

<style>
/* Style untuk tooltip agar tidak terlalu memanjang */
.tooltip {
  max-width: 250px !important;
  width: auto !important;
}

.tooltip-inner {
  max-width: 250px !important;
  width: auto !important;
  white-space: normal !important;
  word-wrap: break-word !important;
  word-break: break-word !important;
  overflow-wrap: break-word !important;
  text-align: left !important;
  padding: 8px 12px !important;
  line-height: 1.4 !important;
  font-size: 12px !important;
}

/* Khusus untuk Bootstrap tooltip */
.bs-tooltip-bottom .tooltip-inner,
.bs-tooltip-top .tooltip-inner,
.bs-tooltip-left .tooltip-inner,
.bs-tooltip-right .tooltip-inner {
  max-width: 250px !important;
  width: auto !important;
  white-space: normal !important;
  word-wrap: break-word !important;
  word-break: break-word !important;
  overflow-wrap: break-word !important;
}
</style>
