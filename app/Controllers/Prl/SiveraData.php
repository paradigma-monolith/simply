<?php

namespace App\Controllers\Prl;

use App\Controllers\BaseController;

class SiveraData extends BaseController
{
  public function __construct()
  {
    $substansi = session()->get('substansi');

    if (session()->get('substansi')) {
      if ($substansi == 'PRL' || session()->get('role') == 'Admin') {
      } else {
        session()->setFlashdata('error', 'Anda tidak mempunyai akses ke halaman tersebut!.');
        header("Location: /home");
        die;
      }
    } else {
      session()->setFlashdata('error', 'Tolong lakukan login ulang pada sistem!.');
      header("Location: /home");
      die;
    }
  }

  public function index()
  {
    $data['title'] = "PRL | SIVERA (Sistem Informasi Vektor dan Reservoar)";
    $data['activeMenu'] = "sivera";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    $data['wilayah_kerja'] = $this->unitKerjaModel->findAll();

    return view('prl/sivera/index', $data);
  }

  public function dashboard()
  {
    if ($this->request->isAJAX()) {
      $wilkerId = $this->request->getVar('id_wilayah_kerja');
      $tglMulai = $this->request->getVar('tanggal_mulai');
      $tglSelesai = $this->request->getVar('tanggal_selesai');

      $jentikBuilder = $this->siveraJentikModel
        ->select('prl_sivera_jentik.*, unit_kerja.unit_kerja as wilker')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_jentik.id_wilayah_kerja', 'left');
      if (!empty($wilkerId)) {
        $jentikBuilder->where('prl_sivera_jentik.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $jentikBuilder->where('prl_sivera_jentik.tanggal_mulai >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $jentikBuilder->where('prl_sivera_jentik.tanggal_mulai <=', $tglSelesai);
      }
      $jentik = $jentikBuilder->findAll();

      $lalatBuilder = $this->siveraLalatModel
        ->select('prl_sivera_lalat.*, unit_kerja.unit_kerja as wilker')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_lalat.id_wilayah_kerja', 'left');
      if (!empty($wilkerId)) {
        $lalatBuilder->where('prl_sivera_lalat.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $lalatBuilder->where('prl_sivera_lalat.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $lalatBuilder->where('prl_sivera_lalat.tanggal <=', $tglSelesai);
      }
      $lalat = $lalatBuilder->findAll();

      $kecoaBuilder = $this->siveraKecoaModel
        ->select('prl_sivera_kecoa.*, unit_kerja.unit_kerja as wilker')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_kecoa.id_wilayah_kerja', 'left');
      if (!empty($wilkerId)) {
        $kecoaBuilder->where('prl_sivera_kecoa.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $kecoaBuilder->where('prl_sivera_kecoa.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $kecoaBuilder->where('prl_sivera_kecoa.tanggal <=', $tglSelesai);
      }
      $kecoa = $kecoaBuilder->findAll();

      $tikusBuilder = $this->siveraTikusModel
        ->select('prl_sivera_tikus.*, unit_kerja.unit_kerja as wilker')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_tikus.id_wilayah_kerja', 'left');
      if (!empty($wilkerId)) {
        $tikusBuilder->where('prl_sivera_tikus.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $tikusBuilder->where('prl_sivera_tikus.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $tikusBuilder->where('prl_sivera_tikus.tanggal <=', $tglSelesai);
      }
      $tikus = $tikusBuilder->findAll();

      $pinjalBuilder = $this->siveraPinjalModel
        ->select('prl_sivera_pinjal.*, unit_kerja.unit_kerja as wilker')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_pinjal.id_wilayah_kerja', 'left');
      if (!empty($wilkerId)) {
        $pinjalBuilder->where('prl_sivera_pinjal.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $pinjalBuilder->where('prl_sivera_pinjal.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $pinjalBuilder->where('prl_sivera_pinjal.tanggal <=', $tglSelesai);
      }
      $pinjal = $pinjalBuilder->findAll();

      $summary = [
        'jentik' => count($jentik),
        'lalat' => count($lalat),
        'kecoa' => count($kecoa),
        'tikus' => count($tikus),
        'pinjal' => count($pinjal),
        'kontainer_positif' => array_sum(array_map(static function ($row) {
          return (int) ($row['jml_kontainer_positif'] ?? 0);
        }, $jentik)),
        'kecoa_tertangkap' => array_sum(array_map(static function ($row) {
          return (int) ($row['jml_kecoa_tertangkap'] ?? 0);
        }, $kecoa)),
        'tikus_tertangkap' => array_sum(array_map(static function ($row) {
          return (int) ($row['jml_tikus_tertangkap'] ?? 0);
        }, $tikus)),
        'pinjal_umum' => array_sum(array_map(static function ($row) {
          return (int) ($row['pinjal_umum'] ?? 0);
        }, $pinjal)),
      ];

      $lalatRata = array_map(static function ($row) {
        return (float) ($row['rata_rata'] ?? 0);
      }, $lalat);
      $successTrap = array_map(static function ($row) {
        return (float) ($row['success_trap'] ?? 0);
      }, $tikus);

      $summary['avg_lalat'] = count($lalatRata) ? round(array_sum($lalatRata) / count($lalatRata), 2) : 0;
      $summary['avg_success_trap'] = count($successTrap) ? round(array_sum($successTrap) / count($successTrap), 2) : 0;

      $wilkerMap = [];
      foreach (array_merge($jentik, $lalat, $kecoa, $tikus, $pinjal) as $row) {
        $wilker = trim((string) ($row['wilker'] ?? 'Tidak Diketahui'));
        if ($wilker === '') {
          $wilker = 'Tidak Diketahui';
        }
        $wilkerMap[$wilker] = true;
      }
      if (!empty($wilkerId) && empty($wilkerMap)) {
        $singleWilker = $this->unitKerjaModel->find($wilkerId);
        if ($singleWilker && !empty($singleWilker['unit_kerja'])) {
          $wilkerMap[$singleWilker['unit_kerja']] = true;
        }
      }
      $wilkerLabels = array_keys($wilkerMap);
      sort($wilkerLabels);

      $speciesKeys = [
        'Periplatena americana' => 'P. americana',
        'Blatella germanica' => 'B. germanica',
        'Supella longipalpa' => 'S. longipalpa',
        'Neostylopiga rhombifolia' => 'N. rhombifolia',
        'Lain-Lain' => 'Lain-Lain',
      ];

      $kecoaChart = [];
      foreach ($speciesKeys as $label) {
        $kecoaChart[$label] = array_fill(0, count($wilkerLabels), 0);
      }

      foreach ($kecoa as $row) {
        $wilker = trim((string) ($row['wilker'] ?? 'Tidak Diketahui'));
        if ($wilker === '') {
          $wilker = 'Tidak Diketahui';
        }
        $labelIndex = array_search($wilker, $wilkerLabels, true);
        if ($labelIndex === false) {
          continue;
        }

        $spesies = json_decode($row['spesies_tertangkap'] ?? '', true);
        if (!is_array($spesies)) {
          continue;
        }

        foreach ($spesies as $nama => $jumlah) {
          $datasetLabel = $speciesKeys[$nama] ?? 'Lain-Lain';
          if (!isset($kecoaChart[$datasetLabel])) {
            $kecoaChart[$datasetLabel] = array_fill(0, count($wilkerLabels), 0);
          }
          $kecoaChart[$datasetLabel][$labelIndex] += (int) $jumlah;
        }
      }

      $lalatStatusChart = [
        'MS' => array_fill(0, count($wilkerLabels), 0),
        'TMS' => array_fill(0, count($wilkerLabels), 0),
      ];

      foreach ($lalat as $row) {
        $wilker = trim((string) ($row['wilker'] ?? 'Tidak Diketahui'));
        if ($wilker === '') {
          $wilker = 'Tidak Diketahui';
        }
        $labelIndex = array_search($wilker, $wilkerLabels, true);
        if ($labelIndex === false) {
          continue;
        }

        $rata = (float) ($row['rata_rata'] ?? 0);
        $status = $rata < 2 ? 'MS' : 'TMS';
        $lalatStatusChart[$status][$labelIndex]++;
      }

      $jenisKontainerChart = [];
      foreach ($jentik as $row) {
        $items = array_filter(array_map('trim', explode(',', (string) ($row['jenis_kontainer'] ?? ''))));
        foreach ($items as $item) {
          if (!isset($jenisKontainerChart[$item])) {
            $jenisKontainerChart[$item] = 0;
          }
          $jenisKontainerChart[$item] += 1;
        }
      }
      arsort($jenisKontainerChart);
      $jenisKontainerChart = array_slice($jenisKontainerChart, 0, 6, true);

      $spesiesTikusChart = [];
      foreach (array_merge($pinjal, $tikus) as $row) {
        $rawSpesies = trim((string) ($row['spesies_tikus'] ?? $row['spesies'] ?? ''));
        $wilker = trim((string) ($row['wilker'] ?? 'Tidak Diketahui'));
        if ($rawSpesies === '') {
          continue;
        }
        if ($wilker === '') {
          $wilker = 'Tidak Diketahui';
        }
        $labelIndex = array_search($wilker, $wilkerLabels, true);

        $decoded = json_decode($rawSpesies, true);
        if (is_array($decoded)) {
          foreach ($decoded as $spName => $count) {
            $spName = trim((string) $spName);
            $cnt = (int) $count;
            if ($spName !== '' && $cnt > 0) {
              if (!isset($spesiesTikusChart[$spName])) {
                $spesiesTikusChart[$spName] = array_fill(0, count($wilkerLabels), 0);
              }
              if ($labelIndex !== false) {
                $spesiesTikusChart[$spName][$labelIndex] += $cnt;
              }
            }
          }
        } else {
          if (!isset($spesiesTikusChart[$rawSpesies])) {
            $spesiesTikusChart[$rawSpesies] = array_fill(0, count($wilkerLabels), 0);
          }
          if ($labelIndex !== false) {
            $spesiesTikusChart[$rawSpesies][$labelIndex]++;
          }
        }
      }

      // Perhitungan Rekapitulasi Rinci per Wilayah Kerja
      $wilkerDetail = [];
      foreach ($wilkerLabels as $w) {
        $wilkerDetail[$w] = [
          'wilker'                      => $w,
          'jentik_survei'               => 0,
          'jentik_kontainer_diperiksa'  => 0,
          'jentik_kontainer_positif'    => 0,
          'jentik_rumah_diperiksa'      => 0,
          'jentik_rumah_positif'        => 0,
          'lalat_survei'                => 0,
          'lalat_rata_rata_sum'         => 0,
          'lalat_ms'                    => 0,
          'lalat_tms'                   => 0,
          'kecoa_survei'                => 0,
          'kecoa_perangkap'             => 0,
          'kecoa_tertangkap'            => 0,
          'tikus_survei'                => 0,
          'tikus_perangkap'             => 0,
          'tikus_tertangkap'            => 0,
          'tikus_success_trap_sum'      => 0,
          'pinjal_survei'               => 0,
          'pinjal_umum'                 => 0,
          'pinjal_khusus'               => 0,
        ];
      }

      foreach ($jentik as $row) {
        $w = trim((string) ($row['wilker'] ?? 'Tidak Diketahui')) ?: 'Tidak Diketahui';
        if (isset($wilkerDetail[$w])) {
          $wilkerDetail[$w]['jentik_survei']++;
          $wilkerDetail[$w]['jentik_kontainer_diperiksa'] += (int) ($row['jml_kontainer_diperiksa'] ?? 0);
          $wilkerDetail[$w]['jentik_kontainer_positif']   += (int) ($row['jml_kontainer_positif'] ?? 0);
          $wilkerDetail[$w]['jentik_rumah_diperiksa']     += (int) ($row['jml_rumah_diperiksa'] ?? 0);
          $wilkerDetail[$w]['jentik_rumah_positif']       += (int) ($row['jml_rumah_positif'] ?? 0);
        }
      }

      foreach ($lalat as $row) {
        $w = trim((string) ($row['wilker'] ?? 'Tidak Diketahui')) ?: 'Tidak Diketahui';
        if (isset($wilkerDetail[$w])) {
          $wilkerDetail[$w]['lalat_survei']++;
          $r = (float) ($row['rata_rata'] ?? 0);
          $wilkerDetail[$w]['lalat_rata_rata_sum'] += $r;
          if ($r < 2) {
            $wilkerDetail[$w]['lalat_ms']++;
          } else {
            $wilkerDetail[$w]['lalat_tms']++;
          }
        }
      }

      foreach ($kecoa as $row) {
        $w = trim((string) ($row['wilker'] ?? 'Tidak Diketahui')) ?: 'Tidak Diketahui';
        if (isset($wilkerDetail[$w])) {
          $wilkerDetail[$w]['kecoa_survei']++;
          $wilkerDetail[$w]['kecoa_perangkap']  += (int) ($row['perangkap_terpasang'] ?? 0);
          $wilkerDetail[$w]['kecoa_tertangkap'] += (int) ($row['jml_kecoa_tertangkap'] ?? 0);
        }
      }

      foreach ($tikus as $row) {
        $w = trim((string) ($row['wilker'] ?? 'Tidak Diketahui')) ?: 'Tidak Diketahui';
        if (isset($wilkerDetail[$w])) {
          $wilkerDetail[$w]['tikus_survei']++;
          $wilkerDetail[$w]['tikus_perangkap']  += (int) ($row['jml_perangkap_dipasang'] ?? 0);
          $wilkerDetail[$w]['tikus_tertangkap'] += (int) ($row['jml_tikus_tertangkap'] ?? 0);
          $wilkerDetail[$w]['tikus_success_trap_sum'] += (float) ($row['success_trap'] ?? 0);
        }
      }

      foreach ($pinjal as $row) {
        $w = trim((string) ($row['wilker'] ?? 'Tidak Diketahui')) ?: 'Tidak Diketahui';
        if (isset($wilkerDetail[$w])) {
          $wilkerDetail[$w]['pinjal_survei']++;
          $wilkerDetail[$w]['pinjal_umum']   += (int) ($row['pinjal_umum'] ?? 0);
          $wilkerDetail[$w]['pinjal_khusus'] += (int) ($row['pinjal_khusus'] ?? 0);
        }
      }

      foreach ($wilkerDetail as $w => &$item) {
        $item['lalat_avg'] = $item['lalat_survei'] > 0 ? round($item['lalat_rata_rata_sum'] / $item['lalat_survei'], 2) : 0;
        $item['tikus_success_trap_avg'] = $item['tikus_survei'] > 0 ? round($item['tikus_success_trap_sum'] / $item['tikus_survei'], 2) : 0;
        if ($item['jentik_rumah_diperiksa'] > 0) {
          $item['abj'] = round((($item['jentik_rumah_diperiksa'] - $item['jentik_rumah_positif']) / $item['jentik_rumah_diperiksa']) * 100, 1);
        } elseif ($item['jentik_kontainer_diperiksa'] > 0) {
          $item['abj'] = round((($item['jentik_kontainer_diperiksa'] - $item['jentik_kontainer_positif']) / $item['jentik_kontainer_diperiksa']) * 100, 1);
        } else {
          $item['abj'] = 100;
        }
      }
      unset($item);

      $data['summary'] = $summary;
      $data['dashboard'] = [
        'labels'           => $wilkerLabels,
        'kecoa'            => $kecoaChart,
        'lalat_status'     => $lalatStatusChart,
        'jentik_kontainer' => $jenisKontainerChart,
        'spesies_tikus'    => $spesiesTikusChart,
        'wilker_detail'    => $wilkerDetail,
      ];

      return view('prl/sivera/dashboard', $data);
    }
  }

  public function jentik_daftar()
  {
    if ($this->request->isAJAX()) {
      $wilkerId = $this->request->getVar('id_wilayah_kerja');
      $tglMulai = $this->request->getVar('tanggal_mulai');
      $tglSelesai = $this->request->getVar('tanggal_selesai');
      $musim = $this->request->getVar('kondisi_musim');
      $wilayah = $this->request->getVar('wilayah');
      $status = $this->request->getVar('status');

      $builder = $this->siveraJentikModel
        ->select('prl_sivera_jentik.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = prl_sivera_jentik.created_by', 'left')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_jentik.id_wilayah_kerja', 'left')
        ->orderBy('prl_sivera_jentik.updated_at DESC');

      if (!empty($wilkerId)) {
        $builder->where('prl_sivera_jentik.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $builder->where('prl_sivera_jentik.tanggal_mulai >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $builder->where('prl_sivera_jentik.tanggal_mulai <=', $tglSelesai);
      }
      if (!empty($musim)) {
        $builder->where('prl_sivera_jentik.kondisi_musim', $musim);
      }
      if (!empty($wilayah)) {
        $builder->where('prl_sivera_jentik.wilayah', $wilayah);
      }

      $jentik = $builder->findAll();

      if (!empty($status)) {
        $jentik = array_values(array_filter($jentik, static function($row) use ($status) {
          $hi = $row['jml_rumah_diperiksa'] > 0 ? ($row['jml_rumah_positif'] / $row['jml_rumah_diperiksa']) * 100 : 0;
          $ci = $row['jml_kontainer_diperiksa'] > 0 ? ($row['jml_kontainer_positif'] / $row['jml_kontainer_diperiksa']) * 100 : 0;
          $bi = $row['jml_rumah_diperiksa'] > 0 ? ($row['jml_kontainer_positif'] / $row['jml_rumah_diperiksa']) * 100 : 0;
          $isMs = ($hi == 0 && $ci == 0 && $bi == 0);
          if ($status === 'MS') {
            return $isMs;
          } elseif ($status === 'TMS') {
            return !$isMs;
          }
          return true;
        }));
      }

      $data['jentik'] = $jentik;
      $data['wilayah_kerja'] = $this->unitKerjaModel->findAll();
      return view('prl/sivera/jentik_daftar', $data);
    }
  }

  public function lalat_daftar()
  {
    if ($this->request->isAJAX()) {
      $wilkerId = $this->request->getVar('id_wilayah_kerja');
      $tglMulai = $this->request->getVar('tanggal_mulai');
      $tglSelesai = $this->request->getVar('tanggal_selesai');
      $musim = $this->request->getVar('musim');
      $status = $this->request->getVar('status');

      $builder = $this->siveraLalatModel
        ->select('prl_sivera_lalat.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = prl_sivera_lalat.created_by', 'left')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_lalat.id_wilayah_kerja', 'left')
        ->orderBy('prl_sivera_lalat.updated_at DESC');

      if (!empty($wilkerId)) {
        $builder->where('prl_sivera_lalat.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $builder->where('prl_sivera_lalat.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $builder->where('prl_sivera_lalat.tanggal <=', $tglSelesai);
      }
      if (!empty($musim)) {
        $builder->where('prl_sivera_lalat.musim', $musim);
      }
      if ($status === 'MS') {
        $builder->where('prl_sivera_lalat.rata_rata <', 2);
      } elseif ($status === 'TMS') {
        $builder->where('prl_sivera_lalat.rata_rata >=', 2);
      }

      $data['lalat'] = $builder->findAll();
      $data['wilayah_kerja'] = $this->unitKerjaModel->findAll();
      return view('prl/sivera/lalat_daftar', $data);
    }
  }

  public function kecoa_daftar()
  {
    if ($this->request->isAJAX()) {
      $wilkerId = $this->request->getVar('id_wilayah_kerja');
      $tglMulai = $this->request->getVar('tanggal_mulai');
      $tglSelesai = $this->request->getVar('tanggal_selesai');
      $status = $this->request->getVar('status');

      $builder = $this->siveraKecoaModel
        ->select('prl_sivera_kecoa.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = prl_sivera_kecoa.created_by', 'left')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_kecoa.id_wilayah_kerja', 'left')
        ->orderBy('prl_sivera_kecoa.updated_at DESC');

      if (!empty($wilkerId)) {
        $builder->where('prl_sivera_kecoa.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $builder->where('prl_sivera_kecoa.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $builder->where('prl_sivera_kecoa.tanggal <=', $tglSelesai);
      }
      if ($status === 'MS') {
        $builder->where('prl_sivera_kecoa.rata_rata <', 2);
      } elseif ($status === 'TMS') {
        $builder->where('prl_sivera_kecoa.rata_rata >=', 2);
      }

      $data['kecoa'] = $builder->findAll();
      $data['wilayah_kerja'] = $this->unitKerjaModel->findAll();
      return view('prl/sivera/kecoa_daftar', $data);
    }
  }

  public function tikus_daftar()
  {
    if ($this->request->isAJAX()) {
      $wilkerId = $this->request->getVar('id_wilayah_kerja');
      $tglMulai = $this->request->getVar('tanggal_mulai');
      $tglSelesai = $this->request->getVar('tanggal_selesai');

      $builder = $this->siveraTikusModel
        ->select('prl_sivera_tikus.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = prl_sivera_tikus.created_by', 'left')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_tikus.id_wilayah_kerja', 'left')
        ->orderBy('prl_sivera_tikus.updated_at DESC');

      if (!empty($wilkerId)) {
        $builder->where('prl_sivera_tikus.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $builder->where('prl_sivera_tikus.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $builder->where('prl_sivera_tikus.tanggal <=', $tglSelesai);
      }

      $data['tikus'] = $builder->findAll();
      $data['wilayah_kerja'] = $this->unitKerjaModel->findAll();
      return view('prl/sivera/tikus_daftar', $data);
    }
  }

  public function pinjal_daftar()
  {
    if ($this->request->isAJAX()) {
      $wilkerId = $this->request->getVar('id_wilayah_kerja');
      $tglMulai = $this->request->getVar('tanggal_mulai');
      $tglSelesai = $this->request->getVar('tanggal_selesai');
      $statusUmum = $this->request->getVar('status_umum');
      $statusKhusus = $this->request->getVar('status_khusus');

      $builder = $this->siveraPinjalModel
        ->select('prl_sivera_pinjal.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = prl_sivera_pinjal.created_by', 'left')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sivera_pinjal.id_wilayah_kerja', 'left')
        ->orderBy('prl_sivera_pinjal.updated_at DESC');

      if (!empty($wilkerId)) {
        $builder->where('prl_sivera_pinjal.id_wilayah_kerja', $wilkerId);
      }
      if (!empty($tglMulai)) {
        $builder->where('prl_sivera_pinjal.tanggal >=', $tglMulai);
      }
      if (!empty($tglSelesai)) {
        $builder->where('prl_sivera_pinjal.tanggal <=', $tglSelesai);
      }
      if ($statusUmum === 'MS') {
        $builder->where('prl_sivera_pinjal.indeks_pinjal_umum <', 2);
      } elseif ($statusUmum === 'TMS') {
        $builder->where('prl_sivera_pinjal.indeks_pinjal_umum >=', 2);
      }
      if ($statusKhusus === 'MS') {
        $builder->where('prl_sivera_pinjal.indeks_pinjal_khusus <', 1);
      } elseif ($statusKhusus === 'TMS') {
        $builder->where('prl_sivera_pinjal.indeks_pinjal_khusus >=', 1);
      }

      $data['pinjal'] = $builder->findAll();
      $data['wilayah_kerja'] = $this->unitKerjaModel->findAll();
      return view('prl/sivera/pinjal_daftar', $data);
    }
  }

}
