<?php

namespace App\Controllers\Cuti;

use App\Controllers\BaseController;

class Ajax extends BaseController
{
  function tgl_indo($tanggal)
  {
    $bulan = array(
      1 =>   'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    );
    $split = explode('/', $tanggal);
    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun
    return $split[0] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[2];
  }

  public function ambil_data_cuti()
  {
    if ($this->request->isAJAX()) {


      $kepegawaian = false;
      $atasan = false;
      $pejabat = false;
      $byIdPegawai = $this->dataPegawaiModel
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))->first();
      if (!isset($byIdPegawai)) {
        $data['dataCuti'] = array();
        $skema = '';
        // return redirect()->to('infografis/kapal');
      } else {
        $byIdPegawai = $byIdPegawai['id_data_pegawai'];
        $skema = $this->skemaTitimangsaModel->where('jenis_surat', 'cuti')->findAll();

        $kondisiValue = '';
        $kondisi = '';
        $whereClause = '';
        for ($i = 0; $i < count($skema); $i++) {
          if ($skema[$i]['kepegawaian'] == $byIdPegawai) {
            $whereClause = 'cuti_status_kepegawaian';
            $kondisiValue = $byIdPegawai . '|';
            $kondisi = 'after';
            $kepegawaian = true;
          }
          if ($skema[$i]['atasan'] == $byIdPegawai) {
            $whereClause = 'cuti_status_atasan';
            $kondisiValue = '|' . $byIdPegawai . '|';
            $kondisi = 'both';
            $atasan = true;
          }
          if ($skema[$i]['pejabat'] == $byIdPegawai) {
            $whereClause = 'cuti_status_pejabat';
            $kondisiValue = '|' . $byIdPegawai;
            $kondisi = 'before';
            $pejabat = true;
          }
        }


        if ($kepegawaian == true || $atasan == true || $pejabat == true) {
          $data['dataCuti'] = $this->cutiDataCutiModel
            ->select("status_pegawai.*, cuti_data_cuti.id_cuti_data_cuti, cuti_data_cuti.id_skema_titimangsa, cuti_data_cuti.id_cuti_data_cuti, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
            cuti_status_kepegawaian.status AS cuti_status_kepegawaian, cuti_status_atasan.status AS cuti_status_atasan, cuti_status_pejabat.status AS cuti_status_pejabat,
      user.nama AS updated,
      cuti_data_cuti.jenis_cuti, cuti_data_cuti.tanggal_awal, cuti_data_cuti.tanggal_akhir, cuti_data_cuti.updated_at AS time, cuti_data_cuti.koordinator_status")
            ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_cuti.id_cuti_status_kepegawaian')
            ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_cuti.id_cuti_status_atasan')
            ->join('cuti_status AS cuti_status_pejabat', 'cuti_status_pejabat.id_cuti_status=cuti_data_cuti.id_cuti_status_pejabat')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
            ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
            ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
            ->where($whereClause . '.status', 'Pending')
            ->where('id_cuti_status_kepegawaian !=', 4)
            ->where('id_cuti_status_atasan !=', 4)
            ->where('id_cuti_status_pejabat !=', 4)
            ->like('cuti_data_cuti.id_skema_titimangsa', $kondisiValue, $kondisi)
            ->orderBy('cuti_data_cuti.updated_at', 'ASC')
            ->findAll();

          for ($i = 0; $i < count($data['dataCuti']); $i++) {
            $tanggal1 = explode("|", $data['dataCuti'][$i]['tanggal_awal']);
            $tanggal2 = explode("|", $data['dataCuti'][$i]['tanggal_akhir']);

            $ubahKeIndo1 = '';
            $ubahKeIndo2 = '';
            for ($j = 0; $j < count($tanggal1); $j++) {
              $date1 = date_create($tanggal1[$j]);
              $date2 = date_create($tanggal2[$j]);
              // $data['dataCuti'][$i] += ['periode' => $this->tgl_indo(date_format($date1, "d/m/Y")) . ' - ' . $this->tgl_indo(date_format($date2, "d/m/Y"))];
              $ubahKeIndo1 .= $this->tgl_indo(date_format($date1, "d/m/Y")) . '|';
              $ubahKeIndo2 .= $this->tgl_indo(date_format($date2, "d/m/Y")) . '|';
            }
            $data['dataCuti'][$i] += [
              'periode1' => substr($ubahKeIndo1, 0, -1),
              'periode2' => substr($ubahKeIndo2, 0, -1),
              'kepegawaian' => $kepegawaian,
              'atasan' => $atasan,
              'pejabat' => $pejabat,
            ];
          }
        } else {
          $id = $this->dataPegawaiModel->join("user", "data_pegawai.id_user=user.id_user")
            ->where('user.username', session()->get("username"))
            ->first();

          $data['dataCuti'] = $this->cutiDataCutiModel
            ->select("status_pegawai.*, cuti_data_cuti.id_cuti_data_cuti, cuti_data_cuti.id_skema_titimangsa, cuti_data_cuti.id_cuti_data_cuti, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
            cuti_status_kepegawaian.status AS cuti_status_kepegawaian, cuti_status_atasan.status AS cuti_status_atasan, cuti_status_pejabat.status AS cuti_status_pejabat,
            user.nama AS updated,
            cuti_data_cuti.jenis_cuti, cuti_data_cuti.tanggal_awal, cuti_data_cuti.tanggal_akhir, cuti_data_cuti.updated_at AS time, cuti_data_cuti.koordinator_status")
            ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_cuti.id_cuti_status_kepegawaian')
            ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_cuti.id_cuti_status_atasan')
            ->join('cuti_status AS cuti_status_pejabat', 'cuti_status_pejabat.id_cuti_status=cuti_data_cuti.id_cuti_status_pejabat')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
            ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
            ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
            ->where('cuti_data_cuti.koordinator_status', 'Pending')
            ->where('cuti_data_cuti.koordinator_tempat', $id['id_data_pegawai'])
            ->orderBy('cuti_data_cuti.updated_at', 'DESC')
            ->findAll();

          for ($i = 0; $i < count($data['dataCuti']); $i++) {
            $tanggal1 = explode("|", $data['dataCuti'][$i]['tanggal_awal']);
            $tanggal2 = explode("|", $data['dataCuti'][$i]['tanggal_akhir']);
            $ubahKeIndo1 = '';
            $ubahKeIndo2 = '';
            for ($j = 0; $j < count($tanggal1); $j++) {
              $date1 = date_create($tanggal1[$j]);
              $date2 = date_create($tanggal2[$j]);
              // $data['dataCuti'][$i] += ['periode' => $this->tgl_indo(date_format($date1, "d/m/Y")) . ' - ' . $this->tgl_indo(date_format($date2, "d/m/Y"))];
              $ubahKeIndo1 .= $this->tgl_indo(date_format($date1, "d/m/Y")) . '|';
              $ubahKeIndo2 .= $this->tgl_indo(date_format($date2, "d/m/Y")) . '|';
            }
            $data['dataCuti'][$i] += [
              'periode1' => substr($ubahKeIndo1, 0, -1),
              'periode2' => substr($ubahKeIndo2, 0, -1),
              'kepegawaian' => $kepegawaian,
              'atasan' => $atasan,
              'pejabat' => $pejabat,
            ];
          }
        }
      }

      $role = '<span="text-danger">Anda tidak mempunyai hak akses sementara ini untuk melihat data</span>';
      $kepegawaian == true ? $role = "Kepegawaian" : '';
      $atasan == true ? $role = "Atasan" : '';
      $pejabat == true ? $role = "Pejabat" : '';
      if (isset($id)) {
        if ($data['dataCuti'])
          $role = "Koordinator: " . name_capitalize($id['nama']);
      }
      $data['role'] = $role;

      $msg = [
        'tes' => $data,
        'data' => view('cuti/cuti_tabel', $data),
        'count' => count($data['dataCuti']),
        'skema' => $skema
      ];

      echo json_encode($msg);
    } else {
      exit('Maaf tidak bisa diproses!');
    }
  }
  public function ambil_data_permohonan()
  {
    if ($this->request->isAJAX()) {

      $kepegawaian = false;
      $atasan = false;
      $byIdPegawai = $this->dataPegawaiModel
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))->first();
      if (!isset($byIdPegawai)) {
        $data['dataCuti'] = array();
        $skema = '';
        // session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
        // return redirect()->to('infografis/kapal');
      } else {
        $byIdPegawai = $byIdPegawai['id_data_pegawai'];
        $skema = $this->skemaTitimangsaModel->where('jenis_surat', 'surat_sakti')->findAll();

        $kondisiValue = '';
        $kondisi = '';
        $whereClause = '';
        for ($i = 0; $i < count($skema); $i++) {
          if ($skema[$i]['kepegawaian'] == $byIdPegawai) {
            $whereClause = 'status_kepegawaian';
            $kondisiValue = $byIdPegawai . '|';
            $kondisi = 'after';
            $kepegawaian = true;
          }
          if ($skema[$i]['atasan'] == $byIdPegawai) {
            $whereClause = 'status_atasan';
            $kondisiValue = '|' . $byIdPegawai;
            $kondisi = 'before';
            $atasan = true;
          }
        }

        if ($kepegawaian == true || $atasan == true) {
          $data['dataCuti'] = $this->cutiDataPermohonanModel
            ->select("cuti_data_permohonan.id_cuti_data_permohonan, cuti_data_permohonan.id_skema_titimangsa, cuti_data_permohonan.id_cuti_data_permohonan, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
            status_kepegawaian.status AS status_kepegawaian, status_atasan.status AS status_atasan,
        user.nama AS updated,
        cuti_data_permohonan.jenis_permohonan, cuti_data_permohonan.tanggal, cuti_data_permohonan.updated_at AS time")
            ->join('cuti_status AS status_kepegawaian', 'status_kepegawaian.id_cuti_status=cuti_data_permohonan.id_cuti_status_kepegawaian')
            ->join('cuti_status AS status_atasan', 'status_atasan.id_cuti_status=cuti_data_permohonan.id_cuti_status_atasan')
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
            ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
            ->where($whereClause . '.status', 'Pending')
            ->where('id_cuti_status_kepegawaian !=', 4)
            ->where('id_cuti_status_atasan !=', 4)
            ->like('cuti_data_permohonan.id_skema_titimangsa', $kondisiValue, $kondisi)
            ->orderBy('cuti_data_permohonan.updated_at', 'DESC')
            ->findAll();

          for ($i = 0; $i < count($data['dataCuti']); $i++) {
            $buatFormat = date_create($data['dataCuti'][$i]['tanggal']);
            $tanggal = date_format($buatFormat, "d/m/Y");
            $ubahKeIndo = $this->tgl_indo($tanggal);

            $data['dataCuti'][$i] += [
              'periode' => $ubahKeIndo,
              'kepegawaian' => $kepegawaian,
              'atasan' => $atasan,
            ];
          }
        } else {
          // session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
          $data['dataCuti'] = array();
        }
      }

      $role = '<span="text-danger">Anda tidak mempunyai hak akses sementara ini untuk melihat data</span>';
      $kepegawaian == true ? $role = "Kepegawaian" : '';
      $atasan == true ? $role = "Atasan" : '';
      $data['role'] = $role;

      $msg = [
        'tes' => $data,
        'data' => view('cuti/cuti_tabel_permohonan', $data),
        'count' => count($data['dataCuti']),
        'skema' => $skema
      ];

      echo json_encode($msg);
    } else {
      exit('Maaf tidak bisa diproses!');
    }
  }
  
  public function sisa_cuti()
  {
    if ($this->request->isAJAX()) {
      $data['sisa_cuti'] = $this->cutiSisaCutiModel->join('data_pegawai', 'id_data_pegawai', 'RIGHT')
        ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
        ->select('data_pegawai.*, COALESCE(cuti_sisa_cuti.sisa_cuti_tahunan, 0) as sisa_cuti_tahunan')
        ->where('status_bekerja', 'Aktif')
        ->orderBy('golongan.golongan', 'DESC')
        ->findAll();

      $msg = [
        'data' => view('cuti/getSisaCuti', $data)
      ];

      echo json_encode($msg);
    }
  }
  
  public function ajax_cek_sisa_cuti()
  {
    if ($this->request->isAJAX()) {
      $nip = $this->request->getPost('nip');
      $jenisCuti = $this->request->getPost('jenis_cuti');
      $jmlHari = $this->request->getPost('jumlah_hari');
      
      // Tambahkan log untuk debugging
      log_message('info', 'Ajax cek sisa cuti - NIP: ' . $nip . ', Jenis: ' . $jenisCuti . ', Jumlah Hari: ' . $jmlHari);
      
      // Ambil data pegawai berdasarkan NIP
      $dataPegawai = $this->dataPegawaiModel->where('nip', $nip)->first();
      
      if (!$dataPegawai) {
        log_message('error', 'Data pegawai tidak ditemukan untuk NIP: ' . $nip);
        echo json_encode([
          'status' => false,
          'message' => 'Data pegawai tidak ditemukan.'
        ]);
        return;
      }
      
      // Ambil data cuti yang sudah diambil tahun ini
      $currentYear = date('Y');
      $cekSisa = $this->cutiDataCutiModel
        ->where('id_data_pegawai', $dataPegawai['id_data_pegawai'])
        ->where('YEAR(tanggal_awal)', $currentYear)
        ->where('id_cuti_status_kepegawaian !=', 4)
        ->where('id_cuti_status_atasan !=', 4)
        ->where('id_cuti_status_pejabat !=', 4)
        ->findAll();
      
      $totalHariSebelumnya = 0;
      foreach ($cekSisa as $key) {
        if ($key['jenis_cuti'] == 'Cuti Tahunan') {
          $cekHariSebelumnya = explode("|", $key['total_hari']);
          for ($i = 0; $i < count($cekHariSebelumnya); $i++) {
            $totalHariSebelumnya = $totalHariSebelumnya + $cekHariSebelumnya[$i];
          }
        }
      }
      
      // Ambil data sisa cuti tambahan
      $tambahanCuti = $this->cutiSisaCutiModel->join('data_pegawai', 'id_data_pegawai', 'RIGHT')
        ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
        ->select('data_pegawai.*, COALESCE(cuti_sisa_cuti.sisa_cuti_tahunan, 0) as sisa_cuti_tahunan')
        ->where('id_data_pegawai', $dataPegawai['id_data_pegawai'])
        ->first();
      
      // Default jatah cuti tahunan adalah 12 hari
      $jatahCutiTahunan = 12;
      
      // Cek status pegawai untuk penanganan khusus PPNPN
      $statusPegawai = $this->dataPegawaiModel->where('nip', $nip)->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai', 'LEFT')->first();
      if ($statusPegawai && $statusPegawai['status_pegawai'] == 'PPNPN') {
        // Untuk PPNPN, jatah cuti tahunan hanya 9 hari
        $jatahCutiTahunan = 9;
      }
      
      $sisaCuti = $jatahCutiTahunan + (isset($tambahanCuti['sisa_cuti_tahunan']) ? $tambahanCuti['sisa_cuti_tahunan'] : 0);
      $sisaCutiTersedia = $sisaCuti - $totalHariSebelumnya;
      
      log_message('info', 'Jatah cuti: ' . $jatahCutiTahunan . ', Sisa cuti: ' . $sisaCuti . ', Total hari sebelumnya: ' . $totalHariSebelumnya . ', Jumlah hari diajukan: ' . $jmlHari . ', NIP: ' . $nip);
      
      if ($statusPegawai && $statusPegawai['status_pegawai'] == 'PPNPN') {
        // Cek permohonan cuti untuk PPNPN
        // Perbaikan kondisi query untuk permohonan cuti
        $cekPerizinan = $this->cutiDataPermohonanModel
          ->join('data_pegawai', 'id_data_pegawai')
          ->where('data_pegawai.id_data_pegawai', $dataPegawai['id_data_pegawai'])
          ->where('YEAR(tanggal)', date("Y"))
          ->findAll();
        
        // Log untuk debugging jumlah permohonan
        log_message('info', 'AJAX - PPNPN - NIP: ' . $nip . ', ID: ' . $dataPegawai['id_data_pegawai'] . ', Jumlah permohonan: ' . count($cekPerizinan));
        
        // Tampilkan detail permohonan untuk debugging
        foreach ($cekPerizinan as $key => $value) {
          log_message('info', 'AJAX - Permohonan #' . $key . ': ' . json_encode($value));
        }
        
        $sisaCutiTersedia = $sisaCutiTersedia - count($cekPerizinan);
        log_message('info', 'Status pegawai: PPNPN, Jumlah permohonan: ' . count($cekPerizinan) . ', Sisa cuti tersedia: ' . $sisaCutiTersedia);
      }
      
      if ($jenisCuti == "Cuti Tahunan" && ($jmlHari > $sisaCutiTersedia)) {
        $response = [
          'status' => false,
          'message' => 'Pengajuan cuti melebihi sisa cuti yang tersedia!<br>Anda telah mengajukan ' . $totalHariSebelumnya . ' hari pada "' . $jenisCuti . '"!<br>Anda menginputkan total pengajuan "' . $jenisCuti . '" selama ' . $jmlHari . ' hari!<br>Sisa cuti Anda adalah sebanyak ' . $sisaCutiTersedia . ' hari pada tahun ' . date("Y") . ' ini!'
        ];
        echo json_encode($response);
        return;
      }
      
      $response = [
        'status' => true,
        'message' => 'Sisa cuti mencukupi.'
      ];
      echo json_encode($response);
      return;
    } else {
      exit('Maaf tidak bisa diproses!');
    }
  }
}