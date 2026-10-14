<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Main Menu - Simply App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Moody, blurred glassmorphism vibe inspired by the reference image */
            --bg-color: #2b343b;
            --accent-color: #5f8a92; /* The teal from 'Sign Up' */
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
        }

        * {
            -webkit-tap-highlight-color: transparent; 
            -webkit-touch-callout: none; 
            user-select: none; 
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 70% 30%, rgba(220, 160, 100, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 20% 70%, rgba(100, 150, 180, 0.1) 0%, transparent 50%),
                linear-gradient(180deg, rgba(43, 52, 59, 0.8) 0%, #1a2228 100%);
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding-bottom: 30px;
            overflow-x: hidden;
            color: var(--text-main);
        }

        .app-container {
            position: relative;
            z-index: 1;
            padding: 30px 25px;
            max-width: 500px;
            margin: 0 auto;
            animation: fadeInDown 0.8s ease;
        }

        /* Top Toolbar */
        .top-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .date-left {
            font-size: 24px;
            font-weight: 500;
            color: var(--text-main);
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        
        .date-left small {
            display: block;
            font-size: 13px;
            font-weight: 400;
            color: var(--text-muted);
            letter-spacing: 0px;
        }

        .date-display {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .date-display i {
            font-size: 18px;
        }

        /* Welcome Section */
        .welcome-section {
            margin-bottom: 25px; /* Reduced from 40px */
        }

        .welcome-title {
            font-size: 46px;
            font-weight: 600;
            color: var(--text-main);
            margin: 0 0 5px 0;
            letter-spacing: -1px;
        }

        .welcome-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            font-style: italic;
            margin-bottom: 25px;
        }

        .btn-primary-action {
            background: var(--accent-color);
            color: white;
            border-radius: 30px;
            padding: 14px 35px;
            font-size: 16px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }
        
        .btn-primary-action:active {
            transform: scale(0.95);
            color: white;
        }

        /* Middle List */
        .middle-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 20px; /* Reduced from 40px */
        }

        .list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding-bottom: 15px;
        }

        .text-box {
            flex-grow: 1;
            padding-right: 15px;
        }

        .list-title {
            display: block;
            color: var(--text-main);
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .list-desc {
            display: block;
            color: var(--text-muted);
            font-size: 12px;
            line-height: 1.3;
        }

        .glass-pill {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            color: var(--text-main);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .glass-pill:active {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Bottom Grid */
        .bottom-grid-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 15px;
            padding-top: 15px; /* Reduced from 25px */
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .bottom-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 40px;
        }

        .grid-item {
            flex: 1;
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 18px 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: transform 0.2s;
        }

        .grid-item:active {
            transform: scale(0.95);
            background: rgba(255, 255, 255, 0.15);
        }

        .grid-item i {
            font-size: 22px;
            color: var(--text-main);
        }

        .grid-item span {
            font-size: 11px;
            font-weight: 500;
        }

        .version-footer {
            text-align: center;
            color: rgba(255,255,255,0.3);
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Modal overrides for Mobile */
        .modal-dialog {
            margin: 1rem;
            display: flex;
            align-items: center;
            min-height: calc(100% - 2rem);
        }
        .modal-content {
            border-radius: 30px;
            border: none;
            background: var(--bg-color);
            overflow: hidden;
            color: white;
        }
        .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
    </style>
    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
</head>

<body>
    <div class="app-container">
        <!-- Top Toolbar -->
        <div class="top-toolbar">
            <div class="date-left" id="realtime-date">
                <?= date('D') ?><br>
                <small><?= date('d M Y') ?></small>
            </div>
            <div class="date-display" id="realtime-time">
                <?= date('H:i') ?>
            </div>
        </div>

        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Simply</h1>
            <p class="welcome-subtitle">
                Sistem Informasi Manajemen Pelayanan<br>
                Balai Kekarantinaan Kesehatan Kelas I Bandung
            </p>
        </div>

        <!-- Middle List (Conditional Menus) -->
        <div class="middle-list">
            <?php if ($verifikatorKendaraan && $countMessage > 0 ): ?>
                <div class="list-item">
                    <div class="text-box">
                        <span class="list-title">Administrator</span>
                        <span class="list-desc">Verifikasi peminjaman (<?= $countMessage; ?>)</span>
                    </div>
                    <a href="javascript:void(0);" onclick="inputPinVerifikatorKendaraan()" class="glass-pill">Cek</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Bottom Grid (Glass Squares) -->
        <div class="bottom-grid-title">Pintasan Menu</div>
        <div class="bottom-grid">
            <a href="/auth/login-v2" class="grid-item">
                <i class="fas fa-sign-in-alt"></i>
                <span>Log in</span>
            </a>
            <?php if ($nip != ""): ?>
            <a href="kendaraan_kantor" class="grid-item">
                <i class="fas fa-car-side"></i>
                <span>Kendaraan</span>
            </a>
            <?php endif; ?>
            <a href="/absensi-wfo" class="grid-item">
                <i class="fas fa-fingerprint"></i>
                <span>Absensi</span>
            </a>
            <a href="/manualBooks" class="grid-item">
                <i class="fas fa-book-open"></i>
                <span>Manual</span>
            </a>
            <?php if ($nip != ""): ?>
            <a href="javascript:void(0);" class="grid-item linimasa-absen">
                <i class="far fa-clock"></i>
                <span>Riwayat</span>
            </a>
            <?php endif; ?>
        </div>

        <div class="version-footer">
            IT BKK Kelas I Bandung &bull; Simply App v2.0 &copy; <?= date('Y'); ?>
        </div>
    </div>

    <script>
        // Update waktu dan tanggal realtime
        function updateRealtimeClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
            
            // Format Hari, Tanggal Bulan Tahun dalam Bahasa Indonesia Lengkap
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const dateNum = now.getDate().toString().padStart(2, '0');
            const monthName = months[now.getMonth()];
            const yearNum = now.getFullYear();
            
            document.getElementById('realtime-time').innerText = timeStr;
            document.getElementById('realtime-date').innerHTML = `${dayName}<br><small>${dateNum} ${monthName} ${yearNum}</small>`;
        }
        
        setInterval(updateRealtimeClock, 1000);
        updateRealtimeClock();
    </script>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Masukkan PIN Security Verifikator</h1>
                </div>
                <div class="modal-body">
                    <?= $this->include('kendaraan_kantor/pin_verifikator'); ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for Attendance Method Selection - Commented out since Absen button now directly goes to WFO
    <div class="modal fade" id="exampleModalAbsen" tabindex="-1" aria-labelledby="exampleModalAbsenLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalAbsenLabel">Metode Absensi</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <a href="/absensi-wfo" class="menu-btn mx-auto">Absen WFO</a>
            <a href="#" class="btn menu-btn text-light mx-auto disabled btn-wfa-absen" style="line-height: 35px; background-color: #6c757d; opacity: 0.6; cursor: not-allowed; pointer-events: none;">Absen WFA (Dinonaktifkan)</a>
          </div>
          <div class="modal-footer text-danger">
              <strong>Khusus absensi <i>Work From Anywhere</i> (WFA) akan dibuka dari pukul 06.00 s.d 18.00 WIB. diluar dari jam tersebut absensi ditutup!</strong>
          </div>
        </div>
      </div>
    </div>
    -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".btn-maintenance").click(function() {
            Swal.fire({
                icon: 'info',
                title: 'Pembaruan Sistem Sedang Berlangsung!',
                html: 'Kami sedang melakukan perubahan signifikan pada proses bisnis dan pengembangan sistem <strong>Pinjam Kendaraan Induk</strong>. Mohon maaf atas ketidaknyamanannya, silakan kembali beberapa hari lagi.'
            })
        });

        function inputPinVerifikatorKendaraan() {
            showInfiniteLoading();
            $("#exampleModal").modal('show');
            setTimeout(() => {
                Swal.close();
            }, 600);
            setTimeout(() => {
                firstFocus();
            }, 1000);
        }
    </script>



    <!-- verifikator pin -->
    <script>
        "use strict";
        window.onload = function() {
            firstFocus();
        };

        function firstFocus() {
            document.querySelector('.input-field-pin').focus();
        }

        function handleClick(event) {
            event.currentTarget.select();
            checkAllInputs();
        }

        function handleInput(event) {
            const currentInput = event.currentTarget;
            const nextInput = currentInput.nextElementSibling;
            currentInput.value = `${currentInput.valueAsNumber}`; // parse number
            if (currentInput.value.length > 0) {
                currentInput.value = currentInput.value.slice(currentInput.value.length - 1); // keep the last number
                if (nextInput && nextInput.tagName === "INPUT") {
                    nextInput.focus();
                }
            }
            checkAllInputs();
        }

        function checkAllInputs() {
            const inputFields = document.querySelectorAll('.input-field-pin');
            let allFilled = true;

            inputFields.forEach(input => {
                if (input.value.trim() === "") {
                    allFilled = false;
                }
            });

            if (allFilled) {
                cekPIN();
            }
        }


        function showInfiniteLoading() {
            Swal.fire({
                title: 'Loading...',
                text: 'Mengecek data verifikasi!',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false
            });
        }

        function cekPIN() {
            var formData = {
                pin: $("#pin_1").val() + $("#pin_2").val() + $("#pin_3").val() + $("#pin_4").val()
            };

            $.ajax({
                url: '/kendaraan_kantor/verifikasi/pin',
                data: formData,
                method: 'post',
                dataType: 'json',
                beforeSend: function() {
                    showInfiniteLoading()
                },
                success: function(response) {
                    if (response.data == 'gagal') {
                        $("#pin_1").val("");
                        $("#pin_2").val("");
                        $("#pin_3").val("");
                        $("#pin_4").val("");

                        Swal.fire({
                            icon: 'warning',
                            title: 'PIN Salah!',
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                setTimeout(function() {
                                    firstFocus();
                                }, 500);
                            }
                        });

                    } else if (response.data == 'sukses') {
                        Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Data berhasil terkonfirmasi!',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                            },
                            setTimeout(() => {
                                window.location.href = '/kendaraan_kantor_verifikator';
                            }, "500"));
                    } else {

                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                },
                complete: function() {}
            });
        }

        function handleKeydown(event) {
            const currentInput = event.currentTarget;
            const prevInput = event.target
                .previousElementSibling;
            if (event.key === "Backspace") {
                if (currentInput.value.length === 0 &&
                    prevInput &&
                    prevInput.tagName === "INPUT") {
                    prevInput.focus();
                } else {
                    currentInput.value = "";
                }
            } else {
                currentInput.focus();
            }

            checkAllInputs();
        }
    </script>
    
  <script>
    $(document).ready(function () {
        // Fungsi untuk menonaktifkan tombol WFA secara permanen
        function checkTimeAndDisableButton() {
            // Memilih tombol dengan class 'btn-wfa-absen'
            const button = $('.btn-wfa-absen');
    
            // Menonaktifkan tombol WFA secara permanen
            button.addClass('disabled');
        }
    
        checkTimeAndDisableButton();
    });
  </script>
  
  <script>
      $(".linimasa-absen").click(function() {
        $.ajax({
          url: "/getLinimasa/absensi",
          method: 'post',
          dataType: 'json',
          beforeSend: function() {
            Swal.fire({
                title: 'Loading...',
                text: 'Mengambil data',
                allowOutsideClick: false, // mencegah klik di luar untuk menutup
                showConfirmButton: false, // sembunyikan tombol konfirmasi
                didOpen: () => {
                  Swal.showLoading(); // tampilkan animasi loading
                }
            });
          },
          success: function(response) {
            Swal.close(); // Tutup loading

            if (response.data && response.data.length > 0) {
                // Buat HTML untuk list data absensi
                let listHTML = '<ul style="text-align:left; list-style:none; padding-left:0; font-size: 87%;">';
    
                let data = true;
                response.data.forEach(function(item, index) {
                    if(data) {
                      data = false;
                      
                      listHTML += `
                        <li style="text-align: center; border-bottom: solid 1px; margin-bottom: 10px;">
                            ${item.nama}
                        </li>`
                    }
                    
                    const timeOptions = { 
                      hour: '2-digit', 
                      minute: '2-digit', 
                      second: '2-digit', 
                      hour12: false // Format 24 jam
                    };
                    
                    const dateOptions = {
                      year: 'numeric',
                      month: '2-digit',
                      day: '2-digit'
                    };
                    
                    const jamMasuk = new Date(item.jam_masuk).toLocaleTimeString(undefined, timeOptions);
                    const jamPulang = item.jam_pulang 
                                      ? new Date(item.jam_pulang).toLocaleTimeString(undefined, timeOptions) 
                                      : 'Belum pulang';
                    const tanggal = new Date(item.jam_masuk).toLocaleDateString(undefined, dateOptions);
                                      
                    const styleBelumPulang = item.jam_pulang 
                                      ? '' 
                                      : 'color: red';
                    const statusMasuk = item.status_masuk ? `(${item.status_masuk})` : '';
                const statusPulang = item.status_pulang ? `(${item.status_pulang})` : '';
    
                    listHTML += `
                        <li>
                            <small style="float: right; clear: none;">${tanggal}</small><br>
                            <strong>Masuk:</strong> ${jamMasuk} <small style="float: right; clear: none;"><strong>${statusMasuk}</strong></small><br>
                            <strong>Pulang:</strong> <span style="${styleBelumPulang}">${jamPulang}</span> <small style="float: right; clear: none;"><strong>${statusPulang}</strong></small><br>
                            <strong>Jam Kerja:</strong> ${item.jam_kerja || '-'}
                            <hr>
                        </li>
                    `;
                });
    
                listHTML += '</ul>';
    
                // Tampilkan SweetAlert dengan list
                Swal.fire({
                    title: 'Riwayat Absensi',
                    html: listHTML,
                    confirmButtonText: 'Tutup'
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada data',
                    text: 'Tidak ditemukan riwayat absensi untuk pegawai ini.',
                    confirmButtonText: 'OK'
                });
            }
          },
        }); 
      });
  </script>
</body>

</html>