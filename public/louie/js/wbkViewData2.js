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

  $('#pegawai').on('select2:opening', function(event) {
    event.preventDefault();
  });

  $('#pokja').on('select2:opening', function(event) {
    event.preventDefault();
  });

  tampilKategori();
  viewRencanaKerja();
  viewArsip();
});

$("#tahunRencanaKerja").change(function(){
  viewRencanaKerjaTahun($(this).val())
})

$("#tahunArsip").change(function(){
  viewArsipTahun($(this).val())
})

function viewRencanaKerja() {
  $.ajax({
    url: "/wbk/rencana_kerja_list",
    method: "get",
    dataType: "json",
    success: function(response) {
      if (response.rencanaKerja) {
        $(".view_rencana_kerja").html(response.rencanaKerja);
      } else {
        console.error("Error in response:", response)
      }
    },
  })
}

function viewRencanaKerjaTahun(id) {
  $.ajax({
    url: "/wbk/rencana_kerja_list/"+id,
    method: "get",
    dataType: "json",
    success: function(response) {
      if (response.rencanaKerja) {
        $(".view_rencana_kerja").html(response.rencanaKerja);
      } else {
        console.error("Error in response:", response)
      }
    },
  })
}

function viewArsip() {
  $.ajax({
    url: "/wbk/arsip_list",
    method: "get",
    dataType: "json",
    success: function(response) {
      if (response.wbkArsip) {
        $(".arsip").html(response.wbkArsip);
      } else {
        console.error("Error in response:", response)
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
}

function viewArsipTahun(id) {
  $.ajax({
    url: "/wbk/arsip_list/"+id,
    method: "get",
    dataType: "json",
    success: function(response) {
      if (response.wbkArsip) {
        $(".arsip").html(response.wbkArsip);
      } else {
        console.error("Error in response:", response)
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
}

$('select[name="target_waktu"]').change(function () {
  var selectedMonth = $(this).val();
  var currentMonth = new Date().getMonth() + 1;

  console.log(selectedMonth);
  console.log(currentMonth);

  if (selectedMonth != currentMonth) {
    // Tampilkan label peringatan
    showWarningLabel();
  } else {
    // Sembunyikan label peringatan jika bulan yang dipilih sesuai
    hideWarningLabel();
  }
});

// Fungsi untuk menampilkan label peringatan
function showWarningLabel() {
  var warningLabel = $("#warningLabel");
  if (warningLabel) {
    warningLabel.show();
  }
}

// Fungsi untuk menyembunyikan label peringatan
function hideWarningLabel() {
  var warningLabel = $("#warningLabel");
  if (warningLabel) {
    warningLabel.hide();
  }
}

function tampilKategori() {
  $('#kategori').on('change', function() {
    var selectedKategori = $(this).val();
    var subKategoriSelect = $('#sub_kategori');

    // Hapus semua opsi yang ada dalam sub_kategori
    subKategoriSelect.empty();

    // Tambahkan opsi default
    subKategoriSelect.append('<option value="">-- Pilih Sub Kategori Dokumen --</option>');

    if (selectedKategori) {
      // Lakukan Ajax request untuk mengambil subkategori berdasarkan kategori yang dipilih
      $.ajax({
        type: "get",
          url: "/get/kategori/",
          dataType: "json",
          success: function(response) {
            for(let i=0; i < response.dataKategori.length; i++){
              var idKategori = response.dataKategori[i]['id_wbk_kategori'];
              var noKategori = response.dataKategori[i]['no'];
              var jenisKategori = response.dataKategori[i]['jenis'];
                
              if (idKategori == selectedKategori) {
                var optionAdded = false;

                for (let j=0; j < response.dataSubKategori.length; j++) {
                  var idSubKategori = response.dataSubKategori[j]['id_wbk_kategori'];
                  var noSubKategori = response.dataSubKategori[j]['no'];
                  var poinSubKategori = response.dataSubKategori[j]['poin'];
                  var namaSubKategori = response.dataSubKategori[j]['keterangan'];
                  var jenisSubKategori = response.dataSubKategori[j]['jenis'];
                  var option = "";

                  if (jenisSubKategori == jenisKategori) {
                    if (noSubKategori == noKategori) {
                      option = '<option value="'+idSubKategori+ '">'+noSubKategori+'.'+poinSubKategori+ '. '+namaSubKategori+ '</option>';
                      optionAdded = true;
                    }
                  }
                  subKategoriSelect.append(option);
                }
                if (!optionAdded) {
                  // Jika tidak ada opsi yang cocok, tambahkan opsi kosong
                  subKategoriSelect.append('<option value="" disabled>Tidak ada data</option>');
                }
              }
            }
          }
      });
    }
  });
}

$(document).on('submit', '#wbkSimpanRencanaKerja', function (e) {
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
        window.location = "/wbk/rencana_kerja";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});

$(document).on('submit', '#wbkSimpanFileWbk', function (e) {
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
        window.location = "/wbk/daftar_dokumen";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
});
