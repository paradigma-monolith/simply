<?php

namespace App\Controllers\Absen;

use App\Controllers\BaseController;
use Config\Services;

class Data extends BaseController
{
  public function directWFO() {
      session()->setFlashdata('isWFO', 'isWFO');
      return redirect()->to('/absensi');
  }
  public function index()
  {
    $cache = Services::cache();
    $data['nip'] = "";

    // Cek token persisten di cookie
    $persistentToken = $this->request->getCookie('persistent_token');
    if ($persistentToken) {
      $cacheKey = 'user_nip_token_' . $persistentToken;
      $nip = $cache->get($cacheKey);

      if ($nip) {
        $data['nip'] = $nip;
      }
    }

    $absenSettingModel = new \App\Models\AbsenSettingModel();
    $data['absenSetting'] = $absenSettingModel->first();
    
    // if (session()->has('role') && session()->get('role') === "Admin") {
    //     return view('absen/index_v2', $data);
    // }
    
    return view('absen/index', $data);
  }
  public function wfa()
  {
    $currentHour = (int) date('H'); // Ambil jam saat ini dalam format 24 jam

    // Rentang waktu absensi
    $startHour = 6; // Jam mulai absensi
    $endHour = 18; // Jam akhir absensi
    
    if ($currentHour < $startHour || $currentHour >= $endHour) {
        // Jika di luar rentang waktu absensi
        echo "
        <style>
        * {
            font-family: sans-serif;
        }
        </style>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Absensi Ditutup',
                    html: 'Sesuai peraturan yang sudah ditetapkan. Khusus untuk <i>Work From Anywhere</i> (WFA) absensi hanya tersedia dari pukul 06:00 s.d 18:00 WIB.',
                    confirmButtonText: 'Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/menu'; // Arahkan kembali ke halaman utama
                    }
                });
            });
        </script>
        ";
        exit; // Hentikan eksekusi script lebih lanjut
    }
      
    $cache = Services::cache();
    $data['nip'] = "";

    // Cek token persisten di cookie
    $persistentToken = $this->request->getCookie('persistent_token');
    if ($persistentToken) {
      $cacheKey = 'user_nip_token_' . $persistentToken;
      $nip = $cache->get($cacheKey);

      if ($nip) {
        $data['nip'] = $nip;
      }
    }

    $absenSettingModel = new \App\Models\AbsenSettingModel();
    $data['absenSetting'] = $absenSettingModel->first();
    
    // if (session()->has('role') && session()->get('role') === "Admin") {
    //     return view('absen/index_v2', $data);
    // }
    
    return view('absen/index-wfa', $data);
  }
  public function zonaAbsen()
  {
    $data['title'] = "Absen | Zona yang Terdaftar";
    $data['activeMenu'] = "zonaAbsen";
      
    $data['area'] = $this->absenTargetModel
        ->select('unit_kerja.unit_kerja, absen_target.*')
        ->join('unit_kerja','id_unit_kerja')
        ->findAll();
        
    $data['mark'] = $this->absenModel
        ->select('latitude_masuk, longitude_masuk, latitude_pulang, longitude_pulang, nama')
        ->join('data_pegawai', 'id_data_pegawai')
        ->where("DATE(jam_masuk)", date('Y-m-d'))
        ->findAll();
        

    return view('absen/zona_absen', $data);
  }
  public function getZonaAbsen()
  {
    if ($this->request->isAJAX()) {
      $data['alamat'] = $this->request->getVar("alamat");
      $data['latitude'] = $this->request->getVar("latitude");
      $data['longitude'] = $this->request->getVar("longitude");

      $msg = [
        'data' => view('absen/get_zona_absen', $data)
      ];

      echo json_encode($msg);
    }
  }
  public function dataAbsen()
  {
    $data['title'] = "Absen | Daftar Hadir";
    $data['activeMenu'] = "absenHarian";

    $day = date("Y-m-d");

    $dataPegawai = $this->dataPegawaiModel->join('user', 'id_user')->where('user.username', session()->get('username'))->first();

// if (session()->get('role') != "Admin") {
//     $this->absenModel
//       ->join('data_pegawai', 'absen.id_data_pegawai = data_pegawai.id_data_pegawai', 'RIGHT')
//       ->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan')
//       ->where('data_pegawai.status_bekerja', 'Aktif');
//     // if (session()->get('role') == "Admin") {
//     //   $this->absenModel
//     //     ->groupStart()
//     //     ->where('DATE(jam_masuk)', $day)
//     //     ->orWhere('jam_masuk IS NULL')
//     //     ->groupEnd();
//     // }
//     $this->absenModel
//       ->orderBy('golongan.urutan', 'desc');
//     if ($dataPegawai)
//       $this->absenModel->where('absen.id_data_pegawai', $dataPegawai['id_data_pegawai']);
//     $data['absen'] = $this->absenModel->findAll();
// } else {
//     // dd($data['absen']);
//     $data['absen'] = array();
// }

    $builderDataPegawai = $this->dataPegawaiModel->builder();
    $builderAbsen = $this->absenModel->builder();
    $builderGolongan = $this->golonganModel->builder();

    // Membuat subquery menggunakan model
    $subquery = $builderDataPegawai
      ->select('id_data_pegawai')
      ->union(
        $builderAbsen
          ->select('id_data_pegawai')
      )
      ->getCompiledSelect();

    // Mengatur query utama untuk menampilkan kolom tertentu
    $builder = $this->dataPegawaiModel->builder();
    $builder->select("
        CASE 
            WHEN rekam_absen = '' THEN 0
            ELSE JSON_LENGTH(rekam_absen) 
        END AS jumlah_array,
        data_pegawai.id_data_pegawai AS dp_id_data_pegawai, 
        data_pegawai.nama,
        absen.id_absen,
        absen.status_masuk,
        absen.status_pulang,
        absen.longitude_masuk,
        absen.latitude_masuk,
        absen.longitude_pulang,
        absen.latitude_pulang,
        absen.alamat_masuk,
        absen.alamat_pulang,
        absen.jam_masuk,
        absen.jam_pulang,
        absen.validation_masuk,
        absen.validation_pulang,
        absen.keterangan,
        absen.aturan_jam_masuk,
        absen.aturan_jam_pulang,
        absen.toleransi_keterlambatan,
        golongan.id_golongan,
        golongan.golongan,
        golongan.urutan
    ");
    $builder->join("($subquery) tu", 'data_pegawai.id_data_pegawai = tu.id_data_pegawai', 'inner');
    $builder->join('absen', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai', 'left');
    $builder->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan');
    $builder->join('user','user.id_user=data_pegawai.id_user');
    $builder->where('user.username', session()->get('username'));

    $builder->groupStart();
    $builder->where('absen.status_masuk !=', 'DL');
    $builder->orWhere('absen.status_masuk IS NULL');
    $builder->groupEnd();
    
    $builder->groupStart();
    $builder->where('absen.status_pulang !=', 'DL');
    $builder->orWhere('absen.status_pulang IS NULL');
    $builder->groupEnd();

    $builder->limit(40);
    $builder->orderBy('jam_masuk', 'desc');

    // Menjalankan query
    $query = $builder->get();
    $data['absen'] = $query->getResultArray();

    // Ambil semua data alamat dari model
    $alamat_zona = $this->absenZonaModel
      ->select("alamat")
      ->where('status', 'Terkonfirmasi')
      ->findAll();

    // Ubah format $alamat_zona menjadi array asosiatif
    $getData = [];
    foreach ($alamat_zona as $row) {
      $getData[] = $row['alamat'];
    }

    $data['zona'] = $getData;
    $data['alamat'] = $this->absenTargetModel->select('ket_tempat')->findAll();
    
    $data['superUser'] = false;
    $data['aksesEditDel'] = false;
          
    $cek = $this->absenAksesModel->join('user', 'id_user')->where(['username' => session()->get('username'), 'status' => 'Akses Diterima'])->first();
  
    if ($cek) {
      $data['superUser'] = true; 
      if (isset($cek['akses_edit_del']) && $cek['akses_edit_del'] == 1) {
          $data['aksesEditDel'] = true;
      }
    }

    return view('absen/absen_harian', $data);
  }
  public function absenDinasLuar()
  {
    $data['title'] = "Absen | Dinas Luar";
    $data['activeMenu'] = "absenDinasLuar";

    $data['alamat'] = $this->absenTargetModel->select('ket_tempat')->findAll();

    $data['superUser'] = false;
    $data['aksesEditDel'] = false;

    $cek = $this->absenAksesModel->join('user', 'id_user')->where(['username' => session()->get('username'), 'status' => 'Akses Diterima'])->first();

    if ($cek) {
      $data['superUser'] = true;
      if (isset($cek['akses_edit_del']) && $cek['akses_edit_del'] == 1) {
          $data['aksesEditDel'] = true;
      }
    }

    $builderDataPegawai = $this->dataPegawaiModel->builder();
    $builderAbsen = $this->absenModel->builder();
    $builderGolongan = $this->golonganModel->builder();

    // Membuat subquery menggunakan model
    $subquery = $builderDataPegawai
      ->select('id_data_pegawai')
      ->union(
        $builderAbsen
          ->select('id_data_pegawai')
      )
      ->getCompiledSelect();

    // Mengatur query utama untuk menampilkan kolom tertentu
    $builder = $this->dataPegawaiModel->builder();
    $builder->select("
        CASE 
            WHEN rekam_absen = '' THEN 0
            ELSE JSON_LENGTH(rekam_absen) 
        END AS jumlah_array,
        data_pegawai.id_data_pegawai AS dp_id_data_pegawai, 
        data_pegawai.nama,
        absen.id_absen,
        absen.status_masuk,
        absen.status_pulang,
        absen.longitude_masuk,
        absen.latitude_masuk,
        absen.longitude_pulang,
        absen.latitude_pulang,
        absen.alamat_masuk,
        absen.alamat_pulang,
        absen.jam_masuk,
        absen.jam_pulang,
        absen.validation_masuk,
        absen.validation_pulang,
        absen.keterangan,
        absen.aturan_jam_masuk,
        absen.aturan_jam_pulang,
        absen.toleransi_keterlambatan,
        absen.ttd_masuk,
        absen.ttd_pulang,
        golongan.id_golongan,
        golongan.golongan,
        golongan.urutan,
        absen_akses.akses_edit_del
    ");
    $builder->join("($subquery) tu", 'data_pegawai.id_data_pegawai = tu.id_data_pegawai', 'inner');
    $builder->join('absen', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai', 'left');
    $builder->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan');
    $builder->join('user','user.id_user=data_pegawai.id_user');
    $builder->join('absen_akses', 'absen_akses.id_user=user.id_user', 'left');
    $builder->where('user.username', session()->get('username'));
    // $builder->where('absen.status_masuk', 'DL'); // remove this line because some might be DL on return only
    $builder->groupStart();
    $builder->where('absen.status_masuk', 'DL');
    $builder->orWhere('absen.status_pulang', 'DL');
    $builder->groupEnd();
    $builder->limit(40);
    $builder->orderBy('jam_masuk', 'desc');

    // Menjalankan query
    $query = $builder->get();
    $data['absen'] = $query->getResultArray();

    return view('absen/absen_dinas_luar', $data);
  }
  public function pengaturan()
  {
    $data['title'] = "Absen | Full Akses Data & Verifikasi"; // kelola_data_cuti
    $data['activeMenu'] = "pengaturanAbsen";

    $data['akses'] = $this->userModel
      ->join('absen_akses', 'absen_akses.id_user=user.id_user', 'LEFT')
      ->where("user.username", session()->get('username'))
      ->first();

    if ($data['akses']['id_absen_akses']) {
      if ($data['akses']['status'] == 'Akses Diblokir') {
        $data['activeMenu'] = "absenBlockir";
      }
    } else {
      $data['activeMenu'] = "absenBlockir";
    }


    $data['user'] = $this->userModel
      ->select('user.id_user,user.nama, user.id_user, absen_akses.id_absen_akses, absen_akses.status, absen_akses.akses_wfa, absen_akses.akses_edit_del')
      ->join('absen_akses', 'absen_akses.id_user=user.id_user', 'LEFT')
      ->where("user.status_pegawai_aktif", "Aktif")
      ->orderBy("user.nama", "ASC")
      ->findAll();
      
      
    $data['targetAkses'] = $this->absenTargetAksesModel->join('data_pegawai', 'id_data_pegawai', 'right')->where('status_bekerja', 'Aktif')
      ->orderBy("data_pegawai.nama", "ASC")->findAll();
    $data['unitKerja'] = $this->unitKerjaModel->findAll();
    
    $data['day1'] = $this->absenJamModel->select('user.nama, absen_jam.*')->join('user', 'user.id_user=absen_jam.updated_by')->where('hari', '1')->orderBy('created_at', 'DESC')->limit(1)->first();
    $data['day2'] = $this->absenJamModel->select('user.nama, absen_jam.*')->join('user', 'user.id_user=absen_jam.updated_by')->where('hari', '2')->orderBy('created_at', 'DESC')->limit(1)->first();
    $data['day3'] = $this->absenJamModel->select('user.nama, absen_jam.*')->join('user', 'user.id_user=absen_jam.updated_by')->where('hari', '3')->orderBy('created_at', 'DESC')->limit(1)->first();
    $data['day4'] = $this->absenJamModel->select('user.nama, absen_jam.*')->join('user', 'user.id_user=absen_jam.updated_by')->where('hari', '4')->orderBy('created_at', 'DESC')->limit(1)->first();
    $data['day5'] = $this->absenJamModel->select('user.nama, absen_jam.*')->join('user', 'user.id_user=absen_jam.updated_by')->where('hari', '5')->orderBy('created_at', 'DESC')->limit(1)->first();
    $data['day6'] = $this->absenJamModel->select('user.nama, absen_jam.*')->join('user', 'user.id_user=absen_jam.updated_by')->where('hari', '6')->orderBy('created_at', 'DESC')->limit(1)->first();
    $data['day7'] = $this->absenJamModel->select('user.nama, absen_jam.*')->join('user', 'user.id_user=absen_jam.updated_by')->where('hari', '7')->orderBy('created_at', 'DESC')->limit(1)->first();

    $absenSettingModel = new \App\Models\AbsenSettingModel();
      $data['absenSetting'] = $absenSettingModel->first();
      
      $data['absenTargets'] = $this->absenTargetModel->select('unit_kerja.unit_kerja, absen_target.*')->join('unit_kerja', 'id_unit_kerja', 'left')->findAll();

      return view("absen/setting", $data);
  }
}
