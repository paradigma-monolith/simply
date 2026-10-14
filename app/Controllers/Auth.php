<?php

namespace App\Controllers;
use Config\Services;

class Auth extends BaseController
{
  public function view_login()
  {
  if (session()->get('username')) {
      return redirect()->to('/home');
    }
    return view('view_login');
  }
  public function view_login_v2()
  {
    if (session()->get('username')) {
      return redirect()->to('/home');
    }
    return view('view_login_v2');
  }
  public function index()
  {
    return view('welcome');
  }
  public function newIndex()
  {
    $data['jmlPegawaiPerWilker'] =  $this->unitKerjaModel->select("COUNT(nama) AS total, unit_kerja.unit_kerja")
      ->join('data_pegawai', 'unit_kerja.id_unit_kerja = data_pegawai.id_unit_kerja', 'left')
      ->whereNotIn('nip', [
        '000000000000000001', '000000000000000002', '000000000000000003',
        '000000000000000004', '000000000000000005', '000000000000000006',
        '000000000000000007', '000000000000000008', '000000000000000009'
      ])
      ->groupBy('unit_kerja')
      ->findAll();
    $unit_kerja = $this->unitKerjaModel->findAll();

    foreach ($unit_kerja as $key) {
      $found = false;
      foreach ($data['jmlPegawaiPerWilker'] as $keys) {
        if ($key['unit_kerja'] == $keys['unit_kerja']) {
          $found = true;
          break;
        }
      }
      if (!$found) {
        $data['jmlPegawaiPerWilker'][] = [
          'total' => 0,
          'unit_kerja' => $key['unit_kerja']
        ];
      }
    }
    $data['unit_kerja'] = $this->unitKerjaModel->select("unit_kerja")->findAll();
    
    return view('infografis_news', $data);
  }
  public function index1()
  {
    $data['title'] = "Sistem Informasi BKK Bandung";

    return view('login', $data);
  }
  public function login()
  {
    $username  = $this->request->getVar('username');
    $password  = $this->request->getVar('password');
    $login     = $this->request->getVar('login');

    $value = $this->isLogin($username, $password, $login);

    if ($value == true) {
      return redirect()->to('home');
    } else {

      if ($login == 'login1') {
        $login = '/';
      } else if ($login == 'login2') {
        $login = '/auth/login';
      } else {
        die();
      }

      return redirect()->to($login);
    }
  }
  public function login_v2()
  {
    $username  = $this->request->getVar('username');
    $password  = $this->request->getVar('password');

    $value = $this->isLogin($username, $password);

    if ($value) {
      return redirect()->to('/home/profil');
    } else {
      return redirect()->to('/auth/login-v2');
    }
  }
  public function isLogin($username, $password, $login = null)
  {
    $data = $this->userModel
      ->select("*, user.id_user, user.nama, user.email, data_pegawai.nip")
      ->join('role_access', 'user.id_role_access = role_access.id_role_access')
      ->join('data_pegawai', 'user.id_user=data_pegawai.id_user', 'left')
      ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi', 'left')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja', 'left')
      ->where('username', $username)->first();
    
    if ($data) {

    //   if (true) {
      if (password_verify($password, $data['password'])) {
          
        // membuat session
        
        $adminAbsen = $this->userModel
              ->select('status')
              ->join('absen_akses', 'absen_akses.id_user=user.id_user', 'LEFT')
              ->where("user.username", $username)
              ->first();
        if ($adminAbsen['status'] == 'Akses Diterima') {
            session()->setFlashdata('aksesAdmin', $adminAbsen['status']);
        }

        // Declare an associative array
        $arr = array(
          "a" => "avatar-01.png",
          "b" => "avatar-02.png",
          "c" => "avatar-03.png",
          "d" => "avatar-04.png",
          "e" => "avatar-05.png",
          "f" => "avatar-06.png",
          "g" => "avatar-07.png",
          "h" => "avatar-08.png",
          "i" => "avatar-09.png",
          "j" => "avatar-10.png",
          "k" => "avatar-11.png",
          "l" => "avatar-12.png",
        );
        $arr1 = array(
          "a" => "avatar-03.png",
          "b" => "avatar-06.png",
          "c" => "avatar-08.png",
          "d" => "avatar-09.png",
          "e" => "avatar-11.png",
          "f" => "avatar-12.png",
        );
        $arr2 = array(
          "a" => "avatar-01.png",
          "b" => "avatar-02.png",
          "c" => "avatar-04.png",
          "d" => "avatar-05.png",
          "e" => "avatar-07.png",
          "f" => "avatar-10.png",
        );

        shuffle($arr);
        shuffle($arr1);
        shuffle($arr2);

        if ($data['role'] == 'Admin') {
          $accessMenu = ['Dashboard', 'Admin', 'Infografis', 'Data Infografis', 'Validation Infografis'];
        }
        if ($data['role'] == 'Pegawai') {
          $accessMenu = ['Dashboard'];
        }
        if ($data['role'] == 'PNBP') {
          $accessMenu = ['Dashboard', 'Infografis', 'Data Infografis', 'Validation Infografis'];
        }
        if ($data['role'] == 'Verifikator') {
          $accessMenu = [''];
        }

        $cekJK = $this->userModel
          ->join('data_pegawai', 'data_pegawai.id_user=user.id_user')
          ->where('user.id_user', $data['id_user'])
          ->first();
        $jk = $arr[0];
        if ($cekJK) {
          $cek = substr($cekJK['nip'], 14, 1);

          if ($cek == 1) {
            $jk = $arr1[0];
          }
          if ($cek == 2) {
            $jk = $arr2[0];
          }
        }

        $ses_data = [
          'id_user'     => $data['id_user'],
          'nama'        => $data['nama'],
          'username'    => $data['username'],
          'role'        => $data['role'],
          'substansi'   => $data['nama_substansi'] == null ? 'Default' : $data['nama_substansi'],
          'unit_kerja'  => $data['unit_kerja'] ?? 'Default',
          'picture'     => $jk,
          'accessMenu'  => $accessMenu
        ];

        $timeline = [
          'icon'        => 'fas fa-sign-in-alt bg-info',
          'id_table'    => $data['id_role_access'],
          'table'       => 'user',
          'id_user'     => $data['id_user'],
          'aktivitas'   => 'masuk kedalam sistem',
          'keterangan'  => "",
        ];
        if ($username != 197306292002121003)
          $this->linimasaModel->save($timeline);

        if ($data['image'] != "" || $data['image'] != null) {
          $ses_data['picture'] = $data['image'];
        }
        
        // Cek ulang tahun dari NIP
        if (isset($data['nip']) && !empty($data['nip']) && strlen($data['nip']) >= 8) {
          $this->checkBirthdayFromNIP($data['nip'], $data['nama']);
        }
        
        session()->set($ses_data);
        // dd(session()->get('nama')); // pemanggilan session
        // return redirect()->to('/admin');
        if ($this->request->getVar('password') == '12345678')
          session()->setFlashdata('gantiPassword', 'Ganti Password!');

        return true;
      } else {

        // jika password salah
        session()->setFlashdata('error', 'Password yang dimasukkan salah!');
        return false;
      }
    } else {
      // jika password salah
      session()->setFlashdata('error', 'Akun tidak tersedia!');
      return false;
    }
    
      dd();
  }
  
  public function logout()
  {
    $session = session();
    $data = $this->userModel->join('role_access', 'user.id_role_access = role_access.id_role_access')->where('username', session()->get('username'))->first();

    if ($data) {
      $timeline = [
        'icon'        => 'fas fa-sign-out-alt bg-primary',
        'id_table'    => $data['id_role_access'],
        'table'       => 'user',
        'id_user'     => $data['id_user'],
        'aktivitas'   => 'keluar dari sistem',
        'keterangan'  => "",
      ];
      if($data['username'] != 197306292002121003)
        $this->linimasaModel->save($timeline);
    }

    session()->set([
      'nama'        => '',
      'username'    => '',
      'role'        => '',
      'picture'     => '',
      'accessMenu'  => ''
    ]);
    $session->destroy();
    session()->setFlashdata('info', 'Terimakasih atas kerjanya..');
    return redirect()->to('/setLogout');
  }
  public function setLogout()
  {
    session()->setFlashdata('info', 'Terimakasih atas kerjanya..');
    return redirect()->to('/auth/login');
  }
  
  /**
   * Fungsi untuk mengecek ulang tahun dari 8 digit pertama NIP
   * Format NIP: YYYYMMDDXXXXXXXXXX
   * 8 digit pertama: YYYYMMDD (tahun, bulan, tanggal)
   */
  private function checkBirthdayFromNIP($nip, $nama)
  {
    // Ambil 8 digit pertama dari NIP
    $birthDateString = substr($nip, 0, 8);
    
    // Validasi format (harus 8 digit angka)
    if (strlen($birthDateString) != 8 || !is_numeric($birthDateString)) {
      return;
    }
    
    // Ekstrak tahun, bulan, dan tanggal
    $tahun = substr($birthDateString, 0, 4);
    $bulan = substr($birthDateString, 4, 2);
    $tanggal = substr($birthDateString, 6, 2);
    
    // Validasi tanggal
    if (!checkdate($bulan, $tanggal, $tahun)) {
      return;
    }
    
    // Buat tanggal lahir
    $birthDate = $tahun . '-' . $bulan . '-' . $tanggal;
    
    // Dapatkan tanggal hari ini
    $today = date('Y-m-d');
    
    // Dapatkan tanggal ulang tahun tahun ini
    $currentYear = date('Y');
    $thisYearBirthday = $currentYear . '-' . $bulan . '-' . $tanggal;
    
    // Cek apakah hari ini adalah ulang tahun
    if ($today === $thisYearBirthday) {
      // Hitung umur
      $age = $currentYear - $tahun;
      
      // Array ucapan selamat ulang tahun yang friendly
      $birthdayMessages = [
        [
          "title" => "🎉 Selamat Ulang Tahun yang ke-{$age}!",
          "message" => "Wah, hari ini hari spesial nih! Selamat ulang tahun yang ke-{$age}! Semoga tahun ini penuh dengan kebahagiaan, kesehatan, dan sukses terus ya. Makasih udah jadi bagian dari keluarga besar kita!",
          "icon" => "success"
        ],
        [
          "title" => "🎂 Happy Birthday!",
          "message" => "Horeee! Hari ini hari ulang tahun kamu yang ke-{$age}! Semoga semua impian kamu tercapai, semua harapan kamu terwujud, dan setiap hari kamu penuh dengan kebahagiaan. Keep shining!",
          "icon" => "success"
        ],
        [
          "title" => "🎈 Selamat Bertambah Usia!",
          "message" => "Wah, udah {$age} tahun aja nih! Selamat ulang tahun! Semoga tahun ini jadi tahun terbaik buat kamu, penuh dengan pencapaian keren dan kebahagiaan yang gak ada habisnya. You rock!",
          "icon" => "success"
        ],
        [
          "title" => "🎊 Selamat Ulang Tahun!",
          "message" => "Hari ini hari yang super special! Selamat ulang tahun yang ke-{$age}! Semoga selalu sehat, bahagia, dan sukses terus ya. Kamu udah jadi inspirasi buat kita semua!",
          "icon" => "success"
        ],
        [
          "title" => "🎁 Happy Birthday!",
          "message" => "Yey! Hari ini hari ulang tahun kamu! Selamat yang ke-{$age}! Semoga semua cita-cita kamu tercapai, semua mimpi kamu jadi kenyataan, dan hidup kamu penuh dengan kebahagiaan. Go get it!",
          "icon" => "success"
        ],
        [
          "title" => "🌟 Selamat Ulang Tahun!",
          "message" => "Wah, hari ini hari yang penuh cahaya! Selamat ulang tahun yang ke-{$age}! Semoga selalu sehat, bahagia, dan beruntung dalam semua hal. Kamu bener-bener amazing!",
          "icon" => "success"
        ],
        [
          "title" => "🎪 Selamat Bertambah Usia!",
          "message" => "Horeee! Udah {$age} tahun aja! Selamat ulang tahun! Semoga tahun ini penuh dengan petualangan seru, pencapaian keren, dan kebahagiaan yang melimpah. You're awesome!",
          "icon" => "success"
        ],
        [
          "title" => "🎯 Happy Birthday!",
          "message" => "Selamat ulang tahun yang ke-{$age}! Hari ini hari yang super special! Semoga semua hal berjalan lancar, semua usaha kamu berhasil, dan kebahagiaan selalu menyertai kamu. Keep being awesome!",
          "icon" => "success"
        ]
      ];
      
      // Pilih ucapan secara random
      $selectedMessage = $birthdayMessages[array_rand($birthdayMessages)];
      
      // Set flashdata untuk popup ulang tahun
      session()->setFlashdata('birthday_popup', [
        'title' => $selectedMessage['title'],
        'message' => $selectedMessage['message'],
        'icon' => $selectedMessage['icon'],
        'nama' => $nama,
        'age' => $age,
        'birthday' => $this->formatBirthdayIndonesian($tanggal, $bulan, $tahun),
        'tahun_lahir' => $tahun,
        'bulan_lahir' => $bulan,
        'tanggal_lahir' => $tanggal
      ]);
    }
  }
  
  /**
   * Fungsi untuk memformat tanggal lahir ke format Indonesia
   */
  private function formatBirthdayIndonesian($tanggal, $bulan, $tahun)
  {
    $bulanIndonesia = [
      '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
      '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
      '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    
    return $tanggal . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahun;
  }
  
  public function updateProfilePegawai()
  {

    if ($this->request->isAJAX()) {
      $msg = [];
      $by = $this->userModel
        ->join('data_pegawai', 'data_pegawai.id_user=user.id_user')
        ->where('username', session()->get("username"))->first();
      $dataSebelumnya = $this->dataPegawaiModel
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
        ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
        ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')
        ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi')
        ->join('tim_kerja', 'tim_kerja.id_tim_kerja=data_pegawai.id_tim_kerja')
        ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
        ->where('id_data_pegawai', $by['id_data_pegawai'])
        ->first();

      $id_user            = $by['id_user'];
      $id_unit_kerja      = $this->request->getVar("unit_kerja");
      $id_golongan        = $this->request->getVar("golongan");
      $id_jabatan         = $this->request->getVar("jabatan");
      $id_substansi       = $this->request->getVar("substansi");
      $id_tim_kerja       = $this->request->getVar("tim_kerja");
      $id_status_pegawai  = $this->request->getVar("status_pegawai");
      $nama               = $this->request->getVar("nama_pegawai");
      $nip                = $this->request->getVar("nip");
      $tgl_awal_masa      = $this->request->getVar("tgl_masuk");
      $pelatihan_jafung_status = $this->request->getVar("pelatihan_jafung_status");
      $pelatihan_jafung_tahun = $this->request->getVar("pelatihan_jafung_tahun");
      $pelatihan_jafung_nama = $this->request->getVar("pelatihan_jafung_nama");
      $pelatihan_jafung_sertifikat = $this->request->getVar("pelatihan_jafung_sertifikat");

      $data = [
        'id_data_pegawai'    => $by['id_data_pegawai'],
        'id_user'            => $id_user,
        'id_unit_kerja'      => $id_unit_kerja,
        'id_golongan'        => $id_golongan,
        'id_jabatan'         => $id_jabatan,
        'id_substansi'       => $id_substansi,
        'id_tim_kerja'       => $id_tim_kerja,
        'id_status_pegawai'  => $id_status_pegawai,
        'nama'               => $nama,
        'nip'                => $nip,
        'tgl_awal_masa'      => $tgl_awal_masa,
        'pelatihan_jafung_status' => $pelatihan_jafung_status,
        'pelatihan_jafung_tahun' => $pelatihan_jafung_status == 'Sudah' ? $pelatihan_jafung_tahun : null,
        'pelatihan_jafung_nama' => $pelatihan_jafung_status == 'Sudah' ? $pelatihan_jafung_nama : null,
        'pelatihan_jafung_sertifikat' => $pelatihan_jafung_status == 'Sudah' ? $pelatihan_jafung_sertifikat : null,
      ];
      $cekNip = $this->dataPegawaiModel->where('nip', $nip)->first();

      $save = false;
      if ($cekNip) {
        if ($cekNip['nip'] == $dataSebelumnya['nip']) {
          $save = true;
        }
      } else {
        $save = true;
      }

      if ($save == true) {
        if ($this->dataPegawaiModel->save($data)) {

          // ============================================================================================================================
          $newId = $by['id_data_pegawai'];
          $dataPegawai = $this->dataPegawaiModel
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
            ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
            ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')
            ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi')
            ->join('tim_kerja', 'tim_kerja.id_tim_kerja=data_pegawai.id_tim_kerja')
            ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
            ->where('id_data_pegawai', $by['id_data_pegawai'])
            ->first();

          $linimasa = false;
          $elemen = '';
          $aktivitas = '';
          if ($dataSebelumnya['unit_kerja'] != $dataPegawai['unit_kerja']) {
            $elemen .= "<div class='col-12'><strong>Unit Kerja</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['unit_kerja'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['unit_kerja'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['golongan'] != $dataPegawai['golongan']) {
            $elemen .= "<div class='col-12'><strong>Golongan</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['golongan'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['golongan'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['jabatan'] != $dataPegawai['jabatan']) {
            $elemen .= "<div class='col-12'><strong>Jabatan</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['jabatan'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['jabatan'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['nama_substansi'] != $dataPegawai['nama_substansi']) {
            $elemen .= "<div class='col-12'><strong>Substansi</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['nama_substansi'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['nama_substansi'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['nama_tim_kerja'] != $dataPegawai['nama_tim_kerja']) {
            $elemen .= "<div class='col-12'><strong>Tim Kerja</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['nama_tim_kerja'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['nama_tim_kerja'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['status_pegawai'] != $dataPegawai['status_pegawai']) {
            $elemen .= "<div class='col-12'><strong>Status Pegawai</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['status_pegawai'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['status_pegawai'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['nama'] != $dataPegawai['nama']) {

            $updateUser = [
              'id_user' => $by['id_user'],
              'nama' => $nama,
            ];
            $this->userModel->save($updateUser);

            $elemen .= "<div class='col-12'><strong>Alasan Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['nama'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['nama'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['nip'] != $dataPegawai['nip']) {
            $elemen .= "<div class='col-12'><strong>Alasan Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['nip'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['nip'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['tgl_awal_masa'] != $dataPegawai['tgl_awal_masa']) {
            $elemen .= "<div class='col-12'><strong>Alasan Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['tgl_awal_masa'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['tgl_awal_masa'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['pelatihan_jafung_status'] != $data['pelatihan_jafung_status']) {
            $elemen .= "<div class='col-12'><strong>Status Pelatihan JaFung</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['pelatihan_jafung_status'] . "</div>
          <div class='col-6 border'>" . $data['pelatihan_jafung_status'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['pelatihan_jafung_tahun'] != $data['pelatihan_jafung_tahun']) {
            $elemen .= "<div class='col-12'><strong>Tahun Pelatihan JaFung</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['pelatihan_jafung_tahun'] . "</div>
          <div class='col-6 border'>" . $data['pelatihan_jafung_tahun'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['pelatihan_jafung_nama'] != $data['pelatihan_jafung_nama']) {
            $elemen .= "<div class='col-12'><strong>Nama Pelatihan JaFung</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['pelatihan_jafung_nama'] . "</div>
          <div class='col-6 border'>" . $data['pelatihan_jafung_nama'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }
          if ($dataSebelumnya['pelatihan_jafung_sertifikat'] != $data['pelatihan_jafung_sertifikat']) {
            $elemen .= "<div class='col-12'><strong>Sertifikat Pelatihan JaFung</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['pelatihan_jafung_sertifikat'] . "</div>
          <div class='col-6 border'>" . $data['pelatihan_jafung_sertifikat'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan perubahan data profile';
          }

          if ($linimasa == true) {
            $msg = [
              'data' => 'sukses',
              'namaBaru' => $nama
            ];
            $timeline = [
              'icon'        => 'fas fa-user-edit bg-warning',
              'id_table'    => $newId,
              'table'       => 'data_pegawai',
              'id_user'     => $by['id_user'],
              'aktivitas'   => $aktivitas,
              'keterangan'  => "memperbaharui profile pegawai" . "
                              <div class='row pl-4 pr-4 pt-2'>
                              " . $elemen . "
                              </div>"
            ];
            $this->linimasaModel->save($timeline);


            // Ubah nilai 'nama'
            $ses_data['nama'] = name_capitalize($nama);
            session()->set($ses_data);
          }
          // ============================================================================================================================        
        }
      } else {
        $msg = [
          'data' => 'unique',
          'pesan' => 'data NIP/NOPEG anda sama dengan ' . $cekNip['nama']
        ];
      }
      echo json_encode($msg);
    }
  }
  public function updateUser()
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $by = $this->userModel
        ->where('username', session()->get("username"))->first();
      $data = [
        'id_user' => $by['id_user']
      ];

      $username = $this->request->getVar("username");
      $password = $this->request->getVar("password");
      $password2 = $this->request->getVar("password2");
      $password3 = $this->request->getVar("password3");
      $save = false;

      if ($password != "" && $password2 != "" && $password3 != "") {
        if (password_verify($password, $by['password'])) {
            
          if ($password2 == $password3) {

            $password = password_hash($this->request->getVar('password2'), PASSWORD_DEFAULT);
            $data += [
              'password' => $password
            ];
            $save = true;
          } else {
            $msg = [
              'data' => 'unique',
              'pesan' => 'Password konfirmasi anda tidak sama'
            ];
          }
        } else {
          $msg = [
            'data' => 'unique',
            'pesan' => 'Password yang anda masukkan salah'
          ];
        }
      }


      if ($username != "") {
        $cekUsername = $this->userModel->where("username", $username)->first();
        if ($cekUsername) {
          if ($cekUsername['username'] == $by['username']) {
            $save = true;
            $data += [
              'username' => $username
            ];
          } else {
            $msg = [
              'data' => 'unique',
              'pesan' => 'Username baru anda sama dengan ' . $cekUsername['nama']
            ];
          }
        } else {
          $save = true;
          $data += [
            'username' => $username
          ];
        }
      }

      if ($save == true) {
        if ($this->userModel->save($data)) {

          // ============================================================================================================================
          $newId = $by['id_user'];
          $dataPegawai = $this->userModel
            ->where('id_user', $by['id_user'])
            ->first();

          $linimasa = false;
          $elemen = '';
          $aktivitas = '';
          if ($by['username'] != $dataPegawai['username']) {
            $elemen .= "<div class='col-12'><strong>Username</strong></div>
          <div class='col-6 border'>" . $by['username'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['username'] . "</div>";
            $linimasa = true;
            $aktivitas = 'melakukan pembaharuan pada username';
          }
          if ($by['password'] != $dataPegawai['password']) {
            $linimasa = true;
            $aktivitas = 'melakukan pembaharuan pada password';
          }
          if ($linimasa == true) {
            $msg = [
              'data' => 'sukses'
            ];
            $timeline = [
              'icon'        => 'fas fa-user-edit bg-warning',
              'id_table'    => $newId,
              'table'       => 'user',
              'id_user'     => $by['id_user'],
              'aktivitas'   => $aktivitas,
              'keterangan'  => "<div class='row pl-4 pr-4 pt-2'>
                              " . $elemen . "
                              </div>"
            ];
            $this->linimasaModel->save($timeline);


            // Ubah nilai 'nama'
            if ($username != "") {
              $ses_data['username'] = $username;
              session()->set($ses_data);
            }
          }
          // ============================================================================================================================        
        }
      }
      echo json_encode($msg);
    }
  }
  public function updateKontak()
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $by = $this->userModel
        ->where('username', session()->get("username"))->first();
      $data = [
        'id_user' => $by['id_user']
      ];

      $email = $this->request->getVar("email");
      $no_telp = $this->request->getVar("no_telp");
      $instagram = $this->request->getVar("instagram");
      $link_instagram = $this->request->getVar("link_instagram");
      $facebook = $this->request->getVar("facebook");
      $link_facebook = $this->request->getVar("link_facebook");
      $lainnya = $this->request->getVar("lainnya");
      $link_lainnya = $this->request->getVar("link_lainnya");

      $data += [
        'email' => $email,
        'no_telp' => $no_telp,
        'instagram' => $instagram,
        'link_instagram' => $link_instagram,
        'facebook' => $facebook,
        'link_facebook' => $link_facebook,
        'lainnya' => $lainnya,
        'link_lainnya' => $link_lainnya,
      ];
      $whatsapp = $no_telp;
      if ($whatsapp != "") {
        if (substr($whatsapp, 0, 1) === '0') {
          // Ganti 0 di awal dengan 62
          $whatsapp = 'https://wa.me/62' . substr($whatsapp, 1);
        } else {
          // Jika tidak dimulai dengan 0, biarkan string tetap sama
          $whatsapp = 'https://wa.me/' . $whatsapp;
        }
      }

      if ($this->userModel->save($data)) {
        $newId = $by['id_user'];
        $msg = [
          'data' => 'sukses',
          'kontak0' => '<span onclick="openBoxEmail(this)" data-email="' . $email . '" data-toggle="tooltip" data-placement="top" title="Kirim Email"><i class="fas fa-envelope"></i> ' . $email . '</span>',
          'kontak1' => '<a href="' . $whatsapp . '" target="_blank"><i class="fab fa-whatsapp"></i> ' . $no_telp . '</a>',
          'kontak2' => '<a href="' . $link_instagram . '" target="_blank"><i class="fab fa-instagram"></i> ' . $instagram . '</a>',
          'kontak3' => '<a href="' . $link_facebook . '" target="_blank"><i class="fab fa-facebook"></i> ' . $facebook . '</a>',
          'kontak4' => '<a href="' . $link_lainnya . '" target="_blank">' . $lainnya . '</a>',
        ];

        $timeline = [
          'icon'        => 'fas fa-user-edit bg-warning',
          'id_table'    => $newId,
          'table'       => 'user',
          'id_user'     => $by['id_user'],
          'aktivitas'   => "memperbaharui kontak",
          'keterangan'  => ""
        ];
        $this->linimasaModel->save($timeline);
      }

      echo json_encode($msg);
    }
  }
  public function updatePhoto()
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $by = $this->userModel
        ->where('username', session()->get("username"))->first();

      // =======================================================================================================================
      $file = $this->request->getFile('image');

      if ($file) {
        // Periksa apakah file diunggah dan ukurannya valid
        if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['jpg', 'jpeg', 'png'])) {
          // Periksa ukuran file
          $maxFileSize = 1024 * 1024 * 5; // Misalnya, batas ukuran file adalah 5 MB

          if ($file->getSize() <= $maxFileSize) {
            // Ukuran file memenuhi syarat, lanjutkan dengan pemrosesan

            // Ubah nama file menjadi bilangan unik/acak
            $newName = uniqid() . '_' . $file->getRandomName();

            // Simpan file ke direktori '/file-simandja'
            $file->move(FCPATH . '/dist/img/avatar', $newName);

            // Periksa apakah file telah tersimpan
            if (file_exists(FCPATH . '/dist/img/avatar/' . $newName)) {
              $data = [
                'id_user' => $by['id_user'],
                'image' => $newName
              ];

              if ($this->userModel->save($data)) {
                $newId = $by['id_user'];
                $msg = [
                  'data' => 'sukses',
                  'pesan' => '/dist/img/avatar/' . $newName
                ];

                $ses_data['picture'] = $newName;
                session()->set($ses_data);

                $timeline = [
                  'icon'        => 'fas fa-user-edit bg-warning',
                  'id_table'    => $newId,
                  'table'       => 'user',
                  'id_user'     => $by['id_user'],
                  'aktivitas'   => "memperbaharui kontak",
                  'keterangan'  => ""
                ];
                $this->linimasaModel->save($timeline);
              }
            }
          } else {
            $msg = [
              'data' => 'unique',
              'pesan' => 'Masukkan gambar tidak lebih dari 5MB'
            ];
          }
        } else {
          $msg = [
            'data' => 'unique',
            'pesan' => 'File tidak valid, atau tidak sesuai ekstensi'
          ];
        }
        // =======================================================================================================================

      }

      echo json_encode($msg);
    }
  }
  public function menu(){
    $userAgent = $this->request->getUserAgent()->getAgentString();
    
    // Deteksi apakah akses berasal dari aplikasi WebView (APK)
    $isWebView = (strpos($userAgent, '; wv') !== false) || 
                 (stripos($userAgent, 'WebView') !== false) || 
                 (stripos($userAgent, 'Android') !== false && stripos($userAgent, 'Version/') !== false);
                 
    $isNewApk = strpos($userAgent, 'SimplyApp-v2.0') !== false;

    // Blokir HANYA jika menggunakan APK lama (WebView yang tidak memiliki SimplyApp-v2.0)
    // Browser biasa (Chrome, Safari, dll) akan lolos karena $isWebView bernilai false
    if ($isWebView && !$isNewApk) {
        return view('absen/update_apk');
    }

    $cache = Services::cache();
    $data['nip'] = "";

    // Cek token persisten di cookie
    $persistentToken = $this->request->getCookie('persistent_token');
    $nip = "";
    if ($persistentToken) {
      $cacheKey = 'user_nip_token_' . $persistentToken;
      $nip = $cache->get($cacheKey);

      if ($nip) {
        $data['nip'] = $nip;
      }
    }

    $cek = false;
    $verifikatorKendaraan = $this->kendaraankantorVerifikatorModel
      ->join('data_pegawai', 'id_data_pegawai')
      ->where('data_pegawai.nip', $nip)
      ->first();

    if ($verifikatorKendaraan) {
      $cek = true;
      $cekData = $this->kendaraanKantorRiwayatModel
        ->where('id_kendaraan_kantor_daftar', '0')
        ->findAll();
    
      if ($cekData) {
        $countMessage = 0;
        foreach ($cekData as $row) {
          $tanggalArray = json_decode($row['tanggal'], true);

          if (is_array($tanggalArray)) {
            $today = date('Y-m-d');
            $filteredDates = array_filter($tanggalArray, function ($date) use ($today) {
              return $date >= $today;
            });
            $countMessage += count($filteredDates);
          }
        }

        $data['countMessage'] = $countMessage;
      } else {
        $data['countMessage'] = 0;
      }
    } else {
      $data['countMessage'] = 0;
    }


    $data['verifikatorKendaraan'] = $cek;

    return view('absen/menu-v2', $data);
  }
  public function menuV2(){
    $userAgent = $this->request->getUserAgent()->getAgentString();
    
    // Deteksi apakah akses berasal dari aplikasi WebView (APK)
    $isWebView = (strpos($userAgent, '; wv') !== false) || 
                 (stripos($userAgent, 'WebView') !== false) || 
                 (stripos($userAgent, 'Android') !== false && stripos($userAgent, 'Version/') !== false);
                 
    $isNewApk = strpos($userAgent, 'SimplyApp-v2.0') !== false;

    // Blokir HANYA jika menggunakan APK lama (WebView yang tidak memiliki SimplyApp-v2.0)
    // Browser biasa (Chrome, Safari, dll) akan lolos karena $isWebView bernilai false
    if ($isWebView && !$isNewApk) {
        return view('absen/update_apk');
    }

    $cache = Services::cache();
    $data['nip'] = "";

    // Cek token persisten di cookie
    $persistentToken = $this->request->getCookie('persistent_token');
    $nip = "";
    if ($persistentToken) {
      $cacheKey = 'user_nip_token_' . $persistentToken;
      $nip = $cache->get($cacheKey);

      if ($nip) {
        $data['nip'] = $nip;
      }
    }

    $cek = false;
    $verifikatorKendaraan = $this->kendaraankantorVerifikatorModel
      ->join('data_pegawai', 'id_data_pegawai')
      ->where('data_pegawai.nip', $nip)
      ->first();

    if ($verifikatorKendaraan) {
      $cek = true;
      $cekData = $this->kendaraanKantorRiwayatModel
        ->where('id_kendaraan_kantor_daftar', '0')
        ->findAll();
    
      if ($cekData) {
        $countMessage = 0;
        foreach ($cekData as $row) {
          $tanggalArray = json_decode($row['tanggal'], true);

          if (is_array($tanggalArray)) {
            $today = date('Y-m-d');
            $filteredDates = array_filter($tanggalArray, function ($date) use ($today) {
              return $date >= $today;
            });
            $countMessage += count($filteredDates);
          }
        }

        $data['countMessage'] = $countMessage;
      } else {
        $data['countMessage'] = 0;
      }
    } else {
      $data['countMessage'] = 0;
    }


    $data['verifikatorKendaraan'] = $cek;

    return view('absen/menu-v2', $data);
  }
}
