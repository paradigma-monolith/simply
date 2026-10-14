$(document).ready(function () {
  ambil_data();
});

$("#unit_kerja").change(function(){
  var unit_kerja = $("#unit_kerja").val()
  var jenis_dokumen = $('#jenis_dokumen');
  if (unit_kerja === 'PKSE' || unit_kerja == 'UKLW' || unit_kerja == 'PRL' || unit_kerja == 'Timker 1' || unit_kerja == 'Timker 2' || unit_kerja == 'Timker 3' || unit_kerja == 'Timker 4' || unit_kerja == 'Timker 5') {
    jenis_dokumen.val('SOP Utama');
  } else if (unit_kerja == 'Adum') {
    jenis_dokumen.val('SOP Pendukung');
  } else {
    jenis_dokumen.val('SOP Lainnya');
  }
})

$("#view_sop").change(function(){
  ambil_data_sop($(this).val())
})

function ambil_data() {
  $.ajax({
    url: '/sipepes/view_data_sop',
    method: 'get',
    dataType: "json",
    success: function(data) {
      $(".view_data_sop").html(data.data)
    },
  })
}

function ambil_data_sop(id) {
  $.ajax({
    url: '/sipepes/view_data_sop/'+id,
    method: 'get',
    dataType: 'json',
    success: function(data) {
      $(".view_data_sop").html(data.data)
    }
  })
}
