<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Simply App</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="/login-style/dist/vendor/bootstrap-4.5.3/css/bootstrap.min.css" type="text/css">
  <!-- Material design icons -->
  <link rel="stylesheet" href="/login-style/dist/icons/material-design-icons/css/mdi.min.css" type="text/css">
  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Latform styles -->
  <link rel="stylesheet" href="/login-style/dist/css/latform-style-9.min.css" type="text/css">
  
  <style>
    :root {
        --bg-color: #2b343b;
        --primary: #5f8a92; /* Teal accent */
        --glass-bg: rgba(255, 255, 255, 0.08);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-main: #ffffff;
        --text-muted: rgba(255, 255, 255, 0.6);
    }

    * {
        -webkit-tap-highlight-color: transparent;
        -webkit-touch-callout: none;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        background-image: 
            radial-gradient(circle at 70% 30%, rgba(220, 160, 100, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 20% 70%, rgba(100, 150, 180, 0.1) 0%, transparent 50%),
            linear-gradient(180deg, rgba(43, 52, 59, 0.8) 0%, #1a2228 100%);
        background-attachment: fixed;
        min-height: 100vh;
        margin: 0;
        position: relative;
        overflow-x: hidden;
        color: var(--text-main);
    }

    .form-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 0;
        position: relative;
        z-index: 1;
    }

    .container {
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    .card {
        border: none;
        border-radius: 0;
        box-shadow: none;
        background: transparent;
        position: relative;
    }

    .card-body {
        padding: 30px 25px;
    }

    /* Mobile First Adjustments */
    @media (max-width: 991px) {
        .illustration-col {
            display: none !important;
        }
        .card-body {
            padding: 40px 25px;
        }
    }

    @media (min-width: 992px) {
        .container {
            max-width: 1140px;
            padding: 0 15px;
        }
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
    }

    .logo img {
        transition: transform 0.3s ease;
        margin: 0 5px;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
        background: rgba(255,255,255,0.8);
        border-radius: 10px;
        padding: 5px;
    }
    
    .logo img:hover {
        transform: scale(1.05);
    }

    h5 {
        color: var(--text-main) !important;
        font-weight: 600 !important;
        font-size: 1.1rem;
        line-height: 1.5;
    }

    .form-control {
        border-radius: 30px;
        padding: 14px 20px 14px 50px;
        height: auto;
        border: 1px solid var(--glass-border);
        font-size: 15px;
        background-color: var(--glass-bg);
        color: var(--text-main);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        background-color: rgba(255, 255, 255, 0.12);
        color: var(--text-main);
        box-shadow: 0 0 0 4px rgba(95, 138, 146, 0.2);
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .form-icon-wrapper .form-icon-left {
        left: 15px;
        color: var(--text-muted);
        font-size: 20px;
        top: 50%;
        transform: translateY(-50%);
    }

    .form-icon-wrapper .form-icon-right {
        right: 15px;
        color: var(--text-muted);
        top: 50%;
        transform: translateY(-50%);
    }

    .form-control:focus ~ .form-icon-left {
        color: var(--primary);
    }

    .btn-primary {
        background: var(--primary);
        border: none;
        border-radius: 30px;
        padding: 14px;
        font-weight: 600;
        font-size: 16px;
        letter-spacing: 0.5px;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #4c6f75;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        transform: translateY(-2px);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .social-links {
        color: var(--text-muted);
    }

    .alert {
        border-radius: 16px;
        border: none;
        font-size: 14px;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        color: var(--text-main);
    }

    .alert-danger {
        border-left: 4px solid #e74c3c;
    }

    .alert-success {
        border-left: 4px solid #2ecc71;
    }

    /* Custom style for the illustration image */
    .illustration-col {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .illustration-col img {
        max-width: 100%;
        height: auto;
        opacity: 0.9;
        border-radius: 20px;
    }
  </style>
</head>

<body>

  <div class="form-wrapper">
    <div class="container">
      <div class="card">
        <div class="card-body">

          <div class="row align-items-center">
            <div class="col-lg-6 illustration-col">
              <figure class="m-0">
                <img class="img-fluid d-none d-lg-inline" src="/login-style/dist/images/background_login.png" alt="Login Illustration">
              </figure>
            </div>
            <div class="col-lg-5 offset-lg-1">
              <div class="logo text-center">
                <img src="/dist/img/logo-bkk-no-margin.png" alt="logo bkk" height="50">
                <img src="/dist/img/LOGO_KARANTINA.png" alt="logo karantina" height="50">
              </div>
              <div class="my-4 text-center">
                <h5 class="font-weight-bold mb-3">Balai Kekarantinaan Kesehatan<br>Kelas I Bandung</h5>
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
              <form action="/auth/v2" method="post">
                <div class="form-group mb-4">
                  <div class="form-icon-wrapper">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan NIP Pengguna" autofocus required>
                    <i class="form-icon-left mdi mdi-account"></i>
                  </div>
                </div>
                <div class="form-group mb-4">
                  <div class="form-icon-wrapper">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>
                    <i class="form-icon-left mdi mdi-lock"></i>
                    <a href="#" class="form-icon-right password-show-hide" title="Hide or show password">
                      <i class="mdi mdi-eye"></i>
                    </a>
                  </div>
                </div>
                <div class="form-group mt-5">
                  <button class="btn btn-primary btn-block">Masuk ke Sistem</button>
                </div>
                <hr class="my-4">
                <div class="social-links text-center">
                  <span class="small font-weight-medium">
                    &copy; <?= date('Y'); ?> IT Support BKK Kelas I Bandung
                  </span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Jquery -->
  <script src="/login-style/dist/vendor/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="/login-style/dist/vendor/bootstrap-4.5.3/js/bootstrap.min.js"></script>
  <!-- Latform scripts -->
  <script src="/login-style/dist/js/latform.min.js"></script>
  <script src="/js/inspectElement.js"></script>
</body>

</html>