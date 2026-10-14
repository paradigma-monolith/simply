$(document).ready(function () {
  viewArsipTemplateNaskah();
  viewArsipSurat();
  tampilSubstansi();

  if ($(".inputFile").prop("checked")) {
    $('input[type="file"]').eq(0).prop('disabled', false);
  } else {
    $('input[type="file"]').eq(0).prop('disabled', true);
  }
  $(".inputFile").eq(0).change(function () {
    var isChecked = $(this).prop("checked");
    $('input[type="file"]').eq(0).prop('disabled', !isChecked);
  });

  $('#pegawai').on('select2:opening', function(event) {
    event.preventDefault();
  });

  $('#substansi').on('select2:opening', function(event) {
    event.preventDefault();
  });
});

var nilaiAwalPegawai = $("#pegawai").val();
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

function tampilSubstansi() {
  $('#pegawai').on('change', function() {
    var selectedIdPegawai = $(this).val();
    var substansiSelect = $('#substansi');

    // Hapus semua opsi yang ada dalam sub_kategori
    substansiSelect.empty();

    // Tambahkan opsi default
    substansiSelect.append('<option value="">-- Pilih Substansi --</option>');

    if (selectedIdPegawai) {
      $.ajax({
        type: "get",
        url: "/get/substansi/",
        dataType: "json",
        success: function(response) {
          var optionAdded = false;
          var option = "";
          for (let j = 0; j < response.dataPegawai.length; j++) {
            var idPegawai = response.dataPegawai[j]['id_data_pegawai'];
            var substansiPegawai = response.dataPegawai[j]['id_substansi'];
            
            if (selectedIdPegawai == idPegawai) {
              console.log(substansiPegawai);
              for (let i = 0; i < response.dataSubstansi.length; i++) {
                var idSubstansi = response.dataSubstansi[i]['id_substansi'];
                var namaSubstansi = response.dataSubstansi[i]['nama_substansi'];
                var keteranganSubstansi = response.dataSubstansi[i]['keterangan_substansi'];

                var isSelected = (idSubstansi == substansiPegawai) ? 'selected' : '';

                option = '<option value="' + idSubstansi + '" ' + isSelected + '>' + namaSubstansi + ' | ' + keteranganSubstansi + '</option>';
                substansiSelect.append(option);
              }
            }
          }
        }
      });
    }
  });
}

$(document).on('submit', '#arkabSimpanSurat', function (e) {
  e.preventDefault();

  var formData = new FormData($(this)[0]);
  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/arkab/arsip_surat";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});

$("#arkabCheckAll").change(function () {
  var isChecked = $(this).prop("checked");
  viewArsipSurat(isChecked);
});

function viewArsipSurat(isChecked = '') {
  if (isChecked == true) {
    isChecked = 'true';
  }
  $.ajax({
    url: '/arkab/arsip_surat_list',
    method: 'get',
    data: {
      cekAll : isChecked
    },
    dataType: "json",
    success: function(response) {
      if (response.data) {
        // console.log(response.cek)
        if(response.cek != "") {
          $(".arkab_arsip_surat").html(response.data);
        } else {
          $(".arkab_arsip_surat").html(`<div class="text-center">Tidak ada data ditemukan.</div>`);
        }
      }
      formHapusData()
    },
    error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
}

function viewArsipTemplateNaskah() {
  $.ajax({
    url: '/tata_naskah_dinas/template_naskah_list',
    method: 'get',
    dataType: "json",
    success: function(response) {
      if (response.data) {
        console.log(response.cek);
        $(".template_naskah_list").html(response.data);
      } else {
        $(".template_naskah_list").html(`<div class="text-center">Tidak ada data ditemukan.</div>`);
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
}

$(document).on('submit', '#simpanTemplateNaskah', function (e) {
  e.preventDefault();
  
  // Ambil tombol submit
  var $submitButton = $(this).find('button[type="submit"]');

  // Ubah tombol jadi disabled dan tampilkan ikon loading
  $submitButton.prop('disabled', true);
  var originalButtonHtml = $submitButton.html(); // Simpan HTML asli tombol
  $submitButton.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

  var formData = new FormData($(this)[0]);
  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/tata_naskah_dinas/template_naskah";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }, complete: function() {
      // Kembalikan tombol ke keadaan semula
      $submitButton.prop('disabled', false);
      $submitButton.html(originalButtonHtml);
    }
  })
});