<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Consent Forms</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0; /* Tambahan padding untuk mobile */
        }
        .home-container {
            max-width: 900px;
            width: 100%;
            padding: 15px; /* Mengurangi padding di mobile */
        }
        .logos {
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
        .logos img {
            max-height: 80px; /* Diperkecil agar pas di mobile */
            object-fit: contain;
        }
        /* Penyesuaian khusus layar kecil */
        @media (max-width: 576px) {
            .logos img {
                max-height: 60px;
            }
            .header-title h2 {
                font-size: 1.5rem;
            }
            .header-title p {
                font-size: 0.9rem;
            }
        }
        .card-menu {
            transition: transform 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .card-menu:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .card-menu .card-body {
            text-align: center;
            padding: 30px 15px; /* Sedikit lebih padat */
        }
        .card-menu i {
            font-size: 3.5rem;
            color: #007bff;
            margin-bottom: 20px;
        }
        .card-menu h5 {
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.4;
        }
        a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="home-container">
    <div class="logos">
        <img src="<?= base_url('dist/img/logo-bkk-no-margin.png') ?>" alt="Logo BKK Bandung">
        <img src="<?= base_url('dist/img/LOGO_KARANTINA.png') ?>" alt="Logo Karantina">
    </div>

    <div class="text-center mb-5 header-title">
        <h2>Travel Consent Form</h2>
        <p class="text-muted">Balai Kekarantinaan Kesehatan Kelas I Bandung</p>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="<?= base_url('travelconsent/public_aircraft') ?>" class="card card-menu h-100">
                <div class="card-body">
                    <i class="fas fa-plane-departure"></i>
                    <h5>Aircraft Sanitation Inspection</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="<?= base_url('travelconsent/public_sanitasi') ?>" class="card card-menu h-100">
                <div class="card-body">
                    <i class="fas fa-ship"></i>
                    <h5>Sanitasi Kapal (Ship Sanitation)</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="<?= base_url('travelconsent/public_alat') ?>" class="card card-menu h-100">
                <div class="card-body">
                    <i class="fas fa-medkit"></i>
                    <h5>Alat Kesehatan Kotak P3K & Emergensi</h5>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="<?= base_url('travelconsent/public_obat') ?>" class="card card-menu h-100">
                <div class="card-body">
                    <i class="fas fa-pills"></i>
                    <h5>Obat Kotak P3K & Emergensi</h5>
                </div>
            </a>
        </div>
    </div>

    <div class="text-center mt-5">
        <p class="text-muted" style="font-size: 0.9em;">Copyright &copy; <?= date('Y') ?> IT Support Balai Kekarantinaan Kesehatan Kelas I Bandung</p>
    </div>
</div>

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>