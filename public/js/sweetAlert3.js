var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
var ToastError = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

// Event handler untuk tombol link agenda dalam SweetAlert
$(document).on('click', '.link-button', function(e) {
  e.preventDefault();
  e.stopPropagation();
  var url = $(this).data('url');
  if (url) {
    window.open(url, '_blank');
  }
});

// Event handler untuk SweetAlert yang berisi HTML dengan tombol link
$(document).on('swal2:open', function() {
  // Pastikan event handler untuk link-button aktif dalam SweetAlert
  $('.swal2-html-container').on('click', '.link-button', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var url = $(this).data('url');
    if (url) {
      window.open(url, '_blank');
    }
  });
});

  function toggleVaksinasi() {
    $.ajax({
      url: '/sisovi/setPengalihan',
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data == 'true') {
          const toggleOn = document.getElementById('toggle-on');
          const toggleOff = document.getElementById('toggle-off');

          if (toggleOn.style.display !== 'none') {
            toggleOn.style.display = 'none';
            toggleOff.style.display = 'inline';
          } else {
            toggleOn.style.display = 'inline';
            toggleOff.style.display = 'none';
          }
          Toast.fire({
            icon: 'success',
            text: 'Data berhasil disimpan!'
          });
        } else {
          ToastError.fire({
            icon: 'error',
            text: 'Terjadi kesalahan saat menyimpan data.'
          });
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
          ToastError.fire({
            icon: 'error',
            text: 'Terjadi kesalahan saat menyimpan data. Error sistem coba refresh ulang!'
          });
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  }

function viewProfile($user) {
  $.ajax({
    url: `/home/viewProfile/${$user}`,
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $("#overflow").show()
  },
    success: function(response) {
      if (response) {
        Swal.fire({
          width: '800px',
          html:
            '<div class="row px-3" style="overview:hidden;"> ' +
            '<div class="col"></div> ' +
            '<div class="col-5 text-left">'+ response.data1 +'</div> ' +
            '<div class="col-5">'+ response.data2 +'</div> ' +
            '<div class="col"></div> ' +
            '</div>',
          showCloseButton: true,
          showCancelButton: true,
          focusConfirm: false,
        })
      }
    }, error: function(xhr, ajaxOptions, thrownError) {
        Swal.fire({
        icon: 'info',
        title: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
      })
    }
  });
}

$(document).ready(function() { 
  // Menyembunyikan elemen overlay
  $("#overlay").hide();
  formHapusData();

  // Lazy-load daftar pegawai sidebar kanan hanya saat tombol control-sidebar dibuka
  $(document).on('click', '[data-widget="control-sidebar"]', function() {
    if (!window.__simplyRightUsersLoaded) {
      window.__simplyRightUsersLoaded = true;
      callAllUser();
    }
  });
});

$(function() {
  // Date range picker (inisialisasi hanya jika elemen tersedia di halaman)
  if ($('.date-dashboard').length) {
    $('.date-dashboard').daterangepicker({
      startDate: moment().subtract(1, 'months').startOf('month'),
      endDate: moment(),
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  }
  if ($('.datetime').length) {
    $('.datetime').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    });
  }
  if ($('.range-date').length) {
    $('.range-date').daterangepicker({
      startDate: moment(),
      endDate: moment(),
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  }

  // Auto-update notification date when execution time is selected
  $('.datetime').on('apply.daterangepicker', function(ev, picker) {
    var executionStartDate = picker.startDate;
    var executionEndDate = picker.endDate;
    var rangeDate = $('.range-date');

    if (rangeDate.length > 0 && rangeDate.data('daterangepicker')) {
      var notificationStartDate = executionStartDate.clone().startOf('day');
      var notificationEndDate = executionEndDate.clone().startOf('day');

      rangeDate.data('daterangepicker').setStartDate(notificationStartDate);
      rangeDate.data('daterangepicker').setEndDate(notificationEndDate);

      var dateFormat = 'YYYY-MM-DD';
      if (notificationStartDate.isSame(notificationEndDate, 'day')) {
        rangeDate.val(notificationStartDate.format(dateFormat));
      } else {
        rangeDate.val(notificationStartDate.format(dateFormat) + ' - ' + notificationEndDate.format(dateFormat));
      }
    }
  });
  if ($('.range-date-data').length) {
    $('.range-date-data').daterangepicker({
      startDate: $(this).attr("data-start-date"),
      endDate: $(this).attr("data-end-date"),
      locale: {
        format: 'YYYY-MM-DD'
      }
    });
  }
  if ($('.date').length) {
    $('.date').datetimepicker({
      defaultDate: moment(),
      format: 'YYYY/MM/DD'
    });
  }
  if ($('.date-today').length) {
    $('.date-today').datetimepicker({
      defaultDate: moment(),
      format: 'YYYY/MM/DD'
    });
  }
});
function updateDatePicker(value) {
  // Memisahkan nilai menjadi startDate dan endDate
  var dateValues = value.split(' - ');
  var startDate = dateValues[0];
  var endDate = dateValues[1];

  // Mengatur tanggal manual pada date picker
  $('.range-date').data('daterangepicker').setStartDate(startDate);
  $('.range-date').data('daterangepicker').setEndDate(endDate);
}

function menuOpen(idMenu) {
  var $target = $(idMenu);
  if (!$target.length) return;

  // Pastikan sidebar terbuka saat pengguna mengklik kartu menu dari halaman utama
  if ($('body').hasClass('sidebar-collapse') || $('body').hasClass('sidebar-closed')) {
    $('body').removeClass('sidebar-collapse sidebar-closed').addClass('sidebar-open');
  }

  $(".nav-sidebar .nav-item").removeClass('menu-open');
  $target.addClass('menu-open');
  $target.children('.nav-treeview').stop(true, true).slideDown(200);

  // Sorot item di sidebar agar langsung terlihat
  $target.css({
    backgroundColor: 'rgba(3, 119, 140, 0.75)',
    borderRadius: '8px',
    transition: 'background-color 0.4s ease'
  }).delay(900).queue(function() {
    $(this).css({
      backgroundColor: 'transparent'
    }).dequeue();
  });

  if ($target[0] && typeof $target[0].scrollIntoView === 'function') {
    $target[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
}

function logoutSweetAlert(element) {
  window.location = $(element).attr('data-location') || '/auth/logout';
}

// Hapus Data
function formHapusData() {
  
  $(".formHapusData").submit(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Hapus Data yang dipilih?',
      showDenyButton: true,
      showCancelButton: false,
      icon: 'warning',
      confirmButtonText: 'Konfirm..',
      denyButtonText: `Tidak`,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          url: $(this).attr('action'),
          method: 'DELETE'
        }).done(function() {
          Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Data informasi berhasil dihapuskan!',
              showConfirmButton: false,
              allowOutsideClick: false,
            },
            setTimeout(() => {
              location.reload();
            }, "500"))
        }).fail(function(xhr, ajaxOptions, thrownError) {
        //   alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
          })
        });
      }
    })
  });
}

$(document).on("submit", ".formHapusData", function(e) {
  e.preventDefault();
  Swal.fire({
    title: 'Hapus Data yang dipilih?',
    showDenyButton: true,
    showCancelButton: false,
    icon: 'warning',
    confirmButtonText: 'Konfirm..',
    denyButtonText: `Tidak`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      $.ajax({
        url: $(this).attr('action'),
        method: 'DELETE'
      }).done(function() {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Data informasi berhasil dihapuskan!',
            showConfirmButton: false,
            allowOutsideClick: false,
          },
          setTimeout(() => {
            location.reload();
          }, "500"))
      }).fail(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
        })
      });
    }
  })
});

function callAllUser() {
  $.ajax({
    url: `/home/ajax/getUser`,
    dataType: "json",
    beforeSend: function() {
      $('#side-right-user').html(
        '<div class="text-center text-muted small py-5 px-3"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat direktori pegawai...</div>'
      );
    },
    success: function(response) {
      if (response.data) {
        $("#side-right-user").html(response.data);
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

var __notifInFlight = false;

$(document).ready(function () {
  getNotifikasi();
  setInterval(function () {
    if (!document.hidden) {
      getNotifikasi();
    }
  }, 30000);
  document.addEventListener('visibilitychange', function () {
    if (!document.hidden) {
      getNotifikasi();
    }
  });
});

function getNotifikasi() {
  if (__notifInFlight) return;
  __notifInFlight = true;
  $.ajax({
    url: "/ajax/notifikasi",
    method: 'get',
    dataType: 'json',
    success: function(response) {
      if (response && response.data !== undefined) {
        $('.notifikasi-pesan-sistem-number').text(response.number || '');
        $('.notifikasi-pesan-sistem').html(response.data);
      }
    },
    complete: function() {
      __notifInFlight = false;
    }
  });
}

$(document).on('submit', '.form-notifikasi-dibaca', function(e){
  e.preventDefault();
  var form = $(this); // Menyimpan referensi ke elemen form saat ini

  $.ajax({
    url: form.attr('action'), // Menggunakan referensi form untuk mengambil nilai 'action'
    method: 'post',
    dataType: 'json',
    success: function(response) {
      if (response.data) {
        form.closest('.form-notifikasi-dibaca').remove(); // Menghapus elemen form dan parentnya
        getNotifikasi()
      }
    },
  });
});

$(document).ready(function() {
  // Menangkap peristiwa yang terjadi sebelum modal terbuka
  $('#btn-notifikasi-read').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Tombol yang memicu modal
    var pesan = button.data('notif-pesan'); // Ekstrak data dari atribut data-mydata
    var pengirim = button.data('notif-pengirim'); // Ekstrak data dari atribut data-mydata
    var waktu = button.data('notif-waktu'); // Ekstrak data dari atribut data-mydata
    var id = button.data('notif-id'); // Ekstrak data dari atribut data-mydata
    var modal = $(this);

    // Menetapkan data ke elemen dalam modal
    modal.find('.notif-pesan').html(pesan);
    modal.find('.notif-waktu').text(waktu);
    modal.find('.notif-pengirim').text(pengirim);
    
    $.ajax({
      url: `/home/ajax/read-notif/${id}`,
      method: 'post',
      dataType: 'json',
      success: function(response) {
        if (response.data) {
          button.removeAttr('style');
          getNotifikasi()
        }
      },
    });
  });
});
