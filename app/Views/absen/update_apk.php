<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Aplikasi Diperlukan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .update-container {
            text-align: center;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 90%;
        }
        .update-icon {
            font-size: 60px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .btn-update {
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="update-container">
    <i class="fas fa-exclamation-triangle update-icon"></i>
    <h4><b>Versi Aplikasi Usang</b></h4>
    <p class="text-muted mt-3">
        Mohon maaf, Anda menggunakan versi aplikasi (APK) yang lama atau mengakses melalui peramban yang tidak diizinkan. 
        <br><br>
        Silakan unduh dan pasang aplikasi <b>Simply App versi terbaru (v2.0)</b> untuk dapat mengakses menu ini.
    </p>
    <button class="btn btn-primary btn-update" onclick="copyLink()">
        <i class="fas fa-copy"></i> Copy Link Download APK
    </button>
</div>

<script>
function copyLink() {
    // Teks link yang akan dicopy
    var link = "https://simply.bkkbandung.net/apk/simply.apk";
    
    // Menggunakan API Clipboard jika tersedia
    if (navigator.clipboard) {
        navigator.clipboard.writeText(link).then(function() {
            alert("Link download berhasil dicopy!\nSilakan paste di browser Anda.");
        }).catch(function(err) {
            fallbackCopyTextToClipboard(link);
        });
    } else {
        fallbackCopyTextToClipboard(link);
    }
}

function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Hindari scroll ke bawah
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        var successful = document.execCommand('copy');
        if (successful) {
            alert("Link download berhasil dicopy!\nSilakan paste di browser Anda.");
        } else {
            alert("Gagal copy link. Silakan copy manual: " + text);
        }
    } catch (err) {
        alert("Browser Anda tidak mendukung fitur copy otomatis. Silakan copy manual: " + text);
    }

    document.body.removeChild(textArea);
}
</script>

</body>
</html>