function directLinkUpdateTargetBulanan() {
  var element = $("#relaxtimeTargetBulananTahunModal").find('select')
  

  if(element.val() == ""){
    Swal.fire({
      icon: 'error',
      title: 'Gagal memuat data!',
      text: 'Coba periksa koneksi internet anda, atau lakukan reload pada halaman ini!'
    })
  } else {
    window.location.href = `/relaxtime/target_bulanan_edit/${element.val()}`;

  }
}

$("#relaxtimeTargetBulanan").find("button").on('click', function(e) {
  e.preventDefault();
  
  var submit = true;
  var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];
  for (let i = 1; i <= 12; i++) {
    var target = $(`input[name="bulan${i}"]`).val();
    var target51 = $(`input[name="bulan${i}51"]`).val();
    var target52 = $(`input[name="bulan${i}52"]`).val();
    var target53 = $(`input[name="bulan${i}53"]`).val();
  
    if (parseFloat(target) != (parseFloat(target51) + parseFloat(target52) + parseFloat(target53))) {    
      Swal.fire({
        icon: 'warning',
        title: 'Perhitungan tidak akurat',
        text: 'Pada bulan ' + bulan[i-1] + " perhitungan target bulanan tidak sesuai dengan angka uraiannya!",
      });
      submit = false;
      break;
    }
  }
  if(submit == true) {
    Toast.fire({
      icon: 'success',
      title: 'Data akan diproses',
    })
    setTimeout(() => {
      $("#relaxtimeTargetBulanan").submit()
    }, 1000);
  }
})