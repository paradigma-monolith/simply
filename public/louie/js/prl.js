$(document).on('click', '.button-show-tpp-sec-a', function(){
  $('#exampleModalTppSecA').modal("show");
});

$(document).on('click', '.button-show-tpp-sec-b', function(){
  $('#exampleModalTppSecB').modal("show");
});

$(document).on('click', '.button-show-tpp-sec-c', function(){
  $('#exampleModalTppSecC').modal("show");
});

$('.custom-input[readonly]').each(function () {
  this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
}).on('input', function () {
  this.style.height = 'auto';
  this.style.height = (this.scrollHeight) + 'px';
});



$(document).ready(function () {
  tampilDaftarTpp();
  tampilDaftarSab();
  tampilDaftarHsbu();

  $('#dateInspeksiTpp').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD' // Format tanggal
    },
  });

  $('#dateInspeksiTpp').on('change', function() {
    tampilDaftarInspeksiTpp($(this).val());
  });

  $('#dateInspeksiSab').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD' // Format tanggal
    }
  });

  $('#dateInspeksiSab').on('change', function() {
    tampilDaftarInspeksiSab($(this).val());
  });
  
  $('#dateInspeksiHsbu').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD' // Format tanggal
    }
  });

  $('#dateInspeksiHsbu').on('change', function() {
    tampilDaftarInspeksiHsbu($(this).val());
  });

  tampilDaftarInspeksiTpp();
  tampilDaftarInspeksiSab();
  tampilDaftarInspeksiHsbu();

  tampilTpp();
  tampilDetailTpp();
  tampilSab();
  tampilDetailSab();
  tampilHsbu();
  tampilDetailHsbu();

  $('#pegawai').on('select2:opening', function(event) {
    event.preventDefault();
  });

});


// TABEL DATA TPP =====================================================================================>
function tampilDaftarTpp() {
  $.ajax({
    url: '/prl/tpp_daftar',
    method: 'get',
    dataType: "json",
    success: function(data) {
      $(".prl_daftar_tpp").html(data.data);
    },
    error: function(xhr, status, error) {
      console.error("AJAX error: " + status + ' - ' + error);
    }
  });
}

function tampilDaftarInspeksiTpp(dateRange = '') {
  // Kirimkan data dateRange jika ada
  $.ajax({
    url: '/prl/tpp_daftar_inspeksi',
    method: 'get',
    data: { dateRange: dateRange }, // Kirimkan dateRange jika ada
    dataType: "json",
    beforeSend: function() {
      $(".prl_daftar_inspeksi_tpp").html('<p class="text-center text-muted">Memuat data...</p>');  
    },
    success: function(response) {
      if (response.data) {
        $(".prl_daftar_inspeksi_tpp").html(response.data);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  });
}

// function tampilDaftarInspeksiTpp() {
//   $.ajax({
//     url: '/prl/tpp_daftar_inspeksi',
//     method: 'get',
//     dataType: "json",
//     success: function(data) {
//       $(".prl_daftar_inspeksi_tpp").html(data.data);
//     },
//     error: function(xhr, status, error) {
//       console.error("AJAX error: " + status + ' - ' + error);
//     }
//   });
// }


// SUBMIT FORM TPP ====================================================================================>
$(document).on('submit', '#prlSimpanFormTpp', function (e) {
  e.preventDefault();
  var formData = new FormData($(this)[0]);

  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {   
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/prl/tpp";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});


$(document).on('submit', '#prlSimpanFormInspeksiTpp', function (e) {
  e.preventDefault();
  var formData = new FormData($(this)[0]);

  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {   
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/prl/tpp";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});


// FORM INSPEKSI TPP ==================================================================================>
// Menampilkan nama TPP berdasarkan wilayah kerja yang dipilih
function tampilTpp() {
  $('#wilayah_kerja_tpp').on('change', function() {
    var selectedWilker = $(this).val();
    var namaTppSelect = $('#nama_tpp');
    var penanggungJawabTpp = $('#penanggung_jawab');
    var jumlahKaryawanTpp = $('#jml_karyawan');
    var jumlahKaryawanTpp1 = $('#jml_karyawan1');
    var kapasitasProduksiTpp = $('#kapasitas_produksi');
    var keteranganRisikoInput = $("#keterangan_risiko_sec_c");
    var skorRisikoInput = $("#skor_risiko_sec_c");
    var hasilTppInput = $("#hasil");

    // Kosongkan nilai input
    namaTppSelect.empty().append('<option value="">-- Pilih Nama TPP --</option>');
    penanggungJawabTpp.val('');
    jumlahKaryawanTpp.val('');
    jumlahKaryawanTpp1.val('');
    kapasitasProduksiTpp.val('');
    keteranganRisikoInput.val('');
    skorRisikoInput.val('');
    hasilTppInput.val('');

    if (selectedWilker) {
      $.ajax({
        type: "get",
          url: "/prl/get/tpp/",
          dataType: "json",
          success: function(response) {
            for (let i=0; i < response.dataTpp.length; i++){
              var idWilker = response.dataTpp[i]['id_wilayah_kerja'];
              var idTpp = response.dataTpp[i]['id_prl_tpp'];
              var namaTpp = response.dataTpp[i]['nama_tpp'];
                
              if (idWilker == selectedWilker) {
                var optionAdded = false;
                option = '<option value="'+idTpp+ '">'+namaTpp+'</option>';
                optionAdded = true;
                namaTppSelect.append(option);
                if (!optionAdded) { // Jika tidak ada opsi yang cocok, tambahkan opsi kosong
                  namaTppSelect.append('<option value="" disabled>Tidak ada data</option>');
                }
              }
            }
          }
      });
    }
  });
}


// Lakukan Ajax request untuk mendapatkan data berdasarkan nama_tpp yang dipilih
function tampilDetailTpp() {
  $('#nama_tpp').on('change', function() {
    var selectedTpp = $(this).val();
    var penanggungJawabTpp = $('#penanggung_jawab');
    var jumlahKaryawanTpp = $('#jml_karyawan');
    var jumlahKaryawanTpp1 = $('#jml_karyawan1');
    var kapasitasProduksiTpp = $('#kapasitas_produksi');

    if (selectedTpp) {
      $.ajax({
        type: "get",
        url: "/prl/get/tpp/",
        dataType: "json",
        data: { selectedTpp: selectedTpp },
        success: function(response) {
          if (response) { // Set data ke dalam inputan
            penanggungJawabTpp.val(response.penanggungJawab);
            jumlahKaryawanTpp.val(response.jumlahKaryawan);
            jumlahKaryawanTpp1.val(response.jumlahKaryawan);
            kapasitasProduksiTpp.val(response.kapasitasProduksi);

            // Pemicu event change secara manual setelah mengatur nilai
            jumlahKaryawanTpp1.trigger('change');
            kapasitasProduksiTpp.trigger('change');
          } else {
            // Jika tidak ada data, atau atur nilai menjadi kosong
            penanggungJawabTpp.val('');
            jumlahKaryawanTpp.val('');
            jumlahKaryawanTpp1.val('');
            kapasitasProduksiTpp.val('');
          }
        }
      });
    }
  });
}


// Start handle Section A
$('input[name="sec_a1"]').on('change', function() {
  var selectedValue = $(this).val();
  var skorRisikoInput = $('#skor_risiko_sec_a');
  var keteranganLabel = $('.keterangan-label-sec-a');

  // Hide atau tampilkan form kedua berdasarkan nilai radio button "Tidak"
  if (selectedValue === 'Tidak') {
      $('#sec_a2').show();

      // Jika "Tidak" dipilih, reset nilai skor_risiko_sec_a dan keterangan
      skorRisikoInput.val('');
      keteranganLabel.text('Skor Risiko (Keterangan: tinggi/sedang/rendah)');
  } else {
      $('#sec_a2').hide();
      $('#sec_a3').hide();

      // Jika "Ya" dipilih pada radio button sec_a1, set nilai skor_risiko_sec_a
      if (selectedValue === 'Ya') {
        var skor = 5;
        skorRisikoInput.val(skor);
        keteranganLabel.html('Skor Risiko <span class="badge bg-success">RENDAH</span>');
      }
  }
  skorRisikoInput.trigger('change');
});



$('input[name="sec_a2"]').on('change', function() {
  var selectedValue = $(this).val();
  var skorRisikoInput = $('#skor_risiko_sec_a');
  var keteranganLabel = $('.keterangan-label-sec-a');

  // Hide atau tampilkan form kedua berdasarkan nilai radio button "Ya"
  if (selectedValue === 'Ya') {
    $('#sec_a3').show();

    // Jika "Ya" dipilih, reset nilai skor_risiko_sec_a dan keterangan
    skorRisikoInput.val('');
    keteranganLabel.text('Skor Risiko (Keterangan: tinggi/sedang/rendah)');
  } else {
    $('#sec_a3').hide();

    // Jika "Tidak" dipilih pada radio button sec_a2, set nilai skor_risiko_sec_a
    if (selectedValue === 'Tidak') {
      var skor = 5;
      skorRisikoInput.val(skor);
      keteranganLabel.html('Skor Risiko <span class="badge bg-success">RENDAH</span>');
    }
  }
  skorRisikoInput.trigger('change');
});


$('input[name="sec_a3"]').on('change', function() {
  var selectedValue = $(this).val();
  var skorRisikoInput = $('#skor_risiko_sec_a');
  var keteranganLabel = $('.keterangan-label-sec-a');

  if (selectedValue === 'Ya') {
    var skor = 15;
    skorRisikoInput.val(skor);
    keteranganLabel.html('Skor Risiko <span class="badge bg-danger">TINGGI</span>');
  } else {
    var skor = 10;
    skorRisikoInput.val(skor);
    keteranganLabel.html('Skor Risiko <span class="badge bg-warning">SEDANG</span>');
  }
  skorRisikoInput.trigger('change');
});
// End handle Section A


// Start handle Section B
$('input[name="sec_b1"]').on('change', function() {
  var selectedValue = $(this).val();
  var skorRisikoInput = $('#skor_risiko_sec_b');
  var keteranganLabel = $('.keterangan-label-sec-b');

  // Hide atau tampilkan form kedua berdasarkan nilai radio button "Tidak"
  if (selectedValue === 'Tidak') {
    $('#sec_b2').show();

    // Jika "Tidak" dipilih, reset nilai skor_risiko_sec_a dan keterangan
    skorRisikoInput.val('');
    keteranganLabel.text('Skor Risiko (Keterangan: tinggi/sedang/rendah)');
  } else {
    $('#sec_b2').hide();
    $('#sec_b3').hide();

    // Jika "Ya" dipilih pada radio button sec_a1, set nilai skor_risiko_sec_a
    if (selectedValue === 'Ya') {
      var skor = 15;
      skorRisikoInput.val(skor);
      keteranganLabel.html('Skor Risiko <span class="badge bg-danger">TINGGI</span>');
    }
  }
  skorRisikoInput.trigger('change');
});


$('input[name="sec_b2"]').on('change', function() {
  var selectedValue = $(this).val();
  var skorRisikoInput = $('#skor_risiko_sec_b');
  var keteranganLabel = $('.keterangan-label-sec-b');

  // Hide atau tampilkan form kedua berdasarkan nilai radio button "Tidak"
  if (selectedValue === 'Tidak') {
    $('#sec_b3').show();
    // Jika "Tidak" dipilih, reset nilai skor_risiko_sec_a dan keterangan
    skorRisikoInput.val('');
    keteranganLabel.text('Skor Risiko (Keterangan: tinggi/sedang/rendah)');
  } else {
    $('#sec_b3').hide();

    // Jika "Ya" dipilih pada radio button sec_a1, set nilai skor_risiko_sec_a
    if (selectedValue === 'Ya') {
      var skor = 5;
      skorRisikoInput.val(skor);
      keteranganLabel.html('Skor Risiko <span class="badge bg-success">RENDAH</span>');
    }
  }
  skorRisikoInput.trigger('change');
});


$('input[name="sec_b3"]').on('change', function() {
  var selectedValue = $(this).val();
  var skorRisikoInput = $('#skor_risiko_sec_b');
  var keteranganLabel = $('.keterangan-label-sec-b');

  // Hide atau tampilkan form kedua berdasarkan nilai radio button "Tidak"
  if (selectedValue === 'Tidak') {
    var skor = 15;
    skorRisikoInput.val(skor);
    keteranganLabel.html('Skor Risiko <span class="badge bg-danger">TINGGI</span>');
  } else {
    var skor = 10;
    skorRisikoInput.val(skor);
    keteranganLabel.html('Skor Risiko <span class="badge bg-warning">SEDANG</span>');
  }
  skorRisikoInput.trigger('change');
});
// End handle Section B


// Start handle Section C
$('#jml_karyawan1, #kapasitas_produksi').on('change', function() {
  var jumlahKaryawan = $('#jml_karyawan1').val();
  var kapasitasProduksi = $('#kapasitas_produksi').val();
  var keteranganRisikoInput = $("#keterangan_risiko_sec_c");
  var skorRisikoInput = $("#skor_risiko_sec_c");

  // Kondisi untuk menampilkan skor
  var skor, keterangan, label;
  switch (true) {
    // =====================
    case (jumlahKaryawan == '1-2 Pekerja' && kapasitasProduksi == '<100 kg/bulan atau <400 porsi/bulan'):
      skor = 5;
      break;
    case (jumlahKaryawan == '3-19 Pekerja' && kapasitasProduksi == '<100 kg/bulan atau <400 porsi/bulan'):
      skor = 10;
      break;
    case (jumlahKaryawan == '20-100 Pekerja' && kapasitasProduksi == '<100 kg/bulan atau <400 porsi/bulan'):
      skor = 15;
      break;
    case (jumlahKaryawan == '>100 Pekerja' && kapasitasProduksi == '<100 kg/bulan atau <400 porsi/bulan'):
      skor = 20;
      break;

    // =====================
    case (jumlahKaryawan == '1-2 Pekerja' && kapasitasProduksi == '100-200 kg/bulan atau 400-800 porsi/bulan'):
      skor = 10;
      break;
    case (jumlahKaryawan == '3-19 Pekerja' && kapasitasProduksi == '100-200 kg/bulan atau 400-800 porsi/bulan'):
      skor = 15;
      break;
    case (jumlahKaryawan == '20-100 Pekerja' && kapasitasProduksi == '100-200 kg/bulan atau 400-800 porsi/bulan'):
      skor = 20;
      break;
    case (jumlahKaryawan == '>100 Pekerja' && kapasitasProduksi == '100-200 kg/bulan atau 400-800 porsi/bulan'):
      skor = 20;
      break;

    // =====================
    case (jumlahKaryawan == '1-2 Pekerja' && kapasitasProduksi == '200-500 kg/bulan atau 801-1000 porsi/bulan'):
      skor = 15;
      break;
    case (jumlahKaryawan == '3-19 Pekerja' && kapasitasProduksi == '200-500 kg/bulan atau 801-1000 porsi/bulan'):
      skor = 20;
      break;
    case (jumlahKaryawan == '20-100 Pekerja' && kapasitasProduksi == '200-500 kg/bulan atau 801-1000 porsi/bulan'):
      skor = 20;
      break;
    case (jumlahKaryawan == '>100 Pekerja' && kapasitasProduksi == '200-500 kg/bulan atau 801-1000 porsi/bulan'):
      skor = 20;
      break;

    // =====================
    case (jumlahKaryawan == '1-2 Pekerja' && kapasitasProduksi == '>500 kg/bulan atau >1000 porsi/bulan'):
      skor = 20;
      break;
    case (jumlahKaryawan == '3-19 Pekerja' && kapasitasProduksi == '>500 kg/bulan atau >1000 porsi/bulan'):
      skor = 20;
      break;
    case (jumlahKaryawan == '20-100 Pekerja' && kapasitasProduksi == '>500 kg/bulan atau >1000 porsi/bulan'):
      skor = 20;
      break;
    case (jumlahKaryawan == '>100 Pekerja' && kapasitasProduksi == '>500 kg/bulan atau >1000 porsi/bulan'):
      skor = 20;
      break;

    // DEFAULT
    default:
      keterangan = 'Belum terisi';
      skor = 'Belum terisi';
  }


  if (skor == 5) 
    keterangan = 'Usaha Mikro';
  if (skor == 10)
    keterangan = "Usaha Kecil";
  if (skor == 15)
    keterangan = "Usaha Menengah";
  if (skor == 20)
    keterangan = "Usaha Besar";

  keteranganRisikoInput.val(keterangan);
  skorRisikoInput.val(skor);
  skorRisikoInput.trigger('change');
});
// End handle Section C


// Start handle Section D
$('#nilai_inspeksi_sebelumnya').on('input', function() {
  var nilai = $(this).val();
  var skorRisikoInput = $('#skor_risiko_sec_d');
  var keteranganLabel = $('.keterangan-label-sec-d');

  if (nilai === '') {
    // Jika nilai kosong, kosongkan skorRisikoInput dan keteranganLabel
    skorRisikoInput.val('');
    keteranganLabel.html('Skor Risiko (Keterangan: tinggi/sedang/rendah)');
  } else if (nilai >= 80) {
    skorRisikoInput.val(nilai);
    keteranganLabel.html('Skor Risiko <span class="badge bg-success">RENDAH</span>');
  } else if (nilai >= 60 && nilai <= 79) {
    skorRisikoInput.val(nilai);
    keteranganLabel.html('Skor Risiko <span class="badge bg-warning">SEDANG</span>');
  } else if (nilai < 60) {
    skorRisikoInput.val(nilai);
    keteranganLabel.html('Skor Risiko <span class="badge bg-danger">TINGGI</span>');
  }
  skorRisikoInput.trigger('change');
});
// End handle Section D


// Start handle Section hasil
$('#skor_risiko_sec_a, #skor_risiko_sec_b, #skor_risiko_sec_c, #skor_risiko_sec_d').on('change', function() {
  // Memanggil fungsi checkAndAddValidClass untuk masing-masing input
  // checkAndAddValidClass('skor_risiko_sec_a');
  // checkAndAddValidClass('skor_risiko_sec_b');
  // checkAndAddValidClass('skor_risiko_sec_c');
  // checkAndAddValidClass('skor_risiko_sec_d');
  // checkAndAddValidClass('hasil');

  var nilaiA = parseInt($('#skor_risiko_sec_a').val()) || 0;
  var nilaiB = parseInt($('#skor_risiko_sec_b').val()) || 0;
  var nilaiC = parseInt($('#skor_risiko_sec_c').val()) || 0;
  var nilaiD = parseInt($('#skor_risiko_sec_d').val()) || 0;
  var total;
  total = nilaiA + nilaiB + nilaiC + nilaiD;
  $('#hasil').val(total);

  let badgeColor;
  if (total < 35) {
    badgeColor = 'badge-success';
  } else if (total >= 35 && total <= 50) {
    badgeColor = 'badge-warning';
  } else if (total >= 55) {
    badgeColor = 'badge-danger';
  }

  $('#kategori-tpp').show();
  // Menambahkan badge ke dalam dokumen di bawah hasil persentase
  const badgeElement = $('<span>').addClass(`badge ${badgeColor}`).text(getRiskCategory(total));
  $('#kategoriTppBadgeContainer').empty().append(badgeElement);

  // Fungsi untuk mendapatkan kategori risiko
  function getRiskCategory(percentage) {
    if (percentage < 35) {
      return 'RISIKO RENDAH';
    } else if (percentage >= 35 && percentage <= 50) {
      return 'RISIKO SEDANG';
    } else if (percentage >= 55) {
      return 'RISIKO TINGGI';
    }
  }
});
// Start handle Section hasil


function checkAndAddValidClass(inputId) {
  var inputValue = $('#' + inputId).val().trim();

  if (inputValue !== '') {
    $('#' + inputId).addClass('is-valid');
  } else {
    $('#' + inputId).removeClass('is-valid');
  }
}


// TABEL DATA SAB =====================================================================================>
function tampilDaftarSab() {
  $.ajax({
    url: '/prl/sab_daftar',
    method: 'get',
    dataType: "json",
    success: function(data) {
      $(".prl_daftar_sab").html(data.data);
    },
    error: function(xhr, status, error) {
      console.error("AJAX error: " + status + ' - ' + error);
    }
  });
}

function tampilDaftarInspeksiSab(dateRange = '') {
  // Kirimkan data dateRange jika ada
  $.ajax({
    url: '/prl/sab_daftar_inspeksi',
    method: 'get',
    data: { dateRange: dateRange }, // Kirimkan dateRange jika ada
    dataType: "json",
    beforeSend: function() {
      $(".prl_daftar_inspeksi_sab").html('<p class="text-center text-muted">Memuat data...</p>');  
    },
    success: function(response) {
      if (response.data) {
        $(".prl_daftar_inspeksi_sab").html(response.data);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  });
}
// function tampilDaftarInspeksiSab() {
//   $.ajax({
//     url: '/prl/sab_daftar_inspeksi',
//     method: 'get',
//     dataType: "json",
//     success: function(data) {
//       $(".prl_daftar_inspeksi_sab").html(data.data);
//     },
//     error: function(xhr, status, error) {
//       console.error("AJAX error: " + status + ' - ' + error);
//     }
//   });
// }


// SUBMIT FORM SAB ====================================================================================>
$(document).on('submit', '#prlSimpanFormSab', function (e) {
  e.preventDefault();
  var formData = new FormData($(this)[0]);

  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {   
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/prl/sab";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});


$(document).on('submit', '#prlSimpanFormInspeksiSab', function (e) {
  e.preventDefault();
  var formData = new FormData($(this)[0]);

  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {   
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/prl/sab";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});


// FORM INSPEKSI SAB ==================================================================================>
// Menampilkan nama SAB berdasarkan wilayah kerja yang dipilih
function tampilSab() {
  $('#wilayah_kerja_sab').on('change', function() {
    var selectedWilker = $(this).val();
    var namaSabSelect = $('#nama_sab');
    var penanggungJawabInput = $('#penanggung_jawab');
    var sumberAirInput = $('#sumber_air');
    var kapasitasPenampunganInput = $('#kapasitas_penampungan');
    var namaPjInput = $('#nama_pj');
    var noHpPjInput = $('#nomor_hp_pj');
    var sumberAirInput1 = $('#sumber_air1');

    // Kosongkan nilai input
    namaSabSelect.empty().append('<option value="">-- Pilih Nama SAB --</option>');
    penanggungJawabInput.val('');
    sumberAirInput.val('');
    kapasitasPenampunganInput.val('');
    namaPjInput.val('');
    noHpPjInput.val('');
    sumberAirInput1.val('');

    if (selectedWilker) {
      $.ajax({
        type: "get",
          url: "/prl/get/sab/",
          dataType: "json",
          success: function(response) {
            for (let i=0; i < response.dataSab.length; i++){
              var idWilker = response.dataSab[i]['id_wilayah_kerja'];
              var idSab = response.dataSab[i]['id_prl_sab'];
              var namaSab = response.dataSab[i]['nama_sab'];

              if (idWilker == selectedWilker) {
                var optionAdded = false;
                option = '<option value="'+idSab+ '">'+namaSab+'</option>';
                optionAdded = true;
                namaSabSelect.append(option);
                if (!optionAdded) { // Jika tidak ada opsi yang cocok, tambahkan opsi kosong
                  namaSabSelect.append('<option value="" disabled>Tidak ada data</option>');
                }
              }
            }
          }
      });
    }
  });
}


// Lakukan Ajax request untuk mendapatkan data berdasarkan nama_sab yang dipilih
function tampilDetailSab() {
  $('#nama_sab').on('change', function() {
    var selectedSab = $(this).val();
    var penanggungJawabInput = $('#penanggung_jawab');
    var sumberAirInput = $('#sumber_air');
    var kapasitasPenampunganInput = $('#kapasitas_penampungan');
    var namaPjInput = $('#nama_pj');
    var noHpPjInput = $('#nomor_hp_pj');
    var sumberAirInput1 = $('#sumber_air1');

    // Kosongkan nilai input
    penanggungJawabInput.val('');
    sumberAirInput.val('');
    kapasitasPenampunganInput.val('');
    namaPjInput.val('');
    noHpPjInput.val('');
    sumberAirInput1.val('');

    if (selectedSab) {
        $.ajax({
        type: "get",
        url: "/prl/get/sab/",
        dataType: "json",
        data: { selectedSab: selectedSab },
        success: function(response) {
          if (response) {
            // Set data ke dalam inputan
            penanggungJawabInput.val(response.penanggungJawab);
            sumberAirInput.val(response.sumberAir);
            kapasitasPenampunganInput.val(response.kapasitasPenampungan);
            namaPjInput.val(response.penanggungJawab);
            noHpPjInput.val(response.penanggungJawabNohp);
            sumberAirInput1.val(response.sumberAir);
          } else {
            // Jika tidak ada data, munculkan pesan atau atur nilai menjadi kosong
            penanggungJawabInput.val('');
            sumberAirInput.val('');
            kapasitasPenampunganInput.val('');
            namaPjInput.val('');
            noHpPjInput.val('');
            sumberAirInput1.val('');
          }
        }
      });
    }
  });
}


$('input[type="radio"][id^="c"]').change(function() {
  updateTotalYa();
});


function updateTotalYa() {
  var totalYa = 0;

  // Menghitung total 'Ya' dari semua radio button yang dipilih dengan ID yang dimulai dengan 'c'
  $('input[type="radio"][id^="c"]:checked').each(function() {
    if ($(this).val() == 'Ya') {
      totalYa += 1;
    }
  });

  // Menghitung persentase dan menampilkannya pada input hasil
  var hasil = (totalYa / 10) * 100;
  $('#hasil').val(hasil.toFixed(0) + '%');

  // var inputValue = $('#hasil').val().trim();
  // if (inputValue !== '') {
  //   $('#hasil').addClass('is-valid');
  // } else {
  //   $('#hasil').removeClass('is-valid');
  // }

  // Menambahkan badge warna berdasarkan kriteria
  let badgeColor;
  if (hasil < 25) {
    badgeColor = 'badge-success';
  } else if (hasil >= 25 && hasil <= 50) {
    badgeColor = 'badge-warning';
  } else if (hasil > 50 && hasil <= 75) {
    badgeColor = 'badge-danger';
  } else {
    badgeColor = 'badge-dark';
  }
  $('#risiko-kontaminasi').show();

  // Menambahkan badge ke dalam dokumen di bawah hasil persentase
  const badgeElement = $('<span>').addClass(`badge ${badgeColor}`).text(getRiskCategory(hasil));
  $('#risikoBadgeContainer').empty().append(badgeElement);

  // Fungsi untuk mendapatkan kategori risiko
  function getRiskCategory(percentage) {
    if (percentage < 25) {
      return 'Rendah (R)';
    } else if (percentage >= 25 && percentage <= 50) {
      return 'Sedang (S)';
    } else if (percentage > 50 && percentage <= 75) {
      return 'Tinggi (T)';
    } else {
      return 'Amat Tinggi (AT)';
    }
  }
}


// TABEL DATA HSBU ====================================================================================>
function tampilDaftarHsbu() {
  $.ajax({
    url: '/prl/hsbu_daftar',
    method: 'get',
    dataType: "json",
    success: function(data) {
      $(".prl_daftar_hsbu").html(data.data);
    },
    error: function(xhr, status, error) {
      console.error("AJAX error: " + status + ' - ' + error);
    }
  });
}

function tampilDaftarInspeksiHsbu(dateRange = '') {
  // Kirimkan data dateRange jika ada
  $.ajax({
    url: '/prl/hsbu_daftar_inspeksi',
    method: 'get',
    data: { dateRange: dateRange }, // Kirimkan dateRange jika ada
    dataType: "json",
    beforeSend: function() {
      $(".prl_daftar_inspeksi_hsbu").html('<p class="text-center text-muted">Memuat data...</p>');  
    },
    success: function(response) {
      if (response.data) {
        $(".prl_daftar_inspeksi_hsbu").html(response.data);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  });
}
// function tampilDaftarInspeksiHsbu() {
//   $.ajax({
//     url: '/prl/hsbu_daftar_inspeksi',
//     method: 'get',
//     dataType: "json",
//     success: function(data) {
//       $(".prl_daftar_inspeksi_hsbu").html(data.data);
//     },
//     error: function(xhr, status, error) {
//       console.error("AJAX error: " + status + ' - ' + error);
//     }
//   });
// }


// SUBMIT FORM HSBU ===================================================================================>
$(document).on('submit', '#prlSimpanFormHsbu', function (e) {
  e.preventDefault();
  var formData = new FormData($(this)[0]);

  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {   
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/prl/hsbu";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});


$(document).on('submit', '#prlSimpanFormInspeksiHsbu', function (e) {
  e.preventDefault();
  var formData = new FormData($(this)[0]);

  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {   
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/prl/hsbu";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});


// FORM INSPEKSI HSBU ==================================================================================>
// Menampilkan nama HSBU berdasarkan wilayah kerja yang dipilih
function tampilHsbu() {
  $('#wilayah_kerja_hsbu').on('change', function() {
    var selectedWilker = $(this).val();
    var namaHsbuSelect = $('#nama_hsbu');

    // Kosongkan nilai input nama_tpp dan penanggung_jawab
    namaHsbuSelect.empty().append('<option value="">-- Pilih Nama Gedung/Bangunan --</option>');

    if (selectedWilker) {
      $.ajax({
        type: "get",
          url: "/prl/get/hsbu/",
          dataType: "json",
          success: function(response) {
            for (let i=0; i < response.dataHsbu.length; i++){
              var idWilker = response.dataHsbu[i]['id_wilayah_kerja'];
              var idHsbu = response.dataHsbu[i]['id_prl_hsbu'];
              var namaHsbu = response.dataHsbu[i]['nama_gedung'];
           
              if (idWilker == selectedWilker) {
                var optionAdded = false;
                option = '<option value="'+idHsbu+ '">'+namaHsbu+'</option>';
                optionAdded = true;
                namaHsbuSelect.append(option);
                if (!optionAdded) { // Jika tidak ada opsi yang cocok, tambahkan opsi kosong
                  namaHsbuSelect.append('<option value="" disabled>Tidak ada data</option>');
                }
              }
            }
          }
      });
    }
  });
}


// Lakukan Ajax request untuk mendapatkan data berdasarkan nama_hsbu yang dipilih
function tampilDetailHsbu() {
  $('#nama_hsbu').on('change', function() {
    var selectedHsbu = $(this).val();
    var penanggungJawabInput = $('#penanggung_jawab');
    var jumlahKaryawanInput = $('#jml_karyawan');

    // Kosongkan nilai input
    penanggungJawabInput.val('');
    jumlahKaryawanInput.val('');

    if (selectedHsbu) {
        $.ajax({
        type: "get",
        url: "/prl/get/hsbu/",
        dataType: "json",
        data: { selectedHsbu: selectedHsbu },
        success: function(response) {
          if (response) { // Set data ke dalam inputan
            penanggungJawabInput.val(response.penanggungJawab);
            jumlahKaryawanInput.val(response.jumlahKaryawan);
          } else { // Jika tidak ada data, munculkan pesan atau atur nilai menjadi kosong
            penanggungJawabInput.val('');
            jumlahKaryawanInput.val('');
          }
        }
      });
    }
  });
}


// Hitung skor bagian 1
$('#satu_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#satu_a_skor').val(totalSkor).trigger('change');
});

$('#satu_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#satu_b_skor').val(totalSkor).trigger('change');
});

$('#satu_c_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#satu_c_skor').val(totalSkor).trigger('change');
});

$('#satu_d_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#satu_d_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 2
$('#dua_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#dua_a_skor').val(totalSkor).trigger('change');
});

$('#dua_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#dua_b_skor').val(totalSkor).trigger('change');
});

$('#dua_c_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#dua_c_skor').val(totalSkor).trigger('change');
});

$('#dua_d_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#dua_d_skor').val(totalSkor).trigger('change');
});

$('#dua_e_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#dua_e_skor').val(totalSkor).trigger('change');
});

$('#dua_f_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#dua_f_skor').val(totalSkor).trigger('change');
});

$('#dua_g_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 6;
  $('#dua_g_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 3
$('#tiga_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#tiga_a_skor').val(totalSkor).trigger('change');
});

$('#tiga_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#tiga_b_skor').val(totalSkor).trigger('change');
});

$('#tiga_c_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#tiga_c_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 4
$('#empat_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#empat_a_skor').val(totalSkor).trigger('change');
});

$('#empat_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#empat_b_skor').val(totalSkor).trigger('change');
});

$('#empat_c_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#empat_c_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 5
$('#lima_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 15;
  $('#lima_a_skor').val(totalSkor).trigger('change');
});

$('#lima_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 15;
  $('#lima_b_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 6
$('#enam_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#enam_a_skor').val(totalSkor).trigger('change');
});

$('#enam_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#enam_b_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 7
$('#tujuh_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#tujuh_a_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 8
$('#delapan_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#delapan_a_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 9
$('#sembilan_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 8;
  $('#sembilan_a_skor').val(totalSkor).trigger('change');
});

$('#sembilan_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 8;
  $('#sembilan_b_skor').val(totalSkor).trigger('change');
});

$('#sembilan_c_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 8;
  $('#sembilan_c_skor').val(totalSkor).trigger('change');
});

$('#sembilan_d_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 8;
  $('#sembilan_d_skor').val(totalSkor).trigger('change');
});

$('#sembilan_e_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 8;
  $('#sembilan_e_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 10
$('#sepuluh_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 5;
  $('#sepuluh_a_skor').val(totalSkor).trigger('change');
});

$('#sepuluh_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 5;
  $('#sepuluh_b_skor').val(totalSkor).trigger('change');
});


// Hitung skor bagian 11
$('#sebelas_a_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#sebelas_a_skor').val(totalSkor).trigger('change');
});

$('#sebelas_b_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#sebelas_b_skor').val(totalSkor).trigger('change');
});

$('#sebelas_c_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#sebelas_c_skor').val(totalSkor).trigger('change');
});

$('#sebelas_d_nilai').on('input', function() {
  var nilai = $(this).val();
  var totalSkor = nilai * 10;
  $('#sebelas_d_skor').val(totalSkor).trigger('change');
});


function updateTotal() {
  var totalSkor = 0;

  $('[id$="_skor"]').each(function() {
    totalSkor += parseFloat($(this).val()) || 0;
  });
  $('#total').val(totalSkor); // Tampilkan hasil dalam input total

  // Tentukan keterangan berdasarkan nilai totalSkor
  var keterangan = '';
  var badgeClass = '';

  if (totalSkor > 712.5) {
    keterangan = 'Rendah (R)';
    badgeClass = 'badge-success';
  } else if (totalSkor > 475 && totalSkor < 712.5) {
    keterangan = 'Sedang (S)';
    badgeClass = 'badge-warning';
  } else {
    keterangan = 'Tinggi (T)';
    badgeClass = 'badge-danger';
  }

  $('#keteranganBadge').html('<span class="badge ' + badgeClass + '">' + keterangan + '</span>');
  $('#keterangan').show();
}

$('[id$="_skor"]').on('change', updateTotal);