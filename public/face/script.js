const video = document.getElementById('video');
const desc = document.getElementById('desc');

Promise.all([
  faceapi.nets.faceRecognitionNet.loadFromUri('/face/models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('/face/models'),
  faceapi.nets.ssdMobilenetv1.loadFromUri('/face/models')
]).then(startVideo);

async function startVideo() {
  const container = document.querySelector('.container');
  const labeledFaceDescriptors = await loadLabeledImages();
  const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

  navigator.mediaDevices.getUserMedia({ video: {} })
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((err) => {
      console.error('Error accessing webcam:', err);
    });

  video.addEventListener('play', async () => {
    const canvas = faceapi.createCanvasFromMedia(video);
    container.append(video); // Append video element first
    container.append(canvas); // Then append canvas element
    canvas.style.position = 'absolute'; // Set canvas position to absolute
    // canvas.style.top = '0'; // Position canvas at the top
    // canvas.style.left = '0'; // Position canvas at the left
    canvas.style.width = '100vh'; // Set canvas width to 100vw
    canvas.style.height = 'auto'; // Set canvas height to 100vh
    const displaySize = { width: video.videoWidth, height: video.videoHeight }; // Use video.videoWidth and video.videoHeight
    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
      const detections = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors();
      const resizedDetections = faceapi.resizeResults(detections, displaySize);
      canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
      const results = resizedDetections.map(detection => faceMatcher.findBestMatch(detection.descriptor));

      var name = results.map(result => result._label).join(', ');
      if (name != '' && name != 'unknown') {
        desc.innerText = "Halo " + name + "!";
      } else {
        desc.innerText = "Halo...";
      }

      results.forEach((result, i) => {
        const box = resizedDetections[i].detection.box;
        const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
        // console.log('Label terdeteksi:', result.toString());
        // const cleanLabel = result.toString().replace(/[^a-zA-Z ]/g, '');
        // console.log(cleanLabel);        
        drawBox.draw(canvas);
      });
    }, 100);
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


$(document).ready(function() {
  $('.clock-in').click(function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
      $('#location').text("Geolocation is not supported by this browser.");
    }
  });

  function getFormatTime() {
    // Mendapatkan waktu saat ini
    var currentTime = new Date();

    // Mendapatkan informasi tanggal, bulan, tahun, jam, menit, dan detik
    var day = currentTime.getDate();
    var month = currentTime.getMonth() + 1; // Bulan dimulai dari 0 (Januari) hingga 11 (Desember)
    var year = currentTime.getFullYear();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();

    // Mengonversi ke format tertentu (DD/MM/YYYY HH:mm:ss AM/PM GMT +07:00)
    var formattedTime = day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds + ' ' + (hours >= 12 ? 'PM' : 'AM') + ' GMT +07:00';

    return formattedTime;
  }

  function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;

    var apiKey = 'AIzaSyD4jKUxHGYL-QZDTL6KH1f47tOZnOxrHdA'; // API Key yang diberikan
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