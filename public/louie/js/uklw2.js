function dataPegawai(){
  $.ajax({
    url: `/uklw/getDataPegawai`,
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $("#overlay").show()
    },
    success: function(response) {
      if (response.data) {
        $("#uklwDirectWithDataPegawai").find(".modal-body").html(response.data)
        $("#uklwDirectWithDataPegawai").modal('show')
      }
      $("#overlay").hide()
    },error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({ text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError});
    }
  });
}

$(document).on("click", ".uklwViewPasien", function(e){
  e.preventDefault();

  $.ajax({
    url: $(this).attr('href'),
    method: 'get',
    dataType: 'json',
    beforeSend: function() {
      $("#overlay").show()
    },
    success: function(response) {
      if (response.data) {
        $("#uklwDirectWithDataPegawai").find(".modal-body").html(response.data)
        $("#uklwDirectWithDataPegawai").modal('show')
      }
      $("#overlay").hide()
    },error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire({ text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError});
    }
  });
})

$(document).on('click','.simpanSementaraPemkes',function(e){
  e.preventDefault();

  var formData = $('#uklwTambahPemkes').serialize();
  
  $.ajax({
    url: $("#uklwTambahPemkes").attr('action') +"/ajax",
    data: formData,
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      $("#overlay").show()
  },
    success: function(response) {
      $("#overlay").hide()
      if (response.data) {
        $('#uklwTambahPemkes').find('input[name="id_uklw_pemkes"]').val(response.data)

        Toast.fire({
          icon: 'success',
          title: 'Berhasil menyimpan.'
        })
      }else{        
        Toast.fire({
          icon: 'error',
          title: 'Gagal menyimpan!'
        })
      }
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  });
})

$(document).on("click", ".redirectLink", function (){
  var link = $(this).find('.d-none').html()

  $("#uklwDirectPemkes").find('.modal-body').html(link)
  $("#uklwDirectPemkes").modal("show")
})

$(document).ready(function () {
  // // activeMenu
  // uklwPemkes
  filterChange()
});

$(document).on('change','input[name="filter_tanggal"], select[name="status_pemeriksaan"], select[name="kota"]', function(){
  filterChange();
});

function filterChange() {
  var tgl_change = $('input[name="filter_tanggal"]').length > 0 ? $('input[name="filter_tanggal"]').val() : '';
  if(tgl_change != '') {
    var tanggalArray = tgl_change.split(" - ");

    var start = tanggalArray[0];
    var end = tanggalArray[1];
  }
  var pemeriksaan = $('input[name="activeMenu"]').length > 0 ? $('input[name="activeMenu"]').val() : '';
  var status_pemeriksaan = $('select[name="status_pemeriksaan"]').length > 0 ? $('select[name="status_pemeriksaan"]').val() : '';
  
  var kota = $('select[name="kota"]').length > 0 ? $('select[name="kota"]').val() : '';

  if(pemeriksaan != ''){
    pemeriksaan_view(pemeriksaan, status_pemeriksaan, start, end, kota);
  }
}

function pemeriksaan_view(activeMenu, status_pemeriksaan='', start='', end='', kota='') {

  var ajaxUrl = '/uklw/ajax/pemeriksaanView';

  $.ajax({
    url: ajaxUrl,
    method: 'post',
    dataType: "json",
    data: {
      status_pemeriksaan: status_pemeriksaan,
      start: start,
      end: end,
      activeMenu: activeMenu,
      kota: kota
    },
    success: function(response) {
      console.log(response.cekData)
      $(".data_pemeriksaan_view").html(response.data)
      
      console.log(response.start)
      console.log(response.end)
      $('#filterTanggal').daterangepicker({
        startDate: response.start,
        endDate: response.end,
        locale: {
            format: 'YYYY-MM-DD'
        },
      });
    }, error: function(xhr, ajaxOptions, thrownError) {
      // Menampilkan SweetAlert error
      Swal.fire(
        "Terjadi kesalahan!",
        "Error: " + xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
        "error"
      );
      // Tolak Promise dengan pesan error
      reject(thrownError);
    }
  });
}