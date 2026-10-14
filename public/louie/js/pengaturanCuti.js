$(document).on('click', '.button-show-modal-pengaturan-sisa-tahunan', function(){
  var id = $(this).data('id');
  var nama = $(this).data('nama');
  var cuti_tahunan = $(this).data('sisa-cuti-tahunan');

  $("#exampleModalCutiSisaTahunan").find('input').val('')
  $("#exampleModalCutiSisaTahunan").find('input[name="id_data_pegawai"]').val(id)
  $("#exampleModalCutiSisaTahunan").find('input[name="nama"]').val(nama)
  $("#exampleModalCutiSisaTahunan").find('input[name="sisa_cuti_tahunan"]').val(cuti_tahunan)

  $('#exampleModalCutiSisaTahunan').modal("show");
});

$(document).ready(function () {
  getSisaCuti()
});

function getSisaCuti() {
  $.ajax({
    url: `/cuti/get_sisa_cuti`,
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $("#overlay").show()
    },
    success: function(response) {
      if (response.data) {
        $(".data-sisa-cuti-tahun-lalu").html(response.data)
        $("#overlay").hide()
      }
    }, error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        position: 'top-end',
        icon:'error',
        title: 'Oops..',
        // text: 'Ada kesalahan dalam sistem, tolong hubungi Tim IT'
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
      })
    }
  });
}

$(document).on('click', '.cuti-btn-submit-pengaturan-sisa-tahunan', function(){

  var formulir = $("#exampleModalCutiSisaTahunan").find('form');
  var formData = $(formulir).serialize();
  
  $.ajax({
    url: $(formulir).attr('action'),
    data: formData,
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      $(this).attr('disabled', true);
      $(this).html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
  },
    success: function(response) {
      if (response.data == "sukses") {
        $('#exampleModalCutiSisaTahunan').modal("hide");
        Toast.fire({
          icon: 'success',
          title: 'Data berhasil diperbaharui.'
        })
        getSisaCuti()
      } else {
        Toast.fire({
          icon: 'error',
          title: 'Data gagal diperbaharui.'
        })
      }
      $(this).attr('disabled', false)
      $(this).text('Simpan Perubahan')
    }, error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({
        position: 'top-end',
        icon:'error',
        title: 'Oops..',
        text: 'Ada kesalahan dalam sistem, tolong hubungi Tim IT'
      })
    }
  });

})