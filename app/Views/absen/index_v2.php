<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balai Kekarantinaan Kesehatan Kelas I Bandung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #3498db;
      /* Warna dasar yang ingin digunakan dari gambar */
      color: #0072ff;
    }


    .preloader {
      width: 100%;
      height: 100vh;
      position: fixed;
      background-color: #3498db;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .preloader img {
      max-width: 300px;
    }

    .wrapper {
      width: 100%;
      text-align: center;
    }

    .text {
      color: #ecf0f1;
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .progress-bar {
      width: 30%;
      min-width: 150px;
      height: 10px;
      background-color: #333;
      margin: 0 auto;
    }

    .progress {
      width: 0;
      height: 100%;
      background-color: gold;
    }

    @media (width <=768px) {
      .text {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.8rem;
      }

      .progress-bar {
        width: 50%;
      }
    }

    @media (width <=400px) {
      .text {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
      }
    }

    /* ================================================================================== */


    h1 {
      margin-bottom: 20px;
      font-size: 2.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    p {
      margin-bottom: 40px;
      font-size: 1rem;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    }

    .footer {
      background-color: #2c3e50;
      /* Warna latar belakang footer */
      color: #ecf0f1;
      /* Warna teks footer */
      text-align: center;
      padding: 10px 0 0;
      width: 100%;
      position: absolute;
      bottom: 0;
      z-index: 12;
    }

    .footer button {
      border: none;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      width: 120px;
      /* Lebar tombol */
    }

    .footer button.clock-in {
      background-color: #3498db;
      /* Warna untuk "Tandai Masuk" */
      color: white;
    }

    .footer button.clock-out {
      background-color: #e74c3c;
      /* Warna untuk "Tandai Keluar" */
      color: white;
    }

    .footer button:hover {
      opacity: 0.8;
      /* Efek transparansi saat hover */
    }

    video,
    canvas {
      width: 100vw;
      /* Membuat video dan canvas responsif */
      height: auto;
    }

    .header {
      position: absolute;
      text-align: center;
      width: 100vw;
    }

    .desc {
      background-color: #fff;
    }

    #map {
      float: left;
      height: 90px;
      width: 110px;
      margin-right: 10px;
    }

    #location,
    .ket,
    #time,
    .location-view {
      font-size: 9pt;
    }

    #overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      /* Warna latar belakang dengan transparansi 50% */
      z-index: 99;
      /* Pastikan elemen ini ada di atas konten lain */

      /* Animasi loading */
      &::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 60px;
        height: 60px;
        margin: -30px 0 0 -30px;
        border: 6px solid transparent;
        border-top: 6px solid #3498db;
        /* Warna garis loading */
        border-radius: 50%;
        animation: spin 2s linear infinite;
      }

      &::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 40px;
        height: 40px;
        margin: -20px 0 0 -20px;
        border: 6px solid transparent;
        border-top: 6px solid #e74c3c;
        /* Warna garis loading */
        border-radius: 50%;
        animation: spin 1s linear reverse infinite;
      }
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .container {
      position: relative;
      width: 100vw;
      /* height: calc(100vh - 40px); */
      /* Tinggi footer */
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    #video {
      transform: scaleX(-1);
      border: 20px solid #fff;
      background-color: #3498db;
      /* Warna dan ketebalan border */
    }

    .labelFoto {
      position: absolute;
      bottom: 0;
      width: 88%;
      height: 80px;
      background: rgba(0, 0, 0, 0.3);
      color: #fff;
      text-align: left;
      padding: 10px;
      box-sizing: border-box;
      border-radius: 5px;
      font-size: 7.5pt;
      margin-bottom: 25px;
      z-index: 10;
    }

    .thumbnailLabel {
      position: absolute;
      bottom: 0;
      right: 25px;
      width: 80px;
      margin-bottom: 25px;
      z-index: 11;
    }

    .thumbnailLabel img {
      width: 100%;
      height: auto;
    }

    .d-none {
      /*display: none;*/
    }
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
  <div id="overlay"></div>
  <!-- Preloader starts -->
  <div class="preloader">
    <div class='wrapper'>
      <div class="text-center">
        <img loading="lazy" src="/dist/img/logo-bkk.png" alt="logo" class="img-thumbnail p-0">
      </div>
      <div class='text'></div>
      <div class="progress-bar">
        <div class="progress"></div>
      </div>
    </div>
  </div>

  <div class="header">
    <p class="desc p-0 m-0">Selamat Datang Di BKK Kelas I Bandung</p>
    <p id="info" class="desc p-0 m-0 changeTitleInfo text-danger"></p>
    <p class="desc p-0 m-0">&nbsp;</p>
    <!--<p class="desc p-0 m-0">Masukkan NIP Anda</p>-->
  </div>
  <div class="container">
    <video id="video" autoplay="" muted="" playsinline=""></video>
    <div class="labelFoto d-none">
      <label class="location"></label>
      <div class="time"></div>
      <div class="ket">Latitude: <span class="latitude">-</span>, Longitude: <span class="longitude">-</span></div>
    </div>
    <div class="thumbnailLabel  d-none"><img src="/dist/img/logo-bkk.png" alt="logo" class=""></div>
  </div>

  <div class="footer">
    <div class="row">
      <div class="col"></div>
      <div class="col-sm-2 col-8">
        <input type="number" name="nip" class="form-control" placeholder="NIP/NOPEG" value="<?= $nip; ?>">
        <textarea name="address" class="form-control d-none" id="address"></textarea>
      </div>
      <div class="col"></div>
    </div>
    <button class="btn clock-in">Tandai Masuk</button>
    <button class="btn clock-out">Tandai Keluar</button>
    <div class="container-fluid bg-dark">
      <div class="row">
        <div class="col"></div>
        <div class="col-md-5 mb-0 py-4 text-start text-light">
          <div id="map" class="img-thumbnail"></div>
          <span class="location-view">Memuat lokasi terkini....</span>
          <span id="location" class="d-none">Informasi Lokasi Anda Akan Muncul Disini!</span>
          <div id="time"></div>
          <div class="ket">Latitude: <span id="latitude">-</span>, Longitude: <span id="longitude">-</span></div>
        </div>
        <div class="col"></div>
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
          document.getElementById('longitude').innerText = parseFloat(data.lon).toFixed(6);
          document.getElementById('latitude').innerText = parseFloat(data.lat).toFixed(6);
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

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(position) {
          onLocationFound({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy
          });
        },
        showError, // Use showError function for handling geolocation errors
        {
          enableHighAccuracy: true
        }
      );
    } else {
      target.textContent = 'Geolocation is not supported by this browser.';
    }
  </script>

  <script>
    $(".clock-in").on('click', function() {
      // Mengambil video element
      var video = document.getElementById('video');

      // Membuat canvas untuk menangkap gambar
      var canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      var context = canvas.getContext('2d');
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

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
                Swal.fire({
                  icon: 'success',
                  title: `Selamat datang!`,
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
                Swal.fire({
                  icon: 'success',
                  title: `Terimakasih, hati-hati dijalan!`,
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
    // Fungsi untuk mendapatkan format waktu yang diinginkan
    function getFormatTime() {
      var currentTime = new Date();
      var day = currentTime.getDate();
      var month = currentTime.getMonth() + 1;
      var year = currentTime.getFullYear();
      var hours = currentTime.getHours();
      var minutes = currentTime.getMinutes();
      var seconds = currentTime.getSeconds();
      var formattedTime = day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds + ' ' + (hours >= 12 ? 'PM' : 'AM') + ' GMT +07:00';
      return formattedTime;
    }

    setInterval(function() {
      var time = getFormatTime();
      document.getElementById('time').textContent = 'Waktu: ' + time;
    }, 1000);

    async function startVideo() {
      function requestWebcam() {
        navigator.mediaDevices.getUserMedia({
            video: {
              width: 640,
              height: 480
            }
          })
          .then((stream) => {
            video.srcObject = stream;

            // // Fix for iOS Safari from https://leemartin.dev/hello-webrtc-on-safari-11-e8bcb5335295
            // video.setAttribute('autoplay', '');
            // video.setAttribute('muted', '');
            // video.setAttribute('playsinline', '')
          })
          .catch((err) => {
            console.error('Error accessing webcam:', err);
            if (confirm('Failed to access webcam. Retry?')) {
              requestWebcam(); // Mencoba lagi jika pengguna menekan OK
            }
          });
      }

      requestWebcam();

      // Setelah video ditambahkan ke DOM
      video.addEventListener('play', async () => {
        video.style.marginTop = '50px'; // Atur margin top
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
      const data = [setLongitude, setLatitude];
      const isDataInside = getLocationDescription(data, targetPolygons);

      console.log(targetPolygons);
      console.log(isDataInside);

      if (isDataInside) {
        if (isDataInside == "Anda berada diluar wilayah absen") {
          $(".location-view").text($("#location").text());
          $(".location-view").addClass('text-danger');
        } else {
          $(".location-view").text(isDataInside);
          $(".location-view").removeClass('text-danger');
        }

        Swal.fire({
          icon: (isDataInside == "Anda berada diluar wilayah absen") ? "warning" : "success",
          title: "Terdeteksi",
          text: isDataInside,
          showConfirmButton: false,
          timer: 1500
        });
      }
      $("#overlay").hide();
    }

    // Simpan nilai awal teks
    var previousText = $('#location').text();
    var cekPreviousText = true;

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
    // Fungsi untuk memeriksa apakah titik berada di dalam salah satu poligon
    function getLocationDescription(point, polygons) {
      const x = point[0];
      const y = point[1];

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
        for (let i = 0; i < polygons.length; i++) {
          if (isInSinglePolygon(polygons[i].polygon)) {
            return polygons[i].ket_tempat; // Return the location description
          }
        }
        
        // Prioritas 2: Berada dalam radius toleransi (buffer zone)
        for (let i = 0; i < polygons.length; i++) {
            // Gunakan tolerance_meters spesifik per polygon jika ada, default ke 50 jika tidak
            let currentToleranceMeters = polygons[i].tolerance_meters ? parseFloat(polygons[i].tolerance_meters) : 50;
            let currentToleranceDegrees = currentToleranceMeters / 111320;
            
            let dist = distToPolygon([x, y], polygons[i].polygon);
            if (dist <= currentToleranceDegrees) {
                return polygons[i].ket_tempat;
            }
        }
        
        return 'Anda berada diluar wilayah absen'; // Default message if no match
    }

    // Data Zona Target (Multiple Polygons)
    const targetPolygons = [];

    getTargetPolygons();

    function getTargetPolygons() {
      $.ajax({
        url: '/getTargetAreaAbsen',
        dataType: 'json',
        success: function(response) {
          if (response.data) {
            // response.data[]['ket_tempat']
            response.data.forEach(function(item) {
              // Menghapus whitespace dan newline dari string poligon
              const cleanedPolygon = item.polygon
                .replace(/\r\n|\r|\n/g, '')
                .replace(/\s+/g, ' ')
                .trim();

              // Mengonversi string poligon menjadi array
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
          }
        },
      });
    }
  </script>
  <script>
    // Random stop between 30% and 80%
    let randomStop = Math.random() * (0.8 - 0.3) + 0.3;

    let tl = gsap.timeline();

    tl
      .to(".text", {
        duration: 2,
        text: "Selamat datang di Balai Kekarantinaan Kesehatan Kelas I Bandung"
      })
      .from(".progress-bar", {
        duration: 0.5,
        opacity: 0
      })
      .to(".progress", {
        duration: 1,
        width: `${randomStop * 100}%`
      })
      .to(".progress", {
        duration: 1 - randomStop,
        width: "100%"
      })
      .to(".text, .progress-bar", {
        duration: 0.5,
        opacity: 0
      })
      .to(".preloader", {
        duration: 0.5,
        height: 0,
        onComplete: () => {
          document.querySelector(".preloader").style.display = "none";
        }
      });
  </script>
</body>

</html>