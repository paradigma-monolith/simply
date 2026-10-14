$("#surat").change(function(){
  $(".select2").attr('disabled', true)
  $(this).attr('disabled', false)
  if($(this).val() != ''){
    $("#unit_kerja").attr('disabled', false)
    
    $("#kepegawaian").html(
      '<option value="">--NIP/Nama--</option>'
    )
  }
  else{
    // $("#unit_kerja").val('')
    $(".select2").attr('disabled', true)
    $(this).attr('disabled', false)
  }
  // if($(this).val() == "cuti"){
  //   $("#atasan").attr('disabled', false)
  //   $("#pejabat").attr('disabled', false)
  // }
});

function openSelect()
{
  if($("#surat").val() == "cuti"){
    $("#atasan").attr('disabled', false)
    $("#kepegawaian").attr('disabled', false)
    $("#pejabat").attr('disabled', false)
  }
  if($("#surat").val() == "surat_sakti"){
    $("#atasan").attr('disabled', false)
    $("#kepegawaian").attr('disabled', false)
    $("#pejabat").attr('disabled', true)
  }
}
function closeSelect()
{
  $(".select2").attr('disabled', true)
  $("#surat").attr('disabled', false)
  $("#unit_kerja").attr('disabled', false)
}

$(function () {
  jQuery(document).ready(function() {
    
    $("#unit_kerja").change(function(){
      var unit_kerja = $("#unit_kerja").val()
      var option = '';
      $.ajax({
        type: "get",
        url: '/admin/ajax_getUnitKerja',
        data:{
          unit_kerja : unit_kerja
        },
        dataType: "json",
        success: function(response){
          
          if(response.sukses){

            for(let i=0; i<response.sukses.pegawai.length; i++){
              option += '<option value="'+response.sukses.pegawai[i]['nip']+'">'+response.sukses.pegawai[i]['nip'] +"\n"+response.sukses.pegawai[i]['nama']+'</option>'
            }

            if($("#unit_kerja").val() != ''){
              openSelect();
            $("#kepegawaian").html(
              '<option value="">--NIP/Nama--</option>'+option
            )
            $("#atasan").html(
              '<option value="">--NIP/Nama--</option>'+option
            )
            $("#pejabat").html(
              '<option value="">--NIP/Nama--</option>'+option
            )
            }else{
              closeSelect();
              $("#kepegawaian").html(
                '<option value="">--NIP/Nama--</option>'
              )
              $("#atasan").html(
                '<option value="">--NIP/Nama--</option>'
              )
              $("#pejabat").html(
                '<option value="">--NIP/Nama--</option>'
              )
            }
          }
        }
      });
      return false;
    })

    $("#atasan").change(function(){
      var nip = $("#atasan").val()
      if(nip != ''){
        $.ajax({
          type: "get",
          url: '/admin/ajax_getPegawai',
          data:{
            nip : nip
          },
          dataType: "json",
          success: function(response){
            if(response.sukses){
              $("#jabatanAtasan").text(response.sukses['jabatan'])
              $("#namaAtasan").text(response.sukses['nama'])
              $("#nipAtasan").text(response.sukses['nip'])
            }
          },
        });
        return false;
      }
    });
    $("#pejabat").change(function(){
      var nip = $("#pejabat").val()
      if(nip != ''){
        $.ajax({
          type: "get",
          url: '/admin/ajax_getPegawai',
          data:{
            nip : nip
          },
          dataType: "json",
          success: function(response){
            if(response.sukses){
              $("#jabatanPejabat").text(response.sukses['jabatan'])
              $("#namaPejabat").text(response.sukses['nama'])
              $("#nipPejabat").text(response.sukses['nip'])
            }
          }
        });
        return false;
      }
    });
    $("#pegawai").change(function(){
      var nip = $("#pegawai").val()
      if(nip != ''){
        $.ajax({
          type: "get",
          url: '/admin/ajax_getPegawai',
          data:{
            nip : nip
          },
          dataType: "json",
          success: function(response){
            console.log(response.sukses);
            if(response.sukses){
              $("#jabatanPegawai").val(response.sukses['jabatan'])
              $("#golonganPegawai").val(response.sukses['golongan'])
              $("#masaKerjaPegawai").val(response.sukses['masaKerja'])
              $("#unitKerjaPegawai").val(response.sukses['unitKerja'])
            }
          }
        });
        return false;
      }
    });
  });
});