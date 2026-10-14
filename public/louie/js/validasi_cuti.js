$(document).ready(function() {
  console.log("Validasi cuti script loaded!");
  
  // Fungsi untuk memeriksa sisa cuti
  function cekSisaCuti(nip, jenisCuti, jmlHari, callback) {
    console.log("Memeriksa sisa cuti untuk NIP:", nip, "Jenis cuti:", jenisCuti, "Jumlah hari:", jmlHari);
    $.ajax({
      url: "/cuti/ajax_cek_sisa_cuti",
      type: "POST",
      data: {
        nip: nip,
        jenis_cuti: jenisCuti,
        jumlah_hari: jmlHari
      },
      dataType: "json",
      success: function(response) {
        console.log("Response dari server:", response);
        callback(response);
      },
      error: function(xhr, status, error) {
        console.error("Error saat memeriksa sisa cuti:", error);
        // Tampilkan detail error untuk debugging
        console.log("Error details:", xhr.responseText);
        
        // Coba parse response jika ada
        let errorMsg = "Pengajuan cuti melebihi sisa cuti yang tersedia! Silakan periksa kembali jumlah hari cuti yang diajukan.";
        try {
          if (xhr.responseText) {
            const jsonResponse = JSON.parse(xhr.responseText);
            if (jsonResponse && jsonResponse.message) {
              errorMsg = jsonResponse.message;
            }
          }
        } catch (e) {
          console.error("Gagal parse error response:", e);
        }
        
        callback({
          status: false,
          message: errorMsg
        });
      }
    });
  }

  // Fungsi untuk menghitung total hari cuti yang diajukan
  function hitungTotalHariCuti() {
    let totalHari = 0;
    
    // Jika jenis cuti adalah Cuti Melahirkan
    if ($("#jenis_cuti").val() === "Cuti Melahirkan") {
      totalHari = parseInt($("#selamaCutiMelahirkan").val() || 0);
    } else {
      // Untuk jenis cuti lainnya, hitung dari semua input selama[]
      $("input[name='selama[]']").each(function() {
        totalHari += parseInt($(this).val() || 0);
      });
    }
    
    return totalHari;
  }

  // Fungsi validasi form
  function validasiFormCuti(formId, e) {
    console.log("Validasi form:", formId);
    // Cek apakah jenis cuti adalah Cuti Tahunan
    if ($("#jenis_cuti").val() === "Cuti Tahunan") {
      console.log("Jenis cuti adalah Cuti Tahunan, melakukan validasi...");
      const nip = $("#pegawai").val();
      const jenisCuti = $("#jenis_cuti").val();
      const jmlHari = hitungTotalHariCuti();
      
      // Hentikan submit form
      e.preventDefault();
      
      // Tampilkan loading
      Swal.fire({
        title: 'Memeriksa sisa cuti...',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      
      // Periksa sisa cuti melalui AJAX
      cekSisaCuti(nip, jenisCuti, jmlHari, function(response) {
        // Tutup loading
        Swal.close();
        
        if (response.status === true) {
          // Jika sisa cuti mencukupi, lanjutkan submit form
          $("#" + formId)[0].submit();
        } else {
          // Jika sisa cuti tidak mencukupi, tampilkan pesan error
          Swal.fire({
            icon: 'error',
            title: 'Pengajuan Cuti Ditolak',
            html: response.message,
            confirmButtonText: 'OK'
          });
        }
      });
      
      return false;
    }
    
    // Jika bukan Cuti Tahunan, lanjutkan submit form tanpa validasi tambahan
    return true;
  }

  // Validasi form tambah cuti
  $("#tambahDataCuti").submit(function(e) {
    console.log("Form tambahDataCuti disubmit");
    return validasiFormCuti("tambahDataCuti", e);
  });
  
  // Validasi form update cuti
  $("#updateDataCuti").submit(function(e) {
    console.log("Form updateDataCuti disubmit");
    return validasiFormCuti("updateDataCuti", e);
  });
  
  // Log untuk memastikan form ada
  console.log("Form tambahDataCuti exists:", $("#tambahDataCuti").length);
  console.log("Form updateDataCuti exists:", $("#updateDataCuti").length);
});