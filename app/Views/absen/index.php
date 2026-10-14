<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balai Kekarantinaan Kesehatan Kelas I Bandung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <script>
    // Pengecekan Platform Android & Browser vs WebView
    (function() {
      var userAgent = navigator.userAgent || navigator.vendor || window.opera;
      
      // Jika perangkat adalah Android
      if (/android/i.test(userAgent)) {
          // Deteksi apakah sedang dibuka di dalam WebView aplikasi buatan kita sendiri
          // WebView Android biasanya menyertakan kata 'wv' dalam User-Agent-nya, 
          // tapi untuk lebih spesifik dan aman (mencegah loop), kita cek header X-Requested-With
          var isWebView = /wv/.test(userAgent) || (window.navigator && window.navigator.standalone) || document.referrer.includes('android-app://');
          
          if (!isWebView) {
              // Jika dibuka di browser Chrome/lainnya (bukan WebView APK), tampilkan peringatan
              window.onload = function() {
                  document.body.innerHTML = `
                    <div style="display:flex;flex-direction:column;justify-content:center;align-items:center;height:100vh;background:#f8f9fa;font-family:sans-serif;text-align:center;padding:20px;">
                        <h2 style="color:#d9534f;margin-bottom:15px;">Akses Dibatasi</h2>
                        <p style="font-size:1.1rem;color:#555;margin-bottom:25px;">Untuk pengguna Android, mohon buka halaman ini melalui <strong>Aplikasi Simply</strong> untuk fitur keamanan dan lokasi yang optimal.</p>
                        <a href="https://simply.bkkbandung.net/apk/simply.apk" style="background-color:#007bff;color:white;padding:12px 24px;text-decoration:none;border-radius:8px;font-weight:bold;font-size:1rem;box-shadow:0 4px 6px rgba(0,0,0,0.1);">⬇ Download Aplikasi APK</a>
                    </div>
                  `;
              };
          }
      }
      // Jika perangkat iOS (iPhone/iPad/iPod), biarkan saja lewat (bisa diakses via browser Safari)
    })();
  </script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

    #toolbarContainer {
        display: none;
    }
    
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #000;
      overflow: hidden;
      color: #fff;
    }

    /* Video Background */
    #video {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      z-index: -1;
      transform: scaleX(-1);
    }

    /* Preloader Modern */
    .preloader {
      width: 100%;
      height: 100vh;
      position: fixed;
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      transition: opacity 0.5s ease;
    }
    
    .preloader-logo {
        width: 120px;
        height: auto;
        margin-bottom: 20px;
        animation: pulse 2s infinite ease-in-out;
        filter: drop-shadow(0 0 10px rgba(255,255,255,0.3));
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    .loader-container {
        width: 200px;
        text-align: center;
    }
    
    .loader-text {
        color: #fff;
        font-size: 1.2rem;
        font-weight: 500;
        margin-bottom: 15px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    
    .progress-bar-container {
        width: 100%;
        height: 6px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: #00d2ff;
        background: linear-gradient(90deg, #00d2ff 0%, #3a7bd5 100%);
        width: 0%;
        border-radius: 10px;
        position: relative;
        box-shadow: 0 0 10px rgba(0, 210, 255, 0.7);
    }
    
    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-image: linear-gradient(
            -45deg,
            rgba(255, 255, 255, 0.2) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.2) 50%,
            rgba(255, 255, 255, 0.2) 75%,
            transparent 75%,
            transparent
        );
        z-index: 1;
        background-size: 20px 20px;
        animation: move 1s linear infinite;
        border-radius: 10px;
    }
    
    @keyframes move {
        0% { background-position: 0 0; }
        100% { background-position: 20px 20px; }
    }

    /* Header */
    .header {
      position: absolute;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: 90%;
      max-width: 600px;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(2px);
      -webkit-backdrop-filter: blur(2px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      padding: 15px;
      text-align: center;
      z-index: 10;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .desc {
      color: #fff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.5);
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    #info {
        font-weight: 600;
        color: #ffd700; /* Gold color for info */
        text-shadow: 0 1px 2px rgba(0,0,0,0.8);
    }

    /* Footer Glassmorphism */
    .footer {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: 95%;
      max-width: 500px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(2px);
      -webkit-backdrop-filter: blur(2px);
      border-radius: 24px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      padding: 15px;
      text-align: center;
      z-index: 12;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    /* Absen Type Buttons */
    .absen-type-container {
      display: flex;
      justify-content: center;
      gap: 8px;
      align-self: center;
      margin-bottom: 2px;
      align-items: center; /* Untuk mensejajarkan tombol secara vertikal */
    }

    .absen-selector-group {
      display: flex;
      background: rgba(0, 0, 0, 0.3); /* Latar belakang lebih gelap agar tombol putih menonjol */
      padding: 4px;
      border-radius: 30px;
      gap: 5px;
    }

    .absen-type-btn {
      padding: 8px 24px;
      border: none;
      border-radius: 25px;
      background: transparent;
      color: rgba(255, 255, 255, 0.8); /* Teks lebih terang */
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .absen-type-btn.active {
      background: #fff;
      color: #000; /* Teks hitam pekat pada tombol aktif */
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    /* Inputs */
    .footer input.form-control {
      background: rgba(255, 255, 255, 0.95); /* Hampir solid putih */
      border: 1px solid rgba(0,0,0,0.1);
      border-radius: 12px;
      padding: 10px;
      text-align: center;
      font-weight: 700; /* Font lebih tebal */
      color: #000; /* Teks hitam pekat */
      width: 100%;
      font-size: 0.95rem;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .footer input.form-control::placeholder {
        color: #555; /* Placeholder lebih gelap */
        font-weight: 500;
        font-size: 0.85rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
    }

    .footer button.btn {
      flex: 1;
      border: none;
      padding: 10px 0;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 4px 6px rgba(0,0,0,0.2);
      width: 100%;
      margin: 0;
    }

    .footer button.clock-in {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .footer button.clock-out {
      background: linear-gradient(135deg, #ff5f6d 0%, #ffc371 100%);
    }
    
    .footer button:active {
        transform: scale(0.96);
    }

    /* Location Info */
    .location-info {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        padding: 10px;
        color: #fff;
        font-size: 11px;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    #map {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        flex-shrink: 0;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .location-details {
        flex-grow: 1;
        overflow: hidden;
    }

    .location-view {
        display: block;
        font-weight: 500;
        margin-bottom: 2px;
        line-height: 1.3;
        max-height: 40px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .ket {
        font-size: 9px;
        opacity: 0.7;
        margin-top: 2px;
    }

    #time {
        font-size: 10px;
        color: #aaa;
    }

    /* Floating Button */
    .floating-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        background: #fff;
        color: #333;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        z-index: 100;
        transition: all 0.3s;
    }
    .floating-button:hover { background: #f0f0f0; transform: rotate(45deg); }
    .floating-button svg { width: 24px; height: 24px; }

    .switch-camera-btn {
        position: fixed;
        bottom: 75px;
        right: 20px;
        width: 45px;
        height: 45px;
        background: #fff;
        color: #333;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        z-index: 100;
        transition: all 0.3s;
    }
    .switch-camera-btn:hover { background: #f0f0f0; transform: scale(1.1); }
    .switch-camera-btn svg { width: 22px; height: 22px; }

    .linimasa-absen {
        background: rgba(0, 0, 0, 0.3); /* Background gelap agar ikon putih terlihat jelas */
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 42px; 
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0;
        margin-left: 5px; 
    }
    
    .linimasa-absen:hover { 
        background: rgba(0, 0, 0, 0.5); 
        color: #fff;
        transform: scale(1.05);
    }
    
    /* Overlay */
    #overlay {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.5); z-index: 99;
      display: none;
    }

    /* Responsive Adjustments */
    @media (max-width: 400px) {
        .text { font-size: 1.5rem; }
        .footer { width: 92%; padding: 15px; bottom: 15px; }
        .header { width: 92%; padding: 10px; top: 15px; }
    }
    
    .d-none { display: none !important; }
  </style>

  <!-- jQuery -->
  <script src="/plugins/jquery/jquery.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js" integrity="sha512-xAxjKW1J/R4TFytv43xvQ7jFTrJlBzsDOH/Aw0fFc2nvarQkE3d08lTicjzUsZJYDoHYDCpwsUUqOJXh34A1sw==" crossorigin="anonymous" referrerpolicy="no-referrer">
  </script>

  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>-->

</head>

<body>
  <button class="floating-button" onclick="getKoordinat()" title="Perbarui Lokasi">
      <svg id="Layer_1" style="enable-background:new 0 0 128 128;" version="1.1" viewBox="0 0 128 128" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M96.1,103.6c-10.4,8.4-23.5,12.4-36.8,11.1c-10.5-1-20.3-5.1-28.2-11.8H44v-8H18v26h8v-11.9c9.1,7.7,20.4,12.5,32.6,13.6   c1.9,0.2,3.7,0.3,5.5,0.3c13.5,0,26.5-4.6,37-13.2c19.1-15.4,26.6-40.5,19.1-63.9l-7.6,2.4C119,68.6,112.6,90.3,96.1,103.6z"/><path d="M103,19.7c-21.2-18.7-53.5-20-76.1-1.6C7.9,33.5,0.4,58.4,7.7,81.7l7.6-2.4C9,59.2,15.5,37.6,31.9,24.4   C51.6,8.4,79.7,9.6,98,26H85v8h26V8h-8V19.7z"/></g></svg>
  </button>
  
  <button class="switch-camera-btn" onclick="switchCamera()" title="Ganti Kamera">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 2.1l4 4-4 4"/>
          <path d="M3 12.2v-2a4 4 0 0 1 4-4h12.8M7 21.9l-4-4 4-4"/>
          <path d="M21 11.8v2a4 4 0 0 1-4 4H4.2"/>
      </svg>
  </button>

  <div id="overlay"></div>
  
  <!-- Preloader starts -->
  <div class="preloader">
    <img src="/dist/img/logo-bkk.png" alt="logo" class="preloader-logo">
    <div class="loader-container">
        <div class="loader-text">Loading...</div>
        <div class="progress-bar-container">
            <div class="progress-bar-fill"></div>
        </div>
    </div>
  </div>

  <!-- Video Background -->
  <video id="video" autoplay="" muted="" playsinline=""></video>

  <div class="header">
    <p class="desc p-0 m-0" style="font-size: 1.1rem; font-weight: 600;">BKK Kelas I Bandung</p>
    <p id="info" class="desc p-0 m-0 mt-2"></p>
  </div>
  
  <div class="footer">
    <div class="absen-type-container">
      <div class="absen-selector-group">
        <?php
        $showWfo = !isset($absenSetting['show_wfo']) || $absenSetting['show_wfo'];
        $showWfa = !isset($absenSetting['show_wfa']) || $absenSetting['show_wfa'];
        $showDl = !isset($absenSetting['show_dl']) || $absenSetting['show_dl'];
        ?>
        <?php if ($showWfo): ?>
        <button class="absen-type-btn active" data-type="WFO" onclick="setAbsenType('WFO')">WFO</button>
        <?php endif; ?>
        <?php if ($showWfa): ?>
        <button class="absen-type-btn <?= !$showWfo ? 'active' : '' ?>" data-type="WFA" onclick="setAbsenType('WFA')">WFA</button>
        <?php endif; ?>
        <?php if ($showDl): ?>
        <button class="absen-type-btn <?= !$showWfo && !$showWfa ? 'active' : '' ?>" data-type="DL" onclick="setAbsenType('DL')">DL</button>
        <?php endif; ?>
      </div>
      <?php if ($nip != ""): ?>
        <button class="linimasa-absen" title="Riwayat Absensi">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
        </button>
        <button class="linimasa-absen" id="btnDownloadDL" title="Download Foto DL Terakhir" style="display:none;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
          </svg>
        </button>
      <?php endif; ?>
    </div>
    
    <div class="w-100">
        <input type="number" name="nip" class="form-control" placeholder="Masukkan NIP / NOPEG" value="<?= $nip ? $nip : ''; ?>">
        <textarea name="address" class="form-control d-none" id="address"></textarea>
        <input type="hidden" name="status_absen" id="status_absen" value="<?= $showWfo ? 'WFO' : ($showWfa ? 'WFA' : 'DL') ?>">
    </div>

    <div class="action-buttons">
        <button class="btn clock-in">Tandai Masuk</button>
        <button class="btn clock-out">Tandai Keluar</button>
    </div>

    <label class="d-block text-danger ketAkses d-none font-weight-bold" style="font-size: 12px; background: rgba(255,255,255,0.9); padding: 8px; border-radius: 8px; margin: 0;">
        Anda tidak diizinkan untuk absen!<br/>NIP tidak sesuai dengan wilayah kerja!
    </label>

    <div class="location-info w-100">
        <div id="map"></div>
        <div class="location-details">
             <span class="location-view">Memuat lokasi...</span>
             <span id="location" class="d-none"></span>
             <div id="time"></div>
             <div class="ket">Lat: <span id="latitude">-</span>, Long: <span id="longitude">-</span></div>
        </div>
    </div>
  </div>
  


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  

  <script>
    $(document).ready(function() {
      // Array berisi teks yang akan ditampilkan
      var infoTexts = [
        "Pastikan foto jelas dan dalam posisi benar.",
        "Ambil gambar baru jika diperlukan.",
        "Data perbandingan dari foto pertama & terakhir.",
        "Pastikan foto diambil dengan latar area kantor.",
        "Foto dan lokasi Anda akan dicatat untuk absensi.",
        "Anda dapat meminjam perangkat teman jika diperlukan.",
        "Absen masuk akan aktif sampai dengan jam 9 Pagi.",
        "Absen pulang akan aktif setelah jam 3 Sore.",
        "Pegawai hanya dapat absen sesuai Wilayah Kerjanya."
      ];

      var index = 0; // Indeks array untuk melacak teks yang sedang ditampilkan

      // Fungsi untuk memperbarui teks elemen
      function updateText() {
        $('#info').text(infoTexts[index]);
        index = (index + 1) % infoTexts.length; // Mengupdate indeks dan kembali ke 0 jika mencapai akhir array
      }

      // Memperbarui teks setiap 3 detik (3000 ms)
      setInterval(updateText, 5000);
    });

    // Pilih elemen target
    var target = document.getElementById('location');
    var setLatitude = null;
    var setLongitude = null;

    // Daftar nilai target lokasi
    var targetLocations = [
      "Informasi Lokasi Anda Akan Muncul Disini!",
      "Gagal mendapatkan alamat.",
      "User denied the request for Geolocation.",
      "Location information is unavailable.",
      "The request to get user location timed out.",
      "An unknown error occurred.",
      "Geolocation is not supported by this browser.",
      "Failed to get location name."
    ];


    var map = L.map('map', {
      center: [51.505, -0.09], // Default coordinates (London)
      zoom: 14,
      zoomControl: false // Disable zoom control
    });

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: ''
    }).addTo(map);

    function onLocationFound(e) {
      var radius = e.accuracy / 2;

      L.marker([e.latitude, e.longitude]).addTo(map)
        .bindPopup('You are within ' + radius + ' meters from this point.')
        .openPopup();

      L.circle([e.latitude, e.longitude], radius).addTo(map);

      map.setView([e.latitude, e.longitude], 13);

      fetch(`https://nominatim.openstreetmap.org/reverse?lat=${e.latitude}&lon=${e.longitude}&format=json`)
        .then(response => response.json())
        .then(data => {
          console.log(data);
          target.innerText = data.display_name;
          setLongitude = data.lon
          setLatitude = data.lat

          document.getElementById('address').value = JSON.stringify(data.address, null, 2);
        })
        .catch(error => {
          target.innerText = 'Gagal mendapatkan alamat.';
          console.error(error);
        });
    }

    function showError(error) {
      var message = '';

      switch (error.code) {
        case error.PERMISSION_DENIED:
          message = "User denied the request for Geolocation.";
          break;
        case error.POSITION_UNAVAILABLE:
          message = "Location information is unavailable.";
          break;
        case error.TIMEOUT:
          message = "The request to get user location timed out.";
          break;
        case error.UNKNOWN_ERROR:
          message = "An unknown error occurred.";
          break;
      }

      target.textContent = message;
    }
    
    function getKoordinat() {
        Swal.fire({
            title: "Menyesuaikan area sekitar dengan device!",
            html: "Tolong tunggu beberapa saat..",
            timer: 10000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            },
            willClose: () => {
              clearInterval(timerInterval);
            }
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              console.log("I was closed by the timer");
            }
        });
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Mendapatkan latitude dan longitude
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
    
                    // Menampilkan latitude dan longitude di console
                    document.getElementById('longitude').innerText = parseFloat(longitude).toFixed(6);
                    document.getElementById('latitude').innerText = parseFloat(latitude).toFixed(6);
                    Swal.fire({
                        position: "top-end",
                        icon: "info",
                        title: "Akurasi sekitar " + accuracy.toFixed(2) + " meter dari lokasi yang terdeteksi",
                        showConfirmButton: false,
                        timer: 2500
                    });

    
                    // Memanggil fungsi onLocationFound dengan objek koordinat
                    onLocationFound({
                        latitude: latitude,
                        longitude: longitude,
                        accuracy: accuracy
                    });
                },
                showError, // Fungsi untuk menangani error geolocation
                {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: 15000
                }
            );
        } else {
            target.textContent = 'Geolocation is not supported by this browser.';
        }
    }


    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Mendapatkan latitude dan longitude
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                // Menampilkan latitude dan longitude di console
                document.getElementById('longitude').innerText = parseFloat(longitude).toFixed(6);
                document.getElementById('latitude').innerText = parseFloat(latitude).toFixed(6);
                Swal.fire({
                    position: "top-end",
                    icon: "info",
                    html: "Akurasi sekitar<br />" + accuracy.toFixed(1) + " meter dari lokasi yang terdeteksi",
                    showConfirmButton: false,
                    timer: 2500
                });

                // Memanggil fungsi onLocationFound dengan objek koordinat
                onLocationFound({
                    latitude: latitude,
                    longitude: longitude,
                    accuracy: accuracy
                });
            },
            showError, // Fungsi untuk menangani error geolocation
            {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: 15000
            }
        );
    } else {
        target.textContent = 'Geolocation is not supported by this browser.';
    }
  </script>

  <script>
    var watermarkLogo = new Image();
    watermarkLogo.src = '/dist/img/logo-bkk-no-margin.png';

    $(".clock-in").on('click', function() {
      // Mengambil video element
      var video = document.getElementById('video');

      // Membuat canvas untuk menangkap gambar
      var canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      var context = canvas.getContext('2d');
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      var statusAbsen = $('#status_absen').val();

      // Tambahkan Watermark untuk Dinas Luar (DL)
      if (statusAbsen === 'DL') {
          var timeStr = "Waktu: " + getFormatTime();
          var locStr = "Lokasi: " + $("#location").text();
          var coordStr = "Lat: " + $("#latitude").text() + " | Long: " + $("#longitude").text();
          
          // Gambar Logo di Kiri Atas
          var logoWidth = 180; // Logo diperbesar 2x lipat
          var logoHeight = (watermarkLogo.height / watermarkLogo.width) * logoWidth;
          if (isNaN(logoHeight)) logoHeight = 180;
          
          if (watermarkLogo.complete && watermarkLogo.naturalHeight !== 0) {
              // Posisi x=15, y=15 (Kiri Atas)
              context.drawImage(watermarkLogo, 15, 15, logoWidth, logoHeight);
          }

          context.font = "14px Arial";
          var maxWidth = canvas.width - 30; // Kembalikan full width margin 15px kiri kanan
          
          var textToWrap = timeStr + " | " + locStr + " | " + coordStr;
          
          var words = textToWrap.split(' ');
          var line = '';
          var locLines = [];

          for(var n = 0; n < words.length; n++) {
            var testLine = line + words[n] + ' ';
            var metrics = context.measureText(testLine);
            if (metrics.width > maxWidth && n > 0) {
              locLines.push(line);
              line = words[n] + ' ';
            } else {
              line = testLine;
            }
          }
          locLines.push(line);

          var lineHeight = 18;
          var totalLines = 1 + locLines.length; // Title + Alamat(N lines)
          var bgHeight = (totalLines * lineHeight) + 15;

          context.fillStyle = "rgba(0, 0, 0, 0.5)";
          context.fillRect(0, canvas.height - bgHeight, canvas.width, bgHeight);
          
          context.fillStyle = "white";
          context.textAlign = "left";
          
          var currentY = canvas.height - bgHeight + 20;

          context.font = "bold 14px Arial";
          context.fillText("DINAS LUAR - MASUK", 15, currentY);
          currentY += lineHeight;
          
          context.font = "12px Arial";
          
          for(var k = 0; k < locLines.length; k++) {
              context.fillText(locLines[k], 15, currentY);
              currentY += lineHeight;
          }
      }

      // Mengambil data gambar dari canvas
      var imageData = canvas.toDataURL('image/jpeg');

      // Mengirim data gambar menggunakan AJAX
      var nip = $('input[name="nip"]').val();

      if (nip != "") {
        var formData = new FormData();
        formData.append('nip', nip);
        formData.append('image', imageData);
        formData.append('latitude', $("#latitude").text());
        formData.append('longitude', $("#longitude").text());
        formData.append('alamat', $(".location-view").text());
        formData.append('posisi_alamat', $('textarea[name="address"]').val());
        formData.append('status', statusAbsen);

        $.ajax({
          url: `/absensi/clockIn`,
          data: formData,
          method: 'post',
          dataType: 'json',
          contentType: false, // Important! Tell jQuery not to set contentType
          processData: false, // Important! Tell jQuery not to process data
          beforeSend: function() {
            Swal.fire({
              title: "Tunggu...",
              showConfirmButton: false,
              allowOutsideClick: false,
              backdrop: `rgba(0,0,123,0.4) left top no-repeat`
            });
          },
          success: function(response) {
            if (response.data) {
              if (response.data == 'sukses') {
                if (statusAbsen === 'DL') {
                  var link = document.createElement('a');
                  link.download = 'DL_Masuk_' + nip + '_' + new Date().getTime() + '.jpg';
                  link.href = imageData;
                  link.click();
                  
                  Swal.fire({
                    title: 'Berhasil Absen DL!',
                    html: `
                      <img src="${imageData}" style="width: 220px; border-radius: 8px; margin-bottom: 10px; border: 1px solid #ddd; display: block; margin-left: auto; margin-right: auto;" alt="Preview DL">
                      <p style="margin-bottom: 10px; font-size: 14px;">Foto dengan watermark telah otomatis diunduh.<br/>Silakan tanda tangan di bawah ini:</p>
                      <div style="position: relative; width: 100%;">
                        <canvas id="signature-pad" style="width: 100%; height: 150px; border: 1px solid #ccc; border-radius: 8px; touch-action: none; background: #fff; box-sizing: border-box; display: block;"></canvas>
                        <button type="button" id="clear-signature" title="Hapus Tanda Tangan" style="position: absolute; bottom: 8px; right: 8px; background: rgba(220, 53, 69, 0.9); color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); padding: 0;">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828l6.879-6.879zm2.121.707a1 1 0 0 0-1.414 0L4.16 7.547l5.293 5.293 4.633-4.633a1 1 0 0 0 0-1.414l-3.879-3.879zM8.746 13.547 3.453 8.254 1.914 9.547a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293l.16-.16z"/></svg>
                        </button>
                      </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Tutup',
                    allowOutsideClick: false,
                    didOpen: () => {
                         const canvas = document.getElementById('signature-pad');
                         // Set ukuran internal canvas menyesuaikan dengan ukuran elemen (responsif)
                         const ratio = Math.max(window.devicePixelRatio || 1, 1);
                         canvas.width = canvas.offsetWidth * ratio;
                         canvas.height = canvas.offsetHeight * ratio;
                         const ctx = canvas.getContext('2d');
                         ctx.scale(ratio, ratio);
                        let drawing = false;
                        ctx.lineWidth = 3; // Sedikit ditebalkan
                        ctx.lineCap = "round";
                        ctx.lineJoin = "round";
                        ctx.strokeStyle = "#000";

                        let lastPos = null;

                        function getPos(e) {
                            const rect = canvas.getBoundingClientRect();
                            let clientX = e.clientX;
                            let clientY = e.clientY;
                            if (e.touches && e.touches.length > 0) {
                                clientX = e.touches[0].clientX;
                                clientY = e.touches[0].clientY;
                            }
                            return { x: clientX - rect.left, y: clientY - rect.top };
                        }

                        function startPos(e) {
                            drawing = true;
                            lastPos = getPos(e);
                            
                            // Gambar titik awal agar jika hanya disentuh (tap) tetap muncul
                            ctx.beginPath();
                            ctx.arc(lastPos.x, lastPos.y, ctx.lineWidth / 2, 0, Math.PI * 2);
                            ctx.fill();
                            
                            ctx.beginPath();
                            ctx.moveTo(lastPos.x, lastPos.y);
                            e.preventDefault();
                        }

                        function drawLine(e) {
                            if (!drawing) return;
                            const pos = getPos(e);
                            
                            // Algoritma Midpoint Smoothing (Kurva Kuadratik)
                            const midX = (lastPos.x + pos.x) / 2;
                            const midY = (lastPos.y + pos.y) / 2;
                            
                            ctx.quadraticCurveTo(lastPos.x, lastPos.y, midX, midY);
                            ctx.stroke();
                            
                            ctx.beginPath();
                            ctx.moveTo(midX, midY);
                            
                            lastPos = pos;
                            e.preventDefault();
                        }

                        function endPos(e) { 
                            if (!drawing) return;
                            // Selesaikan garis ke titik terakhir
                            ctx.lineTo(lastPos.x, lastPos.y);
                            ctx.stroke();
                            drawing = false; 
                        }

                        canvas.addEventListener('mousedown', startPos, {passive: false});
                        canvas.addEventListener('mousemove', drawLine, {passive: false});
                        canvas.addEventListener('mouseup', endPos);
                        canvas.addEventListener('mouseleave', endPos);
                        canvas.addEventListener('touchstart', startPos, {passive: false});
                        canvas.addEventListener('touchmove', drawLine, {passive: false});
                        canvas.addEventListener('touchend', endPos);
                        canvas.addEventListener('touchcancel', endPos);

                        document.getElementById('clear-signature').addEventListener('click', () => {
                            // Karena di-scale, clearRect harus menggunakan ukuran fisik aslinya atau scale inverse
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                        });
                    },
                    preConfirm: () => {
                        const canvas = document.getElementById('signature-pad');
                        const blank = document.createElement('canvas');
                        blank.width = canvas.width;
                        blank.height = canvas.height;
                        if (canvas.toDataURL() === blank.toDataURL()) {
                            Swal.showValidationMessage('Tanda tangan tidak boleh kosong!');
                            return false;
                        }
                        return $.ajax({
                            url: '/absensi/simpanTandaTangan',
                            method: 'POST',
                            dataType: 'json',
                            data: { nip: nip, status: 'DL', jenis: 'masuk', signature: canvas.toDataURL('image/png') }
                        }).then(response => {
                            if (response.status !== 'success') {
                                throw new Error(response.message || 'Gagal menyimpan ke database');
                            }
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(`Gagal: ${error.message || error}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                  }).then((result) => {
                      if (result.isConfirmed) {
                          Swal.fire({ icon: 'success', title: 'Tersimpan!', text: 'Tanda tangan berhasil disimpan.', timer: 2000, showConfirmButton: false });
                      }
                  });
                } else {
                  Swal.fire({
                    icon: 'success',
                    title: `Selamat datang!`,
                    html: response.pesan
                  });
                }
              } else if(response.pesan == 'akses terhadap kamera mati, tolong periksa kembali izin perangkat anda atau coba lagi!') {
                Swal.fire({
                  icon: 'warning',
                  title: `Kesalahan pengambilan gambar!`,
                  html: response.pesan
                });
              } else {
                Swal.fire({
                  icon: 'warning',
                  title: `Lokasi tidak terdeteksi!`,
                  html: response.pesan
                });
              }
            } else {
              Swal.fire({
                icon: 'error',
                title: `NIP/NOPEG anda tidak terdaftar!`,
              });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
            });
          }
        });
      }
    });
    $(".clock-out").on('click', function() {
      // Mengambil video element
      var video = document.getElementById('video');

      // Membuat canvas untuk menangkap gambar
      var canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      var context = canvas.getContext('2d');
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      var statusAbsen = $('#status_absen').val();

      // Tambahkan Watermark untuk Dinas Luar (DL)
      if (statusAbsen === 'DL') {
          var timeStr = "Waktu: " + getFormatTime();
          var locStr = "Lokasi: " + $("#location").text();
          var coordStr = "Lat: " + $("#latitude").text() + " | Long: " + $("#longitude").text();
          
          // Gambar Logo di Kiri Atas
          var logoWidth = 180; // Logo diperbesar 2x lipat
          var logoHeight = (watermarkLogo.height / watermarkLogo.width) * logoWidth;
          if (isNaN(logoHeight)) logoHeight = 180;
          
          if (watermarkLogo.complete && watermarkLogo.naturalHeight !== 0) {
              // Posisi x=15, y=15 (Kiri Atas)
              context.drawImage(watermarkLogo, 15, 15, logoWidth, logoHeight);
          }

          context.font = "14px Arial";
          var maxWidth = canvas.width - 30; // Kembalikan full width margin 15px kiri kanan
          
          var textToWrap = timeStr + " | " + locStr + " | " + coordStr;
          
          var words = textToWrap.split(' ');
          var line = '';
          var locLines = [];

          for(var n = 0; n < words.length; n++) {
            var testLine = line + words[n] + ' ';
            var metrics = context.measureText(testLine);
            if (metrics.width > maxWidth && n > 0) {
              locLines.push(line);
              line = words[n] + ' ';
            } else {
              line = testLine;
            }
          }
          locLines.push(line);

          var lineHeight = 18;
          var totalLines = 1 + locLines.length; // Title + Alamat(N lines)
          var bgHeight = (totalLines * lineHeight) + 15;

          context.fillStyle = "rgba(0, 0, 0, 0.5)";
          context.fillRect(0, canvas.height - bgHeight, canvas.width, bgHeight);
          
          context.fillStyle = "white";
          context.textAlign = "left";
          
          var currentY = canvas.height - bgHeight + 20;

          context.font = "bold 14px Arial";
          context.fillText("DINAS LUAR - PULANG", 15, currentY);
          currentY += lineHeight;
          
          context.font = "12px Arial";
          
          for(var k = 0; k < locLines.length; k++) {
              context.fillText(locLines[k], 15, currentY);
              currentY += lineHeight;
          }
      }

      // Mengambil data gambar dari canvas
      var imageData = canvas.toDataURL('image/jpeg');

      // Mengirim data gambar menggunakan AJAX
      var nip = $('input[name="nip"]').val();

      if (nip != "") {
        var formData = new FormData();
        formData.append('nip', nip);
        formData.append('image', imageData);
        formData.append('latitude', $("#latitude").text());
        formData.append('longitude', $("#longitude").text());
        formData.append('alamat', $(".location-view").text());
        formData.append('posisi_alamat', $('textarea[name="address"]').val());
        formData.append('status', statusAbsen);

        $.ajax({
          url: `/absensi/clockOut`,
          data: formData,
          method: 'post',
          dataType: 'json',
          contentType: false, // Important! Tell jQuery not to set contentType
          processData: false, // Important! Tell jQuery not to process data
          beforeSend: function() {
            Swal.fire({
              title: "Tunggu...",
              showConfirmButton: false,
              allowOutsideClick: false,
              backdrop: `rgba(0,0,123,0.4) left top no-repeat`
            });
          },
          success: function(response) {
            if (response.data) {
              if (response.data == 'sukses') {
                if (statusAbsen === 'DL') {
                  var link = document.createElement('a');
                  link.download = 'DL_Pulang_' + nip + '_' + new Date().getTime() + '.jpg';
                  link.href = imageData;
                  link.click();
                  
                  Swal.fire({
                    title: 'Berhasil Absen DL!',
                    html: `
                      <img src="${imageData}" style="width: 220px; border-radius: 8px; margin-bottom: 10px; border: 1px solid #ddd; display: block; margin-left: auto; margin-right: auto;" alt="Preview DL">
                      <p style="margin-bottom: 10px; font-size: 14px;">Foto dengan watermark telah otomatis diunduh.<br/>Silakan tanda tangan di bawah ini:</p>
                      <div style="position: relative; width: 100%;">
                        <canvas id="signature-pad" style="width: 100%; height: 150px; border: 1px solid #ccc; border-radius: 8px; touch-action: none; background: #fff; box-sizing: border-box; display: block;"></canvas>
                        <button type="button" id="clear-signature" title="Hapus Tanda Tangan" style="position: absolute; bottom: 8px; right: 8px; background: rgba(220, 53, 69, 0.9); color: #fff; border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); padding: 0;">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828l6.879-6.879zm2.121.707a1 1 0 0 0-1.414 0L4.16 7.547l5.293 5.293 4.633-4.633a1 1 0 0 0 0-1.414l-3.879-3.879zM8.746 13.547 3.453 8.254 1.914 9.547a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293l.16-.16z"/></svg>
                        </button>
                      </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Tutup',
                    allowOutsideClick: false,
                    didOpen: () => {
                         const canvas = document.getElementById('signature-pad');
                         // Set ukuran internal canvas menyesuaikan dengan ukuran elemen (responsif)
                         const ratio = Math.max(window.devicePixelRatio || 1, 1);
                         canvas.width = canvas.offsetWidth * ratio;
                         canvas.height = canvas.offsetHeight * ratio;
                         const ctx = canvas.getContext('2d');
                         ctx.scale(ratio, ratio);
                        let drawing = false;
                        ctx.lineWidth = 2.5;
                        ctx.lineCap = "round";
                        ctx.lineJoin = "round";
                        ctx.strokeStyle = "#000";

                        function getPos(e) {
                            const rect = canvas.getBoundingClientRect();
                            let clientX = e.clientX;
                            let clientY = e.clientY;
                            if (e.touches && e.touches.length > 0) {
                                clientX = e.touches[0].clientX;
                                clientY = e.touches[0].clientY;
                            }
                            return { x: clientX - rect.left, y: clientY - rect.top };
                        }
                        function startPos(e) {
                            drawing = true;
                            const pos = getPos(e);
                            ctx.beginPath();
                            ctx.moveTo(pos.x, pos.y);
                            e.preventDefault();
                        }
                        function drawLine(e) {
                            if (!drawing) return;
                            const pos = getPos(e);
                            ctx.lineTo(pos.x, pos.y);
                            ctx.stroke();
                            e.preventDefault();
                        }
                        function endPos(e) { drawing = false; }

                        canvas.addEventListener('mousedown', startPos);
                        canvas.addEventListener('mousemove', drawLine);
                        canvas.addEventListener('mouseup', endPos);
                        canvas.addEventListener('mouseleave', endPos);
                        canvas.addEventListener('touchstart', startPos);
                        canvas.addEventListener('touchmove', drawLine);
                        canvas.addEventListener('touchend', endPos);
                        canvas.addEventListener('touchcancel', endPos);

                        document.getElementById('clear-signature').addEventListener('click', () => {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                        });
                    },
                    preConfirm: () => {
                        const canvas = document.getElementById('signature-pad');
                        const blank = document.createElement('canvas');
                        blank.width = canvas.width;
                        blank.height = canvas.height;
                        if (canvas.toDataURL() === blank.toDataURL()) {
                            Swal.showValidationMessage('Tanda tangan tidak boleh kosong!');
                            return false;
                        }
                        return $.ajax({
                            url: '/absensi/simpanTandaTangan',
                            method: 'POST',
                            dataType: 'json',
                            data: { nip: nip, status: 'DL', jenis: 'pulang', signature: canvas.toDataURL('image/png') }
                        }).then(response => {
                            if (response.status !== 'success') {
                                throw new Error(response.message || 'Gagal menyimpan ke database');
                            }
                            return response;
                        }).catch(error => {
                            Swal.showValidationMessage(`Gagal: ${error.message || error}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                  }).then((result) => {
                      if (result.isConfirmed) {
                          Swal.fire({ icon: 'success', title: 'Tersimpan!', text: 'Tanda tangan berhasil disimpan.', timer: 2000, showConfirmButton: false });
                      }
                  });
                } else {
                  Swal.fire({
                    icon: 'success',
                    title: `Terimakasih, hati-hati dijalan!`,
                    html: response.pesan
                  });
                }
              } else if(response.pesan == 'akses terhadap kamera mati, tolong periksa kembali izin perangkat anda atau coba lagi!') {
                Swal.fire({
                  icon: 'warning',
                  title: `Kesalahan pengambilan gambar!`,
                  html: response.pesan
                });
              } else {
                Swal.fire({
                  icon: 'warning',
                  title: `Lokasi tidak terdeteksi!`,
                  html: response.pesan
                });
              }
            } else {
              Swal.fire({
                icon: 'error',
                title: `Anda belum "Tandai Masuk" sebelumnya!`,
              });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
            });
          }
        });
      }
    });
  </script>

  <script>
    // Server time offset to prevent client time manipulation
    var serverTimeOffset = <?php echo time() * 1000; ?> - new Date().getTime();
    function getRealTime() {
        return new Date(new Date().getTime() + serverTimeOffset);
    }

    // Fungsi untuk mendapatkan format waktu yang diinginkan
    function getFormatTime() {
      var currentTime = getRealTime();
      var day = currentTime.getDate().toString().padStart(2, '0');
      var month = (currentTime.getMonth() + 1).toString().padStart(2, '0');
      var year = currentTime.getFullYear();
      var hours = currentTime.getHours().toString().padStart(2, '0');
      var minutes = currentTime.getMinutes().toString().padStart(2, '0');
      var seconds = currentTime.getSeconds().toString().padStart(2, '0');
      var formattedTime = day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds + ' GMT +07:00';
      return formattedTime;
    }

    setInterval(function() {
      var time = getFormatTime();
      document.getElementById('time').textContent = 'Waktu: ' + time;
    }, 1000);

    let currentFacingMode = 'user'; // mode default: kamera depan

    async function startVideo() {
      function requestWebcam(facingMode) {
        // Hentikan stream yang sedang berjalan jika ada
        if (video.srcObject) {
          video.srcObject.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({
            video: {
              width: { ideal: 640 },
              height: { ideal: 480 },
              facingMode: facingMode
            }
          })
          .then((stream) => {
            video.srcObject = stream;
            
            // Mirror efek hanya untuk kamera depan (user)
            if (facingMode === 'user') {
                video.style.transform = 'scaleX(-1)';
            } else {
                video.style.transform = 'none';
            }
          })
          .catch((err) => {
            console.error('Error accessing webcam:', err);
            if (confirm('Failed to access webcam. Retry?')) {
              requestWebcam(facingMode); // Mencoba lagi jika pengguna menekan OK
            }
          });
      }

      window.switchCamera = function() {
          currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
          requestWebcam(currentFacingMode);
      };

      requestWebcam(currentFacingMode);

      // Setelah video ditambahkan ke DOM
      video.addEventListener('play', async () => {
        // video.style.marginTop = '50px'; // Atur margin top
      });
    }

    startVideo();
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      let timerInterval;
      Swal.fire({
        title: "Mencari alamat terdekat!",
        html: "Tolong tunggu beberapa saat..",
        timer: 10000,
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
        willClose: () => {
          clearInterval(timerInterval);
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log("I was closed by the timer");
        }
      });
    })


    function getDistanceLocation() {
      var nip = $('input[name="nip"]').val();
      if (!nip || nip.trim() === "") {
          $(".btn").attr("disabled", true);
          $(".ketAkses").removeClass("d-none");
          $(".ketAkses").html("NIP belum diisi!<br/>Silakan masukkan NIP / NOPEG terlebih dahulu.");
          $(".location-view").text($("#location").text());
          $("#overlay").hide();
          return;
      }

      const data = [setLongitude, setLatitude];
      const isDataInside = getLocationDescription(data, targetPolygons);
      const isDataAccesArea = getLocationDescription(data, targetPolygonsAkses);
      const statusAbsen = $('#status_absen').val();

      console.log(targetPolygonsAkses);
      console.log(isDataAccesArea);
      console.log(targetPolygons);
      console.log(isDataInside);
      
      if (statusAbsen === 'DL') {
          // DL (Dinas Luar) allowed anywhere, but it records DL
          if (setLongitude && setLatitude) {
              $(".btn").attr("disabled", false);
              $(".ketAkses").addClass("d-none");
              $(".location-view").text($("#location").text() + " (Dinas Luar)");
              $(".location-view").removeClass('text-danger');
          } else {
              $(".btn").attr("disabled", true);
              $(".ketAkses").removeClass("d-none");
              $(".ketAkses").html("Menunggu lokasi perangkat...<br/>Pastikan GPS aktif dan izinkan akses lokasi.");
              $(".location-view").text("Mendeteksi lokasi...");
              $(".location-view").addClass('text-danger');
          }
      } else if (statusAbsen === 'WFA') {
          // Logic for WFA: Check specific WFA permission
          if(!wfaAllowed) {
             $(".btn").attr("disabled", true);
             $(".ketAkses").removeClass("d-none");
             $(".ketAkses").html("Anda tidak diizinkan untuk absen WFA!<br/>Silakan hubungi admin.");
             
             Swal.fire({
                title: "Akses WFA Ditolak!",
                text: "Anda tidak memiliki izin untuk melakukan absensi WFA. Silakan hubungi admin.",
                icon: "error"
            });
          } else {
             if (setLongitude && setLatitude) {
                 $(".btn").attr("disabled", false);
                 $(".ketAkses").addClass("d-none");
                 $(".location-view").text($("#location").text() + " (WFA)");
                 $(".location-view").removeClass('text-danger');
             } else {
                 $(".btn").attr("disabled", true);
                 $(".ketAkses").removeClass("d-none");
                 $(".ketAkses").html("Menunggu lokasi perangkat...<br/>Pastikan GPS aktif dan izinkan akses lokasi.");
                 $(".location-view").text("Mendeteksi lokasi...");
                 $(".location-view").addClass('text-danger');
             }
          }
      } else {
          // Logic for WFO
          // Harus berada di dalam zona absen (targetPolygons)
          
          // Cek dulu apakah data polygon tersedia (NIP valid)
          if(targetPolygons.length === 0 && targetPolygonsAkses.length === 0) {
             $(".btn").attr("disabled", true);
             $(".ketAkses").removeClass("d-none");
             Swal.fire({
                title: "NIP tidak ditemukan!",
                text: "Pastikan NIP Anda telah terdaftar.",
                icon: "error"
            });
            return;
          }

          if (targetPolygonsAkses.length > 0) {
              // Jika user memiliki spesifik lokasi absen
              if (isDataAccesArea && isDataAccesArea !== "Anda berada diluar wilayah absen") {
                  $(".btn").attr("disabled", false);
                  $(".ketAkses").addClass("d-none");
                  $(".location-view").text(isDataAccesArea);
                  $(".location-view").removeClass('text-danger');
                  
                  Swal.fire({
                    icon: "success",
                    title: "Terdeteksi",
                    text: isDataAccesArea,
                    showConfirmButton: false,
                    timer: 1500
                  });
              } else {
                  $(".btn").attr("disabled", true);
                  $(".ketAkses").removeClass("d-none");
                  $(".ketAkses").html("Anda berada di luar zona Absen WFO yang diizinkan untuk Anda!");
                  $(".location-view").text($("#location").text());
                  $(".location-view").addClass('text-danger');
                  
                  Swal.fire({
                    icon: "warning",
                    title: "Di Luar Zona WFO",
                    text: "Posisi Anda tidak terdeteksi di dalam area kerja Anda.",
                    showConfirmButton: false,
                    timer: 1500
                  });
              }
          } else {
              // Jika user tidak memiliki spesifik lokasi absen, gunakan general lokasi absen
              if (isDataInside && isDataInside !== "Anda berada diluar wilayah absen") {
                  $(".btn").attr("disabled", false);
                  $(".ketAkses").addClass("d-none");
                  $(".location-view").text(isDataInside);
                  $(".location-view").removeClass('text-danger');
                  
                  Swal.fire({
                    icon: "success",
                    title: "Terdeteksi",
                    text: isDataInside,
                    showConfirmButton: false,
                    timer: 1500
                  });
              } else {
                  $(".btn").attr("disabled", true);
                  $(".ketAkses").removeClass("d-none");
                  $(".ketAkses").html("Anda berada di luar zona Absen WFO!");
                  $(".location-view").text($("#location").text());
                  $(".location-view").addClass('text-danger');
                  
                  Swal.fire({
                    icon: "warning",
                    title: "Di Luar Zona WFO",
                    text: "Posisi Anda tidak terdeteksi di dalam area kerja.",
                    showConfirmButton: false,
                    timer: 1500
                  });
              }
          }
      }
      
      $("#overlay").hide();
    }

    // Simpan nilai awal teks
    var previousText = $('#location').text();
    var cekPreviousText = true;

    // Fungsi untuk mengubah tipe absen
    function setAbsenType(type) {
      // Update hidden input
      $('#status_absen').val(type);
      
      // Update button styles
      $('.absen-type-btn').removeClass('active');
      $(`.absen-type-btn[data-type="${type}"]`).addClass('active');
      
      if(type === 'DL') {
          $('#btnDownloadDL').css('display', 'flex');
      } else {
          $('#btnDownloadDL').css('display', 'none');
      }
      
      // Re-check location/polygon based on new type immediately
      console.log("Absen Type changed to: " + type);
      getDistanceLocation();
    }

    $(document).ready(function() {
        if ($('#status_absen').val() === 'DL') {
            $('#btnDownloadDL').css('display', 'flex');
        }
    });

    $("#btnDownloadDL").click(function() {
        var nip = $('input[name="nip"]').val();
        if (nip != "") {
            $.ajax({
              url: "/absensi/getLatestDLPhoto",
              data: {nip: nip},
              method: 'post',
              dataType: 'json',
              beforeSend: function() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Mengambil foto DL terakhir',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                      Swal.showLoading();
                    }
                });
              },
              success: function(response) {
                if (response.data == 'sukses' && response.image) {
                    Swal.close();
                    var link = document.createElement('a');
                    link.download = 'DL_Terakhir_' + nip + '.jpg';
                    link.href = response.image;
                    link.click();
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak ada data',
                        text: response.pesan,
                        confirmButtonText: 'OK'
                    });
                }
              },
              error: function() {
                  Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Gagal mengambil foto DL',
                  });
              }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'NIP Kosong',
                text: 'Silakan isi NIP terlebih dahulu',
            });
        }
    });

    // Fungsi untuk memeriksa perubahan teks
    function checkForChange() {
      var currentText = $('#location').text();
      if (currentText !== previousText) {
        getDistanceLocation()
        previousText = $('#location').text();
      }
    }
    setInterval(checkForChange, 1000);
  </script>

  <script>
    function getLocationDescription(point, polygons, toleranceMeters = 50) {
      const x = point[0]; // User Longitude
      const y = point[1]; // User Latitude

      // Konversi toleransi meter ke derajat (perkiraan kasar di khatulistiwa)
      // 1 derajat ~ 111,320 meter
      const toleranceDegrees = toleranceMeters / 111320; 

      // Function to check if a point is in a single polygon
      function isInSinglePolygon(polygon) {
        let inside = false;
        for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
          const xi = polygon[i][0],
            yi = polygon[i][1];
          const xj = polygon[j][0],
            yj = polygon[j][1];

          const intersect = ((yi > y) !== (yj > y)) &&
            (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
          if (intersect) inside = !inside;
        }
        return inside;
      }

      function dist2(v, w) {
        return (v[0] - w[0]) * (v[0] - w[0]) + (v[1] - w[1]) * (v[1] - w[1]);
      }

      function distToSegmentSquared(p, v, w) {
        var l2 = dist2(v, w);
        if (l2 == 0) return dist2(p, v);
        var t = ((p[0] - v[0]) * (w[0] - v[0]) + (p[1] - v[1]) * (w[1] - v[1])) / l2;
        t = Math.max(0, Math.min(1, t));
        return dist2(p, [v[0] + t * (w[0] - v[0]), v[1] + t * (w[1] - v[1])]);
      }

      function distToPolygon(point, polygon) {
          let minDist2 = Infinity;
          for (let i = 0; i < polygon.length; i++) {
              let j = (i + 1) % polygon.length;
              let d2 = distToSegmentSquared(point, polygon[i], polygon[j]);
              if (d2 < minDist2) minDist2 = d2;
          }
          return Math.sqrt(minDist2);
      }

      // Check if the point is in any of the polygons
      // Prioritas 1: Benar-benar di dalam polygon
      for (let i = 0; i < polygons.length; i++) {
        if (isInSinglePolygon(polygons[i].polygon)) {
          return polygons[i].ket_tempat; 
        }
      }

      // Prioritas 2: Berada dalam radius toleransi (buffer zone)
        for (let i = 0; i < polygons.length; i++) {
            // Gunakan tolerance_meters spesifik per polygon jika ada, default ke 50 jika tidak
            let currentToleranceMeters = polygons[i].tolerance_meters ? parseFloat(polygons[i].tolerance_meters) : 50;
            let currentToleranceDegrees = currentToleranceMeters / 111320;
            
            let dist = distToPolygon([x, y], polygons[i].polygon);
            // Kita gunakan latitude scaling untuk longitude distance agar lebih akurat (opsional, tapi simple degree distance cukup untuk jarak dekat)
            // Jarak Euclidean derajat vs Toleransi derajat
            if (dist <= currentToleranceDegrees) {
                // Jika masuk dalam toleransi, kita anggap VALID tapi beri catatan di UI jika perlu
                // Untuk clock-in, kita return nama tempat saja agar dianggap valid
                return polygons[i].ket_tempat;
            }
        }

      return 'Anda berada diluar wilayah absen'; // Default message if no match
    }

    // Data Zona Target (Multiple Polygons)
    let targetPolygons = [];
    let targetPolygonsAkses = [];
    let wfaAllowed = false;

    getTargetPolygons();
    
    $(document).on("change", 'input[name="nip"]', function(){
        getTargetPolygons("cekLokasi");
    });
    function getTargetPolygons(cekLokasi = "") {
      targetPolygons = [];
      targetPolygonsAkses = [];
      wfaAllowed = false;
        
      var nip = $('input[name="nip"]').val();
      
      if(nip == "") {
        Swal.fire({
          icon: 'info',
          title: `Pastikan NIP diisi dengan benar!`
        });
        getDistanceLocation();
      } else {
          $.ajax({
            url: '/getTargetAreaAbsen/' + nip,
            dataType: 'json',
            before: function() {
                $("#overlay").show();
                Swal.fire({
                    title: "Menyesuaikan data pegawai dengan area tempat sekarang!",
                    html: "Tolong tunggu beberapa saat..",
                    timer: 10000,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    didOpen: () => {
                      Swal.showLoading();
                    },
                    willClose: () => {
                      clearInterval(timerInterval);
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                      console.log("I was closed by the timer");
                    }
                });
            },
            success: function(response) {
                console.log(response)
              if (response.data) {
                // start
                response.data.forEach(function(item) {
                  const cleanedPolygon = item.polygon
                    .replace(/\r\n|\r|\n/g, '')
                    .replace(/\s+/g, ' ')
                    .trim();
    
                  try {
                      const polygonArray = JSON.parse(cleanedPolygon);
                      targetPolygons.push({
                        polygon: polygonArray,
                        ket_tempat: item.ket_tempat, // Simpan ket_tempat bersama dengan poligon
                        tolerance_meters: item.tolerance_meters // Simpan nilai toleransi spesifik
                      });
                    } catch (e) {
                    console.error('Error parsing polygon data:', e);
                  }
                });
                // end
              }
              if (response.akses) {
                // start
                console.log("response akses tersedia")
                response.akses.forEach(function(item) {
                  const cleanedPolygonAkses = item.polygon_akses
                    .replace(/\r\n|\r|\n/g, '')
                    .replace(/\s+/g, ' ')
                    .trim();
    
                  try {
                    const polygonArrayAkses = JSON.parse(cleanedPolygonAkses);
                    targetPolygonsAkses.push({
                      polygon: polygonArrayAkses,
                      ket_tempat: item.ket_tempat // Simpan ket_tempat bersama dengan poligon
                    });
                  } catch (e) {
                    console.error('Error parsing polygon data:', e);
                  }
                });
                // end
              }

              if (response.wfa === true || response.wfa === "1" || response.wfa === 1) {
                  wfaAllowed = true;
              } else {
                  wfaAllowed = false;
              }
              console.log("WFA Access:", wfaAllowed, "Raw:", response.wfa);

              if ((response.akses && response.akses.length > 0) || (response.data && response.data.length > 0 && (!response.akses || response.akses.length === 0)) || wfaAllowed) {
                $(".btn").attr("disabled", false);
                $(".ketAkses").addClass("d-none");
              } else {
                $(".btn").attr("disabled", true);
                $(".ketAkses").removeClass("d-none");
              }
            }, 
            error: function(xhr, ajaxOptions, thrownError) {
              alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            },
            complete: function() {
                Swal.close();
                $("#overlay").show();
                if(cekLokasi != "") {
                    getDistanceLocation()
                }
            }
          });
      }
    }
  </script>
  <script>
    // Random stop between 30% and 80%
    let randomStop = Math.random() * (0.8 - 0.3) + 0.3;

    let tl = gsap.timeline();

    tl
      .to(".progress-bar-fill", {
        duration: 1,
        width: `${randomStop * 100}%`
      })
      .to(".progress-bar-fill", {
        duration: 1 - randomStop,
        width: "100%"
      })
      .to(".preloader", {
        duration: 0.5,
        opacity: 0,
        onComplete: () => {
          document.querySelector(".preloader").style.display = "none";
        }
      });
  </script>
  

  
  <script>
      $(".linimasa-absen:not(#btnDownloadDL)").click(function() {
        $.ajax({
          url: "/getLinimasa/absensi",
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            Swal.fire({
                title: 'Loading...',
                text: 'Mengambil data',
                allowOutsideClick: false, // mencegah klik di luar untuk menutup
                showConfirmButton: false, // sembunyikan tombol konfirmasi
                didOpen: () => {
                  Swal.showLoading(); // tampilkan animasi loading
                }
            });
          },
          success: function(response) {
            Swal.close(); // Tutup loading

            if (response.data && response.data.length > 0) {
                // Buat HTML untuk list data absensi
                let listHTML = '<ul style="text-align:left; list-style:none; padding-left:0; font-size: 87%;">';
    
                let data = true;
                response.data.forEach(function(item, index) {
                    if(data) {
                      data = false;
                      
                      listHTML += `
                        <li style="text-align: center; border-bottom: solid 1px; margin-bottom: 10px;">
                            ${item.nama}
                        </li>`
                    }
                    
                    const timeOptions = { 
                      hour: '2-digit', 
                      minute: '2-digit', 
                      second: '2-digit', 
                      hour12: false // Format 24 jam
                    };
                    
                    const dateOptions = {
                      year: 'numeric',
                      month: '2-digit',
                      day: '2-digit'
                    };
                    
                    const jamMasuk = new Date(item.jam_masuk).toLocaleTimeString(undefined, timeOptions);
                    const jamPulang = item.jam_pulang 
                                      ? new Date(item.jam_pulang).toLocaleTimeString(undefined, timeOptions) 
                                      : 'Belum pulang';
                    const tanggal = new Date(item.jam_masuk).toLocaleDateString(undefined, dateOptions);
                                      
                    const styleBelumPulang = item.jam_pulang 
                                      ? '' 
                                      : 'color: red';
                    const statusMasuk = item.status_masuk == "WFA" 
                                  ? '(WFA)' 
                                  : (item.status_masuk == "WFO" ? '(WFO)' : (item.status_masuk == "DL" ? '(DL)' : ''));
                const statusPulang = item.status_pulang == "WFA" 
                                  ? '(WFA)' 
                                  : (item.status_pulang == "WFO" ? '(WFO)' : (item.status_pulang == "DL" ? '(DL)' : ''));
    
                    listHTML += `
                        <li>
                            <small style="float: right; clear: none;">${tanggal}</small><br>
                            <strong>Masuk:</strong> ${jamMasuk} <small style="float: right; clear: none;"><strong>${statusMasuk}</strong></small><br>
                            <strong>Pulang:</strong> <span style="${styleBelumPulang}">${jamPulang}</span> <small style="float: right; clear: none;"><strong>${statusPulang}</strong></small>
                            <hr>
                        </li>
                    `;
                });
    
                listHTML += '</ul>';
    
                // Tampilkan SweetAlert dengan list
                Swal.fire({
                    title: 'Riwayat Absensi',
                    html: listHTML,
                    confirmButtonText: 'Tutup'
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada data',
                    text: 'Tidak ditemukan riwayat absensi untuk pegawai ini.',
                    confirmButtonText: 'OK'
                });
            }
          },
        }); 
      });
  </script>
  
  <script>
    // Event handler untuk tombol link agenda dalam SweetAlert
    $(document).on('click', '.link-button', function(e) {
      e.preventDefault();
      e.stopPropagation();
      var url = $(this).data('url');
      if (url) {
        window.open(url, '_blank');
      }
    });
  </script>
</body>

</html>