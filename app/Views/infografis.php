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
</head>

<body>

  <!-- Page Wrapper -->
  <div class="page-wrapper">

    <!-- START PRELOADER  -->
    <div id="atf-preloader-wrapper">
      <div id="atf-loader"></div>
    </div>
    <!-- END PRELOADER -->
    <section class="intro">
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
    <!-- =========================================================================================== -->
    <div style="display: none;" id="infografis-kedatangan-kapal">
      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/kapal.png" alt="Kapal"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Kedatangan Kapal</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="datang_total_keseluruhan_kapal">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="datang_total_ln_kapal">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="datang_total_dn_kapal">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/abk.png" alt="ABK"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Kedatangan ABK</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="datang_total_keseluruhan_abk">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="datang_total_ln_abk">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="datang_total_dn_abk">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/user.jpg" alt="Penumpang"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Kedatangan Penumpang</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="datang_total_keseluruhan_penumpang_kapal">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="datang_total_ln_penumpang_kapal">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="datang_total_dn_penumpang_kapal">-</span></div>
          </div>
        </a>
      </div>
    </div>
    <div style="display: none;" id="infografis-keberangkatan-kapal">
      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/kapal.png" alt="Kapal"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Keberangkatan Kapal</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="berangkat_total_keseluruhan_kapal">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="berangkat_total_ln_kapal">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="berangkat_total_dn_kapal">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/abk.png" alt="ABK"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Keberangkatan ABK</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="berangkat_total_keseluruhan_abk">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="berangkat_total_ln_abk">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="berangkat_total_dn_abk">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/user.jpg" alt="Penumpang"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Keberangkatan Penumpang</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="berangkat_total_keseluruhan_penumpang_kapal">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="berangkat_total_ln_penumpang_kapal">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="berangkat_total_dn_penumpang_kapal">-</span></div>
          </div>
        </a>
      </div>
    </div>
    <!-- =========================================================================================== -->
    <div style="display: none;" id="infografis-kedatangan-pesawat">
      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/pesawat.png" alt="Pesawat"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Kedatangan Pesawat</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="datang_total_keseluruhan_pesawat">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="datang_total_ln_pesawat">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="datang_total_dn_pesawat">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/crew.png" alt="Crew"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Kedatangan Crew</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="datang_total_keseluruhan_crew">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="datang_total_ln_crew">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="datang_total_dn_crew">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/user.jpg" alt="Penumpang"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Kedatangan Penumpang</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="datang_total_keseluruhan_penumpang_pesawat">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="datang_total_ln_penumpang_pesawat">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="datang_total_dn_penumpang_pesawat">-</span></div>
          </div>
        </a>
      </div>
    </div>
    <!-- =========================================================================================== -->
    <div style="display: none;" id="infografis-keberangkatan-pesawat">
      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/kapal.png" alt="kapal"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Keberangkatan Pesawat</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="berangkat_total_keseluruhan_pesawat">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="berangkat_total_ln_pesawat">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="berangkat_total_dn_pesawat">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/crew.png" alt="Crew"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Keberangkatan Crew</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="berangkat_total_keseluruhan_crew">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="berangkat_total_ln_crew">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="berangkat_total_dn_crew">-</span></div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/user.jpg" alt="Penumpang"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Keberangkatan Penumpang</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="berangkat_total_keseluruhan_penumpang_pesawat">-</h1>
            </div>
            <div class="col-6 detail-ket">LN : <span id="berangkat_total_ln_penumpang_pesawat">-</span></div>
            <div class="col-6 detail-ket">DN : <span id="berangkat_total_dn_penumpang_pesawat">-</span></div>
          </div>
        </a>
      </div>
    </div>
    <!-- =========================================================================================== -->
    <div style="display: none;" id="infografis-dokumen">
      <div class="timeline timeline-dokumen wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">PHQC</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="phqc">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-dokumen wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">COP</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="cop">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-dokumen wow fadeIn" data-wow-duration="1s" data-wow-delay="0.6s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">SSCEC</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="sscec">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-dokumen wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">SSCC</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="sscc">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-dokumen wow fadeIn" data-wow-duration="1s" data-wow-delay="1s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Sertifikat P3K Kapal</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="p3k">-</h1>
            </div>
          </div>
        </a>
      </div>
    </div>
    <!-- =========================================================================================== -->
    <div style="display: none;" id="infografis-sertifikat">
      <div class="timeline timeline-sertifikat wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/icv.png" alt="icv"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">ICV</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="icv">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-sertifikat wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">SKLT</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="sklt">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-sertifikat wow fadeIn" data-wow-duration="1s" data-wow-delay="0.6s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">SIAOS</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="siaos">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-sertifikat wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title"><small>Surat Izin Angkut Jenazah</small></h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="siaj">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline timeline-sertifikat wow fadeIn" data-wow-duration="1s" data-wow-delay="1s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/buku.png" alt="buku"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title"><small>Pengujian Kesehatan</small></h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="kir">-</h1>
            </div>
          </div>
        </a>
      </div>

      <!-- <div class="timeline timeline-sertifikat wow fadeIn" data-wow-duration="1s" data-wow-delay="1.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/user.jpg" alt="Penumpang"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title"><small><small>Sertifikat Pengawasan Kualitas Air</small></small></h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="spka">-</h1>
            </div>
          </div>
        </a>
      </div> -->
    </div>
    <!-- =========================================================================================== -->
    <div style="display: none;" id="infografis-vaksinasi">
      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/vaksin-mm.png" alt="Vaksin Meningitis"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Vaksinasi Meninghitis</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="vaksin_mm">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/vaksin-yf.png" alt="Vaksin Yellow Fever"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Vaksinasi Yellowfever</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="vaksin_yf">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/vaksin-covid19.png" alt="Vaksin Covid-19"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Vaksinasi Covid-19</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="vaksin_covid">-</h1>
            </div>
          </div>
        </a>
      </div>

      <div class="timeline wow fadeIn" data-wow-duration="1s" data-wow-delay="1.1s" data-wow-offset="0">
        <a href="#" class="timeline-content">
          <div class="timeline-icon">
            <div class="ctn"><img width="50" src="/first-page-new-01/images/vaksin-polio.svg" alt="Vaksin Polio"></div>
          </div>
          <span class="timeline-year"></span>
          <h3 class="title">Vaksinasi Polio</h3>
          <div class="description row">
            <div class="col-12">
              <h1 id="vaksin_polio">-</h1>
            </div>
          </div>
        </a>
      </div>
    </div>
    <!-- =========================================================================================== -->
    <!-- END TIMELINE -->

  </div>
  <!-- PAGE WRAPPER END-->

  <!-- Latest jQuery -->
  <script src="/first-page-new-01/js/jquery-1.12.4.min.js"></script>
  <!-- Latest compiled and minified Bootstrap -->
  <script src="/first-page-new-01/js/bootstrap.js"></script>
  <script src="/first-page-new-01/js/form-contact.js"></script>
  <script src="/first-page-new-01/js/main.js"></script>
  <script src="/first-page-new-01/js/transition.js"></script>
  <script src="/first-page/js/infografis.js"></script>
</body>

</html>