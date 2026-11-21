
function dataCuti() {
  $.ajax({
    url: "/cuti/ambil_data_cuti",
    dataType: "json",
    success: function(response) {

      $(".view-data-cuti-baru").html(response.data);
      if (response.count > 0) {
        $('.view-jumlah-data-cuti-baru').text(response.count)
        $('.setValidationCuti').removeClass('d-none')
        cekValidasiCuti()
      } else if (response.skema) {
        $('.setValidationCuti').removeClass('d-none')
      } else {
        $('.view-jumlah-data-cuti-baru').text('')
        $('.setValidationCuti').addClass('d-none')
      }
    },
  });
}

function dataPermohonan() {
  $.ajax({
    url: "/cuti/ambil_data_permohonan",
    dataType: "json",
    success: function(response) {

      $(".view-data-permohonan-baru").html(response.count);
      if (response.count > 0) {
        $('.view-jumlah-data-permohonan-baru').text(response.count)
        $('.setValidationPermohonan').removeClass('d-none')
        cekValidasiCuti()
      } else if (response.skema) {
        $('.setValidationCuti').removeClass('d-none')
      } else {
        $('.view-jumlah-data-permohonan-baru').text('')
        $('.setValidationPermohonan').addClass('d-none')
      }
    },
  });
}

function getData() {
  var jml1, jml2;
  var promise1 = $.ajax({
    url: "/cuti/ambil_data_cuti",
    dataType: "json",
    success: function(response) {

      $(".view-data-cuti-baru").html(response.data);
      jml1 = response.count
      if (response.count > 0) {
        $('.view-jumlah-data-cuti-baru').text(response.count)
        $('.setValidationCuti').removeClass('d-none')
        cekValidasiCuti()
      } else if (response.skema) {
        $('.setValidationCuti').removeClass('d-none')
      } else {
        $('.view-jumlah-data-cuti-baru').text('')
        $('.setValidationCuti').addClass('d-none')
      }
    },
  });
  var promise2 = $.ajax({
    url: "/cuti/ambil_data_permohonan",
    dataType: "json",
    success: function(response) {

      $(".view-data-permohonan-baru").html(response.data);
      jml2 = response.count
      if (response.count > 0) {
        $('.view-jumlah-data-permohonan-baru').text(response.count)
        $('.setValidationPermohonan').removeClass('d-none')
        cekValidasiCuti()
      } else if (response.skema) {
        $('.setValidationCuti').removeClass('d-none')
      } else {
        $('.view-jumlah-data-permohonan-baru').text('')
        $('.setValidationPermohonan').addClass('d-none')
      }
    },
  });
  Promise.all([promise1, promise2]).then(function() {
    var hasil = jml1 + jml2
    if (hasil > 0) {
      $('.view-jumlah-data-baru').text(hasil)
    } else {
      $('.view-jumlah-data-baru').text('')
    }
  });
}

function cekValidasiCuti() {
  $('.setValidationCuti-Permohonan').html('<li class="nav-header">Validator</li>')
}
$(document).ready(function() {
  // Hanya jalankan polling cuti jika elemen target SICUTE benar-benar ada di DOM
  var hasCutiTargets = $('.view-data-cuti-baru, .view-data-permohonan-baru, .view-jumlah-data-baru, .setValidationCuti, .setValidationPermohonan').length > 0;
  if (hasCutiTargets) {
    getData();
    setInterval(function() {
      if (!document.hidden) {
        getData();
      }
    }, 30000);
  }

  if ($('.lds-ring').length > 0 || $('.previewPDF').length > 0) {
    var pdfRevealTimer = setInterval(function() {
      $(".lds-ring").css('display', 'none');
      $(".previewPDF").css('display', 'block');
      clearInterval(pdfRevealTimer);
    }, 500);
  }
});