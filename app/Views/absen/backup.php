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
      background-image: url('/images/background.jpg');
      /* Path ke gambar latar belakang */
      background-color: #3498db;
      /* Warna dasar yang ingin digunakan dari gambar */
      background-size: cover;
      /* Untuk mengisi seluruh latar belakang */
      color: #0072ff;
    }

    .container {
      width: 100vw;
      height: calc(100vh - 40px);
      /* Tinggi footer */
      display: flex;
      flex-direction: column;
      /*justify-content: center;*/
      align-items: center;
      text-align: center;
    }

    h1 {
      margin-bottom: 20px;
      opacity: 0;
      font-size: 2.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    p {
      margin-bottom: 40px;
      opacity: 0;
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
      border: 20px solid #fff;
      background-color: #3498db;
      /* Warna dan ketebalan border */
    }

    .header {
      position: absolute;
      text-align: center;
      width: 100vw;
    }

    #desc {
      background-color: #fff;
    }

    #map {
      float: left;
      height: 90px;
      margin-right: 10px;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1 id="title">
      <!--<strong>Selamat Datang di <br/>BKK Bandung</strong>-->
    </h1>
    <p id="desc">Loading...</p>
  </div>
  <div class="container">
    <video loading="lazy" id="video" autoplay></video>
  </div>
  <div class="footer">
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




  <!-- jQuery -->
  <script src="/plugins/jquery/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/animejs"></script>

  <script src="/face/face-api.min.js"></script>
  <!-- <script src="/face/script.js"></script> -->

  <script>
    const video = document.getElementById('video');
    const desc = document.getElementById('desc');

    Promise.all([
      faceapi.nets.faceRecognitionNet.loadFromUri('/face/models'),
      faceapi.nets.faceLandmark68Net.loadFromUri('/face/models'),
      faceapi.nets.ssdMobilenetv1.loadFromUri('/face/models')
    ]).then(startVideo)
    .catch((error) => {
      console.error('Error loading models:', error);
      alert('Error loading models. Please try again later.');
    });


    async function startVideo() {
      const container = document.querySelector('.container');
      const labeledFaceDescriptors = await loadLabeledImages();
      const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

      navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
        .then((stream) => {
          video.srcObject = stream;
        })
        .catch((err) => {
          console.error('Error accessing webcam:', err);
          alert('Error accessing webcam: ' + err.message);
        });

      video.addEventListener('play', async () => {
        const canvas = faceapi.createCanvasFromMedia(video);
        container.append(video); // Append video element first
        container.append(canvas); // Then append canvas element
        canvas.style.position = 'absolute'; // Set canvas position to absolute
        canvas.style.width = '100vw'; // Set canvas width to 100vh
        canvas.style.height = 'auto'; // Set canvas height to auto
        const displaySize = { width: video.videoWidth, height: video.videoHeight }; // Use video.videoWidth and video.videoHeight
        faceapi.matchDimensions(canvas, displaySize);

        // setInterval(async () => {
        //   const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();
        //   const resizedDetections = faceapi.resizeResults(detections, displaySize);
        //   canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        //   const results = resizedDetections.map(detection => faceMatcher.findBestMatch(detection.descriptor));

        //   var name = results.map(result => result._label).join(', ');
        //   if (name != '' && name != 'unknown') {
        //     desc.innerText = "Halo " + name + "!";
        //   } else {
        //     desc.innerText = "Halo...";
        //   }

        //   results.forEach((result, i) => {
        //     const box = resizedDetections[i].detection.box;
        //     const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
        //     drawBox.draw(canvas);
        //   });
        // }, 5000); // Set interval to 200ms
      });
    }

    function loadLabeledImages() {
      const labels = ['Hadian Zainal M', 'Anak Cianjur'];
      return Promise.all(
        labels.map(async label => {
          const descriptions = [];
          for (let i = 1; i <= 2; i++) {
            const img = await faceapi.fetchImage(`/face/labeled_images/${label}/${i}.jpg`);
            const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
            descriptions.push(detections.descriptor);
          }
          return new faceapi.LabeledFaceDescriptors(label, descriptions);
        })
      );
    }
    
    function requestWebcam() {
      navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
        .then((stream) => {
          video.srcObject = stream;
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

    $(document).ready(function() {
    //   requestWebcam();
    
      $('.clock-in').click(function() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
          $('#location').text("Geolocation is not supported by this browser.");
        }
      });

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

      function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        // var apiKey = 'AIzaSyD4jKUxHGYL-QZDTL6KH1f47tOZnOxrHdA';
        var apiKey = '';
        var mapSrc = `https://maps.googleapis.com/maps/api/staticmap?center=${latitude},${longitude}&zoom=15&size=600x400&markers=color:red%7C${latitude},${longitude}&key=${apiKey}`;
        $('#map').attr('src', mapSrc);

        var geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${apiKey}`;
        $.get(geocodeUrl, function(data) {
          if (data.status === 'OK') {
            var address = data.results[0].formatted_address;
            $('#location').html(address);
            $("#time").html("Latitude: " + latitude + ", Longitude: " + longitude + "<br>" + getFormatTime());
          } else {
            $('#location').html("Geocode was not successful for the following reason: " + data.status + "<br>");
            $("#time").html('');
          }
        });
      }

      function showError(error) {
        switch (error.code) {
          case error.PERMISSION_DENIED:
            $('#location').text("User denied the request for Geolocation.");
            break;
          case error.POSITION_UNAVAILABLE:
            $('#location').text("Location information is unavailable.");
            break;
          case error.TIMEOUT:
            $('#location').text("The request to get user location timed out.");
            break;
          case error.UNKNOWN_ERROR:
            $('#location').text("An unknown error occurred.");
            break;
        }
      }
    });
  </script>

  <script>
    // Animasi judul
    anime({
      targets: '#title',
      translateY: [-50, 0],
      opacity: [0, 1],
      duration: 1000,
      easing: 'easeOutQuad',
      delay: 500
    });

    // Animasi deskripsi
    anime({
      targets: '#desc',
      translateY: [50, 0],
      opacity: [0, 1],
      duration: 1000,
      easing: 'easeOutQuad',
      delay: 800
    });
  </script>

</body>

</html>