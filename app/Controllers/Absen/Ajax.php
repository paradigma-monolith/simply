<?php

namespace App\Controllers\Absen;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Ajax extends BaseController
{
  public function getRekamAbsen($id) {
      if($this->request->isAJAX()) {
          $getData = $this->absenModel->select('rekam_absen')->where('id_absen', $id)->first();
          $data['id_absen'] = $id;
          $data['superUser'] = false;
          
          $cek = $this->absenAksesModel->join('user', 'id_user')->where(['username' => session()->get('username'), 'status' => 'Akses Diterima'])->first();
          
          if ($cek) {
            $data['superUser'] = true; 
          }
          
          if ($getData['rekam_absen'] == "") {
              $msg = [
                'data' => '<div class="d-flex justify-content-center align-items-center vh-100"><h4>Sistem Belum Menambahkan Rekam Absen!</h4></div>'    
              ];
          } else {
            $data['getData'] = json_decode($getData['rekam_absen'], true);
              $msg = [
                'data' => view('absen/rekam_absen', $data)    
              ];
          }
          
          echo json_encode($msg);
      }
  }
  
  public function getImage() {
      if ($this->request->isAjax()) {
          $id_absen = $this->request->getVar("id_absen");
          $cek = $this->absenModel->where("id_absen", $id_absen)->first();
          
          $msg = ['data' => $cek];
          
          echo json_encode($msg);
      }
  }
  public function dataAbsenDinasLuar()
  {
    $tanggal = $this->request->getPost('tanggal');

    $builderDataPegawai = $this->dataPegawaiModel->builder();
    $builderAbsen = $this->absenModel->builder();

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
    $builder->join('user', 'user.id_user=data_pegawai.id_user');
    $builder->join('absen_akses', 'absen_akses.id_user=user.id_user', 'left');

    $builder->groupStart();
    $builder->where('absen.status_masuk', 'DL');
    $builder->orWhere('absen.status_pulang', 'DL');
    $builder->groupEnd();

    if ($tanggal) {
      $splitRange = explode(' - ', $tanggal);
      $start_date = $splitRange[0];
      $end_date = isset($splitRange[1]) ? $splitRange[1] : $start_date;

      $builder->where("DATE(jam_masuk) >=", $start_date);
      $builder->where("DATE(jam_masuk) <=", $end_date);
    }
    
    $builder->orderBy('jam_masuk', 'desc');

    // Menjalankan query
    $query = $builder->get();
    $data['absen'] = $query->getResultArray();
    $data['superUser'] = true;

    $data['aksesEditDel'] = false;
    $cek_akses = $this->absenAksesModel->join('user', 'user.id_user = absen_akses.id_user')->where(['user.username' => session()->get('username'), 'absen_akses.status' => 'Akses Diterima'])->first();
    if ($cek_akses && isset($cek_akses['akses_edit_del']) && $cek_akses['akses_edit_del'] == 1) {
        $data['aksesEditDel'] = true;
    }

    $viewPage = view('absen/get_absen_dinas_luar', $data);
    $msg = [
      'data' => $viewPage
    ];
    echo json_encode($msg);
  }

  public function dataAbsen()
  {
    if ($this->request->isAJAX()) {
        
      $tanggalArray = explode(' - ', $this->request->getVar("tanggal"));

      $day1 = $tanggalArray[0];
      $day2 = isset($tanggalArray[1]) ? $tanggalArray[1] : $day1;
      
      $data['aksesEditDel'] = false;
      $cek_akses = $this->absenAksesModel->join('user', 'user.id_user = absen_akses.id_user')->where(['user.username' => session()->get('username'), 'absen_akses.status' => 'Akses Diterima'])->first();
      if ($cek_akses && isset($cek_akses['akses_edit_del']) && $cek_akses['akses_edit_del'] == 1) {
          $data['aksesEditDel'] = true;
      }
      
      if($day1 == $day2){

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
        ->getCompiledSelect(); // Menghasilkan query subquery yang dikompilasi

        // Mengatur query utama untuk menampilkan kolom tertentu
        $builder = $this->dataPegawaiModel->builder();
        $builder->select("
              CASE
                  WHEN rekam_absen = '' THEN 0
                  ELSE JSON_LENGTH(rekam_absen)
              END AS jumlah_array,
              absen.ttd_masuk, absen.ttd_pulang,
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
        $builder->join('absen', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai AND DATE(absen.jam_masuk) = \'' . $day1 . '\'', 'left');
        $builder->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan');
        $builder->where('data_pegawai.status_bekerja', 'Aktif');

        $builder->groupStart();
        $builder->where('absen.status_masuk !=', 'DL');
        $builder->orWhere('absen.status_masuk IS NULL');
        $builder->groupEnd();
        
        $builder->groupStart();
        $builder->where('absen.status_pulang !=', 'DL');
        $builder->orWhere('absen.status_pulang IS NULL');
        $builder->groupEnd();

        $builder->orderBy('golongan.urutan', 'desc');
    
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

          $viewPage = view('absen/get_absen_harian_v5', $data);
          $msg = [
            'cekData' => $data['absen'],
            'data' => $viewPage
          ];
      } else {

          // Membuat query menggunakan builder model
            $builder = $this->absenModel->builder();
            
            // Menentukan kolom yang akan dipilih
            $builder->select("
                (SELECT COUNT(*) FROM data_pegawai WHERE status_bekerja = 'Aktif') AS jumlah_array,
                absen.ttd_masuk,
                absen.ttd_pulang,
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
            ");
            
            // Bergabung dengan tabel data_pegawai
            $builder->join('data_pegawai', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai 
                AND DATE(absen.jam_masuk) BETWEEN \'' . $day1 . '\' AND \'' . $day2 . '\'', 'left');
            
            // Bergabung dengan tabel golongan
            $builder->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan', 'left');
            
            // Menambahkan kondisi where
            $builder->where('data_pegawai.status_bekerja', 'Aktif');
            
            $builder->groupStart();
            $builder->where('absen.status_masuk !=', 'DL');
            $builder->orWhere('absen.status_masuk IS NULL');
            $builder->groupEnd();
            
            $builder->groupStart();
            $builder->where('absen.status_pulang !=', 'DL');
            $builder->orWhere('absen.status_pulang IS NULL');
            $builder->groupEnd();
            
            // Mengurutkan hasil query
            $builder->orderBy('golongan.urutan', 'desc');
            
            // Menjalankan query dan mendapatkan hasilnya
            $data['absen'] = $builder->get()->getResultArray();

    
    
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
    
          $viewPage = view('absen/get_absen_harian_v5', $data);
          
          $data['zona'] = $getData;
          $msg = [
            'cekData' => $data['absen'],
            'data' => $viewPage
          ];
      }
      if (date('Y-m', strtotime($day1)) != date('Y-m', strtotime($day2))) {
        $viewPage = view('absen/get_absen_harian_rekap', $data);
        $msg = [
          'cekData' => $data['absen'],
          'data' => $viewPage
        ];
      }
      echo json_encode($msg);
    }
  }
  
  public function haversine_distance($lat1, $lon1, $lat2, $lon2)
  {
      $earth_radius = 6371000; // dalam meter
    
      // Konversi derajat ke radian
      $lat1_rad = deg2rad($lat1);
      $lon1_rad = deg2rad($lon1);
      $lat2_rad = deg2rad($lat2);
      $lon2_rad = deg2rad($lon2);
    
      // Selisih koordinat
      $delta_lat = $lat2_rad - $lat1_rad;
      $delta_lon = $lon2_rad - $lon1_rad;
    
      // Rumus Haversine
      $a = sin($delta_lat / 2) * sin($delta_lat / 2) +
        cos($lat1_rad) * cos($lat2_rad) *
        sin($delta_lon / 2) * sin($delta_lon / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
      // Hitung jarak
      $distance = $earth_radius * $c;
    
      return $distance;
  }
  public function getMarkLocation() {
      $data = $this->absenZonaModel->where('status', 'Terkonfirmasi')->findAll();
      return $this->response->setJSON($data); 
  }
  public function getDistance(){
      if($this->request->isAjax()) {
          $distance = 100;
          $alamat = $this->request->getVar('alamat');
          
          $cekAlamat = $this->absenZonaModel->where(['alamat' => $alamat, 'status' =>'Terkonfirmasi'])->first();
          $msg['alamat'] = null;
          if($alamat) {
              $msg['alamat'] = $cekAlamat;
          } else {
              $getData = $this->absenZonaModel->where('status','Terkonfirmasi')->findAll();
              foreach($getData as $data) {
                  $lat1 = $data['latitude'];
                  $lon1 = $data['longitude'];
                  $lat2 = $this->request->getVar('latitude');
                  $lon2 = $this->request->getVar('longitude');
                  
                  $getDistance = $this->haversine_distance($lat1, $lon1, $lat2, $lon2);
                  if($getDistance < $distance) {
                      $distance = $getDistance;
                  }
              }
          }
          
          $msg['distance'] = $distance;
          echo json_encode($msg);
      }
  }
  public function getSecondImage($id) {
      if($this->request->isAjax()) {
          $msg = [];
          
          $data = $this->absenModel->where('id_absen',$id)->first();
          if($data) {
              $msg = [
                  'data' => $data
              ];
          }
          echo json_encode($msg);
      }
  }
  public function getPegawai()
  {
    if ($this->request->isAJAX()) {
      $data['pegawai'] = $this->dataPegawaiModel
        ->join('golongan', 'id_golongan')
        ->where('data_pegawai.status_bekerja', 'Aktif')
        ->orderBy('golongan.urutan', 'desc')
        ->findAll();

      $msg = [
        'data' => $data
      ];

      echo json_encode($msg);
    }
  }

  public function getAbsenById($id)
  {
    if ($this->request->isAJAX()) {
      $absen = $this->absenModel
        ->select('absen.*, data_pegawai.nama')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai', 'left')
        ->where('id_absen', $id)
        ->first();
      if ($absen) {
        echo json_encode(['data' => $absen]);
      } else {
        echo json_encode(['data' => null]);
      }
    }
  }

  public function absenCekSinadi()
  {
    if ($this->request->isAJAX()) {
      $id_data_pegawai = $this->request->getVar("id_data_pegawai");
      $tgl = $this->request->getVar("tgl_masuk");

      $pegawai = $this->dataPegawaiModel->where('id_data_pegawai', $id_data_pegawai)->first();

      $cek = $this->sinadiPerjalananDinasPesertaModel
        ->join('sinadi_perjalanan_dinas', 'id_perjalanan')
        ->where('sinadi_perjalanan_dinas_peserta.tgl_pergi <=', $tgl)
        ->where('sinadi_perjalanan_dinas_peserta.tgl_pulang >=', $tgl)
        ->where('sinadi_perjalanan_dinas_peserta.nip_pegawai', $pegawai['nip'])
        ->first();
      $msg = [];
      if ($cek) {
        $getTanggal = "";
        if ($cek['tgl_pergi'] == $cek['tgl_pulang']) {
          $getTanggal = $cek['tgl_pulang'];
        } else {
          $getTanggal = mediumdate_indo($cek['tgl_pergi']) . ' s.d ' . mediumdate_indo($cek['tgl_pulang']);
        }
        $msg = [
          'data' => 'sukses',
          'nama' => $pegawai['nama'],
          'pesan' => 'melakukan perjalanan dinas tertanggal ' . $getTanggal
        ];
      } else {
        $msg = [
          'data' => 'error',
          'nama' => $pegawai['nama'],
          'pesan' => 'tidak ada data perjalanan dinas pada tanggal ' . mediumdate_indo($tgl) . '!'
        ];
      }

      echo json_encode($msg);
    }
  }
  public function absenCekCuti()
  {
    if ($this->request->isAJAX()) {
      $id_data_pegawai = $this->request->getVar("id_data_pegawai");
      $tgl = $this->request->getVar("tgl_masuk");

      $pegawai = $this->dataPegawaiModel->where('id_data_pegawai', $id_data_pegawai)->first();

      $cek = $this->cutiDataCutiModel
        ->where('id_data_pegawai', $id_data_pegawai)
        ->orderBy('id_cuti_data_cuti', 'desc')
        ->findAll();

      $msg = [];
      $tanggal_awal = "";
      $tanggal_akhir = "";
      $findTanggal = false;

      $jenis_cuti = '';
      $alasan_cuti = '';

      foreach ($cek as $data) {
        if ($findTanggal == true) {
          break;
        }
        $tgl_1 = explode("|", $data['tanggal_awal']);
        $tgl_2 = explode("|", $data['tanggal_akhir']);

        for ($i = 0; $i < count($tgl_1); $i++) {
          $tgl_1[$i] = str_replace('/', '-', $tgl_1[$i]);
          $tgl_2[$i] = str_replace('/', '-', $tgl_2[$i]);
          if ($tgl_1[$i] <= $tgl && $tgl_2[$i] >= $tgl) {
            $jenis_cuti = $data['jenis_cuti'];
            $alasan_cuti = $data['alasan_cuti'];
            $tanggal_awal = $tgl_1[$i];
            $tanggal_akhir = $tgl_2[$i];
            $findTanggal = true;
            break;
          }
        }
      }

      if ($findTanggal == true) {
        $getTanggal = "";
        if ($tanggal_awal == $tanggal_akhir) {
          $getTanggal = $tanggal_akhir;
        } else {
          $getTanggal = mediumdate_indo($tanggal_awal) . ' s.d ' . mediumdate_indo($tanggal_akhir);
        }
        $msg = [
          'data' => 'sukses',
          'nama' => $pegawai['nama'],
          'pesan' => 'melakukan <strong>' . $jenis_cuti . '</strong> dengan alasan "' . $alasan_cuti . '" tertanggal ' . $getTanggal
        ];
      } else {
        $msg = [
          'data' => 'error',
          'nama' => $pegawai['nama'],
          'pesan' => 'tidak ada data cuti pada tanggal ' . mediumdate_indo($tgl) . '!'
        ];
      }

      echo json_encode($msg);
    }
  }
  
  public function getDataAbsen()
  {
    $page = $this->request->getVar('page') ?? 1;
    $name = $this->request->getVar('name') ?? "";
    $serialize = $this->request->getVar('serialize') ?? "";
    $rekam = $this->request->getVar('rekam') ?? "";
    
    $perPage = 25;

    $this->absenModel
        ->select('nama, jabatan, golongan, status_masuk, status_pulang, longitude_masuk, latitude_masuk, alamat_masuk, jam_masuk, longitude_pulang, latitude_pulang, alamat_pulang, jam_pulang, aturan_jam_masuk, aturan_jam_pulang, toleransi_keterlambatan');
    if($rekam == "rekam") {
      $this->absenModel
        ->select('rekam_absen');
    }
    $this->absenModel
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=absen.id_data_pegawai')
        ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
        ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan');
        
    if($name != "") {
        $this->absenModel
            ->like('data_pegawai.nama', $name);
    }
    
    $dataUsers = $this->absenModel->orderBy('absen.created_at', 'DESC')
        ->paginate($perPage, 'default');
    
    if ($serialize == 'serialize') {
        foreach ($dataUsers as &$row) {
            if (!empty($row['rekam_absen'])) {
                $row['rekam_absen'] = json_decode($row['rekam_absen'], true);
            } else {
                $row['rekam_absen'] = []; 
            }
        }
    }

    $pager = $this->absenModel->pager;

    $totalData = $this->absenModel->countAllResults(false);

    $totalPages = ceil($totalData / $perPage);

    $current_page = $pager->getCurrentPage();
    $has_next = $current_page < $totalPages;
    $has_previous = $current_page > 1;
    $next_page = $has_next ? $current_page + 1 : null;
    $previous_page = $has_previous ? $current_page - 1 : null;

    return $this->response->setJSON([
        'status' => 'sukses',
        'current_page' => $current_page,
        'per_page' => $perPage,
        'total_data' => $totalData,
        'total_halaman' => $totalPages,
        'has_next' => $has_next,
        'has_previous' => $has_previous,
        'next_page' => $next_page,
        'previous_page' => $previous_page,
        'data' => $dataUsers
    ]);
  }
  
  public function getDataAbsenTanggal()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Content-Type: application/json; charset=UTF-8");
        $name = $this->request->getVar('name') ?? "";
        $serialize = $this->request->getVar('serialize') ?? "";
        $rekam = $this->request->getVar('rekam') ?? "";
    
        $from = $this->request->getVar('from');
        $to   = $this->request->getVar('to');
    
        $this->absenModel
            // ->select("CONCAT(\"'\", nip) AS nip", false)
            ->select('nip, nama, jabatan, golongan,
                      DATE(jam_masuk) as tanggal_masuk, TIME(jam_masuk) AS jam_masuk,
                      DATE(jam_pulang) as tanggal_pulang, TIME(jam_pulang) AS jam_pulang,
                      TIMEDIFF(jam_pulang, jam_masuk) AS total_jam, status_masuk, status_pulang
                      ');
    
        if ($rekam === "rekam") {
            $this->absenModel->select('rekam_absen');
        }
    
        $this->absenModel
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai')
            ->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan')
            ->join('jabatan', 'data_pegawai.id_jabatan = jabatan.id_jabatan');
    
        if ($name !== "") {
            $this->absenModel->like('data_pegawai.nama', $name);
        }
    
        if ($from && $to) {
            $this->absenModel
                ->where('DATE(jam_masuk) >=', $from)
                ->where('DATE(jam_masuk) <=', $to);
        }
    
        $dataUsers = $this->absenModel
            ->orderBy('absen.created_at', 'DESC')
            ->findAll();
    
        if ($serialize === 'serialize') {
            foreach ($dataUsers as &$row) {
                $row['rekam_absen'] = !empty($row['rekam_absen'])
                    ? json_decode($row['rekam_absen'], true)
                    : [];
            }
        }
    
        return $this->response->setJSON($dataUsers);
    }

  
  public function absenCekPerizinan()
  {
    if ($this->request->isAJAX()) {
      $id_data_pegawai = $this->request->getVar("id_data_pegawai");
      $tgl = $this->request->getVar("tgl_masuk");

      $tgl_formatted = date('d F Y', strtotime($tgl));

      $pegawai = $this->dataPegawaiModel->where('id_data_pegawai', $id_data_pegawai)->first();

      $cek = $this->cutiDataPermohonanModel
        ->where('tanggal', $tgl_formatted)
        ->where('id_data_pegawai', $id_data_pegawai)
        ->first();

      $msg = [];
      if ($cek) {
        $getTanggal = mediumdate_indo($tgl);

        $msg = [
          'data' => 'sukses',
          'nama' => $pegawai['nama'],
          'pesan' => 'melakukan <strong>Perizinan ' . $cek['jenis_permohonan'] . '</strong> dengan alasan ' . $cek['alasan_permohonan'] . ' tertanggal ' . $getTanggal
        ];
      } else {
        $msg = [
          'data' => 'error',
          'nama' => $pegawai['nama'],
          'pesan' => 'tidak ada data perizinan dinas pada tanggal ' . mediumdate_indo($tgl) . '!'
        ];
      }

      echo json_encode($msg);
    }
  }
  
  public function getTargetAreaAbsen($nip = "")
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $get = $this->absenTargetModel->findAll();
      
      $lokasi = false;
      $akses = [];
      $cekLokasi = $this->dataPegawaiModel->select('absen_target_akses.lokasi')->join('absen_target_akses', 'id_data_pegawai')->where('data_pegawai.nip', $nip)->first();
      if($cekLokasi && isset($cekLokasi["lokasi"])) {
          $array = json_decode($cekLokasi["lokasi"], true);
          $this->absenTargetModel;
          $this->absenTargetModel->where('id_unit_kerja', $array[0]);
          for ($i=1; $i<count($array); $i++) {
            $this->absenTargetModel->orWhere('id_unit_kerja', $array[$i]);
          }
          $akses = $this->absenTargetModel->find();
          $lokasi = true;
      }
      
      $isWfa = false;
      $pegawai = $this->dataPegawaiModel->where('nip', trim($nip))->first();
      if ($pegawai) {
         $aksesWFA = $this->absenAksesModel->where('id_user', $pegawai['id_user'])->first();
         if ($aksesWFA && (int)$aksesWFA['akses_wfa'] === 1) {
             $isWfa = true;
         }
      }

      $msg = ['data' => $get];
      if ($lokasi) {
        $msg['akses'] = $akses;
      }
      $msg['wfa'] = $isWfa;

      echo json_encode($msg);
    }
  }
  public function getTargetAreaAbsenWFA($nip = "")
  {
    if ($this->request->isAJAX()) {
      $msg = [];

      $lokasi = false;
      $akses = [];
      $cekLokasi = $this->dataPegawaiModel->where('data_pegawai.nip', $nip)->first();
      if($cekLokasi) {
          $lokasi = true;
      }
      

      $msg = ['data' => true];
      if ($lokasi) {
        $msg['akses'] = true;
      }

      echo json_encode($msg);
    }
  }
  public function getLatestDLPhoto() {
      if ($this->request->isAJAX()) {
          $nip = $this->request->getVar("nip");
          $msg = ['data' => 'error', 'pesan' => 'Data tidak ditemukan'];
          
          if ($nip) {
              $pegawai = $this->dataPegawaiModel->where('nip', $nip)->first();
              if ($pegawai) {
                  // Find latest DL
                  $latestDL = $this->absenModel
                      ->where('id_data_pegawai', $pegawai['id_data_pegawai'])
                      ->groupStart()
                        ->where('status_masuk', 'DL')
                        ->orWhere('status_pulang', 'DL')
                      ->groupEnd()
                      ->orderBy('jam_masuk', 'DESC')
                      ->first();
                      
                  if ($latestDL) {
                      // Prefer pulang if status_pulang = DL
                      $photoBase64 = null;
                      if ($latestDL['status_pulang'] == 'DL' && !empty($latestDL['image_pulang_1'])) {
                          $photoBase64 = $latestDL['image_pulang_1'];
                      } else if ($latestDL['status_masuk'] == 'DL' && !empty($latestDL['image_masuk_1'])) {
                          $photoBase64 = $latestDL['image_masuk_1'];
                      } else if ($latestDL['status_masuk'] == 'DL' && !empty($latestDL['image_masuk_2'])) {
                          $photoBase64 = $latestDL['image_masuk_2'];
                      }
                      
                      if ($photoBase64) {
                          $msg = ['data' => 'sukses', 'image' => $photoBase64, 'waktu' => $latestDL['jam_pulang'] ? $latestDL['jam_pulang'] : $latestDL['jam_masuk']];
                      } else {
                          $msg = ['data' => 'error', 'pesan' => 'Foto tidak tersedia dalam format base64'];
                      }
                  } else {
                      $msg = ['data' => 'error', 'pesan' => 'Belum ada data absen DL'];
                  }
              }
          }
          echo json_encode($msg);
      }
  }

  public function getLinimasaData() {
      if ($this->request->isAJAX()) {
        $msg = [];
        
        $cache = Services::cache();
    
        $persistentToken = $this->request->getCookie('persistent_token');
        if ($persistentToken) {
          $cacheKey = 'user_nip_token_' . $persistentToken;
          $nip = $cache->get($cacheKey);
    
          if ($nip) {
            $get = $this->absenModel
                ->select('absen.jam_masuk, absen.jam_pulang, absen.status_masuk, absen.status_pulang, data_pegawai.nama')
                ->join('data_pegawai', 'id_data_pegawai')
                ->where('data_pegawai.nip', $nip)
                ->orderBy('absen.jam_masuk', 'desc')
                ->limit(5)
                ->find();
            
            // Tambahkan perhitungan jam kerja untuk setiap record
            foreach ($get as &$record) {
                if (!empty($record['jam_masuk']) && !empty($record['jam_pulang'])) {
                    $record['jam_kerja'] = hitungJamKerja($record['jam_masuk'], $record['jam_pulang']);
                } else {
                    $record['jam_kerja'] = '-';
                }
            }
            
            $msg = [
                'data' => $get
            ];
          }
        }
        
        echo json_encode($msg);
      }
  }
}
