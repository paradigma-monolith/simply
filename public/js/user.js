$(document).on('submit', '.statusPegawai', function(e) {
  e.preventDefault();
  
  var isActive = $(this).find('button[type="submit"]').data('status'); // baca status dari button
  if (isActive == "Aktif") {      
    var titleMessage = 'Nonaktifkan status pegawai?';
  } else {
    var titleMessage = 'Aktifkan status pegawai?';
  }

  Swal.fire({
    title: titleMessage,
    showDenyButton: true,
    showCancelButton: false,
    icon: 'warning',
    confirmButtonText: 'Konfirmasi',
    denyButtonText: 'Batalkan',
  }).then((result) => {
    if (result.isConfirmed) {
      // ==================================================================================
      var formData = $(this).serialize();
      var button = $(this).find('button[type="submit"]');
      var buttonHTML = button.html()
      
      button.removeClass('bg-gradient-success')
      button.removeClass('bg-gradient-secondary')
      button.addClass('bg-gradient-secondary')
      
      $.ajax({
        url: $(this).attr('action'),
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
          $(button).attr('disabled', true);
          $(button).html('<i class="fas fa-sync-alt fa-spin"></i> <span class="text-bold"></span>')            
      },
        success: function(response) {
          if (response.data) {
            console.log(response.data)
            Toast.fire({
              icon: 'success',
              title: 'Status pegawai berhasil diperbarui...'
            })
          } else{
            Toast.fire({
              icon: 'error',
              title: 'Status pegawai gagal diperbarui...'
            })
          }
          if(response.data == "Aktif") {                        
            button.removeClass('bg-gradient-secondary')
            button.addClass('bg-gradient-success')
          }

          $(button).data('status', response.data)
          $(button).attr('disabled', false)
          $(button).html(buttonHTML)
        }, error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire({
            icon: 'error',
            text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          })
        }
      });
      // ==================================================================================
    } else if (result.isDenied) {
      Swal.fire('Status pegawai tidak jadi diubah.', '', 'info')
    }
  })
});
