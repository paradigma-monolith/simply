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
  // Initialize Smooth Scroll plugin
//   $('a[href^="#"]').on('click', function(event) {
//     var target = $(this.getAttribute('href'));
//     if (target.length) {
//       event.preventDefault();
//       $('html, body').stop().animate({
//         scrollTop: target.offset().top
//       }, {
//         duration: 500, // waktu total animasi (ms)
//         easing: 'easeInOutQuad', // jenis easing animasi
//         step: function(now, fx) {
//           // Efek motion blur
//           var blur = Math.min(20, Math.abs(fx.end - fx.start) / 40);
//           $('.content-wrapper').css('filter', 'blur(' + blur + 'px)');
//         },
//         complete: function() {
//           // Hapus efek motion blur setelah animasi selesai
//           $('.content-wrapper').css('filter', '');
//         }
//       });
//     }
//   });
  formHapusData();
  callAllUser();
});

$(function() {
  // Date range picker
  $('.date-dashboard').daterangepicker({
    startDate: moment().subtract(1, 'months').startOf('month'),
    endDate: moment(),
    locale: {
      format: 'YYYY-MM-DD'
    }
  });
  $('.datetime').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD HH:mm:ss'
    }
  });
  $('.range-date').daterangepicker({
    startDate: moment(),
    endDate: moment(),
    locale: {
      format: 'YYYY-MM-DD'
    }
  });

  // Auto-update notification date when execution time is selected
  $('.datetime').on('apply.daterangepicker', function(ev, picker) {
    // Get the selected execution date range
    var executionStartDate = picker.startDate;
    var executionEndDate = picker.endDate;
    
    // Find notification checkbox and range date elements
    var notifCheckbox = $('#myCheckbox');
    var rangeDate = $('.range-date');
    
    // Update notification date if elements exist
    if (rangeDate.length > 0 && rangeDate.data('daterangepicker')) {
      // Set notification date to match execution date (extract date only, ignore time)
      var notificationStartDate = executionStartDate.clone().startOf('day');
      var notificationEndDate = executionEndDate.clone().startOf('day');
      
      // Update the notification date picker
      rangeDate.data('daterangepicker').setStartDate(notificationStartDate);
      rangeDate.data('daterangepicker').setEndDate(notificationEndDate);
      
      // Update the input value to reflect the new dates
      var dateFormat = 'YYYY-MM-DD';
      if (notificationStartDate.isSame(notificationEndDate, 'day')) {
        rangeDate.val(notificationStartDate.format(dateFormat));
      } else {
        rangeDate.val(notificationStartDate.format(dateFormat) + ' - ' + notificationEndDate.format(dateFormat));
      }
      
      // If notification checkbox exists and is not checked, optionally enable it
      // (This is optional - you can uncomment the lines below if you want to auto-enable notifications)
      // if (notifCheckbox.length > 0 && !notifCheckbox.is(':checked')) {
      //   notifCheckbox.prop('checked', true).trigger('change');
      // }
    }
  });
  $('.range-date-data').daterangepicker({
    startDate: $(this).attr("data-start-date"),
    endDate: $(this).attr("data-end-date"),
    locale: {
      format: 'YYYY-MM-DD'
    }
  });
  $('.date').datetimepicker({
    defaultDate: moment(),
    format: 'YYYY/MM/DD'
  });
  $('.date-today').datetimepicker({
    defaultDate: moment(),
    format: 'YYYY/MM/DD'
  });
  
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
  $(".nav-item").removeClass('menu-open')
  $(idMenu).addClass('menu-open').css({
    backgroundColor: 'blue',
    transition: 'all 0.5s ease-in-out'
  }).delay(500).queue(function() {
    $(this).css({
      backgroundColor: 'rgba(255, 255, 255, 0.0)'
    }).dequeue();
  });
}

function logoutSweetAlert(element) {
  Swal.fire({
    title: 'Ingin keluar dari aplikasi?',
    showDenyButton: true,
    showCancelButton: false,
    icon: 'warning',
    confirmButtonText: 'Konfirm..',
    denyButtonText: `Tidak`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Kerjaan kamu pada sistem telah disimpan, Terimakasih...',
          showConfirmButton: false,
          allowOutsideClick: false,
          // timer: 1500
        },
        setTimeout(() => {
          window.location = $(element).attr('data-location')
        }, "1500"))
    } else if (result.isDenied) {
      Swal.fire('Tidak jadi kerluar', '', 'info')
      Swal.fire({
        icon: 'info',
        title: 'Anda masih di Sistem aplikasi KKP',
        showConfirmButton: false,
        timer: 1500,
        allowOutsideClick: false
      })
    }
  })
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
      } else if (result.isDenied) {
        Swal.fire('Informasi data tidak dihapus', '', 'info')
        Swal.fire({
          icon: 'info',
          title: 'Data masih tersimpan pada sistem',
          showConfirmButton: false,
          timer: 1500,
          allowOutsideClick: false
        })
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
    } else if (result.isDenied) {
      Swal.fire('Informasi data tidak dihapus', '', 'info')
      Swal.fire({
        icon: 'info',
        title: 'Data masih tersimpan pada sistem',
        showConfirmButton: false,
        timer: 1500,
        allowOutsideClick: false
      })
    }
  })
});

function callAllUser() {
  $.ajax({
    url: `/home/ajax/getUser`,
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('#side-right-user').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $('#side-right-user img').css({
        display: 'block',
        margin: '0 auto'
      });
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

$(document).ready(function () {
  getNotifikasi();
  setInterval(() => {
    getNotifikasi();
  }, 10000);
});

function getNotifikasi() {
  $.ajax({
    url: "/ajax/notifikasi",
    method: 'get',
    dataType: 'json',
    success: function(response) {
      if (response.data) {
        $('.notifikasi-pesan-sistem-number').text(response.number)
        $('.notifikasi-pesan-sistem').html(response.data)
      }
    },
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
