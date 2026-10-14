<?php if (in_array(session()->get('username'), ['919890531201701101', '919910706201301201', '919920801201501101'])) :  ?>
  <meta http-equiv="refresh" content="0; URL=/auth/logout" />
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="expires" content="0">
  <title><?= strip_tags($title); ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="/plugins/ekko-lightbox/ekko-lightbox.css">
  <!-- Ion Slider -->
  <link rel="stylesheet" href="/plugins/ion-rangeslider/css/ion.rangeSlider.min.css">
  <!-- jsGrid -->
  <link rel="stylesheet" href="/plugins/jsgrid/jsgrid.min.css">
  <link rel="stylesheet" href="/plugins/jsgrid/jsgrid-theme.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="/plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="/css/bootstrap-float-label.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
  <!-- Animate.css for Birthday Animation -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="/css/customLayout.css">
  <link rel="stylesheet" href="/css/customLayout-v2.css">
  
  <!-- Feedback Highlight Styles -->
  <style>
    
    .feedback-badge {
      background: #ff6b6b;
      color: white;
      font-size: 9px;
      font-weight: bold;
      padding: 2px 6px;
      border-radius: 10px;
      margin-left: 5px;
      animation: badgeBounce 1.5s ease-in-out infinite;
      display: inline-block;
    }
    
    
    @keyframes badgeBounce {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-3px);
      }
    }
    
    @keyframes iconRotate {
      0%, 100% {
        transform: rotate(0deg);
      }
      25% {
        transform: rotate(5deg);
      }
      75% {
        transform: rotate(-5deg);
      }
    }
    
    /* Floating Feedback Button Highlight */
    .feedback-float-highlight {
      animation: floatPulse 2s ease-in-out infinite;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
    }
    
    @keyframes floatPulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
      }
      50% {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.8);
      }
    }
    
    /* Welcome Message Highlight */
    .feedback-welcome-highlight {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
      animation: welcomeSlide 0.8s ease-out;
    }
    
    @keyframes welcomeSlide {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    
    .feedback-badge-intense {
      background: #ff4757 !important;
      animation: badgeBounce 0.8s ease-in-out infinite !important;
      font-size: 10px !important;
      padding: 3px 8px !important;
    }
    
    @keyframes intenseGlow {
      0%, 100% {
        box-shadow: 0 6px 25px rgba(255, 107, 107, 0.6);
      }
      50% {
        box-shadow: 0 8px 35px rgba(255, 107, 107, 0.9);
      }
    }
    
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .feedback-badge {
        font-size: 8px;
        padding: 1px 4px;
      }
      
      .feedback-badge-intense {
        font-size: 9px !important;
        padding: 2px 6px !important;
      }
    }
    
    /* Luxury Birthday Popup Animation Customization */
    .birthday-popup {
      background: linear-gradient(135deg, #ffffff, #fdfbf7) !important;
      border: 3px solid #d4af37 !important;
      border-radius: 20px !important;
      box-shadow: 0 10px 50px rgba(212, 175, 55, 0.4) !important;
      font-family: 'Poppins', sans-serif;
    }
    
    .birthday-popup .swal2-title {
      color: #b8860b !important;
      font-size: 2.2em !important;
      font-weight: 800 !important;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.15);
    }
    
    .birthday-popup .swal2-html-container {
      font-size: 1.15em !important;
      color: #4a4a4a !important;
      line-height: 1.8;
      font-style: italic;
    }
    
    .birthday-popup .swal2-confirm {
      background: linear-gradient(45deg, #d4af37, #ffd700, #d4af37) !important;
      color: #fff !important;
      border: none !important;
      border-radius: 30px !important;
      padding: 12px 40px !important;
      font-size: 1.1em !important;
      font-weight: bold !important;
      box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4) !important;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease !important;
    }
    
    .birthday-popup .swal2-confirm:hover {
      transform: translateY(-2px) !important;
      box-shadow: 0 6px 20px rgba(212, 175, 55, 0.6) !important;
    }

    /* Falling Balloons Animation */
    .balloon-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 999999; /* di atas SweetAlert */
      overflow: hidden;
    }
    .balloon {
      position: absolute;
      width: 45px;
      height: 60px;
      border-radius: 50% 50% 50% 50% / 40% 40% 60% 60%;
      box-shadow: inset -5px -5px 15px rgba(0,0,0,0.2);
      animation: fallDown linear forwards;
      opacity: 0;
    }
    .balloon::before {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-left: 6px solid transparent;
      border-right: 6px solid transparent;
      border-bottom: 12px solid inherit;
      filter: brightness(0.8);
    }
    .balloon::after {
      content: '';
      position: absolute;
      bottom: -50px;
      left: 50%;
      width: 1px;
      height: 50px;
      background: rgba(0,0,0,0.3);
    }
    
    @keyframes fallDown {
      0% { transform: translateY(-120px) rotate(0deg); opacity: 0; }
      10% { opacity: 1; }
      80% { opacity: 1; }
      100% { transform: translateY(110vh) rotate(20deg); opacity: 0; }
    }
  </style>
  <!--<?php if(in_array($activeMenu, ['sinadiVerifikator', 'notaDinas', 'sinadiPengaturan', 'sinadiSaya', 'formulirPerjalananDinas', 'dinasDalamKota', 'dinasLuarKota'])): ?>-->
  <!--  <link rel="stylesheet" href="/css/customLayout-v3-pink.css">-->
  <!--  <style>-->
  <!--    .navbar-white {-->
  <!--      background-color: #fff1fb !important;-->
  <!--    }-->
  <!--    .content-wrapper {-->
  <!--      background-color: #f8c9f2 !important;-->
  <!--    }-->
  <!--  </style>-->
  <!--<?php endif; ?>-->
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">
  <!-- Magnific Popup CSS -->
  <link href="https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/magnific-popup.min.css" rel="stylesheet">
  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
  <!-- FullCalendar CSS untuk halaman agenda -->
  <?= in_array($activeMenu, ['agenda']) ? '<link rel="stylesheet" href="/plugins/fullcalendar/main.min.css" />' : ""; ?>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/css/custom2.css">
  
  <?php if (session()->get("role") == "Admin"): ?>
    <style>
        #toolbarContainer {
            display: none;
        }
    </style>
  <?php endif; ?>
  
  <!-- jQuery -->
  <script src="/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
  
  <!-- Feedback Highlight Control Script -->
  <script>
    $(document).ready(function() {
      // Check if user has seen feedback feature before
      var hasSeenFeedback = localStorage.getItem('hasSeenFeedback');
      
      if (!hasSeenFeedback) {
        // First time user - show all highlights
        showFeedbackHighlights();
        
        // Mark as seen after 10 seconds
        setTimeout(function() {
          localStorage.setItem('hasSeenFeedback', 'true');
          // Reduce highlight intensity but keep some visibility
          reduceFeedbackHighlights();
        }, 10000);
      } else {
        // Returning user - show subtle highlights
        showSubtleFeedbackHighlights();
      }
      
      // Add click tracking for feedback features
      $('.feedback-float-highlight').on('click', function() {
        // Track that user interacted with feedback
        localStorage.setItem('feedbackInteracted', 'true');
        // Remove highlights after interaction
        removeFeedbackHighlights();
      });
    });
    
    function showFeedbackHighlights() {
      // Intense highlighting for first-time users
      $('.feedback-float-highlight').addClass('feedback-intense');
      
      // Add pulsing effect
      $('.feedback-badge').addClass('feedback-badge-intense');
    }
    
    function showSubtleFeedbackHighlights() {
      // Subtle highlighting for returning users
      $('.feedback-float-highlight').addClass('feedback-subtle');
    }
    
    function reduceFeedbackHighlights() {
      // Reduce intensity but keep visible
      $('.feedback-float-highlight').removeClass('feedback-intense').addClass('feedback-subtle');
      $('.feedback-badge').removeClass('feedback-badge-intense');
    }
    
    function removeFeedbackHighlights() {
      // Remove all special highlighting
      $('.feedback-float-highlight').removeClass('feedback-intense feedback-subtle');
      $('.feedback-badge').removeClass('feedback-badge-intense');
    }
  </script>
  <script src='/calendar/dist/index.global.js'></script>
  <!-- Leaflet -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js"></script>
  
  <script>
    // Record the start time
    let startTime;

    // Function to log the load time
    function logLoadTime() {
        const endTime = performance.now();
        const loadTime = endTime - startTime;
        console.log(`Halaman telah dimuat dalam ${loadTime.toFixed(2)} ms`);
    }

    // When the page starts loading
    window.addEventListener('load', () => {
        startTime = performance.now();
        // Call the logLoadTime function after the page has fully loaded
        logLoadTime();
    });
  </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed text-sm">
  <div id="overlay"></div>
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="/dist/img/logo.png" alt="AdminLTELogo" height="140" width="200">
    </div>

    <?= $this->include('layouts/modal'); ?>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <!-- <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
          <div class="navbar-search-block">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                  <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li> -->

        <!-- Messages Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments"></i>
            <span class="badge badge-danger navbar-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
              Message Start
              <div class="media">
                <img src="/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Brad Diesel
                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">Call me whenever you can...</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              Message End
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              Message Start
              <div class="media">
                <img src="/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    John Pierce
                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">I got your message bro</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              Message End
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              Message Start
              <div class="media">
                <img src="/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Nora Silvester
                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">The subject goes here</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              Message End
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
          </div>
        </li> -->
        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li> -->
        <?php if(session()->get('role') == 'Admin'): ?>
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-sliders-h"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">Pengaturan Administrator</span>
            <div class="dropdown-divider"></div>
            <a href="javascript:void(0)" onclick="toggleVaksinasi()" class="dropdown-item">
              <i class="fas fa-stethoscope mr-2"></i>Pengalihan Vaksinasi di SISOVI
              <span id="toggle-on" class="float-right text-success text-sm" style="display: none;"><i class="fas fa-toggle-on"></i></span>
              <span id="toggle-off" class="float-right text-muted text-sm"><i class="fas fa-toggle-off"></i></span>
            </a>
            <div class="dropdown-divider"></div>
          </div>
        </li>
        <?php endif; ?>
        <li class="nav-item dropdown">
          <a class="nav-link" target="_blank" href="/manualBooks">
            <i class="fas fa-book"></i>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-danger navbar-badge notifikasi-pesan-sistem-number"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notifikasi-pesan-sistem">
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="/home/agenda">
            <i class="fas fa-calendar"></i>
          </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-ellipsis-h"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="/home/profil" class="dropdown-item">
                    <i class="fas fa-user-circle mr2"></i> Akun saya
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" disabled class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> Pesan Baru
                    <span class="float-right text-muted text-sm"><small>Perkembangan</small></span>
                </a>
                <div class="dropdown-divider"></div>
                <div class="dropdown">
            		<button class="btn dropdown-toggle" type="button" id="newDrop_Linimasa" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                		<i class="fas fa-history mr2"></i> Linimasa
            		</button>
            		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="newDrop_Linimasa">
                		<a class="dropdown-item" href="/home/history/login">
                    		<i class="fas fa-sign-in-alt"></i> Login
        		        </a>
                		<a class="dropdown-item" href="/home/history/infografis">
                    		<i class="fas fa-file-alt"></i> Infografis Kapal/Pesawat/Vaksin
                		</a>
                		<a class="dropdown-item" href="#">
                    		<i class="fas fa-laptop-house"></i> Sicute
                    		<span class="float-right text-muted text-sm"><small>Perkembangan</small></span>
                		</a>
                		<a class="dropdown-item" href="#">
                    		<i class="fas fa-copy"></i> Sipepes
                    		<span class="float-right text-muted text-sm"><small>Perkembangan</small></span>
                		</a>
                		<a class="dropdown-item" href="#">
                    		<i class="fas fa-book"></i> Sipedit
                    		<span class="float-right text-muted text-sm"><small>Perkembangan</small></span>
                		</a>
            		</div>
        		</div>
            </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item" onclick="positionRightMessage()">
          <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/admin" class="brand-link">
        <img src="/dist/img/LOGO_KARANTINA.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Balai Karkes Bandung</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="/dist/img/avatar/<?= session()->get('picture'); ?>" class="profile-picture img-circle elevation-2" alt="User Image">
          </div>
          <div class="info" style="white-space: normal; width: 100%; overflow: hidden; padding-right: 10px;">
            <a href="#" class="d-block font-weight-bold" style="white-space: normal; color: #ffffff; letter-spacing: 0.3px; margin-bottom: 3px;"><?= session()->get('nama'); ?></a>
            <?php
            $db = \Config\Database::connect();
            $pegawaiInfo = $db->table('data_pegawai')
              ->select('jabatan.jabatan, golongan.golongan, tim_kerja.nama_tim_kerja')
              ->join('jabatan', 'jabatan.id_jabatan = data_pegawai.id_jabatan', 'left')
              ->join('golongan', 'golongan.id_golongan = data_pegawai.id_golongan', 'left')
              ->join('tim_kerja', 'tim_kerja.id_tim_kerja = data_pegawai.id_tim_kerja', 'left')
              ->where('data_pegawai.id_user', session()->get('id_user'))
              ->get()->getRowArray();
              
            if ($pegawaiInfo):
            ?>
            <div style="color: #ffffff; line-height: 1.3;">
                <?php if(!empty($pegawaiInfo['jabatan'])): ?>
                    <div style="font-size: 12px; opacity: 0.95; margin-bottom: 3px;"><?= esc($pegawaiInfo['jabatan']) ?></div>
                <?php endif; ?>
                
                <?php if(!empty($pegawaiInfo['golongan']) || !empty($pegawaiInfo['nama_tim_kerja'])): ?>
                    <div style="font-size: 11px; opacity: 0.75;">
                        <?php 
                        $details = [];
                        if(!empty($pegawaiInfo['golongan'])) $details[] = esc($pegawaiInfo['golongan']);
                        if(!empty($pegawaiInfo['nama_tim_kerja'])) $details[] = esc($pegawaiInfo['nama_tim_kerja']);
                        echo implode(' &bull; ', $details);
                        ?>
                    </div>
                <?php endif; ?>
                <div style="margin-top: 5px;">
                    <a href="/home/profil" class="btn btn-xs btn-outline-light"><i class="fas fa-edit"></i> Edit Profil</a>
                </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <?= $this->include('layouts/sidebar'); ?>
        <!-- /.sidebar-menu -->

      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0"><?= $title; ?></h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <section class="content">
        <div class="container-fluid">
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
        </div>
      </section>

      <?= $this->renderSection('content'); ?>

    </div>
    <!-- /.content-wrapper -->
    
    <!-- Include Floating Feedback Button -->
    <?= $this->include('home/floating_feedback_button'); ?>
    <footer class="main-footer">
      <strong>Copyright &copy; <?= date('Y'); ?></strong>
      IT Support Balai Kekarantinaan Kesehatan Kelas I Bandung

      <span class="float-right time_microtime"></span>
      <?php if (isset($start_time_microtime)): ?>
        <script>
          window.onload = function() {
            const clientEndTime = new Date().getTime() / 1000;
            const serverStartTime = <?= json_encode($start_time_microtime); ?>;

            const totalTime = clientEndTime - serverStartTime;
            document.querySelector('.time_microtime').textContent = `Pemuatan halaman: ${totalTime.toFixed(4)} detik.`;
          };
        </script>
      <?php endif; ?>

    </footer>

    <!-- Control Sidebar -->
    <aside id="side-right-user" class="control-sidebar control-sidebar-light" style="overflow-y: scroll; width: 250px;">
      <!-- Control sidebar content goes here -->
    </aside>
    <aside class="positionRightMessage position-fixed" style="bottom: 0; right: 0; width: 550px; z-index: 1;"></aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="/plugins/toastr/toastr.min.js"></script>
  <!-- Ekko Lightbox -->
  <script src="/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
  <!-- Filterizr-->
  <script src="/plugins/filterizr/jquery.filterizr.min.js"></script>
  <!-- BS-Stepper -->
  <script src="/plugins/bs-stepper/js/bs-stepper.min.js"></script>
  <!-- jsGrid -->
  <script src="/plugins/jsgrid/demos/db.js"></script>
  <script src="/plugins/jsgrid/jsgrid.min.js"></script>
  <!-- bs-custom-file-input -->
  <script src="/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <!-- Select2 -->
  <script src="/plugins/select2/js/select2.full.min.js"></script>
  <!-- jquery-validation -->
  <script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="/plugins/jquery-validation/additional-methods.min.js"></script>
  <!-- ChartJS -->
  <script src="/plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="/plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <!-- <script src="/plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="/plugins/jqvmap/maps/jquery.vmap.usa.js"></script> -->
  <!-- jQuery Knob Chart -->
  <script src="/plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="/plugins/moment/moment.min.js"></script>
  <script src="/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Select2 -->
  <script src="/plugins/select2/js/select2.full.min.js"></script>
  <!-- Summernote -->
  <script src="/plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- DataTables  & Plugins -->
  <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="/plugins/jszip/jszip.min.js"></script>
  <script src="/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- Magnific Popup JS -->
  <script src="https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/jquery.magnific-popup.min.js"></script>
  <!-- AdminLTE App -->
  <!-- <script src="/dist/js/adminlte.js"></script> -->
  <script src="/dist/js/adminlte.min.js"></script>
  <!-- Ion Slider -->
  <script src="/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="/dist/js/demo.js"></script> -->
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="/dist/js/pages/dashboard.js"></script> -->
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <!-- <script src="dist/js/pages/dashboard3.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
  <?php if (session()->get('nama') && session()->get('role') && session()->get('username') && session()->get('picture') && session()->get('accessMenu')) : ?>
    <!--<script src="/js/formValidation.js"></script>-->
    <script src="/js/formValidation1.js"></script>
  <?php endif; ?>
  <?php
  if (isset($multiSignature)) {
    if (in_array($multiSignature, [true])) {
      echo '<script src="/dist/js/signature_pad2.3.2.min.js"></script>';
      echo '<script src="/js/multiSignature.js"></script>';
    }
  }
  if (isset($signature)) {
    if (in_array($signature, [true])) {
      echo '<script src="/dist/js/signature_pad2.3.2.min.js"></script>';
      echo '<script src="/js/signature.js"></script>';
    }
  }
  ?>
  
  <script>
    $(document).ready(function () {
        // Inisialisasi DateRangePicker
        $('.jam').daterangepicker({
            singleDatePicker: true, // Hanya satu waktu (bukan rentang)
            timePicker: true,       // Aktifkan picker waktu
            timePicker24Hour: true, // Format 24 jam
            timePickerIncrement: 1, // Increment detik (opsional)
            timePickerSeconds: true, // Tampilkan detik
            autoApply: true,        // Otomatis terapkan tanpa tombol "Apply"
            locale: {
                format: 'HH:mm:ss'     // Format waktu HH:mm:ss
            }
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide(); // Sembunyikan kalender
        });

        // Event listener untuk mengisi input dengan waktu saja
        $('.jam').on('apply.daterangepicker', function (ev, picker) {
            $(this).find('input').val(picker.startDate.format('HH:mm:ss')); // Isi input dengan waktu saja
        });

        // Mencegah pengetikan manual pada input
        $('.jam').find('input').on('keydown paste input', function (event) {
            event.preventDefault();
        });

        // Tombol Clear untuk mengosongkan input
        $('.clear-button').on('click', function () {
            $(this).closest('.jam').find('input').val(''); // Kosongkan nilai input
        });
    });
  </script>
  
  <script src="/js/sweetAlert3.js"></script>
  <?= in_array($activeMenu, ['user']) ? '<script src="/js/user.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['pegawai']) ? '<script src="/js/pegawai.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['unit_kerja']) ? '<script src="/js/unit_kerja.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['profileku']) ? '<script src="/js/profileku.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['dashboard_kapal']) ? '<script src="/js/dashboard_kapal.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['dashboard_pesawat']) ? '<script src="/js/dashboard_pesawat.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['dashboard_vaksin']) ? '<script src="/js/dashboard_vaksin.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['skema_titimangsa']) ? '<script src="/js/skema_titimangsa.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['kelola_data_cuti', 'kelola_data_permohonan']) ? '<script src="/js/kelola_data_cuti.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['sinadiSaya', 'dinasDalamKota', 'dinasLuarKota']) ? '<script src="/js/sinadi.js"></script>' : ''; ?>
  <?php if (in_array($activeMenu, ['dinasDalamKota', 'dinasLuarKota'])): ?>
    <script src="/js/formulirPerjalananDinas3.js?v=<?= time(); ?>"></script>
  <?php endif; ?>
  <?= in_array($activeMenu, ['sinadiBlockir']) ? '<script src="/js/sinadiBlockir.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['Dashboard']) ? '<script src="/js/dashboard(1).js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['sipeditArsipSaya']) ? '<script src="/js/sipedit1.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['rencana_kerja', 'daftar_dokumen']) ? '<script src="/js/wbkViewData2.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['arsip_surat', 'template_naskah', 'tambah_naskah']) ? '<script src="/js/arkab1.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['pengaturan_cuti']) ? '<script src="/js/pengaturanCuti.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['relaxtimePagu', 'relaxtimeRealisasi']) && !in_array($activeMenu, ['relaxtimeBlockir']) ? '<script src="/js/relaxtime.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['relaxtimeRealisasi']) && !in_array($activeMenu, ['relaxtimeBlockir']) ? '<script src="/js/relaxtimeRealisasi.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['relaxtimePengaturan']) && !in_array($activeMenu, ['relaxtimeBlockir']) ? '<script src="/js/relaxtimeSetting.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['relaxtimeBlockir']) ? '<script src="/js/relaxtimeBlockir.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['simandjaCapaian', 'simandjaRencana', 'simandjaRevisi']) ? '<script src="/js/simandja.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['relaxtimeTargetBulanan']) ? '<script src="/js/relaxtimeTargetBulanan.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['uklwPenjamahMakanan', 'uklwPasien', 'uklwPemkes', 'uklwSkriningTBC', 'uklwSkriningHIV', 'uklwLaboratorium']) ? '<script src="/js/uklw2.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['uklwPenjamahMakanan']) ? '<script src="/js/uklwPenjamahMakanan1.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['tpp', 'sab', 'hsbu']) ? '<script src="/js/prl.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['sivera']) ? '<script src="/js/sivera.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['bangunan', 'lokasi']) ? '<script src="/js/abs.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['lhkpnSpt']) ? '<script src="/js/lhkpnSpt.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['absenBlockir']) ? '<script src="/js/absenBlockir.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['pengawasanKapal']) ? '<script src="/js/pengawasanKapal.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['pengawasanKapalBlockir']) ? '<script src="/js/pengawasanKapalBlockir.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['monitoringSanitasiKapal']) ? '<script src="/js/monitoringSanitasiKapal.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['laporanPengawasanKapal']) ? '<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['laporanPengawasanKapal']) ? '<script src="/js/laporanPengawasanKapal.js"></script>' : ""; ?>
  
  <!-- FullCalendar untuk halaman agenda -->
  <?= in_array($activeMenu, ['agenda']) ? '<script src="/plugins/moment/moment.min.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['agenda']) ? '<script src="/plugins/fullcalendar/main.min.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['agenda']) ? '<script src="/plugins/fullcalendar/locales/id.js"></script>' : ""; ?>
  
  <!-- Google Maps JavaScript API -->
  <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4jKUxHGYL-QZDTL6KH1f47tOZnOxrHdA&libraries=geometry"></script>-->

  <!--<script src="/js/wbk.js"></script>-->
  <script src="/js/validation_data_cuti.js"></script>
  <script src="/js/sweetEmail.js"></script>
  <?= in_array($activeMenu, ['tambah_dokumen', 'utama', 'pendukung', 'lainnya', 'daftar_sop']) ? '<script src="/js/viewSopUtama.js"></script>' : ""; ?>
  <!--<script src="/js/inspectElement.js"></script>-->
  <?php if(session()->get('role') != "Admin"): ?>
  <?php endif; ?>
  
  <?php if (session()->getFlashdata('gantiPassword')) : ?>
    <script src="/js/tutorial.js"></script>
  <?php endif; ?>
  
  <script>
    $(document).ready(function() {
      $('.preview-image').on('click', function() {
        const base64 = $(this).data('base64'); // Ambil data base64 dari atribut data
        const imageUrl = base64;

        // Ganti konten modal-body dengan gambar langsung
        $('#modal-md')
          .find('.modal-body')
          .html(`<img src="${imageUrl}" class="img-fluid border" alt="Preview">`);
        $('#modal-md')
          .find('.modal-body').addClass('text-center');

        // Tambahkan header/modal-title jika perlu
        $('#modal-md')
          .find('.modal-header')
          .html(`
          <h5 class="modal-title">Preview</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        `);

        // Tampilkan modal
        $('#modal-md').modal('show');
      });
    });
  </script>
  <script>
    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function() {
        // Mencari elemen dengan kelas 'bs-stepper'
        var stepperElement = document.querySelector('.bs-stepper');
        
        // Memeriksa apakah elemen ada
        if (stepperElement) {
            // Jika ada, inisialisasi Stepper
            window.stepper = new Stepper(stepperElement);
        }
    });
    $(function() {
      $('.summernote').summernote({
        height: 300, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: true // set focus to editable area after initializing summernote
      });
      $('.summernote_no_media').summernote({
        height: 300, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: true, // set focus to editable area after initializing summernote
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['view', ['fullscreen']]
        ],
      });
      $('[data-toggle="tooltip"]').tooltip()
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })

      $("#tableWithoutExporting").DataTable({
        "responsive": true,
        "responsive": {
            "details": {
                "renderer": DataTable.Responsive.renderer.listHiddenNodes()
            }
        },
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "colvis"]
      }).buttons().container().appendTo('#tableWithoutExporting_wrapper .col-md-6:eq(0)');
      $("#example1").DataTable({
        "responsive": true,
        "responsive": {
            "details": {
                "renderer": DataTable.Responsive.renderer.listHiddenNodes()
            }
        },
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "excel", "pdf", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $("#example2").DataTable({
        "responsive": true,
        "responsive": {
            "details": {
                "renderer": DataTable.Responsive.renderer.listHiddenNodes()
            }
        },
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "excel", "colvis"]
      }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
      $('#example3').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "responsive": {
            "details": {
                "renderer": DataTable.Responsive.renderer.listHiddenNodes()
            }
        },
        columns: [{
            data: "Nama",
            className: "never"
          } //does not show on load
        ]
      });
      bsCustomFileInput.init();
    });
  </script>
  <script>
    $(document).ready(function() {
        // Inisialisasi Magnific Popup untuk gambar yang sudah ada di DOM
        $(document).on('click', '.gallery', function() {
            $.magnificPopup.open({
                items: {
                    src: $(this).data('src'),
                    type: 'image'
                },
                closeBtnInside: true
            });
            return false; // Mencegah aksi default
        });

        // Untuk gambar yang mungkin ditambahkan secara dinamis, Anda bisa menginisialisasi Magnific Popup setelah gambar ditambahkan
    });
    $(document).on('change', 'input[type="file"]', function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })
  </script>
  <script>
  
  <?php if (session()->getFlashdata('aksesAdmin') && date('Y-m-d') <= '2025-03-28') : ?>
    
    $(document).ready(function() {
        $('body')
            .removeClass('sidebar-collapse sidebar-closed')
            .addClass('sidebar-open');
        $("#Absen").addClass("menu-open");
        $("#Absen").find("ul").css('display', 'block');
        setTimeout(function() {
            introJs().setOptions({
                steps: [
                    {
                        element: document.querySelector('.pengaturan-absen-menu'),
                        intro: 'Pengaturan waktu absensi telah ditambahkan pada sistem di bagian menu "Pengaturan Absen"',
                        position: 'right'
                    }
                ],
                doneLabel: 'Ok'
            }).start();
        }, 200);
    });
  
  <?php endif; ?>
  
  <?php if (session()->getFlashdata('birthday_popup')) : ?>
  <?php $birthday = session()->getFlashdata('birthday_popup'); ?>
    $(document).ready(function() {
      
      // Fungsi untuk membuat animasi balon jatuh
      function createBalloons() {
        const container = document.createElement('div');
        container.className = 'balloon-container';
        container.id = 'balloon-container';
        document.body.appendChild(container);

        // Warna-warna mewah (Gold, Silver, Rose Gold)
        const luxColors = [
            'radial-gradient(circle at 15px 15px, #ffdf73, #d4af37)', // Gold
            'radial-gradient(circle at 15px 15px, #ffffff, #e0e0e0)', // Silver
            'radial-gradient(circle at 15px 15px, #ffc4d9, #b76e79)', // Rose Gold
            'radial-gradient(circle at 15px 15px, #ffd700, #daa520)'  // Dark Gold
        ];

        // Buat 40 balon
        for (let i = 0; i < 40; i++) {
            const balloon = document.createElement('div');
            balloon.className = 'balloon';
            
            balloon.style.left = (Math.random() * 100) + 'vw';
            balloon.style.animationDuration = (Math.random() * 4 + 5) + 's'; // 5-9 detik jatuh
            balloon.style.animationDelay = (Math.random() * 2) + 's'; // Delay 0-2 detik
            
            balloon.style.background = luxColors[Math.floor(Math.random() * luxColors.length)];
            
            // Variasi ukuran balon
            const scale = Math.random() * 0.6 + 0.8;
            balloon.style.transform = `scale(${scale})`;
            
            container.appendChild(balloon);
        }
      }

      // Fungsi untuk menghapus balon
      function removeBalloons() {
        const container = document.getElementById('balloon-container');
        if (container) {
            container.remove();
        }
      }

      // Fungsi untuk menampilkan popup ulang tahun
      window.showBirthdayPopup = function() {
        createBalloons(); // Munculkan balon
        
        Swal.fire({
          title: '<?= $birthday['title'] ?>',
          text: '<?= $birthday['message'] ?>',
          icon: '<?= $birthday['icon'] ?>',
          confirmButtonText: 'Terima Kasih!',
          allowOutsideClick: false,
          backdrop: `
            rgba(0,0,0,0.6)
            url("/dist/img/confetti.gif")
            left top
            no-repeat
          `,
          showClass: {
            popup: 'animate__animated animate__zoomIn'
          },
          hideClass: {
            popup: 'animate__animated animate__zoomOut'
          },
          customClass: {
            popup: 'birthday-popup'
          }
        }).then((result) => {
            if (result.isConfirmed) {
                removeBalloons(); // Hapus balon saat tombol ditekan
            }
        });
      };

      // Tampilkan ulang tahun setelah delay 2 detik
      // Jika ada popup lain (seperti popup Agenda atau Motivasi) yang sedang tampil,
      // tunggu sampai popup tersebut ditutup baru tampilkan popup ulang tahun.
      function tryShowBirthday() {
        if (!window.agendaAlertWillShow && !Swal.isVisible()) {
          showBirthdayPopup();
        } else {
          // Coba lagi setelah 1 detik jika masih ada popup lain yang terbuka
          setTimeout(tryShowBirthday, 1000);
        }
      }
      
      setTimeout(tryShowBirthday, 2000);
    });
  <?php endif; ?>
  
  </script>

  <script>
    // Smooth Tab Navigation for E-KIP
    $(document).on('click', '#penilaianTab a.nav-link', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if ($(this).hasClass('active')) return;
        
        loadEkipContent(url);
    });

    // Smooth Filter Navigation for E-KIP
    $(document).on('change', '.form-filter-evaluasi select', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var url = form.attr('action') + '?' + form.serialize();
        loadEkipContent(url);
    });

    function loadEkipContent(url) {
        // Add loading indicator
        Swal.fire({
            title: 'Memuat...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Parse the response using DOMParser to avoid executing scripts prematurely
                var parser = new DOMParser();
                var doc = parser.parseFromString(response, 'text/html');
                var newContentWrapper = doc.querySelector('.content-wrapper');
                
                if (newContentWrapper) {
                    var newContent = newContentWrapper.innerHTML;
                    $('.content-wrapper').html(newContent);
                    window.history.pushState({"html":response,"pageTitle":document.title},"", url);
                    
                    // Update active menu in sidebar
                    $('.nav-sidebar a.nav-link').removeClass('active');
                    $('.nav-sidebar a[href="/ekip/kinerja"]').addClass('active');

                    Swal.close();
                } else {
                    window.location.href = url;
                }
            },
            error: function() {
                window.location.href = url; // Fallback to normal reload
            }
        });
    }

    // Handle back/forward buttons
    window.onpopstate = function(e){
        if(e.state && e.state.html){
            var parser = new DOMParser();
            var doc = parser.parseFromString(e.state.html, 'text/html');
            var newContentWrapper = doc.querySelector('.content-wrapper');
            if (newContentWrapper) {
                $('.content-wrapper').html(newContentWrapper.innerHTML);
            } else {
                window.location.reload();
            }
        }
    };
  </script>
  
  <?= $this->include('layouts/allSection'); ?>
</body>

</html>