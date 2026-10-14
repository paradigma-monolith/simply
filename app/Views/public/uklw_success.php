<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            margin: 20px;
        }
        .logo-section {
            margin-bottom: 30px;
        }
        .logo-section img {
            margin: 0 10px;
            max-height: 60px;
            width: auto;
        }
        @media (max-width: 768px) {
            .logo-section img {
                max-height: 45px;
                margin: 5px;
            }
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 30px;
            animation: bounceIn 1s ease-in-out;
        }
        .success-title {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .success-message {
            color: #6c757d;
            font-size: 1.2rem;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .info-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            border-left: 5px solid #667eea;
        }
        .info-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .info-text {
            color: #6c757d;
            margin-bottom: 10px;
        }
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="/dist/img/LOGO_KARANTINA.png" alt="Logo Karantina Kesehatan">
            <img src="/dist/img/logo-bkk-no-margin.png" alt="Logo BKK Bandung">
        </div>
        
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="success-title">Pendaftaran Berhasil!</h1>
        
        <p class="success-message">
            Terima kasih telah mengisi formulir pendaftaran kesehatan UKLW. 
            Data Anda telah berhasil disimpan dan akan diproses oleh tim medis kami.
        </p>

        <div class="info-card">
            <h5 class="info-title"><i class="fas fa-info-circle text-primary"></i> Informasi Penting</h5>
            <p class="info-text"><strong>✓</strong> Data Anda telah tersimpan dengan aman</p>
            <p class="info-text"><strong>✓</strong> Tim medis akan meninjau hasil skrining Anda</p>
            <p class="info-text"><strong>✓</strong> Anda akan dihubungi jika diperlukan tindak lanjut</p>
            <p class="info-text"><strong>✓</strong> Simpan nomor identitas Anda untuk referensi</p>
        </div>

        <div class="mt-4">
            <a href="/public/uklw" class="btn btn-primary">
                <i class="fas fa-plus"></i> Daftar Lagi
            </a>
            <a href="/" class="btn btn-outline-secondary ms-3">
                <i class="fas fa-home"></i> Beranda
            </a>
        </div>

        <div class="mt-4">
            <small class="text-muted">
                <i class="fas fa-shield-alt"></i> Data Anda dilindungi dan hanya akan digunakan untuk keperluan medis
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
