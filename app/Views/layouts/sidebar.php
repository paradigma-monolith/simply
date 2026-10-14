<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
    <li class="nav-header">MENU</li>
    <?php if (in_array('Dashboard', session()->get('accessMenu'))) : ?>
      <li id="dashboard" class="nav-item">
        <a href="/home/dashboard" class="nav-link <?= in_array($activeMenu, ['Dashboard']) ? "active" : ""; ?>">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
            Dashboard
          </p>
        </a>
      </li>
    <?php endif; ?>
    
    <!-- Agenda Menu -->
    <li id="agenda" class="nav-item">
      <a href="/home/agenda" class="nav-link <?= in_array($activeMenu, ['agenda']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-calendar-alt"></i>
        <p>
          Agenda
        </p>
      </a>
    </li>
    
    <!-- Feedback Menu for All Users -->
    <li id="feedback-user" class="nav-item">
      <a href="/home/feedback" class="nav-link <?= in_array($activeMenu, ['feedback']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-comment-dots"></i>
        <p>
          Feedback Saya
        </p>
      </a>
    </li>
    
    <!-- APLIKASI E-KIP -->
     
    <li id="ekip" class="nav-item <?= in_array($activeMenu, ['ekip_kinerja', 'ekip_perilaku', 'ekip_hasil', 'ekip_pengaturan', 'ekip_penilaian_360']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['ekip_kinerja', 'ekip_perilaku', 'ekip_hasil', 'ekip_pengaturan', 'ekip_penilaian_360']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-chart-line"></i>
        <p>
          E-KIP
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-success right">Baru</span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/ekip/kinerja" class="nav-link <?= in_array($activeMenu, ['ekip_kinerja', 'ekip_perilaku', 'ekip_penilaian_360']) ? "active" : ""; ?>">
            <i class="fas fa-clipboard-check nav-icon"></i>
            <p>Input Hasil Kinerja</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/ekip/hasil" class="nav-link <?= in_array($activeMenu, ['ekip_hasil']) ? "active" : ""; ?>">
            <i class="fas fa-chart-pie nav-icon"></i>
            <p>Nilai Kinerja Pegawai</p>
          </a>
        </li>
        <?php if(session()->get('role') == 'Admin'): ?>
        <li class="nav-item">
          <a href="/ekip/pengaturan" class="nav-link <?= in_array($activeMenu, ['ekip_pengaturan']) ? "active" : ""; ?>">
            <i class="fas fa-cogs nav-icon"></i>
            <p>Pengaturan</p>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </li>
     
    <!-- APLIKASI E-KIP -->

    <?php if (in_array('Dashboard', session()->get('accessMenu'))) : ?>
      <li id="Absen" class="nav-item <?= in_array($activeMenu, ['pengaturanAbsen', 'absenHarian', 'zonaAbsen', 'absenDinasLuar']) ? "menu-open" : ""; ?>">
        <a href="#" class="nav-link <?= in_array($activeMenu, ['pengaturanAbsen', 'absenHarian', 'zonaAbsen', 'absenDinasLuar']) ? "active" : ""; ?>">
          <i class="nav-icon fas fa-business-time"></i>
          <p>
            Absen Pegawai
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-danger right"></span>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="/absen/absen_harian" class="nav-link <?= in_array($activeMenu, ['absenHarian']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Absen Harian</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/absen/absen_dinas_luar" class="nav-link <?= in_array($activeMenu, ['absenDinasLuar']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Absen Dinas Luar</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/absen/zona_absen" class="nav-link <?= in_array($activeMenu, ['zonaAbsen']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Zona Absen</p>
            </a>
          </li>
          <li class="nav-item">
            <hr style="margin: 0;border-style: inset;border-width: 1px;" />
          </li>
          <li class="nav-item">
            <a href="/absen/pengaturan_absen" class="nav-link <?= in_array($activeMenu, ['pengaturanAbsen']) ? "active" : ""; ?> pengaturan-absen-menu">
              <i class="far fa-circle nav-icon"></i>
              <p>Pengaturan Absen</p>
            </a>
          </li>
        </ul>
      </li>
    <?php endif; ?>
    
    <li class="nav-header">APLIKASI PENGELOLAAN</li>
    
    <!-- APLIKASI INFOGRAFIS -->
      <li id="infografis" class="nav-item <?= in_array($activeMenu, ['infografis_laporan', 'kapal', 'pesawat', 'vaksin', 'infografis_validation_kapal', 'infografis_validation_pesawat']) ? "menu-open" : ""; ?>">
        <a href="#" class="nav-link <?= in_array($activeMenu, ['infografis_laporan', 'kapal', 'pesawat',  'vaksin', 'infografis_validation_kapal', 'infografis_validation_pesawat']) ? "active" : ""; ?>">
          <i class="nav-icon fas fa-file-alt"></i>
          <p>
            INFOGRAFIS
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-danger right"></span>
          </p>
        </a>
        <ul class="nav nav-treeview">
        <?php if (in_array('Infografis', session()->get('accessMenu'))) : ?>
          <?php if (in_array('Data Infografis', session()->get('accessMenu'))) : ?>
            <li class="nav-item">
              <a href="/infografis/kapal" class="nav-link <?= in_array($activeMenu, ['kapal']) ? "active" : ""; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Infografis Kapal</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/infografis/pesawat" class="nav-link <?= in_array($activeMenu, ['pesawat']) ? "active" : ""; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Infografis Pesawat</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/infografis/vaksin" class="nav-link <?= in_array($activeMenu, ['vaksin']) ? "active" : ""; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Infografis Vaksinasi</p>
              </a>
            </li>
          <?php endif; ?>
          <?php endif; ?>
          <li class="nav-item">
            <a href="/infografis/laporan" class="nav-link laporan-infografis-menu">
              <i class="far fa-circle nav-icon"></i>
              <p>Laporan Infografis</p>
            </a>
          </li>
        </ul>
      </li>
    <!-- APLIKASI INFOGRAFIS -->
    
    <!-- APLIKASI ABS -->
    <?php
    // Logic to count pending Rawat Kendaraan verifications
    $countRawatKendaraanPending = 0;
    $isVerifikator1 = false;
    $isVerifikator2 = false;
    try {
        $userId = session()->get('id_user');
        if ($userId) {
            $rawatVerifikatorModel = new \App\Models\Abs\RawatVerifikatorModel();
            $isVerifikator1 = $rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_1')->first();
            $isVerifikator2 = $rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_2')->first();

            if ($isVerifikator1 || $isVerifikator2) {
                $rawatKendaraanModel = new \App\Models\Abs\RawatKendaraanModel();
                
                if ($isVerifikator1) {
                    $countRawatKendaraanPending += $rawatKendaraanModel->where('status', 'pending')->countAllResults();
                }
                
                if ($isVerifikator2) {
                    $countRawatKendaraanPending += $rawatKendaraanModel->where('status', 'verifikasi_2')->countAllResults();
                }
            }
        }
    } catch (\Throwable $th) {
        // Silent error
    }
    ?>
    <li id="abs" class="nav-item <?= in_array($activeMenu, ['abs_pengajuan','abs_gambar', 'managemen_qr_code', 'abs_verifikasi', 'bangunan', 'lokasi', 'abs_rawat_dashboard', 'abs_rawat_pengajuan', 'abs_rawat_kendaraan', 'abs_rawat_pengaturan', 'abs_rawat_verifikasi']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['abs_pengajuan','abs_gambar', 'managemen_qr_code', 'abs_verifikasi', 'bangunan', 'lokasi', 'abs_rawat_dashboard', 'abs_rawat_pengajuan', 'abs_rawat_kendaraan', 'abs_rawat_pengaturan', 'abs_rawat_verifikasi']) ? "active" : ""; ?>">
        <i class="nav-icon fa fa-boxes"></i>
        <p>
          ABS
          <i class="fas fa-angle-left right"></i>
          <?php if ($countRawatKendaraanPending > 0) : ?>
            <span class="badge badge-danger right"><?= $countRawatKendaraanPending; ?></span>
          <?php else: ?>
            <span class="badge badge-danger right"></span>
          <?php endif; ?>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item <?= in_array($activeMenu, ['abs_pengajuan','abs_gambar', 'managemen_qr_code', 'abs_verifikasi', 'bangunan', 'lokasi']) ? "menu-open" : ""; ?>">
          <a href="#" class="nav-link <?= in_array($activeMenu, ['abs_pengajuan','abs_gambar', 'managemen_qr_code', 'abs_verifikasi', 'bangunan', 'lokasi']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>
              ABS BMN
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/abs/bangunan" class="nav-link <?= in_array($activeMenu, ['bangunan']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Peta Ruang dan Kategori</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/data_barang" class="nav-link <?= in_array($activeMenu, ['lokasi']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Daftar Inventaris</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/data_gambar" class="nav-link <?= in_array($activeMenu, ['abs_gambar']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Manajemen Gambar</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/verifikasi_update_data" class="nav-link <?= in_array($activeMenu, ['abs_verifikasi']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Verifikasi & Pembaruan Data</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/manajemen_qr_code" class="nav-link <?= in_array($activeMenu, ['managemen_qr_code']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Manajemen QR Code</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/pengajuan" class="nav-link <?= in_array($activeMenu, ['abs_pengajuan']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Permintaan Pengajuan</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?= in_array($activeMenu, ['abs_rawat_dashboard', 'abs_rawat_pengajuan', 'abs_rawat_kendaraan', 'abs_rawat_pengaturan', 'abs_rawat_verifikasi']) ? "menu-open" : ""; ?>">
          <a href="#" class="nav-link <?= in_array($activeMenu, ['abs_rawat_dashboard', 'abs_rawat_pengajuan', 'abs_rawat_kendaraan', 'abs_rawat_pengaturan', 'abs_rawat_verifikasi']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>
              Rawat Kendaraan
              <i class="fas fa-angle-left right"></i>
              <?php if (isset($countRawatKendaraanPending) && $countRawatKendaraanPending > 0) : ?>
                <span class="badge badge-danger right"><?= $countRawatKendaraanPending; ?></span>
              <?php endif; ?>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/abs/rawat_kendaraan" class="nav-link <?= in_array($activeMenu, ['abs_rawat_dashboard']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Dashboard History</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/rawat_kendaraan/pengajuan" class="nav-link <?= in_array($activeMenu, ['abs_rawat_pengajuan']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Pengajuan</p>
              </a>
            </li>
            <?php
            // Check access for Rawat Kendaraan menus
            $isAdmin = session()->get('role') == 'Admin';
            $isVerifikator1 = false;
            $isVerifikator2 = false;

            try {
                $rawatVerifikatorModel = new \App\Models\Abs\RawatVerifikatorModel();
                $userId = session()->get('id_user');
                
                if ($userId) {
                    // Cek apakah tabel ada untuk menghindari error jika belum migrasi di server
                    // Menggunakan try-catch query sederhana untuk cek eksistensi
                    $isVerifikator1 = $rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_1')->first();
                    $isVerifikator2 = $rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_2')->first();
                }
            } catch (\Throwable $th) {
                // Silent error jika tabel belum ada atau model bermasalah
                // Agar halaman tidak stuck loading
            }
            ?>

            <?php if ($isAdmin || $isVerifikator1 || $isVerifikator2) : ?>
            <li class="nav-item">
              <a href="/abs/rawat_kendaraan/verifikasi_list" class="nav-link <?= in_array($activeMenu, ['abs_rawat_verifikasi']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>
                    Verifikasi Pengajuan
                    <?php if (isset($countRawatKendaraanPending) && $countRawatKendaraanPending > 0) : ?>
                        <span class="badge badge-danger right"><?= $countRawatKendaraanPending; ?></span>
                    <?php endif; ?>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/abs/rawat_kendaraan/kendaraan" class="nav-link <?= in_array($activeMenu, ['abs_rawat_kendaraan']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>List Kendaraan</p>
              </a>
            </li>
            <?php endif; ?>

            <?php if ($isAdmin) : ?>
            <li class="nav-item">
              <a href="/abs/rawat_kendaraan/pengaturan" class="nav-link <?= in_array($activeMenu, ['abs_rawat_pengaturan']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Pengaturan Verifikator</p>
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </li>
      </ul>
    </li>
    <!-- APLIKASI ABS -->
    
    <!-- APLIKASI SICUTE -->
    <?php if (false) : // MENU INI DI-HIDE (TIDAK AKAN TAMPIL) ?>
    <li id="sicuti" class="nav-item <?= in_array($activeMenu, ['pengaturan_cuti', 'kelola_data_cuti', 'validation_data_cuti', 'kelola_data_permohonan', 'validation_data_permohonan']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['pengaturan_cuti', 'kelola_data_cuti', 'validation_data_cuti', 'kelola_data_permohonan', 'validation_data_permohonan']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-laptop-house"></i>
        <p>
          SICUTE (Cuti/Perizinan)
          <i class="fas fa-angle-left right"></i>
          <!-- <span class="badge badge-danger right"></span> -->
          <!-- <span class="badge badge-warning right view-jumlah-data-cuti-baru"></span>
                  <span class="badge badge-warning right view-jumlah-data-permohonan-baru"></span> -->
          <span class="badge badge-warning right view-jumlah-data-baru"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/cuti/data_cuti" class="nav-link  <?= in_array($activeMenu, ['kelola_data_cuti']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Kelola Data Cuti</p>
          </a>
        </li>
        <li class="nav-item">
          <!--/cuti/data_permohonan-->
          <a href="<?= session()->get('role') != 'admin' ? '/cuti/data_permohonan' : '/cuti/data_permohonan'; ?>" class="nav-link  <?= in_array($activeMenu, ['kelola_data_permohonan']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Kelola Data Perizinan</p>
          </a>
        </li>
        <div class="setValidationCuti-Permohonan">
        </div>
        <li class="nav-item">
          <hr style="margin: 0;border-style: inset;border-width: 1px;" />
        </li>
        <li class="nav-item setValidationCuti">
          <a href="/cuti/validation_cuti" class="nav-link  <?= in_array($activeMenu, ['validation_data_cuti']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Validation Cuti <span class="badge badge-warning right view-jumlah-data-cuti-baru"></span></p>
          </a>
        </li>
        <li class="nav-item setValidationPermohonan">
          <a href="/cuti/validation_permohonan" class="nav-link  <?= in_array($activeMenu, ['validation_data_permohonan']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Validation Perizinan <span class="badge badge-warning right view-jumlah-data-permohonan-baru"></span></p>
          </a>
        </li>
        <?php if (in_array('Admin', session()->get('accessMenu'))) : ?>
          <li class="nav-item">
            <a href="/cuti/sisa_cuti" class="nav-link <?= in_array($activeMenu, ['pengaturan_cuti']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Pengaturan Cuti</p>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </li>
    <?php endif; // MENU INI DI-HIDE (TIDAK AKAN TAMPIL) ?>
    <!-- APLIKASI SICUTE -->
    
    <!-- PENGUKURAN KEBUGARAN DIPINDAHKAN KE DALAM PELATUK -->
    

    
    <!-- APLIKASI SINADI -->
    <li id="sinadi" class="nav-item <?= in_array($activeMenu, ['sinadiVerifikator', 'notaDinas', 'sinadiPengaturan', 'sinadiSaya', 'formulirPerjalananDinas', 'dinasDalamKota', 'dinasLuarKota']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['sinadiVerifikator', 'notaDinas', 'sinadiPengaturan', 'sinadiSaya', 'formulirPerjalananDinas', 'dinasDalamKota', 'dinasLuarKota']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-shuttle-van"></i>
        <p>
          SINADI (Perjalanan Dinas)
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <!-- <li class="nav-item">
          <a href="/sinadi/formulirPerjalananDinas" class="nav-link  <?= in_array($activeMenu, ['formulirPerjalananDinas']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Perjalanan Dinas</p>
          </a>
        </li> -->
        <li class="nav-item">
          <a href="/sinadi/perjalananSaya" class="nav-link  <?= in_array($activeMenu, ['sinadiSaya']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Perjalanan Saya</p>
          </a>
        </li>
        <li class="nav-header">Pengelolaan Data</li>
        <li class="nav-item">
          <a href="/sinadi/dinasDalamKota" class="nav-link  <?= in_array($activeMenu, ['dinasDalamKota']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Dinas Dalam Kota</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/sinadi/dinasLuarKota" class="nav-link  <?= in_array($activeMenu, ['dinasLuarKota']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Dinas Luar Kota</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/sinadi/notaDinas" class="nav-link  <?= in_array($activeMenu, ['notaDinas']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Nota Dinas</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="https://jdih.kemenkeu.go.id/api/download/ccce2e9f-11fe-41ee-9da3-c7d42609b484/2025pmkeuangan032.pdf" target="_blank" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>SBM</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/sinadi/verifikasiSurat" class="nav-link  <?= in_array($activeMenu, ['sinadiVerifikator']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Validasi Dokumen</p>
          </a>
        </li>
        <?php
        $userModel = new \App\Models\Master\UserModel();
        $sinadiAkses = $userModel->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
          ->where("user.username", session()->get('username'))
          ->first();
        ?>
        <?php if ($sinadiAkses && $sinadiAkses['status'] == "Akses Diterima") : ?>
          <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="modal" data-target="#modal-export-sinadi">
              <i class="far fa-circle nav-icon"></i>
              <p>Export Laporan</p>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <hr style="margin: 0;border-style: inset;border-width: 1px;" />
        </li>
        <li class="nav-item">
          <a href="/sinadi/pengaturan" class="nav-link  <?= in_array($activeMenu, ['sinadiPengaturan']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Sinadi Pengaturan</p>
          </a>
        </li>
      </ul>
    </li>
    <!-- APLIKASI SINADI -->
    
      <li id="relaxtime" class="nav-item <?= in_array($activeMenu, ['relaxtimeRealisasiTransaksi', 'relaxtimeRealisasiSP2D', 'relaxtimeRealisasiAkrual', 'relaxtimeTargetBulanan', 'relaxtimePengaturan', 'relaxtimePagu', 'relaxtimeRealisasi', 'anggaran_dashboard', 'anggaran_data', 'anggaran_input', 'anggaran_edit', 'hasilRapatMonev']) ? "menu-open" : ""; ?>">
        <a href="#" class="nav-link <?= in_array($activeMenu, ['relaxtimeRealisasiTransaksi', 'relaxtimeRealisasiSP2D', 'relaxtimeRealisasiAkrual', 'relaxtimeTargetBulanan', 'relaxtimePengaturan', 'relaxtimePagu', 'relaxtimeRealisasi', 'anggaran_dashboard', 'anggaran_data', 'anggaran_input', 'anggaran_edit', 'hasilRapatMonev']) ? "active" : ""; ?>">
          <i class="nav-icon fas fa-money-check-alt"></i>
          <p>
            RELAXTIME
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-danger right"></span>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item" style="display: none;">
            <a href="/relaxtime/pagu" class="nav-link  <?= in_array($activeMenu, ['relaxtimePagu']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Pagu</p>
            </a>
          </li>
          <li class="nav-item" style="display: none;">
            <a href="/relaxtime/target_bulanan" class="nav-link <?= in_array($activeMenu, ['relaxtimeTargetBulanan']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Target Bulanan</p>
            </a>
          </li>
          <li class="nav-item" style="display: none;">
            <a href="/relaxtime/realisasiAnggaran" class="nav-link  <?= in_array($activeMenu, ['relaxtimeRealisasi']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Realisasi Anggaran</p>
            </a>
          </li>
          <li class="nav-item" style="cursor: not-allowed;">
            <!--https://docs.google.com/spreadsheets/d/1fOYiln_l57j5y63KOJ7dxuqR8hNe-RsU8mKRkAZ4rEE/edit?usp=sharing-->
            <a href="javascript:void(0)" target="_blank" class="nav-link disabled text-secondary">
              <i class="far fa-dot-circle nav-icon"></i>
              <p>Link Spreadsheet RPD</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/relaxtime/relaxtimeRealisasiAkrual" class="nav-link <?= in_array($activeMenu, ['relaxtimeRealisasiAkrual']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Realisasi Akrual</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/relaxtime/relaxtimeRealisasiSP2D" class="nav-link <?= in_array($activeMenu, ['relaxtimeRealisasiSP2D']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Realisasi SP2D</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/relaxtime/relaxtimeRealisasiTransaksi" class="nav-link <?= in_array($activeMenu, ['relaxtimeRealisasiTransaksi']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Realisasi Transaksi</p>
            </a>
          </li>
          <li class="nav-item">
            <hr style="margin: 0;border-style: inset;border-width: 1px;" />
      
          <li class="nav-item">
            <a href="/anggaran/dashboard" class="nav-link <?= in_array($activeMenu, ['anggaran_dashboard']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Dashboard Anggaran</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/anggaran/data" class="nav-link <?= in_array($activeMenu, ['anggaran_data']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Data Anggaran</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/relaxtime/hasil_rapat_monev" class="nav-link <?= in_array($activeMenu, ['hasilRapatMonev']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Hasil Rapat Monev bulanan</p>
            </a>
          </li>
      
          <li class="nav-item">
            <hr style="margin: 0;border-style: inset;border-width: 1px;" />
          </li>
          <li class="nav-item">
            <a href="/relaxtime/pengaturan" class="nav-link  <?= in_array($activeMenu, ['relaxtimePengaturan']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Relaxtime Pengaturan</p>
            </a>
          </li>
        </ul>
      </li>
    
    <li id="simandja" class="nav-item <?= in_array($activeMenu, ['simandjaCapaian', 'simandjaRencana', 'simandjaRevisi', 'simandjaMonevPK']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['simandjaCapaian', 'simandjaRencana', 'simandjaRevisi', 'simandjaMonevPK']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-file-signature"></i>
        <p>
          SIMANDJA
          <!-- Monitoring Kinerja dan Anggaran -->
          <i class="fas fa-angle-left right"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/simandja/simandjaCapaian" class="nav-link  <?= in_array($activeMenu, ['simandjaCapaian']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Capaian Kinerja</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/simandja/simandjaMonevPK" class="nav-link  <?= in_array($activeMenu, ['simandjaMonevPK']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>MONEV PK</p>
          </a>
        </li>
        <!--<li class="nav-item">-->
        <!--  <a href="https://bit.ly/MONEV_PK_2023" target="_blank" class="nav-link  ">-->
        <!--    <i class="far fa-dot-circle nav-icon"></i>-->
        <!--    <p>MONEV PK</p>-->
        <!--  </a>    -->
        <!--</li>-->
        <li class="nav-item">
          <a href="/simandja/simandjaRencana" class="nav-link  <?= in_array($activeMenu, ['simandjaRencana']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Rencana Anggaran</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/simandja/simandjaRevisi" class="nav-link  <?= in_array($activeMenu, ['simandjaRevisi']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Revisi Anggaran (Semulmen)</p>
          </a>
        </li>
        <!-- <li class="nav-item">-->
        <!--  <a href="" target="_blank" class="nav-link ">-->
        <!--    <i class="far fa-circle nav-icon"></i>-->
        <!--    <p>SEMULMEN</p>-->
        <!--  </a>-->
        <!--</li>-->
        <li id="kendaraanKantor" class="nav-item">
            <a href="#" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>
                  <small>MONEV CAPUT RPD REALISASI</small>
                  <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>2025 <i class="fas fa-angle-left right"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="https://docs.google.com/spreadsheets/d/1LTIQtiCFiB08eYvdkXloqoH38awvCwSQ/edit?gid=1868851836#gid=1868851836" target="_blank" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>4249</p>
                      </a>
                    </li>
                    <li class="nav-item" tolkit="Monitoring Caput RPD Realisasi">
                      <a href="https://docs.google.com/spreadsheets/d/1MfGJeaRLCCqQVpj5nry60K_iD9HUQd_c/edit?usp=drive_web&ouid=110503911282252705598&rtpof=true" target="_blank" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>4815</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>2026 <i class="fas fa-angle-left right"></i></p>
               </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="https://docs.google.com/spreadsheets/d/1UWRKtYfh1GUBJeQkoi8e8XSm7-ow5S-Y/edit?usp=sharing&ouid=103327788879510797240&rtpof=true&sd=true" target="_blank" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>7960</p>
                      </a>
                    </li>
                    <li class="nav-item" tolkit="Monitoring Caput RPD Realisasi">
                      <a href="https://docs.google.com/spreadsheets/d/1-sMScEAE__GY9hH_i7rlMt8To5qvibUM/edit?usp=drive_link&ouid=103327788879510797240&rtpof=true&sd=true" target="_blank" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>4815</p>
                      </a>
                    </li>
                  </ul>
                </li>
            </ul>
        </li>
      </ul>
    </li>
    
    <li id="kendaraanKantor" class="nav-item <?= in_array($activeMenu, ['daftar_kendaraan', 'riwayat_peminjaman', 'kendaraanKantorPengaturan']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['daftar_kendaraan', 'riwayat_peminjaman', 'kendaraanKantorPengaturan']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-car"></i>
        <p>
          Peminjaman Kendaraan
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/kendaraan_kantor/daftar_kendaraan" class="nav-link  <?= in_array($activeMenu, ['daftar_kendaraan']) ? "active" : ""; ?>">
            <i class="fas fa-car-side nav-icon"></i>
            <p>Daftar Kendaraan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/kendaraan_kantor/riwayat_peminjaman" class="nav-link  <?= in_array($activeMenu, ['riwayat_peminjaman']) ? "active" : ""; ?>">
            <i class="fas fa-calendar-alt nav-icon"></i>
            <p>Riwayat Peminjaman</p>
          </a>
        </li>
        <li class="nav-item">
          <hr style="margin: 0;border-style: inset;border-width: 1px;" />
        </li>
        <li class="nav-item">
          <a href="/kendaraan_kantor/pengaturan" class="nav-link  <?= in_array($activeMenu, ['kendaraanKantorPengaturan']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Verifikator</p>
          </a>
        </li>
      </ul>
    </li>
    
    <li class="nav-header">Aplikasi Substansi</li>
    <!-- APLIKASI PELATUK -->
    <li id="sipedit" class="nav-item <?= in_array($activeMenu, ['uklwFarmasi', 'uklwObat', 'uklwReport', 'uklwPenjamahMakanan', 'uklwLaboratorium', 'uklwPasien', 'uklwPemkes', 'uklwSkriningTBC', 'uklwSkriningHIV', 'pengukuranKebugaran']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['uklwFarmasi', 'uklwObat', 'uklwReport', 'uklwPenjamahMakanan', 'uklwLaboratorium', 'uklwPasien', 'uklwPemkes', 'uklwSkriningTBC', 'uklwSkriningHIV', 'pengukuranKebugaran']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-heartbeat"></i>
        <p>
          PELATUK
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/uklw/pasien" class="nav-link  <?= in_array($activeMenu, ['uklwPasien', 'uklwPenjamahMakanan', 'uklwPemkes', 'uklwSkriningTBC', 'uklwSkriningHIV', 'uklwLaboratorium']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Pemeriksaan Kesehatan</p>
          </a>
        </li>
        <!--<li class="nav-item">-->
        <!--  <a href="/uklw/farmasi" class="nav-link  <?= in_array($activeMenu, ['uklwFarmasi']) ? "active" : ""; ?>">-->
        <!--    <i class="far fa-circle nav-icon"></i>-->
        <!--    <p>Farmasi</p>-->
        <!--  </a>-->
        <!--</li>-->
        <li class="nav-item <?= in_array($activeMenu, ['uklwFarmasi', 'uklwObat']) ? "menu-open" : ""; ?>">
          <a href="#" class="nav-link <?= in_array($activeMenu, ['uklwFarmasi', 'uklwObat']) ? "active" : ""; ?>">
            <i class="nav-icon fas fa-pills"></i>
            <p>
              Farmasi
              <i class="fas fa-angle-left right"></i>
              <span class="badge badge-danger right"></span>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/uklw/farmasi" class="nav-link  <?= in_array($activeMenu, ['uklwFarmasi']) ? "active" : ""; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Manajemen Obat</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/uklw/data_obat" class="nav-link  <?= in_array($activeMenu, ['uklwObat']) ? "active" : ""; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Obat</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="/uklw/laporan" class="nav-link  <?= in_array($activeMenu, ['uklwReport']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Laporan</p>
          </a>
        </li>
        <li class="nav-item">
                      <a href="/pengukuran_kebugaran" class="nav-link <?= in_array($activeMenu, ['pengukuranKebugaran']) ? "active" : ""; ?>">
            <i class="fas fa-running nav-icon"></i>
              <p>Pengukuran Kebugaran</p>
            </a>
        </li>
        <!-- <li class="nav-item">
          <a href="/uklw/penjamah_makanan" class="nav-link  <? //= in_array($activeMenu, []) ? "active" : ""; 
                                                            ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Penjamah Makanan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/uklw/pemkes" class="nav-link  <? //= in_array($activeMenu, []) ? "active" : ""; 
                                                  ?>">
            <i class="far fa-circle nav-icon"></i>
            <p><small>Pemeriksaan Fisik & Riwayat Penyakit</small></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/uklw/skriningTBC" class="nav-link  <? //= in_array($activeMenu, []) ? "active" : ""; 
                                                        ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Skrining TBC</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/uklw/skriningHIV" class="nav-link  <? //= in_array($activeMenu, []) ? "active" : ""; 
                                                        ?>">
            <i class="far fa-circle nav-icon"></i>
            <p><small>Skrining HIV & Penyakit Menular</small></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/uklw/laboratorium" class="nav-link  <? //= in_array($activeMenu, ['']) ? "active" : ""; 
                                                        ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Laboratorium</p>
          </a>
        </li> -->
      </ul>
    </li>
    <!-- APLIKASI PELATUK -->
    
    <!-- APLIKASI PRL -->
    <li id="prl" class="nav-item <?= in_array($activeMenu, ['tpp', 'sab', 'hsbu', 'sivera']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['tpp', 'sab', 'hsbu', 'sivera']) ? "active" : ""; ?>">
        <i class="nav-icon fa fa-shield-virus"></i>
        <p>
          KESLING
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/prl/tpp" class="nav-link  <?= in_array($activeMenu, ['tpp']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>TPP (Tempat Pengelolaan Pangan)</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/prl/sab" class="nav-link  <?= in_array($activeMenu, ['sab']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>SAB (Sarana Air Bersih)</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/prl/hsbu" class="nav-link  <?= in_array($activeMenu, ['hsbu']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>HSBU (Hygiene Sanitasi Bangunan Umum)</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/prl/sivera" class="nav-link  <?= in_array($activeMenu, ['sivera']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>SIVERA (Sistem Informasi Vektor)</p>
          </a>
        </li>
      </ul>
    </li>
    <!-- APLIKASI PRL -->
    
    <!-- APLIKASI PENGAWASAN KAPAL -->
    <li id="pengawasanKapal" class="nav-item <?= in_array($activeMenu, ['pengawasanKapal', 'laporanPengawasanKapal', 'pengawasanKapalAkses', 'laporan_pengawasan_kapal_akses', 'monitoringSanitasiKapal', 'layananKesehatanKapal', 'travel_consent']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['pengawasanKapal', 'laporanPengawasanKapal', 'pengawasanKapalAkses', 'laporan_pengawasan_kapal_akses', 'monitoringSanitasiKapal', 'layananKesehatanKapal', 'travel_consent']) ? "active" : ""; ?>">
        <i class="nav-icon fa fa-ship"></i>
        <p>
          TANGKAP
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/pengawasan_kapal" class="nav-link  <?= in_array($activeMenu, ['pengawasanKapal']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Pengawasan Kapal</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/travelconsent/index" class="nav-link  <?= in_array($activeMenu, ['travel_consent']) ? "active" : ""; ?>">
            <i class="fas fa-plane nav-icon"></i>
            <p>Travel Consent Form</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/layanan_kesehatan_kapal" class="nav-link  <?= in_array($activeMenu, ['layananKesehatanKapal']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Layanan Kesehatan Kapal</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/laporan_pengawasan_kapal" class="nav-link  <?= in_array($activeMenu, ['laporanPengawasanKapal']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Laporan Pengawasan Kapal</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/monitoring_sanitasi_kapal" class="nav-link  <?= in_array($activeMenu, ['monitoringSanitasiKapal']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Monitoring Sanitasi Kapal</p>
          </a>
        </li>
        <li class="nav-item">
            <hr style="margin: 0;border-style: inset;border-width: 1px;" />
          </li>
        <li class="nav-item">
          <a href="/pengawasan_kapal_akses" class="nav-link  <?= in_array($activeMenu, ['pengawasanKapalAkses']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Pengawasan Kapal Akses</p>
          </a>
        </li>
      </ul>
    </li>
    <!-- APLIKASI PENGAWASAN KAPAL -->
    
    
    <li class="nav-header">ARSIP</li>
    <!-- STORAGE SK -->
    <?php
        $isSkMenu = str_starts_with($activeMenu, 'sk_');
    ?>
    <li id="surat_keputusan" class="nav-item <?= $isSkMenu ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= $isSkMenu ? "active" : ""; ?>">
        <i class="nav-icon fas fa-mail-bulk"></i>
        <p>
          SK dan Peraturan Storage
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/surat_sk/2025" class="nav-link  <?= in_array($activeMenu, ['sk_2025']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>2025</p>
          </a>
        </li>
        <li class="nav-item <?= $isSkMenu ? "menu-open" : ""; ?>">
          <a href="/surat_sk/2026" class="nav-link <?= $isSkMenu ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>2026</p><i class="fas fa-angle-left right"></i>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/surat_sk/2026/sk" class="nav-link <?= in_array($activeMenu, ['sk_2026_sk']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>SK</p>
              </a>
            </li>
    
            <li class="nav-item">
              <a href="/surat_sk/2026/nota-dinas" class="nav-link <?= in_array($activeMenu, ['sk_2026_nota_dinas']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Nota Dinas</p>
              </a>
            </li>
    
            <li class="nav-item">
              <a href="/surat_sk/2026/peraturan" class="nav-link <?= in_array($activeMenu, ['sk_2026_peraturan']) ? "active" : ""; ?>">
                <i class="far fa-dot-circle nav-icon"></i>
                <p>Peraturan</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    
    <!-- APLIKASI SIPEDIT -->
    <li id="sipedit" class="nav-item <?= in_array($activeMenu, ['sipeditArsipSaya']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['sipeditArsipSaya']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-book"></i>
        <p>
          SIPEDIT (Arsip Pelatihan)
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/sipedit/arsip_saya" class="nav-link  <?= in_array($activeMenu, ['sipeditArsipSaya']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Arsip Saya</p>
          </a>
        </li>
      </ul>
    </li>
    <!-- APLIKASI SIPEDIT -->
    
    <!-- APLIKASI LHKPN DAN SPT -->
   
    <li id="lhkpnSpt" class="nav-item <?= in_array($activeMenu, ['lhkpnSpt']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['lhkpnSpt']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-folder"></i>
        <p>
          LHKPN DAN SPT
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/lhkpn_spt/daftar_dokumen" class="nav-link  <?= in_array($activeMenu, ['lhkpnSpt']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Daftar Dokumen</p>
          </a>
        </li>
      </ul>
    </li>
   
    <!-- APLIKASI LHKPN DAN SPT -->
    
    <!-- APLIKASI SIPEPES -->
    <li id="sop" class="nav-item <?= in_array($activeMenu, ['tambah_dokumen', 'daftar_sop', 'lainnya']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['tambah_dokumen', 'daftar_sop', 'lainnya']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-copy"></i>
        <p>
          SIPEPES (SOP)
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/sipepes/tambah_dokumen_sop" class="nav-link  <?= in_array($activeMenu, ['tambah_dokumen']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Tambah Dokumen</p>
          </a>
        </li>
        <li class="nav-header">
          <hr style="margin: 0;border-style: inset;border-width: 1px;" />
        </li>
        <li class="nav-item">
          <a href="/sipepes/sop" class="nav-link  <?= in_array($activeMenu, ['daftar_sop']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Daftar SOP</p>
          </a>
        </li>
        
        <!--<li class="nav-item">-->
        <!--  <a href="/sipepes/utama" class="nav-link  <?//= in_array($activeMenu, ['utama']) ? "active" : ""; ?>">-->
        <!--    <i class="far fa-circle nav-icon"></i>-->
        <!--    <p>SOP Proses Utama</p>-->
        <!--  </a>-->
        <!--</li>-->
        <!--<li class="nav-item">-->
        <!--  <a href="/sipepes/pendukung" class="nav-link  <?//= in_array($activeMenu, ['pendukung']) ? "active" : ""; ?>">-->
        <!--    <i class="far fa-circle nav-icon"></i>-->
        <!--    <p>SOP Proses Pendukung</p>-->
        <!--  </a>-->
        <!--</li>-->
        <li class="nav-item">
          <a href="/sipepes/lainnya" class="nav-link  <?= in_array($activeMenu, ['lainnya']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>SOP Proses Lainnya</p>
          </a>
        </li>
      </ul>
    </li>
    <!-- APLIKASI SIPEPES -->
    
    <!-- APLIKASI WBK -->
    <li id="wbk" class="nav-item <?= in_array($activeMenu, ['rencana_kerja', 'daftar_dokumen']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['rencana_kerja', 'daftar_dokumen']) ? "active" : ""; ?>">
        <i class="nav-icon fab fa-google-drive"></i>
        <p>
          WBK STORAGE
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/wbk/rencana_kerja" class="nav-link  <?= in_array($activeMenu, ['rencana_kerja']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Rencana Kerja</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/wbk/daftar_dokumen" class="nav-link  <?= in_array($activeMenu, ['daftar_dokumen']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Daftar Dokumen</p>
          </a>
        </li>
      </ul>
    </li>
    <!-- APLIKASI WBK -->
    
    <!-- APLIKASI WBK -->
    <li id="arkab" class="nav-item <?= in_array($activeMenu, ['arsip_surat', 'template_naskah', 'tambah_naskah']) ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['arsip_surat', 'template_naskah', 'tambah_naskah']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-archive"></i>
        <p>
          Tata Naskah Dinas
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <!--<ul class="nav nav-treeview">-->
      <!--  <li class="nav-item">-->
      <!--    <a href="/arkab/arsip_surat" class="nav-link  <?= in_array($activeMenu, ['arsip_surat']) ? "active" : ""; ?>">-->
      <!--      <i class="far fa-circle nav-icon"></i>-->
      <!--      <p>Arsip Surat</p>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--</ul>-->
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="/tata_naskah_dinas/template_naskah" class="nav-link  <?= in_array($activeMenu, ['template_naskah']) ? "active" : ""; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Template Naskah</p>
          </a>
        </li>
      </ul>
    </li>
    <!-- APLIKASI ARKAB -->

    <li class="nav-header">Link External</li>
    <li id="wbk" class="nav-item <?= in_array($activeMenu, ['portalLinkSet']) ? "menu-open" : ""; ?> <?= (isset($segmentNow) && $segmentNow != "") ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['portalLinkSet']) ? "active" : ""; ?>">
        <i class="nav-icon fas fa-link"></i>
        <p>
          Portal Link
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview portal-link-menu">
        <li class="nav-item">
          <a href="/portal_link/setLink" class="nav-link <?= in_array($activeMenu, ['portalLinkSet']) ? "active" : ""; ?>">
            <i class="fas fa-cogs nav-icon"></i>
            <p>Management Portal Link</p>
          </a>
        </li>
        <li class="nav-item">
          <hr style="margin: 0;border-style: inset;border-width: 1px;" />
        </li>
        <div class="portal-link-get-menu">
          <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
        </div>
      </ul>
    </li>
    <li id="wbk" class="nav-item <?= in_array($activeMenu, ['iframeSetLink']) ? "menu-open" : ""; ?> <?= (isset($segmentNow) && $segmentNow != "") ? "menu-open" : ""; ?>">
      <a href="#" class="nav-link <?= in_array($activeMenu, ['iframeSetLink']) ? "active" : ""; ?> <?= (isset($segmentNow) && $segmentNow != "") ? "active" : ""; ?>">
        <i class="nav-icon fas fa-external-link-alt"></i>
        <p>
          Konten Tersemat
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-danger right"></span>
        </p>
      </a>
      <ul class="nav nav-treeview iframe-menu">
        <li class="nav-item">
          <a href="/iframe/setLink" class="nav-link <?= in_array($activeMenu, ['iframeSetLink']) ? "active" : ""; ?>">
            <i class="fas fa-cogs nav-icon"></i>
            <p>Management Link</p>
          </a>
        </li>
        <li class="nav-item">
          <hr style="margin: 0;border-style: inset;border-width: 1px;" />
        </li>
        <div class="iframe-get-menu">
          <i class='fas fa-sync-alt fa-spin'></i> <span class='text-bold pt-2'>Loading...</span>
        </div>
      </ul>
    </li>
    
    <li class="nav-header">
      <hr style="margin: 0;border-style: inset;border-width: 1px;" />
    </li>
    
    <!-- MENU ADMIN -->
    <?php if (in_array('Admin', session()->get('accessMenu'))) : ?>
      <li id="admin" class="nav-item <?= in_array($activeMenu, ['user', 'unit_kerja', 'pegawai', 'skema_titimangsa', 'jenis', 'feedback']) ? "menu-open" : ""; ?>">
        <a href="#" class="nav-link <?= in_array($activeMenu, ['user', 'unit_kerja', 'pegawai', 'skema_titimangsa', 'jenis', 'feedback']) ? "active" : ""; ?>">
          <i class="nav-icon fas fa-user-cog"></i>
          <p>
            Admin
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-danger right"></span>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="/admin/user" class="nav-link <?= in_array($activeMenu, ['user']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/feedback" class="nav-link <?= in_array($activeMenu, ['feedback']) ? "active" : ""; ?>">
              <i class="fas fa-comment-dots nav-icon"></i>
              <p>Feedback & Saran</p>
            </a>
          </li>
          <li class="nav-header">Data Master</li>
          <li class="nav-item">
            <a href="/admin/unit_kerja" class="nav-link <?= in_array($activeMenu, ['unit_kerja']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Unit Kerja</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/pegawai" class="nav-link <?= in_array($activeMenu, ['pegawai']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Pegawai</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/skema_titimangsa" class="nav-link <?= in_array($activeMenu, ['skema_titimangsa']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Skema Titimangsa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/jenis" class="nav-link <?= in_array($activeMenu, ['jenis']) ? "active" : ""; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Jenis Surat</p>
            </a>
          </li>
        </ul>
      </li>
      <!-- MENU ADMIN -->
      
    <?php endif; ?>
    <li id="logout" class="nav-item">
      <a href="javascript:void(0)" id="coba" data-location="/auth/logout" onclick="logoutSweetAlert(this)" class="nav-link">
        <i class="nav-icon fas fa-door-open"></i>
        <p>
          Keluar
        </p>
      </a>
    </li>
  </ul>
</nav>
