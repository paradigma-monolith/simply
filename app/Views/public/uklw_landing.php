<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pelayanan Terpadu Upaya Kesehatan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }
        .btn-hero {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 10px;
        }
        .btn-hero:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .features-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #667eea;
        }
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .feature-desc {
            color: #6c757d;
            line-height: 1.6;
        }
        .info-section {
            padding: 80px 0;
        }
        .info-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 50px;
            color: #2c3e50;
        }
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            border-left: 5px solid #667eea;
        }
        .info-item i {
            font-size: 1.5rem;
            color: #667eea;
            margin-right: 20px;
        }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        .logo-section {
            margin-bottom: 30px;
        }
        .logo-section img {
            margin: 0 10px;
            max-height: 80px;
            width: auto;
        }
        @media (max-width: 768px) {
            .logo-section img {
                max-height: 60px;
                margin: 5px;
            }
            .hero-title {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <!-- Logo Section -->
            <div class="logo-section">
                <img src="/dist/img/LOGO_KARANTINA.png" alt="Logo Karantina Kesehatan">
                <img src="/dist/img/logo-bkk-no-margin.png" alt="Logo BKK Bandung">
            </div>
            <h1 class="hero-title">Formulir Pelayanan Terpadu Upaya Kesehatan</h1>
            <p class="hero-subtitle">
                Balai Kekarantinaan Kesehatan Kelas I Bandung<br>
            </p>
            <a href="/public/uklw" class="btn-hero">
                <i class="fas fa-clipboard-list"></i> Mulai Pengisian
            </a>
            <a href="#info" class="btn-hero">
                <i class="fas fa-info-circle"></i> Informasi
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h4 class="feature-title">Pendaftaran Pasien</h4>
                        <p class="feature-desc">
                            Isi data diri lengkap dengan identitas, alamat, dan informasi kontak untuk pendaftaran
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-lungs"></i>
                        </div>
                        <h4 class="feature-title">Skrining TBC</h4>
                        <p class="feature-desc">
                            Deteksi dini gejala Tuberkulosis melalui serangkaian pertanyaan medis
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-virus"></i>
                        </div>
                        <h4 class="feature-title">Skrining HIV</h4>
                        <p class="feature-desc">
                            Evaluasi faktor risiko HIV untuk pencegahan dan deteksi dini
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="feature-title">Faktor Risiko PTM</h4>
                        <p class="feature-desc">
                            Penilaian risiko Penyakit Tidak Menular seperti diabetes, hipertensi, dan jantung
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="info-section" id="info">
        <div class="container">
            <h2 class="info-title">Informasi Penting</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Waktu Pengisian:</strong><br>
                            Formulir dapat diisi kapan saja selama 24 jam
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-mobile-alt"></i>
                        <div>
                            <strong>Akses Mobile:</strong><br>
                            Formulir dapat diakses melalui smartphone atau tablet
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-file-medical"></i>
                        <div>
                            <strong>Dokumen yang Diperlukan:</strong><br>
                            NIK/Paspor/No. Porsi dan informasi kontak darurat
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="info-item">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Keamanan Data:</strong><br>
                            Data Anda dijamin aman dan hanya digunakan untuk keperluan medis
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-user-md"></i>
                        <div>
                            <strong>Tindak Lanjut:</strong><br>
                            Tim medis akan menghubungi jika diperlukan pemeriksaan lebih lanjut
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-question-circle"></i>
                        <div>
                            <strong>Bantuan:</strong><br>
                            Hubungi 
                            balaikarkesbandung@kemkes.go.id
                            (022) 4219305 
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="/public/uklw" class="btn btn-primary btn-lg">
                    <i class="fas fa-play"></i> Mulai Pengisian Formulir
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> IT BKK Kelas I Bandung. Semua hak dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
