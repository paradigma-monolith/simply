<?php

namespace App\Controllers\Cuti;

use App\Controllers\BaseController;

class Crud extends BaseController
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
    $split = explode('-', $tanggal);
    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun
    return $split[0] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[2];
  }
  public function update_cuti()
  {
    $statusCutiTahunan = true;
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();
    if ($by) {
      $by = $by['id_user'];
    } else {
      session()->setFlashdata('error', 'Maaf anda harus login ulang!');
      return redirect()->to('auth/logout');
    }

    // $rentan_waktu = explode(" - ", $this->request->getVar("rentan_waktu"));
    // $tanggalAwal = date_create($rentan_waktu[0]);
    // $tanggalAkhir = date_create($rentan_waktu[1]);
    // $tanggalAwal = $this->tgl_indo(date_format($tanggalAwal, "d-m-Y"));
    // $tanggalAkhir = $this->tgl_indo(date_format($tanggalAkhir, "d-m-Y"));

    $rentan_waktu = '';
    $tanggalAwal = '';
    $tanggalAkhir = '';
    $totalHari = '';
    $cek = $this->request->getVar("rentan_waktu");
    $jmlHari = 0;

    $dataPegawai = $this->dataPegawaiModel->where('nip', $this->request->getvar("pegawai"))->first();

    if ($dataPegawai) {
      $dataPegawai = $dataPegawai['id_data_pegawai'];
    } else {
      session()->setFlashdata('error', 'Data gagal diproses silahkan pastikan anda mengisi kolom yang tersedia dengan benar!');
      return redirect()->to('cuti/tambah_cuti')->withInput();
    }

    $jenisCuti = $this->request->getVar("jenis_cuti");
    $currentYear = date('Y');
    $cekSisa = $this->cutiDataCutiModel
      ->where('id_data_pegawai', $dataPegawai)  // Filter by specific employee ID
      ->where('YEAR(tanggal_awal)', $currentYear)  // Filter by current year
      ->where('id_cuti_status_kepegawaian !=', 4)  // Exclude status 4 for kepegawaian
      ->where('id_cuti_status_atasan !=', 4)  // Exclude status 4 for atasan
      ->where('id_cuti_status_pejabat !=', 4)  // Exclude status 4 for pejabat
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

    if ($jenisCutiMelahirkan == true && $jenisCuti == 'Cuti Melahirkan') {
      if (!$this->cutiDataCutiModel->where('id_cuti_data_cuti', $this->request->getVar("id"))) {
        session()->setFlashdata('error', 'Data gagal ditambahkan, anda telah mengajukan "' . $jenisCuti . '" pada tahun ' . date("Y") . ' ini! <br/> <span class="text-warning">Sisa "' . $jenisCuti . '" anda sudah habis pada tahun ' . date("Y") . ' ini!</span>');
        return redirect()->to('cuti/update_cuti/' . $this->request->getVar("id"));
      }
    }

    if ($jenisCuti != 'Cuti Melahirkan') {
      for ($i = 0; $i <= count($cek) - 1; $i++) {
        $rentan_waktu = explode(" - ", $this->request->getVar("rentan_waktu")[$i]);
        $start = $rentan_waktu[0];
        $end = $rentan_waktu[1];
        $tanggalAwal = $tanggalAwal . $start . '|';
        $tanggalAkhir = $tanggalAkhir . $end . '|';
        $totalHari = $totalHari . $this->request->getVar("selama")[$i] . '|';
        $jmlHari = $jmlHari + $this->request->getVar("selama")[$i];
      }
      $tanggalAwal = substr($tanggalAwal, 0, -1);
      $tanggalAkhir = substr($tanggalAkhir, 0, -1);
      $totalHari = substr($totalHari, 0, -1);

      $cekData = $this->cutiDataCutiModel->where('id_cuti_data_cuti', $this->request->getVar("id"))->first();

      $totalDataHariSebelumnya = 0;
      if ($cekData['jenis_cuti'] == 'Cuti Tahunan') {
        $cekDataHariSebelumnya = explode("|", $cekData['total_hari']);
        for ($i = 0; $i < count($cekDataHariSebelumnya); $i++) {
          $totalDataHariSebelumnya = $totalDataHariSebelumnya + $cekDataHariSebelumnya[$i];
        }
      }
    } else {
      $tanggal = new \DateTime($this->request->getVar("rentan_waktu")[0]);
      $tanggal->modify('+3 months');
      $tanggalString = $tanggal->format('Y/m/d');

      $tanggalAwal = $cek;
      $tanggalAkhir = $tanggalString;
      $jmlHari = $this->request->getVar("selama");
      $totalHari = $this->request->getVar("selamaCutiMelahirkan");
    }

    $tambahanCuti = $this->cutiSisaCutiModel->join('data_pegawai', 'id_data_pegawai', 'RIGHT')
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->select('data_pegawai.*, COALESCE(cuti_sisa_cuti.sisa_cuti_tahunan, 0) as sisa_cuti_tahunan')
      ->where('id_data_pegawai', $dataPegawai)
      ->first();
    $sisaCuti = 12 + $tambahanCuti['sisa_cuti_tahunan'];
    if ($jenisCuti == "Cuti Tahunan" && ($jmlHari + $totalHariSebelumnya - $totalDataHariSebelumnya) > $sisaCuti) {
      session()->setFlashdata('error', 'Data gagal ditambahkan, anda telah mengajukan ' . $totalHariSebelumnya . ' hari pada "' . $jenisCuti . '"! <br/> Anda menginputkan total pengajuan"' . $jenisCuti . '" selama ' . $jmlHari . ' hari! <br/> Maximal pengambilan "' . $jenisCuti . '" adalah sebanyak 12 hari per tahunnya ditambah sisa cuti dari hasil yang sudah diperhitungkan pada tahun lalu! <br/> <span class="text-warning">Sisa "' . $jenisCuti . '" anda adalah sebanyak ' . ((12 - $totalHariSebelumnya + $totalDataHariSebelumnya) + $tambahanCuti['sisa_cuti_tahunan']) . ' hari pada tahun ' . date("Y") . ' ini!</span> <br/><strong>Cuti tahunan selebihnya akan dipotong dengan dana tunjangan kinerja!</strong>');
      // return redirect()->to('cuti/update_cuti/' . $this->request->getVar("id"));
      // return redirect()->to('cuti/tambah_cuti');
      $statusCutiTahunan = false;
    }

    // for ($i = 0; $i <= count($cek) - 1; $i++) {
    //   $rentan_waktu = explode(" - ", $this->request->getVar("rentan_waktu")[$i]);
    //   $start = $rentan_waktu[0];
    //   $end = $rentan_waktu[1];
    //   $tanggalAwal = $tanggalAwal . $start . '|';
    //   $tanggalAkhir = $tanggalAkhir . $end . '|';
    //   $totalHari = $totalHari . $this->request->getVar("selama")[$i] . '|';
    // }

    // $tanggalAwal = substr($tanggalAwal, 0, -1);
    // $tanggalAkhir = substr($tanggalAkhir, 0, -1);
    // $totalHari = substr($totalHari, 0, -1);

    $id = $this->request->getVar("skema_titimangsa");
    $id = $this->skemaTitimangsaModel->where('id_skema_titimangsa', $id)->first();

    $id = $this->request->getVar("id");
    $getData = $this->cutiDataCutiModel->where('id_cuti_data_cuti', $id)->first();

    $statusKepegawaian = 1;
    $statusAtasan = 1;
    $statusPejabat = 1;

    $dataSebelumnya = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $id)->first();

    $data = [
      'id_cuti_data_cuti'           => $id,
      "id_cuti_status_kepegawaian"  => $statusKepegawaian,
      "id_cuti_status_atasan"       => $statusAtasan,
      "id_cuti_status_pejabat"      => $statusPejabat,
      'jenis_cuti'                  => $this->request->getVar("jenis_cuti"),
      'alasan_cuti'                 => $this->request->getVar("alasan_cuti"),
      'tanggal_awal'                => $tanggalAwal,
      'tanggal_akhir'               => $tanggalAkhir,
      'tanggal_pengajuan'           => $this->request->getVar("tanggal_pengajuan"),
      'total_hari'                  => $totalHari,
      'alamat_cuti'                 => $this->request->getVar("alamat_cuti"),
      'no_telp'                     => $this->request->getVar("no_telp"),
      'updated_by'                  => $by
    ];
    
    if ($jenisCuti == "Cuti Karena Alasan Penting") {
      $data += [
        'osdm_nama' => $this->request->getVar("osdm_nama"),
        'osdm_nip' => $this->request->getVar("osdm_nip"),
      ];
    }
    
    if ($dataSebelumnya['koordinator_tempat'] != $this->request->getVar("koordinator_tempat")) {
      $data += [
        'koordinator_tempat'          => $this->request->getVar("koordinator_tempat"),
      ];

      if ($this->request->getVar("koordinator_tempat") != "0") {
        $data += [
          'koordinator_tempat' => "Pending",
        ];
      }
    }
    if ($this->request->getVar("koordinator_tempat") == "0") {
      $data += [
        'koordinator_tempat' => "Terkirim",
      ];
    }

    if ($this->request->getVar("cekTTD") == true) {
      $data += [
        'ttd_data_pegawai' => $this->request->getVar("signature")
      ];
    }

    if ($this->cutiDataCutiModel->save($data)) {
      $dataPegawai = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $id)->first();
      $linimasa = false;
      $tanpaPerubahan = false;
      $elemen = '';
      $aktivitas = '';
      if ($dataSebelumnya['no_telp'] != $dataPegawai['no_telp']) {
        $elemen .= "<div class='col-12'><strong>Nomor Telepon</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['no_telp'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['no_telp'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['jenis_cuti'] != $dataPegawai['jenis_cuti']) {
        $elemen .= "<div class='col-12'><strong>Jenis Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['jenis_cuti'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['jenis_cuti'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['alasan_cuti'] != $dataPegawai['alasan_cuti']) {
        $elemen .= "<div class='col-12'><strong>Alasan Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['alasan_cuti'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['alasan_cuti'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['total_hari'] != $dataPegawai['total_hari']) {
        $elemen .= "<div class='col-12'><strong>Lamanya Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['total_hari'] . " Hari</div>
          <div class='col-6 border'>" . $dataPegawai['total_hari'] . " Hari</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['tanggal_awal'] != $dataPegawai['tanggal_awal'] || $dataSebelumnya['tanggal_akhir'] != $dataPegawai['tanggal_akhir']) {
        $elemen .= "<div class='col-12'><strong>Perubahan pada tanggal cuti</strong></div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['tanggal_pengajuan'] != $dataPegawai['tanggal_pengajuan']) {
        $elemen .= "<div class='col-12'><strong>Tanggal Pengajuan</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['tanggal_pengajuan'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['tanggal_pengajuan'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['ttd_data_pegawai'] != $dataPegawai['ttd_data_pegawai']) {
        $elemen .= "<div class='col-12'><strong>Tanda Tangan</strong></div>
          <div class='col-6 border text-center'><img src='" . $dataSebelumnya['ttd_data_pegawai'] . "' style='width:50%;'/></div>
          <div class='col-6 border text-center'><img src='" . $dataPegawai['ttd_data_pegawai'] . "' style='width:50%;'/></div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['alamat_cuti'] != $dataPegawai['alamat_cuti']) {
        $elemen .= "<div class='col-12'><strong>Tanda Tangan</strong></div>
          <div class='col-6 border text-center'><img src='" . $dataSebelumnya['alamat_cuti'] . "' style='width:50%;'/></div>
          <div class='col-6 border text-center'><img src='" . $dataPegawai['alamat_cuti'] . "' style='width:50%;'/></div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['koordinator_tempat'] != $this->request->getVar("koordinator_tempat")) {
        $elemen .= "<div class='col-12'><strong>Mengganti Koordinator</strong></div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['id_cuti_status_kepegawaian'] != $dataPegawai['id_cuti_status_kepegawaian']) {
        if ($linimasa == false) {
          $aktivitas = 'tidak mengubah data pada surat yang dikembalikan dengan status sekarang adalah Pending';
          $tanpaPerubahan = true;
        }
      }
      if ($dataSebelumnya['id_cuti_status_atasan'] != $dataPegawai['id_cuti_status_atasan']) {
        if ($linimasa == false) {
          $aktivitas = 'tidak mengubah data pada surat yang dikembalikan dengan status sekarang adalah Pending';
          $tanpaPerubahan = true;
        }
      }
      if ($dataSebelumnya['id_cuti_status_pejabat'] != $dataPegawai['id_cuti_status_pejabat']) {
        if ($linimasa == false) {
          $aktivitas = 'tidak mengubah data pada surat yang dikembalikan dengan status sekarang adalah Pending';
          $tanpaPerubahan = true;
        }
      }
      if ($dataSebelumnya['osdm_nama'] != $dataPegawai['osdm_nama']) {
        $elemen .= "<div class='col-12'><strong>Nama Kepala Biro OSDM</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['osdm_nama'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['osdm_nama'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }
      if ($dataSebelumnya['osdm_nip'] != $dataPegawai['osdm_nip']) {
        $elemen .= "<div class='col-12'><strong>NIP Kepala Biro OSDM</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['osdm_nip'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['osdm_nip'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat cuti';
      }

      if ($linimasa == true) {
        $newId = $id;
        $timeline = [
          'icon'        => 'fas fa-envelope bg-warning',
          'id_table'    => $newId,
          'table'       => 'cuti_data_cuti',
          'id_user'     => $by,
          'aktivitas'   => $aktivitas,
          'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data surat untuk cuti dengan jenis cuti '" . $jenisCuti . "' untuk Pegawai bernama " . $dataPegawai['nama'] . "
        <div class='row pl-4 pr-4 pt-2'>
        " . $elemen . "
        </div>
        ",
        ];
        $this->linimasaModel->save($timeline);

        $dataPegawai = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $id)->first();
        $explode = explode("|", $dataPegawai['id_skema_titimangsa']);
        if ($dataPegawai['koordinator_status'] == 'Pending')
          $explode[0] = $dataPegawai['koordinator_tempat'];
        $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('id_data_pegawai', $explode[0])
          ->first();


        $data = [
          'nama_pengirim'   => $by,
          'nama_penerima'   => $nama_penerima['id_user'],
          'pesan'           =>  "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data surat untuk cuti dengan jenis cuti '" . $jenisCuti . "' untuk Pegawai bernama " . $dataPegawai['nama'] . "
          <div class='row pl-4 pr-4 pt-2'>
          " . $elemen . "
          </div>
          ",
        ];
        $this->notifikasiModel->save($data);
      }

      if ($tanpaPerubahan == true) {
        $newId = $id;
        $timeline = [
          'icon'        => 'fas fa-envelope bg-warning',
          'id_table'    => $newId,
          'table'       => 'cuti_data_cuti',
          'id_user'     => $by,
          'aktivitas'   => $aktivitas,
        ];
        $this->linimasaModel->save($timeline);
      }
      if ($statusCutiTahunan)
        session()->setFlashdata('pesan', 'Data berhasil diubah.');
      return redirect()->to('cuti/data_cuti');
    } else {
      if ($statusCutiTahunan)
        session()->setFlashdata('error', 'Data gagal diubah.');
      return redirect()->to('cuti/tambah_cuti')->withInput();
    }
  }
  public function tambah_cuti()
  {
    $statusCutiTahunan = true;
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();
    if ($by) {
      $by = $by['id_user'];
    } else {
      session()->setFlashdata('error', 'Maaf anda harus login ulang!');
      return redirect()->to('auth/logout');
    }

    $dataUnitKerja = $this->unitKerjaModel->where('unit_kerja', $this->request->getVar("unit_kerja_skema"))->first();

    if ($dataUnitKerja) {
      $dataUnitKerja = $dataUnitKerja['id_unit_kerja'];
    } else {
      session()->setFlashdata('error', 'Data gagal diproses silahkan pastikan anda mengisi kolom yang tersedia dengan benar!');
      return redirect()->to('cuti/tambah_cuti')->withInput();
    }

    $dataPegawai = $this->dataPegawaiModel->where('nip', $this->request->getvar("pegawai"))->first();
    // dd($this->request->getvar("pegawai"));
    // $rentan_waktu = explode(" - ", $this->request->getVar("rentan_waktu"));
    // $tanggalAwal = date_create($rentan_waktu[0]);
    // $tanggalAkhir = date_create($rentan_waktu[1]);
    // $tanggalAwal = $this->tgl_indo(date_format($tanggalAwal, "d-m-Y"));
    // $tanggalAkhir = $this->tgl_indo(date_format($tanggalAkhir, "d-m-Y"));

    $rentan_waktu = '';
    $tanggalAwal = '';
    $tanggalAkhir = '';
    $totalHari = '';
    $cek = $this->request->getVar("rentan_waktu");
    $jmlHari = 0;


    $jenisCuti = $this->request->getVar("jenis_cuti");
    $cekSisa = $this->cutiDataCutiModel
      ->where('id_data_pegawai', $dataPegawai['id_data_pegawai'])
      ->where('YEAR(tanggal_awal)', date('Y'))
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

    if ($jenisCutiMelahirkan == true && $jenisCuti == 'Cuti Melahirkan') {
      session()->setFlashdata('error', 'Data gagal ditambahkan, anda telah mengajukan "' . $jenisCuti . '" pada tahun ' . date("Y") . ' ini! <br/> <span class="text-warning">Sisa "' . $jenisCuti . '" anda sudah habis pada tahun ' . date("Y") . ' ini!</span>');
      return redirect()->to('cuti/tambah_cuti');
    }

    if ($jenisCuti != 'Cuti Melahirkan') {
      for ($i = 0; $i <= count($cek) - 1; $i++) {
        $rentan_waktu = explode(" - ", $this->request->getVar("rentan_waktu")[$i]);
        $start = $rentan_waktu[0];
        $end = $rentan_waktu[1];
        $tanggalAwal = $tanggalAwal . $start . '|';
        $tanggalAkhir = $tanggalAkhir . $end . '|';
        $totalHari = $totalHari . $this->request->getVar("selama")[$i] . '|';
        $jmlHari = $jmlHari + $this->request->getVar("selama")[$i];
      }
      $tanggalAwal = substr($tanggalAwal, 0, -1);
      $tanggalAkhir = substr($tanggalAkhir, 0, -1);
      $totalHari = substr($totalHari, 0, -1);
    } else {
      $tanggal = new \DateTime($this->request->getVar("rentan_waktu")[0]);
      $tanggal->modify('+3 months');
      $tanggalString = $tanggal->format('Y/m/d');

      $tanggalAwal = $cek;
      $tanggalAkhir = $tanggalString;
      $jmlHari = $this->request->getVar("selama");
    }

    $tambahanCuti = $this->cutiSisaCutiModel->join('data_pegawai', 'id_data_pegawai', 'RIGHT')
      ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan')
      ->select('data_pegawai.*, COALESCE(cuti_sisa_cuti.sisa_cuti_tahunan, 0) as sisa_cuti_tahunan')
      ->where('id_data_pegawai', $dataPegawai['id_data_pegawai'])
      ->first();
      
    // Default jatah cuti tahunan adalah 12 hari
    $jatahCutiTahunan = 12;
    
    // Cek status pegawai untuk penanganan khusus PPNPN
    $statusPegawai = $this->dataPegawaiModel->where('id_data_pegawai', $dataPegawai['id_data_pegawai'])
      ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai', 'LEFT')
      ->first();
    if ($statusPegawai && $statusPegawai['status_pegawai'] == 'PPNPN') {
      // Untuk PPNPN, jatah cuti tahunan hanya 9 hari
      $jatahCutiTahunan = 9;
    }
    
    $sisaCuti = $jatahCutiTahunan + $tambahanCuti['sisa_cuti_tahunan'];
    $sisaCutiTersedia = $sisaCuti - $totalHariSebelumnya;
    
    if ($statusPegawai && $statusPegawai['status_pegawai'] == 'PPNPN') {
      // Cek permohonan cuti untuk PPNPN
      // Perbaikan kondisi query untuk permohonan cuti
      $cekPerizinan = $this->cutiDataPermohonanModel
        ->join('data_pegawai', 'id_data_pegawai')
        ->where('data_pegawai.id_data_pegawai', $dataPegawai['id_data_pegawai'])
        ->where('YEAR(tanggal)', date("Y"))
        ->findAll();
      
      // Log untuk debugging jumlah permohonan
      log_message('info', 'CRUD - PPNPN - ID: ' . $dataPegawai['id_data_pegawai'] . ', Jumlah permohonan: ' . count($cekPerizinan));
      
      // Tampilkan detail permohonan untuk debugging
      foreach ($cekPerizinan as $key => $value) {
        log_message('info', 'CRUD - Permohonan #' . $key . ': ' . json_encode($value));
      }
      
      $sisaCutiTersedia = $sisaCutiTersedia - count($cekPerizinan);
    }
    
    if ($jenisCuti == "Cuti Tahunan" && ($jmlHari > $sisaCutiTersedia)) {
      session()->setFlashdata('info', 'PENTING!!, anda telah mengajukan ' . $totalHariSebelumnya . ' hari pada "' . $jenisCuti . '"! <br/> Anda menginputkan total pengajuan"' . $jenisCuti . '" selama ' . $jmlHari . ' hari! <br/> Maximal pengambilan "' . $jenisCuti . '" adalah sebanyak ' . $jatahCutiTahunan . ' hari per tahunnya ditambah sisa cuti dari hasil yang sudah diperhitungkan pada tahun lalu! <br/> <span class="text-warning">Sisa "' . $jenisCuti . '" anda adalah sebanyak ' . $sisaCutiTersedia . ' hari pada tahun ' . date("Y") . ' ini!</span><br/><strong>Cuti tahunan selebihnya akan dipotong dengan dana tunjangan kinerja!</strong>');
      // return redirect()->to('cuti/tambah_cuti');
      $statusCutiTahunan = false;
    }


    $id = $this->request->getVar("skema_titimangsa");
    $id = $this->skemaTitimangsaModel->where('id_skema_titimangsa', $id)->first();
    $id_skema_titimangsa = $id['kepegawaian'] . '|' . $id['atasan'] . '|' . $id['pejabat'];

    $data = [
      "id_data_pegawai"             => $dataPegawai['id_data_pegawai'],
      "id_jabatan"                  => $dataPegawai['id_jabatan'],
      'id_skema_titimangsa'         => $id_skema_titimangsa,
      "id_unit_kerja"               => $dataUnitKerja,
      "id_cuti_status_kepegawaian"  => 1,
      "id_cuti_status_atasan"       => 1,
      "id_cuti_status_pejabat"      => 1,
      'jenis_cuti'                  => $jenisCuti,
      'alasan_cuti'                 => $this->request->getVar("alasan_cuti"),
      'tanggal_awal'                => $tanggalAwal,
      'tanggal_akhir'               => $tanggalAkhir,
      'tanggal_pengajuan'           => $this->request->getVar("tanggal_pengajuan"),
      'total_hari'                  => $totalHari,
      'alamat_cuti'                 => $this->request->getVar("alamat_cuti"),
      'no_telp'                     => $this->request->getVar("no_telp"),
      'ttd_data_pegawai'            => $this->request->getVar("signature"),
      'koordinator_tempat'          => $this->request->getVar("koordinator_tempat"),
      'created_by'                  => $by,
      'updated_by'                  => $by
    ];

    if ($jenisCuti == "Cuti Karena Alasan Penting") {
      $data += [
        'osdm_nama' => $this->request->getVar("osdm_nama"),
        'osdm_nip' => $this->request->getVar("osdm_nip"),
      ];
    }

    if ($jenisCuti == "Cuti Sakit" || $jenisCuti == "Cuti Karena Alasan Penting") {
      // =======================================================================================================================
      $file = $this->request->getFile('bukti_lampiran');

      // Periksa apakah file diunggah dan ukurannya valid
      if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
        // Periksa ukuran file
        $maxFileSize = 1024 * 1024 * 10; // Misalnya, batas ukuran file adalah 10 MB

        if ($file->getSize() <= $maxFileSize) {
          // Ukuran file memenuhi syarat, lanjutkan dengan pemrosesan

          // Ubah nama file menjadi bilangan unik/acak
          $newName = uniqid() . '_' . $file->getRandomName();

          // Simpan file ke direktori '/file-bukti-cuti'
          $file->move(ROOTPATH . 'writable/file-bukti-cuti', $newName);

          // Periksa apakah file telah tersimpan
          if (file_exists(ROOTPATH . 'writable/file-bukti-cuti/' . $newName)) {
            $data += [
              'bukti_lampiran' => $newName
            ];
          }
        } else {
          session()->setFlashdata('error', 'Ukuran file melebihi batas maksimum');
          return redirect()->to('cuti/tambah_cuti');
          // Ukuran file melebihi batas maksimum
          // Tambahkan log atau pesan kesalahan jika diperlukan
          // Misalnya: log_message('error', 'Ukuran file melebihi batas maksimum');
        }
      } else {

        session()->setFlashdata('error', 'File tidak diunggah, tidak valid, atau tidak sesuai ekstensi');
        return redirect()->to('cuti/tambah_cuti');
        // File tidak diunggah, tidak valid, atau tidak sesuai ekstensi
        // Tambahkan log atau pesan kesalahan jika diperlukan
        // Misalnya: log_message('error', 'File tidak diunggah, tidak valid, atau tidak sesuai ekstensi');
      }
      // =======================================================================================================================
    }

    if ($this->request->getVar("koordinator_tempat") == "0") {
      $data += [
        'koordinator_status' => "Terkirim"
      ];
    }
    if ($this->cutiDataCutiModel->save($data)) {

      $newId = $this->cutiDataCutiModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-envelope bg-blue',
        'id_table'    => $newId,
        'table'       => 'cuti_data_cuti',
        'id_user'     => $by,
        'aktivitas'   => 'Melakukan pengajuan surat cuti',
        'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan pengajuan surat untuk cuti dengan jenis cuti '" . $jenisCuti . "' untuk Pegawai bernama " . $dataPegawai['nama'] . "",
      ];
      $this->linimasaModel->save($timeline);

      $dataPegawai = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $newId)->first();
      $explode = explode("|", $dataPegawai['id_skema_titimangsa']);
      if ($dataPegawai['koordinator_status'] == 'Pending')
        $explode[0] = $dataPegawai['koordinator_tempat'];

      $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('id_data_pegawai', $explode[0])
        ->first();

      $data = [
        'nama_pengirim'   => $by,
        'nama_penerima'   => $nama_penerima['id_user'],
        'pesan'           => "<strong>" . session()->get('nama') . "</strong> telah melakukan pengajuan surat untuk cuti dengan jenis cuti '" . $jenisCuti . "' untuk Pegawai bernama " . $dataPegawai['nama'] . "",
      ];
      $this->notifikasiModel->save($data);

      if ($statusCutiTahunan)
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('cuti/data_cuti');
    } else {
      if ($statusCutiTahunan)
        session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('cuti/tambah_cuti')->withInput();
    }
  }
  public function tambah_permohonan()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();
    if ($by) {
      $by = $by['id_user'];
    } else {
      session()->setFlashdata('error', 'Maaf anda harus login ulang!');
      return redirect()->to('auth/logout');
    }

    $dataUnitKerja = $this->unitKerjaModel->where('unit_kerja', $this->request->getVar("unit_kerja_skema"))->first();

    if ($dataUnitKerja) {
      $dataUnitKerja = $dataUnitKerja['id_unit_kerja'];
    } else {
      session()->setFlashdata('error', 'Data gagal diproses silahkan pastikan anda mengisi kolom yang tersedia dengan benar!');
      return redirect()->to('cuti/tambah_permohonan')->withInput();
    }

    $dataPegawai = $this->dataPegawaiModel->where('nip', $this->request->getvar("pegawai"))->first();

    $id = $this->request->getVar("skema_titimangsa");
    $id = $this->skemaTitimangsaModel->where('id_skema_titimangsa', $id)->first();
    $id_skema_titimangsa = $id['kepegawaian'] . '|' . $id['atasan'];

    $data = [
      "id_data_pegawai"             => $dataPegawai['id_data_pegawai'],
      "id_jabatan"                  => $dataPegawai['id_jabatan'],
      "id_golongan"                 => $dataPegawai['id_golongan'],
      'id_skema_titimangsa'         => $id_skema_titimangsa,
      "id_unit_kerja"               => $dataUnitKerja,
      "id_cuti_status_kepegawaian"  => 1,
      "id_cuti_status_atasan"       => 1,
      'jenis_permohonan'            => $this->request->getVar("jenis_permohonan"),
      'alasan_permohonan'           => $this->request->getVar("alasan_permohonan"),
      'tanggal'                     => $this->request->getVar("tanggal"),
      'ttd_data_pegawai'            => $this->request->getVar("signature"),
      'created_by'                  => $by,
      'updated_by'                  => $by
    ];

    $cek = $this->cutiDataPermohonanModel->where('id_data_pegawai', $dataPegawai['id_data_pegawai'])->like('tanggal', date("Y"), 'after')->findAll();

    if ($this->cutiDataPermohonanModel->save($data)) {
      $newId = $this->cutiDataPermohonanModel->getInsertID();
      $timeline = [
        'icon'        => 'fas fa-envelope bg-blue',
        'id_table'    => $newId,
        'table'       => 'cuti_data_permohonan',
        'id_user'     => $by,
        'aktivitas'   => 'Melakukan pengajuan surat sakti',
        'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan pengajuan surat dengan jenis permohonan " . $this->request->getVar("jenis_permohonan"),
      ];
      $this->linimasaModel->save($timeline);

      $explode = explode("|", $id_skema_titimangsa);
      $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('id_data_pegawai', $explode[0])
        ->first();

      $data = [
        'nama_pengirim'   => $by,
        'nama_penerima'   => $nama_penerima['id_user'],
        'pesan'           =>  "<strong>" . session()->get('nama') . "</strong> telah melakukan pengajuan surat dengan jenis permohonan " . $this->request->getVar("jenis_permohonan"),
      ];
      $this->notifikasiModel->save($data);

      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('cuti/data_permohonan');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('cuti/tambah_permohonan')->withInput();
    }
  }
  public function update_permohonan()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();
    if ($by) {
      $by = $by['id_user'];
    } else {
      session()->setFlashdata('error', 'Maaf anda harus login ulang!');
      return redirect()->to('auth/logout');
    }

    $id = $this->request->getVar("skema_titimangsa");
    $id = $this->skemaTitimangsaModel->where('id_skema_titimangsa', $id)->first();

    $id = $this->request->getVar("id_cuti_data_permohonan");
    $getData = $this->cutiDataPermohonanModel->where('id_cuti_data_permohonan', $id)->first();

    $statusKepegawaian = 1;
    $statusAtasan = 1;

    // if ($getData['id_cuti_status_kepegawaian'] == 2) {
    //   $statusKepegawaian = 2;
    // }
    // if ($getData['id_cuti_status_atasan'] == 2) {
    //   $statusAtasan = 2;
    // }

    $dataSebelumnya = $this->cutiDataPermohonanModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai")->where('id_cuti_data_permohonan', $id)->first();
    $data = [
      "id_cuti_data_permohonan"     => $id,
      "id_cuti_status_kepegawaian"  => $statusKepegawaian,
      "id_cuti_status_atasan"       => $statusAtasan,
      'jenis_permohonan'            => $this->request->getVar("jenis_permohonan"),
      'alasan_permohonan'           => $this->request->getVar("alasan_permohonan"),
      'tanggal'                     => $this->request->getVar("tanggal"),
      'updated_by'                  => $by
    ];

    if ($this->request->getVar("cekTTD") == true) {
      $data += [
        'ttd_data_pegawai' => $this->request->getVar("signature")
      ];
    }

    if ($this->cutiDataPermohonanModel->save($data)) {

      // ============================================================================================================================

      $dataPegawai = $this->cutiDataPermohonanModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai")->where('id_cuti_data_permohonan', $id)->first();
      $linimasa = false;
      $tanpaPerubahan = false;
      $elemen = '';
      $aktivitas = '';
      if ($dataSebelumnya['jenis_permohonan'] != $dataPegawai['jenis_permohonan']) {
        $elemen .= "<div class='col-12'><strong>Nomor Telepon</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['jenis_permohonan'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['jenis_permohonan'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat sakti';
      }
      if ($dataSebelumnya['alasan_permohonan'] != $dataPegawai['alasan_permohonan']) {
        $elemen .= "<div class='col-12'><strong>Jenis Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['alasan_permohonan'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['alasan_permohonan'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat sakti';
      }
      if ($dataSebelumnya['tanggal'] != $dataPegawai['tanggal']) {
        $elemen .= "<div class='col-12'><strong>Alasan Cuti</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['tanggal'] . "</div>
          <div class='col-6 border'>" . $dataPegawai['tanggal'] . "</div>";
        $linimasa = true;
        $aktivitas = 'Melakukan perubahan data surat sakti';
      }
      if ($dataSebelumnya['id_cuti_status_kepegawaian'] != $dataPegawai['id_cuti_status_kepegawaian']) {
        if ($linimasa == false) {
          $aktivitas = 'tidak mengubah data pada surat yang dikembalikan dengan status sekarang adalah Pending';
          $tanpaPerubahan = true;
        }
      }
      if ($dataSebelumnya['id_cuti_status_atasan'] != $dataPegawai['id_cuti_status_atasan']) {
        if ($linimasa == false) {
          $aktivitas = 'tidak mengubah data pada surat yang dikembalikan dengan status sekarang adalah Pending';
          $tanpaPerubahan = true;
        }
      }
      if ($linimasa == true) {
        $newId = $id;
        $timeline = [
          'icon'        => 'fas fa-envelope bg-warning',
          'id_table'    => $newId,
          'table'       => 'cuti_data_permohonan',
          'id_user'     => $by,
          'aktivitas'   => $aktivitas,
          'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data surat sakti untuk perizinan dengan jenis permohonan '" . $this->request->getVar("jenis_permohonan") . "' untuk Pegawai bernama " . $dataPegawai['nama'] . "
        <div class='row pl-4 pr-4 pt-2'>
        " . $elemen . "
        </div>
        ",
        ];
        $this->linimasaModel->save($timeline);

        $explode = explode("|", $dataPegawai['id_skema_titimangsa']);
        $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('id_data_pegawai', $explode[0])
          ->first();

        $data = [
          'nama_pengirim'   => $by,
          'nama_penerima'   => $nama_penerima['id_user'],
          'pesan'           =>  "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data surat sakti untuk perizinan dengan jenis permohonan '" . $this->request->getVar("jenis_permohonan") . "' untuk Pegawai bernama " . $dataPegawai['nama'] . "
          <div class='row pl-4 pr-4 pt-2'>
          " . $elemen . "
          </div>
          ",
        ];
        $this->notifikasiModel->save($data);
      }
      if ($tanpaPerubahan == true) {
        $newId = $id;
        $timeline = [
          'icon'        => 'fas fa-envelope bg-warning',
          'id_table'    => $newId,
          'table'       => 'cuti_data_permohonan',
          'id_user'     => $by,
          'aktivitas'   => $aktivitas,
        ];
        $this->linimasaModel->save($timeline);
      }
      // ============================================================================================================================
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('cuti/data_permohonan');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('cuti/tambah_permohonan')->withInput();
    }
  }
  public function validation_detail_cuti_koordinator()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();
    if ($by) {
      $by = $by['id_user'];
    } else {
      session()->setFlashdata('error', 'Maaf anda harus login ulang!');
      return redirect()->to('auth/logout');
    }

    $data = [
      'id_cuti_data_cuti' => $this->request->getVar("id_cuti_data_cuti"),
      'koordinator_status' => "Terkirim"
    ];

    if ($this->cutiDataCutiModel->save($data)) {

      $newId = $this->request->getVar("id_cuti_data_cuti");

      $timeline = [
        'icon'        => "fas fa-user-tie bg-success",
        'id_table'    => $newId,
        'table'       => 'cuti_data_cuti',
        'id_user'     => $by,
        'aktivitas'   => '| Koordinator Tempat Mengetahui Permohonan Pengajuan Surat Cuti <a href="#">dengan data yang ada pada halaman validation cuti </a>.',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
      $dataPegawai = $this->cutiDataCutiModel->where('id_cuti_data_cuti', $this->request->getVar("id_cuti_data_cuti"))->first();
      $explode = explode("|", $dataPegawai['id_skema_titimangsa']);
      $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('id_data_pegawai', $explode[0])
        ->first();

      $data = [
        'nama_pengirim'   => $by,
        'nama_penerima'   => $nama_penerima['id_user'],
        'pesan'           =>  "<strong>" . session()->get('nama') . '</strong> | Koordinator Tempat Mengetahui Permohonan Pengajuan Surat Cuti <a href="#">dengan data yang ada pada halaman validation cuti </a>.'
      ];
      $this->notifikasiModel->save($data);

      session()->setFlashdata('pesan', 'Data berhasil diupdate.');
      return redirect()->to('cuti/validation_cuti');
    } else {
      session()->setFlashdata('error', 'Data gagal diupdate.');
      return redirect()->to('cuti/validation_detail_cuti/' . $data['id_cuti_data_cuti'])->withInput();
    }
  }
  public function validation_detail_cuti()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();
    if ($by) {
      $by = $by['id_user'];
    } else {
      session()->setFlashdata('error', 'Maaf anda harus login ulang!');
      return redirect()->to('auth/logout');
    }

    $data['id_cuti_data_cuti'] = $this->request->getVar("id_cuti_data_cuti");
    $data['id_cuti_status'] = $this->request->getVar("status");
    $data['ket_cuti_validation'] = $this->request->getVar("keterangan");

    $cekRole = $this->request->getVar("updateAs");
    if ($cekRole == 'kepegawaian') {
      $statusAs = 'id_cuti_status_kepegawaian';
      $ketAs = 'ket_cuti_validation_kepegawaian';
      $signature = 'ttd_kepegawaian';
    } elseif ($cekRole == 'atasan') {
      $statusAs = 'id_cuti_status_atasan';
      $ketAs = 'ket_cuti_validation_atasan';
      $signature = 'ttd_atasan';
    } elseif ($cekRole == 'pejabat') {
      $statusAs = 'id_cuti_status_pejabat';
      $ketAs = 'ket_cuti_validation_pejabat';
      $signature = 'ttd_pejabat';
    } else {
      session()->setFlashdata('error', 'Maaf terjadi kesalahan, ambiguous data!');
      return redirect()->to('cuti/validation_cuti');
    }


    $data = [
      'id_cuti_data_cuti' => $this->request->getVar("id_cuti_data_cuti"),
      $statusAs => $this->request->getVar("status"),
      $ketAs => $this->request->getVar("keterangan"),
    ];

    if ($this->request->getVar("status") == "2") {
      if ($signature == 'ttd_pejabat') {
        $data['ttd_pejabat'] = $this->getTTDPejabat();
      } else if ($signature == 'ttd_atasan') {
        $data['ttd_atasan'] = $this->getTTDAdum();
      } else {
        $data[$signature] = $this->request->getVar("signature");
      }
    } else {
      $data[$signature] = $this->getTTDBlank();
    }

    $id_cuti_cata_cuti = $this->request->getVar("id_cuti_data_cuti");
    $cekPPNPN = $this->cutiDataCutiModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
      ->first();
    if ($cekPPNPN) {
      if ($this->request->getVar("status") == "2") {
        if ($signature == 'ttd_atasan') {
          if ($cekPPNPN['status_pegawai'] == "PPNPN") {
            $data += [
              "id_cuti_status_pejabat" => $this->request->getVar("status"),
              "ket_cuti_validation_pejabat" => "Pesan otomatis",
              "ttd_pejabat" => $this->getTTDBlank(),
            ];
          }
        }
      }
    }


    if ($this->cutiDataCutiModel->save($data)) {
      $by = session()->get('username');
      $by = $this->userModel->where('username', $by)->first();
      if ($by) {
        $by = $by['id_user'];
      } else {
        session()->setFlashdata('error', 'Maaf anda harus login ulang!');
        return redirect()->to('auth/logout');
      }

      $newId = $this->request->getVar("id_cuti_data_cuti");
      $icon = '';
      $status = $this->request->getVar("status");
      if ($status == 1) {
        $icon = 'far fa-clock bg-warning';
        $status = 'Pending';
      } else if ($status == 2) {
        $icon = 'fas fa-check bg-success';
        $status = 'Disetujui';
      } else if ($status == 3) {
        $icon = 'fas fa-exchange-alt bg-secondary';
        $status = 'Dikembalikan';
      } else if ($status == 4) {
        $icon = 'fas fa-times bg-red';
        $status = 'Ditolak';
      }
      $dataPegawai = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $newId)->first();

      $timeline = [
        'icon'        => $icon,
        'id_table'    => $newId,
        'table'       => 'cuti_data_cuti',
        'id_user'     => $by,
        'aktivitas'   => 'Verifikasi data surat cuti (' . $status . ')',
        'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan verifikasi surat untuk cuti Pegawai bernama " . $dataPegawai['nama'] . " dengan status <strong>" . $status . "</strong> dengan keterangan <i>" . $this->request->getVar("keterangan") . "</i>",
      ];
      $this->linimasaModel->save($timeline);

      $explode = explode("|", $dataPegawai['id_skema_titimangsa']);

      if ($dataPegawai['id_cuti_status_kepegawaian'] == 3 || $dataPegawai['id_cuti_status_kepegawaian'] == 4) {
        $nama_penerima = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $newId)->first();
      } else if ($dataPegawai['id_cuti_status_kepegawaian'] == 1) {
        $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('id_data_pegawai', $explode[0])
          ->first();
      } else if ($dataPegawai['id_cuti_status_atasan'] == 3 || $dataPegawai['id_cuti_status_atasan'] == 4) {
        $nama_penerima = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $newId)->first();
      } else if ($dataPegawai['id_cuti_status_atasan'] == 1) {
        $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('id_data_pegawai', $explode[1])
          ->first();
      } else if ($dataPegawai['id_cuti_status_pejabat'] == 2 || $dataPegawai['id_cuti_status_pejabat'] == 3 || $dataPegawai['id_cuti_status_pejabat'] == 4) {
        $nama_penerima = $this->cutiDataCutiModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai")->where('id_cuti_data_cuti', $newId)->first();
      } else if ($dataPegawai['id_cuti_status_pejabat'] == 1) {
        $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('id_data_pegawai', $explode[2])
          ->first();
      }

      $data = [
        'nama_pengirim'   => $by,
        'nama_penerima'   => $nama_penerima['id_user'],
        'pesan'           =>  "<strong>" . session()->get('nama') . "</strong> telah melakukan verifikasi surat untuk cuti Pegawai bernama " . $dataPegawai['nama'] . " dengan status <strong>" . $status . "</strong> dengan keterangan <i>" . $this->request->getVar("keterangan") . "</i>",
      ];
      $this->notifikasiModel->save($data);

      session()->setFlashdata('pesan', 'Data berhasil diupdate.');
      return redirect()->to('cuti/validation_cuti');
    } else {
      session()->setFlashdata('error', 'Data gagal diupdate.');
      return redirect()->to('cuti/validation_detail_cuti/' . $data['id_cuti_data_cuti'])->withInput();
    }
  }
  public function validation_detail_permohonan()
  {
    $by = session()->get('username');
    $by = $this->userModel->where('username', $by)->first();
    if ($by) {
      $by = $by['id_user'];
    } else {
      session()->setFlashdata('error', 'Maaf anda harus login ulang!');
      return redirect()->to('auth/logout');
    }

    $cekRole = $this->request->getVar("updateAs");

    if ($cekRole == 'kepegawaian') {
      $statusAs = 'id_cuti_status_kepegawaian';
      $ketAs = 'ket_permohonan_validation_kepegawaian';
      $signature = 'ttd_kepegawaian';
    } elseif ($cekRole == 'atasan') {
      $statusAs = 'id_cuti_status_atasan';
      $ketAs = 'ket_permohonan_validation_atasan';
      $signature = 'ttd_atasan';
    } else {
      session()->setFlashdata('error', 'Maaf terjadi kesalahan, ambiguous data!');
      return redirect()->to('cuti/validation_permohonan');
    }

    $data = [
      'id_cuti_data_permohonan' => $this->request->getVar("id_cuti_data_permohonan"),
      $statusAs => $this->request->getVar("status"),
      $ketAs => $this->request->getVar("keterangan"),
      $signature => $this->request->getVar("signature"),
    ];

    if ($this->request->getVar("status") == "2") {
      if ($signature == 'ttd_atasan') {
        $data['ttd_atasan'] = $this->getTTDAdum();
      } else {
        $data[$signature] = $this->request->getVar("signature");
      }
    } else {
      $data[$signature] = $this->getTTDBlank();
    }

    if ($this->cutiDataPermohonanModel->save($data)) {

      $newId = $this->request->getVar("id_cuti_data_permohonan");
      $icon = '';
      $status = $this->request->getVar("status");
      if ($status == 1) {
        $icon = 'far fa-clock bg-warning';
        $status = 'Pending';
      } else if ($status == 2) {
        $icon = 'fas fa-check bg-success';
        $status = 'Disetujui';
      } else if ($status == 3) {
        $icon = 'fas fa-exchange-alt bg-secondary';
        $status = 'Dikembalikan';
      } else if ($status == 4) {
        $icon = 'fas fa-times bg-red';
        $status = 'Ditolak';
      }
      $dataPegawai = $this->cutiDataPermohonanModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai")->where('id_cuti_data_permohonan', $newId)->first();

      $timeline = [
        'icon'        => $icon,
        'id_table'    => $newId,
        'table'       => 'cuti_data_permohonan',
        'id_user'     => $by,
        'aktivitas'   => 'Verifikasi data surat sakti (' . $status . ')',
        'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan verifikasi surat untuk perizinan Pegawai bernama " . $dataPegawai['nama'] . " dengan status <strong>" . $status . "</strong> dengan keterangan <i>" . $this->request->getVar("keterangan") . "</i>",
      ];
      $this->linimasaModel->save($timeline);

      $explode = explode("|", $dataPegawai['id_skema_titimangsa']);
      if ($dataPegawai['id_cuti_status_kepegawaian'] == 3 || $dataPegawai['id_cuti_status_kepegawaian'] == 4) {
        $nama_penerima =  $this->cutiDataPermohonanModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai")->where('id_cuti_data_permohonan', $newId)->first();
      } else if ($dataPegawai['id_cuti_status_kepegawaian'] == 1) {
        $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('id_data_pegawai', $explode[0])
          ->first();
      } else if ($dataPegawai['id_cuti_status_atasan'] == 2 || $dataPegawai['id_cuti_status_atasan'] == 3 || $dataPegawai['id_cuti_status_atasan'] == 4) {
        $nama_penerima =  $this->cutiDataPermohonanModel->join("data_pegawai", "data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai")->where('id_cuti_data_permohonan', $newId)->first();
      } else if ($dataPegawai['id_cuti_status_atasan'] == 1) {
        $nama_penerima = $this->dataPegawaiModel->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('id_data_pegawai', $explode[1])
          ->first();
      }

      $data = [
        'nama_pengirim'   => $by,
        'nama_penerima'   => $nama_penerima['id_user'],
        'pesan'           =>  "<strong>" . session()->get('nama') . "</strong> telah melakukan verifikasi surat untuk perizinan Pegawai bernama " . $dataPegawai['nama'] . " dengan status <strong>" . $status . "</strong> dengan keterangan <i>" . $this->request->getVar("keterangan") . "</i>",
      ];
      $this->notifikasiModel->save($data);

      session()->setFlashdata('pesan', 'Data berhasil diupdate.');
      return redirect()->to('cuti/validation_permohonan');
    } else {
      session()->setFlashdata('error', 'Data gagal diupdate.');
      return redirect()->to('cuti/validation_detail_permohonan/' . $data['id_cuti_data_permohonan'])->withInput();
    }
  }
  public function hapus_cuti($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel->where('username', session()->get('username'))->first();
      $cekDataMaster = $this->cutiDataCutiModel->where('id_cuti_data_cuti', $id)->first();
      if ($this->cutiDataCutiModel->delete($id)) {
        $timeline = [
          'icon'        => 'fas fa-trash-alt bg-danger',
          'id_table'    => $cekDataMaster['id_cuti_data_cuti'],
          'table'       => 'cuti_data_cuti',
          'id_user'     => $by['id_user'],
          'aktivitas'   => 'menghapus data pengajuan permohonan surat cuti dengan jenis keterangan sebelumnya  <i>' . $cekDataMaster['jenis_cuti'] . '</i>',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      } else {
        session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
      }
    } else {
      exit('Maaf tidak bisa diproses!');
    }
  }
  public function hapus_permohonan($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel->where('username', session()->get('username'))->first();
      $cekDataMaster = $this->cutiDataPermohonanModel->where('id_cuti_data_permohonan', $id)->first();
      if ($this->cutiDataPermohonanModel->delete($id)) {

        $timeline = [
          'icon'        => 'fas fa-trash-alt bg-danger',
          'id_table'    => $cekDataMaster['id_cuti_data_permohonan'],
          'table'       => 'cuti_data_permohonan',
          'id_user'     => $by['id_user'],
          'aktivitas'   => 'menghapus data pengajuan permohonan surat sakti dengan jenis keterangan sebelumnya  <i>' . $cekDataMaster['jenis_permohonan'] . '</i>',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
      } else {
        session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
      }
    } else {
      exit('Maaf tidak bisa diproses!');
    }
  }

  private function sendToMail($toEmail, $subjek, $pesan)
  {
    $by = $this->userModel->select('id_user')->where('username', session()->get('username'))->first();
    $penerima = 0;
    $user = $this->userModel->where("email", $toEmail)->first(); // Mengambil data pengguna dengan alamat email yang sesuai
    if ($user) {
      $penerima = $user['id_user'];
    }

    $subjek = $subjek;
    $isi = $pesan;
    $data = [
      'pengirim' => $by['id_user'],
      'penerima' => $penerima,
      'email_tujuan' => $toEmail,
      'subjek' => $subjek,
      'isi' => $isi,
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
    ];
    if ($this->emailDataModel->save($data)) {
      $newId = $this->emailDataModel->getInsertID();
      if (null !== $this->request->getVar("id"))
        for ($i = 0; $i < count($this->request->getVar("id")); $i++) {
          $data = [
            'id_email_lampiran' => $this->request->getVar("id")[$i],
            'id_email_data' => $newId,
            'status' => 'Terkirim'
          ];
          $this->emailLampiranModel->save($data);
        }
      // Lakukan proses pengiriman ke email disini
      $email = service('email');
      $email->setTo($toEmail);
      $email->setFrom('kkpbandung@gmail.com', 'KKP Bandung | Simply Aplikasi - SICUTE');

      $email->setSubject($subjek);
      $email->setMessage($isi);
      $email->send();
    }
    return;
  }

  public function sisa_cuti()
  {
    if ($this->request->isAJAX()) {
      $id_data_pegawai = $this->request->getVar("id_data_pegawai");
      $data = [];
      $sisa = $this->request->getVar("sisa_cuti_tahunan");
      if ($sisa < 0) {
        $msg = ['data' => 'error'];
      } else {
        if ($id_data_pegawai) {
          $by = $this->userModel->where('username', session()->get('username'))->first();
          $cek = $this->cutiSisaCutiModel->where('id_data_pegawai', $id_data_pegawai)->first();

          if ($cek) {
            $data += [
              'id_cuti_sisa_cuti' => $cek['id_cuti_sisa_cuti']
            ];
          } else {
            $data += [
              'created_by' => $by['id_user']
            ];
          }

          $data += [
            'id_data_pegawai'   => $this->request->getVar("id_data_pegawai"),
            'sisa_cuti_tahunan' => $this->request->getVar("sisa_cuti_tahunan"),
            'updated_by'        => $by['id_user']
          ];

          if ($this->cutiSisaCutiModel->save($data)) {
            $newId = $this->cutiSisaCutiModel->getInsertID();
            $timeline = [
              'icon'        => 'fas fa-edit bg-warning',
              'id_table'    => $newId,
              'table'       => 'cuti_sisa_cuti',
              'id_user'     => $by['id_user'],
              'aktivitas'   => "memperbaharui data sisa cuti tahun lalu pegawai bernama <strong>" . name_capitalize($this->request->getVar("nama")) . "</strong> dengan sisa = " . $this->request->getVar("sisa_cuti_tahunan") . " hari kerja",
            ];
            $this->linimasaModel->save($timeline);
            $msg = ['data' => 'sukses'];
          } else {
            $msg = ['data' => 'error'];
          }
        }
      }
      echo json_encode($msg);
    }
  }
  private function getTTDPejabat()
  {
    return "data:image/png;base64,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";
  }
  private function getTTDAdum()
  {
    return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACWCAIAAADrOSKFAAAACXBIWXMAAAsTAAALEwEAmpwYAAAGbWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDIgNzkuMTYwOTI0LCAyMDE3LzA3LzEzLTAxOjA2OjM5ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyNC0wMS0xOFQwOTowOTo1OCswNzowMCIgeG1wOk1vZGlmeURhdGU9IjIwMjQtMDEtMThUMDk6MjE6MjkrMDc6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjQtMDEtMThUMDk6MjE6MjkrMDc6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OWE5ZmFmODYtYjBhNS1mYTQ1LTg4YzYtY2FkZGJkMDMxNTEwIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOmRlOWZkMGIyLThlNTItMzQ0OC04NTlhLTZlNzU0NzhiMzIyZiIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOmRlOWZkMGIyLThlNTItMzQ0OC04NTlhLTZlNzU0NzhiMzIyZiI+IDxwaG90b3Nob3A6RG9jdW1lbnRBbmNlc3RvcnM+IDxyZGY6QmFnPiA8cmRmOmxpPnhtcC5kaWQ6N2I1YjY5ZDgtMzhlMy03YjQzLThjMWQtY2YwNWU0YjMyNzA0PC9yZGY6bGk+IDwvcmRmOkJhZz4gPC9waG90b3Nob3A6RG9jdW1lbnRBbmNlc3RvcnM+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ZGU5ZmQwYjItOGU1Mi0zNDQ4LTg1OWEtNmU3NTQ3OGIzMjJmIiBzdEV2dDp3aGVuPSIyMDI0LTAxLTE4VDA5OjA5OjU4KzA3OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo5YTlmYWY4Ni1iMGE1LWZhNDUtODhjNi1jYWRkYmQwMzE1MTAiIHN0RXZ0OndoZW49IjIwMjQtMDEtMThUMDk6MjE6MjkrMDc6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+y+fMlgAAKXhJREFUeJztnXd8VFXax59zbplekgxJIJ0aQLoiiChYcEWxYkEFsa0oinVFFFlldS3r6qJreW1rV7CuiKirgiIiTUAJkIT03qbXO/ec8/6RGJMQ0pgk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";
  }
  private function getTTDBlank()
  {
    return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIcAAACcCAYAAABV9rhSAAAABGdBTUEAALGPC/xhBQAACklpQ0NQc1JHQiBJRUM2MTk2Ni0yLjEAAEiJnVN3WJP3Fj7f92UPVkLY8LGXbIEAIiOsCMgQWaIQkgBhhBASQMWFiApWFBURnEhVxILVCkidiOKgKLhnQYqIWotVXDjuH9yntX167+3t+9f7vOec5/zOec8PgBESJpHmomoAOVKFPDrYH49PSMTJvYACFUjgBCAQ5svCZwXFAADwA3l4fnSwP/wBr28AAgBw1S4kEsfh/4O6UCZXACCRAOAiEucLAZBSAMguVMgUAMgYALBTs2QKAJQAAGx5fEIiAKoNAOz0ST4FANipk9wXANiiHKkIAI0BAJkoRyQCQLsAYFWBUiwCwMIAoKxAIi4EwK4BgFm2MkcCgL0FAHaOWJAPQGAAgJlCLMwAIDgCAEMeE80DIEwDoDDSv+CpX3CFuEgBAMDLlc2XS9IzFLiV0Bp38vDg4iHiwmyxQmEXKRBmCeQinJebIxNI5wNMzgwAABr50cH+OD+Q5+bk4eZm52zv9MWi/mvwbyI+IfHf/ryMAgQAEE7P79pf5eXWA3DHAbB1v2upWwDaVgBo3/ldM9sJoFoK0Hr5i3k4/EAenqFQyDwdHAoLC+0lYqG9MOOLPv8z4W/gi372/EAe/tt68ABxmkCZrcCjg/1xYW52rlKO58sEQjFu9+cj/seFf/2OKdHiNLFcLBWK8ViJuFAiTcd5uVKRRCHJleIS6X8y8R+W/QmTdw0ArIZPwE62B7XLbMB+7gECiw5Y0nYAQH7zLYwaC5EAEGc0Mnn3AACTv/mPQCsBAM2XpOMAALzoGFyolBdMxggAAESggSqwQQcMwRSswA6cwR28wBcCYQZEQAwkwDwQQgbkgBwKoRiWQRlUwDrYBLWwAxqgEZrhELTBMTgN5+ASXIHrcBcGYBiewhi8hgkEQcgIE2EhOogRYo7YIs4IF5mOBCJhSDSSgKQg6YgUUSLFyHKkAqlCapFdSCPyLXIUOY1cQPqQ28ggMor8irxHMZSBslED1AJ1QLmoHxqKxqBz0XQ0D12AlqJr0Rq0Hj2AtqKn0UvodXQAfYqOY4DRMQ5mjNlhXIyHRWCJWBomxxZj5Vg1Vo81Yx1YN3YVG8CeYe8IJAKLgBPsCF6EEMJsgpCQR1hMWEOoJewjtBK6CFcJg4Qxwicik6hPtCV6EvnEeGI6sZBYRqwm7iEeIZ4lXicOE1+TSCQOyZLkTgohJZAySQtJa0jbSC2kU6Q+0hBpnEwm65Btyd7kCLKArCCXkbeQD5BPkvvJw+S3FDrFiOJMCaIkUqSUEko1ZT/lBKWfMkKZoKpRzame1AiqiDqfWkltoHZQL1OHqRM0dZolzZsWQ8ukLaPV0JppZ2n3aC/pdLoJ3YMeRZfQl9Jr6Afp5+mD9HcMDYYNg8dIYigZaxl7GacYtxkvmUymBdOXmchUMNcyG5lnmA+Yb1VYKvYqfBWRyhKVOpVWlX6V56pUVXNVP9V5qgtUq1UPq15WfaZGVbNQ46kJ1Bar1akdVbupNq7OUndSj1DPUV+jvl/9gvpjDbKGhUaghkijVGO3xhmNIRbGMmXxWELWclYD6yxrmE1iW7L57Ex2Bfsbdi97TFNDc6pmrGaRZp3mcc0BDsax4PA52ZxKziHODc57LQMtPy2x1mqtZq1+rTfaetq+2mLtcu0W7eva73VwnUCdLJ31Om0693UJuja6UbqFutt1z+o+02PreekJ9cr1Dund0Uf1bfSj9Rfq79bv0R83MDQINpAZbDE4Y/DMkGPoa5hpuNHwhOGoEctoupHEaKPRSaMnuCbuh2fjNXgXPmasbxxirDTeZdxrPGFiaTLbpMSkxeS+Kc2Ua5pmutG003TMzMgs3KzYrMnsjjnVnGueYb7ZvNv8jYWlRZzFSos2i8eW2pZ8ywWWTZb3rJhWPlZ5VvVW16xJ1lzrLOtt1ldsUBtXmwybOpvLtqitm63Edptt3xTiFI8p0in1U27aMez87ArsmuwG7Tn2YfYl9m32zx3MHBId1jt0O3xydHXMdmxwvOuk4TTDqcSpw+lXZxtnoXOd8zUXpkuQyxKXdpcXU22niqdun3rLleUa7rrStdP1o5u7m9yt2W3U3cw9xX2r+00umxvJXcM970H08PdY4nHM452nm6fC85DnL152Xlle+70eT7OcJp7WMG3I28Rb4L3Le2A6Pj1l+s7pAz7GPgKfep+Hvqa+It89viN+1n6Zfgf8nvs7+sv9j/i/4XnyFvFOBWABwQHlAb2BGoGzA2sDHwSZBKUHNQWNBbsGLww+FUIMCQ1ZH3KTb8AX8hv5YzPcZyya0RXKCJ0VWhv6MMwmTB7WEY6GzwjfEH5vpvlM6cy2CIjgR2yIuB9pGZkX+X0UKSoyqi7qUbRTdHF09yzWrORZ+2e9jvGPqYy5O9tqtnJ2Z6xqbFJsY+ybuIC4qriBeIf4RfGXEnQTJAntieTE2MQ9ieNzAudsmjOc5JpUlnRjruXcorkX5unOy553PFk1WZB8OIWYEpeyP+WDIEJQLxhP5aduTR0T8oSbhU9FvqKNolGxt7hKPJLmnVaV9jjdO31D+miGT0Z1xjMJT1IreZEZkrkj801WRNberM/ZcdktOZSclJyjUg1plrQr1zC3KLdPZisrkw3keeZtyhuTh8r35CP5c/PbFWyFTNGjtFKuUA4WTC+oK3hbGFt4uEi9SFrUM99m/ur5IwuCFny9kLBQuLCz2Lh4WfHgIr9FuxYji1MXdy4xXVK6ZHhp8NJ9y2jLspb9UOJYUlXyannc8o5Sg9KlpUMrglc0lamUycturvRauWMVYZVkVe9ql9VbVn8qF5VfrHCsqK74sEa45uJXTl/VfPV5bdra3kq3yu3rSOuk626s91m/r0q9akHV0IbwDa0b8Y3lG19tSt50oXpq9Y7NtM3KzQM1YTXtW8y2rNvyoTaj9nqdf13LVv2tq7e+2Sba1r/dd3vzDoMdFTve75TsvLUreFdrvUV99W7S7oLdjxpiG7q/5n7duEd3T8Wej3ulewf2Re/ranRvbNyvv7+yCW1SNo0eSDpw5ZuAb9qb7Zp3tXBaKg7CQeXBJ9+mfHvjUOihzsPcw83fmX+39QjrSHkr0jq/dawto22gPaG97+iMo50dXh1Hvrf/fu8x42N1xzWPV56gnSg98fnkgpPjp2Snnp1OPz3Umdx590z8mWtdUV29Z0PPnj8XdO5Mt1/3yfPe549d8Lxw9CL3Ytslt0utPa49R35w/eFIr1tv62X3y+1XPK509E3rO9Hv03/6asDVc9f41y5dn3m978bsG7duJt0cuCW69fh29u0XdwruTNxdeo94r/y+2v3qB/oP6n+0/rFlwG3g+GDAYM/DWQ/vDgmHnv6U/9OH4dJHzEfVI0YjjY+dHx8bDRq98mTOk+GnsqcTz8p+Vv9563Or59/94vtLz1j82PAL+YvPv655qfNy76uprzrHI8cfvM55PfGm/K3O233vuO+638e9H5ko/ED+UPPR+mPHp9BP9z7nfP78L/eE8/stRzjPAAAAIGNIUk0AAHomAACAhAAA+gAAAIDoAAB1MAAA6mAAADqYAAAXcJy6UTwAAAAJcEhZcwAACxMAAAsTAQCanBgAAHKrSURBVHic7X13mFXV1f679jm33zu9d2YYYGgDDChIVUHB3htqbKkao4mfJSb5UowtiSZq7DFqbNhRioAiAoL03mFgmF7u1NvPOXv9/rj3XGaGAcEW8vuynmc/M/feU3ZZe+2113rX2rjzzjuVTz75RLz33nvinnvuUVJSUkR2drZgZupd2traKDExUUybNk0xv+vs7DzsukgkQgDo6quvVjRNo5dffllRFEXceuutitVqFbfeeqtiGEb8+qamJsrJyRG///3vFWamjo4OKisrU2688Ual97N/8IMfKDk5OYKZafbs2QoA8f777/e47m9/+5titVrF2rVrxaZNm0RSUpL4wQ9+oFRWVoo9e/aIUChEzzzzjJKfny/uvvtuhZnpxRdfVFwul5gzZ06fbe+rBINBuvfee5Xs7Gzx61//WjEMgyKRCN15552Ky+WimpoaYmZqbm4mZqbGxkZyOp302GOPHdauLVu20C233KK4XC768MMP43WQUsav+fvf/y6uvvpqUVZWJiZMmCDeeeed7nXFN13UDz/8kOfMmQMiQkFBAaZMmUKrV69m9EHMjPT0dLS3t6O1tRUpKSnweDyHXef3+wGALBYLVFWFw+GAYRjYvHkzbDYbdu3ahUgkArvdjmAwCKvVCmaGz+cjAGhra6OamhrOzMwkAGhvb4fT6YTVakVaWhq8Xi/M7+12O8aMGcMAoGkaLBYLpkyZwoZh4MCBA9TR0YH29nb+7LPPeMmSJWhuboYQgtLT09nr9SIYDEJKiY6ODjgcDpSXl/fZ9t60f/9+PProo+L999/nSy+9lO68804phIAQAi6XC4ZhoKOjg3JzczkpKQkA0NHRASEEmHu+YvPmzbjjjjuopaWFH3jgATrjjDMYAKSUEELEr7viiiv4pptuwvr16/G73/2O/vjHP6K8vBwlJSUAQACOqe7HSuq8efN4//79RERISEjgefPmUXt7e58vSk5OxplnnkmzZs3ilStX0tlnn91nZV588UVhs9m4vLwcABAOhwEAZ599Nq677jr62c9+xg899JD4zW9+Ix0OBzRNQzgcRjAYBAAUFhayx+Oh2tpaAgCzcwEgFApRcnJy/L1JSUlwOp0M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LrgAGRkZ3F2HWLRoEc2fPx/d2ocBAwb0eNbnn39Or7/+OlpbW1FYWIgzzjgDp556KgspJQKBAO69915cfPHFZr5OtLW14Y477qCnnnoKbW1t7HK5eMmSJRg2bBjV1NQA6LlV+/DDD+mJJ57A/PnzD2vo1q1bce+994qlS5dCVVVEIhH885//xE033STq6+sPYwwAePXVV8Xzzz8vN27c2OP7N954g8rLy+OGm+rqar7nnnvwk5/8RJgWyq6uLtx1113in//8Z9zz+Nprr+EXv/iFaGxsjD/rT3/6E911113Ytm0bu91urFmzhseNG4fVq1fHV7SnnnqKLr/8clq5ciULIeD3+3HvvffiN7/5DbW1tfVgjKqqKtxzzz20cOHCuHcXiHpza2trccstt9CLL76I1tZW5OfnY+XKlZg2bRoeffTRwyT4ggUL6I477qCPPvqIu/uwgKjp/+6776YXXngBgUAAkUgEr776Ku644w5qamqKW1b//Oc/0y9/+Uvs3LkTiYmJWL9+PcaPH9+jfXfddRd9//vfR2VlJVwuF/bu3YtbbrkFDz/8MEFKSV6vl37yk58Ih8NBmzZtImamNWvW0MknnyzuuusuUV9fT8xM8+fPFwUFBeL3v/+9MI1Kra2t9PDDD4sZM2YIAJSVlUXMTIZhxLdo999/vzJmzBjxyiuviGAwSI2NjXTvvfcqhYWFYt68eYdtCd955x1xzjnnKADommuuiSPOWlpaaPDgweKMM84QTU1NcaPcPffco+Tm5opPPvlEMDPNmTNHDBgwQPzlL38RkUiEpJT0t7/9TeTm5ornnntOMDNVVlbSlClTxPXXX6+Y7duxYweVl5eLyy67TPj9fvL5fDRu3DgxadIk0dDQQMxMjY2N9MMf/lAUFxeL999/P173jz/+WNx2220iPT2dAJBpODPLq6++KgoLC+nWW28VnZ2dxMyoqamhyZMni9NPP13U1dXFt6+vvPJKvD8BmIbH+BI/f/58GjhwIP35z38WwWAQuq7jr3/9K+Xn59M//vEPYmbE2kc33HAD1dfXg5mxc+dOKi8vp3POOYeYGV6vF+PGjaNJkybR3r17wcyora3FuHHjqKKiggQRISUlBffccw8nJSXhlVdeIQCYP38+1dfX83nnncemoWzatGl88skn4+WXX477EV555RX64IMPkJOTQ+PGjaPs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";
  }
}
