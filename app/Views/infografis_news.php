<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Meta -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Infografis BKK Bandung</title>
  <!-- Favicon -->

  <!-- Latest Bootstrap min CSS -->
  <link rel="stylesheet" href="/first-page-new-01/css/bootstrap.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500&amp;display=swap" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="/first-page-new-01/css/font-awesome.css">
  <!--animate Css-->
  <link rel="stylesheet" href="/first-page-new-01/css/animate.css">
  <!-- Style CSS -->
  <link rel="stylesheet" href="/first-page-new-01/css/style.css">
  <!-- Responsive CSS -->
  <link rel="stylesheet" href="/first-page-new-01/css/responsive.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/first-page-new-01/css/customStyle.css">
  <?php if (session()->get("role") == "Admin"): ?>
    <style>
        #toolbarContainer {
            display: none;
        }
    </style>
  <?php endif; ?>
</head>

<body>
    
  <button class="btn btn-secondary setData" style="position: absolute; top: 20px; right: 20px; z-index: 10; width: 80px; border-radius: 40px;"><i class="fas fa-2x fa-cogs"></i></button>

  <!-- Page Wrapper -->
  <div class="page-wrapper">

    <!-- START PRELOADER  -->
    <div id="atf-preloader-wrapper">
      <div id="atf-loader"></div>
    </div>
    <!-- END PRELOADER -->
    <section class="intro" style="display: none;">
      <div class="intro-pattern-02">
        <img src="/first-page-new-01/images/pattern-02.gif" alt="pattern 2">
      </div>
      <div class="intro-pattern-03">
        <img src="/first-page-new-01/images/pattern-03.gif" alt="pattern 3">
      </div>
      <div class="intro-container">
        <h1 class="intro-text">Selamat datang di website kami!</h1>
        <div class="intro-logo-container">
          <img class="intro-logo" src="/first-page-new-01/images/logo-kesehatan.png" alt="Logo 1">
          <img class="intro-logo" src="/first-page-new-01/images/logo-p2p.png" alt="Logo 2">
          <img class="intro-logo" src="/first-page-new-01/images/logo_kkp.png" alt="Logo 3">
        </div>
    </section>
    <section class="atf-section-padding">
      <div class="container-fluid">
        <div class="row">
          <div class="col-xl-8">
            <div class="background-video">
              <video autoplay loop muted id="background-videos1">
                <source src="/first-page-new-01/videos/video1-1.mp4" type="video/mp4">
              </video>
              <video autoplay loop muted id="background-videos2">
                <source src="/first-page-new-01/videos/video2_1.mp4" type="video/mp4">
              </video>
              <video autoplay loop muted id="background-videos3">
                <source src="/first-page-new-01/videos/video3_1.mp4" type="video/mp4">
              </video>
              <div class="row">
                <div class="col-12">
                  <div class="head-infografis">
                    <div class="container-fluid infografis-heading">
                      <div class="rekapitulasi-title"><img src="/first-page-new-01/images/logo-kesehatan.png" /><img src="/first-page-new-01/images/logo-p2p.png" /><img src="/first-page-new-01/images/logo_kkp.png" style="display: block;" /> <img src="/first-page-new-01/images/logo_kkp.png" style=" width: 0; height: 43px; border-right: solid; border-width: 80%; margin-right: 18px; box-shadow: 1px 1px 0 0 #fff inset, 1px 3px 0 0 #333;">
                        <p class="rekapitulasi-sub-title">Rekapitulasi Infografis</p>
                        <p class="rekapitulasi-keterangan">Kedatangan Kapal</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 mt-6">
                  <div class="container">
                    <div class="main-timeline">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-4">
            <div class="row custom-row">
              <div class="col-12 mb-5">
                <table class="bg-transparent table-bordered border-primary">
                  <thead>
                    <tr>
                      <th colspan="2">TOTAL PEGAWAI</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($jmlPegawaiPerWilker as $data) : ?>
                      <tr>
                        <td><?= $data['unit_kerja']; ?></td>
                        <td><?= $data['total']; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END TIMELINE -->

  </div>
  <!-- PAGE WRAPPER END-->
  <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Data Yang Ditampilkan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-check form-check">
              <input class="form-check-input" type="checkbox" id="check1" value="1" checked>
              <label class="form-check-label" for="check1">Infografis Kapal</label>
            </div>
            <div class="form-check form-check">
              <input class="form-check-input" type="checkbox" id="check2" value="2" checked>
              <label class="form-check-label" for="check2">Infografis Pesawat</label>
            </div>
            <div class="form-check form-check">
              <input class="form-check-input" type="checkbox" id="check3" value="3" checked>
              <label class="form-check-label" for="check3">Infografis Dokumen dan Vaksinasi</label>
            </div>
            <div class="form-check form-check">
              <input class="form-check-input" type="checkbox" id="check4" value="4" checked>
              <label class="form-check-label" for="check4">Tampilkan Rekapitulasi</label>
            </div>
            <div class="form-check form-check">
              <input class="form-check-input" type="checkbox" id="check5" value="5" checked>
              <label class="form-check-label" for="check5">Tampilkan Setiap Wilker</label>
            </div>
            <div class="form-check form-check">
              <input class="form-check-input" type="checkbox" id="check6" value="6" checked>
              <label class="form-check-label" for="check6">Tampilkan Data Bulan Ini dan Sebelumnya</label>
            </div>
            <div class="form-check form-check">
              <input class="form-check-input" type="checkbox" id="check7" value="7" checked>
              <label class="form-check-label" for="check7">Tampilkan Data Hari Ini dan Sebelumnya</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary setData">Close</button>
            <button type="button" class="btn btn-primary setDataValue">Save changes</button>
          </div>
        </div>
      </div>
    </div>

  <!-- Latest jQuery -->
  <script src="/first-page-new-01/js/jquery-1.12.4.min.js"></script>
  <!-- Latest compiled and minified Bootstrap -->
  <script src="/first-page-new-01/js/bootstrap.js"></script>
  <script src="/first-page-new-01/js/form-contact.js"></script>
  <script src="/first-page-new-01/js/main.js"></script>
  <!--<script src="/first-page-new-01/js/transition.js"></script>-->
  
  <script>
    const timmerTransition = 3000;
  
    // Fungsi untuk melakukan AJAX call dengan Promise
    function ajaxCall(endpoint, data) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: endpoint, // Ganti dengan URL yang sesuai
                method: 'POST', // Sesuaikan metode yang digunakan
                data: data, // Data yang ingin dikirim
                success: function(response) {
                    $(".main-timeline").html(response.data);
                    resolve(response); // Selesaikan Promise
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    reject(error);
                }
            });
        });
    }
    
    // Fungsi untuk menambahkan delay
    function timeout(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    
    
    async function call_data_infografis(valueJPararel) {
        for (let i = 0; i < arrayValueDataView.length; i++) {
            if (arrayValueDataView[i]) {
                if(i == 1) {
                    for (let j=0; j < arrayWilker.length; j++) {
                        for (let k = 0; k < arrayValueDataView.length; k++) {
                            if(k != 1) {
                                await ajaxCall('/informasi_infografis/'+ valueJPararel +'/'+ arrayDataView[i], { wilker: arrayWilker[j], data_view: arrayValueDataView[k] });
                                await timeout(timmerTransition);
                            }
                        }
                    }
                } else {
                        await ajaxCall('/informasi_infografis/'+ valueJPararel +'/'+ arrayDataView[i], { /* data yang ingin dikirim */ });
                        await timeout(timmerTransition);
                }
                
                await timeout(timmerTransition);
            }
        }
        await timeout(timmerTransition);
    }
  </script>
  <script>
//   background-videos1
//   background-videos2
//   background-videos2
    
    let arrayInfografis = [
        [
            "dashboard_kedatangan_kapal",
            "dashboard_keberangkatan_kapal",
        ],
        [
            "dashboard_kedatangan_pesawat",
            "dashboard_keberangkatan_pesawat",
        ],
        [
            "dashboard_dokumen",
            "dashboard_sertifikat",
            "dashboard_vaksinasi",
        ]
    ];
    let arrayWilker = [
        <?php foreach($unit_kerja as $data): ?>
        "<?= $data['unit_kerja']; ?>",
        <?php endforeach ?>
    ];
    let arrayValueInfografis = [
        false, // "kapal",
        false, // "pesawat",
        false, // "dokumen_dan_vaksinasi",
    ];
    
    let arrayDataView = [
        "rekapitulasi",
        "per_wilker",
        "bulan_ini_dan_sebelumnya",
        "hari_kemarin",
    ];
    let arrayValueDataView = [
        false, // "rekapitulasi",
        false, // "per_wilker",
        false, // "bulan_ini_dan_sebelumnya",
        false, // "hari_kemarin",
    ];
    
    $(document).ready(function() {
      $('#check1, #check2, #check3').change(function() {
        // Check if at least one checkbox is checked
        if (!$('#check1').is(':checked') && !$('#check2').is(':checked') && !$('#check3').is(':checked')) {
          // If none are checked, re-check the changed checkbox
          $(this).prop('checked', true);
        }
    
        // Log the ID and value of the currently checked checkboxes
        if ($('#check1').is(':checked')) {
          console.log("Checked: #check1 - Value: " + $('#check1').val());
        }
        if ($('#check2').is(':checked')) {
          console.log("Checked: #check2 - Value: " + $('#check2').val());
        }
        if ($('#check3').is(':checked')) {
          console.log("Checked: #check3 - Value: " + $('#check3').val());
        }
      });
      $('#check4, #check5, #check6, #check7').change(function() {
        // Check if at least one checkbox is checked
        if (!$('#check4').is(':checked') && !$('#check5').is(':checked') && !$('#check6').is(':checked') && !$('#check7').is(':checked')) {
          // If none are checked, re-check the changed checkbox
          $(this).prop('checked', true);
        }
    
        // Log the ID and value of the currently checked checkboxes
        if ($('#check4').is(':checked')) {
          console.log("Checked: #check4 - Value: " + $('#check4').val());
        }
        if ($('#check5').is(':checked')) {
          console.log("Checked: #check5 - Value: " + $('#check5').val());
        }
        if ($('#check6').is(':checked')) {
          console.log("Checked: #check6 - Value: " + $('#check6').val());
        }
        if ($('#check7').is(':checked')) {
          console.log("Checked: #check7 - Value: " + $('#check7').val());
        }
      });
        
      // JavaScript/JQuery untuk mengubah kalimat dengan fade-out dan fade-in yang lebih smooth
      setDataChecked();
      loopInfografis();
      $(".atf-section-padding").hide()
      var intro
      setTimeout(function() {
        intro = $('.intro-container').html();
        $('.intro-container').html('');
      }, 3000);
      setTimeout(function() {
        $('.intro-container').html(intro)
        $('.intro-text').text("Kami menyajikan informasi terbaru seputar data kapal, pesawat, dokumen dan vaksinasi dalam bentuk infografis yang mudah dipahami.").fadeIn();
      }, 3100);
      setTimeout(function() {
        $('.intro').fadeOut(2000, function() {
          $(this).remove();
          $(".atf-section-padding").fadeIn(1000);
        });
      }, 10000);
    });
    
    $(".setData").on("click", function() {
        $("#exampleModal").modal("toggle")
    })
  </script>
  <script>
      $(".setDataValue").click(function() {
        setDataChecked()
      });
      function setDataChecked() {
        $(".form-check-input").each(function() {
            const isChecked = $(this).is(":checked");
            const checkboxValue = $(this).val();
    
            console.log(isChecked);
            if (checkboxValue == 1) {
                arrayValueInfografis[0] = isChecked;
            } else if (checkboxValue == 2) {
                arrayValueInfografis[1] = isChecked;
            } else if (checkboxValue == 3) {
                arrayValueInfografis[2] = isChecked;
            } else if (checkboxValue == 4) {
                arrayValueDataView[0] = isChecked;
            } else if (checkboxValue == 5) {
                arrayValueDataView[1] = isChecked;
            } else if (checkboxValue == 6) {
                arrayValueDataView[2] = isChecked;
            } else if (checkboxValue == 7) {
                arrayValueDataView[3] = isChecked;
            }
        });
      }
  </script>
  <script>
    // Fungsi loopInfografis dengan AJAX
    async function loopInfografis() {
        let i = 0; // Inisialisasi i di luar loop
        while (true) { // Loop tak terbatas
            // Jika i mencapai akhir array, reset ke 0
            if (i >= arrayValueInfografis.length) {
                console.log("Resetting index to 0.");
                i = 0; // Reset i ke 0
            }
            if(arrayValueInfografis[i]) {
                if (i == 0) {
                    $("#background-videos1").show();
                    $("#background-videos2").hide();
                    $("#background-videos3").hide();
                } else if(i == 1) {
                    $("#background-videos1").hide();
                    $("#background-videos2").show();
                    $("#background-videos3").hide();
                } else if(i == 2) {
                    $("#background-videos1").hide();
                    $("#background-videos2").hide();
                    $("#background-videos3").show();
                }
                
        
                // Jika nilai saat ini adalah false, pindah ke indeks berikutnya
                if (!arrayValueInfografis[i]) {
                    console.log(`Skipping index ${i} because it's false.`);
                    i++; // Pindah ke indeks berikutnya
                    continue; // Kembali ke awal loop
                }
        
                for (let j = 0; j < arrayInfografis[i].length; j++) {
                    await call_data_infografis(arrayInfografis[i][j]); // Panggil fungsi AJAX dan tunggu selesai
                }
            }
            i++;
        }
    }
  </script>
</body>

</html>