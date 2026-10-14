// Global variables untuk charts
let statusChart, pelabuhanChart;

$(document).ready(function () {
    $('#datePengawasanKapal').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD' // Format tanggal
      },
      startDate: function() {
        const today = new Date();
        // Set ke 1 Januari tahun berjalan
        return new Date(today.getFullYear(), 0, 1);
      }(),
      endDate: new Date() // Set ke hari ini
    });

    // Initialize charts
    initializeCharts();
  
    $("#overlay").show();
  
    setTimeout(function() {
      ambil_data($('#datePengawasanKapal').val());
    }, 1000);

    $('#datePengawasanKapal').on('change', function() {
      ambil_data($(this).val());
    });

    // Event handler untuk tombol export Excel
    $('#btnExportExcel').on('click', function() {
      var dateRange = $('#datePengawasanKapal').val();
      var url = '/pengawasan_kapal/export_excel';
      
      if (dateRange) {
        url += '?dateRange=' + encodeURIComponent(dateRange);
      }
      
      // Redirect ke URL export
      window.open(url, '_blank');
    });
});

function initializeCharts() {
  // Status Chart (Pie Chart) - only initialize if element exists
  const statusElement = document.getElementById('statusChart');
  if (statusElement) {
    const statusCtx = statusElement.getContext('2d');
    statusChart = new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: ['SANDAR', 'KONFIRMASI', 'ZONA KARANTINA'],
        datasets: [{
          data: [0, 0, 0],
          backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  }

  // Pelabuhan Chart (Bar Chart) - only initialize if element exists
  const pelabuhanElement = document.getElementById('pelabuhanChart');
  if (pelabuhanElement) {
    const pelabuhanCtx = pelabuhanElement.getContext('2d');
    pelabuhanChart = new Chart(pelabuhanCtx, {
      type: 'bar',
      data: {
        labels: [],
        datasets: [{
          label: 'Jumlah Kapal',
          data: [],
          backgroundColor: '#007bff',
          borderColor: '#0056b3',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });
  }
}

function updateInfografis(data) {
  // Hitung statistik
  const stats = {
    total: data.length,
    dalamNegeri: 0,
    luarNegeri: 0,
    resikoTinggi: 0,
    status: { SANDAR: 0, KONFIRMASI: 0, 'ZONA KARANTINA': 0 },
    pelabuhan: {}
  };

  data.forEach(item => {
    // Kategori berdasarkan upload Master's Statement Letter
    if (item.surat_pernyataan_nahkoda && item.surat_pernyataan_nahkoda.trim() !== '') {
      stats.luarNegeri++;
    } else {
      stats.dalamNegeri++;
    }

    // Hitung resiko berdasarkan skoring
    let kresiko = item.kresiko;
    if (!kresiko && item.skoring) {
      const skoring = parseInt(item.skoring);
      if (skoring >= 20) kresiko = 'TINGGI';
      else if (skoring >= 10) kresiko = 'SEDANG';
      else kresiko = 'RENDAH';
    }
    if (kresiko === 'TINGGI') stats.resikoTinggi++;

    // Hitung status berdasarkan skoring
    let hasilStatus = item.hasilStatus;
    if (!hasilStatus && item.skoring) {
      const skoring = parseInt(item.skoring);
      if (skoring >= 20) hasilStatus = 'ZONA KARANTINA';
      else if (skoring >= 10) hasilStatus = 'KONFIRMASI';
      else hasilStatus = 'SANDAR';
    }
    if (stats.status[hasilStatus] !== undefined) {
      stats.status[hasilStatus]++;
    }

    // Hitung pelabuhan
    if (item.pelabuhan) {
      stats.pelabuhan[item.pelabuhan] = (stats.pelabuhan[item.pelabuhan] || 0) + 1;
    }
  });

  // Update cards - only if elements exist
  const totalKapalElement = $('#total-kapal');
  if (totalKapalElement.length) {
    totalKapalElement.text(stats.total);
    $('#dalam-negeri').text(stats.dalamNegeri);
    $('#luar-negeri').text(stats.luarNegeri);
    $('#resiko-tinggi').text(stats.resikoTinggi);
  }

  // Update status chart - only if chart exists
  if (statusChart) {
    statusChart.data.datasets[0].data = [
      stats.status.SANDAR,
      stats.status.KONFIRMASI,
      stats.status['ZONA KARANTINA']
    ];
    statusChart.update();
  }

  // Update pelabuhan chart - only if chart exists
  if (pelabuhanChart) {
    // Top 5 only for compact display
    const sortedPelabuhan = Object.entries(stats.pelabuhan)
      .sort(([,a], [,b]) => b - a)
      .slice(0, 5);
    const pelabuhanLabels = sortedPelabuhan.map(([label]) => label);
    const pelabuhanData = sortedPelabuhan.map(([,value]) => value);
    
    pelabuhanChart.data.labels = pelabuhanLabels;
    pelabuhanChart.data.datasets[0].data = pelabuhanData;
    pelabuhanChart.update();
  }
}

function ambil_data(dateRange = '') {
  // Show loading indicators
  showLoadingState();
  
  // Kirimkan data dateRange jika ada
  $.ajax({
    url: '/pengawasan_kapal/data_pengawasan_kapal',
    method: 'get',
    data: { dateRange: dateRange }, // Kirimkan dateRange jika ada
    dataType: "json",
    beforeSend: function() {
      // Pastikan overlay disembunyikan saat mulai request
      $("#overlay").hide();  
    },
    success: function(response) {
      if (response.data) {
        $(".data-pengawasan-kapal").html(response.data);
      }
      if (response.pengawasanKapal) {
        // Update infografis dengan data mentah
        updateInfografis(response.pengawasanKapal);
      }
      if(response.redirect) {
          ToastError.fire({
            icon: 'error',
            text: 'Akses diblokir!'
          })
          setTimeout(() => {
            window.location=response.redirect
          }, 3000);
          $("#overlay").show();
      } else {
        // Pastikan overlay disembunyikan jika tidak ada redirect
        $("#overlay").hide();
      }
      
      // Hide loading indicators
      hideLoadingState();
    },
    error: function(xhr, ajaxOptions, thrownError) {
        console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        
        // Pastikan overlay disembunyikan saat error
        $("#overlay").hide();
        hideLoadingState();
        
        // Show error message
        ToastError.fire({
          icon: 'error',
          text: 'Gagal memuat data!'
        });
    },
    complete: function() {
      // Pastikan loading state selalu dihilangkan
      hideLoadingState();
      // Pastikan overlay disembunyikan kecuali ada redirect
      if (!$('.swal2-container').length) { // Jika tidak ada SweetAlert yang aktif
        $("#overlay").hide();
      }
      
      // Inisialisasi ulang tooltip setelah data di-refresh
      $('[data-toggle="tooltip"]').tooltip();
    }
  });
}

function showLoadingState() {
  // Show loading in info boxes
  $('#total-kapal').html('<i class="fas fa-spinner fa-spin"></i>');
  $('#dalam-negeri').html('<i class="fas fa-spinner fa-spin"></i>');
  $('#luar-negeri').html('<i class="fas fa-spinner fa-spin"></i>');
  $('#resiko-tinggi').html('<i class="fas fa-spinner fa-spin"></i>');
  
  // Show loading in table area
  $(".data-pengawasan-kapal").html(`
    <div class="text-center p-4">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
      <p class="mt-2 text-muted">Memuat data pengawasan kapal...</p>
    </div>
  `);
  
  // Show loading overlay on charts
  $('.card-body canvas').each(function() {
    $(this).parent().append(`
      <div class="chart-loading-overlay">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    `);
  });
}

function hideLoadingState() {
  // Remove chart loading overlays
  $('.chart-loading-overlay').remove();
}