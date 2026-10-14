$(document).ready(function () {
  var nilaiAwalPegawai = $("#pegawai").val();
  
  // Load semua data tanpa filter
  viewSemuaDokumen();
    
  // Event handler untuk rename file otomatis
  $('#unggah_dokumen').on('change', function() {
    updateFileName();
  });
  
  // Update nama file saat dropdown berubah
  $('#pegawai, select[name="jenis_dokumen"], select[name="tanggal_dokumen"]').on('change', function() {
    updateFileName();
  });
    
  if ($('#customSwitch1').prop('checked')) {
    $('#unggah_dokumen').prop('disabled', false);
  } else {
      $('#unggah_dokumen').prop('disabled', true);
  }

  $('#customSwitch1').change(function () {
      var isChecked = $(this).prop('checked');
      $('#unggah_dokumen').prop('disabled', !isChecked);
  });

  $('#pegawai').on('select2:opening', function(event) {
    event.preventDefault();
  });
  
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

  // Filter Tahun LHKPN SPT
  $(document).on('change', '#filter_tahun', function() {
      var year = $(this).val();
      if(year) {
          viewDokumenByYear(year);
      } else {
          viewSemuaDokumen(); 
      }
  });

  $(document).on('click', '#btn_download_zip', function() {
      var year = $('#filter_tahun').val();
      if(year) {
          window.location.href = '/lhkpn_spt/download_zip/' + year;
      }
  });

  $(document).on('click', '#btn_export_excel', function() {
      var year = $('#filter_tahun').val();
      if(year) {
          window.location.href = '/lhkpn_spt/export_excel/' + year;
      }
  });
});

function viewDokumen() {
  $.ajax({
    url: '/lhkpn_spt/dokumen_lhkpn_spt',
    method: 'get',
    dataType: "json",
    success: function(data) {
      $(".dokumen_lhkpn_spt").html(data.data)
    },
    error: function(xhr, ajaxOptions, thrownError) {
      Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    }
  })
}

function viewSemuaDokumen() {
  // Tampilkan semua data tanpa filter tahun
  // Gunakan range tahun yang lebar (2020-2030) untuk menampilkan semua data
  const startDate = '2020-01-01';
  const endDate = '2030-12-31';
  
  $.ajax({
      url: `/lhkpn_spt/dokumen_lhkpn_spt/${startDate}/${endDate}`,
      method: 'get',
      dataType: "json",
      success: function(data) {
          $(".dokumen_lhkpn_spt").html(data.data);
      },
      error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
  });
}

function updateFileName() {
  const fileInput = document.getElementById('unggah_dokumen');
  const file = fileInput.files[0];
  
  if (file) {
    const pegawaiText = $('#pegawai option:selected').text();
    const pegawai = pegawaiText.split(' | ')[1] || '';
    const jenisDokumen = $('select[name="jenis_dokumen"]').val() || '';
    const tahun = $('select[name="tanggal_dokumen"]').val() || '';
    const ext = file.name.split('.').pop();
    
    // Validasi bahwa semua field terisi
    if (!pegawai || !jenisDokumen || !tahun) {
      $(fileInput).next('.custom-file-label').html('Pilih File PDF/Gambar');
      return;
    }
    
    // Format nama file: pemilik_jenis_dokumen_tahun.ext
    // Hapus spasi dan karakter khusus dari nama pegawai
    const cleanPegawai = pegawai.replace(/[^a-zA-Z0-9]/g, '_');
    const newFileName = `${cleanPegawai}_${jenisDokumen}_${tahun}.${ext}`;
    
    // Update label file
    $(fileInput).next('.custom-file-label').html(newFileName);
    
    // Create new File object with renamed file
    const renamedFile = new File([file], newFileName, { type: file.type });
    
    // Create new FileList with renamed file
    const dt = new DataTransfer();
    dt.items.add(renamedFile);
    fileInput.files = dt.files;
  }
}

$(document).on('submit', '#simpanDokumenLhkpnSpt', function (e) {
  e.preventDefault();

  // Validasi dropdown jenis dokumen
  const jenisDokumen = $('select[name="jenis_dokumen"]').val();
  if (!jenisDokumen) {
    Swal.fire({
      icon: 'warning',
      title: 'Peringatan',
      text: 'Silakan pilih jenis dokumen terlebih dahulu!',
    });
    return;
  }

  var formData = new FormData($(this)[0]);
  $.ajax({
    url: $(this).attr('action'),
    type: "post",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function(response) {
        // console.log(response)
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Data berhasil disimpan.',
        showConfirmButton: false,
        allowOutsideClick: false,
      },
      setTimeout(() => {
        window.location = "/lhkpn_spt/daftar_dokumen";
      }, "1500"))
    }, error: function(xhr, ajaxOptions, thrownError) {
    //   alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
      })
    }
  })
});

// Event handler untuk konfirmasi delete
$(document).on('submit', '.formHapusData', function(e) {
  e.preventDefault();
  
  const form = this;
  
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: "Data yang dihapus tidak dapat dikembalikan!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      // Submit form jika user mengkonfirmasi
      $.ajax({
        url: $(form).attr('action'),
        type: 'post',
        data: new FormData(form),
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: 'Data berhasil dihapus.',
              showConfirmButton: false,
              allowOutsideClick: false,
            });
            
            // Reload halaman setelah delete
            setTimeout(() => {
              window.location.reload();
            }, "1500");
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: response.error || 'Terjadi kesalahan saat menghapus data!',
            });
          }
        },
        error: function(xhr, ajaxOptions, thrownError) {
          let errorMessage = 'Terjadi kesalahan saat menghapus data!';
          try {
            const response = JSON.parse(xhr.responseText);
            errorMessage = response.error || errorMessage;
          } catch (e) {
            errorMessage = xhr.statusText || errorMessage;
          }
          
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessage,
          });
        }
      });
    }
  });
});

function viewDokumenByYear(year) {
  $.ajax({
      url: '/lhkpn_spt/dokumen_lhkpn_spt_by_year/' + year,
      method: 'get',
      dataType: "json",
      success: function(data) {
          $(".dokumen_lhkpn_spt").html(data.data);
      },
      error: function(xhr, ajaxOptions, thrownError) {
          Swal.fire("", xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
      }
  });
}