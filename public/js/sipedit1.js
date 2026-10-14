

var firstLoad = 0;
$(document).ready(function () {
  
  if ($(".inputFile").prop("checked")) {
    $('input[type="file"]').eq(0).prop('disabled', false);
  } else {
    $('input[type="file"]').eq(0).prop('disabled', true);
  }
  $(".inputFile").eq(0).change(function () {
    var isChecked = $(this).prop("checked");
    $('input[type="file"]').eq(0).prop('disabled', !isChecked);
  });
    
  sipeditViewData()

  // Menonaktifkan fungsi inputan untuk Select2
  $('#pegawai').on('select2:opening', function(event) {
    event.preventDefault();
  });
  
  $(document).on('change', '.subkategori', function () {
    var subKategoriId = $(this).val();
    var arsipId = $(this).data('id');

    console.log('Dropdown changed', subKategoriId, arsipId);

    if (subKategoriId !== "") {
      $.ajax({
        url: "/sipedit/update_sub_kategori",
        type: "POST",
        dataType: "json",
        data: {
          subKategoriId: subKategoriId,
          arsipId: arsipId
        },
        success: function (response) {
          console.log(response);
          console.log(response['status']);
          Toast.fire({
            icon: response['status'],
            text: response['message'],
          });
        },
        error: function (xhr, status, error) {
          console.error('Gagal update:', error);
        }
      });
    }
  });
  
});

  $("#sipeditSort").change(function() {
    if(firstLoad != 0){
      var isChecked = $("#sipeditCheckAll").prop("checked");
      sipeditViewData($(this).val(), isChecked);
    }      
  });


  // Simpan nilai awal dropdown #pegawai sebelum checkbox diubah
  var nilaiAwalPegawai = $("#pegawai").val();

  // Inisialisasi Select2 pada dropdown #pegawai
  $("#pegawai").select2();

  $("#sipeditCheckAll").change(function () {
    var isChecked = $(this).prop("checked");
    sipeditViewData('', isChecked);
    if(isChecked){
      $("#sipeditAdministrator").val(1)
      firstLoad = 0
    }else{      
      $("#sipeditAdministrator").val(0)
      firstLoad = 0
    }
  });

  $("#selectPegawai").change(function () {
    var isChecked = $(this).prop("checked");
    if (isChecked) {
      // Mengaktifkan fungsi inputan untuk Select2
      $('#pegawai').off('select2:opening');
    } else {
      // Mengembalikan nilai awal dropdown #pegawai
      $("#pegawai").val(nilaiAwalPegawai).trigger("change.select2");
      // Menonaktifkan fungsi inputan untuk Select2
      $('#pegawai').on('select2:opening', function (event) {
        event.preventDefault();
      });
    }
  });

  
function sipeditViewData(periode = '', isChecked = '') {
  if(isChecked == true){
      isChecked = 'true';
  }
  
  $.ajax({
    url: `/sipedit/view_data_arsip`, 
    method: "post",
    data: {
      periode : periode,
      cekAll : isChecked
    },
    dataType: "json",
    beforeSend: function() {
      // Menampilkan animasi loading pada elemen dengan class 'loader'      
      $('.view_data_arsip').html(
        '<img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">'
      );
      $("#sipeditSortButton ").attr('disabled', true)
      $('.view_data_arsip img').css({
        display: 'block',
        margin: '0 auto'
      });
    },
    success: function(response) {
      if (response.data) {
        // console.log(response.cek)
        $(".view_data_arsip").html(response.data);
      }
      if(firstLoad == 0){
        if(response.cek != ''){
          $("#sipeditSortButton ").attr('disabled', false)           
          $("#sipeditSort").attr('disabled', false)
          $('#sipeditSort').daterangepicker({
            startDate: response.startDate,
            endDate: response.endDate,
            locale: {
              format: 'YYYY-MM-DD'
            }
          });
          firstLoad = 1
        } 
      }
      else{          
        $("#sipeditSort").attr('disabled', false)
        if(response.cek != ''){
          $("#sipeditSortButton ").attr('disabled', false)                     
        } 
      }

      formHapusData()
    },
    error: function(xhr, ajaxOptions, thrownError) {
      // alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    //   Swal.fire({
    //     position: 'top-end',
    //     icon: 'error',
    //     title: 'Oops... ERROR SINTAKS',
    //     // text: 'Terjadi kesalahan pemrograman saat mengambil data silahkan hubungi pihak yang terkait',
    //     text: (xhr.status + "\n" + xhr.responseText + "\n" + thrownError),
    //   })
    }
  });
}