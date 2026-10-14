document.addEventListener('DOMContentLoaded', function() {
  // Temukan elemen dengan class 'pushmenu' dan lakukan klik
  var pushmenuElement = document.querySelector('.pushmenu');

  if (pushmenuElement) {
    pushmenuElement.click();
  }
});

$(document).ready(function () { 
  var sekarang = new Date();
  var tahunSekarang = sekarang.getFullYear();

  getPaguRelaxtime(tahunSekarang)
});

function getPaguRelaxtime(tahunSekarang) {
  
  $.ajax({
    url: `/relaxtime/ajax/getPagu/${tahunSekarang}`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.relaxtime-pagu').html(
        '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>'
      );
    },
    success: function(response) {
      if (response.data) {
        $(".relaxtime-pagu").html(response.data);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      // alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Oops... ERROR SINTAKS',
        // text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
        text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
      })
    }
  });

  setTimeout(() => {
    var relaxtimeHapusElement = $(".overlay-wrapper").find(".overlay");
    relaxtimeHapusElement.remove();
  }, 5000);
}

$(document).on('click', '.relaxtime-realisasi-button-formulir', function(){
    
  var nilai = $(".relaxtime-realisasi-formulir").find(".relaxtime-anggaran-realisasi").val();

  var angkaSaja = nilai.replace(/\D/g, '');
  // Mengonversi hasilnya menjadi bilangan bulat
  var angkaInt = parseInt(angkaSaja, 10);

  if(angkaInt === "" || angkaInt === null || nilai === "Rp" || nilai === ""){    
  }
  else{
    
    var formData = $('.relaxtime-realisasi-formulir').serialize();
    
    $.ajax({
      url: $('.relaxtime-realisasi-formulir').attr('action'),
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(".relaxtime-realisasi-button-formulir").attr('disabled', true);
        $(".relaxtime-realisasi-button-formulir").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
    },
      success: function(response) {
        if (response.data) {
          Toast.fire({
            icon: 'success',
            title: 'Data berhasil disimpan...'
          })
          var tahun = $('select[name="pagu-list-relaxtime"]').val()
          getDataRealisasiRelaxtime(tahun)
          
          $(".btn.btn-default.btn-close-modal").trigger("click");
        } else {
          Toast.fire({
            icon: 'error',
            title: 'Data gagal disimpan!.'
          })
        }
        $(".relaxtime-realisasi-button-formulir").attr('disabled', false)
        $(".relaxtime-realisasi-button-formulir").text('Simpan Perubahan')
      },error: function(xhr, ajaxOptions, thrownError) {
        Swall.fire({
            text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        });
      }
    });
  }
});
$(document).on('click', '.relaxtime-realisasi-button-formulir-edit', function(){
  var nilai = $(".relaxtime-realisasi-formulir-edit").find(".relaxtime-anggaran-realisasi").val();

  var angkaSaja = nilai.replace(/\D/g, '');
  // Mengonversi hasilnya menjadi bilangan bulat
  var angkaInt = parseInt(angkaSaja, 10);

  if(angkaInt === "" || angkaInt === null || nilai === "Rp" || nilai === ""){    
  }
  else{
    var formData = $('.relaxtime-realisasi-formulir-edit').serialize();
    
    $.ajax({
      url: $('.relaxtime-realisasi-formulir-edit').attr('action'),
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $(".relaxtime-realisasi-button-formulir-edit").attr('disabled', true);
        $(".relaxtime-realisasi-button-formulir-edit").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
    },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            title: 'Data berhasil disimpan...'
          })
          var tahun = $('select[name="pagu-list-relaxtime"]').val()
          getDataRealisasiRelaxtime(tahun)
          $(".btn.btn-default.btn-close-modal").trigger("click");
        } else if(response.data == "tidak ada perubahan") {
          Toast.fire({
            icon: 'warning',
            title: 'Tidak ada perubahan data!'
          })
        } else {
          Toast.fire({
            icon: 'error',
            title: 'Data gagal disimpan!.'
          })
        }
        $(".relaxtime-realisasi-button-formulir-edit").attr('disabled', false)
        $(".relaxtime-realisasi-button-formulir-edit").text('Simpan Perubahan')
      },
    });
  }
});

$(document).on("input", ".relaxtime-anggaran-realisasi", function() {
  relaxtimeFormatAngka(this);
});

$(document).on('submit', '.relaxtime-realisasi-formulir, .relaxtime-realisasi-formulir-edit', function(e) {
  e.preventDefault();
})


function relaxtimeFormatAngka(e) { 
  // Mengambil nilai inputan
  var val = $(e).val();
  // Menghilangkan karakter selain angka dan koma
  val = val.replace(/[^0-9,]/g, '');
  // Memformat angka dengan menambahkan titik pada setiap ribuan
  val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
  // Menampilkan nilai yang sudah diformat kembali ke inputan
  $(e).val("Rp" + val);
}

$(document).on('click', '.btn-realisasi-detail-anggaran', function() {
  var dataId = $(this).attr("data-id");
  var dataKRO = $(this).attr("data-kro")
  var dataKomponen = $(this).attr("data-komponen")
  var dataKomponenDeskripsi = $(this).attr("data-komponen-deskripsi")
  var dataSubKomponen = $(this).attr("data-sub-komponen")
  var dataSubKomponenDeskripsi = $(this).attr("data-sub-komponen-deskripsi")
  var dataUraian = $(this).attr("data-uraian")
  var dataUraianDeskripsi = $(this).attr("data-uraian-deskripsi")
  var dataDetailUraianDeskripsi = $(this).attr("data-uraian-deskripsi")

  $('.relaxtime-anggaran-realisasi').val("");
  $('.date-today').val("");

  $(".relaxtime-anggaran-realisasi-id").val(dataId)
  $("#relaxtime-kro-modal").text(dataKRO)
  $("#relaxtime-komponen-modal").text(dataKomponen)
  $("#relaxtime-komponen-deskripsi-modal").text(dataKomponenDeskripsi)
  $("#relaxtime-sub-komponen-modal").text(dataSubKomponen)
  $("#relaxtime-sub-komponen-deskripsi-modal").text(dataSubKomponenDeskripsi)
  $("#relaxtime-uraian-modal").html(dataUraian +"&emsp;"+ dataUraianDeskripsi)
  $("#relaxtime-detail-uraian-modal").text(dataDetailUraianDeskripsi)

  $.ajax({
    url: `/relaxtime/getSisaAnggaran/${dataId}`,
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $(".sisa-anggaran-pagu-relaxtime").text('memuat sisa pagu...');
    },
    success: function(response) {
      var sisa = parseFloat(response).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
      if(response < 0){ 
        $(".sisa-anggaran-pagu-relaxtime").html(`Sisa anggaran <span class='text-danger'>Rp${sisa} </span>`);
      } else {
        $(".sisa-anggaran-pagu-relaxtime").html(`Sisa anggaran <span class='text-success'>Rp${sisa} </span>`);
      }
    },error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: 'error',
        text: 'Sisa anggaran tidak dapat dimuat!'
      })
    }
  });

})
$(document).on('click', '.btn-realisasi-detail-anggaran-edit', function(){
  var dataId = $(this).attr("data-id");
  var dataSegment = $(this).attr("data-segment");
  
  
  $.ajax({
    url: `/relaxtime/getDataPagu/${dataSegment}/${dataId}`,
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $("#overlay").show()
  },
    success: function(response) {
      if (response.data == 'sukses') {        
        $('.deskripsi-kode-relaxtime').text('')
        $('input').val('')

        if(dataSegment == "rkt"){
          $('input').prop('disabled', true).parent().hide()
          $('input[name="id"]').val(response.value.rkt['id_relaxtime_rkt'])
          $('input[name="kode"]').val(response.value.rkt['kode_rkt']).prop('disabled', false).parent().show()
          $('input[name="uraian"]').val(response.value.rkt['deskripsi_rkt']).prop('disabled', false).parent().show()
        } else if(dataSegment == "kro") {
          $('input').prop('disabled', true).parent().hide()
          $('input[name="id"]').val(response.value.kro['id_relaxtime_kro'])
          $('input[name="digit_kode"]').val(response.value.kro['digit_kro']).prop('disabled', false).parent().show()
          $('input[name="digit_uraian"]').val(response.value.kro['detail_digit_kro']).prop('disabled', false).parent().show()
          $('input[name="kode"]').val(response.value.kro['kode_kro']).prop('disabled', false).parent().show()
          $('input[name="uraian"]').val(response.value.kro['deskripsi_kro']).prop('disabled', false).parent().show()
        } else if(dataSegment == "ro") {
          $('.deskripsi-kode-relaxtime').html('Deskripsi Kode : <span class="text-warning">'+ response.value.ro['digit_kro']+'.'+response.value.ro['kode_kro']+'</span>.'+response.value.ro['kode_relaxtime_ro'])
          $('input').prop('disabled', true).parent().hide()
          $('input[name="id"]').val(response.value.ro['id_relaxtime_ro'])
          $('input[name="kode"]').val(response.value.ro['kode_relaxtime_ro']).prop('disabled', false).parent().show()
          $('input[name="uraian"]').val(response.value.ro['deskripsi_ro']).prop('disabled', false).parent().show()
          $('input[name="vol"]').val(response.value.ro['vol_ro']).prop('disabled', false).parent().show()
          $('input[name="sat"]').val(response.value.ro['sat_ro']).prop('disabled', false).parent().show()
          $('input[name="tahun"]').val(response.value.ro['tahun']).prop('disabled', true).parent().show()
        } else if(dataSegment == "komponen") {
          $('.deskripsi-kode-relaxtime').html('Deskripsi Kode : <span class="text-warning">'+ response.value.komponen['digit_kro']+'.'+response.value.komponen['kode_kro']+"."+response.value.komponen['kode_relaxtime_ro'] + '</span>.'+response.value.komponen['kode_komponen'])
          $('input').prop('disabled', true).parent().hide()
          $('input[name="id"]').val(response.value.komponen['id_relaxtime_komponen'])
          $('input[name="kode"]').val(response.value.komponen['kode_komponen']).prop('disabled', false).parent().show()
          $('input[name="uraian"]').val(response.value.komponen['deskripsi_komponen']).prop('disabled', false).parent().show()
        } else if(dataSegment == "sub-komponen") {
          $('.deskripsi-kode-relaxtime').html('Deskripsi Kode : <span class="text-warning">'+ response.value.komponen['digit_kro']+'.'+response.value.komponen['kode_kro']+"."+response.value.komponen['kode_relaxtime_ro'] + "."+response.value.komponen['kode_komponen']+ '</span>.'+response.value.komponen['kode_sub_komponen'])
          $('input').prop('disabled', true).parent().hide()
          $('input[name="id"]').val(response.value.komponen['id_relaxtime_komponen'])
          $('input[name="kode"]').val(response.value.komponen['kode_sub_komponen']).prop('disabled', false).parent().show()
          $('input[name="uraian"]').val(response.value.komponen['deskripsi_sub_komponen']).prop('disabled', false).parent().show()
        } else if(dataSegment == "uraian") {
          $('.deskripsi-kode-relaxtime').html('Deskripsi Kode : <span class="text-warning">'+ response.value.uraian['digit_kro']+'.'+response.value.uraian['kode_kro']+"."+response.value.uraian['kode_relaxtime_ro'] + "."+response.value.uraian['kode_komponen']+"."+response.value.uraian['kode_sub_komponen']+ '</span>.'+response.value.uraian['kode_uraian'])
          $('input').prop('disabled', true).parent().hide()
          $('input[name="id"]').val(response.value.uraian['id_relaxtime_uraian'])
          $('input[name="kode"]').val(response.value.uraian['kode_uraian']).prop('disabled', false).parent().show()
          $('input[name="uraian"]').val(response.value.uraian['deskripsi_uraian']).prop('disabled', false).parent().show()
        } else if(dataSegment == "detil") {
          $('.deskripsi-kode-relaxtime').html('Deskripsi Kode : <span class="text-warning">'+ response.value.uraian['digit_kro']+'.'+response.value.uraian['kode_kro']+"."+response.value.uraian['kode_relaxtime_ro'] + "."+response.value.uraian['kode_komponen']+"."+response.value.uraian['kode_sub_komponen']+'.'+response.value.uraian['kode_uraian']+ '</span>')
          $('input').prop('disabled', true).parent().hide()
          $('input[name="id"]').val(response.value.uraian['id_relaxtime_detail_uraian'])
          $('input[name="uraian"]').val(response.value.uraian['deskripsi_detail_uraian']).prop('disabled', false).parent().show()
          $('input[name="vol"]').val(response.value.uraian['vol_detail_uraian']).prop('disabled', false).parent().show()
          $('input[name="sat"]').val(response.value.uraian['sat_detail_uraian']).prop('disabled', false).parent().show()
          $('input[name="harga"]').val(response.value.uraian['harga_detail_uraian']).prop('disabled', false).parent().show()
          $('input[name="jumlah"]').val(response.value.uraian['jumlah_detail_uraian']).prop('disabled', false).parent().show()
          $('input[name="tahun"]').val(response.value.uraian['tahun']).prop('disabled', true).parent().show()
        }
        $('input[name="id"]').prop('disabled', false);
        $('input[name="segment"]').prop('disabled', false).val(dataSegment);
        $('#relaxtime-edit-realisasi-modal').modal('show');
      } else {
        Swal.fire({
          position: 'top-end',
          icon: response.icon,
          title: response.title,
          text: response.text,
          // text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
        })
      }
      $("#overlay").hide()
    }, error: function(xhr, ajaxOptions, thrownError) {
      $("#overlay").hide()
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Oops... ERROR SINTAKS',
        // text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
        text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
      })
    }
  });

})

$(document).on('click', '.btn-edit-realisasi-detail-anggaran', function() {
  var dataId = $(this).attr("data-id");
  var dataKRO = $(this).attr("data-kro")
  var dataKomponen = $(this).attr("data-komponen")
  var dataKomponenDeskripsi = $(this).attr("data-komponen-deskripsi")
  var dataSubKomponen = $(this).attr("data-sub-komponen")
  var dataSubKomponenDeskripsi = $(this).attr("data-sub-komponen-deskripsi")
  var dataUraian = $(this).attr("data-uraian")
  var dataUraianDeskripsi = $(this).attr("data-uraian-deskripsi")
  var dataDetailUraianDeskripsi = $(this).attr("data-uraian-deskripsi")
  var dataTanggal = $(this).attr("data-tanggal")
  var dataRealisasi = $(this).attr("data-realisasi")

  // Mengambil nilai inputan
  var val = dataRealisasi;
  // Menghilangkan karakter selain angka dan koma
  val = val.replace(/\./g, ',');
  val = val.replace(/[^0-9,]/g, '');
  // Memformat angka dengan menambahkan titik pada setiap ribuan
  val = val.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
  // Menampilkan nilai yang sudah diformat kembali ke inputan

  $('.relaxtime-anggaran-realisasi').val("Rp" + val);
  $('.date-today').val(dataTanggal);

  $(".relaxtime-anggaran-realisasi-id").val(dataId)
  $("#relaxtime-kro-modal-edit").text(dataKRO)
  $("#relaxtime-komponen-modal-edit").text(dataKomponen)
  $("#relaxtime-komponen-deskripsi-modal-edit").text(dataKomponenDeskripsi)
  $("#relaxtime-sub-komponen-modal-edit").text(dataSubKomponen)
  $("#relaxtime-sub-komponen-deskripsi-modal-edit").text(dataSubKomponenDeskripsi)
  $("#relaxtime-uraian-modal-edit").html(dataUraian +"&emsp;"+ dataUraianDeskripsi)
  $("#relaxtime-detail-uraian-modal-edit").text(dataDetailUraianDeskripsi)

  
  $.ajax({
    url: `/relaxtime/getSisaAnggaranEdit/${dataId}`,
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $(".sisa-anggaran-pagu-relaxtime").text('memuat sisa pagu...');
    },
    success: function(response) {
      var sisa = parseFloat(response).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
      if(response < 0){ 
        $(".sisa-anggaran-pagu-relaxtime").html(`Sisa anggaran <span class='text-danger'>Rp${sisa} </span>`);
      } else {
        $(".sisa-anggaran-pagu-relaxtime").html(`Sisa anggaran <span class='text-success'>Rp${sisa} </span>`);
      }
    },error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: 'error',
        text: 'Sisa anggaran tidak dapat dimuat!'
      })
    }
  });
})
$(document).on("change", 'input[name="file"]', function(){
  var fileName = $(this).val();
  if (fileName.length > 0) {
      var validExtensions = ['.xls', '.xlsx'];
      var fileExtension = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();

      if ($.inArray(fileExtension, validExtensions) === -1) {
          // File bukan file Excel
          $(this).val('');
          $(".custom-file-label").text("Lampirkan file pagu (Sesuaikan format file)");
          $(".btn-relaxtime-pagu").prop('disabled', true);
      } else {
          // File adalah file Excel
          $(".btn-relaxtime-pagu").prop('disabled', false); 
      }
  }
});

$(document).on("submit", '.form-relaxtime-import-pagu', function(e) {
  e.preventDefault();

  var formData = new FormData(this);
  
  $.ajax({
    url: $(this).attr('action'),
    data: formData,
    method: 'post',
    dataType: 'json',
    contentType: false, // Tidak mengatur tipe konten
    processData: false, // Tidak memproses data
    beforeSend: function() {
      $(".btn-relaxtime-pagu").attr('disabled', true);
      $(".btn-relaxtime-pagu").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
  },
    success: function(response) {

      Swal.fire({
        position: 'top-end',
        title: response.title,
        icon: response.icon,
        text: response.pesan,
      })
      if(response.icon == "success") {
        var sekarang = new Date();
        var tahunSekarang = sekarang.getFullYear();
        getPaguRelaxtime(tahunSekarang)
      }

      $(".btn-relaxtime-pagu").attr('disabled', false)
      $(".btn-relaxtime-pagu").html('<i class="fas fa-upload"></i> Import')
    }, error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Oops... ERROR SINTAKS',
        // text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
        text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
      })
    }
  });
})
$(document).on('change', 'select[name="pagu-list-relaxtime"]', function() {
  var tahun = $(this).val();

  getPaguRelaxtime(tahun)  
  getDataRealisasiRelaxtime(tahun)
})

$(document).on("click", ".edit-relaxtime-page-pagu", function() {
  // Temukan elemen parent dengan class "row" dari tombol yang diklik
  var rowElement = $(this).closest('.row');

  // Temukan elemen <select> di dalam elemen parent
  var selectElement = rowElement.find('select[name="pagu-list-relaxtime-edit-tahun"]');
  var inputElement = rowElement.find('input[name="kode"]');

  // Ambil nilai dari elemen <select>
  var selectedValue = selectElement.val();
  var inputValue = inputElement.val();

  // Lakukan sesuatu dengan nilai yang diambil
  $("#overlay").show()
  window.location.href = `/relaxtime/edit/${inputValue}/${selectedValue}`;
});

$(document).on('submit', '.relaxtime-update-pagu', function(e){
  e.preventDefault()
  var formData = $('.relaxtime-update-pagu').serialize();
  
  $.ajax({
    url: $(this).attr('action'),
    data: formData,
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      $("#overlay").show()
  },
    success: function(response) {
      if (response.icon == "success") {
        $('#relaxtime-edit-realisasi-modal').modal('hide');
      }
      Swal.fire({
        position: 'top-end',
        icon: response.icon,
        title: response.title,
        text: response.text,
        // text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
      })
      $("#overlay").hide()
    }, error: function(xhr, ajaxOptions, thrownError) { 
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Oops... ERROR SINTAKS',
        text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
        // text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
      })
    }
  });
})