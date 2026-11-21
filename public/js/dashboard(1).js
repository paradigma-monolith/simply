
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('infografisForm');
    if (!form) {
        return;
    }

    const pdfButton = form.querySelector('button[name="pdf"]');
    if (pdfButton) {
        pdfButton.addEventListener('click', function () {
            form.action = '/infografis/pdf';
        });
    }

    const excelButton = form.querySelector('button[name="excel"]');
    if (excelButton) {
        excelButton.addEventListener('click', function () {
            form.action = '/infografis/excel';
        });
    }
});

const dataHTMLDashboard = $('.dashboard').html();
let activeDashboardXhr = null;
let sipeditActiveTab = 'dashboard-pelatihan';

function renderDashboardSkeleton() {
  return (
    '<div class="container-fluid"><div class="dm-skel-wrap">' +
      '<div class="dm-skel mb-3" style="width: 240px; height: 22px;"></div>' +
      '<div class="row mb-3">' +
        '<div class="col-md-3 col-6 mb-2"><div class="dm-skel" style="height: 86px;"></div></div>' +
        '<div class="col-md-3 col-6 mb-2"><div class="dm-skel" style="height: 86px;"></div></div>' +
        '<div class="col-md-3 col-6 mb-2"><div class="dm-skel" style="height: 86px;"></div></div>' +
        '<div class="col-md-3 col-6 mb-2"><div class="dm-skel" style="height: 86px;"></div></div>' +
      '</div>' +
      '<div class="dm-skel" style="height: 220px;"></div>' +
    '</div></div>'
  );
}

function abortActiveDashboardRequest() {
  if (activeDashboardXhr && activeDashboardXhr.readyState !== 4) {
    activeDashboardXhr.abort();
  }
  activeDashboardXhr = null;
}

function loadDashboardModule(url, onSuccess, customErrorText) {
  abortActiveDashboardRequest();

  activeDashboardXhr = $.ajax({
    url: url,
    dataType: "json",
    beforeSend: function() {
      $('.dashboard').html(renderDashboardSkeleton());
    },
    success: function(response) {
      if (response && response.data) {
        $(".dashboard").html(response.data);
        if (typeof onSuccess === 'function') {
          onSuccess(response);
        }
      }
    },
    error: function(xhr, status, thrownError) {
      if (status === 'abort') {
        return;
      }
      Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: 'Gagal Memuat Data',
        text: customErrorText || (xhr.status + "\n" + thrownError)
      });
    },
    complete: function() {
      activeDashboardXhr = null;
    }
  });
}

$(function() {
  $(".dashboard-filter").hide();
  if ($("#data-dashboard").val() !== '') {
    cekInput();
  }
});

// Klik kartu cepat di tampilan awal dashboard untuk langsung membuka modul
$(document).on('click', '.dashboard-quick-card', function() {
  var mod = $(this).data('module');
  if (mod) {
    $('#data-dashboard').val(mod).trigger('change');
  }
});

$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function(e) {
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
  if ($(this).closest('.dashboard').length || $('#data-dashboard').val() !== 'infografis') {
    return;
  }
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

function cekInput() {
  var selected = $("#data-dashboard").val();
  if (selected !== '') {
    $(".dashboard-filter").hide();
    if (selected === "infografis") {
      $("#infografis-filter").show();
      var wilker = $("#wilkerInfografis-dashboard").val();
      var jenis = $("#jenisInfografis-dashboard").val();
      var periode = $("#periodeInfografis-dashboard").val();
      infografis(wilker, jenis, periode);
    } else if (selected === "sipepes") {
      sipepes();
    } else if (selected === "sipedit") {
      $("#sipedit-filter").show();
      sipedit(sipeditPeriodeValue(), sipeditBulanValue());
    } else if (selected === "cuti") {
      sicute();
    } else if (selected === "sinadi") {
      sinadi();
    } else if (selected === "relaxtime") {
      relaxtime();
    }
  } else {
    abortActiveDashboardRequest();
    $(".dashboard").html(dataHTMLDashboard);
    $(".dashboard-filter").hide();
  }
}

function infografis(wilker, jenis, periode) {
  loadDashboardModule(
    `/home/infografis/${wilker}/${jenis}/${periode}`,
    null,
    'Terjadi kesalahan saat mengambil data infografis.'
  );
}

function sipedit(periode, bulan) {
  var disabled = periode == "all";
  $("#bulanSipedit-dashboard").prop('disabled', disabled);
  $("#bulanSipedit-dashboard-inner").prop('disabled', disabled);

  loadDashboardModule(`/home/sipedit/${periode}/${bulan}`, function() {
    initSipeditSelect2();
    var targetAnchor = $(`a[href="#${sipeditActiveTab}"]`);
    if (targetAnchor.length) {
      targetAnchor.tab('show');
    } else {
      $('a[href="#dashboard-pelatihan"]').tab('show');
    }
  });
}

function sipepes() {
  loadDashboardModule('/home/sipepes');
}

function sicute() {
  loadDashboardModule('/home/sicute');
}

function sinadi() {
  loadDashboardModule('/home/sinadi');
}

function relaxtime() {
  loadDashboardModule('/home/relaxtime');
}
