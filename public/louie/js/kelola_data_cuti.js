$("#cekSignature").change(function() {
  if($(this).is(":checked")) {
    $("#signature-pad").css('display', 'block');
    $("#signature-pad-disabled").css('display', 'none');
  } else {
    $("#signature-pad").css('display', 'none');
    $("#signature-pad-disabled").css('display', 'block');
  }
});
$(function() {
  jQuery(document).ready(function() {
    
    if($('#jenis_cuti').val() != 'Cuti Melahirkan'){
      $("#hitunganWaktuCutiMelahirkan").hide()
    }else{
      $("#hitunganWaktuRange").hide()      
    }
    date();
    countOfTheDay()

    const jmlTgl = $('[class^=countData]').length
    for (let i = 1; i < jmlTgl; i++) {
      var dataKe = parseInt($("#dateRange-value").val()) + 1
      $("#dateRange-value").val(dataKe)

      $(".multiple-column").append(
        '<div class="form-group row">' +
        '<label class="col-sm-2"><span class="min">-</span></label>' +
        '<div class="col-sm-10 row">' +
        '<label class="has-float-label col-5">' +
        '<input type="text" name="rentan_waktu[]" data-val="' + dataKe + '" value="" class="form-control tanggal' + i + ' dateRange" id="" placeholder="Selama" value="">' +
        '<span>Rentan Waktu</span>' +
        '</label>' +
        '<label class=" col-7">' +
        '<span class="form-control selamaView' + dataKe + '" id="" readonly>1 (hari/<strike>bulan/tahun)</strike></span>' +
        '<input type="hidden" name="selama[]" class="form-control selama' + dataKe + '" id="" readonly placeholder="(hari/bulan/tahun)" value="">' +
        '<span></span>' +
        '</label>' +
        '</div>' +
        '</div>'
      );
      $('.multiple-column .dateRange:last').daterangepicker({
        isInvalidDate: function(date) {
          if (date.day() >= 1 && date.day() <= 5)
            return false;
          return true;
        },
        timePicker: false,
        locale: {
          format: 'YYYY/MM/DD'
        }
      });
    }
    for (let i = 0; i < jmlTgl; i++) {
      $(".tanggal" + i).daterangepicker({
        isInvalidDate: function(date) {
          if (date.day() >= 1 && date.day() <= 5)
            return false;
          return true;
        },
        startDate: $("#tanggalAwal" + i).val(),
        endDate: $("#tanggalAkhir" + i).val(),
        timePicker: false,
        locale: {
          format: 'YYYY/MM/DD'
        }
      });
    }
    for (let k = 0; k < jmlTgl; k++) {
      var value = $("#totalValue" + k).val()
      // console.log(value)
      var dataKe = k + 1

      var dateRange = $('.dateRange').eq(k).val()
      // console.log(dateRange)

      const myArray = dateRange.split(" - ");

      var dari = myArray[0];
      var ke = myArray[1];

      var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
      var start = new Date(dari);
      var end = new Date(ke);

      var totalBusinessDays = 0;
      start.setHours(0, 0, 0, 0);
      end.setHours(0, 0, 0, 0);
      var current = new Date(start);
      current.setDate(current.getDate() + 1);
      var day;
      // loop through each day, checking
      while (current <= end) {
        day = current.getDay();
        if (day >= 1 && day <= 5) {
          ++totalBusinessDays;
        }
        current.setDate(current.getDate() + 1);
      }
      var diffDays = totalBusinessDays + 1
      // var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
      // diffDays += 1;
      var tahun = diffDays;
      var bulan = diffDays;
      var lama;

      var text1 = 'bulan/tahun';
      var text2_1 = 'hari';
      var text2_2 = 'tahun';
      var text3 = 'hari/bulan';

      if (diffDays <= 30) {
        lama = diffDays + " (hari/" + text1.strike() + ")";
      }
      if (diffDays > 30 && diffDays < 365) {
        bulan = (diffDays / 30).toFixed(2);
        var str = bulan.split(".")
        if (str[1] > 50) {
          lama = (parseInt(bulan) + 1) + " (" + text2_1.strike() + "/bulan/" + text2_2.strike() + ")"
        } else {
          lama = parseInt(bulan) + " (" + text2_1.strike() + "/bulan/" + text2_2.strike() + ")"
        }

      }
      if (diffDays >= 365) {
        tahun = (diffDays / 365).toFixed(2);
        var str = tahun.split(".")
        if (str[1] > 50) {
          lama = (parseInt(tahun) + 1) + " (" + text3.strike() + "tahun)"
        } else {
          lama = parseInt(tahun) + " (" + text3.strike() + "tahun)"
        }

      }

      // console.log(lama)
      $(".selamaView" + dataKe).html(lama)
      $(".selama" + (k + 1)).val(value)
    }


    var nip = $("#pegawai").val()
    if (nip = $("#pegawai").val()) {
      // console.log(nip)
      $.ajax({
        type: "get",
        url: '/admin/ajax_getPegawai',
        data: {
          nip: nip
        },
        dataType: "json",
        success: function(response) {
          // const myArray = text.split("|");
          var masa = '';

          var waktuSelisih = response.sukses['masa_kerja']
          let sisa_hari = 0;
          const myArray0 = Math.floor(waktuSelisih / 365)
          sisa_hari = waktuSelisih % 365;
          const myArray1 = Math.floor(sisa_hari / 30);
          sisa_hari = waktuSelisih % 30;
          const myArray2 = sisa_hari;
          // console.log(response.sukses)
          if (myArray0 != 0) {
            masa = masa + myArray0 + " TAHUN "
          }
          if (myArray1 != 0) {
            masa = masa + myArray1 + " BULAN "
          }
          if (myArray2 != 0) {
            masa = masa + myArray2 + " HARI "
          }
          if (masa == '') {
            masa = '-'
          }
          if (response.sukses) {
            $("#jabatanPegawai").val(response.sukses['jabatan'])
            $("#golonganPegawai").val(response.sukses['golongan'])
            $("#masaKerjaPegawai").val(masa)
            $("#unitKerjaPegawai").val(response.sukses['unit_kerja'])
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // console.log(textStatus, errorThrown);
        }

      });
    } else {
      $("#jabatanPegawai").val('')
      $("#golonganPegawai").val('')
      $("#masaKerjaPegawai").val('')
      $("#unitKerjaPegawai").val('')
    }
    // ===========================================================================

    $("#skema_titimangsa").change(function() {
      var element = $("#skema_titimangsa").find('option:selected');
      const jabatan_kepegawaian = element.attr('data-jabatan-kepegawaian');
      const nama_kepegawaian = element.attr('data-nama-kepegawaian');
      const nip_kepegawaian = element.attr('data-nip-kepegawaian');
      const jabatan_atasan = element.attr('data-jabatan-atasan');
      const nama_atasan = element.attr('data-nama-atasan');
      const nip_atasan = element.attr('data-nip-atasan');
      const jabatan_pejabat = element.attr('data-jabatan-pejabat');
      const nama_pejabat = element.attr('data-nama-pejabat');
      const nip_pejabat = element.attr('data-nip-pejabat');
      const unit_kerja = element.attr('data-unit-kerja');
      const lokasi = element.attr('data-lokasi');

      $("#jabatan_kepegawaian").val(jabatan_kepegawaian);
      $("#nama_kepegawaian").val(nama_kepegawaian);
      $("#nip_kepegawaian").val(nip_kepegawaian);
      $("#jabatan_atasan").val(jabatan_atasan);
      $("#nama_atasan").val(nama_atasan);
      $("#nip_atasan").val(nip_atasan);
      $("#jabatan_pejabat").val(jabatan_pejabat);
      $("#nama_pejabat").val(nama_pejabat);
      $("#nip_pejabat").val(nip_pejabat);
      $("#unit_kerja_skema").val(unit_kerja);
      $("#lokasi_skema").val(lokasi);

    });

    $("#pegawai").change(function() {
      // alert();
      var nip = $("#pegawai").val()

      var cekJK = nip.slice(-4, -3);

      if(cekJK == '1'){
        $('#jenis_cuti option[value="Cuti Melahirkan"]').prop('disabled', true);
        if($('#jenis_cuti').val() == 'Cuti Melahirkan'){
          $('#jenis_cuti').val('');
        }
      }else{
        $('#jenis_cuti option[value="Cuti Melahirkan"]').prop('disabled', false);
      }

      if (nip != '') {
        $.ajax({
          type: "get",
          url: '/admin/ajax_getPegawai',
          data: {
            nip: nip
          },
          dataType: "json",
          success: function(response) {
            var text = response.sukses['masa_kerja'];
            // const myArray = text.split("|");
          var masa = '';

          var waktuSelisih = response.sukses['masa_kerja']
          let sisa_hari = 0;
          const myArray0 = Math.floor(waktuSelisih / 365)
          sisa_hari = waktuSelisih % 365;
          const myArray1 = Math.floor(sisa_hari / 30);
          sisa_hari = waktuSelisih % 30;
          const myArray2 = sisa_hari;
          // console.log(response.sukses)
          if (myArray0 != 0) {
            masa = masa + myArray0 + " TAHUN "
          }
          if (myArray1 != 0) {
            masa = masa + myArray1 + " BULAN "
          }
          if (myArray2 != 0) {
            masa = masa + myArray2 + " HARI "
          }
          if (masa == '') {
            masa = '-'
          }
            if (response.sukses) {
              $("#jabatanPegawai").val(response.sukses['jabatan'])
              $("#golonganPegawai").val(response.sukses['golongan'])
              $("#masaKerjaPegawai").val(masa)
              $("#unitKerjaPegawai").val(response.sukses['unit_kerja'])
            }
          }
        });
        // return false;
      } else {
        $("#jabatanPegawai").val('')
        $("#golonganPegawai").val('')
        $("#masaKerjaPegawai").val('')
        $("#unitKerjaPegawai").val('')
      }
    });

    function date() {
      //Date range picker
      $('.dateRange').daterangepicker({
        isInvalidDate: function(date) {
          if (date.day() >= 1 && date.day() <= 5)
            return false;
          return true;
        },
        // startDate: "2023/03/01", 
        // endDate: "2023/03/24",
        timePicker: false,
        locale: {
          format: 'YYYY/MM/DD'
        }
      });
      $('#date').datetimepicker({
        format: 'L',
        format: 'YYYY/MM/DD',
        defaultDate: moment()
      });
      $('#dates').datetimepicker({
        format: 'L',
        format: 'YYYY/MM/DD',
        defaultDate: moment()
      });
    }

    $(".plus").click(function() {
      var dataKe = parseInt($("#dateRange-value").val()) + 1
      $("#dateRange-value").val(dataKe)

      $(".multiple-column").append(
        '<div class="form-group row">' +
        '<label class="col-sm-2"><span class="min">-</span></label>' +
        '<div class="col-sm-10 row">' +
        '<label class="has-float-label col-5">' +
        '<input type="text" name="rentan_waktu[]" data-val="' + dataKe + '" value="" class="form-control dateRange" id="" placeholder="Selama" value="2023/03/01 - 2023/03/21">' +
        '<span>Rentan Waktu</span>' +
        '</label>' +
        '<label class=" col-7">' +
        '<span class="form-control selamaView' + dataKe + '" id="" readonly>1 (hari/<strike>bulan/tahun)</strike></span>' +
        '<input type="hidden" name="selama[]" class="form-control selama' + dataKe + '" id="" readonly placeholder="(hari/bulan/tahun)" value="">' +
        '<span></span>' +
        '</label>' +
        '</div>' +
        '</div>'
      );

      $('.multiple-column .dateRange:last').daterangepicker({
        isInvalidDate: function(date) {
          if (date.day() >= 1 && date.day() <= 5)
            return false;
          return true;
        },
        timePicker: false,
        locale: {
          format: 'YYYY/MM/DD'
        }
      });
      countOfTheDay()

    });

    // $(".min").click(function(){
    //  $(this).parent().parent().remove();
    //   // var dataKe = parseInt($("#dateRange-value").val()) - 1
    //   // $("#dateRange-value").val(dataKe)
    // });

    $(".dateRange").change(function() {
      var dataKe = $(this).attr('data-val')

      var dateRange = $(this).val()
      // console.log(dateRange)

      const myArray = dateRange.split(" - ");

      var dari = myArray[0];
      var ke = myArray[1];

      var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
      var start = new Date(dari);
      var end = new Date(ke);

      var totalBusinessDays = 0;
      start.setHours(0, 0, 0, 0);
      end.setHours(0, 0, 0, 0);
      var current = new Date(start);
      current.setDate(current.getDate() + 1);
      var day;
      // loop through each day, checking
      while (current <= end) {
        day = current.getDay();
        if (day >= 1 && day <= 5) {
          ++totalBusinessDays;
        }
        current.setDate(current.getDate() + 1);
      }
      var diffDays = totalBusinessDays + 1
      // var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
      // diffDays += 1;
      var tahun = diffDays;
      var bulan = diffDays;
      var lama;

      var text1 = 'bulan/tahun';
      var text2_1 = 'hari';
      var text2_2 = 'tahun';
      var text3 = 'hari/bulan';

      if (diffDays <= 30) {
        lama = diffDays + " (hari/" + text1.strike() + ")";
      }
      if (diffDays > 30 && diffDays < 365) {
        bulan = (diffDays / 30).toFixed(2);
        var str = bulan.split(".")
        if (str[1] > 50) {
          lama = (parseInt(bulan) + 1) + " (" + text2_1.strike() + "/bulan/" + text2_2.strike() + ")"
        } else {
          lama = parseInt(bulan) + " (" + text2_1.strike() + "/bulan/" + text2_2.strike() + ")"
        }

      }
      if (diffDays >= 365) {
        tahun = (diffDays / 365).toFixed(2);
        var str = tahun.split(".")
        if (str[1] > 50) {
          lama = (parseInt(tahun) + 1) + " (" + text3.strike() + "tahun)"
        } else {
          lama = parseInt(tahun) + " (" + text3.strike() + "tahun)"
        }

      }

      // console.log(lama)
      $(".selamaView" + dataKe).html(lama)
      $(".selama" + dataKe).val(diffDays)
    });

  });
  $("#jenis_cuti").change(function(){
    if($(this).val() == 'Cuti Melahirkan'){
      $("#hitunganWaktuRange").find(".col-sm-10.row").find("input").attr('disabled', 'disabled')
      $(".multiple-column").text('');
      $("#hitunganWaktuRange").hide()
      
      $("#hitunganWaktuCutiMelahirkan").find(".col-sm-10.row").find("input").removeAttr('disabled')
      $("#hitunganWaktuCutiMelahirkan").show()
    }else{      
      $("#hitunganWaktuRange").find(".col-sm-10.row").find("input").removeAttr('disabled')
      $("#hitunganWaktuRange").show()
      
      $("#hitunganWaktuCutiMelahirkan").find(".col-sm-10.row").find("input").attr('disabled', 'disabled')
      $("#hitunganWaktuCutiMelahirkan").hide()
    }
  });
  function countOfTheDay() {
    $(".min").click(function() {
      $(this).parent().parent().remove();
      // var dataKe = parseInt($("#dateRange-value").val()) - 1
      // $("#dateRange-value").val(dataKe)
    });
    $(".dateRange").change(function() {
      var dataKe = $(this).attr('data-val')

      var dateRange = $(this).val()
      // console.log(dateRange)

      const myArray = dateRange.split(" - ");

      var dari = myArray[0];
      var ke = myArray[1];

      var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
      var start = new Date(dari);
      var end = new Date(ke);

      var totalBusinessDays = 0;
      start.setHours(0, 0, 0, 0);
      end.setHours(0, 0, 0, 0);
      var current = new Date(start);
      current.setDate(current.getDate() + 1);
      var day;
      // loop through each day, checking
      while (current <= end) {
        day = current.getDay();
        if (day >= 1 && day <= 5) {
          ++totalBusinessDays;
        }
        current.setDate(current.getDate() + 1);
      }
      var diffDays = totalBusinessDays + 1
      // var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
      // diffDays += 1;
      var tahun = diffDays;
      var bulan = diffDays;
      var lama;

      var text1 = 'bulan/tahun';
      var text2_1 = 'hari';
      var text2_2 = 'tahun';
      var text3 = 'hari/bulan';

      if (diffDays <= 30) {
        lama = diffDays + " (hari/" + text1.strike() + ")";
      }
      if (diffDays > 30 && diffDays < 365) {
        bulan = (diffDays / 30).toFixed(2);
        var str = bulan.split(".")
        if (str[1] > 50) {
          lama = (parseInt(bulan) + 1) + " (" + text2_1.strike() + "/bulan/" + text2_2.strike() + ")"
        } else {
          lama = parseInt(bulan) + " (" + text2_1.strike() + "/bulan/" + text2_2.strike() + ")"
        }

      }
      if (diffDays >= 365) {
        tahun = (diffDays / 365).toFixed(2);
        var str = tahun.split(".")
        if (str[1] > 50) {
          lama = (parseInt(tahun) + 1) + " (" + text3.strike() + "tahun)"
        } else {
          lama = parseInt(tahun) + " (" + text3.strike() + "tahun)"
        }

      }

      // console.log(lama)
      $(".selamaView" + dataKe).html(lama)
      $(".selama" + dataKe).val(diffDays)
    });
  }
});