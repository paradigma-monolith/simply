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
                <img src="/dist/img/logo-bkk-no-margin.png" alt="Left Logo" height="40">
            </div>
            <div class="logo-right">
                <img src="/dist/img/logo_anti_korupsi_bkk.png" alt="Right Logo 1" height="40">
                <img src="/dist/img/LOGO_KARANTINA.png" alt="Right Logo 2" height="40">
            </div>
        </div>
        <div class="hidden">
            <input type="text" id="dateRangePicker" name="tanggal" placeholder="tanggal" />
            <input type="text" name="penumpang[]" placeholder="penumpang" />
            <input type="text" name="tujuan" placeholder="tujuan" />
            <input type="text" name="keterangan" placeholder="keterangan" />
            <input type="text" name="id" placeholder="id" />
        </div>

        <div class="sticky" style="position: fixed;
                    top: 50%;
                    z-index: 11;
                    background-color: rgb(243 254 255 / 50%);
                    font-size: 9pt;
                    border-radius: 10px;
                    padding: 8px 12px 8px 50px;
                    margin-left: 10px;
                    transform: translateY(-50%);
                    backdrop-filter: blur(10px);
                    font-weight: 700;
                    box-shadow: 4px 4px rgb(165 167 167), inset -1px 2px rgb(165 167 167 / 40%), inset 2px -1px rgb(165 167 167 / 40%);
                    min-width: 195px;
                    max-width: 195px;
                    left: -190px;">
            <!-- left: -45px -->
            <p style="border-bottom: #5e6265 solid 2px;" class="m-0 text-truncate">Tanggal: <i style="font-weight: 400;" class="p-tanggal"></i></p>
            <p class="m-0 text-truncate">Tujuan: <i style="font-weight: 400;" class="p-tujuan"></i></p>
            <p class="m-0 text-truncate">Kegiatan: <i style="font-weight: 400;" class="p-keterangan"></i></p>
            <p class="m-0 text-truncate">Peminjam: <i style="font-weight: 400;" class="p-peminjam"></i></p>
            <p class="m-0 text-truncate">Banyak Penumpang: <i style="font-weight: 400;" class="p-totalpenumpang"></i></p>
        </div>

        <nav>
            <div class="navigation">
                <ul>
                    <li class="list btn-page-1" style="--clr:#d7de23">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                        </a>
                    </li>
                    <li class="list btn-page-2 active" style="--clr:#4CAF50">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="document-outline"></ion-icon>
                            </span>
                        </a>
                    </li>
                    <li class="list btn-page-3" style="--clr:#2196F3">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="car-outline"></ion-icon>
                            </span>
                        </a>
                    </li>
                    <li class="list btn-page-4" style="--clr:#FFC107">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="folder-open-outline"></ion-icon>
                            </span>
                        </a>
                    </li>
                </ul>
                <div class="indicator"></div>
            </div>
        </nav>

        <div class="pemilihanTanggal visible d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <?= $this->include('kendaraan_kantor/get_riwayat_pengajuan_verifikator'); ?>
        </div>

        <div class="menuUtamaPemilihan container hidden">
            <div class="container text-center py-3">
                <h3>Kendaraan Tersedia</h3>
                <h5 class="tgl-peminjaman">06 September 2024</h5>
                <hr>
            </div>
            <!--Produk Grid -->
            <div class="container daftarPilihanKendaraan">

            </div>

        </div>
        <div class="menuUtamaRiwayat container hidden">

        </div>
        <div class="my-5">&nbsp;</div>
    </div>
    <script>
        $('.btn-page-1').on('click', function() {
            Swal.fire({
                title: 'Menu utama!',
                text: "Dengan melanjutkannya anda akan dipindahkan ke menu utama!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Tidak, tetap disini!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to another page if confirmed
                    window.location.href = '/menu'; // Replace with your target URL
                }
            });
        });

        var kendaraanPilihan = "";
        var platPilihan = "";
        let isOpenSticky = false;
        $(document).ready(function() {
            $('.select2').select2({
                tags: true,
                closeOnSelect: false,
                templateSelection: function(selection) {
                    var $option = $(selection.element);

                    if ($option.prop('disabled')) {
                        selection.locked = true;
                    }
                    return selection.text;
                }
            }).on('select2:unselecting', function(e) {
                if ($(e.params.args.data.element).prop('disabled')) {
                    e.preventDefault();
                }
            });


            $('.sticky').on('click', function() {
                getStickyNote()
            });
        });

        function getStickyNote() {
            if (isOpenSticky) {
                $('.sticky').animate({
                    left: '-190px'
                }, 300);
            } else {
                $('.sticky').animate({
                    left: '-45px'
                }, 300);
            }
            isOpenSticky = !isOpenSticky;
        }

        // Event listener untuk btn-page-2 dan btn-page-4
        $(".btn-page-4").click(function() {
            $(".menuUtamaRiwayat").removeClass('hidden');
            $(".menuUtamaRiwayat").addClass('visible');
            $(".pemilihanTanggal").removeClass('visible');
            $(".pemilihanTanggal").addClass('hidden');
            $(".menuUtamaPemilihan").removeClass('visible');
            $(".menuUtamaPemilihan").addClass('hidden');

            $.ajax({
                url: '/kendaraan_kantor_get_riwayat',
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    $('.menuUtamaRiwayat').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
                },
                success: function(response) {
                    if (response.data) {
                        $('.menuUtamaRiwayat').html(response.data)
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });

            getMenuButton($(this));
        });

        $(".btn-page-2").click(function() {
            getMenuButton($(this));
            $(".pemilihanTanggal").addClass('visible')
            $(".pemilihanTanggal").removeClass('hidden')
            $(".menuUtamaPemilihan").addClass('hidden')
            $(".menuUtamaPemilihan").removeClass('visible')
            $(".menuUtamaRiwayat").removeClass('visible')
            $(".menuUtamaRiwayat").addClass('hidden')
        });
        $(".set-data-form-public-transport").on('click', function() {
            var button = $(this);
            var id = $(this).data('id');

            Swal.fire({
                title: "Alasan Menggunakan Kendaraan Umum",
                input: "text",
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: true,
                confirmButtonText: "Kirim",
                cancelButtonText: "Batal",
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value !== "") {
                    var hapusElement = button.closest('.findContainer-1');

                    var formData = {
                        'id_kendaraan_kantor_riwayat': id,
                        'keterangan_verifikator': result.value
                    };

                    $.ajax({
                        url: '/kendaraan_kantor_verifikator_kendaraan_umum',
                        data: formData,
                        method: 'post',
                        dataType: 'json',
                        success: function(response) {
                            if (response.data) {
                                hapusElement.remove();
                                if (response.pesan) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Pesan berhasil disampaikan',
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Data berhasil diperbaharui tetapi pesan gagal dikirimkan'
                                    });
                                }
                            }
                        },
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pesan harus diisikan!'
                    });
                }
            });
        });
        $(".set-data-form").on("click", function() {

            let timerInterval;
            Swal.fire({
                title: "Mengambil data!",
                html: "Memproses data sekitar <b></b> millidetik.",
                timer: 1000,
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                    // console.log("Console Proses");

                    var tanggal = $(this).data("tanggal");
                    var penumpang = $(this).data("penumpang");
                    var tujuan = $(this).data("tujuan");
                    var keterangan = $(this).data("keterangan");
                    var id = $(this).data("id");

                    $("#dateRangePicker").val(tanggal);
                    $('input[name="penumpang[]"]').val(penumpang);
                    $('input[name="tujuan"]').val(tujuan);
                    $('input[name="keterangan"]').val(keterangan);
                    $('input[name="id"]').val(id);

                    let dates = tanggal.split(' - ');
                    let startDate = formatTanggal(dates[0]);
                    let endDate = formatTanggal(dates[1]);

                    if (startDate == endDate) {
                        $(".p-tanggal").text(endDate);
                    } else {
                        $(".p-tanggal").text(startDate + " - " + endDate);
                    }
                    $(".p-tujuan").text(tujuan);
                    $(".p-keterangan").text(keterangan);
                    $(".p-peminjam").text($(this).data("peminjam"));
                    $(".p-totalpenumpang").text($(this).data('totalpenumpang'));
                    kendaraanPilihan = "";
                    platPilihan = "";
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    if (!isOpenSticky) {
                        getStickyNote();
                    }
                    console.log("Finish Console");
                    getMenuButton($(".btn-page-3"));
                    $(".pemilihanTanggal").removeClass('visible')
                    $(".pemilihanTanggal").addClass('hidden')
                    $(".menuUtamaPemilihan").removeClass('hidden')
                    $(".menuUtamaPemilihan").addClass('visible')
                    $(".menuUtamaRiwayat").removeClass('visible')
                    $(".menuUtamaRiwayat").addClass('hidden')
                    cekKendaraan()
                }
            });
        });
        // Event listener untuk btn-page-3 dengan pengecekan data
        $(".btn-page-3").on("click", function() {
            if (checkRequiredData()) {
                getMenuButton($(this));
                $(".pemilihanTanggal").removeClass('visible');
                $(".pemilihanTanggal").addClass('hidden');
                $(".menuUtamaPemilihan").removeClass('hidden');
                $(".menuUtamaPemilihan").addClass('visible');
                $(".menuUtamaRiwayat").removeClass('visible');
                $(".menuUtamaRiwayat").addClass('hidden');
                cekKendaraan()
            } else {
                Swal.fire({
                    icon: "info",
                    title: 'Pilih salah satu data pada menu ke-2',
                    text: 'Anda harus memilih data untuk menentukan tanggal kebutuhan, penumpang, tujuan, dan kegiatan pada menu ke-2 pada halaman ini!'
                });
            }
        });

        // Daftar nama bulan dalam bahasa Indonesia
        const bulanIndonesia = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ];


        function formatTanggal(tanggal) {
            const date = new Date(tanggal);

            const hari = date.getDate();
            const bulan = date.getMonth();
            const tahun = date.getFullYear();

            return `${hari} ${bulanIndonesia[bulan]} ${tahun}`;
        }

        function cekKendaraan() {
            let dateRange = $("#dateRangePicker").val();
            let dates = dateRange.split(' - ');
            let startDate = formatTanggal(dates[0]);
            let endDate = formatTanggal(dates[1]);

            var formData = {
                start: dates[0],
                end: dates[1],
            };

            $.ajax({
                url: "/kendaraan_kantor_get_kendaraan",
                method: 'post',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $(".daftarPilihanKendaraan").html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
                },
                success: function(response) {
                    if (response.data) {
                        console.log(response.red)
                        console.log(response.rea)
                        console.log(response.res)
                        if (startDate === endDate) {
                            $(".tgl-peminjaman").text(endDate);
                        } else {
                            $(".tgl-peminjaman").text(startDate + " - " + endDate);
                        }
                        $(".daftarPilihanKendaraan").html(response.data)
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
                    })
                }
            });
        }

        // Fungsi getMenuButton untuk menangani tombol yang aktif dan mengelola indikator
        function getMenuButton(element) {
            // Hapus semua class 'active' dari tombol dan tambahkan 'active' pada tombol yang diklik
            $(".list").removeClass('active');
            element.addClass('active');

            // Logika tambahan: Perpindahan margin indikator berdasarkan item di dalam class list
            const indicator = document.querySelector('.indicator');
            const index = $(".list").index(element); // Mendapatkan index dari elemen yang diklik

            // Pindahkan margin indikator berdasarkan index
            switch (index) {
                case 0:
                    indicator.style.marginLeft = `${-210}px`;
                    break;
                case 1:
                    indicator.style.marginLeft = `${-69}px`;
                    break;
                case 2:
                    indicator.style.marginLeft = `${69}px`;
                    break;
                case 3:
                    indicator.style.marginLeft = `${210}px`;
                    break;
                default:
                    indicator.style.marginLeft = '0px'; // Default jika tidak ada case yang cocok
            }
        }

        // const element = document.getElementById('animatedElement');
        // element.classList.toggle('visible');
        // element.classList.toggle('hidden');       

        // function cekDate() {
        //     var tanggal = $("#dateRangePicker").val();

        //     if (tanggal != "") {
        //         $(".form-tambahan-keberangkatan").removeClass("hidden");
        //         $(".form-tambahan-keberangkatan").addClass("visible");
        //     } else {
        //         $(".form-tambahan-keberangkatan").removeClass("visible");
        //         $(".form-tambahan-keberangkatan").addClass("hidden");
        //         $('input[name="tujuan"]').val("");
        //         $('input[name="keterangan"]').val("");
        //     }
        // }
        function checkRequiredData() {
            var tanggal = $("#dateRangePicker").val();
            var penumpang = $('input[name="penumpang[]"]').val();
            var tujuan = $('input[name="tujuan"]').val();
            var keterangan = $('input[name="keterangan"]').val();

            if ((tanggal != null && tanggal != "") && (tujuan != null && tujuan != "") && (keterangan != null && keterangan != "")) {
                return true;
            } else {
                return false;
            }
        }
    </script>

    <script>
        $(document).on("click", '.getTheCar', function() {
            var elementIni = $(this);
            cekDuluPemilihan().then(function(kondisi) {
                // if (kondisi) {
                //     Swal.fire({
                //         title: "Anda sudah memilih kendaraan!",
                //         html: "Pilihan " + kendaraanPilihan + " (<strong>" + platPilihan + "</strong>)<br />Pengelolaan data tersedia pada menu SIMPLY jika diperlukan!<hr />Kembali ke menu ke-2?",
                //         icon: "warning",
                //         showCancelButton: true,
                //         confirmButtonColor: "#3085d6",
                //         cancelButtonColor: "#d33",
                //         confirmButtonText: "Ya...",
                //         cancelButtonText: "Tetap disini!",
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             $(".btn-page-2").click();
                //         }
                //     });
                // } else {
                    var kendaraan = elementIni.data('kendaraan');
                    var plat = elementIni.data('plat');

                    Swal.fire({
                        title: "Memilih...",
                        html: "Kendaraan <b>" + kendaraan + "</b> <br />plat nomor <u>" + plat + "</u>?",
                        showCancelButton: true,
                        confirmButtonText: "Yups!",
                        cancelButtonText: "Mau pilih yang lain aja..."
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            var tanggal = $("#dateRangePicker").val();
                            var penumpang = $('input[name="penumpang[]"]').val();
                            var tujuan = $('input[name="tujuan"]').val();
                            var id_kendaraan_kantor_riwayat = $('input[name="id"]').val();
                            var id = elementIni.data("id");

                            var formData = {
                                tanggal: tanggal,
                                penumpang: penumpang,
                                tujuan: tujuan,
                                id_kendaraan_kantor_riwayat: id_kendaraan_kantor_riwayat,
                                id_kendaraan_kantor_daftar: id
                            };

                            $.ajax({
                                url: "/kendaraan_kantor_get_the_car",
                                data: formData,
                                method: 'post',
                                dataType: 'json',
                                beforeSend: function() {
                                    let timerInterval;
                                    Swal.fire({
                                        title: "Pengecekan mobil!",
                                        html: "Akan segera selesai <b></b> milliseconds.",
                                        timer: 50000,
                                        timerProgressBar: true,
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                            const timer = Swal.getPopup().querySelector("b");
                                            timerInterval = setInterval(() => {
                                                timer.textContent = `${Swal.getTimerLeft()}`;
                                            }, 100);
                                        },
                                        willClose: () => {
                                            clearInterval(timerInterval);
                                        }
                                    }).then((result) => {
                                        /* Read more about handling dismissals below */
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log("I was closed by the timer");
                                        }
                                    });
                                },
                                success: function(response) {
                                    if (response.status == "success") {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Selamat, Anda telah berhasil meminjam kendaraan " + kendaraan + " (" + plat + ")",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        $("button").each(function() {
                                            if ($(this).data("id") == id_kendaraan_kantor_riwayat) {
                                                $(this).prop("disabled", true);
                                            }
                                        });

                                        kendaraanPilihan = kendaraan;
                                        platPilihan = plat;
                                    } else if (response.status == "sold") {
                                        Swal.fire({
                                            icon: "warning",
                                            title: "Sepertinya kendaraan " + kendaraan + " (" + plat + ") telah dipilih! Silahkan memilih kendaraan lain.",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Terjadi kesalahan saat menyimpan data, coba lagi!.",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                },
                                complete: function() {

                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    Swal.fire({
                                        text: xhr.status + "\n" + xhr.responseText + "\n" + thrownError
                                    })
                                }
                            });
                        }
                    });
                // }
            });
        });

        function cekDuluPemilihan() {
            let dateRange = $("#dateRangePicker").val();
            let dates = dateRange.split(' - ');
            let startDate = dates[0];
            let endDate = dates[1];

            console.log(startDate);
            console.log(endDate);

            var formData = {
                start: startDate,
                end: endDate
            };

            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: '/kendaraan_kantor_cek_tanggal',
                    data: formData,
                    method: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response.data) {
                            kendaraanPilihan = response.data['nama_kendaraan'];
                            platPilihan = response.data['plat_nomor'];
                            resolve(true);
                        } else {
                            resolve(false);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        reject(false);
                    }
                });
            });
        }

        function updateDisabledOptions(dataDisabled) {
            $('input[name="penumpang[]"] option').each(function() {
                var value = $(this).val();

                // Jika value termasuk dalam dataDisabled, set disabled
                if (dataDisabled.includes(value)) {
                    if ($(this).data('id') == true) {
                        $(this).attr('disabled', true);
                    } else {
                        $(this).attr('disabled', true);
                    }
                } else {
                    // Jika tidak termasuk, aktifkan kembali (disabled: false)
                    if ($(this).data('id') == true) {
                        $(this).attr('disabled', true);
                    } else {
                        $(this).attr('disabled', false);
                    }
                }
            });
        }
    </script>

    <script>
        $(document).on("click", ".batalkan-peminjaman-kendaraan", function() {
            var button = $(this);
            var container = button.closest('.row');
            // container.remove();
            var htmlButton = $(this).html();

            var formData = {
                id: $(this).data('id')
            };

            Swal.fire({
                title: "Ingin membatalkan peminjaman ini?",
                text: "mobil yang dibatalkan dapat dipinjam pada hari yang sudah ditentukan sebelumnya...",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, saya ingin membatalkannya!",
                cancelButtonText: "Tidak jadi membatalkan!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/kendaraan_kantor_batalkan_peminjaman_kendaraan',
                        data: formData,
                        method: 'post',
                        dataType: 'json',
                        beforeSend: function() {
                            button.attr('disabled', true);
                            button.html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
                        },
                        success: function(response) {
                            if (response.data) {
                                container.remove();
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "terimakasih karena telah menggunakan aplikasi.",
                                    icon: "success"
                                });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        },
                        complete: function() {
                            button.attr('disabled', false)
                            button.html(htmlButton)
                        }
                    });
                }
            });

        });

        function loadPenumpang(index = 0) {
            var $penumpangItem = $('#penumpang-list .get-penumpang').eq(index);

            if ($penumpangItem.length === 0) {
                // Semua data telah diproses
                Swal.close()
                return;
            }

            var formData = {
                id: $penumpangItem.data('id')
            };

            $.ajax({
                url: '/kendaraan_kantor/get_penumpang',
                data: formData,
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Memuat data...',
                        showConfirmButton: false,
                        timer: 3000,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                    $("button.btn").attr('disabled', true);
                },
                success: function(response) {
                    if (response.data) {
                        $penumpangItem.html(response.data);
                    } else {
                        $penumpangItem.html('<span class="text-danger">Data tidak ditemukan</span>');
                    }
                },
                complete: function() {
                    $("button.btn").attr('disabled', false);
                    loadPenumpang(index + 1);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    errorLoopingGetPegawai++;
                    if (errorLoopingGetPegawai > 3) {
                        errorLoopingGetPegawai = 0;
                        loadPenumpang(index + 1);
                    } else {
                        loadPenumpang(index);
                    }
                }
            });
        }
    </script>

    <!-- Bootstrap 5.3 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>