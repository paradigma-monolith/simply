<?php

namespace App\Controllers\Cuti;

use App\Controllers\BaseController;

class Data extends BaseController
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

  function tgl_indo2($tanggal)
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
    $split = explode('-', $tanggal);
    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
  }
  public function data_cuti()
  {
    $data['title'] = "Cuti | Data Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_cuti";
    $data['pns'] = false;

    if (session()->get('role') == "Admin") {
      $data['dataCuti'] = $this->cutiDataCutiModel
        ->select("status_pegawai.*, cuti_data_cuti.id_cuti_data_cuti, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
        cuti_status_kepegawaian.status AS cuti_status_kepegawaian, cuti_status_atasan.status AS cuti_status_atasan, cuti_status_pejabat.status AS cuti_status_pejabat,
        user.nama AS updated,
        cuti_data_cuti.jenis_cuti, cuti_data_cuti.tanggal_awal, cuti_data_cuti.tanggal_akhir, cuti_data_cuti.updated_at AS time")
        ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_cuti.id_cuti_status_kepegawaian')
        ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_cuti.id_cuti_status_atasan')
        ->join('cuti_status AS cuti_status_pejabat', 'cuti_status_pejabat.id_cuti_status=cuti_data_cuti.id_cuti_status_pejabat')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
        ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
        ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
        ->orderBy('cuti_data_cuti.updated_at', 'desc')
        ->findAll();
    } else {
      $getPegawai = $this->userModel->join('data_pegawai', 'data_pegawai.id_user=user.id_user')->join('status_pegawai', 'data_pegawai.id_status_pegawai=status_pegawai.id_status_pegawai')->where('user.username', session()->get('username'))->first();
      if($getPegawai['status_pegawai'] == "PNS" || $getPegawai['status_pegawai'] == "PPPK") {
        $data['pns'] = true;
      }
      $getPegawai = $getPegawai['nip'];
      $data['dataCuti'] = $this->cutiDataCutiModel
        ->select("status_pegawai.*, cuti_data_cuti.id_cuti_data_cuti, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
        cuti_status_kepegawaian.status AS cuti_status_kepegawaian, cuti_status_atasan.status AS cuti_status_atasan, cuti_status_pejabat.status AS cuti_status_pejabat,
        user.nama AS updated,
        cuti_data_cuti.jenis_cuti, cuti_data_cuti.tanggal_awal, cuti_data_cuti.tanggal_akhir, cuti_data_cuti.updated_at AS time")
        ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_cuti.id_cuti_status_kepegawaian')
        ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_cuti.id_cuti_status_atasan')
        ->join('cuti_status AS cuti_status_pejabat', 'cuti_status_pejabat.id_cuti_status=cuti_data_cuti.id_cuti_status_pejabat')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
        ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
        ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
        ->where('data_pegawai.nip', $getPegawai)
        ->orderBy('cuti_data_cuti.updated_at', 'desc')
        ->findAll();
    }
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
      ];
    }

    // Cek sisa cuti ============================================================================================
    // cek status pegawai
    $statusPegawai = $this->dataPegawaiModel->select('status_pegawai')
      ->join('status_pegawai', 'id_status_pegawai')
      ->join('user', 'id_user')
      ->where('user.username', session()->get('username'))->first();

    // Cek sisa cuti tahunan
    $cekSisa = $this->cutiDataCutiModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->where('cuti_data_cuti.jenis_cuti', 'Cuti Tahunan')
      ->like('YEAR(tanggal_awal)', date('Y'))
      ->where('id_cuti_status_kepegawaian !=', 4)
      ->where('id_cuti_status_atasan !=', 4)
      ->where('id_cuti_status_pejabat !=', 4)
      ->findAll();

    $totalHariSebelumnya = 0;
    $jenisCutiMelahirkan = false;
    foreach ($cekSisa as $key) {
      if ($key['jenis_cuti'] == 'Cuti Tahunan') {
        $cekHariSebelumnya = explode("|", $key['total_hari']);
        for ($i = 0; $i < count($cekHariSebelumnya); $i++) {
          $totalHariSebelumnya = $totalHariSebelumnya + $cekHariSebelumnya[$i];
        }
      }
      if ($key['jenis_cuti'] == 'Cuti Melahirkan') {
        $jenisCutiMelahirkan = true;
      }
    }
    if ($jenisCutiMelahirkan == true) {
      $data['sisaCutiMelahirkan'] = 0;
    } else {
      $data['sisaCutiMelahirkan'] = '1x';
    }

    $tambahanCuti = $this->cutiSisaCutiModel->join('data_pegawai', 'id_data_pegawai', 'RIGHT')
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->select('data_pegawai.*, COALESCE(cuti_sisa_cuti.sisa_cuti_tahunan, 0) as sisa_cuti_tahunan')
      ->join('user', 'user.id_user=data_pegawai.id_user', 'RIGHT')
      ->where('user.username', session()->get('username'))
      ->first();

    $sisaCuti = (12 + $tambahanCuti['sisa_cuti_tahunan']) - $totalHariSebelumnya;
  
    
    $data['statusPegawai'] = false;
    if ($statusPegawai) {
      if ($statusPegawai['status_pegawai'] == 'PPNPN') {
        // Untuk PPNPN, jatah cuti tahunan hanya 9 hari
        $sisaCuti = (9 + $tambahanCuti['sisa_cuti_tahunan']) - $totalHariSebelumnya;
        
        // Perbaikan kondisi query untuk permohonan cuti
        $cekPerizinan = $this->cutiDataPermohonanModel
          ->join('data_pegawai', 'id_data_pegawai')
          ->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('user.username', session()->get('username'))
          ->where('YEAR(tanggal)', date("Y"))
          ->findAll();

        // Log untuk debugging jumlah permohonan
        log_message('info', 'PPNPN - Jumlah permohonan: ' . count($cekPerizinan) . ', Username: ' . session()->get('username'));
        
        // Tampilkan detail permohonan untuk debugging
        foreach ($cekPerizinan as $key => $value) {
          log_message('info', 'Permohonan #' . $key . ': ' . json_encode($value));
        }

        $sisaCuti = $sisaCuti - count($cekPerizinan);
      }
      if ($statusPegawai['status_pegawai'] == 'PPNPN' || $statusPegawai['status_pegawai'] == 'PPPK' || $statusPegawai['status_pegawai'] == 'PNS') {
        $data['statusPegawai'] = true;
      }
    }
    $data['sisaCutiTahunan'] = $sisaCuti;
    // Cek sisa cuti ============================================================================================
    
    return view('cuti/cuti_data', $data);
  }
  public function tambah_cuti()
  {
    $data['title'] = "Cuti | Data Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_cuti";
    $data['signature'] = true;

    if (session()->get('role') != 'Admin') {
      $data['getThisPegawai'] = $this->dataPegawaiModel
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))->first()['nip'];
    } else {
      $data['getThisPegawai'] = null;
    }

    $data['pegawai'] = $this->dataPegawaiModel->findAll();
    $data['skema'] = $this->skemaTitimangsaModel
      ->select('skema_titimangsa.id_skema_titimangsa, skema_titimangsa.jenis_surat, unit_kerja.unit_kerja, unit_kerja.lokasi,
    jabatan_kepegawaian.jabatan AS jabatan_kepegawaian, kepegawaian.nama AS nama_kepegawaian, kepegawaian.nip AS nip_kepegawaian,
    jabatan_pejabat.jabatan AS jabatan_pejabat, pejabat.nama AS nama_pejabat, pejabat.nip AS nip_pejabat,
    jabatan_atasan.jabatan AS jabatan_atasan, atasan.nama AS nama_atasan, atasan.nip AS nip_atasan, 
    skema_titimangsa.updated_at AS time, user.nama AS updated
    ')
      ->join('data_pegawai AS kepegawaian', 'kepegawaian.id_data_pegawai=skema_titimangsa.kepegawaian')
      ->join('data_pegawai AS pejabat', 'pejabat.id_data_pegawai=skema_titimangsa.pejabat')
      ->join('data_pegawai AS atasan', 'atasan.id_data_pegawai=skema_titimangsa.atasan')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=skema_titimangsa.id_unit_kerja')
      ->join('user', 'user.id_user=skema_titimangsa.updated_by')
      ->join('jabatan AS jabatan_kepegawaian', 'jabatan_kepegawaian.id_jabatan=kepegawaian.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_pejabat', 'jabatan_pejabat.id_jabatan=pejabat.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_atasan', 'jabatan_atasan.id_jabatan=atasan.id_jabatan', 'LEFT')
      ->orderBy('skema_titimangsa.updated_at', 'desc')
      ->findAll();

    return view('crud/cuti_tambah_data', $data);
  }
  public function update_cuti($id = null)
  {
    $data['title'] = "Cuti | Data Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_cuti";
    $data['signature'] = true;

    $data['pegawai'] = $this->dataPegawaiModel->findAll();

    $data['skema'] = $this->skemaTitimangsaModel
      ->select('skema_titimangsa.id_skema_titimangsa, skema_titimangsa.jenis_surat, unit_kerja.unit_kerja, unit_kerja.lokasi,
  jabatan_kepegawaian.jabatan AS jabatan_kepegawaian, kepegawaian.nama AS nama_kepegawaian, kepegawaian.nip AS nip_kepegawaian,
  jabatan_pejabat.jabatan AS jabatan_pejabat, pejabat.nama AS nama_pejabat, pejabat.nip AS nip_pejabat,
  jabatan_atasan.jabatan AS jabatan_atasan, atasan.nama AS nama_atasan, atasan.nip AS nip_atasan, 
  skema_titimangsa.updated_at AS time, user.nama AS updated
  ')
      ->join('data_pegawai AS kepegawaian', 'kepegawaian.id_data_pegawai=skema_titimangsa.kepegawaian')
      ->join('data_pegawai AS pejabat', 'pejabat.id_data_pegawai=skema_titimangsa.pejabat')
      ->join('data_pegawai AS atasan', 'atasan.id_data_pegawai=skema_titimangsa.atasan')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=skema_titimangsa.id_unit_kerja')
      ->join('user', 'user.id_user=skema_titimangsa.updated_by')
      ->join('jabatan AS jabatan_kepegawaian', 'jabatan_kepegawaian.id_jabatan=kepegawaian.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_pejabat', 'jabatan_pejabat.id_jabatan=pejabat.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_atasan', 'jabatan_atasan.id_jabatan=atasan.id_jabatan', 'LEFT')
      ->orderBy('skema_titimangsa.updated_at', 'desc')
      ->findAll();

    $getDataPegawai = $this->dataPegawaiModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('cuti_data_cuti', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first()['unit_kerja'];

    $data['dataCuti'] = $this->cutiDataCutiModel
      ->select("*, cuti_data_cuti.no_telp")
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first();

    $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);

    $data['getSkema'] = $this->skemaTitimangsaModel
      ->where('kepegawaian', $dataSkemaCuti[0])
      ->where('atasan', $dataSkemaCuti[1])
      ->where('pejabat', $dataSkemaCuti[2])
      ->first()['id_skema_titimangsa'];

    $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')->first();
    $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')->first();
    $getPejabat = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[2])
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')->first();
    $getUnitKerja = $this->unitKerjaModel->join('cuti_data_cuti', 'cuti_data_cuti.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_cuti', $id)->first();

    $data['dataCuti'] += [
      'unit_kerja_pegawai'  => $getDataPegawai,
      'jenis_surat'         => 'cuti',
      'unit_kerja_skema'    => $getUnitKerja['unit_kerja'],
      'lokasi_skema'        => $getUnitKerja['lokasi'],
      'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
      'nama_kepegawaian'    => $getKepegawaian['nama'],
      'nip_kepegawaian'     => $getKepegawaian['nip'],
      'jabatan_pejabat'     => $getPejabat['jabatan'],
      'nama_pejabat'        => $getPejabat['nama'],
      'nip_pejabat'         => $getPejabat['nip'],
      'jabatan_atasan'      => $getAtasan['jabatan'],
      'nama_atasan'         => $getAtasan['nama'],
      'nip_atasan'          => $getAtasan['nip'],
    ];

    $tanggal1 = explode("|", $data['dataCuti']['tanggal_awal']);
    $tanggal2 = explode("|", $data['dataCuti']['tanggal_akhir']);
    $ubahKeIndo1 = '';
    $ubahKeIndo2 = '';
    for ($j = 0; $j < count($tanggal1); $j++) {
      $date1 = date_create($tanggal1[$j]);
      $date2 = date_create($tanggal2[$j]);

      $ubahKeIndo1 .= $this->tgl_indo(date_format($date1, "d/m/Y")) . '|';
      $ubahKeIndo2 .= $this->tgl_indo(date_format($date2, "d/m/Y")) . '|';
    }
    $data['dataCuti'] += [
      'periode1' => substr($ubahKeIndo1, 0, -1),
      'periode2' => substr($ubahKeIndo2, 0, -1),
    ];

    return view('crud/cuti_update_data', $data);
  }
  public function validation_cuti()
  {
    $data['title'] = "Cuti | Data Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "validation_data_cuti";


    $kepegawaian = false;
    $atasan = false;
    $pejabat = false;
    $byIdPegawai = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))->first();
    if (!isset($byIdPegawai)) {
      $data['dataCuti'] = array();
      session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
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
          ->select("cuti_data_cuti.id_cuti_data_cuti, cuti_data_cuti.id_skema_titimangsa, cuti_data_cuti.id_cuti_data_cuti, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
          cuti_status_kepegawaian.status AS cuti_status_kepegawaian, cuti_status_atasan.status AS cuti_status_atasan, cuti_status_pejabat.status AS cuti_status_pejabat,
        user.nama AS updated,
        cuti_data_cuti.jenis_cuti, cuti_data_cuti.tanggal_awal, cuti_data_cuti.tanggal_akhir, cuti_data_cuti.updated_at AS time, cuti_data_cuti.koordinator_status")
          ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_cuti.id_cuti_status_kepegawaian')
          ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_cuti.id_cuti_status_atasan')
          ->join('cuti_status AS cuti_status_pejabat', 'cuti_status_pejabat.id_cuti_status=cuti_data_cuti.id_cuti_status_pejabat')
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
          ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
          ->where($whereClause . '.status !=', 'Pending')
          ->orWhere('id_cuti_status_kepegawaian', 4)
          ->orWhere('id_cuti_status_atasan', 4)
          ->orWhere('id_cuti_status_pejabat', 4)
          ->like('cuti_data_cuti.id_skema_titimangsa', $kondisiValue, $kondisi)
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
      } else {
        $id = $this->dataPegawaiModel->join("user", "data_pegawai.id_user=user.id_user")
          ->where('user.username', session()->get("username"))
          ->first();

        $data['dataCuti'] = $this->cutiDataCutiModel
          ->select("cuti_data_cuti.id_cuti_data_cuti, cuti_data_cuti.id_skema_titimangsa, cuti_data_cuti.id_cuti_data_cuti, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
            cuti_status_kepegawaian.status AS cuti_status_kepegawaian, cuti_status_atasan.status AS cuti_status_atasan, cuti_status_pejabat.status AS cuti_status_pejabat,
            user.nama AS updated,
            cuti_data_cuti.jenis_cuti, cuti_data_cuti.tanggal_awal, cuti_data_cuti.tanggal_akhir, cuti_data_cuti.updated_at AS time, cuti_data_cuti.koordinator_status")
          ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_cuti.id_cuti_status_kepegawaian')
          ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_cuti.id_cuti_status_atasan')
          ->join('cuti_status AS cuti_status_pejabat', 'cuti_status_pejabat.id_cuti_status=cuti_data_cuti.id_cuti_status_pejabat')
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
          ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
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
        if ($data['dataCuti']) {
          session()->setFlashdata('info', 'Anda terpilih menjadi koordinator tempat oleh Pemohon pada Sistem yang berjalan!');
        } else {
          session()->setFlashdata('info', 'Anda tidak terpilih menjadi Tim Validator!');
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

    return view('cuti/cuti_validation', $data);
  }
  public function detail_cuti($id = null)
  {
    $data['title'] = "Cuti | Data Detail Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_cuti";
    $getDataPegawai = $this->dataPegawaiModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->join('cuti_data_cuti', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first()['unit_kerja'];
    $data['dataCuti'] = $this->cutiDataCutiModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
      ->join('jabatan', 'cuti_data_cuti.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first();

    $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
    $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getPejabat = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[2])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getUnitKerja = $this->unitKerjaModel->join('cuti_data_cuti', 'cuti_data_cuti.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_cuti', $id)->first();

    $data['dataCuti'] += [
      'unit_kerja_pegawai'  => $getDataPegawai,
      'jenis_surat'         => 'cuti',
      'unit_kerja_skema'    => $getUnitKerja['unit_kerja'],
      'lokasi_skema'        => $getUnitKerja['lokasi'],
      'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
      'nama_kepegawaian'    => $getKepegawaian['nama'],
      'nip_kepegawaian'     => $getKepegawaian['nip'],
      'jabatan_pejabat'     => $getPejabat['jabatan'],
      'nama_pejabat'        => $getPejabat['nama'],
      'nip_pejabat'         => $getPejabat['nip'],
      'jabatan_atasan'      => $getAtasan['jabatan'],
      'nama_atasan'         => $getAtasan['nama'],
      'nip_atasan'          => $getAtasan['nip'],
    ];
    $tanggal1 = explode("|", $data['dataCuti']['tanggal_awal']);
    $tanggal2 = explode("|", $data['dataCuti']['tanggal_akhir']);
    $ubahKeIndo1 = '';
    $ubahKeIndo2 = '';
    for ($j = 0; $j < count($tanggal1); $j++) {
      $date1 = date_create($tanggal1[$j]);
      $date2 = date_create($tanggal2[$j]);

      $ubahKeIndo1 .= $this->tgl_indo(date_format($date1, "d/m/Y")) . '|';
      $ubahKeIndo2 .= $this->tgl_indo(date_format($date2, "d/m/Y")) . '|';
    }
    $data['dataCuti'] += [
      'periode1' => substr($ubahKeIndo1, 0, -1),
      'periode2' => substr($ubahKeIndo2, 0, -1),
    ];
    $data['linimasa'] = $this->linimasaModel
      ->select('user.nama, linimasa.*')
      ->join('user', 'user.id_user=linimasa.id_user')
      ->where('id_table', $id)
      ->where('table', 'cuti_data_cuti')
      ->orderBy('created_at', 'DESC')->findAll();
    // dd($data['dataCuti']);
    foreach ($data['linimasa'] as &$item) {
      $item['tanggal'] = $this->tgl_indo2(substr($item['created_at'], 0, 10));
      $item['jam'] = substr($item['created_at'], 11, 5);
    }

    return view('cuti/cuti_detail', $data);
  }
  public function validation_detail_cuti($id = null)
  {
    $data['title'] = "Cuti | Validation Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "validation_data_cuti";
    $data['signature'] = true;

    $kepegawaian = false;
    $atasan = false;
    $pejabat = false;
    $byIdPegawai = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('user.username', session()->get('username'))->first();
    if (!isset($byIdPegawai)) {
      $data['dataCuti'] = array();
      session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
      return redirect()->to('cuti/data_cuti');
    } else {
      $byIdPegawai = $byIdPegawai['id_data_pegawai'];
      $skema = $this->skemaTitimangsaModel->where('jenis_surat', 'cuti')->findAll();

      $kondisiValue = '';
      $kondisi = '';
      $whereClause = '';
      for ($i = 0; $i < count($skema); $i++) {
        if ($skema[$i]['kepegawaian'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_kepegawaian'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = $byIdPegawai . '|';
          $kondisi = 'after';
          $kepegawaian = true;
        }
        if ($skema[$i]['atasan'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_atasan'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = '|' . $byIdPegawai . '|';
          $kondisi = 'both';
          $atasan = true;
        }
        if ($skema[$i]['pejabat'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_pejabat'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = '|' . $byIdPegawai;
          $kondisi = 'before';
          $pejabat = true;
        }
      }
      // Cek apakah Status Sebelumnya sudah disetujui
      if ($atasan == true) {
        $cek = $this->cutiDataCutiModel
          ->where('id_cuti_data_cuti', $id)
          ->where('id_cuti_status_kepegawaian', 2)
          ->first();
        if (!isset($cek)) {
          session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
          return redirect()->to('cuti/validation_cuti');
        }
      } elseif ($pejabat == true) {
        $cek = $this->cutiDataCutiModel
          ->where('id_cuti_data_cuti', $id)
          ->where('id_cuti_status_atasan', 2)
          ->first();
        if (!isset($cek)) {
          session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
          return redirect()->to('cuti/validation_cuti');
        }
      }

      $data['dataCuti'] = $this->cutiDataCutiModel
        ->select("*,data_pegawai.nama, cuti_data_cuti.no_telp")
        ->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_cuti.' . $whereClause)
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
        ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
        ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
        ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
        ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
        ->like('cuti_data_cuti.id_skema_titimangsa', $kondisiValue, $kondisi)
        ->where('cuti_data_cuti.' . $whereClause, 1)
        ->first();


      $getDataPegawai = $this->dataPegawaiModel
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
        ->join('cuti_data_cuti', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
        ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
        ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
        ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
        ->first();

      if (!isset($getDataPegawai)) {
        session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
        return redirect()->to('cuti/validation_cuti');
      } else {
        $getDataPegawai = $getDataPegawai['unit_kerja'];
      }

      // Pecah dulu id skema titimangsa

      if (isset($data['dataCuti']['id_skema_titimangsa'])) {
        $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
        $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getPejabat = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[2])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getUnitKerja = $this->unitKerjaModel->join('cuti_data_cuti', 'cuti_data_cuti.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_cuti', $id)->first();

        $data['dataCuti'] += [
          'unit_kerja_pegawai'  => $getDataPegawai,
          'jenis_surat'         => 'cuti',
          'unit_kerja_skema'    => $getUnitKerja['unit_kerja'],
          'lokasi_skema'        => $getUnitKerja['lokasi'],
          'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
          'nama_kepegawaian'    => $getKepegawaian['nama'],
          'nip_kepegawaian'     => $getKepegawaian['nip'],
          'jabatan_pejabat'     => $getPejabat['jabatan'],
          'nama_pejabat'        => $getPejabat['nama'],
          'nip_pejabat'         => $getPejabat['nip'],
          'jabatan_atasan'      => $getAtasan['jabatan'],
          'nama_atasan'         => $getAtasan['nama'],
          'nip_atasan'          => $getAtasan['nip'],
        ];
        $tanggal1 = explode("|", $data['dataCuti']['tanggal_awal']);
        $tanggal2 = explode("|", $data['dataCuti']['tanggal_akhir']);
        $ubahKeIndo1 = '';
        $ubahKeIndo2 = '';
        for ($j = 0; $j < count($tanggal1); $j++) {
          $date1 = date_create($tanggal1[$j]);
          $date2 = date_create($tanggal2[$j]);

          $ubahKeIndo1 .= $this->tgl_indo(date_format($date1, "d/m/Y")) . '|';
          $ubahKeIndo2 .= $this->tgl_indo(date_format($date2, "d/m/Y")) . '|';
        }
        $data['dataCuti'] += [
          'periode1' => substr($ubahKeIndo1, 0, -1),
          'periode2' => substr($ubahKeIndo2, 0, -1),
          'kepegawaian' => $kepegawaian,
          'atasan' => $atasan,
          'pejabat' => $pejabat,
        ];

        $data['linimasa'] = $this->linimasaModel
          ->select('user.nama, linimasa.*')
          ->join('user', 'user.id_user=linimasa.id_user')
          ->where('id_table', $id)
          ->where('table', 'cuti_data_cuti')
          ->orderBy('created_at', 'DESC')->findAll();

        foreach ($data['linimasa'] as &$item) {
          $item['tanggal'] = $this->tgl_indo2(substr($item['created_at'], 0, 10));
          $item['jam'] = substr($item['created_at'], 11, 5);
        }
      } else {
        session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
        return redirect()->to('cuti/validation_cuti');
      }
    }

    if (preg_match_all("/\b\d{4}\b/", $data['dataCuti']['tanggal_awal'], $matches)) {
      $tahun = $matches[0];
    }
    $data['tahun'] = $tahun[0];
    $data['cekSisa'] = $this->cutiDataCutiModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('cuti_data_cuti.id_data_pegawai', $data['dataCuti']['id_data_pegawai'])
      ->where('cuti_data_cuti.jenis_cuti', 'Cuti Tahunan')
      ->like('tanggal_awal', $tahun[0])
      ->where('id_cuti_status_kepegawaian !=', 4)
      ->where('id_cuti_status_atasan !=', 4)
      ->where('id_cuti_status_pejabat !=', 4)
      ->findAll();

    $data['tambahanCuti'] = $this->cutiSisaCutiModel->join('data_pegawai', 'id_data_pegawai', 'RIGHT')
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->select('data_pegawai.*, COALESCE(cuti_sisa_cuti.sisa_cuti_tahunan, 0) as sisa_cuti_tahunan')
      ->where('data_pegawai.id_data_pegawai', $data['dataCuti']['id_data_pegawai'])
      ->first();

    return view('cuti/cuti_validation_detail', $data);
  }
  public function validation_detail_cuti_koordinator($id = null)
  {
    $data['title'] = "Cuti | Validation Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "validation_data_cuti";
    $data['signature'] = true;
    $kepegawaian = false;
    $atasan = false;
    $pejabat = false;
    $byIdPegawai = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('user.username', session()->get('username'))->first();

    if (!isset($byIdPegawai)) {
      $data['dataCuti'] = array();
      session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
      return redirect()->to('cuti/data_cuti');
    } else {
      $byIdPegawai = $byIdPegawai['id_data_pegawai'];
      $skema = $this->skemaTitimangsaModel->where('jenis_surat', 'cuti')->findAll();

      $kondisiValue = '';
      $kondisi = '';
      $whereClause = '';
      for ($i = 0; $i < count($skema); $i++) {
        if ($skema[$i]['kepegawaian'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_kepegawaian'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = $byIdPegawai . '|';
          $kondisi = 'after';
          $kepegawaian = true;
        }
        if ($skema[$i]['atasan'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_atasan'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = '|' . $byIdPegawai . '|';
          $kondisi = 'both';
          $atasan = true;
        }
        if ($skema[$i]['pejabat'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_pejabat'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = '|' . $byIdPegawai;
          $kondisi = 'before';
          $pejabat = true;
        }
      }
      // Cek apakah Status Sebelumnya sudah disetujui

      if ($atasan == true) {
        $cek = $this->cutiDataCutiModel
          ->where('id_cuti_data_cuti', $id)
          ->where('id_cuti_status_kepegawaian', 2)
          ->first();
        if (!isset($cek)) {
          session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
          return redirect()->to('cuti/validation_cuti');
        }
      } elseif ($pejabat == true) {
        $cek = $this->cutiDataCutiModel
          ->where('id_cuti_data_cuti', $id)
          ->where('id_cuti_status_atasan', 2)
          ->first();
        if (!isset($cek)) {
          session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
          return redirect()->to('cuti/validation_cuti');
        }
      }
      $cekDataCuti = $this->cutiDataCutiModel
        ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
        ->first();
      if ($cekDataCuti['koordinator_status'] == "Terkirim") {
        $data['dataCuti'] = $this->cutiDataCutiModel
          ->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_cuti.' . $whereClause)
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
          ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
          ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
          ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
          ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
          ->like('cuti_data_cuti.id_skema_titimangsa', $kondisiValue, $kondisi)
          ->where('cuti_data_cuti.' . $whereClause, 1)
          ->first();
      } else {

        $cekDataCuti = $this->cutiDataCutiModel->select('data_pegawai.nama')
          ->join('data_pegawai', "data_pegawai.id_data_pegawai=cuti_data_cuti.koordinator_tempat")
          ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
          ->first();
        $data['dataCuti'] = $this->cutiDataCutiModel
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
          ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
          ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
          ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
          ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
          ->first();

        $data['dataCuti']['koordinator'] = $cekDataCuti['nama'];
        $data['dataCuti']['status'] = "Koordinator Tempat";
      }


      $getDataPegawai = $this->dataPegawaiModel
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
        ->join('cuti_data_cuti', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
        ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
        ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
        ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
        ->first();


      if (!isset($getDataPegawai)) {
        session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
        return redirect()->to('cuti/validation_cuti');
      } else {
        $getDataPegawai = $getDataPegawai['unit_kerja'];
      }

      // Pecah dulu id skema titimangsa

      if (isset($data['dataCuti']['id_skema_titimangsa'])) {
        $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
        $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getPejabat = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[2])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getUnitKerja = $this->unitKerjaModel->join('cuti_data_cuti', 'cuti_data_cuti.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_cuti', $id)->first();

        $data['dataCuti'] += [
          'unit_kerja_pegawai'  => $getDataPegawai,
          'jenis_surat'         => 'cuti',
          'unit_kerja_skema'    => $getUnitKerja['unit_kerja'],
          'lokasi_skema'        => $getUnitKerja['lokasi'],
          'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
          'nama_kepegawaian'    => $getKepegawaian['nama'],
          'nip_kepegawaian'     => $getKepegawaian['nip'],
          'jabatan_pejabat'     => $getPejabat['jabatan'],
          'nama_pejabat'        => $getPejabat['nama'],
          'nip_pejabat'         => $getPejabat['nip'],
          'jabatan_atasan'      => $getAtasan['jabatan'],
          'nama_atasan'         => $getAtasan['nama'],
          'nip_atasan'          => $getAtasan['nip'],
        ];
        $tanggal1 = explode("|", $data['dataCuti']['tanggal_awal']);
        $tanggal2 = explode("|", $data['dataCuti']['tanggal_akhir']);
        $ubahKeIndo1 = '';
        $ubahKeIndo2 = '';
        for ($j = 0; $j < count($tanggal1); $j++) {
          $date1 = date_create($tanggal1[$j]);
          $date2 = date_create($tanggal2[$j]);

          $ubahKeIndo1 .= $this->tgl_indo(date_format($date1, "d/m/Y")) . '|';
          $ubahKeIndo2 .= $this->tgl_indo(date_format($date2, "d/m/Y")) . '|';
        }
        $data['dataCuti'] += [
          'periode1' => substr($ubahKeIndo1, 0, -1),
          'periode2' => substr($ubahKeIndo2, 0, -1),
          'kepegawaian' => $kepegawaian,
          'atasan' => $atasan,
          'pejabat' => $pejabat,
        ];

        $data['linimasa'] = $this->linimasaModel
          ->select('user.nama, linimasa.*')
          ->join('user', 'user.id_user=linimasa.id_user')
          ->where('id_table', $id)
          ->where('table', 'cuti_data_cuti')
          ->orderBy('created_at', 'DESC')->findAll();

        foreach ($data['linimasa'] as &$item) {
          $item['tanggal'] = $this->tgl_indo2(substr($item['created_at'], 0, 10));
          $item['jam'] = substr($item['created_at'], 11, 5);
        }
      } else {
        session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
        return redirect()->to('cuti/validation_cuti');
      }
    }

    return view('cuti/cuti_validation_detail_koordinator', $data);
  }

  // ======================================================================================
  public function data_permohonan()
  {
    $data['title'] = "Cuti | Data Perizinan"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_permohonan";

    if (session()->get('role') == "Admin") {
      $data['dataCuti'] = $this->cutiDataPermohonanModel
        ->select("cuti_data_permohonan.id_cuti_data_permohonan, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
        cuti_status_kepegawaian.status AS status_kepegawaian,
      cuti_status_atasan.status AS status_atasan,
      user.nama AS updated,
      cuti_data_permohonan.jenis_permohonan, cuti_data_permohonan.tanggal, cuti_data_permohonan.updated_at AS time")
        ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_permohonan.id_cuti_status_kepegawaian')
        ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_permohonan.id_cuti_status_atasan')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
        ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
        ->orderBy('cuti_data_permohonan.updated_at', 'desc')
        ->findAll();
    } else {
      $getPegawai = $this->userModel->join('data_pegawai', 'data_pegawai.id_user=user.id_user')->where('user.username', session()->get('username'))->first()['nip'];
      $data['dataCuti'] = $this->cutiDataPermohonanModel
        ->select("cuti_data_permohonan.id_cuti_data_permohonan, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
        cuti_status_kepegawaian.status AS status_kepegawaian,
      cuti_status_atasan.status AS status_atasan,
      user.nama AS updated,
      cuti_data_permohonan.jenis_permohonan, cuti_data_permohonan.tanggal, cuti_data_permohonan.updated_at AS time")
        ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_permohonan.id_cuti_status_kepegawaian')
        ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_permohonan.id_cuti_status_atasan')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
        ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
        ->where('data_pegawai.nip', $getPegawai)
        ->orderBy('cuti_data_permohonan.updated_at', 'desc')
        ->findAll();
    }

    // dd($data['dataCuti']);

    return view('cuti/cuti_data_permohonan', $data);
  }
  public function tambah_permohonan()
  {
    $data['title'] = "Cuti | Data Perizinan"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_permohonan";
    $data['signature'] = true;

    if (session()->get('role') != 'Admin') {
      $data['getThisPegawai'] = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')->where('user.username', session()->get('username'))->first()['nip'];

      $cek = $this->cutiDataPermohonanModel
        ->select("cuti_data_permohonan.id_cuti_data_permohonan, data_pegawai.nama AS nama_pegawai, data_pegawai.nip AS nip_pegawai,
        cuti_status_kepegawaian.status AS status_kepegawaian,
      cuti_status_atasan.status AS status_atasan,
      user.nama AS updated,
      cuti_data_permohonan.jenis_permohonan, cuti_data_permohonan.tanggal, cuti_data_permohonan.updated_at AS time")
        ->join('cuti_status AS cuti_status_kepegawaian', 'cuti_status_kepegawaian.id_cuti_status=cuti_data_permohonan.id_cuti_status_kepegawaian')
        ->join('cuti_status AS cuti_status_atasan', 'cuti_status_atasan.id_cuti_status=cuti_data_permohonan.id_cuti_status_atasan')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
        ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
        ->where('data_pegawai.nip', $data['getThisPegawai'])
        ->like('cuti_data_permohonan.tanggal', date("Y"), 'before')
        ->orderBy('cuti_data_permohonan.updated_at', 'desc')
        ->findAll();

      $count = 0;
      foreach ($cek as $ceks) {
        if ($ceks['status_kepegawaian'] == "Ditolak" || $ceks['status_atasan'] == "Ditolak") {
        } else {
          $count = $count + 1;
          echo "Tes 1 <br/>";
        }
      }

      if ($count >= 2) {
        session()->setFlashdata('info', 'Anda telah mengajukan 2x pengajuan surat sakti pada tahun ' . date('Y') . '!');
        return redirect()->to('cuti/data_permohonan');
      }
    } else {
      $data['getThisPegawai'] = null;
    }

    $data['pegawai'] = $this->dataPegawaiModel->findAll();
    $data['skema'] = $this->skemaTitimangsaModel
      ->select('skema_titimangsa.id_skema_titimangsa, skema_titimangsa.jenis_surat, unit_kerja.unit_kerja, unit_kerja.lokasi,
      jabatan_kepegawaian.jabatan AS jabatan_kepegawaian, kepegawaian.nama AS nama_kepegawaian, kepegawaian.nip AS nip_kepegawaian,
      jabatan_pejabat.jabatan AS jabatan_pejabat, pejabat.nama AS nama_pejabat, pejabat.nip AS nip_pejabat,
      jabatan_atasan.jabatan AS jabatan_atasan, atasan.nama AS nama_atasan, atasan.nip AS nip_atasan, 
      skema_titimangsa.updated_at AS time, user.nama AS updated
      ')
      ->join('data_pegawai AS kepegawaian', 'kepegawaian.id_data_pegawai=skema_titimangsa.kepegawaian', 'LEFT')
      ->join('data_pegawai AS pejabat', 'pejabat.id_data_pegawai=skema_titimangsa.pejabat', 'LEFT')
      ->join('data_pegawai AS atasan', 'atasan.id_data_pegawai=skema_titimangsa.atasan', 'LEFT')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=skema_titimangsa.id_unit_kerja')
      ->join('user', 'user.id_user=skema_titimangsa.updated_by')
      ->join('jabatan AS jabatan_kepegawaian', 'jabatan_kepegawaian.id_jabatan=kepegawaian.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_pejabat', 'jabatan_pejabat.id_jabatan=pejabat.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_atasan', 'jabatan_atasan.id_jabatan=atasan.id_jabatan', 'LEFT')
      ->orderBy('skema_titimangsa.updated_at', 'desc')->findAll();

    return view('crud/cuti_tambah_data_permohonan', $data);
  }
  public function update_permohonan($id = null)
  {
    $data['title'] = "Cuti | Data Perizinan"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_permohonan";
    $data['signature'] = true;

    $data['pegawai'] = $this->dataPegawaiModel->findAll();
    $data['skema'] = $this->skemaTitimangsaModel
      ->select('skema_titimangsa.id_skema_titimangsa, skema_titimangsa.jenis_surat, unit_kerja.unit_kerja, unit_kerja.lokasi,
      jabatan_kepegawaian.jabatan AS jabatan_kepegawaian, kepegawaian.nama AS nama_kepegawaian, kepegawaian.nip AS nip_kepegawaian,
      jabatan_pejabat.jabatan AS jabatan_pejabat, pejabat.nama AS nama_pejabat, pejabat.nip AS nip_pejabat,
      jabatan_atasan.jabatan AS jabatan_atasan, atasan.nama AS nama_atasan, atasan.nip AS nip_atasan, 
      skema_titimangsa.updated_at AS time, user.nama AS updated
      ')
      ->join('data_pegawai AS kepegawaian', 'kepegawaian.id_data_pegawai=skema_titimangsa.kepegawaian', 'LEFT')
      ->join('data_pegawai AS pejabat', 'pejabat.id_data_pegawai=skema_titimangsa.pejabat', 'LEFT')
      ->join('data_pegawai AS atasan', 'atasan.id_data_pegawai=skema_titimangsa.atasan', 'LEFT')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=skema_titimangsa.id_unit_kerja')
      ->join('user', 'user.id_user=skema_titimangsa.updated_by')
      ->join('jabatan AS jabatan_kepegawaian', 'jabatan_kepegawaian.id_jabatan=kepegawaian.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_pejabat', 'jabatan_pejabat.id_jabatan=pejabat.id_jabatan', 'LEFT')
      ->join('jabatan AS jabatan_atasan', 'jabatan_atasan.id_jabatan=atasan.id_jabatan', 'LEFT')
      ->orderBy('skema_titimangsa.updated_at', 'desc')->findAll();

    $data['dataCuti'] = $this->cutiDataPermohonanModel
      ->where('id_cuti_data_permohonan', $id)->first();
    $getSkema = '';
    $getDataID = explode('|', $data['dataCuti']['id_skema_titimangsa']);

    $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $getDataID[0])
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')->first();
    $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $getDataID[1])
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')->first();

    $getSkema = $this->skemaTitimangsaModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=skema_titimangsa.id_unit_kerja')
      ->where('kepegawaian', $getDataID[0])->where('atasan', $getDataID[1])->where('jenis_surat', 'surat_sakti')->first();

    $data['dataCuti'] += [
      'unit_kerja_skema'    => $getSkema['unit_kerja'],
      'lokasi_skema'        => $getSkema['lokasi'],
      'getSkema'            => $getSkema['id_skema_titimangsa'],
      'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
      'nama_kepegawaian'    => $getKepegawaian['nama'],
      'nip_kepegawaian'     => $getKepegawaian['nip'],
      'jabatan_atasan'      => $getAtasan['jabatan'],
      'nama_atasan'         => $getAtasan['nama'],
      'nip_atasan'          => $getAtasan['nip'],
    ];

    return view('crud/cuti_update_data_permohonan', $data);
  }
  public function detail_permohonan($id = null)
  {
    $data['title'] = "Cuti | Detail Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "kelola_data_permohonan";


    $getDataPegawai = $this->dataPegawaiModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('cuti_data_permohonan', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
      ->first()['unit_kerja'];

    $data['dataCuti'] = $this->cutiDataPermohonanModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
      ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
      ->first();

    $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
    $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getUnitKerja = $this->unitKerjaModel->join('cuti_data_permohonan', 'cuti_data_permohonan.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_permohonan', $id)->first();

    $data['dataCuti'] += [
      'unit_kerja_pegawai'  => $getDataPegawai,
      'jenis_surat'         => 'surat_sakti',
      'unit_kerja_skema'    => $getUnitKerja['unit_kerja'],
      'lokasi_skema'        => $getUnitKerja['lokasi'],
      'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
      'nama_kepegawaian'    => $getKepegawaian['nama'],
      'nip_kepegawaian'     => $getKepegawaian['nip'],
      'jabatan_atasan'      => $getAtasan['jabatan'],
      'nama_atasan'         => $getAtasan['nama'],
      'nip_atasan'          => $getAtasan['nip'],
    ];
    $buatFormat = date_create($data['dataCuti']['tanggal']);
    $tanggal = date_format($buatFormat, "d/m/Y");
    $ubahKeIndo = $this->tgl_indo($tanggal);
    $data['dataCuti'] += [
      'periode' => $ubahKeIndo,
    ];
    // dd($data['dataCuti']);

    return view('cuti/cuti_detail_permohonan', $data);
  }
  public function validation_permohonan()
  {
    $data['title'] = "Cuti | Data Perizinan"; // kelola_data_cuti
    $data['activeMenu'] = "validation_data_permohonan";

    $kepegawaian = false;
    $atasan = false;
    $byIdPegawai = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))->first();
    if (!isset($byIdPegawai)) {
      $data['dataCuti'] = array();
      session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
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
          ->where($whereClause . '.status !=', 'Pending')
          ->orWhere('id_cuti_status_kepegawaian', 4)
          ->orWhere('id_cuti_status_atasan', 4)
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
        session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
        $data['dataCuti'] = array();
      }
    }

    $role = '<span="text-danger">Anda tidak mempunyai hak akses sementara ini untuk melihat data</span>';
    $kepegawaian == true ? $role = "Kepegawaian" : '';
    $atasan == true ? $role = "Atasan" : '';
    $data['role'] = $role;

    return view('cuti/cuti_validation_permohonan', $data);
  }
  public function validation_detail_permohonan($id = null)
  {
    $data['title'] = "Cuti | Validation Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "validation_data_permohonan";
    $data['signature'] = true;

    $kepegawaian = false;
    $atasan = false;
    $byIdPegawai = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('user.username', session()->get('username'))->first();
    if (!isset($byIdPegawai)) {
      $data['dataCuti'] = array();
      session()->setFlashdata('info', 'Maaf data validator anda tidak tersedia!');
      return redirect()->to('cuti/data_permohonan');
    } else {
      $byIdPegawai = $byIdPegawai['id_data_pegawai'];
      $skema = $this->skemaTitimangsaModel->where('jenis_surat', 'surat_sakti')->findAll();

      $kondisiValue = '';
      $kondisi = '';
      $whereClause = '';
      for ($i = 0; $i < count($skema); $i++) {
        if ($skema[$i]['kepegawaian'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_kepegawaian'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = $byIdPegawai . '|';
          $kondisi = 'after';
          $kepegawaian = true;
        }
        if ($skema[$i]['atasan'] == $byIdPegawai) {
          $whereClause = 'id_cuti_status_atasan'; // (1)Pending,(2)Disetujui(3)Dikembalikan,(4)Ditolak
          $kondisiValue = '|' . $byIdPegawai;
          $kondisi = 'before';
          $atasan = true;
        }
      }
      // Cek apakah Status Sebelumnya sudah disetujui
      if ($atasan == true) {
        $cek = $this->cutiDataPermohonanModel
          ->where('id_cuti_data_permohonan', $id)
          ->where('id_cuti_status_kepegawaian', 2)
          ->first();
        if (!isset($cek)) {
          session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
          // return redirect()->to('cuti/validation_cuti');
        }
      }

      $data['dataCuti'] = $this->cutiDataPermohonanModel
        ->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_permohonan.' . $whereClause)
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
        ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
        ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
        ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
        ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
        ->like('cuti_data_permohonan.id_skema_titimangsa', $kondisiValue, $kondisi)
        ->where('cuti_data_permohonan.' . $whereClause, 1)
        ->first();

      $getDataPegawai = $this->dataPegawaiModel
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
        ->join('cuti_data_permohonan', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
        ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
        ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
        ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
        ->first();

      if (!isset($getDataPegawai)) {
        session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
        return redirect()->to('cuti/validation_cuti');
      } else {
        $getDataPegawai = $getDataPegawai['unit_kerja'];
      }
      // Pecah dulu id skema titimangsa

      if (isset($data['dataCuti']['id_skema_titimangsa'])) {
        $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
        $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
        $getUnitKerja = $this->unitKerjaModel->join('cuti_data_permohonan', 'cuti_data_permohonan.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_permohonan', $id)->first();

        $data['dataCuti'] += [
          'unit_kerja_pegawai'  => $getDataPegawai,
          'jenis_surat'         => 'surat_sakti',
          'unit_kerja_skema'    => $getUnitKerja['unit_kerja'],
          'lokasi_skema'        => $getUnitKerja['lokasi'],
          'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
          'nama_kepegawaian'    => $getKepegawaian['nama'],
          'nip_kepegawaian'     => $getKepegawaian['nip'],
          'jabatan_atasan'      => $getAtasan['jabatan'],
          'nama_atasan'         => $getAtasan['nama'],
          'nip_atasan'          => $getAtasan['nip'],
        ];

        $buatFormat = date_create($data['dataCuti']['tanggal']);
        $tanggal = date_format($buatFormat, "d/m/Y");
        $ubahKeIndo = $this->tgl_indo($tanggal);
        $data['dataCuti'] += [
          'periode' => $ubahKeIndo,
          'kepegawaian' => $kepegawaian,
          'atasan' => $atasan,
        ];

        $data['linimasa'] = $this->linimasaModel
          ->select('user.nama, linimasa.*')
          ->join('user', 'user.id_user=linimasa.id_user')
          ->where('id_table', $id)
          ->where('table', 'cuti_data_permohonan')
          ->orderBy('created_at', 'DESC')->findAll();

        foreach ($data['linimasa'] as &$item) {
          $item['tanggal'] = $this->tgl_indo2(substr($item['created_at'], 0, 10));
          $item['jam'] = substr($item['created_at'], 11, 5);
        }
      } else {
        session()->setFlashdata('info', 'Ada beberapa halaman yang tidak bisa anda kunjungi!');
        // return redirect()->to('cuti/validation_cuti');
      }
    }
    return view('cuti/cuti_validation_detail_permohonan', $data);
  }

  public function sisa_cuti()
  {
    $data['title'] = "Cuti | Pengaturan Cuti"; // kelola_data_cuti
    $data['activeMenu'] = "pengaturan_cuti";

    return view('cuti/pengaturan_sicuti', $data);
  }
  public function previewBuktiCuti($any)
  {
    $filename = $any;
    // Setelah menyimpan file, Anda dapat mengaksesnya dengan path lengkap
    helper("filesystem");
    $path = WRITEPATH . 'file-bukti-cuti/';

    $fullpath = $path . $filename;
    $file = new \CodeIgniter\Files\File($fullpath, true);
    $binary = readfile($fullpath);
    return $this->response
      ->setHeader('Content-Type', $file->getMimeType())
      ->setHeader('Content-disposition', 'inline; filename="' . $file->getBasename() . '"')
      ->setStatusCode(200)
      ->setBody($binary);
  }
}
