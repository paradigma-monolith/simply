<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balai Kekarantinaan Kesehatan Kelas I Bandung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
      margin-right: 10px;
    }

    #location,
    .ket,
    #time {
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
      display: none;
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
      <div class="ket"><span class="latitude"></span><span class="longitude"></span></div>
      <!--<div class="ket">Latitude: <span class="latitude">-</span>, Longitude: <span class="longitude">-</span></div>-->
    </div>
    <div class="thumbnailLabel  d-none"><img src="/dist/img/logo-bkk.png" alt="logo" class=""></div>
  </div>

  <div class="footer">
    <div class="row">
      <div class="col"></div>
      <div class="col-sm-2 col-8">
        <input type="number" name="nip" class="form-control" placeholder="NIP/NOPEG" value="<?= $nip; ?>">
      </div>
      <div class="col"></div>
    </div>
    <button class="btn clock-in">Tandai Masuk</button>
    <button class="btn clock-out">Tandai Keluar</button>
    <div class="container-fluid bg-dark">
      <div class="row">
        <div class="col"></div>
        <div class="col-md-5 mb-0 py-4 text-start text-light">
          <img loading="lazy" id="map" src="https://www.accessibility.org.au/wp-content/uploads/2024/03/google-maps-logo-768x768.png" class="img-thumbnail" alt="...">
          <span id="location"></span>
          <span id="locationss">Lokasi anda akan terekan oleh Admin, Pastikan foto terlihat berada dilingkungan kantor!</span>
          <div id="time"></div>
          <div class="ket"><span id="latitude"></span><span id="longitude"></span></div>
          <!--<div class="ket">Latitude: <span id="latitude">-</span>, Longitude: <span id="longitude">-</span></div>-->
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

    // Daftar nilai target lokasi
    var targetLocations = [
      "Informasi Lokasi Anda Akan Muncul Disini!",
      "Gagal mendapatkan alamat.",
      "User denied the request for Geolocation.",
      "Location information is unavailable.",
      "The request to get user location timed out.",
      "An unknown error occurred.",
      "Geolocation is not supported by this browser.",
      "Geolocation is not supported by this browser."
    ];

    $(document).ready(function() {
    //   $('input[name="nip"]').on('focus', function() {
    //     $('.clock-in, .clock-out').prop('disabled', true).addClass('disabled-button');
    //   }).on('blur', function() {
    //     checkAndDisable()
    //   });
    });

    // Fungsi untuk memeriksa teks dan menambahkan atribut disabled
    function checkAndDisable() {
    //   var targetLocation = target.textContent;
    //   if (targetLocations.includes(targetLocation)) {
    //     document.querySelectorAll('.clock-in, .clock-out').forEach(function(element) {
    //       element.setAttribute('disabled', 'disabled');
    //     });
    //   } else {
    //     document.querySelectorAll('.clock-in, .clock-out').forEach(function(element) {
    //       element.removeAttribute('disabled');
    //     });
    //   }
    }

    // Buat instance MutationObserver
    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === 'childList') {
          checkAndDisable();
        }
      });
    });

    // Konfigurasi MutationObserver
    var config = {
      childList: true,
      subtree: true
    };

    // Mulai mengamati target
    observer.observe(target, config);

    // Cek dan atur awal
    checkAndDisable();


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

  <script>
    // document.addEventListener('DOMContentLoaded', function() {
    //   clockInAction();
    //   setInterval(clockInAction, 1000);

    //   let timerInterval;
    //   Swal.fire({
    //     title: "Mencari alamat terdekat!",
    //     html: "Tolong tunggu beberapa saat..",
    //     timer: 10000,
    //     timerProgressBar: true,
    //     allowOutsideClick: false,
    //     didOpen: () => {
    //       Swal.showLoading();
    //     },
    //     willClose: () => {
    //       clearInterval(timerInterval);
    //     }
    //   }).then((result) => {
    //     /* Read more about handling dismissals below */
    //     if (result.dismiss === Swal.DismissReason.timer) {
    //       console.log("I was closed by the timer");
    //     }
    //   });
    // })

    // Fungsi untuk memulai video dari webcam
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

    function updateVisibility() {
        // var currentHour = new Date().getHours();
        // var currentDay = new Date().getDay(); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu
        
        // // Menghapus class `d-none` jika hari Sabtu atau Minggu
        // if (currentDay === 0 || currentDay === 6) {
        //     $('.clock-in').removeClass('d-none');
        //     $('.clock-out').removeClass('d-none');
        // } else {
        //     // Menghapus class `d-none` jika jam lebih dari 15 pada hari kerja
        //     if (currentHour >= 9) {
        //         $('.clock-out').removeClass('d-none');
        //     } else {
        //         $('.clock-out').addClass('d-none');
        //     }
        
        //     // Menghapus class `d-none` jika jam lebih dari 14 pada hari kerja
        //     if (currentHour > 9) {
        //         $('.clock-in').addClass('d-none');
        //     } else {
        //         $('.clock-in').removeClass('d-none');
        //     }
        // }

    }
    // Fungsi untuk menangani hasil dari geolocation
    function showPosition(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
    //   var apiKey = 'AIzaSyD4jKUxHGYL-QZDTL6KH1f47tOZnOxrHdA';
      var apiKey = '';
      var url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latitude + ',' + longitude + '&key=' + apiKey;
      var mapSrc = `https://maps.googleapis.com/maps/api/staticmap?center=${latitude},${longitude}&zoom=15&size=600x400&markers=color:red%7C${latitude},${longitude}&key=${apiKey}`;
      document.getElementById('map').src = mapSrc;
      document.getElementById('latitude').textContent = latitude;
      document.getElementById('longitude').textContent = longitude;

      fetch(url)
        .then(response => response.json())
        .then(data => {
          var address = data.results[0].formatted_address;
          var time = getFormatTime();
        //   document.getElementById('location').textContent = address;
          document.getElementById('time').textContent = 'Waktu: ' + time;
          
        //   updateVisibility()
        })
        .catch(error => {
          console.error('Error:', error);
        //   document.getElementById('location').textContent = 'Gagal mendapatkan alamat.';
          document.getElementById('time').textContent = '';
        });
    }

    // Fungsi untuk menampilkan pesan kesalahan geolocation
    function showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
        //   document.getElementById('location').textContent = "User denied the request for Geolocation.";
          break;
        case error.POSITION_UNAVAILABLE:
        //   document.getElementById('location').textContent = "Location information is unavailable.";
          break;
        case error.TIMEOUT:
        //   document.getElementById('location').textContent = "The request to get user location timed out.";
          break;
        case error.UNKNOWN_ERROR:
        //   document.getElementById('location').textContent = "An unknown error occurred.";
          break;
      }
    }

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

    // Memanggil fungsi startVideo saat halaman dimuat
    startVideo();

    // Menambahkan event listener untuk tombol "Tandai Masuk"
    function clockInAction() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
      } else {
        document.getElementById('location').textContent = "Geolocation is not supported by this browser.";
      }
    }

    // Menambahkan event listener untuk tombol "Tandai Keluar"
    document.querySelector('.clock-out').addEventListener('click', function() {
      // Tambahkan logika untuk "Tandai Keluar" di sini
    });
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
        formData.append('alamat', $("#location").text());

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
        formData.append('alamat', $("#location").text());

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

    function getDistanceLocation() {
      var alamat = $("#location").text();
      var formData = {
        longitude: $("#longitude").text(),
        latitude: $("#latitude").text(),
        alamat: alamat
      };

      $.ajax({
        url: `/absen/get_distance`,
        data: formData,
        method: 'post',
        dataType: 'json',
        beforeSend: function() {
        //   $("#overlay").show();
        },
        success: function(response) {
          console.log(response.alamat)
          if (response.alamat) {
            $("#overlay").hide();
            Swal.fire({
              icon: "success",
              title: "Lokasi Sesuai Titik",
              text: alamat,
              showConfirmButton: false,
              timer: 1500
            });
          } else if (response.distance > 10) {
            Swal.fire({
              title: "Lokasi Terbaru",
              html: "Jarak Terdeteksi Melebihi 10 Meter <hr /><h5 class='py-1'>Alamat terdeteksi</h5><small>" + alamat + "</small>",
              icon: "warning",
              showCancelButton: true,
              allowOutsideClick: false,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Perbarui Lokasi!",
              cancelButtonText: "Lanjutkan Absensi!"
            }).then((result) => {
              if (result.isConfirmed) {
                getDistanceLocation();
              } else {
                Swal.fire({
                  icon: "warning",
                  title: "Lokasi Anda Teridentifikasi",
                  text: alamat,
                  showConfirmButton: false,
                  timer: 1500
                });
                $("#overlay").hide();
              }
            });
          } else {
            $("#overlay").hide();
            Swal.fire({
              icon: "success",
              title: "Lokasi Sesuai Titik",
              text: alamat,
              showConfirmButton: false,
              timer: 1500
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

    // Cek perubahan setiap detik
    setInterval(checkForChange, 1000);
  </script>
</body>

</html>