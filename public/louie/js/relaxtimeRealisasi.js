$(document).ready(function () { 
  
  var sekarang = new Date();
  var tahunSekarang = sekarang.getFullYear();
  
  getDataRealisasiRelaxtime(tahunSekarang)
});

function getDataRealisasiRelaxtime(tahun){
  $.ajax({
    type: "GET",
    url: `/relaxtime/ajax/getRealisasi/${tahun}`,
    dataType: 'json',
    beforeSend: function() {
      $("#jsGridRealisasiRelaxtime").html(`<div class="text-center"><i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span></div>`)      
    },
    success: function(response) {
      if (response.data) {
        
        jsGridRealisasi(response.data, response.username);
      }
    },error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  });
}
function jsGridRealisasia(dataRealisasiRelaxtime) {
  $("#jsGridRealisasiRelaxtime").jsGrid({
    height: "100%",
    width: "100%",
    editing: true,
    sorting: true,
    paging: true,

    data: db.clients,
    fields: [
      {
        name: "Name",
        type: "text",
        width: 150,
        itemTemplate: function (value, item) {
          // Gabungkan data "Name" dan "Age" pada tampilan
          return item.Name + "<br/><small>" + item.Age + "</small>";
        }
      }, 
    ]
});
}

function jsGridRealisasi(dataRealisasiRelaxtime, user) {
  $("#jsGridRealisasiRelaxtime").jsGrid({
      height: "2080px",
      width: "100%",
      sorting: false,
      paging: true,

      data: dataRealisasiRelaxtime,
      fields: [
        {
          name: "Kode",
          type: "text",
          width: 45,
          itemTemplate: function (value, item) {
            return item.digit_kro + "." + item.kode_kro + "." + item.kode_relaxtime_ro + "<div class='text-right'>" + item.kode_komponen + "." + item.kode_sub_komponen + "." + item.kode_uraian + "</div>";
          }
        },
        {
          name: "Uraian",
          type: "text",
          width: 150,
          itemTemplate: function (value, item) {
            return item.deskripsi_uraian + "<div><small>(" + item.deskripsi_detail_uraian + ")</small></div>";
          }
        },
        {
          name: "Pagu",
          type: "text",
          width: 50,
          itemTemplate: function (value, item) {
            var jumlah_detail_uraian = parseFloat(item.jumlah_detail_uraian)
            var formattedNumber = jumlah_detail_uraian.toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            return "<div>&nbsp;</div>" + formattedNumber;
          }
        },
        {
          name: "Realisasi (%)",
          type: "text",
          width: 35,
          itemTemplate: function (value, item) {
            var jumlah_detail_uraian = (parseFloat(item.realisasi) / parseFloat(item.jumlah_detail_uraian))*100
            var formattedNumber = jumlah_detail_uraian.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: 5 });
            return "<div>&nbsp;</div>" + formattedNumber + "%";
          }
        },
        {
          name: "Diperbaharui Oleh",
          type: "text",
          width: 65,
          itemTemplate: function (value, item) {
            return "<div><small>" + item.updated_at + "</small></div>" + item.updated;
          }
        },        
      ]
  });
}