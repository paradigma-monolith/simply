<!--<!DOCTYPE html>-->
<!--<html lang="en">-->

<!--<head>-->
<!--  <meta charset="UTF-8">-->
<!--  <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--  <title>BKK Bandung</title>-->
<!--   Bootstrap -->
<!--  <link rel="stylesheet" href="/login-style/dist/vendor/bootstrap-4.5.3/css/bootstrap.min.css" type="text/css">-->
<!--   Material design icons -->
<!--  <link rel="stylesheet" href="/login-style/dist/icons/material-design-icons/css/mdi.min.css" type="text/css">-->
<!--   Google font -->
<!--  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300&display=swap" rel="stylesheet">-->
<!--   Latform styles -->
<!--  <link rel="stylesheet" href="/login-style/dist/css/latform-style-9.min.css" type="text/css">-->
<!--</head>-->

<!--<body>-->

<!--  <div class="form-wrapper">-->
<!--    <div class="container">-->
<!--      <div class="card">-->
<!--        <div class="card-body">-->
            
<!--          <div class="row">-->
<!--            <div class="col-lg-6">-->
<!--              <a href="https://wbs.kemkes.go.id/" target="_blank" class="text-center">-->
<!--              <figure>-->
<!--                <img class="img-fluid d-none d-lg-inline" src="/login-style/dist/images/background_login.png" alt="">-->
<!--                <img class="img-fluid d-none d-lg-inline" src="/dist/img/wbs.png" alt="">-->
<!--              </figure>-->
<!--              <h5 class="mb-0">>>> Halaman Sistem Pelaporan Pelanggaran <<<</h5>-->
<!--              </a>-->
<!--            </div>-->
<!--            <div class="col-lg-4 offset-lg-1">-->
<!--              <div class="logo text-center">-->
<!--                <img src="/dist/img/logo-bkk-no-margin.png" alt="logo" height="40">-->
<!--                <img src="/dist/img/LOGO_KARANTINA.png" alt="logo" height="40">-->
<!--              </div>-->
<!--              <div class="my-5 text-center">-->
<!--                <h5 class="font-weight-bold mb-3">Balai Kekarantinaan Kesehatan Kelas I Bandung</h5>-->
<!--                <?php if (session()->getFlashdata('pesan')) : ?>-->
<!--                  <div class="alert alert-success" role="alert">-->
<!--                    <?= session()->getFlashdata('pesan'); ?>-->
<!--                  </div>-->
<!--                <?php elseif (session()->getFlashdata('error')) : ?>-->
<!--                  <div class="alert alert-danger" role="alert">-->
<!--                    <?= session()->getFlashdata('error'); ?>-->
<!--                  </div>-->
<!--                <?php elseif (session()->getFlashdata('info')) : ?>-->
<!--                  <div class="alert alert-info" role="alert">-->
<!--                    <?= session()->getFlashdata('info'); ?>-->
<!--                  </div>-->
<!--                <?php endif; ?>-->
<!--              </div>-->
<!--              <form action="/auth" method="post">-->
<!--                <input type="hidden" name="login" value="login2"> -->
<!--                <div class="form-group">-->
<!--                  <div class="form-icon-wrapper">-->
<!--                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan NIP Pengguna" autofocus required>-->
<!--                    <i class="form-icon-left mdi mdi-account"></i>-->
<!--                  </div>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                  <div class="form-icon-wrapper">-->
<!--                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>-->
<!--                    <i class="form-icon-left mdi mdi-lock"></i>-->
<!--                    <a href="#" class="form-icon-right password-show-hide" title="Hide or show password">-->
<!--                      <i class="mdi mdi-eye"></i>-->
<!--                    </a>-->
<!--                  </div>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                  <button class="btn btn-primary btn-block">Masuk</button>-->
<!--                </div>-->
<!--                <hr>-->
<!--                <div class="social-links justify-content-center">-->
<!--                  <span class="small">-->
<!--                    Copyright @ IT Support BKK Kelas I Bandung-->
<!--                  </span>-->
<!--                </div>-->
<!--              </form>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--   Jquery -->
<!--  <script src="/login-style/dist/vendor/jquery.min.js"></script>-->
<!--   Bootstrap -->
<!--  <script src="/login-style/dist/vendor/bootstrap-4.5.3/js/bootstrap.min.js"></script>-->
<!--   Latform scripts -->
<!--  <script src="/login-style/dist/js/latform.min.js"></script>-->
<!--  <script src="/js/inspectElement.js"></script>-->
<!--</body>-->

<!--</html>-->


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BKK Bandung - Futuristic Login</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="/login-style/dist/vendor/bootstrap-4.5.3/css/bootstrap.min.css" type="text/css">
  <!-- Material design icons -->
  <link rel="stylesheet" href="/login-style/dist/icons/material-design-icons/css/mdi.min.css" type="text/css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #00d4aa 0%, #00b4d8 100%);
      --secondary-gradient: linear-gradient(135deg, #06ffa5 0%, #00d4aa 100%);
      --dark-gradient: linear-gradient(135deg, #0a0e27 0%, #16213e 50%, #0f3460 100%);
      --glass-bg: rgba(6, 255, 165, 0.08);
      --glass-border: rgba(0, 212, 170, 0.3);
      --neon-green: #06ffa5;
      --neon-cyan: #00d4aa;
      --neon-blue: #00b4d8;
      --text-light: #e8f5e8;
      --fresh-mint: #40e0d0;
      --electric-blue: #0ff0fc;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Exo 2', sans-serif;
      background: var(--dark-gradient);
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
    }

    /* Complex Tech Animation Background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 15% 85%, rgba(6, 255, 165, 0.4) 0%, transparent 40%),
        radial-gradient(circle at 85% 15%, rgba(0, 212, 170, 0.3) 0%, transparent 45%),
        radial-gradient(circle at 50% 50%, rgba(0, 180, 216, 0.2) 0%, transparent 50%),
        linear-gradient(45deg, rgba(6, 255, 165, 0.1) 0%, transparent 30%, rgba(0, 212, 170, 0.1) 70%, transparent 100%);
      animation: complexTechShift 25s ease-in-out infinite;
      z-index: -1;
    }

    body::after {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        repeating-linear-gradient(90deg, transparent, transparent 98px, rgba(6, 255, 165, 0.03) 100px),
        repeating-linear-gradient(0deg, transparent, transparent 98px, rgba(0, 212, 170, 0.03) 100px);
      animation: gridMove 30s linear infinite;
      z-index: -1;
    }

    @keyframes complexTechShift {
      0% { transform: scale(1); filter: hue-rotate(0deg); }
      25% { transform: scale(1.1); filter: hue-rotate(90deg); }
      50% { transform: scale(0.9); filter: hue-rotate(180deg); }
      75% { transform: scale(1.05); filter: hue-rotate(270deg); }
      100% { transform: scale(1); filter: hue-rotate(360deg); }
    }

    @keyframes gridMove {
      0% { transform: translate(0, 0); }
      100% { transform: translate(100px, 100px); }
    }



    .form-wrapper {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      z-index: 2;
    }

    .login-container {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
      max-width: 450px;
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: conic-gradient(from 0deg, transparent, var(--neon-green), var(--neon-cyan), transparent, var(--electric-blue), transparent);
      z-index: -1;
    }

    .login-container::after {
      content: '';
      position: absolute;
      top: 2px;
      left: 2px;
      right: 2px;
      bottom: 2px;
      background: var(--dark-gradient);
      border-radius: 18px;
      z-index: -1;
    }



    .logo-section {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .logo-container img {
      height: 50px;
      filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
      transition: all 0.3s ease;
    }

    .logo-container img:hover {
      transform: scale(1.1);
      filter: drop-shadow(0 0 20px var(--neon-green));
    }

    .title {
      font-family: 'Orbitron', monospace;
      font-size: 1.5rem;
      font-weight: 700;
      background: linear-gradient(45deg, var(--neon-green), var(--neon-cyan), var(--electric-blue));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
      text-shadow: 0 0 30px rgba(6, 255, 165, 0.6);
      animation: titleGlow 3s ease-in-out infinite alternate;
    }

    @keyframes titleGlow {
      0% { filter: brightness(1); }
      100% { filter: brightness(1.2); }
    }

    .subtitle {
      color: var(--text-light);
      font-size: 0.9rem;
      opacity: 0.8;
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    .input-container {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 15px 50px 15px 20px;
      color: var(--text-light);
      font-size: 16px;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .form-control:focus {
      background: rgba(6, 255, 165, 0.08);
      border-color: var(--neon-green);
      box-shadow: 0 0 20px rgba(6, 255, 165, 0.4), inset 0 0 10px rgba(0, 212, 170, 0.2);
      color: white;
      outline: none;
    }

    .form-control::placeholder {
      color: rgba(224, 230, 237, 0.6);
    }

    .input-icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--neon-cyan);
      font-size: 18px;
      z-index: 2;
      text-shadow: 0 0 8px var(--neon-green);
    }

    .password-toggle {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .password-toggle:hover {
      color: var(--neon-green);
      transform: translateY(-50%) scale(1.1);
      text-shadow: 0 0 12px var(--neon-green);
    }

    .btn-login {
      background: var(--primary-gradient);
      border: none;
      border-radius: 12px;
      padding: 15px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      width: 100%;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(6, 255, 165, 0.4), 0 0 30px rgba(0, 212, 170, 0.3);
    }

    .alert {
      border-radius: 12px;
      border: none;
      backdrop-filter: blur(10px);
      margin-bottom: 20px;
    }

    .alert-success {
      background: rgba(6, 255, 165, 0.15);
      color: #d4f6ed;
      border: 1px solid rgba(6, 255, 165, 0.4);
      box-shadow: 0 0 15px rgba(6, 255, 165, 0.2);
    }

    .alert-danger {
      background: rgba(255, 99, 132, 0.15);
      color: #ffe6ea;
      border: 1px solid rgba(255, 99, 132, 0.4);
      box-shadow: 0 0 15px rgba(255, 99, 132, 0.2);
    }

    .alert-info {
      background: rgba(0, 180, 216, 0.15);
      color: #e0f7ff;
      border: 1px solid rgba(0, 180, 216, 0.4);
      box-shadow: 0 0 15px rgba(0, 180, 216, 0.2);
    }

    .copyright {
      text-align: center;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: rgba(224, 230, 237, 0.6);
      font-size: 0.85rem;
    }

    /* Floating WBS Button */
    .wbs-floating-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 60px;
      height: 60px;
      background: var(--secondary-gradient);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 25px rgba(241, 147, 251, 0.4);
      cursor: pointer;
      transition: all 0.3s ease;
      z-index: 1000;
      text-decoration: none;
      border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .wbs-floating-btn:hover {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 12px 35px rgba(241, 147, 251, 0.6);
      text-decoration: none;
    }

    .wbs-floating-btn i {
      color: white;
      font-size: 24px;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    .wbs-tooltip {
      position: absolute;
      right: 70px;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 12px;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .wbs-floating-btn:hover .wbs-tooltip {
      opacity: 1;
      visibility: visible;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .login-container {
        padding: 30px 20px;
        margin: 20px;
      }
      
      .title {
        font-size: 1.3rem;
      }
      
      .wbs-floating-btn {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
      }
      
      .wbs-floating-btn i {
        font-size: 20px;
      }
    }
  </style>
</head>

<body>


  <!-- Tech Grid Lines -->
  <div class="tech-grid">
    <div class="grid-line horizontal" style="top: 20%; animation-delay: 0s;"></div>
    <div class="grid-line horizontal" style="top: 40%; animation-delay: 2s;"></div>
    <div class="grid-line horizontal" style="top: 60%; animation-delay: 4s;"></div>
    <div class="grid-line horizontal" style="top: 80%; animation-delay: 6s;"></div>
    <div class="grid-line vertical" style="left: 20%; animation-delay: 1s;"></div>
    <div class="grid-line vertical" style="left: 40%; animation-delay: 3s;"></div>
    <div class="grid-line vertical" style="left: 60%; animation-delay: 5s;"></div>
    <div class="grid-line vertical" style="left: 80%; animation-delay: 7s;"></div>
  </div>

  <div class="form-wrapper">
    <div class="login-container">
      <!-- Logo Section -->
      <div class="logo-section">
        <div class="logo-container">
          <img src="/dist/img/logo-bkk-no-margin.png" alt="BKK Logo">
          <img src="/dist/img/LOGO_KARANTINA.png" alt="Karantina Logo">
        </div>
        <h1 class="title">SIMPLY</h1>
        <p class="subtitle">Sistem Informasi Manajemen Pelayanan</p>
      </div>

      <!-- Alert Messages -->
      <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success" role="alert">
          <i class="fas fa-check-circle me-2"></i>
          <?= session()->getFlashdata('pesan'); ?>
        </div>
      <?php elseif (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <?= session()->getFlashdata('error'); ?>
        </div>
      <?php elseif (session()->getFlashdata('info')) : ?>
        <div class="alert alert-info" role="alert">
          <i class="fas fa-info-circle me-2"></i>
          <?= session()->getFlashdata('info'); ?>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="/auth" method="post">
        <input type="hidden" name="login" value="login2">
        
        <div class="form-group">
          <div class="input-container">
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan NIP Pengguna" autofocus required>
            <i class="fas fa-user input-icon"></i>
          </div>
        </div>

        <div class="form-group">
          <div class="input-container">
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Kata Sandi" required>
            <i class="fas fa-eye password-toggle input-icon" onclick="togglePassword()"></i>
          </div>
        </div>

        <div class="form-group">
          <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>
            Masuk Sistem
          </button>
        </div>
      </form>

      <!-- Copyright -->
      <div class="copyright">
        <i class="fas fa-code me-1"></i>
        Copyright © <?= date('Y') ?> IT BKK Kelas I Bandung
      </div>
    </div>
  </div>

  <!-- Floating WBS Button -->
  <a href="https://wbs.kemkes.go.id/" target="_blank" class="wbs-floating-btn">
    <i class="fas fa-bullhorn"></i>
    <div class="wbs-tooltip">Whistle Blowing System</div>
  </a>
  <!-- Scripts -->
  <script src="/login-style/dist/vendor/jquery.min.js"></script>
  <script src="/login-style/dist/vendor/bootstrap-4.5.3/js/bootstrap.min.js"></script>
  <script src="/login-style/dist/js/latform.min.js"></script>
  <script src="/js/inspectElement.js"></script>
  
  <script>
    // Password toggle functionality
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.querySelector('.password-toggle');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }



    // Add glow effect on form focus
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.boxShadow = '0 0 25px rgba(6, 255, 165, 0.4), 0 0 50px rgba(0, 212, 170, 0.2)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.boxShadow = 'none';
      });
    });

    // Add click effect to login button
    document.querySelector('.btn-login').addEventListener('click', function(e) {
      const ripple = document.createElement('span');
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top - size / 2;
      
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = x + 'px';
      ripple.style.top = y + 'px';
      ripple.classList.add('ripple');
      
      this.appendChild(ripple);
      
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  </script>
  
  <style>
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(6, 255, 165, 0.7);
      transform: scale(0);
      animation: ripple-animation 0.6s linear;
      pointer-events: none;
    }
    
    @keyframes ripple-animation {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    /* Tech Grid Animation */
    .tech-grid {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }

    .grid-line {
      position: absolute;
      background: linear-gradient(90deg, transparent, var(--neon-green), transparent);
      opacity: 0;
      animation: gridPulse 8s ease-in-out infinite;
    }

    .grid-line.horizontal {
      width: 100%;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent);
    }

    .grid-line.vertical {
      width: 1px;
      height: 100%;
      background: linear-gradient(0deg, transparent, var(--electric-blue), transparent);
    }

    @keyframes gridPulse {
      0%, 100% { opacity: 0; transform: scaleX(0); }
      50% { opacity: 0.6; transform: scaleX(1); }
    }

    .grid-line.vertical {
      animation-name: gridPulseVertical;
    }

    @keyframes gridPulseVertical {
      0%, 100% { opacity: 0; transform: scaleY(0); }
      50% { opacity: 0.6; transform: scaleY(1); }
    }
  </style>
</body>

</html>