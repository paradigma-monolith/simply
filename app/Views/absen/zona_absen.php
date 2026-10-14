<?= $this->extend('layouts/adminLTE-3.2.0'); ?>

<?= $this->section('content'); ?>
<section class="content">
    <div class="container-fluid">
        <div id="map" style="height:600px; width: 100%;"></div>
    </div>
</section>

<script>
  $(".request-zona").on('click', function() {
    var button = $(this);
    var buttonHtml = $(this).html();

    $(button).attr('disabled', true);
    $(button).html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`);

    clockInAction()
      .then(lokasi => {
        if (lokasi !== null && lokasi !== '') {
          // ajax
          var formData = {
            alamat: lokasi.address,
            latitude: lokasi.latitude,
            longitude: lokasi.longitude
          };

          $.ajax({
            url: `/absen/getZonaAbsen`,
            data: formData,
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
              $("#modal-sm").find('.modal-body').html('');
              $(button).attr('disabled', true);
              $(button).html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
            },
            success: function(response) {
              if (response.data) {
                $("#modal-sm").find('.modal-body').html(response.data);
                $("#modal-sm").modal('show');

              }
              $(button).attr('disabled', false)
              $(button).html(buttonHtml)
            },
          });
          // endAjax
        }
      })
      .catch(error => {
        console.error(error);
      });

  });

  function clockInAction() {
    return new Promise((resolve, reject) => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => {
            showPosition(position)
              .then(address => resolve({
                address,
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
              }))
              .catch(error => reject(error));
          },
          error => showError(error)
        );
      } else {
        reject('Geolocation is not supported by this browser.');
        Toast.fire({
          icon: 'error',
          title: 'Geolocation is not supported by this browser.'
        });
      }
    });
  }

  function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    var apiKey = '';
    // var apiKey = 'AIzaSyD4jKUxHGYL-QZDTL6KH1f47tOZnOxrHdA';
    var url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latitude + ',' + longitude + '&key=' + apiKey;

    return fetch(url)
      .then(response => response.json())
      .then(data => {
        if (data.results && data.results.length > 0) {
          return data.results[0].formatted_address;
        } else {
          throw new Error('No address found for the given coordinates.');
        }
      })
      .catch(error => {
        throw new Error('Failed to get address from coordinates: ' + error.message);
      });
  }

  function showError(error) {
    let errorMsg;
    switch (error.code) {
      case error.PERMISSION_DENIED:
        errorMsg = 'User denied the request for Geolocation.';
        break;
      case error.POSITION_UNAVAILABLE:
        errorMsg = 'Location information is unavailable.';
        break;
      case error.TIMEOUT:
        errorMsg = 'The request to get user location timed out.';
        break;
      case error.UNKNOWN_ERROR:
        errorMsg = 'An unknown error occurred.';
        break;
    }
    Toast.fire({
      icon: 'error',
      title: errorMsg
    });
  }
</script>

<script>
    // Inisialisasi peta
        var map = L.map('map').setView([-6.8989777711926195, 107.56555363407392], 8);

        // Menambahkan tile layer ke peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Data poligon dari PHP
        <?php 
        if(session()->get('role') == 'Admin'):
          foreach ($area as $data): ?>
            // Dekode JSON untuk poligon
            var polygonCoordinates = <?php echo json_encode(json_decode($data['polygon'])); ?>;
            console.log(polygonCoordinates);

            // Ubah urutan koordinat menjadi [lat, lng] untuk Leaflet
            polygonCoordinates = polygonCoordinates.map(function(coord) {
                return [coord[1], coord[0]]; // [lat, lng]
            });

            // Tambahkan poligon ke peta
            L.polygon(polygonCoordinates, {
                color: 'blue',
                fillColor: '#0000FF', // Warna hex untuk biru
                fillOpacity: 0.5
            }).addTo(map);
        <?php endforeach;
        endif;
        ?>

        // Data marker dari PHP
        var locations = <?php
            // Menghasilkan array dengan menggunakan metode helper
            $locations = [];
            foreach ($mark as $data) {
                if (!empty($data['latitude_masuk']) && !empty($data['longitude_masuk'])) {
                    $locations[] = [
                        'lat' => (float) $data['latitude_masuk'],
                        'lng' => (float) $data['longitude_masuk'],
                        'popup' => 'Waktu masuk ' . ($data['nama'] == session()->get('nama') ? htmlspecialchars(name_capitalize($data['nama'])) : (session()->get("role") == "Admin" ? htmlspecialchars(name_capitalize($data['nama'])) : 'seseorang'))
                    ];
                }
                if (!empty($data['latitude_pulang']) && !empty($data['longitude_pulang'])) {
                    $locations[] = [
                        'lat' => (float) $data['latitude_pulang'],
                        'lng' => (float) $data['longitude_pulang'],
                        'popup' => 'Waktu pulang ' . ($data['nama'] == session()->get('nama') ? htmlspecialchars(name_capitalize($data['nama'])) : (session()->get("role") == "Admin" ? htmlspecialchars(name_capitalize($data['nama'])) : 'seseorang'))
                    ];
                }
            }

            // Encode array ke format JSON untuk JavaScript
            echo json_encode($locations);
        ?>;

        // Inisialisasi grup marker cluster
        var markers = L.markerClusterGroup();
        
        // Ambil nama session dari PHP dan simpan dalam variabel JavaScript
        var sessionName = <?php echo json_encode(name_capitalize(session()->get('nama'))); ?>;
        
        // Tambahkan marker ke grup marker cluster
        locations.forEach(function(location) {
            // Tentukan warna marker berdasarkan kondisi
            var isUser = location.popup.includes('Waktu masuk ' + sessionName);
            var isPulang = location.popup.includes('Waktu pulang ' + sessionName);
        
            var iconColor;
            if (isUser) {
                iconColor = '#50C878'; // Hijau untuk Waktu masuk dengan nama session
            } else if (isPulang) {
                iconColor = '#FF6347'; // Merah untuk Waktu pulang dengan nama session
            } else {
                iconColor = '#4A90E2'; // Biru untuk kondisi lainnya
            }
        
            // Buat ikon dengan Font Awesome
            var icon = L.divIcon({
                className: 'custom-icon',
                html: '<i class="fa fa-map-marker-alt" style="color: ' + iconColor + '; font-size: 32px;"></i>',
                iconSize: [32, 32]
            });
        
            // Buat marker dengan ikon yang sudah ditentukan
            var marker = L.marker([location.lat, location.lng], { icon: icon })
                .bindPopup(location.popup);
            
            // Tambahkan marker ke grup marker cluster
            markers.addLayer(marker);
        });
        
        // Tambahkan grup marker cluster ke peta
        map.addLayer(markers);



</script>

<?= $this->endSection(); ?>