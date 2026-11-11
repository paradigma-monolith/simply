<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class Home extends BaseController
{
    public function maintenance($date)
    {
        $data['dd'] = str_replace("-", " ", $date);

        return view('/maintenance/index', $data);
    }
    public function forbidden()
    {
        return view('/maintenance/forbidden');
    }
    function tgl_indo2($tanggal)
    {
        $bulan = array(
            1 => 'Januari',
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
        return $split[2] . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];
    }

    public function linimasa($segment = null)
    {
        $data['title'] = '<i class="fas fa-history mr2"></i>';
        $data['activeMenu'] = "";
        if ($segment == null) {
            $data['title'] = '<i class="fas fa-history mr2"></i> History dari semua';
            $data['linimasa'] = $this->linimasaModel
                ->select('user.nama, linimasa.*')
                ->join('user', 'user.id_user=linimasa.id_user')
                ->orderBy('created_at', 'DESC')->limit(200)->find();
        } elseif ($segment == 'infografis') {
            $data['title'] = '<i class="fas fa-history mr2"></i> History aktifitas Infografis Kapal, Pesawat, Vaksinasi';
            $data['linimasa'] = $this->linimasaModel
                ->select('user.nama, linimasa.*')
                ->join('user', 'user.id_user=linimasa.id_user')
                ->where('table', 'infografis_kapal')
                ->orWhere('table', 'infografis_pesawat')
                ->orWhere('table', 'infografis_vaksin')
                ->orderBy('created_at', 'DESC')->findAll();
        } elseif ($segment == 'login') {
            $data['title'] = '<i class="fas fa-history mr2"></i> History aktifitas pengguna yang Login ke Sistem';
            $data['linimasa'] = $this->linimasaModel
                ->select('user.nama, linimasa.*')
                ->join('user', 'user.id_user=linimasa.id_user')
                ->where('table', 'user')
                ->orderBy('created_at', 'DESC')->findAll();
        } else {
            $data['linimasa'] = [];
        }
        foreach ($data['linimasa'] as &$item) {
            $item['tanggal'] = $this->tgl_indo2(substr($item['created_at'], 0, 10));
            $item['jam'] = substr($item['created_at'], 11, 5);
        }

        return view('users/linimasa', $data);
    }
    public function index()
    {
        $data['title'] = "Sistem Informasi Balai Karkes Bandung";
        $data['activeMenu'] = "";

        $by = session()->get('username');
        $data['by'] = $this->userModel->where('username', $by)->first();
        $data['unit_kerja'] = $this->dataPegawaiModel->joinFirst(
            $select = '*',
            $with = 'unit_kerja',
            $value = 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja',
            $where = ['id_user' => $data['by']['id_user']],
            $orderBy = '',
            $order = '',
            $group = '',
            $limit = ''
        )->first();
        if ($data['unit_kerja']) {
            $tanggal_sekarang = date("Y-m-d");
            $tanggal_masuk = $data['unit_kerja']['tgl_awal_masa'];
            $selisih_hari = (strtotime($tanggal_sekarang) - strtotime($tanggal_masuk)) / (60 * 60 * 24);
            if ($selisih_hari != $data['unit_kerja']['masa_kerja']) {
                $update = [
                    'id_data_pegawai' => $data['unit_kerja']['id_data_pegawai'],
                    'masa_kerja' => $selisih_hari,
                ];
                $data['unit_kerja']['masa_kerja'] = $selisih_hari;
                $this->dataPegawaiModel->save($update);
            }
        }
        $data['user'] = $this->dataPegawaiModel->select('*', 'user.name')
            ->where('user.username', session()->get('username'))
            ->join('user', 'user.id_user=data_pegawai.id_user', "right")
            ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan', 'left')
            ->join('tim_kerja', 'id_tim_kerja', 'left')
            ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan', 'left')
            ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi', 'left')
            ->first();

        $environment = getenv('CI_ENVIRONMENT');

        $data['environment'] = false;
        if ($environment === 'development' && session()->get('role') == "Admin") {
            $data['environment'] = true;
        }
        return view('users/admin', $data);
    }
    public function about()
    {
        d("Hallo");
    }
    function kategori_angka($angka)
    {
        $kategori = array("", "ribu", "juta", "milyar", "triliun", "quadrillion", "quintillion", "sextillion", "septillion", "octillion", "nonillion", "decillion");
        $i = 0;
        $nilai = $angka;
        while ($angka >= 1000) {
            $angka /= 1000;
            $i++;
        }
        if ($nilai < 1000) {
            return number_format($angka, 0, ',', '.') . ' ' . $kategori[$i];
        } elseif ($nilai > 99999999999999999999999999999999999) {
            return "&#8734;";
        } else {
            return number_format($angka, 2, ',', '.') . ' ' . $kategori[$i];
        }
    }
    public function dashboard()
    {
        $data['title'] = 'Dashboard';
        $data['activeMenu'] = 'Dashboard';
        // ====================================

        $data['sipeditKategori'] = $this->sipeditArsipKategoriModel->findAll();

        $data['unitKerja'] = $this->unitKerjaModel->findAll();
        return view('dashboard/index', $data);
    }
    function getSumInfografisPesawat($date_awal, $date_akhir, $wilker)
    {
        if ($wilker == 'all')
            return $this->infografisPesawatModel->select('
            SUM(pesawat_datang_negeri) AS pesawat_datang_negeri_pesawat_total,
            SUM(pesawat_datang_luar) AS pesawat_datang_luar_pesawat_total,
            SUM(pesawat_berangkat_negeri) AS pesawat_berangkat_negeri_pesawat_total,
            SUM(pesawat_berangkat_luar) AS pesawat_berangkat_luar_pesawat_total,
            SUM(crew_datang_negeri) AS crew_datang_negeri_pesawat_total,
            SUM(crew_datang_luar) AS crew_datang_luar_pesawat_total,
            SUM(crew_berangkat_negeri) AS crew_berangkat_negeri_pesawat_total,
            SUM(crew_berangkat_luar) AS crew_berangkat_luar_pesawat_total,
            SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_pesawat_total,
            SUM(penumpang_datang_luar) AS penumpang_datang_luar_pesawat_total,
            SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_pesawat_total,
            SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_pesawat_total,
            date(created_at) AS created_at_pesawat_total,
            date(updated_at) AS updated_at_pesawat_total
            ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->first();
        else
            return $this->infografisPesawatModel->select('
            SUM(pesawat_datang_negeri) AS pesawat_datang_negeri_pesawat_total,
            SUM(pesawat_datang_luar) AS pesawat_datang_luar_pesawat_total,
            SUM(pesawat_berangkat_negeri) AS pesawat_berangkat_negeri_pesawat_total,
            SUM(pesawat_berangkat_luar) AS pesawat_berangkat_luar_pesawat_total,
            SUM(crew_datang_negeri) AS crew_datang_negeri_pesawat_total,
            SUM(crew_datang_luar) AS crew_datang_luar_pesawat_total,
            SUM(crew_berangkat_negeri) AS crew_berangkat_negeri_pesawat_total,
            SUM(crew_berangkat_luar) AS crew_berangkat_luar_pesawat_total,
            SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_pesawat_total,
            SUM(penumpang_datang_luar) AS penumpang_datang_luar_pesawat_total,
            SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_pesawat_total,
            SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_pesawat_total,
            date(created_at) AS created_at_pesawat_total,
            date(updated_at) AS updated_at_pesawat_total
            ')->where('unit_kerja', $wilker)
                ->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->first();
    }
    function getSumInfografisKapal($date_awal, $date_akhir, $wilker)
    {
        if ($wilker == 'all')
            return $this->infografisKapalModel->select('
            SUM(kapal_datang_negeri) AS kapal_datang_negeri_kapal_total,
            SUM(kapal_datang_luar) AS kapal_datang_luar_kapal_total,
            SUM(kapal_berangkat_negeri) AS kapal_berangkat_negeri_kapal_total,
            SUM(kapal_berangkat_luar) AS kapal_berangkat_luar_kapal_total,
            SUM(abk_datang_negeri) AS abk_datang_negeri_kapal_total,
            SUM(abk_datang_luar) AS abk_datang_luar_kapal_total,
            SUM(abk_berangkat_negeri) AS abk_berangkat_negeri_kapal_total,
            SUM(abk_berangkat_luar) AS abk_berangkat_luar_kapal_total,
            SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_kapal_total,
            SUM(penumpang_datang_luar) AS penumpang_datang_luar_kapal_total,
            SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_kapal_total,
            SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_kapal_total,
            date(created_at) AS created_at_kapal_total,
            date(updated_at) AS updated_at_kapal_total
            ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->first();
        else
            return $this->infografisKapalModel->select('
            SUM(kapal_datang_negeri) AS kapal_datang_negeri_kapal_total,
            SUM(kapal_datang_luar) AS kapal_datang_luar_kapal_total,
            SUM(kapal_berangkat_negeri) AS kapal_berangkat_negeri_kapal_total,
            SUM(kapal_berangkat_luar) AS kapal_berangkat_luar_kapal_total,
            SUM(abk_datang_negeri) AS abk_datang_negeri_kapal_total,
            SUM(abk_datang_luar) AS abk_datang_luar_kapal_total,
            SUM(abk_berangkat_negeri) AS abk_berangkat_negeri_kapal_total,
            SUM(abk_berangkat_luar) AS abk_berangkat_luar_kapal_total,
            SUM(penumpang_datang_negeri) AS penumpang_datang_negeri_kapal_total,
            SUM(penumpang_datang_luar) AS penumpang_datang_luar_kapal_total,
            SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri_kapal_total,
            SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar_kapal_total,
            date(created_at) AS created_at_kapal_total,
            date(updated_at) AS updated_at_kapal_total
            ')->where('unit_kerja', $wilker)
                ->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->first();
    }
    function getSumInfografisVaksin($date_awal, $date_akhir, $wilker)
    {
        if ($wilker == 'all')
            return $this->infografisVaksinModel->select('
            SUM(phqc) AS phqc,
            SUM(cop) AS cop,
            SUM(sscec) AS sscec,
            SUM(sscc) AS sscc,
            SUM(p3k) AS p3k,
            SUM(icv) AS icv,
            SUM(sklt) AS sklt,
            SUM(siaos) AS siaos,
            SUM(siaj) AS siaj,
            SUM(kir) AS kir,
            SUM(bukes) AS bukes,
            SUM(vaksin_mm) AS vaksin_mm,
            SUM(vaksin_yf) AS vaksin_yf,
            SUM(vaksin_covid) AS vaksin_covid,
            SUM(vaksin_influenza) AS vaksin_influenza,
            SUM(vaksin_tifoid) AS vaksin_tifoid,
            SUM(vaksin_polio) AS vaksin_polio,
            date(created_at) AS created_at_vaksin_total,
            date(updated_at) AS updated_at_vaksin_total,
            ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->first();
        else
            return $this->infografisVaksinModel->select('
            SUM(phqc) AS phqc,
            SUM(cop) AS cop,
            SUM(sscec) AS sscec,
            SUM(sscc) AS sscc,
            SUM(p3k) AS p3k,
            SUM(icv) AS icv,
            SUM(sklt) AS sklt,
            SUM(siaos) AS siaos,
            SUM(siaj) AS siaj,
            SUM(kir) AS kir,
            SUM(bukes) AS bukes,
            SUM(vaksin_mm) AS vaksin_mm,
            SUM(vaksin_yf) AS vaksin_yf,
            SUM(vaksin_covid) AS vaksin_covid,
            SUM(vaksin_influenza) AS vaksin_influenza,
            SUM(vaksin_tifoid) AS vaksin_tifoid,
            SUM(vaksin_polio) AS vaksin_polio,
            date(created_at) AS created_at_vaksin_total,
            date(updated_at) AS updated_at_vaksin_total,
            ')->where('unit_kerja', $wilker)
                ->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->first();
    }

    function getInfografisPesawat($date_awal, $date_akhir, $wilker)
    {
        if ($wilker == 'all')
            return $this->infografisPesawatModel
                ->select('            
                date(created_at) AS tanggal,
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
                ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
                ->groupBy('tanggal')
                ->findAll();
        else
            return $this->infografisPesawatModel
                ->select('
                date(created_at) AS tanggal,
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
                ')->where('unit_kerja', $wilker)
                ->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
                ->groupBy('tanggal')
                ->findAll();
    }
    function getInfografisKapal($date_awal, $date_akhir, $wilker)
    {
        if ($wilker == 'all')
            return $this->infografisKapalModel
                ->select('
                date(created_at) AS tanggal,
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
                ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
                ->groupBy('tanggal')
                ->findAll();
        else
            return $this->infografisKapalModel->where('unit_kerja', $wilker)
                ->select('
                date(created_at) AS tanggal,
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
                ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
                ->groupBy('tanggal')
                ->findAll();
    }
    function getInfografisVaksin($date_awal, $date_akhir, $wilker)
    {
        if ($wilker == 'all')
            return $this->infografisVaksinModel
                ->select('
                date(created_at) AS tanggal,
                SUM(phqc) AS phqc,
                SUM(cop) AS cop,
                SUM(sscec) AS sscec,
                SUM(sscc) AS sscc,
                SUM(p3k) AS p3k,
                SUM(icv) AS icv,
                SUM(sklt) AS sklt,
                SUM(siaos) AS siaos,
                SUM(siaj) AS siaj,
                SUM(kir) AS kir,
                SUM(bukes) AS bukes,
                SUM(vaksin_mm) AS vaksin_mm,
                SUM(vaksin_yf) AS vaksin_yf,
                SUM(vaksin_covid) AS vaksin_covid,
                SUM(vaksin_influenza) AS vaksin_influenza,
                SUM(vaksin_tifoid) AS vaksin_tifoid,
                SUM(vaksin_polio) AS vaksin_polio,
                ')
                ->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
                ->groupBy('tanggal')
                ->findAll();
        else
            return $this->infografisVaksinModel
                ->select('
                date(created_at) AS tanggal,
                SUM(phqc) AS phqc,
                SUM(cop) AS cop,
                SUM(sscec) AS sscec,
                SUM(sscc) AS sscc,
                SUM(p3k) AS p3k,
                SUM(icv) AS icv,
                SUM(sklt) AS sklt,
                SUM(siaos) AS siaos,
                SUM(siaj) AS siaj,
                SUM(kir) AS kir,
                SUM(bukes) AS bukes,
                SUM(vaksin_mm) AS vaksin_mm,
                SUM(vaksin_yf) AS vaksin_yf,
                SUM(vaksin_covid) AS vaksin_covid,
                SUM(vaksin_influenza) AS vaksin_influenza,
                SUM(vaksin_tifoid) AS vaksin_tifoid,
                SUM(vaksin_polio) AS vaksin_polio,
                ')
                ->where('unit_kerja', $wilker)
                ->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
                ->groupBy('tanggal')
                ->findAll();
    }
    public function getInfografis($wilker, $jenis, $periode)
    {
        if ($this->request->isAJAX()) {

            $date = $periode;

            $date_awal = substr($date, 0, 10) . ' 00:00:00';
            $date_akhir = substr($date, 13, 23) . ' 23:59:00';
            $view = '';
            if ($jenis != 'all') {
                if ($jenis == 'dokumen') {
                    $data['infografis'] = $this->getSumInfografisVaksin($date_awal, $date_akhir, $wilker);
                    $data['dataVaksin'] = $this->getInfografisVaksin($date_awal, $date_akhir, $wilker);
                    $view = view('dashboard/infografis/dokumen', $data);
                }
                if ($jenis == 'vaksin') {
                    $data['infografis'] = $this->getSumInfografisVaksin($date_awal, $date_akhir, $wilker);
                    $data['dataVaksin'] = $this->getInfografisVaksin($date_awal, $date_akhir, $wilker);
                    $view = view('dashboard/infografis/vaksin', $data);
                }
                if ($jenis == 'pesawat') {
                    $data['infografis'] = $this->getSumInfografisPesawat($date_awal, $date_akhir, $wilker);
                    $data['dataPesawat'] = $this->getInfografisPesawat($date_awal, $date_akhir, $wilker);
                    $view = view('dashboard/infografis/pesawat', $data);
                }
                if ($jenis == 'kapal') {
                    $data['infografis'] = $this->getSumInfografisKapal($date_awal, $date_akhir, $wilker);
                    $data['dataKapal'] = $this->getInfografisKapal($date_awal, $date_akhir, $wilker);
                    $view = view('dashboard/infografis/kapal', $data);
                }
            } else {
                $data['infografis'] = $this->getSumInfografisKapal($date_awal, $date_akhir, $wilker);
                $data['infografis'] += $this->getSumInfografisPesawat($date_awal, $date_akhir, $wilker);
                $data['infografis'] += $this->getSumInfografisVaksin($date_awal, $date_akhir, $wilker);
                $data['dataKapal'] = $this->getInfografisKapal($date_awal, $date_akhir, $wilker);
                $data['dataPesawat'] = $this->getInfografisPesawat($date_awal, $date_akhir, $wilker);
                $data['dataVaksin'] = $this->getInfografisVaksin($date_awal, $date_akhir, $wilker);
                $view = view('dashboard/infografis/kapal', $data) .
                    view('dashboard/infografis/pesawat', $data) .
                    view('dashboard/infografis/dokumen', $data) .
                    view('dashboard/infografis/vaksin', $data);
            }

            $msg = [
                'data' => $view
            ];
            echo json_encode($msg);
        } else {
            return redirect()->to('home/dashboard');
        }
    }
    public function getSipedit($periode, $bulan)
    {
        if ($this->request->isAJAX()) {
            $tahunIni = $periode;
            if ($periode == "all") {
                $tahunIni = "Semua Periode";
                $data = [
                    'total_jpl' => $this->sipeditArsipModel
                        ->select("count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
                        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
                        ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->first(),
                    'total_jpl_per_kategori' => $this->sipeditArsipModel
                        ->select("sipedit_kategori.kategori_nama, count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
                        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
                        ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->groupBy('sipedit_arsip.id_sipedit_kategori')
                        ->findAll(),
                    'memenuhi' => $this->dataPegawaiModel
                        // ->select("SUM(sipedit_arsip.arsip_jpl) AS total_arsip_jpl")
                        ->select("data_pegawai.*, 
                          (SELECT COALESCE(SUM(sipedit_arsip.arsip_jpl), 0) 
                           FROM sipedit_arsip 
                           WHERE data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai) AS total_arsip_jpl")
                        ->join('sipedit_arsip', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->having("total_arsip_jpl >= 20")
                        ->groupBy('sipedit_arsip.id_data_pegawai')
                        ->findAll(),
                    'belum_memenuhi' => $this->dataPegawaiModel
                        ->select("data_pegawai.*, 
                      (SELECT COALESCE(SUM(sipedit_arsip.arsip_jpl), 0) 
                       FROM sipedit_arsip 
                       WHERE data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai) AS total_arsip_jpl")
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->having("total_arsip_jpl < 20 OR total_arsip_jpl IS NULL")
                        ->orderBy('total_arsip_jpl DESC')
                        ->findAll(),
                    'pelatihan_jafung_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'pelatihan_jafung_belum' => $this->dataPegawaiModel
                        ->select('data_pegawai.*, jabatan.jabatan as nama_jabatan')
                        ->join('jabatan', 'jabatan.id_jabatan = data_pegawai.id_jabatan', 'left')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_pns_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "2")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_pns_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "2")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_pppk_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_pppk_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_nakes_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi !=', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_nakes_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi !=', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_nonnakes_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_nonnakes_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll()
                ];
            } else {
                $data = [
                    'total_jpl' => $this->sipeditArsipModel
                        ->select("count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
                        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
                        ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where("YEAR(sipedit_arsip.arsip_tanggal)", $tahunIni)
                        // ->where("MONTH(sipedit_arsip.arsip_tanggal)", $bulan)
                        ->where("MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan")
                        ->first(),
                    'total_jpl_per_kategori' => $this->sipeditArsipModel
                        ->select("sipedit_kategori.kategori_nama, count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
                        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
                        ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where("YEAR(sipedit_arsip.arsip_tanggal)", $tahunIni)
                        // ->where("MONTH(sipedit_arsip.arsip_tanggal)", $bulan)
                        ->where("MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan")
                        ->groupBy('sipedit_arsip.id_sipedit_kategori')
                        ->findAll(),
                    'memenuhi' => $this->dataPegawaiModel
                        // ->select("SUM(sipedit_arsip.arsip_jpl) AS total_arsip_jpl")
                        ->select("data_pegawai.*, 
                                  (SELECT COALESCE(SUM(sipedit_arsip.arsip_jpl), 0) 
                                   FROM sipedit_arsip 
                                   WHERE data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai 
                                     AND YEAR(sipedit_arsip.arsip_tanggal) = $tahunIni
                                     AND MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan) AS total_arsip_jpl")
                        ->join('sipedit_arsip', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where("YEAR(sipedit_arsip.arsip_tanggal)", $tahunIni)
                        // ->where("MONTH(sipedit_arsip.arsip_tanggal)", $bulan)
                        ->where("MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan")
                        ->having("total_arsip_jpl >= 20")
                        ->groupBy('sipedit_arsip.id_data_pegawai')
                        ->findAll(),
                    // 'belum_memenuhi' => $this->dataPegawaiModel
                    //     ->select("data_pegawai.*, 
                    //   (SELECT COALESCE(SUM(sipedit_arsip.arsip_jpl), 0) 
                    //   FROM sipedit_arsip 
                    //   WHERE data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai 
                    //      AND YEAR(sipedit_arsip.arsip_tanggal) = $tahunIni) AS total_arsip_jpl")
                    //     ->notLike('data_pegawai.nip', '0000%')
                    //     ->having("total_arsip_jpl < 20 OR total_arsip_jpl IS NULL")
                    //     ->orderBy('total_arsip_jpl DESC')
                    //     ->findAll()
                    'belum_memenuhi' => $this->dataPegawaiModel
                        ->select("data_pegawai.*, 
                                  (SELECT COALESCE(SUM(sipedit_arsip.arsip_jpl), 0) 
                                   FROM sipedit_arsip 
                                   WHERE data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai 
                                     AND YEAR(sipedit_arsip.arsip_tanggal) = $tahunIni
                                     AND MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan) AS total_arsip_jpl")
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->having("total_arsip_jpl < 20 OR total_arsip_jpl IS NULL")
                        ->orderBy('total_arsip_jpl DESC')
                        ->findAll(),
                    'pelatihan_jafung_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'pelatihan_jafung_belum' => $this->dataPegawaiModel
                        ->select('data_pegawai.*, jabatan.jabatan as nama_jabatan')
                        ->join('jabatan', 'jabatan.id_jabatan = data_pegawai.id_jabatan', 'left')
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_pns_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "2")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_pns_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "2")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_pppk_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_pppk_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai', "3")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_nakes_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi !=', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_nakes_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi !=', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll(),
                    'jafung_nonnakes_sudah' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Sudah')
                        ->findAll(),
                    'jafung_nonnakes_belum' => $this->dataPegawaiModel
                        ->where('data_pegawai.id_status_pegawai >=', "2")
                        ->where('data_pegawai.id_status_pegawai <=', "3")
                        ->where('data_pegawai.id_substansi', "5")
                        ->notLike('data_pegawai.nip', '0000%')
                        ->where('data_pegawai.status_bekerja', 'Aktif')
                        ->where('pelatihan_jafung_status', 'Belum')
                        ->findAll()
                ];
            }

            // Periksa & buat tabel pengaturan jika belum ada
            $db = \Config\Database::connect();
            if (!$db->tableExists('sipedit_harga_satuan')) {
                $db->query("CREATE TABLE sipedit_harga_satuan (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    kategori VARCHAR(50),
                    jabatan VARCHAR(100),
                    harga INT,
                    link_referensi TEXT NULL
                )");
                
                $nakes = [
                    'Perawat' => 1700000,
                    'Dokter' => 4000000,
                    'Epidemiolog' => 4000000,
                    'Sanitarian' => 2300000,
                    'Pembimbing Kesehatan Kerja' => 1800000,
                    'Administrator Kesehatan' => 3800000,
                    'Laboratorium Kesehatan' => 4000000,
                ];
                $non_nakes = [
                    'Keuangan' => 4500000,
                    'Perencana' => 4500000,
                    'Anggaran' => 4000000,
                    'Sumber Daya Manusia' => 5199000,
                    'Arsiparis' => 6790000,
                    'Hubungan Masyarakat' => 3000000,
                    'Pranata Komputer' => 4000000,
                ];
                foreach ($nakes as $jabatan => $harga) {
                    $db->query("INSERT INTO sipedit_harga_satuan (kategori, jabatan, harga, link_referensi) VALUES ('Nakes', ?, ?, '#')", [$jabatan, $harga]);
                }
                foreach ($non_nakes as $jabatan => $harga) {
                    $db->query("INSERT INTO sipedit_harga_satuan (kategori, jabatan, harga, link_referensi) VALUES ('Non Nakes', ?, ?, '#')", [$jabatan, $harga]);
                }
            } else {
                // Pastikan kolom link_referensi ada pada tabel sipedit_harga_satuan
                $fields = $db->getFieldNames('sipedit_harga_satuan');
                if (!in_array('link_referensi', $fields)) {
                    $db->query("ALTER TABLE sipedit_harga_satuan ADD COLUMN link_referensi TEXT NULL AFTER harga");
                    $db->query("UPDATE sipedit_harga_satuan SET link_referensi = '#'");
                }
            }
            
            if (!$db->tableExists('sipedit_pengaturan')) {
                $db->query("CREATE TABLE sipedit_pengaturan (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nama_pengaturan VARCHAR(100),
                    nilai_pengaturan TEXT
                )");
                $db->query("INSERT INTO sipedit_pengaturan (nama_pengaturan, nilai_pengaturan) VALUES ('link_referensi_anggaran', '#')");
            }
            
            $hargaSatuanData = $db->table('sipedit_harga_satuan')->get()->getResultArray();
            $nakes_prices = [];
            $non_nakes_prices = [];
            $nakes_links = [];
            $non_nakes_links = [];
            foreach ($hargaSatuanData as $row) {
                if ($row['kategori'] == 'Nakes') {
                    $nakes_prices[$row['jabatan']] = $row['harga'];
                    $nakes_links[$row['jabatan']] = $row['link_referensi'];
                } else {
                    $non_nakes_prices[$row['jabatan']] = $row['harga'];
                    $non_nakes_links[$row['jabatan']] = $row['link_referensi'];
                }
            }
            
            $data['nakes_prices'] = $nakes_prices;
            $data['non_nakes_prices'] = $non_nakes_prices;
            $data['nakes_links'] = $nakes_links;
            $data['non_nakes_links'] = $non_nakes_links;

            $by = $this->dataPegawaiModel
                ->select('id_data_pegawai')
                ->join('user', 'user.id_user=data_pegawai.id_user')
                ->where('user.username', session()->get('username'))
                ->first();
            if ($by)
                $data['arsip_saya'] = $this->sipeditArsipModel
                    ->select("SUM(sipedit_arsip.arsip_jpl) AS total_arsip_jpl, YEAR(sipedit_arsip.arsip_tanggal) AS year")
                    ->where("sipedit_arsip.id_data_pegawai", $by['id_data_pegawai'])
                    ->groupBy('year')
                    ->orderBy('year DESC')
                    ->findAll();
            $data['tahun'] = $tahunIni;
            $data['periode_tahun'] = $periode;
            $data['bulan'] = $bulan;
            $msg = [
                'data' => view('dashboard/sipedit/index', $data)
            ];
            echo json_encode($msg);
        } else {
            return redirect()->to('home/dashboard');
        }
    }
    public function getSipepes()
    {
        if ($this->request->isAJAX()) {

            $data['substansi'] = [];
            $data['substansi'] += $this->sipepesModel
                ->select("count(unit_kerja) PKSE")->where('unit_kerja', 'PKSE')->first();
            $data['substansi'] += $this->sipepesModel
                ->select("count(unit_kerja) PRL")->where('unit_kerja', 'PRL')->first();
            $data['substansi'] += $this->sipepesModel
                ->select("count(unit_kerja) UKLW")->where('unit_kerja', 'UKLW')->first();
            $data['substansi'] += $this->sipepesModel
                ->select("count(unit_kerja) Adum")->where('unit_kerja', 'Adum')->first();
            $data['substansi'] += $this->sipepesModel
                ->select("count(unit_kerja) Lainnya")->where('unit_kerja', 'Lainnya')->first();

            $total = array_sum($data['substansi']);
            $persentaseArray = array();
            foreach ($data['substansi'] as $key => $value) {
                $persentase = ($value / $total) * 100;
                $persentaseArray[$key] = number_format($persentase, 2);
            }
            $data += array(
                'persentase' => $persentaseArray
            );


            $maxValue = max($data['persentase']);
            $selisih = 90 - $maxValue;

            $persentaseArray = array();
            foreach ($data['persentase'] as $key => $value) {
                $newValue = $value + $selisih;
                $persentaseArray[$key] = $newValue;
            }

            $data += array(
                'persentaseBar' => $persentaseArray
            );

            $data['substansi']['totalSopUtama'] = $data['substansi']['PKSE'] + $data['substansi']['PRL'] + $data['substansi']['UKLW'];
            $data['substansi']['totalSop'] = $data['substansi']['totalSopUtama'] + $data['substansi']['Adum'] + $data['substansi']['Lainnya'];

            $viewPage_1 = view('dashboard/sipepes/index', $data);

            $data = null;
            $data['substansi'] = [];
            $data['substansi']['Timker 1'] = $this->sipepesModel
                ->select("COUNT(unit_kerja) AS count")->where('unit_kerja', 'Timker 1')->first()['count'];
            $data['substansi']['Timker 2'] = $this->sipepesModel
                ->select("COUNT(unit_kerja) AS count")->where('unit_kerja', 'Timker 2')->first()['count'];
            $data['substansi']['Timker 3'] = $this->sipepesModel
                ->select("COUNT(unit_kerja) AS count")->where('unit_kerja', 'Timker 3')->first()['count'];
            $data['substansi']['Timker 4'] = $this->sipepesModel
                ->select("COUNT(unit_kerja) AS count")->where('unit_kerja', 'Timker 4')->first()['count'];
            $data['substansi']['Timker 5'] = $this->sipepesModel
                ->select("COUNT(unit_kerja) AS count")->where('unit_kerja', 'Timker 5')->first()['count'];
            $data['substansi']['Adum'] = $this->sipepesModel
                ->select("COUNT(unit_kerja) AS count")->where('unit_kerja', 'Adum')->first()['count'];
            $data['substansi']['Lainnya'] = $this->sipepesModel
                ->select("COUNT(unit_kerja) AS count")->where('unit_kerja', 'Lainnya')->first()['count'];

            $total = array_sum($data['substansi']);
            $persentaseArray = array();
            foreach ($data['substansi'] as $key => $value) {
                $persentase = ($value / $total) * 100;
                $persentaseArray[$key] = number_format($persentase, 2);
            }
            $data += array(
                'persentase' => $persentaseArray
            );


            $maxValue = max($data['persentase']);
            $selisih = 90 - $maxValue;

            $persentaseArray = array();
            foreach ($data['persentase'] as $key => $value) {
                $newValue = $value + $selisih;
                $persentaseArray[$key] = $newValue;
            }

            $data += array(
                'persentaseBar' => $persentaseArray
            );

            $data['substansi']['totalSopUtama'] = $data['substansi']['Timker 1'] + $data['substansi']['Timker 2'] + $data['substansi']['Timker 3'] + $data['substansi']['Timker 4'] + $data['substansi']['Timker 5'];
            $data['substansi']['totalSop'] = $data['substansi']['totalSopUtama'] + $data['substansi']['Adum'] + $data['substansi']['Lainnya'];

            $viewPage_2 = view('dashboard/sipepes/index_v2', $data);
            $msg = [
                'data' => $viewPage_2 . $viewPage_1
            ];
            echo json_encode($msg);
        } else {
            return redirect()->to('home/dashboard');
        }
    }

    public function getSicute()
    {
        if ($this->request->isAJAX()) {
            $data['getCuti'] = $this->cutiDataCutiModel
                ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
                ->findAll();
            $msg = [
                'data' => view('dashboard/sicute/index', $data)
            ];
            echo json_encode($msg);
        } else {
            return redirect()->to('home/dashboard');
        }
    }
    public function getListDataCuti($year)
    {
        if ($this->request->isAJAX()) {

            $data['penolakan'] = $this->cutiDataCutiModel
                ->select("count(*) as penolakan")
                ->where("id_cuti_status_pejabat", "4")
                ->orWhere("id_cuti_status_atasan", "4")
                ->orWhere("id_cuti_status_kepegawaian", "4")
                ->where("cuti_data_cuti.tanggal_awal LIKE '$year%'")
                ->first();

            $data['proses'] = $this->cutiDataCutiModel
                ->select("count(*) as proses")
                ->where("id_cuti_status_pejabat !=", "4")
                ->where("id_cuti_status_pejabat !=", "2")
                ->Where("id_cuti_status_atasan !=", "4")
                ->Where("id_cuti_status_atasan !=", "2")
                ->Where("id_cuti_status_kepegawaian !=", "4")
                ->Where("id_cuti_status_kepegawaian !=", "2")
                ->where("cuti_data_cuti.tanggal_awal LIKE '$year%'")
                ->first();

            $terkonfirmasi = $this->cutiDataCutiModel
                ->where("id_cuti_status_kepegawaian", "2")
                ->where("id_cuti_status_atasan", "2")
                ->where("id_cuti_status_pejabat", "2")
                ->where("cuti_data_cuti.tanggal_awal LIKE '$year%'")
                ->findAll();

            $total_hari = 0;
            foreach ($terkonfirmasi as $i => $value) {
                $ex = explode("|", $value['total_hari']);

                for ($i = 0; $i < count($ex); $i++) {
                    $total_hari += $ex[$i];
                }
            }
            $data['terkonfirmasi'] = [
                'terkonfirmasi' => $total_hari
            ];

            $msg = [
                'data' => $data
            ];
            echo json_encode($msg);
        } else {
            $terkonfirmasi = $this->cutiDataCutiModel
                ->where("id_cuti_status_kepegawaian", "2")
                ->where("id_cuti_status_atasan", "2")
                ->where("id_cuti_status_pejabat", "2")
                ->where("cuti_data_cuti.tanggal_awal LIKE '$year%'")
                ->findAll();
            dd($terkonfirmasi);
        }
    }
    public function profile()
    {
        $data['title'] = "Profile"; // kelola_data_cuti
        $data['activeMenu'] = "profileku";

        $pengguna = $this->userModel->where('username', session()->get("username"))->first();
        $data['notifikasi'] = $this->notifikasiModel
            ->select('user_penerima.nama AS nama_pa, user_pengirim.nama AS nama_pm, notifikasi.*')
            ->join('user AS user_penerima', 'user_penerima.id_user = notifikasi.nama_penerima')
            ->join('user AS user_pengirim', 'user_pengirim.id_user = notifikasi.nama_pengirim')
            ->where('nama_penerima', $pengguna['id_user'])
            ->orderBy('notifikasi.created_at', 'desc')
            ->findAll();

        $by = session()->get('username');

        $data['user'] = $this->dataPegawaiModel->select('*', 'user.name')
            ->where('user.username', $by)
            ->join('user', 'user.id_user=data_pegawai.id_user', "right")
            ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan', 'left')
            ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan', 'left')
            ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi', 'left')
            ->join('tim_kerja', 'tim_kerja.id_tim_kerja=data_pegawai.id_tim_kerja', 'left')
            ->first();

        $data['pengguna'] = $data['user'];

        $data['linimasa'] = $this->linimasaModel
            ->select('user.nama, linimasa.*')
            ->join('user', 'user.id_user=linimasa.id_user')
            ->where('linimasa.id_user', $pengguna['id_user'])
            ->orderBy('linimasa.created_at', 'desc')->findAll();

        foreach ($data['linimasa'] as &$item) {
            $item['tanggal'] = $this->tgl_indo2(substr($item['created_at'], 0, 10));
            $item['jam'] = substr($item['created_at'], 11, 5);
        }


        $data['unit_kerja'] = $this->unitKerjaModel->findAll();
        $data['jabatan'] = $this->jabatanModel->findAll();
        $data['golongan'] = $this->golonganModel->findAll();
        $data['status_pegawai'] = $this->statusPegawaiModel->findAll();
        $data['substansi'] = $this->substansiModel->findAll();
        $data['tim_kerja'] = $this->timKerjaModel->findAll();

        $data['profile'] = $this->dataPegawaiModel
            ->join("user", 'user.id_user=data_pegawai.id_user')
            ->join('status_pegawai', 'id_status_pegawai')
            ->where("user.username", session()->get('username'))
            ->first();

        return view('home/profile', $data);
    }

    public function feedback()
    {
        $data['title'] = "Feedback Saya";
        $data['activeMenu'] = "feedback";

        // Get current user
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();

        // Get user's feedback
        $data['feedbacks'] = $this->feedbackModel
            ->where('created_by', $currentUser['id_user'])
            ->orderBy('created_at', 'desc')
            ->findAll();

        // Get feedback statistics for current user
        $data['stats'] = [
            'total' => count($data['feedbacks']),
            'baru' => count(array_filter($data['feedbacks'], fn($f) => $f['status'] === 'Baru')),
            'diproses' => count(array_filter($data['feedbacks'], fn($f) => $f['status'] === 'Diproses')),
            'selesai' => count(array_filter($data['feedbacks'], fn($f) => $f['status'] === 'Selesai')),
            'ditolak' => count(array_filter($data['feedbacks'], fn($f) => $f['status'] === 'Ditolak'))
        ];

        return view('home/my_feedback', $data);
    }

    public function tutorialGantiPasswordModal()
    {
        if ($this->request->isAJAX()) {
            $msg = [
                "data" => view("/tutorial/gantiPassword")
            ];
            echo json_encode($msg);
        }
    }

    public function viewProfile($id)
    {
        // $data['user'] = $this->dataPegawaiModel->select('*', 'user.name')
        //     ->where('user.id_user', $id)
        //     ->join('user', 'user.id_user=data_pegawai.id_user', "right")
        //     ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan', 'left')
        //     ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan', 'left')
        //     ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi', 'left')
        //     ->first();
        // dd($data);
        if ($this->request->isAJAX()) {

            $data['user'] = $this->dataPegawaiModel->select('*', 'user.name')
                ->where('user.id_user', $id)
                ->join('user', 'user.id_user=data_pegawai.id_user', "right")
                ->join('golongan', 'golongan.id_golongan=data_pegawai.id_golongan', 'left')
                ->join('tim_kerja', 'id_tim_kerja', 'left')
                ->join('jabatan', 'jabatan.id_jabatan=data_pegawai.id_jabatan', 'left')
                ->join('substansi', 'substansi.id_substansi=data_pegawai.id_substansi', 'left')
                ->first();

            $msg = [
                'data1' => view('/home/dataProfile', $data),
                'data2' => view('/home/dataKontak', $data),
            ];
            echo json_encode($msg);
        }
    }

    public function getRelaxtimeDetail($bulan)
    {
        if ($this->request->isAJAX()) {
            $year = date('Y');
            $data['getSetting'] = $this->relaxtimeSettingModel->first();
            $data['nomorBulan'] = $bulan;
            $data['getDataTargetBulanan'] = $this->relaxtimeTargetBulananModel
                ->first();
            $data['alreadyRealisasi'] = $this->relaxtimeRealisasiModel
                ->select("COALESCE(SUM(relaxtime_realisasi.realisasi), 0) AS jmlRealisasi")
                ->where('relaxtime_realisasi.bulan', $bulan)
                ->where('relaxtime_realisasi.tahun', $year)
                ->first();
            $data['getData'] = $this->relaxtimeDetailUraianModel
                ->select("*, COALESCE(SUM(relaxtime_realisasi.realisasi), 0) AS jmlRealisasi")
                ->join('relaxtime_realisasi', 'id_relaxtime_detail_uraian')
                ->join('relaxtime_uraian', 'id_relaxtime_uraian', 'left')
                ->join('relaxtime_komponen', 'id_relaxtime_komponen')
                ->join('relaxtime_ro', 'id_relaxtime_ro')
                ->join('relaxtime_kro', 'id_relaxtime_kro')
                ->join('relaxtime_rkt', 'id_relaxtime_rkt')
                ->where('relaxtime_detail_uraian.jumlah_detail_uraian !=', '0')
                ->where('relaxtime_realisasi.bulan', $bulan)
                ->where('relaxtime_detail_uraian.tahun', $year)
                ->groupBy('relaxtime_detail_uraian.id_relaxtime_detail_uraian')
                ->orderBy('relaxtime_rkt.kode_rkt', 'ASC')
                ->orderBy('relaxtime_ro.kode_relaxtime_ro', 'ASC')
                ->orderBy('relaxtime_detail_uraian.deskripsi_detail_uraian', 'ASC')
                ->findAll();
            $msg = [
                'data' => view('dashboard/relaxtime/tableRealisasi', $data)
            ];

            echo json_encode($msg);
        }
    }
    public function getRelaxtime()
    {
        if ($this->request->isAJAX()) {
            $year = date("Y");
            $data['getSetting'] = $this->relaxtimeSettingModel->first();
            $data['getCuti'] = $this->cutiDataCutiModel
                ->join('data_pegawai', 'data_pegawai.id_data_pegawai=cuti_data_cuti.id_data_pegawai')
                ->findAll();

            $data['getData'] = $this->relaxtimeDetailUraianModel
                ->select("*, COALESCE(SUM(relaxtime_realisasi.realisasi), 0) AS jmlRealisasi")
                ->join('relaxtime_realisasi', 'id_relaxtime_detail_uraian', 'left')
                ->join('relaxtime_uraian', 'id_relaxtime_uraian')
                ->join('relaxtime_komponen', 'id_relaxtime_komponen')
                ->join('relaxtime_ro', 'id_relaxtime_ro')
                ->join('relaxtime_kro', 'id_relaxtime_kro')
                ->join('relaxtime_rkt', 'id_relaxtime_rkt')
                ->where('relaxtime_detail_uraian.jumlah_detail_uraian !=', '0')
                ->where('relaxtime_detail_uraian.tahun', $year)
                ->groupBy('relaxtime_detail_uraian.id_relaxtime_detail_uraian')
                ->orderBy('relaxtime_rkt.kode_rkt', 'ASC')
                ->orderBy('relaxtime_ro.kode_relaxtime_ro', 'ASC')
                ->orderBy('relaxtime_detail_uraian.deskripsi_detail_uraian', 'ASC')
                ->findAll();

            $data['getDataBulanan'] = $this->relaxtimeRealisasiModel
                ->select("bulan, tahun, COALESCE(SUM(relaxtime_realisasi.realisasi), 0) AS jmlRealisasi")
                ->where('relaxtime_realisasi.tahun', $year)
                ->groupBy('relaxtime_realisasi.bulan, relaxtime_realisasi.tahun')
                ->orderBy('relaxtime_realisasi.bulan, relaxtime_realisasi.tahun', 'ASC')
                ->findAll();

            $data['getDataTargetBulanan'] = $this->relaxtimeTargetBulananModel
                ->where('relaxtime_target_bulanan.tahun', $year)
                ->first();

            $data['getDataRealisasiBulanan'] = $this->relaxtimeDetailUraianModel
                ->select('SUBSTRING(relaxtime_uraian.kode_uraian, 1, 2) AS kode_prefix, relaxtime_realisasi.bulan')
                ->selectSum('relaxtime_realisasi.realisasi', 'total_nominal')
                ->join('relaxtime_realisasi', 'relaxtime_detail_uraian.id_relaxtime_detail_uraian = relaxtime_realisasi.id_relaxtime_detail_uraian')
                ->join('relaxtime_uraian relaxtime_uraian', 'relaxtime_detail_uraian.id_relaxtime_uraian = relaxtime_uraian.id_relaxtime_uraian')
                ->where('SUBSTRING(relaxtime_uraian.kode_uraian, 1, 2) IN ("51", "52", "53")')
                ->where('relaxtime_detail_uraian.tahun', $year)
                ->groupBy('SUBSTRING(relaxtime_uraian.kode_uraian, 1, 2), relaxtime_realisasi.bulan')
                ->findAll();

            $data['getData515253'] = $this->relaxtimeDetailUraianModel
                ->select('SUBSTRING(relaxtime_uraian.kode_uraian, 1, 2) AS kode_prefix')
                ->selectSum('relaxtime_detail_uraian.jumlah_detail_uraian', 'total_nominal')
                ->join('relaxtime_uraian relaxtime_uraian', 'relaxtime_detail_uraian.id_relaxtime_uraian = relaxtime_uraian.id_relaxtime_uraian')
                ->where('SUBSTRING(relaxtime_uraian.kode_uraian, 1, 2) IN ("51", "52", "53")')
                ->where('relaxtime_detail_uraian.tahun', $year)
                ->groupBy('SUBSTRING(relaxtime_uraian.kode_uraian, 1, 2)')
                ->findAll();
            $msg = [
                'data' => view('dashboard/relaxtime/index', $data)
            ];
            echo json_encode($msg);
        } else {
            return redirect()->to('home/relaxtime/1');
        }
    }

    public function getSinadi()
    {
        if ($this->request->isAJAX()) {
            $akses = $this->userModel
                ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
                ->where("user.username", session()->get('username'))
                ->first();
            $data['akses'] = false;
            if ($akses && $akses['status'] == 'Akses Diterima') {
                $data['akses'] = true;
            }
            $msg = [];
            // $data['getSinadi'] = $this->sinadiPerjalananDinasModel
            //     ->select('count(*) as kegiatan, tgl_pelaksanaan')
            //     ->groupBy('tgl_pelaksanaan')
            //     ->findAll();
            $data['getSinadi'] = $this->sinadiPerjalananDinasModel
                ->select('tgl_pelaksanaan, COUNT(DISTINCT id_perjalanan) AS kegiatan')
                ->whereIn('id_perjalanan', function ($subquery) {
                    $subquery->select('id_perjalanan')
                        ->from('sinadi_perjalanan_dinas_peserta');
                })
                ->groupBy('tgl_pelaksanaan')
                ->findAll();

            // $data['nominal'] = $this->sinadiPerjalananDinasModel
            //     ->selectSum('total_transport', 'transport')
            //     ->selectSum('total_harian', 'harian')
            //     ->selectSum('total_penginapan', 'penginapan')
            //     ->selectSum('total_perjalanan', 'perjalanan')
            //     ->whereIn('id_perjalanan', function ($subquery) {
            //         $subquery->select('id_perjalanan')
            //             ->from('sinadi_perjalanan_dinas_peserta');
            //     })
            //     ->first();

            $data['nominal'] = $this->sinadiPerjalananDinasModel
                ->select('SUM(sinadi_perjalanan_dinas_peserta.transport_jumlah) as transport, SUM(sinadi_perjalanan_dinas_peserta.harian_jumlah) as harian, SUM(sinadi_perjalanan_dinas_peserta.penginapan_jumlah) as penginapan, SUM(sinadi_perjalanan_dinas_peserta.jumlah_transfer) as perjalanan')
                ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan = sinadi_perjalanan_dinas.id_perjalanan', 'left') // Sesuaikan dengan hubungan antara kedua tabel
                ->whereIn('sinadi_perjalanan_dinas_peserta.id_perjalanan', function ($subquery) {
                    $subquery->select('id_perjalanan')
                        ->from('sinadi_perjalanan_dinas_peserta');
                })
                ->first();

            if ($data)
                $msg = [
                    'data' => view('dashboard/sinadi/index', $data)
                ];
            echo json_encode($msg);
        } else {
            return redirect()->to('home/dasboard');
        }
    }
    public function getDataSinadi()
    {
        if ($this->request->isAJAX()) {
            $msg = [];
            $tanggal = trim((string) $this->request->getVar("tanggal"));
            if ($tanggal !== '') {
                $data = $this->sinadiPerjalananDinasModel
                    ->select(
                        'sinadi_perjalanan_dinas.id_perjalanan, ' .
                        'sinadi_perjalanan_dinas.tgl_pelaksanaan, ' .
                        'sinadi_perjalanan_dinas.tgl_pelaksanaan_akhir, ' .
                        'sinadi_perjalanan_dinas.nama_kegiatan, ' .
                        'sinadi_perjalanan_dinas_peserta.kota, ' .
                        'sinadi_perjalanan_dinas_peserta.kota_asal, ' .
                        'sinadi_perjalanan_dinas_peserta.nama_pegawai'
                    )
                    ->join('sinadi_perjalanan_dinas_peserta', 'id_perjalanan')
                    ->where('sinadi_perjalanan_dinas.tgl_pelaksanaan', $tanggal)
                    ->orderBy('sinadi_perjalanan_dinas_peserta.id_perjalanan', "ASC")
                    ->findAll();

                if ($data) {
                    $msg = [
                        'data' => $data
                    ];
                }
            }

            return $this->response->setJSON($msg);
        } else {
            return redirect()->to('home/dasboard');
        }
    }
    public function agenda()
    {
        $data['title'] = "Agenda";
        $data['activeMenu'] = "agenda";

        $data['user'] = $this->userModel->findAll();

        return view('home/agenda', $data);
    }
    public function manualBooks()
    {
        $data['cover'] = $this->manualBooksModel->select('cover')->where('cover !=', null)->orderBy('priority', 'asc')->findAll();

        $data['file'] = $this->manualBooksModel->orderBy('priority', 'asc')->findAll();

        return view('manual_books/index', $data);
    }
    public function tambahManualBooks()
    {
        $nama_sistem = $this->request->getVar('nama_sistem');

        // Cek jika nama_sistem sudah ada
        $cek = $this->manualBooksModel->where('nama_sistem', $nama_sistem)->first();

        if ($cek) {
            return redirect()->to('manualBooks');
        }

        // Ambil prioritas tertinggi dan tambahkan 1
        $cek = $this->manualBooksModel->select('priority')->orderBy('priority', 'desc')->first();
        $priority = $cek ? $cek['priority'] + 1 : 1;

        // Handle cover image
        $cover = null;
        $coverFile = $this->request->getFile('cover');
        if ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved() && in_array($coverFile->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tif', 'tiff', 'svg'])) {
            $maxFileSize = 1024 * 1024 * 5; // 5MB
            if ($coverFile->getSize() <= $maxFileSize) {
                $cover = 'cover_' . uniqid() . '_' . $coverFile->getRandomName();
                if (!$coverFile->move(FCPATH . '/louie/images', $cover)) {
                    $cover = null;
                }
            }
        }

        // Handle PDF file
        $filePDF = null;
        $pdfFile = $this->request->getFile('file');
        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved() && $pdfFile->getExtension() === 'pdf') {
            $maxFileSize = 1024 * 1024 * 50; // 50MB
            if ($pdfFile->getSize() <= $maxFileSize) {
                $filePDF = 'file_' . uniqid() . '_' . $pdfFile->getRandomName();
                if (!$pdfFile->move(FCPATH . '/louie/images', $filePDF)) {
                    $filePDF = null;
                }
            }
        }

        // Simpan data
        $data = [
            'cover' => $cover,
            'file' => $filePDF,
            'nama_sistem' => $nama_sistem,
            'priority' => $priority
        ];

        $this->manualBooksModel->save($data);

        return redirect()->to('manualBooks');
    }
    public function setPriority()
    {
        if ($this->request->isAJAX()) {
            $id_manual_books = $this->request->getVar('id_manual_books');
            $priority = $this->request->getVar('priority');

            $msg = [];

            $cek = $this->manualBooksModel->where('priority', $priority)->first();
            if ($cek) {
                // $cek = $this->manualBooksModel->where('id_manual_books', $cek['id_manual_books'])->first();
                if ($cek['id_manual_books'] != $id_manual_books) {
                    $data = [
                        'id_manual_books' => $id_manual_books,
                        'priority' => $priority,
                    ];
                    if ($this->manualBooksModel->save($data)) {

                        $getAllData = $this->manualBooksModel->orderBy('priority', 'asc')->findAll();
                        $i = 1;
                        foreach ($getAllData as $get) {
                            if ($i == intval($get['priority'])) {
                                $i++;
                            } else {
                                $dataUpdate = [
                                    'id_manual_books' => $cek['id_manual_books'],
                                    'priority' => $i,
                                ];
                                $this->manualBooksModel->save($dataUpdate);
                                $msg = [
                                    'request' => true,
                                    'newPriority' => "priority" . $cek['id_manual_books'],
                                    'newPriorityValue' => $i,
                                ];
                                break;
                            }
                        }
                    }
                }
            } else {
                $data = [
                    'id_manual_books' => $id_manual_books,
                    'priority' => $priority,
                ];
                $this->manualBooksModel->save($data);
            }
            echo json_encode($msg);
        }
    }

    public function simpanCatatanPribadi()
    {
        if ($this->request->isAJAX()) {
            $catatan = $this->request->getVar('catatan');
            $username = session()->get('username');
            
            $user = $this->userModel->where('username', $username)->first();
            if ($user) {
                $this->userModel->update($user['id_user'], [
                    'catatan_pribadi' => $catatan
                ]);
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User not found']);
            }
        }
    }
}

