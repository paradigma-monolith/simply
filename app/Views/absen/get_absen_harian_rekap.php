<table id="tableWithoutExporting" class="table table-stripped table-sm table-bordered">
  <thead>
    <tr>
      <th style="width: 2%;">No</th>
      <th>Nama</th>
      <th>Status Masuk</th>
      <th style="width: 18%;">Alamat Masuk</th>
      <th>Jam Masuk</th>
      <th>Status Pulang</th>
      <th style="width: 18%;">Alamat Pulang</th>
      <th>Jam Pulang</th>
      <th>Jam Kerja</th>
      <th>Rekam Absen</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody class="addDataAbsenHarian">
    <?php
    $nomor = 1;
    foreach ($absen as $data) : 
    $konfirmasiAlamatMasuk = false;
    $konfirmasiAlamatPulang = false;
    
    $jamMasuk = $data['jam_masuk'];
    $jamPulang = $data['jam_pulang'];
    
    $aturanJamMasuk = $data["aturan_jam_masuk"];
    $aturanJamPulang = $data["aturan_jam_pulang"];
    $toleransiKeterlambatan = $data["toleransi_keterlambatan"];
    
    $statusAbsensi = statusAbsensi($jamMasuk,$jamPulang,$aturanJamMasuk,$aturanJamPulang,$toleransiKeterlambatan);

    if ($data['status_pulang'] == null) {
        $statusAbsen = "";
    } elseif ($data['status_pulang'] != $data['status_masuk']) {
        $statusAbsen = 'style="background-color: #f5c6cb !important;"';
    } else {
        $statusAbsen = "";
    }
    ?>
    
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['nama']; ?></td>
        <td><?= $data['status_masuk']; ?></td>
        <td <?= $statusAbsen; ?> class="class-absen-alamat-masuk" data-validation="<?= $data['validation_masuk']; ?>" data-jam="<?= $data['jam_masuk']; ?>" data-alamat="<?= $data['alamat_masuk']; ?>" data-longitude="<?= $data['longitude_masuk']; ?>" data-latitude="<?= $data['latitude_masuk']; ?>">
            <?php if($data['jam_masuk'] != null): ?>
                <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
            <?php endif; ?>
        </td>
        <td <?= $statusAbsen; ?> class="
        <?php
        if($jamMasuk == null) {
            echo "table-danger";
        } elseif($statusAbsensi['masuk'] == 'merah') {
            echo "table-danger";
        } elseif($statusAbsensi['masuk'] == 'kuning') {
            echo "table-warning";
        } elseif($statusAbsensi['masuk'] == 'hijau') {
            echo "table-success";
        }
        ?>
        ">
          <?= $data['jam_masuk'];
          ?>
        </td>

        <td <?= $statusAbsen; ?>><?= $data['status_pulang']; ?></td>
        <td <?= $statusAbsen; ?> class="class-absen-alamat-pulang" data-validation="<?= $data['validation_pulang']; ?>" data-jam="<?= $data['jam_pulang']; ?>" data-alamat="<?= $data['alamat_pulang']; ?>" data-longitude="<?= $data['longitude_pulang']; ?>" data-latitude="<?= $data['latitude_pulang']; ?>">
            <?php if($data['jam_pulang'] != null): ?>
                <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
            <?php endif; ?>
        </td>
        <td <?= $statusAbsen; ?> class="
        <?php
        if($jamPulang == null) {
            echo "table-danger";
        } elseif($statusAbsensi['pulang'] == 'merah') {
            echo "table-danger";
        } elseif($statusAbsensi['pulang'] == 'kuning') {
            echo "table-warning";
        } elseif($statusAbsensi['pulang'] == 'hijau') {
            echo "table-success";
        }
        ?>
        ">
          <?= $data['jam_pulang']; 
          ?>
        </td>

        <td <?= $statusAbsen; ?>>
          <?= selisi_waktu($data['jam_masuk'], $data['jam_pulang']); ?>
        </td>
        <td>
            <?php if($data['id_absen'] && $data['jumlah_array'] > 0): ?>
                <button class="btn btn-sm btn-info showRekamAbsen" data-id="<?= $data['id_absen']; ?>"> Lihat </button> <br />
                <span><?= $data['jumlah_array']; ?> rekaman tersedia!</span>
            <?php endif; ?>
        </td>
        <td>
            <h6 class="m-0 btn-verifikasi-masuk d-none">Verifikasi Masuk</h6>
            <div class="btn-group btn-verifikasi-masuk d-none" role="group" aria-label="Basic example">
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-success btn-absen-validasi-masuk"><i class="fas fa-map-marker-alt"></i> Validasi</button>
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-masuk"><i class="fas fa-map-marker"></i> Invalidasi</button>
            </div>
            <h6 class="m-0 mt-1 btn-verifikasi-pulang d-none">Verifikasi Pulang</h6>
            <div class="btn-group btn-verifikasi-pulang d-none" role="group" aria-label="Basic example">
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-success btn-absen-validasi-pulang"><i class="fas fa-map-marker-alt"></i> Validasi</button>
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-pulang"><i class="fas fa-map-marker"></i> Invalidasi</button>
            </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<script>
    $(document).on("click", ".showRekamAbsen", function(){
        var id = $(this).data('id');
        var $modal = $("#modal-lg");

        $.ajax({
            url: `/absen/getRekamAbsen/${id}`,
            method: 'get',
            dataType: 'json',
            beforeSend: function() {
                $("#overlay").show();
            },
            success: function(response) {
              if (response.data) {
                  $modal.find(".modal-body").html(response.data);
                  $modal.modal('show');
              }
            },error: function(xhr, ajaxOptions, thrownError) {
              Swal.fire({
                  'text' : xhr.status + "\n" + xhr.responseText + "\n" + thrownError
              })
            },
            complete: function() {
                $("#overlay").hide();
            }
        });
    });

    $(document).ready(function() {
        $('#tableWithoutExporting').DataTable().destroy();
        $("#tableWithoutExporting").DataTable({
            "responsive": true,
            "lengthChange": true, // Enable length change option
            "autoWidth": false,
            "lengthMenu": [10, 25],
            "responsive": {
                "details": {
                    "renderer": DataTable.Responsive.renderer.listHiddenNodes()
                }
            },
            "buttons": ["copy", "colvis"]
        }).buttons().container().appendTo('#tableWithoutExporting_wrapper .col-md-6:eq(0)');

        
      });
        
    // Data Zona Target (Multiple Polygons)
    var targetPolygons = [];
    var checkTargetPolygons = false;

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
                  ket_tempat: item.ket_tempat // Simpan ket_tempat bersama dengan poligon
                });
              } catch (e) {
                console.error('Error parsing polygon data:', e);
              }
            });
          }
        }, complete: function() {
            checkTargetPolygons = true;
        }
      });
    }
      
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

      // Check if the point is in any of the polygons
      for (let i = 0; i < polygons.length; i++) {
        if (isInSinglePolygon(polygons[i].polygon)) {
          return polygons[i].ket_tempat; // Return the location description
        }
      }
      return 'Anda berada diluar wilayah absen'; // Default message if no match
    }
    
      
      $(document).ready(function() {
        function processElements() {
            $('.class-absen-alamat-masuk').each(function() {
                const $location_masuk = $(this);
                if ($location_masuk.data('loaded')) {
                    return; // Skip if already processed
                }
                if ($location_masuk.offset().top < $(window).scrollTop() + $(window).height()) {
                    var longitude = $location_masuk.data('longitude');
                    var latitude = $location_masuk.data('latitude');
                    var validation = $location_masuk.data('validation');
                    var jam = $location_masuk.data('jam');
                    
                    if(longitude != "" && latitude != "") {
                        const data = [parseFloat(longitude), parseFloat(latitude)];
                        const isDataInside = getLocationDescription(data, targetPolygons);
                        
                        if (isDataInside == "Anda berada diluar wilayah absen") {
                            if(validation == "Validasi") {
                                $location_masuk.html("<strong class='text-success'>Absen Terverifikasi <i class='fas fa-check-circle'></i></strong> <br/>" + $location_masuk.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                                $location_masuk.addClass('table-warning').removeClass('table-success table-danger');
                                $location_masuk.data('loaded', true);
                            } else {
                                $location_masuk.html("<strong>Diluar Zona Absen</strong> <br/>" + $location_masuk.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                                $location_masuk.addClass('table-danger').removeClass('table-success');
                                $location_masuk.data('loaded', true);
                                if(validation == "Default") {
                                    $location_masuk.parent().find('.btn-verifikasi-masuk').removeClass('d-none');
                                }
                            }
                        } else {
                            $location_masuk.html(isDataInside + "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                            $location_masuk.removeClass('table-danger').addClass('table-success');
                            $location_masuk.data('loaded', true);
                            
                        }
                    } else {
                        if(jam != null && jam != "") {
                            $location_masuk.addClass('table-success').removeClass('table-danger');
                            $location_masuk.text("Data Sesuai");
                        } else {
                            $location_masuk.addClass('table-danger').removeClass('table-warning');
                        }
                        $location_masuk.data('loaded', true);
                    }
    
                }
            });
            $('.class-absen-alamat-pulang').each(function() {
              const $location_pulang = $(this);
              if ($location_pulang.data('loaded')) {
                  return; // Skip if already processed
              }
              if ($location_pulang.offset().top < $(window).scrollTop() + $(window).height()) {
                  var longitude = $location_pulang.data('longitude');
                  var latitude = $location_pulang.data('latitude');
                  var validation = $location_pulang.data('validation');
                  var jam = $location_pulang.data('jam');
                  
                  if(longitude != "" && latitude != "") {
                      const data = [parseFloat(longitude), parseFloat(latitude)];
                      const isDataInside = getLocationDescription(data, targetPolygons);
                      
                      if (isDataInside == "Anda berada diluar wilayah absen") {
                          if(validation == "Valid") {
                              $location_pulang.html("<strong class='text-success'>Absen Terverifikasi <i class='fas fa-check-circle'></i></strong> <br/>" + $location_pulang.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                              $location_pulang.addClass('table-danger').removeClass('table-warning');
                              $location_pulang.data('loaded', true);
                          } else {
                              $location_pulang.html("<strong>Diluar Zona Absen</strong> <br/>" + $location_pulang.data('alamat')+ "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                              $location_pulang.addClass('table-danger').removeClass('table-warning');
                              $location_pulang.data('loaded', true);
                              if(validation == "Default") {
                                  $location_pulang.parent().find('.btn-verifikasi-pulang').removeClass('d-none');
                              }
                          }
                      } else {
                          $location_pulang.html(isDataInside + "<br/><i>lon: " + longitude + ", lat: " + latitude + "</i>");
                          $location_pulang.removeClass('table-danger').addClass('table-success');
                          $location_pulang.data('loaded', true);
                          
                      }
                  } else {
                    if(jam != null && jam != "") {
                        $location_pulang.addClass('table-success').removeClass('table-danger');
                        $location_pulang.text("Data Sesuai");
                    } else {
                        $location_pulang.addClass('table-danger').removeClass('table-warning');
                    }
                    $location_pulang.data('loaded', true);
                  }
            
              }
          });
        }
        
        
        $(window).on('scroll, mouseenter', function() {
            if(checkTargetPolygons){
                processElements();
            }
        });
        
    });

    
</script>

<script>
    $(document).on('click', '.galleryAction', function() {
        var $this = $(this);
        var id = $(this).data('idabsen');
        var ket = $(this).data('ket');
        var secondImageUrl = '/absen/getSecondImage/' + id;
        
        
    
        $.ajax({
            url: secondImageUrl,
            method: 'GET',
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: "Memuat gambar",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                var items = [
                    {
                        src: $this.attr('src'),
                        type: 'image'
                    },
                ];
    
                if (response.data) {
                    if (ket == 'masuk' && response.data['image_masuk_2']) {
                        items.push({
                            src: response.data['image_masuk_2'],
                            type: 'image'
                        });
                    } else if (response.data['image_pulang_2']) {
                        items.push({
                            src: response.data['image_pulang_2'],
                            type: 'image'
                        });
                    }
                }
    
                 $.magnificPopup.open({
                    items: items,
                    gallery: {
                        enabled: true // Mengaktifkan galeri untuk navigasi
                    },
                    closeBtnInside: true
                });
    
                Swal.close(); // Menutup SweetAlert setelah loading selesai
            },
            error: function(xhr, status, error) {
                console.error('Failed to load second image:', error);
                Swal.close(); // Menutup SweetAlert jika terjadi kesalahan
            },
            complite: function() {
                Swal.close();
            }
        });
    
        return false; // Mencegah aksi default
    });


  
  $(document).on("click", ".btn-absen-validasi-masuk", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-masuk');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Validasi alamat masuk?",
      showCancelButton: true,
      confirmButtonText: "Valid",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = {
            keterangan_validasi_masuk: ""
        };
        $.ajax({
          url: '/absen/validasiMasuk/1/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });
              statusElement.removeClass('table-danger');
              statusElement.addClass('table-warning');
              statusElement.find('a').text('Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });

  $(document).on("click", ".btn-absen-invalidasi-masuk", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-masuk');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Invalidasi alamat masuk?",
      input: "text",
      showCancelButton: true,
      confirmButtonText: "Invalid",
      cancelButtonText: "Batal",
      confirmButtonColor: "#d33",
    }).then((result) => {
        var formData = {
            keterangan_validasi_masuk: result.value
        };
      if (result.isConfirmed) {
        $.ajax({
          url: '/absen/validasiMasuk/0/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });
              statusElement.removeClass('table-success');
              statusElement.addClass('table-danger');
              statusElement.find('a').text('Tidak Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });

  $(document).on("click", ".btn-absen-validasi-pulang", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-pulang');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Validasi alamat pulang?",
      showCancelButton: true,
      confirmButtonText: "Valid",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = {
            keterangan_validasi_pulang: ""
        };
        $.ajax({
          url: '/absen/validasiPulang/1/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });
              statusElement.removeClass('table-danger');
              statusElement.addClass('table-warning');
              statusElement.find('a').text('Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });

  $(document).on("click", ".btn-absen-invalidasi-pulang", function() {
    var statusElement = $(this).closest('tr').find('.class-absen-alamat-pulang');
    var id = parseInt($(this).data('id'), 10);

    Swal.fire({
      title: "Invalidasi alamat pulang?",
      input: "text",
      showCancelButton: true,
      confirmButtonText: "Invalid",
      cancelButtonText: "Batal",
      confirmButtonColor: "#d33",
      showLoaderOnConfirm: true,
    }).then((result) => {
      if (result.isConfirmed) {
        var formData = {
            keterangan_validasi_pulang: result.value
        };
        $.ajax({
          url: '/absen/validasiPulang/0/'+id,
          method: 'post',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.data) {
              Swal.fire({
                title: "Berhasil diperbaharui!",
                icon: "success",
                timer: 800, 
                showConfirmButton: false, 
                didOpen: () => {
                  Swal.showLoading();
                },
                willClose: () => {
                  Swal.hideLoading();
                }
              });

              statusElement.removeClass('table-success');
              statusElement.addClass('table-danger');
              statusElement.find('a').text('Tidak Sesuai');
            }
          }, error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
          }
        });
      }
    });
  });
</script>

<script>
  $(document).on('click', '.setNewRekamAbsen', function() {
    var container = $(this).closest('.media');
      
    var formData = {
      id_absen: container.find(".d-none").text(),
      rekam: container.find(".setAbsenRekam").text(),
      alamat: container.find(".setAbsenAlamat").text(),
      longitude: container.find(".setAbsenLongitude").text(),
      latitude: container.find(".setAbsenLatitude").text(),
      jam: container.find(".setAbsenJam").text(),
      image: container.find(".setAbsenImage").data("src"),
    };
    var masuk = false;

    $.ajax({
      url: '/absen/setNewAbsen',
      data: formData,
      method: 'post',
      dataType: 'json',
      beforeSend: function() {
        $("#overlay").show();
      },
      success: function(response) {
        if (response.data) {
            Swal.fire({
              icon: "success",
              title: "Data " + container.find(".setAbsenRekam").text() + " Berhasil Diperbaharui!",
              showConfirmButton: false,
              timer: 1500
            });
        } else {
            Swal.fire({
              icon: "error",
              title: "Data " + container.find(".setAbsenRekam").text() + " Gagal Diperbaharui!",
              showConfirmButton: false,
              timer: 1500
            });
        }
      },
      complete: function() {
        $("#overlay").hide();
      }
    });
  });
</script>