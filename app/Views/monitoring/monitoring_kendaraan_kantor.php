<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kendaraan Kantor BKK Bandung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <!-- daterangepicker -->
    <script src="/plugins/moment/moment.min.js"></script>
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Select2 -->
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #toolbarContainer {
            display: none;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            background-color: rgba(96, 192, 208, .7);
            /* Warna dasar dengan transparansi */
            background-image: url('/dist/img/Texturelabs_Grunge_145S.jpg');
            /* Gambar latar belakang */
            background-size: cover;
            /* Menyesuaikan gambar latar belakang agar menutupi seluruh elemen */
            background-position: center;
            /* Memusatkan gambar latar belakang */
            background-blend-mode: overlay;
            /* Menggabungkan gambar latar belakang dengan warna dasar */
        }

        nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 10;
            /*box-shadow: 0px -20px 20px 0px rgb(215 222 35 / 33%);*/
            box-shadow: 0px -20px 20px 0px rgb(7 184 163 / 33%);
        }

        .navigation {
            margin: auto;
            position: relative;
            width: 100%;
            height: 60px;
            /*background: linear-gradient(to bottom, rgba(215, 222, 35, 0.8), rgb(215, 222, 35));*/
            background: linear-gradient(to bottom, rgba(7, 184, 163, 0.8), rgb(7, 184, 163));
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .navigation ul {
            display: flex;
            padding: 0;
            list-style: none;
        }

        .navigation ul li {
            position: relative;
            width: 70px;
            height: 60px;
            z-index: 1;
        }

        .navigation ul li a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
            color: #555;
        }

        .navigation ul li a .icon {
            position: relative;
            display: block;
            width: 55px;
            height: 55px;
            text-align: center;
            line-height: 55px;
            font-size: 1.5em;
            transition: 0.5s;
            border-radius: 50%;
            background: white;
        }

        .navigation ul li.active a .icon {
            /*background: #07b8a3;*/
            background: #d7de23;
            color: white;
        }

        .indicator {
            position: absolute;
            width: 70px;
            height: 5px;
            /*background: #07b8a3;*/
            background: #d7de23;
            bottom: 0;
            /*left: 62px;*/
            margin-left: -69px;
            border-radius: 5px;
            transition: 0.5s;
        }

        .no-style {
            text-decoration: none;
            color: inherit;
            background: none;
            border: none;
            padding: 0;
            margin: 0;
        }

        hr {
            border: none;
            height: 3px;
            width: 150px;
            background-color: #07b8a3;
            margin: auto;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .pemilihanTanggal {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 75vh;
            border: 1px solid #ccc;
            background-color: transparent;
        }

        .top-header {
            position: relative;
            height: 80px;
        }

        .logo-left {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .logo-right {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }

        .logo-right img {
            display: block;
        }

        .hidden {
            opacity: 0;
            visibility: hidden;
            overflow: hidden;
            transform: scale(0.9);
            position: absolute;
            width: 0;
            height: 0;
            transition: opacity 0.5s ease, visibility 0.5s ease, height 0.5s ease;
        }

        .visible {
            opacity: 1;
            visibility: visible;
            position: relative;
            width: auto;
            height: auto;
            transition: opacity 0.5s ease, visibility 0.5s ease, height 0.5s ease;
        }

        .menuUtamaPemilihan {
            margin-top: -40px;
        }

        .block-cursor a,
        .block-cursor button {
            cursor: not-allowed;
        }

        @media (max-width: 768px) {

            /* Atur lebar maksimal untuk perangkat seluler */
            .daterangepicker:after {
                top: 0;
            }

            .daterangepicker {
                transform: translateY(-45%) !important;
            }

            .daterangepicker:before {
                top: 0;
                border: no-border;
            }
        }
    </style>
</head>

<body>
    <div>
        <div class="top-header">
            <div class="logo-left">
                <img src="/dist/img/logo-bkk-no-margin.png" alt="Left Logo" height="80">
            </div>
            <div class="logo-right">
                <img src="/dist/img/logo_anti_korupsi_bkk.png" alt="Right Logo 1" height="80">
                <img src="/dist/img/LOGO_KARANTINA.png" alt="Right Logo 2" height="80">
            </div>
        </div>

        <div class="menuUtamaRiwayat container-fluid">

            <div class="container-fluid text-center py-3">
                <h1>Kendaraan yang Akan Digunakan Hari Ini dan Seterusnya</h1>
                <hr>
            </div>
            <!--Produk Grid -->
            <div class="container-fluid daftarKendaraan">

            </div>
        </div>
        <div class="my-5">&nbsp;</div>
    </div>
    <script>
    let changeTime = null;
    $(document).ready(function () {
        getKendaraan();
        setInterval(function() {
          getKendaraan()
        }, 2000);
    });
    function getKendaraan() {

        $.ajax({
            url: '/monitoring/kendaraan_kantor_get_riwayat',
            method: 'post',
            dataType: 'json',
            beforeSend: function() {
                // $('.daftarKendaraan').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
            },
            success: function(response) {
                if (response.data) {
                    if(changeTime != response.variable) {
                        $('.daftarKendaraan').html(response.data);
                        changeTime = response.variable
                    }
                }
            },
        });
    };

    </script>

    <!-- Bootstrap 5.3 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>