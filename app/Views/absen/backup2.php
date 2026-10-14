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
      z-index: 99;
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

    .container {
      width: 100vw;
      height: calc(100vh - 40px);
      /* Tinggi footer */
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    h1 {
      margin-bottom: 20px;
      font-size: 2.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    p {
      margin-bottom: 40px;
      font-size: 1.2rem;
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
      position: fixed;
      bottom: 0;
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

    #video {
      transform: scaleX(-1);
      border: 20px solid #fff;
      background-color: #3498db;
      /* Warna dan ketebalan border */
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
    #time {
      font-size: 9pt;
    }
  </style>

  <!-- jQuery -->
  <script src="/plugins/jquery/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js" integrity="sha512-xAxjKW1J/R4TFytv43xvQ7jFTrJlBzsDOH/Aw0fFc2nvarQkE3d08lTicjzUsZJYDoHYDCpwsUUqOJXh34A1sw==" crossorigin="anonymous" referrerpolicy="no-referrer">
  </script>
</head>

<body>

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
    <p class="desc p-0 m-0">Pastikan Foto Berada di Posisi Benar dan Jelas</p>
    <p class="desc p-0 m-0">Masukkan NIK Anda</p>
  </div>
  <div class="container">
    <video id="video" autoplay></video>
  </div>
  <div class="footer">
    <div class="row">
      <div class="col"></div>
      <div class="col-sm-2 col-8">
        <input type="number" name="nip" class="form-control" placeholder="NIP/NOPEG">
      </div>
      <div class="col"></div>
    </div>
    <button class="clock-in">Tandai Masuk</button>
    <button class="clock-out">Tandai Keluar</button>
    <div class="container-fluid bg-dark">
      <div class="row">
        <div class="col"></div>
        <div class="col-md-5 mb-0 py-4 text-start text-light">
          <img loading="lazy" id="map" src="https://www.accessibility.org.au/wp-content/uploads/2024/03/google-maps-logo-768x768.png" class="img-thumbnail" alt="...">
          <span id="location">Informasi Lokasi Anda Akan Muncul Disini!</span>
          <div id="time"></div>
        </div>
        <div class="col"></div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Random stop between 30% and 80%
    let randomStop = Math.random() * (0.8 - 0.3) + 0.3;

    let tl = gsap.timeline();

    tl
      .to(".text", {
        duration: 2,
        text: "Selamat datang di Balai Kekarantinaan Kesetahan Kelas I Bandung"
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
    document.addEventListener('DOMContentLoaded', function() {
      clockInAction();
      setInterval(clockInAction, 1000);
    })

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

    // Fungsi untuk menangani hasil dari geolocation
    function showPosition(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
      var apiKey = '';
    //   var apiKey = 'AIzaSyD4jKUxHGYL-QZDTL6KH1f47tOZnOxrHdA';
      var url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latitude + ',' + longitude + '&key=' + apiKey;
      var mapSrc = `https://maps.googleapis.com/maps/api/staticmap?center=${latitude},${longitude}&zoom=15&size=600x400&markers=color:red%7C${latitude},${longitude}&key=${apiKey}`;
      document.getElementById('map').src = mapSrc;

      fetch(url)
        .then(response => response.json())
        .then(data => {
          var address = data.results[0].formatted_address;
          var time = getFormatTime();
          document.getElementById('location').textContent = address;
          document.getElementById('time').textContent = 'Waktu: ' + time;
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('location').textContent = 'Gagal mendapatkan alamat.';
          document.getElementById('time').textContent = '';
        });
    }

    // Fungsi untuk menampilkan pesan kesalahan geolocation
    function showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          document.getElementById('location').textContent = "User denied the request for Geolocation.";
          break;
        case error.POSITION_UNAVAILABLE:
          document.getElementById('location').textContent = "Location information is unavailable.";
          break;
        case error.TIMEOUT:
          document.getElementById('location').textContent = "The request to get user location timed out.";
          break;
        case error.UNKNOWN_ERROR:
          document.getElementById('location').textContent = "An unknown error occurred.";
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
              console.log(response.data);
              Swal.fire({
                icon: 'success',
                title: `Selamat datang!`,
                html: response.pesan
              });
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
  </script>
</body>

</html>