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
  <link rel="stylesheet" href="/css/customLayout-v2.css?v=<?= @filemtime(FCPATH . 'css/customLayout-v2.css') ?: '1'; ?>">
  
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

    /* Pastikan info user pada sidebar tidak menyisakan ruang kosong saat sidebar di-collapse */
    .main-sidebar .user-panel > .info {
      white-space: normal;
      flex: 1 1 auto;
      min-width: 0;
      overflow: hidden;
      padding-right: 10px;
    }

    .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover):not(.sidebar-focused) .user-panel > .info,
    .sidebar-mini-md.sidebar-collapse .main-sidebar:not(:hover):not(.sidebar-focused) .user-panel > .info,
    .sidebar-mini-xs.sidebar-collapse .main-sidebar:not(:hover):not(.sidebar-focused) .user-panel > .info,
    .sidebar-collapse .main-sidebar.sidebar-no-expand .user-panel > .info {
      display: none !important;
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

  <!-- Modern Sidebar, Top Bar & Control Sidebar Styles (embedded agar langsung aktif tanpa terkendala cache/public_html) -->
  <style>
    :root {
      --simply-teal-900: #084c57;
      --simply-teal-800: #0b6e78;
      --simply-teal-700: #0d858c;
      --simply-teal-600: #0d9488;
      --simply-teal-500: #14b8a6;
      --simply-teal-300: #5eead4;
      --simply-ink: #0f172a;
      --simply-muted: #64748b;
      --simply-border: rgba(15, 23, 42, 0.08);
    }

    /* 1. TOP NAVIGATION BAR */
    .main-header.navbar {
      background: rgba(255, 255, 255, 0.96) !important;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--simply-border) !important;
      box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04);
      min-height: 56px;
      padding: 0 14px;
      position: sticky;
      top: 0;
      z-index: 1030;
    }
    .main-header .navbar-nav {
      align-items: center;
      gap: 4px;
    }
    .main-header .navbar-nav > .nav-item > .nav-link.topbar-icon-btn {
      width: 36px;
      height: 36px;
      padding: 0;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #475569 !important;
      transition: background-color 0.18s ease, color 0.18s ease, transform 0.15s ease;
      position: relative;
    }
    .main-header .navbar-nav > .nav-item > .nav-link.topbar-icon-btn:hover,
    .main-header .navbar-nav > .nav-item.show > .nav-link.topbar-icon-btn {
      background-color: #f1f5f9;
      color: var(--simply-teal-600) !important;
    }
    .topbar-quick-link {
      display: inline-flex !important;
      align-items: center;
      gap: 6px;
      height: 34px;
      padding: 0 12px !important;
      border-radius: 9px;
      font-size: 0.82rem;
      font-weight: 600;
      color: #475569 !important;
      transition: background-color 0.18s ease, color 0.18s ease;
    }
    .topbar-quick-link i {
      font-size: 0.82rem;
      opacity: 0.85;
    }
    .topbar-quick-link:hover {
      background-color: #f1f5f9;
      color: var(--simply-teal-600) !important;
    }
    .topbar-quick-link.is-active {
      background-color: rgba(13, 148, 136, 0.11);
      color: var(--simply-teal-700) !important;
    }
    .topbar-divider {
      width: 1px;
      height: 22px;
      background: rgba(15, 23, 42, 0.1);
      margin: 0 4px;
    }
    .main-header .topbar-icon-btn .navbar-badge {
      position: absolute;
      top: 2px;
      right: 2px;
      font-size: 0.62rem;
      font-weight: 700;
      padding: 2px 5px;
      border-radius: 999px;
      line-height: 1.1;
      box-shadow: 0 0 0 2px #ffffff;
    }
    .main-header .topbar-icon-btn .navbar-badge:empty {
      display: none;
    }
    .topbar-user-btn {
      display: inline-flex !important;
      align-items: center;
      gap: 8px;
      height: 36px;
      padding: 3px 10px 3px 4px !important;
      border-radius: 999px;
      border: 1px solid rgba(15, 23, 42, 0.08);
      background: #f8fafc;
      color: var(--simply-ink) !important;
      transition: background-color 0.18s ease, border-color 0.18s ease;
    }
    .topbar-user-btn:hover,
    .nav-item.show > .topbar-user-btn {
      background: #f0fdfa;
      border-color: rgba(13, 148, 136, 0.35);
    }
    .topbar-user-avatar {
      width: 28px !important;
      height: 28px !important;
      max-width: 28px !important;
      max-height: 28px !important;
      border-radius: 50% !important;
      object-fit: cover !important;
      border: 1.5px solid rgba(13, 148, 136, 0.35);
      flex-shrink: 0;
    }
    .topbar-user-name {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--simply-ink);
      max-width: 130px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .main-header .dropdown-menu {
      border: 1px solid rgba(15, 23, 42, 0.09);
      border-radius: 14px;
      box-shadow: 0 14px 34px rgba(15, 23, 42, 0.13);
      padding: 6px;
      margin-top: 8px;
      min-width: 240px;
    }
    .main-header .dropdown-menu.notifikasi-pesan-sistem {
      min-width: 310px;
      max-width: 340px;
      padding: 0;
      overflow: hidden;
    }
    .main-header .dropdown-header {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.7px;
      color: var(--simply-muted);
      text-align: left;
      padding: 8px 12px 5px;
    }
    .main-header .dropdown-item {
      border-radius: 9px;
      padding: 8px 12px;
      font-size: 0.83rem;
      color: #334155;
      display: flex;
      align-items: center;
      gap: 9px;
      transition: background-color 0.15s ease, color 0.15s ease;
    }
    .main-header .dropdown-item i {
      width: 16px;
      text-align: center;
      color: #64748b;
    }
    .main-header .dropdown-item:hover {
      background-color: #f0fdfa;
      color: var(--simply-teal-800);
    }
    .main-header .dropdown-item:hover i {
      color: var(--simply-teal-600);
    }
    .main-header .dropdown-item.text-danger:hover {
      background-color: #fef2f2;
      color: #dc2626 !important;
    }
    .main-header .dropdown-item.text-danger i {
      color: #dc2626;
    }
    .main-header .dropdown-divider {
      margin: 5px 6px;
      border-top-color: rgba(15, 23, 42, 0.07);
    }

    /* Notification Dropdown */
    .notif-dropdown-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 11px 14px;
      background: #f8fafc;
      border-bottom: 1px solid rgba(15, 23, 42, 0.07);
    }
    .notif-dropdown-list {
      max-height: 310px;
      overflow-y: auto;
    }
    .notif-item-row {
      display: flex !important;
      align-items: flex-start !important;
      gap: 10px !important;
      padding: 10px 14px !important;
      border-radius: 0 !important;
      border-bottom: 1px solid rgba(15, 23, 42, 0.05);
      white-space: normal !important;
    }
    .notif-item-row.is-unread {
      background-color: rgba(13, 148, 136, 0.07);
    }
    .notif-item-row:hover {
      background-color: #f0fdfa !important;
    }
    .notif-avatar-circle {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: linear-gradient(135deg, #0d9488, #0b7285);
      color: #ffffff;
      font-weight: 700;
      font-size: 0.78rem;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 2px;
    }
    .notif-item-content { flex: 1; min-width: 0; }
    .notif-item-sender {
      font-size: 0.81rem;
      font-weight: 700;
      color: var(--simply-ink);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 6px;
    }
    .notif-unread-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--simply-teal-600);
      flex-shrink: 0;
    }
    .notif-item-msg {
      font-size: 0.77rem;
      color: #475569;
      margin: 2px 0 3px;
      line-height: 1.35;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .notif-item-time { font-size: 0.7rem; color: #94a3b8; }
    .notif-dropdown-footer {
      display: block !important;
      text-align: center !important;
      justify-content: center !important;
      padding: 10px 14px !important;
      font-weight: 600 !important;
      font-size: 0.8rem !important;
      color: var(--simply-teal-700) !important;
      background: #f8fafc;
      border-radius: 0 !important;
    }

    /* 2. LEFT MAIN SIDEBAR */
    .sidebar-dark-primary {
      background: linear-gradient(180deg, #0a5e69 0%, #0d7a82 46%, #0c6b66 100%) !important;
      color: #ffffff;
      box-shadow: 4px 0 22px rgba(8, 76, 87, 0.16) !important;
    }
    [class*="sidebar-dark"] .brand-link {
      border-bottom: 1px solid rgba(255, 255, 255, 0.14) !important;
      padding: 10px 14px !important;
      display: flex !important;
      align-items: center;
      gap: 10px;
      min-height: 56px;
      background: rgba(0, 0, 0, 0.1);
      transition: background-color 0.2s ease;
    }
    [class*="sidebar-dark"] .brand-link:hover {
      background: rgba(0, 0, 0, 0.16);
    }
    .brand-link .brand-image {
      float: none !important;
      margin: 0 !important;
      width: 34px !important;
      height: 34px !important;
      max-height: 34px !important;
      object-fit: contain;
      background: rgba(255, 255, 255, 0.94);
      padding: 3px;
      border-radius: 10px !important;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.18) !important;
      flex-shrink: 0;
    }
    .brand-link .brand-text-wrap {
      display: flex;
      flex-direction: column;
      line-height: 1.18;
      min-width: 0;
      overflow: hidden;
    }
    .brand-link .brand-title-main {
      font-size: 0.86rem;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: 0.2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .brand-link .brand-title-sub {
      font-size: 0.65rem;
      font-weight: 500;
      color: rgba(255, 255, 255, 0.72);
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }
    .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover):not(.sidebar-focused) .brand-text-wrap {
      display: none !important;
    }
    [class*="sidebar-dark"] .user-panel {
      border-bottom: none !important;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.14);
      border-radius: 12px;
      padding: 10px !important;
      margin: 12px 4px 10px !important;
      align-items: flex-start;
      gap: 10px;
    }
    .main-sidebar .user-panel > .image {
      padding-left: 0 !important;
      flex-shrink: 0;
    }
    .main-sidebar .user-panel > .image img.profile-picture {
      width: 40px !important;
      height: 40px !important;
      object-fit: cover !important;
      border: 2px solid rgba(255, 255, 255, 0.55);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
    }
    .main-sidebar .user-panel > .info {
      padding: 0 !important;
      white-space: normal;
      flex: 1 1 auto;
      min-width: 0;
      overflow: hidden;
    }
    .sidebar-user-name {
      display: block;
      font-size: 0.83rem;
      font-weight: 700;
      color: #ffffff !important;
      line-height: 1.25;
      margin-bottom: 3px;
    }
    .sidebar-user-jabatan {
      font-size: 0.72rem;
      color: rgba(255, 255, 255, 0.9);
      line-height: 1.3;
      margin-bottom: 2px;
    }
    .sidebar-user-meta {
      font-size: 0.68rem;
      color: rgba(255, 255, 255, 0.72);
      line-height: 1.25;
    }
    .sidebar-user-edit-btn {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      margin-top: 7px;
      padding: 2px 9px;
      font-size: 0.69rem;
      font-weight: 600;
      color: #ffffff !important;
      background: rgba(255, 255, 255, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.28);
      border-radius: 999px;
      transition: background-color 0.18s ease;
    }
    .sidebar-user-edit-btn:hover {
      background: rgba(255, 255, 255, 0.28);
      color: #ffffff !important;
    }
    .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover):not(.sidebar-focused) .user-panel {
      margin: 10px 2px !important;
      padding: 6px !important;
      justify-content: center;
      background: transparent;
      border-color: transparent;
    }
    .sidebar-search-wrap {
      padding: 0 4px 6px;
    }
    .sidebar-search-wrap .input-group {
      border-radius: 10px;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.18);
      background: rgba(255, 255, 255, 0.12);
    }
    [class*="sidebar-dark"] .form-control-sidebar {
      background-color: transparent !important;
      border: none !important;
      color: #ffffff !important;
      font-size: 0.8rem;
      height: 34px;
      padding: 4px 10px;
    }
    [class*="sidebar-dark"] .form-control-sidebar::placeholder {
      color: rgba(255, 255, 255, 0.68);
    }
    [class*="sidebar-dark"] .btn-sidebar {
      background-color: transparent !important;
      border: none !important;
      color: rgba(255, 255, 255, 0.85) !important;
      height: 34px;
      padding: 0 10px;
    }
    .nav-sidebar > .nav-header {
      font-size: 0.67rem !important;
      font-weight: 700 !important;
      letter-spacing: 0.9px;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.56) !important;
      padding: 12px 12px 4px !important;
    }
    .nav-sidebar .nav-item > .nav-link {
      border-radius: 9px !important;
      margin: 2px 4px;
      padding: 0.48rem 0.72rem;
      font-size: 0.82rem;
      font-weight: 500;
      transition: background-color 0.16s ease, color 0.16s ease;
    }
    .nav-sidebar .nav-item > .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.12) !important;
      color: #ffffff !important;
    }
    .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
    .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
      background-color: rgba(255, 255, 255, 0.22) !important;
      color: #ffffff !important;
      font-weight: 700;
      box-shadow: inset 3px 0 0 var(--simply-teal-300), 0 2px 8px rgba(0, 0, 0, 0.12) !important;
    }
    [class*="sidebar-dark-"] .nav-sidebar > .nav-item > .nav-treeview {
      background-color: rgba(0, 0, 0, 0.14) !important;
      border-radius: 10px;
      margin: 2px 4px 5px;
      padding: 4px 0;
    }
    [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link {
      color: rgba(255, 255, 255, 0.86) !important;
      font-size: 0.79rem;
      margin: 1px 4px;
      padding: 0.4rem 0.65rem;
    }
    [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link.active,
    [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link.active:focus,
    [class*="sidebar-dark-"] .nav-treeview > .nav-item > .nav-link.active:hover {
      background-color: rgba(94, 234, 212, 0.22) !important;
      color: #ffffff !important;
      font-weight: 600;
    }
    .nav-sidebar .nav-divider-line {
      border: 0;
      border-top: 1px solid rgba(255, 255, 255, 0.14);
      margin: 6px 12px;
    }
    #logout > .nav-link {
      background: rgba(244, 63, 94, 0.14);
      border: 1px solid rgba(255, 255, 255, 0.14);
      margin-top: 8px;
    }
    #logout > .nav-link:hover {
      background: rgba(225, 29, 72, 0.85) !important;
      color: #ffffff !important;
    }

    /* 3. RIGHT CONTROL SIDEBAR (USER DIRECTORY) */
    #side-right-user.control-sidebar {
      width: 280px !important;
      background: #ffffff;
      border-left: 1px solid var(--simply-border);
      box-shadow: -6px 0 24px rgba(15, 23, 42, 0.08);
      padding: 0;
    }
    .right-user-header {
      position: sticky;
      top: 0;
      z-index: 5;
      background: #ffffff;
      padding: 14px 14px 10px;
      border-bottom: 1px solid var(--simply-border);
    }
    .right-user-title {
      font-size: 0.88rem;
      font-weight: 700;
      color: var(--simply-ink);
      margin: 0 0 8px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .right-user-search {
      width: 100%;
      font-size: 0.8rem;
      padding: 6px 10px 6px 30px;
      border-radius: 9px;
      border: 1px solid rgba(15, 23, 42, 0.12);
      background: #f8fafc;
      color: var(--simply-ink);
    }
    .right-user-list {
      list-style: none;
      margin: 0;
      padding: 6px 8px 24px;
    }
    .right-user-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 8px 10px;
      border-radius: 10px;
      transition: background-color 0.15s ease;
      cursor: pointer;
    }
    .right-user-item:hover { background-color: #f0fdfa; }
    .right-user-avatar {
      width: 36px !important;
      height: 36px !important;
      border-radius: 50%;
      object-fit: cover;
      flex-shrink: 0;
      border: 1px solid rgba(15, 23, 42, 0.08);
    }
    .right-user-meta { flex: 1; min-width: 0; }
    .right-user-name {
      font-size: 0.81rem;
      font-weight: 600;
      color: var(--simply-ink);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin: 0;
    }
    .right-user-email {
      font-size: 0.71rem;
      color: var(--simply-muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .right-user-mail-btn {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      border: none;
      background: #fef2f2;
      color: #dc2626;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .right-user-mail-btn:hover { background: #dc2626; color: #ffffff; }
    .right-user-mail-btn.is-disabled { background: #f1f5f9; color: #94a3b8; cursor: default; }

    @media (max-width: 767.98px) {
      .main-header.navbar { padding: 0 8px; }
      .topbar-user-name { display: none; }
      .topbar-user-btn { padding: 3px !important; }
    }
  </style>
  
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
    <script>
      // Jangan biarkan preloader memblokir tampilan menunggu CDN eksternal yang lambat
      document.addEventListener('DOMContentLoaded', function() {
        var preloader = document.querySelector('.preloader');
        if (!preloader) return;
        setTimeout(function() {
          preloader.style.transition = 'opacity 0.2s ease, height 0.2s ease';
          preloader.style.opacity = '0';
          preloader.style.height = '0';
          preloader.style.pointerEvents = 'none';
          setTimeout(function() {
            preloader.style.display = 'none';
          }, 220);
        }, 120);
      });
    </script>

    <?= $this->include('layouts/modal'); ?>

    <!-- Navbar -->
    <?php
    $navAccessMenu = session()->get('accessMenu');
    if (!is_array($navAccessMenu)) {
      $navAccessMenu = [];
    }
    $navPicture = trim((string) session()->get('picture'));
    $navAvatarSrc = $navPicture !== '' ? '/dist/img/avatar/' . esc($navPicture, 'attr') : '/dist/img/boxed-bg.jpg';
    $navNama = trim((string) session()->get('nama'));
    $navRole = trim((string) session()->get('role'));
    $navActiveMenu = isset($activeMenu) ? (string) $activeMenu : '';
    ?>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link topbar-icon-btn" data-widget="pushmenu" href="#" role="button" title="Toggle Menu Sidebar">
            <i class="fas fa-bars"></i>
          </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="/home" class="nav-link topbar-quick-link <?= $navActiveMenu === '' ? 'is-active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
          </a>
        </li>
        <?php if (in_array('Dashboard', $navAccessMenu)) : ?>
          <li class="nav-item d-none d-md-inline-block">
            <a href="/home/dashboard" class="nav-link topbar-quick-link <?= $navActiveMenu === 'Dashboard' ? 'is-active' : ''; ?>">
              <i class="fas fa-chart-pie"></i>
              <span>Dashboard</span>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item d-none d-lg-inline-block">
          <a href="/absensi" class="nav-link topbar-quick-link">
            <i class="fas fa-fingerprint"></i>
            <span>Absensi</span>
          </a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <?php if ($navRole === 'Admin'): ?>
        <li class="nav-item dropdown">
          <a class="nav-link topbar-icon-btn" data-toggle="dropdown" href="#" title="Pengaturan Administrator">
            <i class="fas fa-sliders-h"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <span class="dropdown-header">Pengaturan Administrator</span>
            <a href="javascript:void(0)" onclick="toggleVaksinasi()" class="dropdown-item justify-content-between">
              <span><i class="fas fa-stethoscope mr-2"></i>Pengalihan Vaksinasi SISOVI</span>
              <span id="toggle-on" class="text-success" style="display: none;"><i class="fas fa-toggle-on fa-lg"></i></span>
              <span id="toggle-off" class="text-muted"><i class="fas fa-toggle-off fa-lg"></i></span>
            </a>
          </div>
        </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link topbar-icon-btn" target="_blank" rel="noopener noreferrer" href="/manualBooks" title="Manual Books">
            <i class="fas fa-book"></i>
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link topbar-icon-btn" data-toggle="dropdown" href="#" title="Notifikasi">
            <i class="far fa-bell"></i>
            <span class="badge badge-danger navbar-badge notifikasi-pesan-sistem-number"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right notifikasi-pesan-sistem">
            <div class="text-center text-muted small py-3 px-3">
              <i class="fas fa-spinner fa-spin mr-1"></i> Memuat notifikasi...
            </div>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link topbar-icon-btn" href="/home/agenda" title="Agenda Saya">
            <i class="far fa-calendar-alt"></i>
          </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
          <a class="nav-link topbar-icon-btn" data-widget="fullscreen" href="#" role="button" title="Layar Penuh">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>

        <li class="nav-item" onclick="positionRightMessage()">
          <a class="nav-link topbar-icon-btn" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button" title="Direktori Pegawai">
            <i class="fas fa-address-book"></i>
          </a>
        </li>

        <li class="nav-item d-none d-sm-flex align-items-center">
          <span class="topbar-divider"></span>
        </li>

        <!-- User Account Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link topbar-user-btn" data-toggle="dropdown" href="#" title="<?= esc($navNama, 'attr'); ?>">
            <img src="<?= $navAvatarSrc; ?>" width="28" height="28" style="width:28px;height:28px;border-radius:50%;object-fit:cover;" onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';" class="topbar-user-avatar" alt="Avatar" decoding="async">
            <span class="topbar-user-name"><?= esc($navNama); ?></span>
            <i class="fas fa-chevron-down d-none d-md-inline" style="font-size: 0.65rem; color: #94a3b8;"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="px-3 py-2 border-bottom mb-1">
              <div class="font-weight-bold text-dark" style="font-size: 0.85rem; line-height: 1.25;"><?= esc($navNama); ?></div>
              <small class="text-muted"><?= esc($navRole ?: 'Pegawai'); ?></small>
            </div>
            <a href="/home/profil" class="dropdown-item">
              <i class="fas fa-user-circle"></i>
              <span>Akun Saya</span>
            </a>
            <span class="dropdown-header mt-1">Linimasa Aktivitas</span>
            <a class="dropdown-item" href="/home/history/login">
              <i class="fas fa-sign-in-alt"></i>
              <span>Riwayat Login</span>
            </a>
            <a class="dropdown-item" href="/home/history/infografis">
              <i class="fas fa-file-alt"></i>
              <span>Infografis Kapal / Pesawat / Vaksin</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="javascript:void(0)" data-location="/auth/logout" onclick="logoutSweetAlert(this)" class="dropdown-item text-danger">
              <i class="fas fa-sign-out-alt"></i>
              <span>Keluar</span>
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/home" class="brand-link">
        <img src="/dist/img/LOGO_KARANTINA.png" width="34" height="34" style="width:34px;height:34px;object-fit:contain;" alt="Logo Balai Karkes Bandung" class="brand-image" decoding="async">
        <span class="brand-text-wrap">
          <span class="brand-title-main">Balai Karkes Bandung</span>
          <span class="brand-title-sub">SIMPLY System</span>
        </span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel d-flex">
          <div class="image">
            <img src="<?= $navAvatarSrc; ?>" width="40" height="40" style="width:40px;height:40px;object-fit:cover;" onerror="this.onerror=null;this.src='/dist/img/boxed-bg.jpg';" class="profile-picture img-circle elevation-2" alt="User Image" decoding="async">
          </div>
          <div class="info">
            <a href="/home/profil" class="sidebar-user-name"><?= esc($navNama); ?></a>
            <?php
            $sidebarUid = (int) session()->get('id_user');
            $sidebarCacheTtl = 180;
            $sidebarMetaCache = session()->get('_sidebar_pegawai_cache');

            if (array_key_exists('pegawaiInfo', get_defined_vars())) {
              // Segarkan cache session saat controller sudah mengambil data terbaru (mis. Home::index)
              if ($sidebarUid > 0) {
                session()->set('_sidebar_pegawai_cache', [
                  'uid'  => $sidebarUid,
                  'ts'   => time(),
                  'data' => $pegawaiInfo,
                ]);
              }
            } elseif (
              is_array($sidebarMetaCache)
              && (int) ($sidebarMetaCache['uid'] ?? 0) === $sidebarUid
              && (time() - (int) ($sidebarMetaCache['ts'] ?? 0)) < $sidebarCacheTtl
            ) {
              $pegawaiInfo = $sidebarMetaCache['data'] ?? null;
            } else {
              $pegawaiInfo = null;
              if ($sidebarUid > 0) {
                $db = \Config\Database::connect();
                $pegawaiInfo = $db->table('data_pegawai')
                  ->select('jabatan.jabatan, golongan.golongan, tim_kerja.nama_tim_kerja')
                  ->join('jabatan', 'jabatan.id_jabatan = data_pegawai.id_jabatan', 'left')
                  ->join('golongan', 'golongan.id_golongan = data_pegawai.id_golongan', 'left')
                  ->join('tim_kerja', 'tim_kerja.id_tim_kerja = data_pegawai.id_tim_kerja', 'left')
                  ->where('data_pegawai.id_user', $sidebarUid)
                  ->get()->getRowArray();
                session()->set('_sidebar_pegawai_cache', [
                  'uid'  => $sidebarUid,
                  'ts'   => time(),
                  'data' => $pegawaiInfo,
                ]);
              }
            }

            if ($pegawaiInfo):
            ?>
            <div>
                <?php if (!empty($pegawaiInfo['jabatan'])): ?>
                    <div class="sidebar-user-jabatan"><?= esc($pegawaiInfo['jabatan']); ?></div>
                <?php endif; ?>

                <?php if (!empty($pegawaiInfo['golongan']) || !empty($pegawaiInfo['nama_tim_kerja'])): ?>
                    <div class="sidebar-user-meta">
                        <?php
                        $details = [];
                        if (!empty($pegawaiInfo['golongan'])) $details[] = esc($pegawaiInfo['golongan']);
                        if (!empty($pegawaiInfo['nama_tim_kerja'])) $details[] = esc($pegawaiInfo['nama_tim_kerja']);
                        echo implode(' &bull; ', $details);
                        ?>
                    </div>
                <?php endif; ?>
                <div>
                    <a href="/home/profil" class="sidebar-user-edit-btn"><i class="fas fa-pen"></i> Edit Profil</a>
                </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="sidebar-search-wrap">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Cari menu..." aria-label="Cari menu">
            <div class="input-group-append">
              <button class="btn btn-sidebar" type="button" aria-label="Cari">
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
    <aside id="side-right-user" class="control-sidebar control-sidebar-light" style="overflow-y: auto;">
      <div class="text-center text-muted small py-5 px-3">
        <i class="fas fa-users d-block mb-2" style="font-size: 1.5rem; opacity: 0.45;"></i>
        Klik untuk memuat direktori pegawai
      </div>
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
        if ($('.jam').length) {
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
        }
    });
  </script>
  
  <script src="/js/sweetAlert3.js?v=<?= @filemtime(FCPATH . 'js/sweetAlert3.js') ?: '1'; ?>"></script>
  <?= in_array($activeMenu, ['user']) ? '<script src="/js/user.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['pegawai']) ? '<script src="/js/pegawai.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['unit_kerja']) ? '<script src="/js/unit_kerja.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['profileku']) ? '<script src="/js/profileku.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['dashboard_kapal']) ? '<script src="/js/dashboard_kapal.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['dashboard_pesawat']) ? '<script src="/js/dashboard_pesawat.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['dashboard_vaksin']) ? '<script src="/js/dashboard_vaksin.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['skema_titimangsa']) ? '<script src="/js/skema_titimangsa.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['kelola_data_cuti', 'kelola_data_permohonan']) ? '<script src="/js/kelola_data_cuti.js"></script>' : ''; ?>
  <?= in_array($activeMenu, ['sinadiSaya']) ? '<script src="/js/sinadi.js?v=' . (@filemtime(FCPATH . 'js/sinadi.js') ?: '2') . '"></script>' : ''; ?>
  <?= in_array($activeMenu, ['sinadiBlockir']) ? '<script src="/js/sinadiBlockir.js"></script>' : ""; ?>
  <?= in_array($activeMenu, ['Dashboard']) ? '<script src="/js/dashboard(1).js?v=' . filemtime(FCPATH . 'js/dashboard(1).js') . '"></script>' : ""; ?>
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
  <?= in_array($activeMenu, ['sivera']) ? '<script src="/js/sivera.js?v=' . filemtime(FCPATH . 'js/sivera.js') . '"></script>' : ""; ?>
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
  <?php if (in_array($activeMenu, ['validation_data_cuti', 'validation_data_permohonan', 'kelola_data_cuti', 'kelola_data_permohonan', 'pengaturan_cuti'])) : ?>
    <script src="/js/validation_data_cuti.js?v=<?= @filemtime(FCPATH . 'js/validation_data_cuti.js') ?: '1'; ?>"></script>
  <?php endif; ?>
  <script src="/js/sweetEmail.js?v=<?= @filemtime(FCPATH . 'js/sweetEmail.js') ?: '1'; ?>"></script>
  <script>
    // Pastikan fungsi menuOpen yang sudah dioptimasi tetap aktif meskipun JS statis ter-cache
    window.menuOpen = function(idMenu) {
      var $target = $(idMenu);
      if (!$target.length) return;
      if ($('body').hasClass('sidebar-collapse') || $('body').hasClass('sidebar-closed')) {
        $('body').removeClass('sidebar-collapse sidebar-closed').addClass('sidebar-open');
      }
      $(".nav-sidebar .nav-item").removeClass('menu-open');
      $target.addClass('menu-open');
      $target.children('.nav-treeview').stop(true, true).slideDown(200);
      $target.css({
        backgroundColor: 'rgba(94, 234, 212, 0.28)',
        borderRadius: '9px',
        transition: 'background-color 0.4s ease'
      }).delay(900).queue(function() {
        $(this).css({ backgroundColor: 'transparent' }).dequeue();
      });
      if ($target[0] && typeof $target[0].scrollIntoView === 'function') {
        $target[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    };
  </script>
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
      if ($('.summernote').length) {
        $('.summernote').summernote({
          height: 300, // set editor height
          minHeight: null, // set minimum height of editor
          maxHeight: null, // set maximum height of editor
          focus: true // set focus to editable area after initializing summernote
        });
      }
      if ($('.summernote_no_media').length) {
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
      }
      if ($('[data-toggle="tooltip"]').length) {
        $('[data-toggle="tooltip"]').tooltip();
      }
      //Initialize Select2 Elements
      if ($('.select2').length) {
        $('.select2').select2();
      }

      //Initialize Select2 Elements
      if ($('.select2bs4').length) {
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });
      }

      if ($('#tableWithoutExporting').length) {
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
      }
      if ($('#example1').length) {
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
      }
      if ($('#example2').length) {
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
      }
      if ($('#example3').length) {
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
      }
      if ($('.custom-file-input').length && typeof bsCustomFileInput !== 'undefined') {
        bsCustomFileInput.init();
      }
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