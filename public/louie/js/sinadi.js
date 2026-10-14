var table = $("#example11").DataTable({
  paging: true, // Nonaktifkan paginasi
  info: false, // Nonaktifkan info tabel
  columnDefs: [
    {
      targets: [0, 1, 2, 3, 4, 5, 6], // Kolom yang akan memiliki fitur ekspansi
      className: "details-control",
      orderable: true,
    },
  ],
});
table.columns(1).visible(false);

// Event handler untuk mengontrol ekspansi baris
$("#example11 tbody").on("click", "td.details-control", function() {
  var tr = $(this).closest("tr");
  var row = table.row(tr);

  if (row.child.isShown()) {
    // Baris sudah terbuka, tutup
    row.child.hide();
    tr.removeClass("shown");
  } else {
    // Baris ditutup, buka dan tampilkan konten detail
    var id = row.data()[1];

    // Menampilkan SweetAlert2 sebagai indikator loading
    Swal.fire({
      title: "Sedang mengambil data...",
      onBeforeOpen: () => {
        Swal.showLoading();
      },
      allowOutsideClick: false,
      allowEscapeKey: false,
      allowEnterKey: false
    });

    getDataSinadi(id)
      .then((response) => {

        Swal.close();
        // Lanjutkan dengan tindakan lain setelah berhasil mendapatkan data
        // Tampilkan konten detail di dalam baris
        row.child(response.data).show(); // Menggunakan 'response' sebagai konten detail
        tr.addClass("shown");
        console.log("Element dari baris yang dibuka:", row.node()); 
        // Swal.close();
      })
      .catch((error) => {
        console.log("Data berhasil diambil:", response.cek);
        Swal.fire("Terjadi kesalahan", error,"error");
      });
  }
});

// Fungsi getDataSinadi yang mengembalikan Promise
function getDataSinadi(id) {
  return new Promise((resolve, reject) => {
    runAjaxWithCaching(id, resolve, reject);
  });
}



// Deklarasi variabel untuk menyimpan permintaan AJAX terakhir
let lastAjaxRequestSinadi;

// Fungsi untuk menjalankan AJAX dengan caching dan mengembalikan Promise
function runAjaxWithCaching(data, resolve, reject) {
  if (lastAjaxRequestSinadi && lastAjaxRequestSinadi.readyState !== 4) {
    // Jika ada permintaan AJAX sebelumnya yang belum selesai, batalkan permintaan tersebut
    lastAjaxRequestSinadi.abort();
  }

  // Jalankan permintaan AJAX terbaru
  lastAjaxRequestSinadi = $.ajax({
    url: "/sinadi/getPeserta/" + data,
    type: "GET",
    dataType: "json",
    success: function(response) {
      // Menampilkan SweetAlert setelah permintaan berhasil
      // Selesaikan Promise dengan hasil respons
      resolve(response);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      // Menampilkan SweetAlert error
      Swal.fire(
        "Terjadi kesalahan!",
        "Error: " + xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
        "error"
      );
      // Tolak Promise dengan pesan error
      reject(thrownError);
    }
  });
}
// Menambahkan event listener untuk tombol dalam elemen <td>
$(document).on('click', '.tombol-td', function() {
  // Menemukan elemen <td> pertama
  var tdPertama = $(this).closest('tr').find('td:first');

  // Menyimulasikan peristiwa klik pada elemen <td> pertama
  tdPertama.trigger('click');
});

function downloadFileSinadi(id, nama, bukti_pembayaran = null) {
  var buktiButton = '';
  if (bukti_pembayaran && bukti_pembayaran !== null) {
    buktiButton = '<a href="/sinadi/previewBuktiBerkas/'+bukti_pembayaran+'" class="btn btn-outline-danger btn-block" download>Bukti Pembayaran</a> ' +'<br/>';
  }
  
  Swal.fire({
    title: 'Download File',
    html:
    '<div class="mb-4"><i>User yang memiliki akses untuk mengedit : <strong>'+nama+'</strong></i></div>' + 
    '<button onclick="downloadAllFilesSinadi(\''+id+'\', \''+nama+'\', \''+bukti_pembayaran+'\')" class="btn btn-success btn-block mb-2"><i class="fas fa-download"></i> Download Semua</button>' +
    '<button onclick="downloadAllFilesSinadiWithoutSPPD(\''+id+'\', \''+nama+'\', \''+bukti_pembayaran+'\')" class="btn btn-info btn-block mb-3"><i class="fas fa-download"></i> Download Semua Tanpa SPPD</button>' +
    '<a href="/sinadi/download_nota_dinas_zip/'+id+'" class="btn btn-warning btn-block mb-3"><i class="fas fa-file-archive"></i> Download Semua Nota Dinas</a>' +
    '<hr/>' +
    '<a href="/sinadi/nominatif/'+id+'" class="btn btn-outline-danger btn-block">Nominatif</a> ' +'<br/>' +
    '<a href="/sinadi/kuitansi/'+id+'" class="btn btn-outline-danger btn-block">Kuitansi</a> ' +'<br/>' +
    '<a href="/sinadi/riil/'+id+'" class="btn btn-outline-danger btn-block">Pengeluaran Riil</a> ' +'<br/>' +
    '<a href="/sinadi/sppd/'+id+'" class="btn btn-outline-danger btn-block">SPPD</a> ' +'<br/>' +
    '<a href="/sinadi/export/lembar_verifikator/pdf/'+id+'" class="btn btn-outline-danger btn-block">Lembar Verifikator</a> ' +'<br/>' +
    buktiButton,
    showCloseButton: true,
    showCancelButton: true,
    showConfirmButton: false,
    cancelButtonText:
      'Tutup',
    cancelButtonAriaLabel: 'Thumbs down'
  })
}
function downloadFileSinadiNew(id, nama, bukti_pembayaran = null) {
  var buktiButton = '';
  if (bukti_pembayaran && bukti_pembayaran !== null) {
    buktiButton = '<a href="/sinadi/previewBuktiBerkas/'+bukti_pembayaran+'" class="btn btn-outline-danger btn-block" download>Bukti Pembayaran</a> ' +'<br/>';
  }
  
  Swal.fire({
    title: 'Download File',
    html:
    '<div class="mb-4"><i>User yang memiliki akses untuk mengedit : <strong>'+nama+'</strong></i></div>' + 
    '<button onclick="downloadAllFilesSinadiNew(\''+id+'\', \''+nama+'\', \''+bukti_pembayaran+'\')" class="btn btn-success btn-block mb-2"><i class="fas fa-download"></i> Download Semua</button>' +
    '<button onclick="downloadAllFilesSinadiNewWithoutSPPD(\''+id+'\', \''+nama+'\', \''+bukti_pembayaran+'\')" class="btn btn-info btn-block mb-3"><i class="fas fa-download"></i> Download Semua Tanpa SPPD</button>' +
    '<a href="/sinadi/download_nota_dinas_zip/'+id+'" class="btn btn-warning btn-block mb-3"><i class="fas fa-file-archive"></i> Download Semua Nota Dinas</a>' +
    '<hr/>' +
    '<a href="/sinadi/nominatif/'+id+'" class="btn btn-outline-danger btn-block">Nominatif</a> ' +'<br/>' +
    '<a href="/sinadi/kuitansi-riil/'+id+'" class="btn btn-outline-danger btn-block">Kuitansi dan Riil</a> ' +'<br/>' +
    '<a href="/sinadi/sppd/'+id+'" class="btn btn-outline-danger btn-block">SPPD</a> ' +'<br/>' +
    '<a href="/sinadi/export/lembar_verifikator/pdf/'+id+'" class="btn btn-outline-danger btn-block">Lembar Verifikator</a> ' +'<br/>' +
    buktiButton,
    showCloseButton: true,
    showCancelButton: true,
    showConfirmButton: false,
    cancelButtonText:
      'Tutup',
    cancelButtonAriaLabel: 'Thumbs down'
  })
}

$(document).ready(function () {
    // Menetapkan penundaan selama 3 detik sebelum menyembunyikan overlay
    // setTimeout(function() {
    //     $('#overlay').show();
    // }, 100);
    // setTimeout(function() {
    //     $('#overlay').hide();
    // }, 5000);
    
    sinadi_dalam_kota_view('', '');
    
    $(document).on('change','#sinadiAdmin', function(){
      var tgl_change = $("#sinadiAdmin").val()
      var tanggalArray = tgl_change.split(" - ");

      var start = tanggalArray[0];
      var end = tanggalArray[1];

      sinadi_dalam_kota_view(start, end)
    })
});

function sinadi_dalam_kota_view(start='', end='') {
  var activeMenu = $('#activeMenuInput').val();
  var ajaxUrl;

  if (activeMenu === 'dinasDalamKota')
    ajaxUrl = '/sinadi/dinasDalamKotaView';
  else
    ajaxUrl = '/sinadi/dinasLuarKotaView';
  
  $.ajax({
    url: ajaxUrl,
    method: 'post',
    dataType: "json",
    data: {
      start: start,
      end: end,
      activeMenu: activeMenu
    },
    success: function(response) {
      $(".sinadi_dalam_kota_view").html(response.data)
      
      console.log(response.start)
      console.log(response.end)
      $('#sinadiAdmin').daterangepicker({
        startDate: response.start,
        endDate: response.end,
        locale: {
            format: 'YYYY-MM-DD'
        },
      });
    }, error: function(xhr, ajaxOptions, thrownError) {
      // Menampilkan SweetAlert error
      Swal.fire(
        "Terjadi kesalahan!",
        "Error: " + xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
        "error"
      );
      // Tolak Promise dengan pesan error
      reject(thrownError);
    }
  });
}

// Fungsi untuk download semua file sekaligus (versi lama)
function downloadAllFilesSinadi(id, nama, bukti_pembayaran = null) {
  Swal.fire({
    title: 'Download Semua File',
    text: 'Semua dokumen akan diunduh secara berurutan...',
    icon: 'info',
    showConfirmButton: false,
    allowOutsideClick: false,
    timer: 2000
  });

  // Daftar URL untuk diunduh
  var urls = [
    '/sinadi/nominatif/' + id,
    '/sinadi/kuitansi/' + id,
    '/sinadi/riil/' + id,
    '/sinadi/sppd/' + id,
    '/sinadi/export/lembar_verifikator/pdf/' + id
  ];

  // Tambahkan bukti pembayaran jika ada
  if (bukti_pembayaran && bukti_pembayaran !== null) {
    urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
  }

  // Download setiap file dengan delay
  urls.forEach(function(url, index) {
    setTimeout(function() {
      var link = document.createElement('a');
      link.href = url;
      link.download = '';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }, index * 500); // Delay 500ms antara setiap download
  });

  // Tutup modal setelah semua download dimulai
  setTimeout(function() {
    Swal.close();
  }, 2000);
}

// Fungsi untuk download semua file sekaligus (versi baru)
function downloadAllFilesSinadiNew(id, nama, bukti_pembayaran = null) {
  Swal.fire({
    title: 'Download Semua File',
    text: 'Semua dokumen akan diunduh secara berurutan...',
    icon: 'info',
    showConfirmButton: false,
    allowOutsideClick: false,
    timer: 2000
  });

  // Daftar URL untuk diunduh
  var urls = [
    '/sinadi/nominatif/' + id,
    '/sinadi/kuitansi-riil/' + id,
    '/sinadi/sppd/' + id,
    '/sinadi/export/lembar_verifikator/pdf/' + id
  ];

  // Tambahkan bukti pembayaran jika ada
  if (bukti_pembayaran && bukti_pembayaran !== null) {
    urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
  }

  // Download setiap file dengan delay
  urls.forEach(function(url, index) {
    setTimeout(function() {
      var link = document.createElement('a');
      link.href = url;
      link.download = '';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }, index * 500); // Delay 500ms antara setiap download
  });

  // Tutup modal setelah semua download dimulai
  setTimeout(function() {
    Swal.close();
  }, 2000);
}

// Fungsi untuk download semua file kecuali SPPD (versi lama)
function downloadAllFilesSinadiWithoutSPPD(id, nama, bukti_pembayaran = null) {
  Swal.fire({
    title: 'Download Semua File Tanpa SPPD',
    text: 'Dokumen akan diunduh secara berurutan (tanpa SPPD)...',
    icon: 'info',
    showConfirmButton: false,
    allowOutsideClick: false,
    timer: 2000
  });

  // Daftar URL untuk diunduh (tanpa SPPD)
  var urls = [
    '/sinadi/nominatif/' + id,
    '/sinadi/kuitansi/' + id,
    '/sinadi/riil/' + id,
    '/sinadi/export/lembar_verifikator/pdf/' + id
  ];

  // Tambahkan bukti pembayaran jika ada
  if (bukti_pembayaran && bukti_pembayaran !== null) {
    urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
  }

  // Download setiap file dengan delay
  urls.forEach(function(url, index) {
    setTimeout(function() {
      var link = document.createElement('a');
      link.href = url;
      link.download = '';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }, index * 500); // Delay 500ms antara setiap download
  });

  // Tutup modal setelah semua download dimulai
  setTimeout(function() {
    Swal.close();
  }, 2000);
}

// Fungsi untuk download semua file kecuali SPPD (versi baru)
function downloadAllFilesSinadiNewWithoutSPPD(id, nama, bukti_pembayaran = null) {
  Swal.fire({
    title: 'Download Semua File Tanpa SPPD',
    text: 'Dokumen akan diunduh secara berurutan (tanpa SPPD)...',
    icon: 'info',
    showConfirmButton: false,
    allowOutsideClick: false,
    timer: 2000
  });

  // Daftar URL untuk diunduh (tanpa SPPD)
  var urls = [
    '/sinadi/nominatif/' + id,
    '/sinadi/kuitansi-riil/' + id,
    '/sinadi/export/lembar_verifikator/pdf/' + id
  ];

  // Tambahkan bukti pembayaran jika ada
  if (bukti_pembayaran && bukti_pembayaran !== null) {
    urls.push('/sinadi/previewBuktiBerkas/' + bukti_pembayaran);
  }

  // Download setiap file dengan delay
  urls.forEach(function(url, index) {
    setTimeout(function() {
      var link = document.createElement('a');
      link.href = url;
      link.download = '';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }, index * 500); // Delay 500ms antara setiap download
  });

  // Tutup modal setelah semua download dimulai
  setTimeout(function() {
    Swal.close();
  }, 2000);
}