
$(function () {
  $('#batas_bawah').ionRangeSlider({
    min: 0,
    max: 200,
    type: 'single',
    step: .1,
    postfix: ' %',
    prettify: false,
    hasGrid: true
  })
  $('#batas_atas').ionRangeSlider({
    min: 0,
    max: 200,
    type: 'single',
    step: .1,
    postfix: ' %',
    prettify: false,
    hasGrid: true
  })
})

$(document).on('change', '.aksesPenggunaCheckboxRelaxtime', function(){
  insert = "/relaxtime/insertAkses";
  update = "/relaxtime/removeAkses";

  elemen = $(this);
  
  if (elemen.prop('checked')) {
    $.ajax({
      url: insert,
      data: {
        id:elemen.val()
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
    },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            text: 'Akses berhasil diperbaharui!'
          });
        } else {
          Toast.fire({
            icon: 'warning',
            text: 'Akses gagal diperbaharui!'
          });
          elemen.prop('checked', false);
        }
        $("#overlay").hide(); 
      },error: function(xhr, ajaxOptions, thrownError) {
        elemen.prop('checked', false);
        $("#overlay").hide(); 
        ToastError.fire({
          icon: 'error',
          // text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
          text: 'Kegagalan sistem!'
        });
      }
    });
  } else {
    $.ajax({
      url: update,
      data: {
        id:elemen.val()
      },
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
    },
      success: function(response) {
        if (response.data == 'sukses') {
          Toast.fire({
            icon: 'success',
            text: 'Akses berhasil diperbaharui!'
          });
        } else {
          Toast.fire({
            icon: 'warning',
            text: 'Akses gagal diperbaharui!'
          });
          elemen.prop('checked', true);
        }
        $("#overlay").hide();
      },error: function(xhr, ajaxOptions, thrownError) {
        elemen.prop('checked', true);
        $("#overlay").hide(); 
        ToastError.fire({
          icon: 'error',
          text: 'Kegagalan sistem!'
        });
      }
    });
  }

})

$(document).ready(function () {
  addEmailRelaxtime();
});


function addEmailRelaxtime(){
  var selectedOptions = $('.multiple-pegawai-relaxtime').find('option:selected');
  var emailRelaxtime = [];

  selectedOptions.each(function () {
    var email = $(this).data('email');
    emailRelaxtime.push(email);
  });

  $(".email-relaxtime").val(emailRelaxtime.join(','));
}

$(document).on('change', 'input[name="batas_bawah"]', function(){
  $('.btn-relaxtime-1').attr('disabled', false)
  $('.btn-relaxtime-1').attr('title', 'Simpan Perubahan')
  $('.btn-relaxtime-1').removeClass('btn-secondary')
  $('.btn-relaxtime-1').addClass('btn-warning')
})
$(document).on('change', 'input[name="batas_atas"]', function(){
  $('.btn-relaxtime-2').attr('disabled', false)
  $('.btn-relaxtime-2').attr('title', 'Simpan Perubahan')
  $('.btn-relaxtime-2').removeClass('btn-secondary')
  $('.btn-relaxtime-2').addClass('btn-warning')
})
$(document).on('change', '.multiple-pegawai-relaxtime', function(){
  addEmailRelaxtime();
  $('.btn-relaxtime-3').attr('disabled', false)
  $('.btn-relaxtime-3').attr('title', 'Simpan Perubahan')
  $('.btn-relaxtime-3').removeClass('btn-secondary')
  $('.btn-relaxtime-3').addClass('btn-warning')
})
$(document).on('change', 'input[name="subjek"], textarea[name="isi"]', function(){
  $('.btn-relaxtime-4').attr('disabled', false)
  $('.btn-relaxtime-4').attr('title', 'Simpan Perubahan')
  $('.btn-relaxtime-4').removeClass('btn-secondary')
  $('.btn-relaxtime-4').addClass('btn-warning')
})

$(document).on('click', '.btn-relaxtime-1', function(){
  var data =  "batas_nilai_bawah, " + $(`input[name="batas_bawah"]`).val()
  updateSettingRelaxtime(data, $(this))
});
$(document).on('click', '.btn-relaxtime-2', function(){
  var data =  "batas_nilai_atas, " + $(`input[name="batas_atas"]`).val()
  updateSettingRelaxtime(data, $(this))
});
$(document).on('click', '.btn-relaxtime-3', function(){
  var data =  "id_users, " + $(`.multiple-pegawai-relaxtime`).val()
  updateSettingRelaxtime(data, $(this))
});
$(document).on('click', '.btn-relaxtime-4', function(){
  var data =  "subjek, " + $(`input[name="subjek"]`).val()+ ", " +'isi_pesan, ' + $(`textarea[name="isi"]`).val();
  updateSettingRelaxtime(data, $(this))
});

function updateSettingRelaxtime(getData, buttonClass) {
  $.ajax({
    url: '/relaxtime/updateSetting',
    data: {
      data : getData,
    },
    method: 'post',
    dataType: 'json',
    beforeSend: function() {
      $("#overlay").show();
    },
    success: function(response) {
      console.log(response.cek);
      if (response.data) {
        Toast.fire({
          icon: 'success',
          text: 'Data berhasil diperbaharui...'
        })
        buttonClass.attr('disabled', true)
        buttonClass.attr('title', '')
        buttonClass.removeClass('btn-warning')
        buttonClass.addClass('btn-secondary')
      } else {
        Toast.fire({
          icon: 'warning',
          text: 'Data gagal diperbaharui!'
        })
      }
      $("#overlay").hide();
    },error: function(xhr, ajaxOptions, thrownError) {
      // alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);      
      $("#overlay").hide();
      ToastError.fire({
        icon: 'error',
        // text: 'Kesalahan sistem!'
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
      })
    }
  });
}