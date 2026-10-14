
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
let sipeditXhr = null;
let sipeditActiveTab = 'dashboard-pelatihan';

$(function() {
  cekInput()
});

$(document).on('shown.bs.tab shown.bs.tab', 'a[data-toggle="tab"]', function(e) {
  var target = $(e.target).attr('href') || '';
  if (target === '#dashboard-pelatihan' || target === '#dashboard-jafung') {
    sipeditActiveTab = target.replace('#', '');
  }
});

$(document).on('change', '.select2', function() {
  if (
    this.id === 'periodeSipedit-dashboard-inner' ||
    this.id === 'bulanSipedit-dashboard-inner'
  ) {
    var periode = sipeditPeriodeValue();
    var bulan = sipeditBulanValue();
    sipedit(periode, bulan);
    return;
  }
  cekInput();
});

$(document).on('change', '.date-dashboard', function() {
  cekInput();
});

function sipeditPeriodeValue() {
  var inner = $('#periodeSipedit-dashboard-inner').val();
  if (inner !== undefined && inner !== null && inner !== '') return inner;
  var outer = $('#periodeSipedit-dashboard').val();
  if (outer !== undefined && outer !== null && outer !== '') return outer;
  return String(new Date().getFullYear());
}

function sipeditBulanValue() {
  var inner = $('#bulanSipedit-dashboard-inner').val();
  if (inner !== undefined && inner !== null && inner !== '') return inner;
  var outer = $('#bulanSipedit-dashboard').val();
  if (outer !== undefined && outer !== null && outer !== '') return outer;
  return String(new Date().getMonth() + 1);
}

function initSipeditSelect2() {
  $('#periodeSipedit-dashboard-inner, #bulanSipedit-dashboard-inner').each(function() {
    if (!$(this).hasClass('select2-hidden-accessible')) {
      $(this).select2({ width: '100%', minimumResultsForSearch: -1 });
    }
  });
}

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
      
      var periode = sipeditPeriodeValue()
      var bulan = sipeditBulanValue()
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
  var disabled = periode == "all";
  $("#bulanSipedit-dashboard").prop('disabled', disabled);
  $("#bulanSipedit-dashboard-inner").prop('disabled', disabled);

  if (sipeditXhr && sipeditXhr.readyState !== 4) {
    sipeditXhr.abort();
  }

  sipeditXhr = $.ajax({
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
        initSipeditSelect2();

        var targetAnchor = $(`a[href="#${sipeditActiveTab}"]`);
        if (targetAnchor.length) {
          targetAnchor.tab('show');
        } else {
          $('a[href="#dashboard-pelatihan"]').tab('show');
        }
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