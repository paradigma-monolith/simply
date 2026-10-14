<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Sistem Informasi BKK Bandung</title>
  <!-- Font Awesome icons (free version)-->
  <script src="/first-page/js/use.fontawesome.v6.1.0.js"></script>
  <!-- Google fonts-->
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&amp;display=swap" rel="stylesheet" />
  <!-- Core theme CSS (includes Bootstrap)-->
  <link href="/first-page/css/styles.css" rel="stylesheet" />
  <?php if ((session()->getFlashdata('pesan')) || (session()->getFlashdata('info')) || (session()->getFlashdata('error'))) : ?>
    <style>
      #kapal {
        display: none;
      }

      #login {
        display: flex;
      }
    </style>

  <?php endif; ?>
  <style>
    .swal-container-zindex {
      z-index: 99999 !important;
    }
  </style>
</head>

<body>
  <!-- Pre-Loader -->
  <div id="loader-wrapper">
    <div id="loader"></div>

    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>

  </div>

  <div id="full-screen" style="z-index: 5;padding:10px; top:0; left:0; position:absolute; display:flex; cursor:pointer; height:20px; color:white; opacity:.3;"><i class="fas fa-expand-arrows-alt"></i></div>
  <div class="header-logo">
    <img class="" src="/dist/img/logo-bkk-no-margin.png" height="65">
    <img class="" src="/dist/img/LOGO_KARANTINA.png" height="65">
  </div>
  <!-- Background Video-->
  <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
    <source src="/first-page/assets/mp4/bg.mp4" type="video/mp4" />
  </video>
  <!-- Masthead-->
  <div class="masthead" id="kapal">
    <div class="masthead-content text-white">
      <div class="container-fluid px-4 px-lg-0">
        <h1 class="fst-italic lh-1 mb-4"><small>Infografis Data Kapal</small></h1>

        <div id="carouselKapal" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators" style="margin-bottom: -20px;">
            <button type="button" data-bs-target="#carouselKapal" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselKapal" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselKapal" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="row">
                <div class="col-6 text-center">
                  <h3 id="datang_total_keseluruhan_kapal">-</h3>
                  <p class="mb-5">Kedatangan<br /> Kapal</p>
                </div>
                <div class="col-6 text-center">
                  <h3 id="berangkat_total_keseluruhan_kapal">-</h3>
                  <p class="mb-5">Keberangkatan<br /> Kapal</p>
                </div>
                <div class="col-4 text-center">
                  <p id="datang_total_ln_kapal">-</p>
                  <hr>
                  <p id="datang_total_dn_kapal">-</p>
                </div>
                <div class="col text-center">
                  <p>Luar Negeri</p>
                  <hr style="opacity: 0;">
                  <p>Dalam Negeri</p>
                </div>
                <div class="col-4 text-center">
                  <p id="berangkat_total_ln_kapal">-</p>
                  <hr>
                  <p id="berangkat_total_dn_kapal">-</p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row">
                <div class="col-6 text-center">
                  <h3 id="datang_total_keseluruhan_abk">-</h3>
                  <p class="mb-5">Kedatangan<br /> ABK</p>
                </div>
                <div class="col-6 text-center">
                  <h3 id="berangkat_total_keseluruhan_abk">-</h3>
                  <p class="mb-5">Keberangkatan<br /> ABK</p>
                </div>
                <div class="col-4 text-center">
                  <p id="datang_total_ln_abk">-</p>
                  <hr>
                  <p id="datang_total_dn_abk">-</p>
                </div>
                <div class="col text-center">
                  <p>Luar Negeri</p>
                  <hr style="opacity: 0;">
                  <p>Dalam Negeri</p>
                </div>
                <div class="col-4 text-center">
                  <p id="berangkat_total_ln_abk">-</p>
                  <hr>
                  <p id="berangkat_total_dn_abk">-</p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row">
                <div class="col-6 text-center">
                  <h3 id="datang_total_keseluruhan_penumpang_kapal">-</h3>
                  <p class="mb-5">Kedatangan<br /> Penumpang</p>
                </div>
                <div class="col-6 text-center">
                  <h3 id="berangkat_total_keseluruhan_penumpang_kapal">-</h3>
                  <p class="mb-5">Keberangkatan<br /> Penumpang</p>
                </div>
                <div class="col-4 text-center">
                  <p id="datang_total_ln_penumpang_kapal">-</p>
                  <hr>
                  <p id="datang_total_dn_penumpang_kapal">-</p>
                </div>
                <div class="col text-center">
                  <p>Luar Negeri</p>
                  <hr style="opacity: 0;">
                  <p>Dalam Negeri</p>
                </div>
                <div class="col-4 text-center">
                  <p id="berangkat_total_ln_penumpang_kapal">-</p>
                  <hr>
                  <p id="berangkat_total_dn_penumpang_kapal">-</p>
                </div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" style="margin-left: -55px;" type="button" data-bs-target="#carouselKapal" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" style="margin-right: -55px;" type="button" data-bs-target="#carouselKapal" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="masthead" id="pesawat">
    <div class="masthead-content text-white">
      <div class="container-fluid px-4 px-lg-0">
        <h1 class="fst-italic lh-1 mb-4"><small>Infografis Data Pesawat</small></h1>

        <div id="carouselPesawat" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators" style="margin-bottom: -20px;">
            <button type="button" data-bs-target="#carouselPesawat" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselPesawat" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselPesawat" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="row">
                <div class="col-6 text-center">
                  <h3 id="datang_total_keseluruhan_pesawat">-</h3>
                  <p class="mb-5">Kedatangan<br /> Pesawat</p>
                </div>
                <div class="col-6 text-center">
                  <h3 id="berangkat_total_keseluruhan_pesawat">-</h3>
                  <p class="mb-5">Keberangkatan<br /> Pesawat</p>
                </div>
                <div class="col-4 text-center">
                  <p id="datang_total_ln_pesawat">-</p>
                  <hr>
                  <p id="datang_total_dn_pesawat">-</p>
                </div>
                <div class="col text-center">
                  <p>Luar Negeri</p>
                  <hr style="opacity: 0;">
                  <p>Dalam Negeri</p>
                </div>
                <div class="col-4 text-center">
                  <p id="berangkat_total_ln_pesawat">-</p>
                  <hr>
                  <p id="berangkat_total_dn_pesawat">-</p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row">
                <div class="col-6 text-center">
                  <h3 id="datang_total_keseluruhan_crew">-</h3>
                  <p class="mb-5">Kedatangan<br /> Crew</p>
                </div>
                <div class="col-6 text-center">
                  <h3 id="berangkat_total_keseluruhan_crew">-</h3>
                  <p class="mb-5">Keberangkatan<br /> Crew</p>
                </div>
                <div class="col-4 text-center">
                  <p id="datang_total_ln_crew">-</p>
                  <hr>
                  <p id="datang_total_dn_crew">-</p>
                </div>
                <div class="col text-center">
                  <p>Luar Negeri</p>
                  <hr style="opacity: 0;">
                  <p>Dalam Negeri</p>
                </div>
                <div class="col-4 text-center">
                  <p id="berangkat_total_ln_crew">-</p>
                  <hr>
                  <p id="berangkat_total_dn_crew">-</p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row">
                <div class="col-6 text-center">
                  <h3 id="datang_total_keseluruhan_penumpang_pesawat">-</h3>
                  <p class="mb-5">Kedatangan<br /> Penumpang</p>
                </div>
                <div class="col-6 text-center">
                  <h3 id="berangkat_total_keseluruhan_penumpang_pesawat">-</h3>
                  <p class="mb-5">Keberangkatan<br /> Penumpang</p>
                </div>
                <div class="col-4 text-center">
                  <p id="datang_total_ln_penumpang_pesawat">-</p>
                  <hr>
                  <p id="datang_total_dn_penumpang_pesawat">-</p>
                </div>
                <div class="col text-center">
                  <p>Luar Negeri</p>
                  <hr style="opacity: 0;">
                  <p>Dalam Negeri</p>
                </div>
                <div class="col-4 text-center">
                  <p id="berangkat_total_ln_penumpang_pesawat">-</p>
                  <hr>
                  <p id="berangkat_total_dn_penumpang_pesawat">-</p>
                </div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" style="margin-left: -55px;" type="button" data-bs-target="#carouselPesawat" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" style="margin-right: -55px;" type="button" data-bs-target="#carouselPesawat" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="masthead" id="dokumen">
    <div class="masthead-content text-white">
      <div class="container-fluid px-4 px-lg-0">
        <h1 class="fst-italic lh-1 mb-4"><small>Infografis Data Dokumen</small></h1>

        <div id="carouselDokumen" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators" style="margin-bottom: -20px;">
            <button type="button" data-bs-target="#carouselDokumen" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselDokumen" data-bs-slide-to="1" aria-label="Slide 2"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="row">
                <div class="col-4 text-center">
                  <p id="phqc">-</p>
                  <p class="mb-2">PHQC</p>
                </div>
                <div class="col-4 text-center">
                  <p id="cop">-</p>
                  <p class="mb-2">COP</p>
                </div>
                <div class="col-4 text-center">
                  <p id="sscec">-</p>
                  <p class="mb-2">SSCEC</p>
                </div>
                <div class="col-6 text-center">
                  <p id="sscc">-</p>
                  <p class="mb-5">SSCC</p>
                </div>
                <div class="col-6 text-center">
                  <p id="p3k">-</p>
                  <p class="mb-5">P3K</p>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="row">
                <div class="col-4 text-center">
                  <p id="icv">-</p>
                  <p class="mb-2">ICV</p>
                </div>
                <div class="col-4 text-center">
                  <p id="sklt">-</p>
                  <p class="mb-2">SKLT</p>
                </div>
                <div class="col-4 text-center">
                  <p id="siaos">-</p>
                  <p class="mb-2">SIAOS</p>
                </div>
                <div class="col-6 text-center">
                  <p id="siaj">-</p>
                  <p class="">Surat Izin Angkut Jenazah</p>
                </div>
                <div class="col-6 text-center">
                  <p id="kir">-</p>
                  <p class="">Pengujian Kesehatan (KIR)</p>
                </div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" style="margin-left: -55px;" type="button" data-bs-target="#carouselDokumen" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" style="margin-right: -55px;" type="button" data-bs-target="#carouselDokumen" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="masthead" id="vaksin">
    <div class="masthead-content text-white">
      <div class="container-fluid px-4 px-lg-0">
        <h1 class="fst-italic lh-1 mb-4"><small>Infografis Data Vaksinasi</small></h1>

        <div id="carouselVaksin" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators" style="margin-bottom: -20px;">
            <button type="button" data-bs-target="#carouselVaksin" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="row">
                <div class="col-6 text-center">
                  <p id="vaksin_mm">-</p>
                  <p class="mb-2">Vaksin MM</p>
                </div>
                <div class="col-6 text-center">
                  <p id="vaksin_yf">-</p>
                  <p class="mb-2">Vaksin YF</p>
                </div>
                <div class="col"></div>
                <div class="col-6 text-center">
                  <p id="vaksin_covid">-</p>
                  <p class="mb-5">Vaksin Covid</p>
                </div>
                <div class="col"></div>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" style="margin-left: -55px;" type="button" data-bs-target="#carouselVaksin" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" style="margin-right: -55px;" type="button" data-bs-target="#carouselVaksin" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="masthead" id="login">
    <div class="masthead-content text-white">
      <div class="container-fluid px-4 px-lg-0">
        <h1 class="fst-italic lh-1 mb-4">Sistem Informasi <br /><small>BKK Kelas I Bandung</small></h1>
        <div class="row">
          <div class="col-12">
            <?php if (session()->getFlashdata('pesan')) : ?>
              <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('pesan'); ?>
              </div>
            <?php elseif (session()->getFlashdata('error')) : ?>
              <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error'); ?>
              </div>
            <?php elseif (session()->getFlashdata('info')) : ?>
              <div class="alert alert-info" role="alert">
                <?= session()->getFlashdata('info'); ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <form action="/auth" method="post">
          <input type="hidden" name="login" value="login1">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
            <label for="username">Username</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            <label for="password">Password</label>
          </div>
          <div class="pt-2 color-white text-end">
            <button type="submit" class="btn btn-lg btn-outline">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="social-icons">
    <div class="d-flex flex-row flex-lg-column justify-content-center align-items-center h-100 mt-3 mt-lg-0">
      <a class="btn btn-cyan m-3" onclick="changeDisplay('#kapal')" href="#!"><i class="fa-solid fa-ship"></i></a>
      <a class="btn btn-cyan m-3" onclick="changeDisplay('#pesawat')" href="#!"><i class="fa-solid fa-plane"></i></a>
      <a class="btn btn-cyan m-3" onclick="changeDisplay('#dokumen')" href="#!"><i class="fa-solid fa-book"></i></a>
      <a class="btn btn-cyan m-3" onclick="changeDisplay('#vaksin')" href="#!"><i class="fas fa-syringe"></i></a>
      <!--<a class="btn btn-light m-3" onclick="changeDisplay('#login')" href="#!"><i class="fa-solid fa-right-to-bracket"></i></a>-->
      <a class="btn btn-light m-3" href="/auth/login"><i class="fa-solid fa-right-to-bracket"></i></a>
    </div>
  </div>
  <!-- Bootstrap core JS-->
  <script src="/first-page/js/jquery.3.6.3.ajax.min.js"></script>
  <script src="/first-page/js/cdn.jsdelivr.bootstrap@5.1.3.bundle.min.js"></script>
  <script src="/first-page/js/infografis.js"></script>
  <!-- Core theme JS-->
  <script src="/first-page/js/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      setTimeout(function() {
        const swalInstance = Swal.fire({
          title: 'Tampilan Infografis Baru',
          icon: 'info',
          html: '<form action="/" method="post"><button type="submit" class="btn btn-secondary">Kunjungi Halaman</button></form>',
          showCloseButton: true,
          showCancelButton: false,
          showConfirmButton: false,
          focusCloseButton: true,
          customClass: {
            container: 'swal-container-zindex',
          },
        });
        setTimeout(function() {
          swalInstance.close();
        }, 10000);
      }, 5000);
      // Fakes the loading setting a timeout
      setTimeout(function() {
        $('body').addClass('loaded');
      }, 1500);

    });
  </script>

</body>

</html>