<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Main Menu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
#toolbarContainer {
    display: none;
}
.menu-container {
    width: 100vw;
    height: 100vh; /* Mengatur tinggi container menjadi 100% dari tinggi layar */
    display: flex; /* Menambahkan flexbox untuk mengatur posisi elemen */
    flex-direction: column; /* Mengatur arah elemen menjadi kolom */
    justify-content: center; /* Memusatkan elemen secara vertikal */
    align-items: center; /* Memusatkan elemen secara horizontal */
}

.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 33.33vh; /* Mengatur tinggi logo-container menjadi sepertiga dari tinggi layar */
}

.logo {
    width: 250px;
    height: 200px;
    background-image: url('https://simply.bkkbandung.net/dist/img/simply-v2.png');
    background-size: contain; 
    background-position: center;
    background-repeat: no-repeat;
}

.menu-btn {
    display: block;
    width: 200px;
    height: 50px;
    margin: 4px; /* Menghapus margin untuk menghilangkan jarak di bawah tombol */
    text-align: center;
    line-height: 50px;
    background-color: #007bff;
    color: white;
    border-radius: 10px;
    font-weight: bold;
    text-decoration: none;
}


</style>
</head>
<body>
<div class="menu-container">
    <div class="logo-container"><a href="/menu-v2" class="logo"></a></div>
    <a href="/auth/login-v2" class="menu-btn">Login</a>
    <?php if($nip != ""): ?>
        <a href="javascript:void()" class="menu-btn btn-maintenance" style="background-color: #71767b">Pinjam Kendaraan Induk</a>
    <?php endif; ?>
    <a href="/manualBooks" class="menu-btn">Manual Books</a>
    <a href="/absensi" class="menu-btn">Absen</a>
</div>
<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(".btn-maintenance").click(function(){
        Swal.fire({
            icon: 'info',
            title: 'Pembaruan Sistem Sedang Berlangsung!',
            html: 'Kami sedang melakukan perubahan signifikan pada proses bisnis dan pengembangan sistem <strong>Pinjam Kendaraan Induk</strong>. Mohon maaf atas ketidaknyamanannya, silakan kembali beberapa hari lagi.'
        })
    });
</script>
</body>
</html>
