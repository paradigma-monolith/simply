$(document).on('submit', '#perbaharuiProfilePegawai' ,function(e) {
  e.preventDefault();

  var formData = $(this).serialize();
  var button = $(".btn-simpan").html()
  
  $.ajax({
    url: $(this).attr('action'),
    data: formData,
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('.btn-simpan').attr('disabled', true);
      $('.btn-simpan').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
  },
    success: function(response) {
      if (response.data == 'sukses') {
        Toast.fire({
          icon: 'success',
          text: 'Data profile berhasil diperbaharui!'
        });
        $(".profile-username").text(response.namaBaru)
      } else if(response.data == 'unique') {        
        Toast.fire({
          icon: 'warning',
          text: response.pesan
        });
      } 
      else {
        Toast.fire({
          icon: 'warning',
          text: 'Tidak ada perubahan data!'
        });
      }
      $(".btn-simpan").attr('disabled', false)
      $(".btn-simpan").html(button)
    }, error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        // text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
      })
    }
  });
})

$(document).on('submit', '#perbaharuiUser, #perbaharuiPassword' ,function(e) {
  e.preventDefault();

  var formData = $(this).serialize();
  var button = $(".btn-simpan").html()
  
  $.ajax({
    url: $(this).attr('action'),
    data: formData,
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('.btn-simpan').attr('disabled', true);
      $('.btn-simpan').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
  },
    success: function(response) {
      if (response.data == 'sukses') {
        Toast.fire({
          icon: 'success',
          text: 'Data akun berhasil diperbaharui!'
        });
      } else if(response.data == 'unique') {        
        Toast.fire({
          icon: 'warning',
          text: response.pesan
        });
      } 
      else {
        Toast.fire({
          icon: 'warning',
          text: 'Tidak ada perubahan data!'
        });
      }
      $(".btn-simpan").attr('disabled', false)
      $(".btn-simpan").html(button)
    }, error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        // text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
      })
    }
  });
})


$(document).on('submit', '#perbaharuiKontak' ,function(e) {
  e.preventDefault();

  var formData = $(this).serialize();
  var button = $(".btn-simpan").html()
  
  $.ajax({
    url: $(this).attr('action'),
    data: formData,
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      $('.btn-simpan').attr('disabled', true);
      $('.btn-simpan').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
  },
    success: function(response) {
      if (response.data == 'sukses') {
        Toast.fire({
          icon: 'success',
          text: 'Data kontak berhasil diperbaharui!'
        });
        // Masukkan disini
        $(".kontak0").html(response.kontak0)
        $(".kontak1").html(response.kontak1)
        $(".kontak2").html(response.kontak2)
        $(".kontak3").html(response.kontak3)
        $(".kontak4").html(response.kontak4)
      } 
      else {
        Toast.fire({
          icon: 'warning',
          text: 'Tidak ada perubahan data!'
        });
      }
      $(".btn-simpan").attr('disabled', false)
      $(".btn-simpan").html(button)
    }, error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        // text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
      })
    }
  });
})
$(document).on('submit', '#perbaharuiPhoto' ,function(e) {
  e.preventDefault();

  var formData = new FormData($(this)[0]);
  
  var button = $(".btn-simpan").html()
  
  $.ajax({
    url: $(this).attr('action'),
    data: formData,
    method: 'post',
    contentType: false,
    processData: false,
    dataType: 'json',
    beforeSend: function() {
      $('.btn-simpan').attr('disabled', true);
      $('.btn-simpan').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
  },
    success: function(response) {
      if (response.data == 'sukses') {
        Toast.fire({
          icon: 'success',
          text: 'Data kontak berhasil diperbaharui!'
        });
        // Masukkan disini
        $(".profile-picture").attr("src", response.pesan)
        $("#perbaharuiPhoto").find('input[type="file"]').val("");
        $("#perbaharuiPhoto").find('label').text("Pilih photo anda...");
      } else if (response.data == 'unique') {
        Toast.fire({
          icon: 'warning',
          text: response.pesan
        });
      }
      else {
        Toast.fire({
          icon: 'warning',
          text: 'Tidak ada perubahan data!'
        });
      }
      $(".btn-simpan").attr('disabled', false)
      $(".btn-simpan").html(button)
    }, error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
        // text: 'Terjadi kesalahan, silahkan coba kembali atau hubungi pihak yang bersangkutan!'
      })
    }
  });
})