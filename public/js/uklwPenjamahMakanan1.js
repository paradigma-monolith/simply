$(document).on('change','input[name="umur"]', function() {
  if($(this).val() != 0 || $(this).val() != '') {
    var umur = parseInt($(this).val());
    var tgl_lahir_value = getBirthDateFromAge(umur);

    $("#tanggal").val(tgl_lahir_value)
    
    $("#tanggal").attr('readonly', true);
  } else {    
    $("#tanggal").attr('readonly', false)
  }
})

function getBirthDateFromAge(age) {
  var today = new Date();
  var year = today.getFullYear() - age;
  var month = today.getMonth() + 1;
  var day = today.getDate();

  // Handle leap year
  if (month == 2 && day > 28) {
      if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
          day = 29;
      } else {
          day = 28;
      }
  }

  return year + '/' + ('0' + month).slice(-2) + '/' + ('0' + day).slice(-2);
}

$(document).on('change','input[name="nama"], input[name="jk"],  select[name="pekerjaan"],  select[name="pendidikan"], .alamat_penjamah' , function(){
  getNext1()
})

function getNext1() {
  var nama = $('.nama_penjamah').val();
  var jk = $('input[name="jk"]:checked').val();
  var pekerjaan = $('select[name="pekerjaan"]').val();
  var pendidikan = $('select[name="pendidikan"]').val();
  var alamat = $('.alamat_penjamah').val();

  console.log("nama = " + nama)
  console.log("jk = " + jk)
  console.log("pekerjaan = " + pekerjaan)
  console.log("pendidikan = " + pendidikan)
  console.log("alamat = " + alamat)

  if (nama != '' && jk != undefined && pekerjaan != '' && pendidikan != '' && alamat != '') {
    $("#next1").removeClass('disabled');
  } else {    
    $("#next1").removeClass('disabled');
    $("#next1").addClass('disabled');
  }
}