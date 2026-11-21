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
            min-height: 100vh;
            padding-bottom: 76px;
            background-color: rgba(96, 192, 208, .7);
            background-image: url('/dist/img/Texturelabs_Grunge_145S.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-blend-mode: overlay;
        }

        nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            box-shadow: 0 -6px 20px rgba(8, 76, 87, 0.22);
        }

        .navigation {
            margin: auto;
            position: relative;
            width: 100%;
            height: 62px;
            background: linear-gradient(135deg, #0b7285, #0d9488);
            display: flex;
            justify-content: center;
            align-items: center;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .navigation ul {
            display: flex;
            padding: 0;
            margin: 0;
            list-style: none;
            width: 100%;
            max-width: 420px;
            justify-content: space-around;
        }

        .navigation ul li {
            position: relative;
            flex: 1;
            height: 58px;
            z-index: 1;
        }

        .navigation ul li a {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.78);
            gap: 2px;
            transition: color 0.2s ease;
        }

        .navigation ul li a .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 30px;
            font-size: 1.25rem;
            transition: all 0.2s ease;
            border-radius: 10px;
            background: transparent;
            color: inherit;
        }

        .navigation ul li a .nav-label {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.2px;
            line-height: 1;
        }

        .navigation ul li.active a {
            color: #ffffff;
        }

        .navigation ul li.active a .icon {
            background: #d7de23;
            color: #0f172a;
            box-shadow: 0 3px 10px rgba(215, 222, 35, 0.35);
        }

        .indicator {
            display: none;
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
            width: 110px;
            background-color: #07b8a3;
            margin: 6px auto 12px;
            opacity: 0.8;
        }

        .pemilihanTanggal {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 150px);
            padding: 10px 0 20px;
            background-color: transparent;
        }

        .top-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            height: 60px;
        }

        .logo-left,
        .logo-right {
            position: static;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .top-header img {
            height: 36px;
            width: auto;
            display: block;
        }

        .hidden {
            opacity: 0;
            visibility: hidden;
            overflow: hidden;
            position: absolute;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .visible {
            opacity: 1;
            visibility: visible;
            position: relative;
            width: auto;
            height: auto;
        }

        .menuUtamaPemilihan,
        .menuUtamaRiwayat {
            margin-top: 0;
            padding-bottom: 24px;
        }

        .block-cursor a,
        .block-cursor button {
            cursor: not-allowed;
        }

        .kk-form-shell {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.13);
            backdrop-filter: blur(10px);
        }

        @media (max-width: 767.98px) {
            .top-header {
                height: 48px;
                padding: 6px 12px;
            }
            .top-header img {
                height: 26px;
            }
            .pemilihanTanggal {
                align-items: flex-start;
                min-height: auto;
                padding: 6px 0 16px;
            }
            .kk-form-shell {
                padding: 14px 14px 16px;
                border-radius: 13px;
            }
            .kk-form-shell h4 {
                font-size: 1.02rem;
            }
            .kk-form-shell .form-control,
            .kk-form-shell .input-group-text {
                font-size: 0.84rem;
            }
            .menuUtamaPemilihan h3,
            .menuUtamaRiwayat h3 {
                font-size: 1.05rem;
                margin-bottom: 2px;
            }
            .daterangepicker {
                max-width: calc(100vw - 16px) !important;
                left: 8px !important;
                right: 8px !important;
                margin: 0 auto;
            }
            .daterangepicker .drp-calendar {
                max-width: 100% !important;
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

        <nav>
            <div class="navigation">
                <ul>
                    <li class="list btn-page-1" style="--clr:#d7de23">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="home-outline"></ion-icon>
                            </span>
                            <span class="nav-label">Menu</span>
                        </a>
                    </li>
                    <li class="list btn-page-2 active" style="--clr:#4CAF50">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </span>
                            <span class="nav-label">Form</span>
                        </a>
                    </li>
                    <li class="list btn-page-3" style="--clr:#2196F3">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="document-outline"></ion-icon>
                            </span>
                            <span class="nav-label">Pengajuan</span>
                        </a>
                    </li>
                    <li class="list btn-page-4" style="--clr:#FFC107">
                        <a href="javascript:void(0)">
                            <span class="icon">
                                <ion-icon name="folder-open-outline"></ion-icon>
                            </span>
                            <span class="nav-label">Jadwal</span>
                        </a>
                    </li>
                </ul>
                <div class="indicator"></div>
            </div>
        </nav>

        <div class="pemilihanTanggal visible d-flex justify-content-center">
            <div class="container px-2 px-sm-3" style="max-width: 680px;">
                <div class="kk-form-shell">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <div>
                            <h4 class="fw-bold mb-0" style="color: #0f172a;"><i class="fas fa-car-side me-2" style="color: #0d9488;"></i>Formulir Peminjaman Kendaraan</h4>
                            <small class="text-muted" style="font-size: 0.76rem;">Pilih tanggal keberangkatan, penumpang, tujuan, dan kegiatan.</small>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted mb-1" style="font-size: 0.72rem;">Tanggal Kebutuhan</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="dateRangePicker" placeholder="Klik untuk memilih rentang tanggal..." value="" autocomplete="off">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-calendar-alt text-secondary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-tambahan-keberangkatan hidden">
                        <div class="row mt-2">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase text-muted mb-1" style="font-size: 0.72rem;">Penumpang (PNS, PPPK, atau PPNPN)</label>
                                <select multiple="multiple" name="penumpang[]" class="form-control select2" placeholder="Pilih beberapa penumpang di sini" style="width: 100%;" autocomplete="off">
                                    <?php foreach ($getPegawai as $data): ?>
                                        <option value="<?= $data['id_data_pegawai']; ?>" <?= $nip == $data['nip'] ? 'selected' : ''; ?> <?= $nip == $data['nip'] ? 'disabled data-id="true"' : ''; ?>><?= name_capitalize($data['nama']); ?> <?= $nip == $data['nip'] ? '- Harus diikutsertakan' : ''; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold small text-uppercase text-muted mb-1" style="font-size: 0.72rem;">Tujuan Perjalanan</label>
                                    <input type="text" name="tujuan" class="form-control form-control-sm" placeholder="Masukkan tujuan perjalanan Anda..." required autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-bold small text-uppercase text-muted mb-1" style="font-size: 0.72rem;">Rincian Kegiatan</label>
                                    <textarea name="keterangan" class="form-control form-control-sm" placeholder="Deskripsikan kegiatan secara singkat..." rows="2" required autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-success fw-bold w-100 py-2 hidden btn-lanjutkan-page-2 getTheCar" style="border-radius: 10px; font-size: 0.88rem;" data-id="0">
                                <i class="fas fa-paper-plane me-1"></i> Ajukan Peminjaman Kendaraan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="menuUtamaPemilihan container hidden">
            <div class="container text-center py-3">
                <h3>Daftar Pengajuan</h3>
                <hr>
            </div>
            <!--Produk Grid -->
            <div class="container daftarPilihanKendaraan">

            </div>

        </div>
        <div class="menuUtamaRiwayat container hidden">

            <div class="container text-center py-3">
                <h3>Kendaraan yang Akan Digunakan Hari Ini dan Seterusnya</h3>
                <hr>
            </div>
            <!--Produk Grid -->
            <div class="container daftarKendaraan">

            </div>
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
        });

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
                    $('.daftarKendaraan').html(`<i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>`)
                },
                success: function(response) {
                    if (response.data) {
                        $('.daftarKendaraan').html(response.data)
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

        $(".btn-lanjutkan-page-2").on("click", function() {
            // getMenuButton($(".btn-page-3"));
            // $(".pemilihanTanggal").removeClass('visible')
            // $(".pemilihanTanggal").addClass('hidden')
            // $(".menuUtamaPemilihan").removeClass('hidden')
            // $(".menuUtamaPemilihan").addClass('visible')
            // $(".menuUtamaRiwayat").removeClass('visible')
            // $(".menuUtamaRiwayat").addClass('hidden')
            // cekKendaraan()
        });
        // Event listener untuk btn-page-3 dengan pengecekan data
        $(".btn-page-3").on("click", function() {
            getMenuButton($(this));
            $(".pemilihanTanggal").removeClass('visible');
            $(".pemilihanTanggal").addClass('hidden');
            $(".menuUtamaPemilihan").removeClass('hidden');
            $(".menuUtamaPemilihan").addClass('visible');
            $(".menuUtamaRiwayat").removeClass('visible');
            $(".menuUtamaRiwayat").addClass('hidden');
            cekKendaraan()
        });

        // Daftar nama bulan dalam bahasa Indonesia
        const bulanIndonesia = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
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
                url: "/kendaraan_kantor_get_pengajuan",
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
        $('#dateRangePicker').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
            var element = $(this)
            var formData = {
                start: picker.startDate.format('YYYY-MM-DD'),
                end: picker.endDate.format('YYYY-MM-DD')
            };

            $.ajax({
                url: '/kendaraan_kantor_cek_tanggal',
                data: formData,
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: "Memeriksa Ketersediaan Tanggal...",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    updateDisabledOptions(response.dataDisabled);

                    if (response.data) {
                        let dateArray = JSON.parse(response.data['tanggal']);

                        let startDate = dateArray[0];
                        let endDate = dateArray[dateArray.length - 1];
                        var tanggalKeberangkatan;

                        // Contoh tanggal
                        var startDates = new Date(startDate); // Contoh tanggal mulai
                        var endDates = new Date(endDate); // Contoh tanggal akhir

                        // Format tanggal
                        var formattedStartDate = formatTanggal(startDates);
                        var formattedEndDate = formatTanggal(endDates);

                        // Mengubah nilai tanggal keberangkatan sesuai dengan format
                        var tanggalKeberangkatan;
                        if (formattedStartDate === formattedEndDate) {
                            tanggalKeberangkatan = formattedStartDate;
                        } else {
                            tanggalKeberangkatan = formattedStartDate + " - " + formattedEndDate;
                        }


                        Swal.fire({
                            icon: "warning",
                            html: "Kendaraan anda sudah dipilih! <br /><small><strong>kendaraan " + response.data['nama_kendaraan'] + " (" + response.data['plat_nomor'] + ")</strong></small><br /><small>pada tanggal " + tanggalKeberangkatan + "</small>. <hr />Pengelolaan data tersedia pada menu SIMPLY jika diperlukan!",
                            showConfirmButton: false,
                            timer: 3500
                        });

                        element.val("");
                        $(".btn-lanjutkan-page-2").addClass("hidden")
                        $(".btn-lanjutkan-page-2").removeClass("visible")
                    } else {
                        element.val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                        Swal.close();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {},
                complete: function() {
                    cekDate()
                }
            });

        });

        $('#dateRangePicker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val("");
            $(".btn-lanjutkan-page-2").addClass("hidden")
            $(".btn-lanjutkan-page-2").removeClass("visible")
            cekDate()
        });

        function cekDate() {
            var tanggal = $("#dateRangePicker").val();

            if (tanggal != "") {
                $(".form-tambahan-keberangkatan").removeClass("hidden");
                $(".form-tambahan-keberangkatan").addClass("visible");
            } else {
                $(".form-tambahan-keberangkatan").removeClass("visible");
                $(".form-tambahan-keberangkatan").addClass("hidden");
                $('input[name="tujuan"]').val("");
                $('textarea[name="keterangan"]').val("");
            }
        }

        $("#dateRangePicker, select").on("change", function() {
            if (checkRequiredData()) {
                $(".btn-lanjutkan-page-2").removeClass("hidden")
                $(".btn-lanjutkan-page-2").addClass("visible")
            } else {
                $(".btn-lanjutkan-page-2").removeClass("visible")
                $(".btn-lanjutkan-page-2").addClass("hidden")
            }
        });
        $("input, textarea").on("keyup", function() {
            if (checkRequiredData()) {
                $(".btn-lanjutkan-page-2").removeClass("hidden")
                $(".btn-lanjutkan-page-2").addClass("visible")
            } else {
                $(".btn-lanjutkan-page-2").removeClass("visible")
                $(".btn-lanjutkan-page-2").addClass("hidden")
            }
        });



        function checkRequiredData() {
            var tanggal = $("#dateRangePicker").val();
            var penumpang = $('select[name="penumpang[]"]').val(); // not-required
            var tujuan = $('input[name="tujuan"]').val();
            var keterangan = $('textarea[name="keterangan"]').val();

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
                if (kondisi) {
                    Swal.fire({
                        title: "Anda sudah mengajukan!",
                        html: "Pilihan " + kendaraanPilihan + " (<strong>" + platPilihan + "</strong>)<br />Pengelolaan data tersedia pada menu SIMPLY jika diperlukan!<hr />Kembali ke menu pengisian formulir?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Reset Formulir...",
                        cancelButtonText: "Tidak!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#dateRangePicker').val("");
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "info",
                        title: "Ajukan Peminjaman",
                        html: "Formulir akan dikirimkan dan pemilihan kendaraan untuk Anda akan dilakukan oleh Atasan.",
                        showCancelButton: true,
                        confirmButtonText: "Ajukan!",
                        cancelButtonText: "Tidak jadi mengajukan..."
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            var tanggal = $("#dateRangePicker").val();
                            var penumpang = $('select[name="penumpang[]"]').val(); // not-required
                            var tujuan = $('input[name="tujuan"]').val();
                            var keterangan = $('textarea[name="keterangan"]').val();
                            var id = elementIni.data("id");

                            var formData = {
                                tanggal: tanggal,
                                penumpang: penumpang,
                                tujuan: tujuan,
                                keterangan: keterangan,
                                id_kendaraan_kantor_daftar: id
                            };

                            $.ajax({
                                url: "/kendaraan_kantor_set_form",
                                data: formData,
                                method: 'post',
                                dataType: 'json',
                                beforeSend: function() {
                                    Swal.fire({
                                        title: "Menyimpan Pengajuan...",
                                        allowOutsideClick: false,
                                        didOpen: () => {
                                            Swal.showLoading();
                                        }
                                    });
                                },
                                success: function(response) {
                                    console.log(response.cek)
                                    if (response.status == "success") {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Pengajuan anda telah selesai disimpan!",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        getMenuButton($(".btn-page-3"));
                                        $(".pemilihanTanggal").removeClass('visible')
                                        $(".pemilihanTanggal").addClass('hidden')
                                        $(".menuUtamaPemilihan").removeClass('hidden')
                                        $(".menuUtamaPemilihan").addClass('visible')
                                        $(".menuUtamaRiwayat").removeClass('visible')
                                        $(".menuUtamaRiwayat").addClass('hidden')
                                        cekKendaraan()
                                    } else if (response.status == "sold") {
                                        Swal.fire({
                                            icon: "warning",
                                            title: "Anda sudah mengajukan kesempatan untuk tanggal " +
                                                JSON.parse(response.data['tanggal'])[0] +
                                                ((JSON.parse(response.data['tanggal']).length > 1) ?
                                                    " - " + (JSON.parse(response.data['tanggal']).length - 1) :
                                                    "") +
                                                "!",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });

                                    } else if (response.status == "error") {
                                        Swal.fire({
                                            text: "Ada kesalahan kode pada sistem, segera hubungi pihak terkait! Terimakasi..."
                                        });
                                        console.log(response.data)
                                    } else {
                                        Swal.fire({
                                            icon: "warning",
                                            title: "Anda sudah mengajukan kesempatan untuk tanggal " +
                                                JSON.parse(response.data['tanggal'])[0] +
                                                ((JSON.parse(response.data['tanggal']).length > 1) ?
                                                    " - " + (JSON.parse(response.data['tanggal']).length - 1) :
                                                    "") +
                                                "!",
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
                                    });
                                }
                            });
                        }
                    });
                }
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
            $('select[name="penumpang[]"] option').each(function() {
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
    </script>

    <!-- Bootstrap 5.3 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>