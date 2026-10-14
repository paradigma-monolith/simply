<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .error-container {
            margin: 100px auto;
            max-width: 500px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f8f8;
        }
        h1 {
            color: #d9534f;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Error</h1>
        <p><?= $error ?? 'Terjadi kesalahan saat memproses data.' ?></p>
        <p>Silahkan hubungi administrator sistem.</p>
    </div>
</body>
</html>
