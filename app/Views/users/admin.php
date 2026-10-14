<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>

<style>
    .swal2-html-container {
        text-align: unset;
    }
    .swal2-popup.swal2-modal.swal2-icon-info.swal2-show {
        background-color: #d0e3e6;
    }
    .swal2-icon.swal2-info.swal2-icon-show {
        background-color: white;
    }
</style>

<!-- Main content -->
<?php if ($environment): ?>
<section class="content text-danger" style="font-weight: 700;">
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="text-align: center;">
                <h2 style="font-size: 24px;">
                    JANGAN LUPA MERUBAH ENVIRONTMENT <strong>"DEVELOPMENT"</strong> MENJADI <strong>"PRODUCTION"</strong>!!!
                </h2>
            </div>
        </div>
    </div>
</section>
<?php endif;?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="main-body">
      <div class="row">
        <div class="col">
          <div class="container">
            <!-- Widget Jadwal Shalat -->
            <div class="card mb-3 border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
              <div class="card-header bg-info text-white text-center py-2" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                <h5 class="mb-0" style="font-size: 1rem; font-weight: 600;"><i class="fas fa-mosque mr-2"></i> Jadwal Shalat Hari Ini - Kota Bandung</h5>
              </div>
              <div class="card-body p-3" style="background-color: #f8f9fa;">
                <div class="d-flex justify-content-between text-center align-items-center" id="jadwal-shalat-container" style="font-size: 0.85rem; font-weight: 500;">
                  <div class="w-100 text-center text-muted py-2">
                    <div class="spinner-border spinner-border-sm text-info mr-2" role="status"></div> Memuat jadwal shalat...
                  </div>
                </div>
              </div>
            </div>
            <!-- End Widget Jadwal Shalat -->

            <!-- Bagian Tombol Utama -->
            <div class="row mt-3 mb-4">
              <!-- Tombol Apa Ada Yang Baru -->
              <div class="col-12 mb-3">
                <button type="button" class="btn btn-info btn-lg btn-block new-fitur shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 10px; font-weight: 600; padding: 12px 20px; transition: all 0.3s ease;">
                  <i class="fas fa-bullhorn mr-2" style="font-size: 1.2rem;"></i> Apa Ada Yang Baru?
                </button>
              </div>

              <!-- Tombol Manual Books -->
              <div class="col-sm-6 mb-3">
                <a href="/manualBooks" target="_blank" class="btn btn-primary btn-block shadow-sm h-100 d-flex flex-column align-items-center justify-content-center text-decoration-none" style="border-radius: 12px; padding: 20px; transition: transform 0.2s, box-shadow 0.2s;">
                  <div class="bg-white rounded-circle p-3 mb-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                    <i class="fas fa-book text-primary" style="font-size: 2rem;"></i>
                  </div>
                  <h5 class="font-weight-bold mb-0">Manual Books</h5>
                  <small class="text-light mt-2 text-center">Panduan penggunaan aplikasi</small>
                </a>
              </div>

              <!-- Tombol Download APK -->
              <div class="col-sm-6 mb-3">
                <a href="/apk/simply.apk" class="btn btn-success btn-block shadow-sm h-100 d-flex flex-column align-items-center justify-content-center text-decoration-none" style="border-radius: 12px; padding: 20px; transition: transform 0.2s, box-shadow 0.2s;">
                  <div class="bg-white rounded-circle p-3 mb-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                    <i class="fab fa-android text-success" style="font-size: 2rem;"></i>
                  </div>
                  <h5 class="font-weight-bold mb-0">Aplikasi Android</h5>
                  <small class="text-light mt-2 text-center">Download APK untuk absensi</small>
                </a>
              </div>
              
              <!-- Catatan & Link Alternatif -->
              <div class="col-12 mt-2">
                <div class="alert alert-light border shadow-sm" style="border-radius: 10px;">
                  <div class="d-flex align-items-start mb-2">
                    <i class="fas fa-info-circle text-info mt-1 mr-2" style="font-size: 1.2rem;"></i>
                    <div>
                      <h6 class="font-weight-bold mb-1">Catatan Penggunaan:</h6>
                      <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.5;">Aplikasi Android dikhususkan untuk keperluan absensi. Untuk pengelolaan fitur dan data yang lebih kompleks, disarankan menggunakan komputer/laptop agar tampilan lebih maksimal.</p>
                    </div>
                  </div>
                  <hr class="my-2">
                  <a href="/absensi" id="btn-link-absen" class="btn btn-outline-danger btn-block d-flex align-items-center justify-content-center text-decoration-none" style="border-radius: 8px; padding: 10px; font-weight: 600; border-width: 2px;">
                    <i class="fas fa-map-marker-alt mr-2" style="color: inherit;"></i> <span style="color: inherit;">Link Alternatif Absen Melalui Browser</span>
                  </a>
                  
                  <style>
                    /* CSS Khusus agar warna teks dan ikon sesuai pada kondisi normal dan hover */
                    #btn-link-absen {
                      color: #dc3545 !important;
                      text-decoration: none !important;
                    }
                    #btn-link-absen:hover {
                      color: #fff !important;
                      text-decoration: none !important;
                    }
                  </style>
                </div>
              </div>
              
              <!-- Catatan Pribadi -->
              <div class="col-12 mt-2">
                <div class="card shadow-sm border-0" style="border-radius: 10px;">
                  <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h6 class="font-weight-bold mb-0 text-secondary"><i class="fas fa-sticky-note mr-2 text-warning"></i>Catatan Pribadi</h6>
                  </div>
                  <div class="card-body pt-2 pb-3">
                    <form id="formCatatanPribadi">
                      <div class="form-group mb-2">
                        <textarea class="form-control" id="catatan_pribadi" name="catatan_pribadi" rows="3" placeholder="Tulis catatan pribadi Anda di sini (hanya Anda yang dapat melihatnya)..." style="border-radius: 8px; font-size: 0.9rem; resize: none;"><?= isset($by['catatan_pribadi']) ? esc($by['catatan_pribadi']) : ''; ?></textarea>
                      </div>
                      <div class="d-flex justify-content-end">
                        <button type="button" id="btnSimpanCatatan" class="btn btn-sm btn-warning text-dark font-weight-bold" style="border-radius: 6px;">
                          <i class="fas fa-save mr-1"></i> Simpan Catatan
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Bagian Tombol Utama -->
          </div>
        </div>
        <div class="col-sm-8 getCallendarAgenda"></div>
      </div>
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Main Menu</li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->
      <div class="row container-fluid">
        <?php if (in_array('Dashboard', session()->get('accessMenu'))) : ?>
          <div class="col"></div>
          <div class="col-sm-6">
            <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#dashboard')">
              <span class="badge warning"></span>
              <i class="fas fa-tachometer-alt"></i>
              <h5>Dashboard</h5>
              <p>Pemantauan secara langsung kinerja dari sistem untuk memperoleh informasi yang dibutuhkan.</p>
            </a>
          </div>
          <div class="col"></div>
        <?php endif; ?>
      </div>
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Kategori Pengelolaan</li>
        </ol>
      </nav>
      <div class="row container-fluid">
        <?php if (in_array('Infografis', session()->get('accessMenu'))) : ?>
          <div class="col-sm-6">
            <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#infografis')">
              <span class="badge warning"></span>
              <i class="fas fa-file-alt"></i>
              <h5>Infografis</h5>
              <p>Pengelolaan informasi kedatangan dan keberangkatan data pada kapal dan pesawat, serta pengeluaran dokumen, sertifikat dan vaksinasi.</p>
            </a>
          </div>
        <?php endif; ?>
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#sicuti')">
            <span class="badge warning"></span>
            <i class="fas fa-laptop-house"></i>
            <h5>SICUTE</h5>
            <p>Permohonan untuk pengajuan surat Cuti dan Perizinan pegawai khusus pegawai PNS, PPPK dan PPNPN.</p>
          </a>
        </div>
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#sinadi')">
            <span class="badge warning"></span>
            <i class="fas fa-shuttle-van"></i>
            <h5>SINADI</h5>
            <p>Sistem Informasi Perjalanan Dinas, untuk pembuatan surat Nominator, Kuitansi, Pengeluaran Rill dan/ atau SPPD perjalanan dinas dalam kota atau perjalanan dinas luar kota</p>
          </a>
        </div>
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#relaxtime')">
            <span class="badge warning"></span>
            <i class="fas fa-money-check-alt"></i>
            <h5>RELAXTIME</h5>
            <p>Rencana Realisasi Keuangan Realtime.</p>
          </a>
        </div>
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#simandja')">
            <span class="badge warning"></span>
            <i class="fas fa-file-signature"></i>
            <h5>SIMANDJA</h5>
            <p>Sistem Informasi Monitoring Anggaran dan kinerja, untuk memonitoring evaluasi Direktorat Jenderal Anggaran (DJA)</p>
          </a>
        </div>
      </div>
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Kategori Arsip</li>
        </ol>
      </nav>
      <div class="row container-fluid">
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#sipedit')">
            <span class="badge warning"></span>
            <i class="fas fa-book"></i>
            <h5>SIPEDIT</h5>
            <p>Penyimpanan sertifikat atau hasil dokumen dari Pelatihan/ Seminar/ Webinar/ Workshop, untuk memenuhi angka (20) Jam Pelajaran per tahunnya.</p>
          </a>
        </div>
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#sop')">
            <span class="badge warning"></span>
            <i class="fas fa-copy"></i>
            <h5>SOP</h5>
            <p>Penyimpanan dokumen Standar Operasional Prosedur (SOP).</p>
          </a>
        </div>
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#wbk')">
            <span class="badge warning"></span>
            <i class="fab fa-google-drive"></i>
            <h5>WBK STORAGE</h5>
            <p>Penyimpanan dokumen WBK.</p>
          </a>
        </div>
        <div class="col-sm-6">
          <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#arkab')">
            <span class="badge warning"></span>
            <i class="fas fa-archive"></i>
            <h5>ARKAB</h5>
            <p>Penyimpanan berdasarkan surat dengan kode Klasifikasinya (Arsiparis) untuk pengarsipan secara digital.</p>
          </a>
        </div>
      </div>

      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Kategori Pengaturan</li>
        </ol>
      </nav>
      <div class="row container-fluid">
        <?php if (in_array('Admin', session()->get('accessMenu'))) : ?>
          <div class="col-sm-6">
            <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;" onclick="menuOpen('#admin')">
              <i class="fas fa-user-cog"></i>
              <h5>Administrator</h5>
              <p>Pengaturan Administrator untuk pengelolaan <i>Advanced</i> dari data User, Pegawai, Kantor dan fitur lainnya</p>
            </a>
          </div>
        <?php else : ?>
          <div class="col-sm-6">
            <a class="btn-menu-utama btn btn-app bg-info btn-block btn-lg" style="height: 85%;">
              <span class="badge badge-danger"><i class="fas fa-ban fa-3x"></i></span>
              <span class="badge warning"></span>
              <i class="fas fa-user-cog"></i>
              <h5>Admin
              </h5>
              <p>Pengaturan Administrator untuk pengelolaan <i>Advanced</i> dari data User, Pegawai, Kantor dan fitur lainnya</p>
            </a>
          </div>
        <?php endif; ?>
        <div class="col-sm-6">
          <a href="/home/profil" class="btn-menu-utama btn btn-app bg-success btn-block btn-lg" style="height: 85%;">
            <span class="badge warning"></span>
            <i class="fas fa-user-cog"></i>
            <h5>Pengaturan Profile</h5>
            <p>Profile sekaligus pengaturan akun anda</p>
          </a>
        </div>
      </div>

    </div>
    <!-- ================================================================= -->
  </div>
</section>
<script>
  $(document).ready(function() {
    getCallendarAgenda();
  });

  function getCallendarAgenda() {
    $.ajax({
      url: '/home/getCallendarAgenda',
      method: 'get',
      dataType: 'json',
      success: function(response) {
        if (response.data) {
          $(".getCallendarAgenda").html(response.data)
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        });
      }
    });
  }
</script>

<script>
  $(document).ready(function() {
    <?php if (!session()->getFlashdata('gantiPassword')) : ?>
      getInformationAgenda()
    <?php endif; ?>
  });

  function getInformationAgenda() {
    var cardsHtml = '<u><p>Jangan lupakan tentang beberapa informasi berikut!!</p></u>';

    // Set flag bahwa agenda sedang di-request
    window.agendaAlertWillShow = true;

    $.ajax({
      url: '/home/ajax/getAgenda',
      method: 'get',
      dataType: 'json',
      success: function(response) {
        if (response.data && response.data.length > 0) {
          response.data.forEach(function(cardData) {
            var card = document.createElement('div');
            card.classList.add('card');

            var cardBody = document.createElement('div');
            cardBody.classList.add('card-body');
            
            // Konversi URL menjadi tombol yang dapat diklik
            var description = convertUrlsToLinksForAlert(cardData.deskripsi);
            cardBody.innerHTML = description;

            var cardHeader = document.createElement('div');
            cardHeader.classList.add('card-header');

            // Membuat teks untuk card header dengan nilai topik dan waktu_pelaksanaan_awal
            cardHeader.innerHTML = `${cardData.topik} <br /><strong>(${cardData.waktu_pelaksana_awal} - ${cardData.waktu_pelaksana_akhir})</strong>`;

            // Memasukkan card header terlebih dahulu, kemudian card body
            card.appendChild(cardHeader);
            card.appendChild(cardBody);

            cardsHtml += card.outerHTML;
          });


          Swal.fire({
            title: "<strong>Informasi untuk Anda</strong>",
            icon: "info",
            html: cardsHtml,
            showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
            allowOutsideClick: false,
            confirmButtonText: `
        <i class="fa fa-thumbs-up"></i> Great!
      `,
            confirmButtonAriaLabel: "Thumbs up, great!",
            didOpen: () => {
              // Tambahkan event handler untuk tombol link di dalam SweetAlert
              document.querySelectorAll('.swal2-html-container .link-button-alert').forEach(button => {
                button.addEventListener('click', function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  var url = this.getAttribute('data-url');
                  if (url) {
                    window.open(url, '_blank');
                  }
                });
              });
            }
          }).then((result) => {
            window.agendaAlertWillShow = false;
            // Setelah modal agenda ditutup, cek apakah ada popup ulang tahun
            if (typeof window.showBirthdayPopup === 'function') {
              window.showBirthdayPopup();
            }
          });
        } else {
          // Tidak ada data agenda, set flag ke false
          window.agendaAlertWillShow = false;
        }
      },
      error: function() {
        window.agendaAlertWillShow = false;
      }
    });
  }

  // Fungsi untuk mengubah URL menjadi tombol yang dapat diklik khusus untuk alert
  function convertUrlsToLinksForAlert(text) {
    if (!text) return '';
    
    // Regex untuk mendeteksi URL dengan berbagai format
    var urlRegex = /((?:https?:\/\/|www\.)(?:[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&//=]*)))|([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}(?:\/[-a-zA-Z0-9()@:%_\+.~#?&//=]*)?)/g;
    
    // Ganti URL dengan tombol link
    return text.replace(urlRegex, function(url) {
      var href = url;
      // Tambahkan protokol http jika tidak ada
      if (!href.match(/^https?:\/\//i)) {
        href = 'http://' + href;
      }
      return '<button type="button" class="btn btn-sm btn-primary link-button-alert" data-url="' + href + '" style="margin: 2px;">Link</button>';
    });
  }

  $(document).ready(function() {
    // Memuat jadwal shalat 5 waktu
    fetch("https://equran.id/api/v2/shalat", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        provinsi: "Jawa Barat",
        kabkota: "Kota Bandung"
      })
    })
    .then(response => response.json())
    .then(result => {
      if (result.code === 200 && result.data && result.data.jadwal) {
        // Ambil tanggal hari ini (1-31)
        var today = new Date().getDate();
        var scheduleToday = result.data.jadwal.find(j => j.tanggal === today);
        
        if (scheduleToday) {
          var html = `
            <div class="px-2 w-100 border-right">
              <span class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Imsak</span>
              <span class="d-block text-dark font-weight-bold" style="font-size: 1.1rem;">${scheduleToday.imsak}</span>
            </div>
            <div class="px-2 w-100 border-right">
              <span class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Subuh</span>
              <span class="d-block text-dark font-weight-bold" style="font-size: 1.1rem;">${scheduleToday.subuh}</span>
            </div>
            <div class="px-2 w-100 border-right">
              <span class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Dzuhur</span>
              <span class="d-block text-dark font-weight-bold" style="font-size: 1.1rem;">${scheduleToday.dzuhur}</span>
            </div>
            <div class="px-2 w-100 border-right">
              <span class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Ashar</span>
              <span class="d-block text-dark font-weight-bold" style="font-size: 1.1rem;">${scheduleToday.ashar}</span>
            </div>
            <div class="px-2 w-100 border-right">
              <span class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Maghrib</span>
              <span class="d-block text-dark font-weight-bold" style="font-size: 1.1rem;">${scheduleToday.maghrib}</span>
            </div>
            <div class="px-2 w-100">
              <span class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Isya</span>
              <span class="d-block text-dark font-weight-bold" style="font-size: 1.1rem;">${scheduleToday.isya}</span>
            </div>
          `;
          document.getElementById('jadwal-shalat-container').innerHTML = html;
        } else {
          document.getElementById('jadwal-shalat-container').innerHTML = '<div class="w-100 text-center text-muted">Jadwal shalat hari ini tidak ditemukan.</div>';
        }
      } else {
        document.getElementById('jadwal-shalat-container').innerHTML = '<div class="w-100 text-center text-danger">Gagal memuat jadwal shalat.</div>';
      }
    })
    .catch(error => {
      console.error("Error fetching prayer times:", error);
      document.getElementById('jadwal-shalat-container').innerHTML = '<div class="w-100 text-center text-danger">Terjadi kesalahan koneksi.</div>';
    });
  });
</script>

<script>
  $(".new-fitur").on('click', function() {
    $.ajax({
      url: '/home/new-fitur',
      method: 'get',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data) {
          $("#modal-lg").find('.modal-body').html(response.data);
          $("#modal-lg").modal('show');
        }
        $("#overlay").hide();
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
    });
  });

  // Fungsi simpan catatan pribadi
  $("#btnSimpanCatatan").on('click', function() {
    var btn = $(this);
    var text = $("#catatan_pribadi").val();
    
    $.ajax({
      url: '/home/simpanCatatanPribadi',
      method: 'post',
      data: { catatan: text },
      dataType: 'json',
      beforeSend: function() {
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...').prop('disabled', true);
      },
      success: function(response) {
        if(response.status == 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Catatan pribadi berhasil disimpan!',
            timer: 1500,
            showConfirmButton: false
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Gagal menyimpan catatan.',
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        });
      },
      complete: function() {
        btn.html('<i class="fas fa-save mr-1"></i> Simpan Catatan').prop('disabled', false);
      }
    });
  });
</script>

<!-- /.content -->
<?= $this->endSection(); ?>