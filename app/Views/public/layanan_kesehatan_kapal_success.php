<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }
        .success-icon {
            color: #28a745;
            font-size: 80px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <i class="fas fa-check-circle success-icon"></i>
        <h2 class="mb-3">Pengajuan Berhasil!</h2>
        <p class="text-muted mb-4">Terima kasih, permohonan pengajuan layanan kesehatan kapal Anda telah berhasil dikirim dan akan segera diproses oleh petugas kami.</p>
        <a href="/public/layanan_kesehatan_kapal" class="btn btn-primary">Kembali ke Form</a>
    </div>
</body>
</html>
