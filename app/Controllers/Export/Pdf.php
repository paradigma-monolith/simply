<?php

namespace App\Controllers\Export;

use App\Controllers\BaseController;

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf extends BaseController
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

  public function layanan_kesehatan_kapal_pdf($id)
  {
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isFontSubsettingEnabled', true);
    
    $dompdf = new Dompdf($options);
    $layananModel = new \App\Models\PengawasanKapal\LayananKesehatanKapalModel();

    $dataLayanan = $layananModel->find($id);

    if (!$dataLayanan) {
        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }

    $data['dataLayanan'] = $dataLayanan;
    $data['kop'] = generate_base64('dist/img/Kop_BKK_bandung.png');

    // Generate checkbox images
    $imagePath = 'karakter/ceklis.png';
    if (!file_exists($imagePath)) {
        $imagePath = 'karakter/ceklis-line-white.png';
    }
    $imageData = file_get_contents($imagePath);
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    $base64Image = base64_encode($imageData);
    $data['checked'] = '<div style="width: 13px; height: 13px; border: 1px solid #000; display: inline-block; vertical-align: middle; text-align: center; line-height: 13px;"><img style="width: 10px; margin-top: 1px;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" /></div>';
    
    // Empty checkbox placeholder
    $data['unchecked'] = '<div style="width: 13px; height: 13px; border: 1px solid #000; display: inline-block; vertical-align: middle;"></div>';

    $dompdf->loadHtml(view('pengawasan_kapal/layanan_kesehatan_kapal_pdf', $data));
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filename = 'Permohonan_Layanan_Kesehatan_Kapal_' . str_replace(' ', '_', $dataLayanan['nama_kapal']) . '_' . date('Ymd_His', strtotime($dataLayanan['created_at'])) . '.pdf';
    $dompdf->stream($filename, array("Attachment" => true)); // Change to true to force download instead of preview
  }
  public function rekamAbsenDL()
  {
    $tanggalInput = $this->request->getVar("tanggal");
    $filterLokasi = $this->request->getVar("filter_lokasi");
    if (empty($tanggalInput)) {
        $tanggalInput = date('Y-m-d') . " - " . date('Y-m-d');
    }
    $tanggal = explode(" - ", $tanggalInput);
    $tglAwal = $tanggal[0];
    $tglAkhir = isset($tanggal[1]) ? $tanggal[1] : $tanggal[0];

    $builder = $this->absenModel
      ->select('id_absen, absen.id_data_pegawai, status_masuk, status_pulang, alamat_masuk, alamat_pulang, jam_masuk, jam_pulang, ttd_masuk, ttd_pulang, nip, nama')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai')
      ->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan')
      ->where('DATE(jam_masuk) >=', $tglAwal)
      ->where('DATE(jam_masuk) <=', $tglAkhir)
      ->groupStart()
        ->where('status_masuk', 'DL')
        ->orWhere('status_pulang', 'DL')
      ->groupEnd();

    if (!empty($filterLokasi)) {
        $keywords = array_filter(array_map('trim', explode(',', $filterLokasi)));
        if (!empty($keywords)) {
            $builder->groupStart();
            $first = true;
            foreach ($keywords as $kw) {
                if ($first) {
                    $builder->groupStart();
                    $first = false;
                } else {
                    $builder->orGroupStart();
                }
                $builder->like('alamat_masuk', $kw)
                        ->orLike('alamat_pulang', $kw)
                        ->groupEnd();
            }
            $builder->groupEnd();
        }
    }

    $getAbsen = $builder->orderBy('DATE(jam_masuk), golongan.urutan', 'desc')->findAll();

    helper('tanggal');

    $data = [
        'absen' => $getAbsen,
        'tglAwal' => $tglAwal,
        'tglAkhir' => $tglAkhir
    ];

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isFontSubsettingEnabled', true);
    $options->set('chroot', realpath(FCPATH));
    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml(view('absen/pdf_rekam_absen_dl', $data));
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $filename = 'Rekam_Absen_Dinas_Luar_' . $tglAwal . '_sd_' . $tglAkhir . '.pdf';
    ob_end_clean(); // Menghapus buffer
    $dompdf->stream($filename, array("Attachment" => true));
  }

  function tgl_indonesia($tanggal)
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
  function bulan($tanggal)
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
    return $bulan[(int)$split[1]];
  }
  public function hariIndo($hariInggris)
  {
    switch ($hariInggris) {
      case 'Sunday':
        return 'Minggu';
      case 'Monday':
        return 'Senin';
      case 'Tuesday':
        return 'Selasa';
      case 'Wednesday':
        return 'Rabu';
      case 'Thursday':
        return 'Kamis';
      case 'Friday':
        return 'Jumat';
      case 'Saturday':
        return 'Sabtu';
      default:
        return 'hari tidak valid';
    }
  }
  public function cuti($id = null)
  {
    if($id == null) {
        $data['alasan_penting'] = true;
        $id = $this->request->getVar("id");
        $data['alasan_penting_jabatan_pejabat'] = $this->request->getVar("jabatan");
        $data['alasan_penting_nama_pejabat'] = $this->request->getVar("nama");
        $data['alasan_penting_nip_pejabat'] = $this->request->getVar("nip");
    } else {
        $data['alasan_penting'] = false;
        $data['alasan_penting_jabatan_pejabat'] = '';
        $data['alasan_penting_nama_pejabat'] = '';
        $data['alasan_penting_nip_pejabat'] = '';
    }
    $data['dataCuti'] = $this->cutiDataCutiModel
      ->select("*,data_pegawai.nama, cuti_data_cuti.no_telp, status_pegawai.status_pegawai")
      // ->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_cuti.id_cuti_status')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
      ->join('jabatan', 'cuti_data_cuti.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->join('status_pegawai', 'data_pegawai.id_status_pegawai=status_pegawai.id_status_pegawai')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first();

    $getDataPegawai = $this->dataPegawaiModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('cuti_data_cuti', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('jabatan', 'cuti_data_cuti.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first();

    $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
    $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getPejabat = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[2])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getUnitKerja = $this->unitKerjaModel->join('cuti_data_cuti', 'cuti_data_cuti.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_cuti', $id)->first();
    $getUnitKerjaAtas = $unit_kerja_skema = strtolower($getUnitKerja['unit_kerja']);

    $data['dataCuti'] += [
      'nama_pegawai'        => $getDataPegawai['nama'],
      'jabatan_pegawai'     => $getDataPegawai['jabatan'],
      'masa_kerja_pegawai'  => $getDataPegawai['masa_kerja'],
      'nip_pegawai'         => $getDataPegawai['nip'],
      'unit_kerja_pegawai'  => $getDataPegawai['unit_kerja'],
      'jenis_surat'         => 'cuti',
      'unit_kerja_skema'    => ucwords($getUnitKerjaAtas),
      'lokasi_skema'        => $getUnitKerja['lokasi'],
      'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
      'nama_kepegawaian'    => $getKepegawaian['nama'],
      'nip_kepegawaian'     => $getKepegawaian['nip'],
      'jabatan_pejabat'     => $getPejabat['jabatan'],
      'nama_pejabat'        => $getPejabat['nama'],
      'nip_pejabat'         => $getPejabat['nip'],
      'ttd_pejabatz'        => $getPejabat['ttd'],
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
    $dates = date_create($data['dataCuti']['tanggal_pengajuan']);
    $day = $this->hariIndo(date_format($dates, "l"));

    $dates = $this->tgl_indo(date_format($dates, "d/m/Y"));

    $data['dataCuti'] += ['tgl_diajukan' => $dates];
    
    // dd($data['dataCuti']);
    // mendapatkan path gambar
    $imagePath = 'karakter/ceklis.svg';
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data['dataCuti'] += ['ceklis' => '<img style="width:16px;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];


    // // mendapatkan path gambar
    // $imagePath = 'dist/img/ttd/' . $data['dataCuti']['ttd_atasanz'];
    // // membaca file gambar
    // $imageData = file_get_contents($imagePath);
    // // mendapatkan extension dari nama file gambar
    // $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // // encode gambar menjadi base64
    // $base64Image = base64_encode($imageData);
    // $data['dataCuti']['ttd_atasan'] = '<img style="display:block; heigth:50%; width:50%;" src="' . $data['dataCuti']['ttd_atasan'] . '" />';

    // Lakukan pengecekan pada bagian ini
    // // mendapatkan path gambar
    // $imagePath = 'dist/img/ttd/' . $data['dataCuti']['ttd_pejabatz'];
    // // membaca file gambar
    // $imageData = file_get_contents($imagePath);
    // // mendapatkan extension dari nama file gambar
    // $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // // encode gambar menjadi base64
    // $base64Image = base64_encode($imageData);
    // $data['dataCuti']['ttd_pejabat'] = '<img style="display:block; heigth:50%; width:50%;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />';

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    // $dompdf->loadHtml('hello world');
    if($data['dataCuti']['jenis_cuti'] == "Cuti Karena Alasan Penting") {
      $dompdf->loadHtml(view('cuti/cuti_alasan_penting_export_pdf', $data));
    } else {
      $dompdf->loadHtml(view('cuti/cuti_export_pdf', $data));
    }
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    $fileNo = $data['dataCuti']['nip'];
    $name = 'Surat-Cuti';
    $description = $data['dataCuti']['tgl_diajukan'];

    // Output the generated PDF to Browser
    $dompdf->stream($fileNo . "-" . $name . "-" . $description . ".pdf");
  }
  public function cutiPreview($id = null)
  {

    $data['dataCuti'] = $this->cutiDataCutiModel
      ->select("*,data_pegawai.nama, cuti_data_cuti.no_telp")
      // ->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_cuti.id_cuti_status')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->join('user', 'user.id_user=cuti_data_cuti.updated_by')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first();

    $getDataPegawai = $this->dataPegawaiModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('cuti_data_cuti', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
      ->where('cuti_data_cuti.id_cuti_data_cuti', $id)
      ->first();

    $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
    $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->first();
    $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->first();
    $getPejabat = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[2])->first();
    $getUnitKerja = $this->unitKerjaModel->join('cuti_data_cuti', 'cuti_data_cuti.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_cuti', $id)->first();
    $getUnitKerjaAtas = $unit_kerja_skema = strtolower($getUnitKerja['unit_kerja']);

    $data['dataCuti'] += [
      'nama_pegawai'        => $getDataPegawai['nama'],
      'jabatan_pegawai'     => $getDataPegawai['jabatan'],
      'masa_kerja_pegawai'  => $getDataPegawai['masa_kerja'],
      'nip_pegawai'         => $getDataPegawai['nip'],
      'unit_kerja_pegawai'  => $getDataPegawai['unit_kerja'],
      'jenis_surat'         => 'cuti',
      'unit_kerja_skema'    => ucwords($getUnitKerjaAtas),
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
    $dates = date_create($data['dataCuti']['tanggal_pengajuan']);
    $day = $this->hariIndo(date_format($dates, "l"));

    $dates = $this->tgl_indo(date_format($dates, "d/m/Y"));

    $data['dataCuti'] += ['tgl_diajukan' => $dates];

    // mendapatkan path gambar
    $imagePath = 'karakter/ceklis.svg';
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data['dataCuti'] += ['ceklis' => '<img style="width:16px;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];

    // mendapatkan path gambar
    $imagePath = 'dist/img/ttd/blank.svg';
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data['dataCuti']['ttd_atasan'] = '<img style="display:block; heigth:70%; width:70%;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />';

    // mendapatkan path gambar
    $imagePath = 'dist/img/ttd/blank.svg';
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data['dataCuti']['ttd_pejabat'] = '<img style="display:block; heigth:70%; width:70%;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />';

    // membangun tag img dengan data base64

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    // $dompdf->loadHtml('hello world');
    // $dompdf->set_base_path(APPPATH . 'Font/');
    $dompdf->loadHtml(view('cuti/cuti_export_pdf', $data));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser

    // Output PDF to browser
    $dompdf->stream(null, array("Attachment" => false));
  }
  public function permohonan($id = null)
  {

    $data['dataCuti'] = $this->cutiDataPermohonanModel
      // ->join('cuti_status', 'cuti_status.id_cuti_status=cuti_data_cuti.id_cuti_status')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      // ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
      ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
      ->first();

    $getDataPegawai = $this->dataPegawaiModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('cuti_data_permohonan', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
      ->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')
      ->join('golongan', 'data_pegawai.id_golongan=golongan.id_golongan')
      ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
      ->first();

    $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
    $getKepegawaian = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[0])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getAtasan = $this->dataPegawaiModel->where('id_data_pegawai', $dataSkemaCuti[1])->join('jabatan', 'data_pegawai.id_jabatan=jabatan.id_jabatan')->first();
    $getUnitKerja = $this->unitKerjaModel->join('cuti_data_permohonan', 'cuti_data_permohonan.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_permohonan', $id)->first();
    $getUnitKerjaAtas = strtolower($getUnitKerja['unit_kerja']);

    $data['dataCuti'] += [
      'nama_pegawai'        => $getDataPegawai['nama'],
      'jabatan_pegawai'     => $getDataPegawai['jabatan'],
      'masa_kerja_pegawai'  => $getDataPegawai['masa_kerja'],
      'nip_pegawai'         => $getDataPegawai['nip'],
      'unit_kerja_pegawai'  => $getDataPegawai['unit_kerja'],
      'jenis_surat'         => 'surat_sakti',
      'unit_kerja_skema'    => ucwords($getUnitKerjaAtas),
      'lokasi_skema'        => $getUnitKerja['lokasi'],
      'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
      'nama_kepegawaian'    => $getKepegawaian['nama'],
      'nip_kepegawaian'     => $getKepegawaian['nip'],
      'jabatan_atasan'      => $getAtasan['jabatan'],
      'nama_atasan'         => $getAtasan['nama'],
      'nip_atasan'          => $getAtasan['nip'],

    ];

    // mendapatkan path gambar
    $imagePath = 'dist/img/ttd/blank.svg';
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data['dataCuti'] += ['ttd_blank' => '<img style="display:block; heigth:10%; width:10%;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];


    $date = date_create($data['dataCuti']['tanggal']);
    $ubahKeIndo = $this->tgl_indo(date_format($date, "d/m/Y"));

    $data['dataCuti'] += [
      'periode' => $ubahKeIndo,
    ];
    $dates = date_create($data['dataCuti']['tanggal']);
    $day = $this->hariIndo(date_format($dates, "l"));

    $dates = $this->tgl_indo(date_format($dates, "d/m/Y"));

    $data['dataCuti'] += ['tgl_diajukan' => $day];
    $data['dataCuti'] += ['jenis_permohonan_cek' => strtolower($data['dataCuti']['jenis_permohonan'])];

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    // $dompdf->loadHtml('hello world');
    $dompdf->loadHtml(view('cuti/cuti_export_pdf_permohonan', $data));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    $fileNo = $data['dataCuti']['nip'];
    $name = 'Surat-Sakti';
    $description = $data['dataCuti']['tgl_diajukan'] . ', ' . $data['dataCuti']['periode'];

    // Output the generated PDF to Browser
    $dompdf->stream($fileNo . "-" . $name . "-" . $description . ".pdf");
    // $dompdf->stream();
  }
  public function permohonanPreview($id = null)
  {

    $data['dataCuti'] = $this->cutiDataPermohonanModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
      ->join('user', 'user.id_user=cuti_data_permohonan.updated_by')
      ->join('jabatan', 'jabatan.id_jabatan=cuti_data_permohonan.id_jabatan')
      ->join('golongan', 'golongan.id_golongan=cuti_data_permohonan.id_golongan')
      ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
      ->first();

    $getDataPegawai = $this->dataPegawaiModel
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja')
      ->join('cuti_data_permohonan', 'data_pegawai.id_data_pegawai=cuti_data_permohonan.id_data_pegawai')
      ->join('jabatan', 'jabatan.id_jabatan=cuti_data_permohonan.id_jabatan')
      ->where('cuti_data_permohonan.id_cuti_data_permohonan', $id)
      ->first();

    $dataSkemaCuti = explode("|", $data['dataCuti']['id_skema_titimangsa']);
    $getKepegawaian = $this->dataPegawaiModel
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')->where('id_data_pegawai', $dataSkemaCuti[0])->first();
    $getAtasan = $this->dataPegawaiModel
      ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan')->where('id_data_pegawai', $dataSkemaCuti[1])->first();
    $getUnitKerja = $this->unitKerjaModel->join('cuti_data_permohonan', 'cuti_data_permohonan.id_unit_kerja=unit_kerja.id_unit_kerja')->where('id_cuti_data_permohonan', $id)->first();
    $getUnitKerjaAtas = $unit_kerja_skema = strtolower($getUnitKerja['unit_kerja']);

    $data['dataCuti'] += [
      'nama_pegawai'        => $getDataPegawai['nama'],
      'jabatan_pegawai'     => $getDataPegawai['jabatan'],
      'masa_kerja_pegawai'  => $getDataPegawai['masa_kerja'],
      'nip_pegawai'         => $getDataPegawai['nip'],
      'unit_kerja_pegawai'  => $getDataPegawai['unit_kerja'],
      'jenis_surat'         => 'surat_sakti',
      'unit_kerja_skema'    => ucwords($getUnitKerjaAtas),
      'lokasi_skema'        => $getUnitKerja['lokasi'],
      'jabatan_kepegawaian' => $getKepegawaian['jabatan'],
      'nama_kepegawaian'    => $getKepegawaian['nama'],
      'nip_kepegawaian'     => $getKepegawaian['nip'],
      'jabatan_atasan'      => $getAtasan['jabatan'],
      'nama_atasan'         => $getAtasan['nama'],
      'nip_atasan'          => $getAtasan['nip'],
    ];

    $date = date_create($data['dataCuti']['tanggal']);
    $ubahKeIndo = $this->tgl_indo(date_format($date, "d/m/Y"));

    $data['dataCuti'] += [
      'periode' => $ubahKeIndo,
    ];
    $dates = date_create($data['dataCuti']['tanggal']);
    $day = $this->hariIndo(date_format($dates, "l"));

    $dates = $this->tgl_indo(date_format($dates, "d/m/Y"));

    $data['dataCuti'] += ['tgl_diajukan' => $day];
    $data['dataCuti'] += ['jenis_permohonan_cek' => strtolower($data['dataCuti']['jenis_permohonan'])];

    // mendapatkan path gambar
    $imagePath = 'dist/img/ttd/blank.svg';
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data['dataCuti'] += ['ttd_blank' => '<img style="display:block; heigth:10%; width:10%;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];

    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    // $dompdf->loadHtml('hello world');
    $dompdf->loadHtml(view('cuti/cuti_export_pdf_permohonan', $data));

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    // $dompdf->stream();
    $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
  }
  
  public function infografisPDFPreview($id = null){
      
    $date = $this->request->getVar('tanggal-infografis');
    $jenis_infografis = $this->request->getVar('jenis_infografis');
    $wilayah = $this->request->getVar('wilayah');
    
    if($wilayah == "") {
        $data['wilayah'] = "SEMUA WILAYAH KERJA";
    } else {
        $data['wilayah'] = "WILAYAH KERJA " . $wilayah;
    }
    if($jenis_infografis != "") {
        $data['jenis_infografis'] = "INFOGRAFIS UNTUK DATA " . $jenis_infografis;
    } else {
        $data['jenis_infografis'] = "AKUMULASI KESELURUHAN DATA INFOGRAFIS";
    }

    $date_awal = substr($date, 0, 10);
    $date_akhir = substr($date, 13, 10);
    
    if ($date_awal==$date_akhir) {
        $data['tanggal'] = $this->tgl_indonesia($date_akhir);
    } elseif (substr($date_awal, 0, 7) == substr($date_akhir, 0, 7)) {
        $data['tanggal'] = substr($date_awal, 8, 2) . ' - ' . $this->tgl_indonesia($date_akhir);
    } elseif (substr($date_awal, 0, 4) == substr($date_akhir, 0, 4)) {
        $data['tanggal'] = substr($date_awal, 8, 2) . ' ' . $this->bulan($date_awal) . ' - ' . $this->tgl_indonesia($date_akhir);
    } else {
        $data['tanggal'] = $this->tgl_indonesia($date_awal) . ' - ' . $this->tgl_indonesia($date_akhir);
    }
    
    // Ubah format tanggal pada $date_awal dan $date_akhir
    $date_awal = date('Y-m-d 00:00:00', strtotime($date_awal));
    $date_akhir = date('Y-m-d 23:59:59', strtotime($date_akhir));
    
    // ============================================================================================================
    // ============================================================================================================
    if($jenis_infografis == "" || $jenis_infografis == "kapal") {
        $this->infografisKapalModel->select('
          SUM(kapal_datang_negeri) AS kapal_datang_negeri,
          SUM(kapal_datang_luar) AS kapal_datang_luar,
          SUM(kapal_berangkat_negeri) AS kapal_berangkat_negeri,
          SUM(kapal_berangkat_luar) AS kapal_berangkat_luar,
          SUM(abk_datang_negeri) AS abk_datang_negeri,
          SUM(abk_datang_luar) AS abk_datang_luar,
          SUM(abk_berangkat_negeri) AS abk_berangkat_negeri,
          SUM(abk_berangkat_luar) AS abk_berangkat_luar,
          SUM(penumpang_datang_negeri) AS penumpang_datang_negeri,
          SUM(penumpang_datang_luar) AS penumpang_datang_luar,
          SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri,
          SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar,
          ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"');
        if ($wilayah != "") {
            $this->infografisKapalModel->where('unit_kerja', $wilayah);
        }
        $data['rekapitulasi_kapal'] = $this->infografisKapalModel->first();
    } else {
        $data['rekapitulasi_kapal'] = [
            'kapal_datang_negeri' => '-',
            'kapal_datang_luar' => '-',
            'kapal_berangkat_negeri' => '-',
            'kapal_berangkat_luar' => '-',
            'abk_datang_negeri' => '-',
            'abk_datang_luar' => '-',
            'abk_berangkat_negeri' => '-',
            'abk_berangkat_luar' => '-',
            'penumpang_datang_negeri' => '-',
            'penumpang_datang_luar' => '-',
            'penumpang_berangkat_negeri' => '-',
            'penumpang_berangkat_luar' => '-',
        ];
    }
    // ============================================================================================================
    // ============================================================================================================
    if($jenis_infografis == "" || $jenis_infografis == "pesawat") {
        $this->infografisPesawatModel->select('
          SUM(pesawat_datang_negeri) AS pesawat_datang_negeri,
          SUM(pesawat_datang_luar) AS pesawat_datang_luar,
          SUM(pesawat_berangkat_negeri) AS pesawat_berangkat_negeri,
          SUM(pesawat_berangkat_luar) AS pesawat_berangkat_luar,
          SUM(crew_datang_negeri) AS crew_datang_negeri,
          SUM(crew_datang_luar) AS crew_datang_luar,
          SUM(crew_berangkat_negeri) AS crew_berangkat_negeri,
          SUM(crew_berangkat_luar) AS crew_berangkat_luar,
          SUM(penumpang_datang_negeri) AS penumpang_datang_negeri,
          SUM(penumpang_datang_luar) AS penumpang_datang_luar,
          SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri,
          SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar,
          ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"');
        if ($wilayah != "") {
            $this->infografisPesawatModel->where('unit_kerja', $wilayah);
        }
        $data['rekapitulasi_pesawat'] = $this->infografisPesawatModel->first();
    } else {
        $data['rekapitulasi_pesawat'] = [
            'pesawat_datang_negeri' => '-',
            'pesawat_datang_luar' => '-',
            'pesawat_berangkat_negeri' => '-',
            'pesawat_berangkat_luar' => '-',
            'crew_datang_negeri' => '-',
            'crew_datang_luar' => '-',
            'crew_berangkat_negeri' => '-',
            'crew_berangkat_luar' => '-',
            'penumpang_datang_negeri' => '-',
            'penumpang_datang_luar' => '-',
            'penumpang_berangkat_negeri' => '-',
            'penumpang_berangkat_luar' => '-',
        ];
    }
    // ============================================================================================================
    // ============================================================================================================
    $data['rekapitulasi_dokumen'] = [];
    $dokumenTambahan = [];
    if($jenis_infografis == "" || $jenis_infografis == "dokumen" || $jenis_infografis == "vaksin") {
        if($jenis_infografis == "" || $jenis_infografis == "dokumen") {
            $this->infografisVaksinModel->select('
              SUM(bukes) AS total_bukes,
              SUM(phqc) AS total_phqc,
              SUM(cop) AS total_cop,
              SUM(sscec) AS total_sscec,
              SUM(sscc) AS total_sscc,
              SUM(p3k) AS total_p3k,
              SUM(icv) AS total_icv,
              SUM(sklt) AS total_sklt,
              SUM(siaos) AS total_siaos,
              SUM(siaj) AS total_siaj,
              SUM(kir) AS total_kir,
              SUM(kontraindikasi) AS total_kontraindikasi,
              SUM(e_icv) AS total_e_icv,
            ');
            if ($jenis_infografis == "dokumen") {
                $dokumenTambahan = [
                    'total_vaksin_mm' => '-',
                    'total_vaksin_yf' => '-',
                    'total_vaksin_covid' => '-',
                    'vaksin_influenza' => '-',
                    'vaksin_tifoid' => '-',
                    'vaksin_polio' => '-',
                ];
            }
        }
        if($jenis_infografis == "" || $jenis_infografis == "vaksin") {
            $this->infografisVaksinModel->select('
              SUM(vaksin_mm) AS total_vaksin_mm,
              SUM(vaksin_yf) AS total_vaksin_yf,
              SUM(vaksin_covid) AS total_vaksin_covid,
              SUM(vaksin_influenza) AS vaksin_influenza,
              SUM(vaksin_tifoid) AS vaksin_tifoid,
              SUM(vaksin_polio) AS vaksin_polio,
            ');
            if ($jenis_infografis == "vaksin") {
                $dokumenTambahan = [
                    'total_bukes' => '-',
                    'total_phqc' => '-',
                    'total_cop' => '-',
                    'total_sscec' => '-',
                    'total_sscc' => '-',
                    'total_p3k' => '-',
                    'total_icv' => '-',
                    'total_sklt' => '-',
                    'total_siaos' => '-',
                    'total_siaj' => '-',
                    'total_kir' => '-',
                    'total_kontraindikasi' => '-',
                    'total_e_icv' => '-',
                ];
            }
        }
        $this->infografisVaksinModel->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"');
        if ($wilayah != "") {
            $this->infografisVaksinModel->where('unit_kerja', $wilayah);
        }
        $data['rekapitulasi_dokumen'] += $this->infografisVaksinModel->first();
        $data['rekapitulasi_dokumen'] += $dokumenTambahan;
    } else {
        $data['rekapitulasi_dokumen'] += [
            'total_bukes' => '-',
            'total_phqc' => '-',
            'total_cop' => '-',
            'total_sscec' => '-',
            'total_sscc' => '-',
            'total_p3k' => '-',
            'total_icv' => '-',
            'total_sklt' => '-',
            'total_siaos' => '-',
            'total_siaj' => '-',
            'total_kir' => '-',
            'total_vaksin_mm' => '-',
            'total_vaksin_yf' => '-',
            'total_vaksin_covid' => '-',
            'vaksin_influenza' => '-',
            'vaksin_tifoid' => '-',
            'vaksin_polio' => '-',
            'total_kontraindikasi' => '-',
            'total_e_icv' => '-',
        ];
    }
    // ============================================================================================================
    // ============================================================================================================
    
    // mendapatkan path gambar
    $imagePath = 'dist/img/Infografis-Baru-v3.png';
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data['background'] = 'data:image/' . $imageExtension . ';base64,' . $base64Image;
    
    // Set memory limit for PDF generation
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 300);
    
    // Configure DomPDF options for better memory management
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isFontSubsettingEnabled', true);
    $options->set('defaultMediaType', 'print');
    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml(view('infografis/infografis_output_pdf', $data));
    
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($data['tanggal'] . "-data-infografis.pdf", array("Attachment" => true));
    
    // Reset memory limit
    ini_restore('memory_limit');
    ini_restore('max_execution_time');
  }
  
  public function sinadi_nominatif($value = '')
  {
    $filename = date('d-m-y-H-i-s') . '-nominatif';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $data['perjalanan'] = $this->sinadiPerjalananDinasPesertaModel
      ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
      ->join('sinadi_setting', 'sinadi_perjalanan_dinas_peserta.id_sinadi_setting=sinadi_setting.id_sinadi_setting')
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $value)
      ->findAll();
      
    $filename = 'nominatif - (' . preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', $data['perjalanan'][0]['nama_kegiatan']) . ') - ' . ($data['perjalanan'][0]['tgl_pergi'] == $data['perjalanan'][0]['tgl_pulang'] ? $data['perjalanan'][0]['tgl_pergi'] : $data['perjalanan'][0]['tgl_pergi'] . " s.d" . $data['perjalanan'][0]['tgl_pulang']);

      
    $data['ttd_pic'] = $this->sinadiPerjalananDinasModel
      ->select('ttd_peserta as ttd_pic, sinadi_perjalanan_dinas.*')
      ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan = sinadi_perjalanan_dinas.id_perjalanan')
      ->where('sinadi_perjalanan_dinas_peserta.nip_pegawai = sinadi_perjalanan_dinas.pic_nip', null, false)
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $value)
      ->first();

    if((!$data['ttd_pic']) || (isset($data['ttd_pic']['ttd_pic']) && $data['ttd_pic']['ttd_pic'] == '')) {
      $fallback = $this->sinadiPerjalananDinasModel->where('id_perjalanan', $value)->first();
      if($fallback && isset($fallback['verifikator_4']) && $fallback['verifikator_4']) {
        try {
          $ver4 = unserialize($fallback['verifikator_4']);
          if(is_array($ver4) && isset($ver4[0]['signature']) && $ver4[0]['signature']) {
            if(!$data['ttd_pic']) $data['ttd_pic'] = $fallback;
            $data['ttd_pic']['ttd_pic'] = $ver4[0]['signature'];
          }
        } catch(\Throwable $e) {}
      }
    }
      
    $data['ttd_verifikator_3'] = null;
    if($data['ttd_pic'] && isset($data['ttd_pic']['verifikator_3']) && $data['ttd_pic']['verifikator_3']) {
        $ttd_verifikator_3 = unserialize($data['ttd_pic']['verifikator_3']);
        if($ttd_verifikator_3[0]['status']) {
            $data['ttd_verifikator_3'] = generate_base64(ltrim($ttd_verifikator_3[0]['signature'], '/'));
        }
    }
    
    if($data['ttd_pic'] && isset($data['ttd_pic']['tgl_pelaksanaan']) && $data['ttd_pic']['tgl_pelaksanaan'] >= "2025-04-30"):
      $dompdf->loadHtml(view('sinadi/nominatif_v2', $data));
    else:
      $dompdf->loadHtml(view('sinadi/nominatif', $data));
    endif;
    
    $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper($customPaper, 'landscape');
    $dompdf->render();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
  }
  public function sinadi_kuitansi($value = '')
  {
    $filename = date('d-m-y-H-i-s') . '-kuitansi';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $data['perjalanan'] = $this->sinadiPerjalananDinasPesertaModel
      ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
      ->join('sinadi_setting', 'sinadi_perjalanan_dinas_peserta.id_sinadi_setting=sinadi_setting.id_sinadi_setting')
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $value)
      ->findAll();
      
    $filename = 'kuitansi - (' . preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', $data['perjalanan'][0]['nama_kegiatan']) . ') - ' . ($data['perjalanan'][0]['tgl_pergi'] == $data['perjalanan'][0]['tgl_pulang'] ? $data['perjalanan'][0]['tgl_pergi'] : $data['perjalanan'][0]['tgl_pergi'] . " s.d" . $data['perjalanan'][0]['tgl_pulang']);

      
    // mendapatkan path gambar
    $imagePath = 'dist/img/logo_kkp.png';
    
    // Mengganti logo KKP menjadi Kemenkes di Tahun 2024
    if(date('Y', strtotime($data['perjalanan'][0]['tgl_pergi'])) >= 2024){
        $imagePath = 'dist/img/logo-kesehatan.png';
    }
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data += ['logo_kkp' => '<img style="width:110%; margin:0; padding:0;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];


    $dompdf->loadHtml(view('sinadi/kuitansi', $data));

    $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper($customPaper, 'potrait');
    $dompdf->render();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
  }
  public function sinadi_riil($value = '')
  {
    $filename = date('d-m-y-H-i-s') . '-riil';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $data['perjalanan'] = $this->sinadiPerjalananDinasPesertaModel
      ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
      ->join('sinadi_setting', 'sinadi_perjalanan_dinas_peserta.id_sinadi_setting=sinadi_setting.id_sinadi_setting')
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $value)
      ->findAll();
    $filename = 'riil - (' . preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', $data['perjalanan'][0]['nama_kegiatan']) . ') - ' . ($data['perjalanan'][0]['tgl_pergi'] == $data['perjalanan'][0]['tgl_pulang'] ? $data['perjalanan'][0]['tgl_pergi'] : $data['perjalanan'][0]['tgl_pergi'] . " s.d" . $data['perjalanan'][0]['tgl_pulang']);


    // mendapatkan path gambar
    $imagePath = 'dist/img/logo_kkp.png';
    
    // Mengganti logo KKP menjadi Kemenkes di Tahun 2024
    if(date('Y', strtotime($data['perjalanan'][0]['tgl_pergi'])) >= 2024){
        $imagePath = 'dist/img/logo-kesehatan.png';
    }
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data += ['logo_kkp' => '<img style="width:110%; margin:0; padding:0;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];


    $dompdf->loadHtml(view('sinadi/riil', $data));

    $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper($customPaper, 'potrait');
    $dompdf->render();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
  }
  public function sinadi_kuitansi_riil($value = '')
  {
    $filename = date('d-m-y-H-i-s') . '-kuitansi-riil';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $data['perjalanan'] = $this->sinadiPerjalananDinasPesertaModel
      ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
      ->join('sinadi_setting', 'sinadi_perjalanan_dinas_peserta.id_sinadi_setting=sinadi_setting.id_sinadi_setting')
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $value)
      ->findAll();
      
    $filename = 'kuitansi-riil - (' . preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', $data['perjalanan'][0]['nama_kegiatan']) . ') - ' . ($data['perjalanan'][0]['tgl_pergi'] == $data['perjalanan'][0]['tgl_pulang'] ? $data['perjalanan'][0]['tgl_pergi'] : $data['perjalanan'][0]['tgl_pergi'] . " s.d" . $data['perjalanan'][0]['tgl_pulang']);

    // mendapatkan path gambar
    $imagePath = 'dist/img/logo_kkp.png';
    
    // Mengganti logo KKP menjadi Kemenkes di Tahun 2024
    if(date('Y', strtotime($data['perjalanan'][0]['tgl_pergi'])) >= 2024){
        $imagePath = 'dist/img/logo-kesehatan.png';
    }
    // membaca file gambar
    $imageData = file_get_contents($imagePath);
    // mendapatkan extension dari nama file gambar
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    // encode gambar menjadi base64
    $base64Image = base64_encode($imageData);
    $data += ['logo_kkp' => '<img style="width:110%; margin:0; padding:0;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];

    $data['ttd_verifikator_2'] = null;
    if($data['perjalanan'][0]['verifikator_2']) {
        $ttd_verifikator_2 = unserialize($data['perjalanan'][0]['verifikator_2']);
        if($ttd_verifikator_2[0]['status']) {
            $data['ttd_verifikator_2'] = generate_base64(ltrim($ttd_verifikator_2[0]['signature'], '/'));
        }
    }
    
    $data['ttd_verifikator_3'] = null;
    if($data['perjalanan'][0]['verifikator_3']) {
        $ttd_verifikator_3 = unserialize($data['perjalanan'][0]['verifikator_3']);
        if($ttd_verifikator_3[0]['status']) {
            $data['ttd_verifikator_3'] = generate_base64(ltrim($ttd_verifikator_3[0]['signature'], '/'));
        }
    }
    
    
    if($data['perjalanan'][0]['tgl_pelaksanaan'] >= "2025-04-30"):
        $dompdf->loadHtml(view('sinadi/kuitansi_riil_v2', $data));
    else:
        $dompdf->loadHtml(view('sinadi/kuitansi_riil', $data));
    endif;


    $customPaper = array(0, 0, 612, 1008);
    $dompdf->setPaper($customPaper, 'potrait');
    $dompdf->render();
    
    // dd();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
  }
  public function sinadi_sppd($value = '', $borang = null)
  {
    // $filename = date('d-m-y-H-i-s') . '-sppd';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $data['perjalanan'] = $this->sinadiPerjalananDinasPesertaModel
      ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
      ->join('sinadi_setting', 'sinadi_perjalanan_dinas_peserta.id_sinadi_setting=sinadi_setting.id_sinadi_setting')
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $value)
      ->findAll();
      
    $filename = 'sppd - (' . preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', $data['perjalanan'][0]['nama_kegiatan']) . ') - ' . ($data['perjalanan'][0]['tgl_pergi'] == $data['perjalanan'][0]['tgl_pulang'] ? $data['perjalanan'][0]['tgl_pergi'] : $data['perjalanan'][0]['tgl_pergi'] . " s.d" . $data['perjalanan'][0]['tgl_pulang']);

    // return (view('sinadi/sppd', $data));
    
    $data['ttd_verifikator_3'] = null;
    $data['borang'] = $borang;
    
    // Tangkap data GET dari Modal SPPD Srikandi
    $data['jabatan_tujuan'] = $this->request->getGet('jabatan_tujuan');
    $pegawai_tujuan = $this->request->getGet('pegawai_tujuan');
    
    if ($pegawai_tujuan === 'manual') {
        $data['nama_tujuan'] = $this->request->getGet('nama_tujuan_manual');
        $data['nip_tujuan']  = $this->request->getGet('nip_tujuan_manual');
    } else if (!empty($pegawai_tujuan)) {
        $peg_arr = explode('|', $pegawai_tujuan);
        if (count($peg_arr) == 2) {
            $data['nama_tujuan'] = $peg_arr[0];
            $data['nip_tujuan'] = $peg_arr[1];
        }
    }
    
    if($data['perjalanan'][0]['verifikator_3']) {
        $ttd_verifikator_3 = unserialize($data['perjalanan'][0]['verifikator_3']);
        if($ttd_verifikator_3[0]['status']) {
            $data['ttd_verifikator_3'] = generate_base64(ltrim($ttd_verifikator_3[0]['signature'], '/'));
        }
    }
    
    if($borang != "borang") {
        if($data['perjalanan'][0]['tgl_pelaksanaan'] >= "2025-04-30"):
            $dompdf->loadHtml(view('sinadi/sppd_v2', $data));
        else:
            $dompdf->loadHtml(view('sinadi/sppd', $data));
        endif;
    } else {
        $dompdf->loadHtml(view('sinadi/sppd', $data));
    }


    $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper($customPaper, 'potrait');
    $dompdf->render(); 

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
  }

  public function pengawasan_kapal_laporan()
  {
    $dompdf = new Dompdf();

    $pengawasanKapalModel = new \App\Models\PengawasanKapal\PengawasanKapalModel();
    $targetAksesModel = new \App\Models\PengawasanKapal\PengawasanKapalTargetAksesModel();
    $unitKerjaModel = new \App\Models\Master\UnitKerjaModel();

    $dateRange = service('request')->getGet('dateRange');
    $query = $pengawasanKapalModel;

    $lokasiRow = $targetAksesModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=pengawasan_kapal_target_akses.id_data_pegawai')
      ->where('data_pegawai.nama', session()->get('nama'))
      ->first();

    if(($lokasiRow && $lokasiRow['lokasi'] != null) || session()->get('role') == 'Admin') {
      if($lokasiRow) {
        $lokasiArray = json_decode($lokasiRow['lokasi'], true);
        $lokasiData = $unitKerjaModel->whereIn('id_unit_kerja', $lokasiArray)->findAll();
        $unitKerjaArray = array_column($lokasiData, 'lokasi');

        $pelabuhanArray = [];
        foreach ($unitKerjaArray as $lokasi) {
          switch (strtolower($lokasi)) {
            case 'cirebon':
              $pelabuhanArray[] = 'cirebon';
              break;
            case 'indramayu':
              $pelabuhanArray[] = 'Indramayu';
              break;
            case 'subang':
              $pelabuhanArray[] = 'patimban';
              break;
            default:
              $pelabuhanArray[] = $lokasi;
              break;
          }
        }
        $query = $query->whereIn('pelabuhan', $pelabuhanArray);
      }

      if ($dateRange) {
        [$startRaw, $endRaw] = explode(' - ', $dateRange);
        $parseDate = function($d) {
          $d = trim($d ?? '');
          if ($d === '') return '';
          $dateOnly = explode(' ', $d)[0];
          if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateOnly)) {
            $dt = \DateTime::createFromFormat('d/m/Y', $dateOnly);
            return $dt ? $dt->format('Y-m-d') : $dateOnly;
          }
          if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateOnly)) {
            $dt = \DateTime::createFromFormat('d-m-Y', $dateOnly);
            return $dt ? $dt->format('Y-m-d') : $dateOnly;
          }
          if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateOnly)) {
            return $dateOnly;
          }
          // fallback attempt
          try { return (new \DateTime($dateOnly))->format('Y-m-d'); } catch (\Throwable $e) { return $dateOnly; }
        };
        $startDate = $parseDate($startRaw);
        $endDate = $parseDate($endRaw);
        if($startDate !== '' && $endDate !== '') {
          $query->where('DATE(tbl_pasien.date_updated) >=', $startDate)
            ->where('DATE(tbl_pasien.date_updated) <=', $endDate);
          $data['dateRange'] = $startDate . ' - ' . $endDate;
        }
      } else {
        $startDate = date('Y-01-01');
        $endDate = date('Y-m-d');
        $query->where('DATE(tbl_pasien.date_updated) >=', $startDate)
          ->where('DATE(tbl_pasien.date_updated) <=', $endDate);
        $data['dateRange'] = $startDate . ' - ' . $endDate;
      }

      $records = $query->orderBy('tbl_pasien.date_updated DESC')->findAll();
      $data['title'] = 'Laporan Pengawasan Kapal';
      $data['dateRange'] = $data['dateRange'] ?? $dateRange;

      // Build period text for header
      $periodText = '..... s.d. .....';
      if (!empty($data['dateRange']) && strpos($data['dateRange'], ' - ') !== false) {
        [$s, $e] = explode(' - ', $data['dateRange']);
        $periodText = $this->formatIndoSafe($s) . ' s.d. ' . $this->formatIndoSafe($e);
      } elseif (!empty($records)) {
        $ts = [];
        foreach($records as $r) {
          $val = $r['date_updated'] ?? ($r['created_at'] ?? ($r['date_created'] ?? ($r['created_date'] ?? null)));
          if($val) { $ts[] = strtotime($val); }
        }
        if($ts) {
          $periodText = $this->formatIndoSafe(date('Y-m-d', min($ts))) . ' s.d. ' . $this->formatIndoSafe(date('Y-m-d', max($ts)));
        }
      }
      $data['periodText'] = $periodText;

      $ports = ['Cirebon','Patimban','Indramayu'];
      $initRow = function() {
        return [
          'jumlah' => 0,
          'terjangkit' => 0,
          'tidak_terjangkit' => 0,
          'rendah' => 0,
          'sedang' => 0,
          'tinggi' => 0,
          'sandar' => 0,
          'zona_karantina' => 0,
        ];
      };
      $recap = [
        'Cirebon' => $initRow(),
        'Patimban' => $initRow(),
        'Indramayu' => $initRow(),
      ];

      foreach($records as $r) {
        $pelabuhan = isset($r['pelabuhan']) ? strtolower(trim($r['pelabuhan'])) : '';
        $key = null;
        if ($pelabuhan === 'cirebon') $key = 'Cirebon';
        elseif ($pelabuhan === 'patimban') $key = 'Patimban';
        elseif ($pelabuhan === 'indramayu') $key = 'Indramayu';
        if (!$key) continue;

        $recap[$key]['jumlah']++;

        // Kategori: gunakan indikator LN (surat_pernyataan_nahkoda) sebagai "Terjangkit" vs DN sebagai "Tidak Terjangkit"
        $isLN = (isset($r['surat_pernyataan_nahkoda']) && !empty($r['surat_pernyataan_nahkoda']));
        if ($isLN) $recap[$key]['terjangkit']++; else $recap[$key]['tidak_terjangkit']++;

        // Kresiko
        $kresiko = $r['kresiko'] ?? '';
        if (!$kresiko && isset($r['skoring'])) {
          $sk = (int)$r['skoring'];
          if ($sk >= 20) $kresiko = 'TINGGI';
          elseif ($sk >= 10) $kresiko = 'SEDANG';
          else $kresiko = 'RENDAH';
        }
        if ($kresiko === 'TINGGI') $recap[$key]['tinggi']++;
        elseif ($kresiko === 'SEDANG') $recap[$key]['sedang']++;
        else $recap[$key]['rendah']++;

        // Status
        $status = $r['hasilStatus'] ?? '';
        if (!$status && isset($r['skoring'])) {
          $sk = (int)$r['skoring'];
          if ($sk >= 20) $status = 'ZONA KARANTINA';
          elseif ($sk >= 10) $status = 'KONFIRMASI';
          else $status = 'SANDAR';
        }
        if (($r['status_verifikasi'] ?? 'pending') === 'pending') {
          // anggap belum verifikasi → tidak menambah status sandar/zona
        } else {
          if ($status === 'ZONA KARANTINA') $recap[$key]['zona_karantina']++;
          elseif ($status === 'SANDAR') $recap[$key]['sandar']++;
        }
      }

      // Total row
      $total = $initRow();
      foreach($recap as $row) {
        foreach($total as $k => $v) { $total[$k] += $row[$k]; }
      }

      $data['recap'] = $recap;
      $data['total'] = $total;
      $data['tanggalCetak'] = $this->formatIndoSafe(date('Y-m-d'));
      $data['userNama'] = session()->get('nama') ?? '';
      $userNip = preg_replace('/\D/', '', session()->get('nip') ?? '');
      if(!$userNip) {
        try {
          $dp = new \App\Models\Master\DataPegawaiModel();
          $byNama = $dp->where('nama', session()->get('nama'))->orderBy('id_data_pegawai','desc')->first();
          if($byNama && isset($byNama['nip'])) {
            $userNip = preg_replace('/\D/','', $byNama['nip']);
          } else {
            $byUser = $dp->where('id_user', session()->get('id_user'))->orderBy('id_data_pegawai','desc')->first();
            if($byUser && isset($byUser['nip'])) {
              $userNip = preg_replace('/\D/','', $byUser['nip']);
            }
          }
        } catch(\Throwable $e) {}
      }
      $data['userNip'] = $userNip;

      $dompdf->loadHtml(view('pengawasan_kapal/laporan_pengawasan_kapal_pdf', $data));
      // Orientasi halaman diatur per halaman melalui CSS @page rules pada view
      $dompdf->render();
      $dompdf->stream('Laporan_Pengawasan_Kapal_' . date('Y-m-d_H-i-s') . '.pdf', array('Attachment' => true));
    } else {
      echo 'Akses tidak diizinkan';
    }
  }

  private function formatIndoSafe($ymd)
  {
    $map = [
      '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
    ];
    try {
      $d = new \DateTime($ymd);
      $m = $d->format('m');
      return $d->format('d') . ' ' . ($map[$m] ?? $m) . ' ' . $d->format('Y');
    } catch (\Throwable $e) {
      return $ymd;
    }
  }


  // public function dokumenSopPreview($id)
  // {
  //   try {
  //     $dompdf = new Dompdf();

  //     $canvas = $dompdf->getCanvas();

  //     $data['dokumenSop'] = $this->sipepesModel->where('dokumen_id', $id)->first();

  //     $data['halaman'] = $canvas->get_page_number();
  //     $data['jml_halaman'] = $canvas->get_page_count();

  //     $dompdf->loadHtml(view('sipepes/dokumen_sop_pdf', $data));
  //     $dompdf->setPaper('A4', 'portrait');
  //     $dompdf->render();
  //     $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
  //   } catch (Exception $e) {
  //     // Display or log the error message
  //     echo "Error: " . $e->getMessage();
  //   }
  // }
  
  public function uklwSkriningTerintegrasi()
  {
    $filename = date('d-m-y-H-i-s') . '-Formulir_Data_Skrining_Terintegrasi';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();

    $id = $this->request->getVar("id");

    // Pertama ambil data pasien dasar untuk memastikan semua kasus ditangani
    $data['pasien'] = $this->uklwPasienModel
      ->where('id_uklw_pasien', $id)
      ->first();

    if (!$data['pasien']) {
      // Jika data pasien tidak ditemukan, tampilkan error
      $data['error'] = 'Data pasien tidak ditemukan';
      $dompdf->loadHtml(view('uklw/pdf/error', $data));
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
      $dompdf->stream($filename, array("Attachment" => true));
      return;
    }

    // Cek apakah ada data pemkes (untuk pasien yang diinput dari admin)
    $data['pemkes'] = $this->uklwPasienModel
      ->select("uklw_pemkes.*, uklw_pasien.*, uklw_catatan_pemeriksaan.*, data_pegawai.nama as nama_dokter, data_pegawai.nip as nip_dokter, uklw_pemkes.updated_at as pemkes_updated_at, uklw_pasien.updated_at as pasien_updated_at, uklw_catatan_pemeriksaan.updated_at as catatan_pemeriksaan_updated_at")
      ->join('uklw_pemkes', 'id_uklw_pasien', 'left')
      ->join('uklw_catatan_pemeriksaan', 'id_uklw_pemkes', 'left')
      ->join('data_pegawai', 'uklw_catatan_pemeriksaan.id_data_pegawai_dokter=data_pegawai.id_data_pegawai', "left")
      ->where('uklw_pasien.id_uklw_pasien', $id)
      ->orderBy('uklw_pemkes.updated_at', 'desc')
      ->first();

    $data['skriningTBC'] = $this->uklwPasienModel
      ->select("*, uklw_skrining_tbc.updated_at as updated_at")
      ->join('uklw_skrining_tbc', 'id_uklw_pasien')
      ->where('uklw_pasien.id_uklw_pasien', $id)
      ->orderBy('uklw_skrining_tbc.updated_at', 'desc')
      ->first();

    $data['skriningHIV'] = $this->uklwPasienModel
      ->select("*, uklw_skrining_hiv.updated_at as updated_at")
      ->join('uklw_skrining_hiv', 'id_uklw_pasien')
      ->where('uklw_pasien.id_uklw_pasien', $id)
      ->orderBy('uklw_skrining_hiv.updated_at', 'desc')
      ->first();

    $data['faktorRisikoPTM'] = $this->uklwPasienModel
      ->select("*, uklw_faktor_risiko_ptm.updated_at as updated_at")
      ->join('uklw_faktor_risiko_ptm', 'id_uklw_pasien')
      ->where('uklw_pasien.id_uklw_pasien', $id)
      ->orderBy('uklw_faktor_risiko_ptm.updated_at', 'desc')
      ->first();

    $data['laboratorium'] = $this->uklwPasienModel
      ->select("*, uklw_laboratorium.updated_at as laboratorium_updated_at, uklw_pasien.updated_at as pasien_updated_at")
      ->join('uklw_laboratorium', 'id_uklw_pasien')
      ->where('uklw_pasien.id_uklw_pasien', $id)
      ->orderBy('uklw_laboratorium.updated_at', 'desc')
      ->first();

    $imagePath = 'karakter/ceklis-line-white.png';
    $imageData = file_get_contents($imagePath);
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    $base64Image = base64_encode($imageData);
    $data += ['checked' => '<img style="width:30%; margin:0; padding:0;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];

    $profil_kantor = array();
    // Cek apakah data pemkes ada (untuk input dari admin) atau gunakan data pasien (untuk input dari publik)
    if (isset($data['pemkes']) && $data['pemkes']) {
      $created_at = $data['pemkes']['pemkes_updated_at'] ?? $data['pemkes']['pasien_updated_at'];
      $profil_kantor = $this->profilKantorModel
        ->where('tertanggal <=', $created_at)
        ->orderBy('tertanggal', 'DESC')
        ->first();
    } else {
      // Gunakan tanggal update pasien untuk form publik
      $created_at = $data['pasien']['updated_at'];
      $profil_kantor = $this->profilKantorModel
        ->where('tertanggal <=', $created_at)
        ->orderBy('tertanggal', 'DESC')
        ->first();
    }
    $data['profil_kantor'] = $profil_kantor;

    $data['riwayatPenyakit'] = $this->uklwRiwayatPenyakitModel->findAll();


    $data['kop'] = generate_base64('dist/img/Kop_BKK_bandung.png');
    // dd($data);
    $imagePath = 'karakter/ceklis-line-white.png';
    $imageData = file_get_contents($imagePath);
    $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
    $base64Image = base64_encode($imageData);
    $data += ['checked' => '<img style="width:110%; margin:0; padding:0;" src="data:image/' . $imageExtension . ';base64,' . $base64Image . '" />'];

    $data['getICDS'] = array();
    $getICDS = false;
    
    // Cek apakah data pemkes ada dan memiliki assesment (untuk input dari admin)
    if(isset($data['pemkes']) && $data['pemkes'] && isset($data['pemkes']['assesment'])) {
        $getICDS = unserialize($data['pemkes']['assesment']);
        
        $setICDS = array();
        if($getICDS) {
            $setICDS = explode(",", $getICDS['assesment_diagnosa_medis']);
        }
        
        $icdsIsTrue = false;
        foreach($setICDS as $findICDS) {
            $icdsIsTrue = true;
            $this->uklwICDSModel->orWhere('code', $findICDS);
        }
        
        if($icdsIsTrue) {
            $data["getICDS"] = $this->uklwICDSModel->findAll();
        }
    }

    // echo '<img src="/karakter/ceklis-line-white.svg">';
    // echo $data['checked'];

    // Pastikan data pasien tersedia di template
    if (!isset($data['pasien']) && isset($data['pemkes']) && $data['pemkes']) {
        $data['pasien'] = $data['pemkes'];
    }
    
    // Untuk debugging jika diperlukan
    // return view('uklw/pdf/skrining_terintegrasi', $data);
    
    $dompdf->loadHtml(view('uklw/pdf/skrining_terintegrasi', $data));

    // $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
  }
  
  // PDF KESLING =====================================================================================================================
  public function prl_print_inspeksi_tpp($id = '')
  {
    $filename = 'inspeksi-tpp';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    // Set opsi agar mendukung Unicode
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');

    $data['tpp'] = $this->prlInspeksiTppModel
      ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_tpp.nama_tpp as nama_tpp, data_pegawai.nama as nama_pemeriksa, prl_inspeksi_tpp.*')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_inspeksi_tpp.id_wilayah_kerja')
      ->join('prl_tpp', 'prl_tpp.id_prl_tpp = prl_inspeksi_tpp.id_prl_tpp')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = prl_inspeksi_tpp.id_data_pegawai')
      ->where('id_prl_inspeksi_tpp', $id)
      ->first();

    $dompdf->loadHtml(view('prl/tpp_inspeksi_pdf', $data));

    $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper($customPaper, 'portrait');
    $dompdf->render();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
    // return view('prl/tpp_inspeksi_pdf', $data);
  }

  public function prl_print_inspeksi_sab($id = '')
  {
    $filename = 'inspeksi-sab';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    // Set opsi agar mendukung Unicode
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');

    $data['sab'] = $this->prlInspeksiSabModel
      ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_sab.nama_sab as nama_sab, data_pegawai.nama as nama_pemeriksa, prl_inspeksi_sab.*')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_inspeksi_sab.id_wilayah_kerja')
      ->join('prl_sab', 'prl_sab.id_prl_sab = prl_inspeksi_sab.id_prl_sab')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = prl_inspeksi_sab.id_data_pegawai')
      ->where('id_prl_inspeksi_sab', $id)
      ->first();

    $dompdf->loadHtml(view('prl/sab_inspeksi_pdf', $data));

    $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper($customPaper, 'portrait');
    $dompdf->render();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));
    // return view('prl/sab_inspeksi_pdf', $data);
  }

  public function prl_print_inspeksi_hsbu($id = '')
  {
    $filename = 'inspeksi-hsbu';
    // instantiate and use the dompdf class
    $dompdf = new Dompdf();
    // Set opsi agar mendukung Unicode
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');

    $data['hsbu'] = $this->prlInspeksiHsbuModel
      ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_hsbu.nama_gedung as nama_hsbu, data_pegawai.nama as nama_pemeriksa, prl_inspeksi_hsbu.*')
      ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_inspeksi_hsbu.id_wilayah_kerja')
      ->join('prl_hsbu', 'prl_hsbu.id_prl_hsbu = prl_inspeksi_hsbu.id_prl_hsbu')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = prl_inspeksi_hsbu.id_data_pegawai')
      ->where('id_prl_inspeksi_hsbu', $id)
      ->first();

    $data['lingkungan_luar_halaman'] = unserialize($data['hsbu']['lingkungan_luar_halaman']);
    $data['ruang_bangunan'] = unserialize($data['hsbu']['ruang_bangunan']);
    $data['penyehatan_air'] = unserialize($data['hsbu']['penyehatan_air']);
    $data['penyehatan_ruang_udara'] = unserialize($data['hsbu']['penyehatan_ruang_udara']);
    $data['pengelolaan_limbah'] = unserialize($data['hsbu']['pengelolaan_limbah']);
    $data['pencahayaan'] = unserialize($data['hsbu']['pencahayaan']);
    $data['kebisingan_pada_ruang'] = unserialize($data['hsbu']['kebisingan_pada_ruang']);
    $data['getaran_di_ruang_kerja'] = unserialize($data['hsbu']['getaran_di_ruang_kerja']);
    $data['pengendalian_vektor_penyakit'] = unserialize($data['hsbu']['pengendalian_vektor_penyakit']);
    $data['instalasi'] = unserialize($data['hsbu']['instalasi']);
    $data['pemeliharaan_jamban_dan_kamar_mandi'] = unserialize($data['hsbu']['pemeliharaan_jamban_dan_kamar_mandi']);

    $dompdf->loadHtml(view('prl/hsbu_inspeksi_pdf', $data));

    $customPaper = array(0, 0, 595, 935);
    $dompdf->setPaper($customPaper, 'portrait');
    $dompdf->render();

    // output the generated pdf
    $dompdf->stream($filename, array("Attachment" => true));

    // return view('prl/hsbu_inspeksi_pdf', $data);
  }
  // PDF KESLING =====================================================================================================================
  
  public function absQRCode($file)
  {
    $dompdf = new Dompdf();

    $data['img'] = generate_base64('dist/img/logo-kesehatan.png');

    $getData = $this->absExportLabelModel->where('file', $file)->first();

    $data['getBarang'] = $this->absLokasiModel
      ->where('id_abs_bangunan >=', $getData['id_awal'])
      ->where('id_abs_bangunan <=', $getData['id_akhir'])
      ->findAll();

    $data['kertas'] = $getData['kertas'];
    if ($getData['kertas'] == "1m") {
      $data['perBaris'] = 21;
    } else {
      $data['perBaris'] = 4;
    }
    
    $dompdf->loadHtml(view('abs/pdf/stiker-qr', $data));

    if ($getData['kertas'] == "1m") {
      $customPaper = array(0, 0, 2834, 2834);
      $dompdf->setPaper($customPaper);
    } else {
      $dompdf->setPaper('A4', 'portrait');
    }

    $dompdf->render();


    $nama_file = $file;
    $pdfFilePath = WRITEPATH . 'uploads/' . $nama_file;
    $fileWritten = file_put_contents($pdfFilePath, $dompdf->output());

    if ($fileWritten !== false) {
      if (file_exists($pdfFilePath)) {
        return response()->download($pdfFilePath, null);
        exit();
      } else {
        return 'Gagal membuat file PDF.';
      }
    } else {
      return 'Failed to write PDF file.';
    }
  }
  
  
  public function absGetQRCode()
  {
    $dompdf = new Dompdf();

    $data['img'] = generate_base64('dist/img/logo-kesehatan.png');

    $kertas = $this->request->getVar("kertas");
    $data['kertas']=$kertas;
    $id_unit_kerja = $this->request->getVar("unit_kerja");
    $id_abs_lantai = $this->request->getVar("lantai");
    $id_abs_ruang = $this->request->getVar("ruang");
    $id_abs_jenis = $this->request->getVar("jenis");

    $this->absLokasiModel->where('id_unit_kerja', $id_unit_kerja);
    $this->absLokasiModel->where('id_abs_lantai', $id_abs_lantai);
    if($id_abs_ruang != '')
        $this->absLokasiModel->where('id_abs_ruang', $id_abs_ruang);
    if($id_abs_jenis != '')
        $this->absLokasiModel->where('id_abs_jenis', $id_abs_jenis);
    
    $data['getBarang'] = $this->absLokasiModel->findAll();
    
    if ($kertas == "1m") {
      $data['perBaris'] = 16;
    } else {
      $data['perBaris'] = 3;
    }

    $dompdf->loadHtml(view('abs/pdf/stiker-qr', $data));

    if ($kertas == "1m") {
      $customPaper = array(0, 0, 2834, 2834);
      $dompdf->setPaper($customPaper);
    } else {
      $dompdf->setPaper('A4', 'portrait');
    }

    $dompdf->render();


    $nama_file = 'abs_file_' . date('d-m-y-H-i-s')  . '.pdf';
    $pdfFilePath = WRITEPATH . 'uploads/' . $nama_file;
    $fileWritten = file_put_contents($pdfFilePath, $dompdf->output());

    if ($fileWritten !== false) {
      if (file_exists($pdfFilePath)) {
        return response()->download($pdfFilePath, null);
        exit();
      } else {
        return 'Gagal membuat file PDF.';
      }
    } else {
      return 'Failed to write PDF file.';
    }
    exit();
  }
  
//   public function absQRCode()
//   {
//     $filename = date('d-m-y-H-i-s') . '-stiker-qr';
//     $img =  generate_base64('dist/img/logo-kesehatan.png');
//     $getData = $this->absLokasiModel->limit(10)->find();
//     // $data['getData'] = $this->absLokasiModel->findAll();
    
//     $kertas = $this->request->getVar("kertas");
//     // for($xyz=0; $xyz<3; $xyz++) {
//     //     if($xyz > 0 ){
//     //         sleep(20);
//     //     }
//         $filename = date('d-m-y-H-i-s') . '-stiker-qr(1)';
//         $this->loopAbsQRCode($img, $getData, $kertas, $filename);
//         $filename = date('d-m-y-H-i-s') . '-stiker-qr(2)';
//         $this->loopAbsQRCode($img, $getData, $kertas, $filename);
//         $filename = date('d-m-y-H-i-s') . '-stiker-qr(3)';
//         $this->loopAbsQRCode($img, $getData, $kertas, $filename);
//     // }
//   }
//   public function loopAbsQRCode($img, $getData, $kertas, $filename) {
//     $data['img'] = $img;
//     $data['getData'] = $getData;
//     $data['kertas'] = $kertas;
    
//     if ($data['kertas'] == "1m") {
//       $data['perBaris'] = 21;
//     } else {
//       $data['perBaris'] = 4;
//     }

//     $dompdf = new Dompdf();
//     $dompdf->loadHtml(view('abs/pdf/stiker-qr', $data));

//     if ($data['kertas'] == "1m") {
//       $customPaper = array(0, 0, 2834, 2834);
//       $dompdf->setPaper($customPaper);
//     } else {
//       // $customPaper = array(0, 0, 595, 935);
//       $dompdf->setPaper('A4', 'portrait');
//     }

//     $dompdf->render();
//     $dompdf->stream($filename, array("Attachment" => true));
//   }
    public function sinadiLembarVerifikasi($id) {
        $get = $this->sinadiLembarVerifikasiModel
          ->select('sinadi_lembar_verifikasi.*, sinadi_perjalanan_dinas.*, sinadi_perjalanan_dinas_peserta.*, sinadi_lembar_verifikasi.updated_by as sheet_updated_by')
          ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas.id_perjalanan=sinadi_lembar_verifikasi.id_perjalanan')
          ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas.id_perjalanan=sinadi_perjalanan_dinas_peserta.id_perjalanan')
          ->where('sinadi_lembar_verifikasi.id_perjalanan', $id)
          ->first();
          
        // Ambil data dari verifikator 1 (Verifikator Pertama)
        $verif_1_data = null;
        if ($get['verifikator_1'] != null) {
            $verif_1_arr = @unserialize($get['verifikator_1']);
            if (is_array($verif_1_arr) && !empty($verif_1_arr)) {
                $verif_1_data = end($verif_1_arr);
            }
        }

        if ($verif_1_data) {
            // Gunakan data Verifikator 1
            $by = [
                'nama' => $verif_1_data['nama'],
                'nip'  => $verif_1_data['nip']
            ];
            
            $imagePath = $verif_1_data['signature'];
            if (str_starts_with($imagePath, '/')) {
                $imagePath = substr($imagePath, 1);
            }
            $data['ttd_verif'] = generate_base64($imagePath);
        } else {
            // Fallback ke user yang sedang login
            $by = $this->dataPegawaiModel
              ->join('user', 'user.id_user=data_pegawai.id_user')
              ->where('user.username', session()->get('username'))
              ->first();
        }
        $checklist = generate_base64('karakter/ceklis-line-white.png');
        $data['checklist'] = '<img style="width: 15px; margin:0; padding:0;" src="'. $checklist .'" />';
        
        
        $data['get'] = $get;
        $data['by'] = $by;
        
        $filename = 'lembar verifikasi - (' . preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', $get['nama_kegiatan']) . ') - ' . ($get['tgl_pergi'] == $get['tgl_pulang'] ? $get['tgl_pergi'] : $get['tgl_pergi'] . " s.d" . $get['tgl_pulang']);
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('sinadi/sinadiLembarVerifikasi', $data));

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $dompdf->stream($filename, array("Attachment" => true));
        
        // dd($data);
    }
}
