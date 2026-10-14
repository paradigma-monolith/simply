<table id="tableWithoutExporting" class="table table-stripped table-sm table-bordered">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Alamat Masuk</th>
      <th>Jam Masuk</th>
      <th style="width: 1%;">Foto Masuk</th>
      <th>Alamat Pulang</th>
      <th>Jam Pulang</th>
      <th style="width: 1%;">Foto Pulang</th>
      <th>Jam Kerja</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody class="addDataAbsenHarian">
    <?php
    $nomor = 1;
    foreach ($absen as $data) : 
    $konfirmasiAlamatMasuk = false;
    $konfirmasiAlamatPulang = false;
    ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $data['nama']; ?></td>
        <td class="class-absen-alamat-masuk <?= $data['validation_masuk'] == "Default" ? (in_array($data['alamat_masuk'], $zona) ? 'table-success' : ($data['keterangan'] != "" ? 'table-success' : 'table-danger')) : ($data['validation_masuk'] == "Validasi" ? 'table-success' : 'table-danger'); ?>">
          <?php if ($data['alamat_masuk'] != null && $data['alamat_masuk'] != '') : ?>
            <a href="javascript:void(0);" class="search-alamat no-decoration" data-alamat="<?= $data['alamat_masuk']; ?>" data-latitude="<?= $data['latitude_masuk']; ?>" data-longitude="<?= $data['longitude_masuk']; ?>">
            <?php if($data['validation_masuk'] == "Default"): ?>
              <?php if (in_array($data['alamat_masuk'], $zona)) {
                echo 'Sesuai';
              } else {
                echo 'Tidak Sesuai';
                $konfirmasiAlamatMasuk = true;
              } ?>
            <?php else: ?>
              <?php if($data['validation_masuk'] == "Validasi"): ?>
                Sesuai
              <?php else: ?>
                Tidak Sesuai
              <?php endif; ?>
            <?php endif; ?>
            </a>
          <?php endif; ?>
        </td>
        <td class="<?php
                    // Menginisialisasi nilai default
                    $status = 'table-danger';
                    $excessMinutes = 0;
                    $minutesLate = 0;
                    $minutesMore = 0;
                    
                    // Mengecek jika jam masuk dan jam pulang ada
                    if ($data['jam_pulang'] != null && $data['jam_pulang'] != "" && $data['jam_masuk'] != null && $data['jam_masuk'] != "") {
                      $jamPulangHour = (int) date('H', strtotime($data['jam_pulang']));
                      $jamPulangMinute = (int) date('i', strtotime($data['jam_pulang']));
                      $hariPulang = (int) date('N', strtotime($data['jam_pulang']));
                    
                      if ($hariPulang == 5) { // Jumat
                        if ($jamPulangHour > 16 || ($jamPulangHour == 16 && $jamPulangMinute >= 30)) {
                          $status = 'table-success';
                    
                          // Hitung selisih menit
                          $endHour = 16;
                          $endMinute = 30;
                          $minutesMore = (($jamPulangHour * 60) + $jamPulangMinute) - (($endHour * 60) + $endMinute);
                          $excessMinutes = max($minutesMore, 0);
                        }
                      } else {
                        if ($jamPulangHour >= 16) {
                          $status = 'table-success';
                    
                          // Hitung selisih menit
                          $endHour = 16;
                          $minutesMore = (($jamPulangHour * 60) + $jamPulangMinute) - ($endHour * 60);
                          $excessMinutes = max($minutesMore, 0);
                        }
                      }
                    
                      $jamMasukHour = (int) date('H', strtotime($data['jam_masuk']));
                      $jamMasukMinute = (int) date('i', strtotime($data['jam_masuk']));
                      $hariMasuk = (int) date('N', strtotime($data['jam_masuk']));
                    
                      // Hitung selisih menit dari jam 8:00
                      $startHour = 8;
                      $startMinute = 0;
                      $totalMinutesMasuk = ($jamMasukHour * 60) + $jamMasukMinute;
                      $totalMinutesStart = ($startHour * 60) + $startMinute;
                      $minutesLate = $totalMinutesMasuk - $totalMinutesStart;
                    
                      if ($jamMasukHour < 8 || ($jamMasukHour == 8 && $jamMasukMinute == 0)) {
                        $status = 'table-success';
                        $excessMinutes = 0; // Tidak terlambat jika sebelum jam 8:00
                      } else {
                        $status = 'table-danger';
                        $excessMinutes = $minutesLate; // Menit keterlambatan
                      }
                    } elseif ($data['jam_masuk'] != null && $data['jam_masuk'] != "") {
                      $jamMasukHour = (int) date('H', strtotime($data['jam_masuk']));
                      $jamMasukMinute = (int) date('i', strtotime($data['jam_masuk']));
                      $hariMasuk = (int) date('N', strtotime($data['jam_masuk']));
                    
                      if ($hariMasuk == 5) { // Jumat
                        if ($jamMasukHour < 8 || ($jamMasukHour == 8 && $jamMasukMinute == 0)) {
                          $status = 'table-success';
                        }
                      } else {
                        if ($jamMasukHour < 8 || ($jamMasukHour == 8 && $jamMasukMinute == 0)) {
                          $status = 'table-success';
                        }
                      }
                    }
                    
                    if ($minutesLate > 0) {
                      if ($minutesLate - $minutesMore <= 0) {
                        $status = 'table-success';
                      }
                    }
                    echo $status;
                    ?>">
          <?= $data['jam_masuk']; // Tampilkan 'jam_masuk' 
          ?>
        </td>

        <td>
          <?php if ($data['image_masuk_1'] != null && $data['image_masuk_1'] != '') : ?>
            <img src="/dist/img/boxed-bg.jpg" data-src="<?= $data['image_masuk_1']; ?>" data-idabsen="<?= $data['id_absen']; ?>" data-ket="masuk" class="lazy galleryAction" alt="Foto Masuk" style="width: 100%; aspect-ratio: 9 / 16; object-fit: contain;" />
          <?php endif; ?>
        </td>
        <td class="class-absen-alamat-pulang <?= $data['validation_pulang'] == "Default" ? (in_array($data['alamat_pulang'], $zona) ? 'table-success' : ($data['keterangan'] != "" ? 'table-success' : 'table-danger')) : ($data['validation_pulang'] == "Validasi" ? 'table-success' : 'table-danger'); ?>">
          <?php if ($data['alamat_pulang'] != null && $data['alamat_pulang'] != '') : ?>
            <a href="javascript:void(0);" class="search-alamat no-decoration" data-alamat="<?= $data['alamat_pulang']; ?>" data-latitude="<?= $data['latitude_pulang']; ?>" data-longitude="<?= $data['longitude_pulang']; ?>">
            <?php if($data['validation_pulang'] == "Default"): ?>
              <?php if (in_array($data['alamat_pulang'], $zona)) {
                echo 'Sesuai';
              } else {
                echo 'Tidak Sesuai';
                $konfirmasiAlamatPulang = true;
              } ?>
            <?php else: ?>
              <?php if($data['validation_pulang'] == "Validasi"): ?>
                Sesuai
              <?php else: ?>
                Tidak Sesuai
              <?php endif; ?>
            <?php endif; ?>
            </a>
          <?php endif; ?>
        </td>
        <td class="<?php
                    // Cek apakah 'jam_pulang' tidak null dan tidak kosong
                    if ($data['jam_pulang'] != null && $data['jam_pulang'] != "") {
                      // Cek apakah hari ini adalah hari Jumat
                      if (date('N', strtotime($data['jam_pulang'])) == 5) {
                        // Jika hari ini Jumat, cek apakah jam pulang lebih besar atau sama dengan 16:30
                        echo ((int) date('H', strtotime($data['jam_pulang'])) > 16 || ((int) date('H', strtotime($data['jam_pulang'])) == 16 && (int) date('i', strtotime($data['jam_pulang'])) >= 30)) ? 'table-success' : 'table-danger';
                      } else {
                        // Jika bukan hari Jumat, cek apakah jam pulang lebih besar atau sama dengan 16:00
                        echo ((int) date('H', strtotime($data['jam_pulang'])) >= 16) ? 'table-success' : 'table-danger';
                      }
                    } else {
                      // Jika 'jam_pulang' null atau kosong, beri kelas 'table-danger'
                      echo 'table-danger';
                    }
                    ?>">
          <?= $data['jam_pulang']; // Tampilkan 'jam_pulang' 
          ?>
        </td>

        <td>
          <?php if ($data['image_pulang_1'] != null && $data['image_pulang_1'] != '') : ?>
            <img src="/dist/img/boxed-bg.jpg" data-src="<?= $data['image_pulang_1']; ?>" data-idabsen="<?= $data['id_absen']; ?>" data-ket="pulang" class="lazy galleryAction" alt="Foto Pulang" style="width: 100%; aspect-ratio: 9 / 16; object-fit: contain;" />
          <?php endif; ?>
        </td>
        <td>
          <?= selisi_waktu($data['jam_masuk'], $data['jam_pulang']); ?>
        </td>
        <td>
            <?php if ($konfirmasiAlamatMasuk) : ?>
            <h6 class="m-0">Verifikasi Masuk</h6>
            <div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-success btn-absen-validasi-masuk"><i class="fas fa-map-marker-alt"></i> Validasi</button>
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-masuk"><i class="fas fa-map-marker"></i> Invalidasi</button>
            </div>
            <?php endif; ?>
            <?php if ($konfirmasiAlamatPulang) : ?>
            <h6 class="m-0 mt-1">Verifikasi Pulang</h6>
            <div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-success btn-absen-validasi-pulang"><i class="fas fa-map-marker-alt"></i> Validasi</button>
              <button type="button" data-id="<?= $data['id_absen']; ?>" class="btn btn-sm btn-danger btn-absen-invalidasi-pulang"><i class="fas fa-map-marker"></i> Invalidasi</button>
            </div>
            <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

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
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                console.log(response);
                console.log(response.data);
                console.log(response.data['image_masuk_2']);
                var items = [
                    {
                        src: $this.data('src'),
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
              statusElement.addClass('table-success');
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
              statusElement.addClass('table-success');
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
  
  $(document).ready(function() {
    $("#tableWithoutExporting").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "colvis"]
    }).buttons().container().appendTo('#tableWithoutExporting_wrapper .col-md-6:eq(0)');
  });


  function lazyLoad() {
    $('.lazy').each(function() {
      const $img = $(this);
      if ($img.offset().top < $(window).scrollTop() + $(window).height()) {
        $img.attr('src', $img.data('src'));
        $img.removeClass('lazy');
      }
    });
  }

  // Initial lazy load
//   lazyLoad();

  // Lazy load on scroll
  $('#tableWithoutExporting tbody').on('mouseenter', 'tr', function() {
    // Menangkap semua gambar lazy dalam baris yang dihover
    var $lazyImages = $(this).find('.lazy');
    lazyLoad($lazyImages);
  });

  // Lazy load after DataTables redraws the table
  table.on('draw', function() {
    // lazyLoad();
  });
  
</script>