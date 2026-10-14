
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('infografisForm');

    // Menangani klik tombol "pdf"
    form.querySelector('button[name="pdf"]').addEventListener('click', function () {
        form.action = '/infografis/pdf';
    });

    // Menangani klik tombol "excel"
    form.querySelector('button[name="excel"]').addEventListener('click', function () {
        form.action = '/infografis/excel';
    });
});

const dataHTMLDashboard = $('.dashboard').html()
$(function() {
  cekInput()
});

// Define the animation function
function scaleAnimation(element) {
  $(element).animate({
    opacity: 0,
    transform: 'scale(2.5)'
  }, {
    duration: 2000,
    easing: 'cubic-bezier(0, 0, 0.49, 1.02)',
    complete: function() {
      $(element).css({
        opacity: 0.7,
        transform: 'scale(1)'
      });
      scaleAnimation(element);
    }
  });
}

$(".select2").change(function() {
  cekInput();
});

$(".date-dashboard").change(function() {
  cekInput();
});

function cekInput() {
  if ($("#data-dashboard").val() !== '') {
    if ($("#data-dashboard").val() == "infografis") {
      $(".dashboard-filter").hide()
      $("#infografis-filter").show()
      var wilker = $("#wilkerInfografis-dashboard").val()
      var jenis = $("#jenisInfografis-dashboard").val()
      var periode = $("#periodeInfografis-dashboard").val()
      infografis(wilker, jenis, periode)
    }
    if ($("#data-dashboard").val() == "sipepes") {
      $(".dashboard-filter").hide()
      sipepes()
    }
    if ($("#data-dashboard").val() == "sipedit") {
      $(".dashboard-filter").hide()
      $("#sipedit-filter").show()
      
      var periode = $("#periodeSipedit-dashboard").val()
      var bulan = $("#bulanSipedit-dashboard").val()
      sipedit(periode, bulan)
    }
    if ($("#data-dashboard").val() == "cuti") {
      $(".dashboard-filter").hide()

      sicute()
    }
    
    if ($("#data-dashboard").val() == "sinadi") {
      $(".dashboard-filter").hide()

      sinadi()
    }
    if ($("#data-dashboard").val() == "relaxtime") {
      $(".dashboard-filter").hide()

      relaxtime()
    }
  } else {
    $(".dashboard").html(dataHTMLDashboard);
    $(".dashboard-filter").hide()
  }
}



function infografis(wilker, jenis, periode) {
  $.ajax({
    url: `/home/infografis/${wilker}/${jenis}/${periode}`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.dashboard').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $('.dashboard img').css({
        display: 'block',
        margin: '0 auto'
      });
    },
    success: function(response) {
      if (response.data) {
        $(".dashboard").html(response.data);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      // alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Oops... ERROR SINTAKS',
        text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
        // text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
      })
    }
  });
}
function sipedit(periode, bulan) {
  if (periode == "all") {
    $("#bulanSipedit-dashboard").prop('disabled', true);
  } else {
    $("#bulanSipedit-dashboard").prop('disabled', false);
  }
  $.ajax({
    url: `/home/sipedit/${periode}/${bulan}`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.dashboard').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $('.dashboard img').css({
        display: 'block',
        margin: '0 auto'
      });
    },
    success: function(response) {
      if (response.data) {
        // console.log(response.cek)
        $(".dashboard").html(response.data);
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
}
function sipepes() {
  $.ajax({
    url: `/home/sipepes`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.dashboard').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $('.dashboard img').css({
        display: 'block',
        margin: '0 auto'
      });
    },
    success: function(response) {
      if (response.data) {
        $(".dashboard").html(response.data);
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
}
function sicute() {
  $.ajax({
    url: `/home/sicute`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.dashboard').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $('.dashboard img').css({
        display: 'block',
        margin: '0 auto'
      });
    },
    success: function(response) {
      if (response.data) {
        $(".dashboard").html(response.data);
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
}
function sinadi() {
  $.ajax({
    url: `/home/sinadi`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.dashboard').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $('.dashboard img').css({
        display: 'block',
        margin: '0 auto'
      });
    },
    success: function(response) {
      if (response.data) {
        $(".dashboard").html(response.data);
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
}
function relaxtime() {
  $.ajax({
    url: `/home/relaxtime`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.dashboard').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $('.dashboard img').css({
        display: 'block',
        margin: '0 auto'
      });
    },
    success: function(response) {
      if (response.data) {
        $(".dashboard").html(response.data);
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
}