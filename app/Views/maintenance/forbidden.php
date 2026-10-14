<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forbidden</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
            color: #fff;
            background: rgba(18, 18, 18, 1);
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .container {
            position: relative;
            width: 90%;
            max-width: 800px;
            padding: 20px;
            border-radius: 15px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            z-index: 1;
            margin: 0 auto;
        }

        .container img {
            width: 100%;
            height: auto;
            max-height: 50vh;
            object-fit: cover;
            border-radius: 10px;
            animation: pulse 8s infinite alternate;
        }

        .message {
            position: relative;
            color: #fff;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
            margin-top: 20px;
        }

        .message h1 {
            font-size: 3em;
            margin: 0;
            animation: fadeIn 2s ease-in-out;
        }

        .message p {
            font-size: 1.5em;
            margin-top: 20px;
            animation: fadeIn 2s ease-in-out 1s;
        }

        .message a {
            display: inline-block;
            margin-top: 20px;
            color: #00bcd4;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2em;
            transition: color 0.3s;
        }

        .message a:hover {
            color: #ff5722;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            from { transform: scale(1); opacity: 0.7; }
            to { transform: scale(1.05); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/dist/img/Error-403.jpg" alt="Forbidden Image" class="animate__animated animate__pulse">
        <div class="message">
            <h1>Access Forbidden</h1>
            <p>Maaf, Anda tidak memiliki izin untuk melihat halaman ini.</p>
            <a href="/home/profil"><strong>> > PROFIL SAYA < <</strong></a>
        </div>
    </div>
</body>
</html>
