// Mengambil elemen aside
const asideElement = $('.positionRightMessage');
    
// Mengambil elemen tombol untuk toggle
const toggleButton = $('#toggleButton');

// Status untuk mengikuti posisi right saat ini
let isRight250px = false;

// Fungsi untuk melakukan toggle
function positionRightMessage () {
    if (isRight250px) {
        asideElement.css({
          'right': '0',
          'transition': 'right 0.2s ease'
      });
    } else {
        asideElement.css({
          'right': '250px',
          'transition': 'right 0.2s ease'
      });
    }
    isRight250px = !isRight250px; // Toggle status
}

function openBoxEmail(e) {
  $.ajax({
    url: `/home/ajax/openBoxEmail`,    
    type: 'POST',
    data: {
      email: $(e).attr('data-email')
    },
    dataType: "json",
    success: function(response) {
      if (response.data) {
        $(".positionRightMessage").html(response.data);
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

$("a").click(function(event) {
  var linkHref = $(this).attr('href');

  if (linkHref !== '#' && linkHref !== '') {
    if ($('.positionRightMessage').html().trim() === '') {}
    else {
      if ($(".positionRightMessage").find('.card').css("display") !== 'none') {
        event.preventDefault(); 
        
        var simplyUrl = $(this).attr('href');
        Swal.fire({
          title: 'Yakin ingin beralih halaman?',
          text: 'Anda memiliki sebuah Box pesan yang masih aktif',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = simplyUrl;
          }
        });
      }
    }
  }
});

$(document).on('change', '.autoUploadFile', function () {
  var formData = new FormData($('#sendMessagesToMail')[0]);
  $.ajax({
      url: '/home/ajax/uploadFile',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      beforeSend: function() {
        $('.autoUploadFileIcon').removeClass('fas fa-paperclip').addClass('fas fa-spinner');
        $("#overlay").show();
      },
      success: function(response) {
        if (response.status === 'success') {
          // Jika status respons adalah 'success', buat elemen input readonly
          // dan isi nilai input pertama dengan original_name dan input kedua dengan new_name
          $('<div>', { class: 'file-container-email-box' })
            .append(
                $('<input>', {
                    type: 'text',
                    class: 'form-control d-block',
                    style: 'border: unset;',
                    name: 'original_name[]',
                    readonly: true,
                    value: response.original_name
                })
            )
            .append(
                $('<div>', { class: 'icon-container' })
                    .append(
                        $('<i>', {
                            class: 'fas fa-times float-right',
                            style: 'margin-top: -25px; margin-right: 25px; cursor: pointer;',
                            onclick: 'autoRemoveFile(this)'
                        })
                    )
            )
            .append(
                $('<input>', {
                    type: 'hidden',
                    class: 'form-control d-block',
                    style: 'border: unset;',
                    name: 'new_name[]',
                    readonly: true,
                    value: response.new_name
                })
            )
            .append(
              $('<input>', {
                  type: 'hidden',
                  class: 'form-control d-block',
                  style: 'border: unset;',
                  name: 'id[]',
                  readonly: true,
                  value: response.id
              })
            )
            .appendTo('.tambahLampiran');
        } else {
          Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Oops... Gagal Melampirkan',
            text: 'Terjadi kesalahan saat melakukan upload data, coba lakukan kembali',
          })
        }

        $('.autoUploadFileIcon').removeClass('fas fa-spinner').addClass('fas fa-paperclip');
        $('.autoUploadFile').val("")
        $("#overlay").hide();
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
});

function autoRemoveFile(iconElement) {
  // Mengambil elemen div terkait yang berisi input
  var $parentDiv = $(iconElement).closest('.file-container-email-box');
  
  // Mencari elemen input dalam div yang sama
  var originalName = $parentDiv.find('input[name="original_name[]"]').val();
  var newName = $parentDiv.find('input[name="new_name[]"]').val();
  var id = $parentDiv.find('input[name="id[]"]').val();

  $(".positionRightMessage").find("button").prop('disabled', true);

  $.ajax({
    url: `/home/ajax/removeFile`,
    type: 'POST',
    data: {
      id: id,
      originalName: originalName,
      newName: newName,
    },
    dataType: "json",
    success: function(response) {
      if (response) {
        iconElement.closest('.file-container-email-box').remove();
      }
      $(".positionRightMessage").find("button").removeAttr('disabled');
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

$(document).on('submit', '#sendMessagesToMail', function (e) {
  e.preventDefault(); // Mencegah aksi bawaan form
  
  var form = $('#sendMessagesToMail')[0];
  var formData = new FormData(form);
  
  $.ajax({
    url: '/home/ajax/sendMessagesToEmail',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    beforeSend: function() {
      $("#overlay").show();
    },
    success: function(response) {
      if (response.status == 'success') {
        Toast.fire({
          icon: 'success',
          title: 'Pesan berhasil terikirim.'
        })
      } else {
        Toast.fire({
          icon: 'error',
          title: 'Pesan tidak terkirim.'
        })
      }
      $(".positionRightMessage").find('.card').css('display', 'none');
      $("#overlay").hide();
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
});