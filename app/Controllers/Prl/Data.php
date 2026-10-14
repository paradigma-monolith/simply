<?php

namespace App\Controllers\Prl;

use App\Controllers\BaseController;

class Data extends BaseController
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

  // TPP ==================================================================================>
  public function tpp()
  {
    $data['title'] = "PRL | TPP (Tempat Pengelolaan Pangan)";
    $data['activeMenu'] = "tpp";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('prl/tpp', $data);
  }

  public function tpp_daftar($id = '')
  {
    if ($this->request->isAJAX()) {
      if ($id == '') {
        $data['tpp'] = $this->prlTppModel
          ->select('prl_tpp.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
          ->join('user', 'user.id_user = prl_tpp.created_by')
          ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_tpp.id_wilayah_kerja')
          ->orderBy('prl_tpp.updated_at DESC')
          ->findAll();
      } else {
      }
      $msg = [
        'data' => view("prl/tpp_daftar", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function tambah_tpp($id = null)
  {
    $data['title'] = "PRL | TPP (Tempat Pengelolaan Pangan)";
    $data['activeMenu'] = "tpp";

    $data['wilayahKerja'] = $this->unitKerjaModel->where('status_unit_kerja', 'Aktif')->findAll();

    if ($id != null) {
      $data['getTpp'] = $this->prlTppModel->where('id_prl_tpp', $id)->first();
    }

    return view('crud/prl_tambah_tpp', $data);
  }

  // INSPEKSI TPP ==================================================================================>
  public function tpp_daftar_inspeksi($id = '')
  {
    if ($this->request->isAJAX()) {
      $dateRange = $this->request->getVar('dateRange');

      $getInspeksiTpp = $this->prlInspeksiTppModel
        ->select('prl_tpp.*, prl_inspeksi_tpp.*, unit_kerja.unit_kerja as wilker, data_pegawai.nama as nama_pegawai')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = prl_inspeksi_tpp.id_data_pegawai')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_inspeksi_tpp.id_wilayah_kerja')
        ->join('prl_tpp', 'prl_tpp.id_prl_tpp = prl_inspeksi_tpp.id_prl_tpp')
        ->orderBy('prl_inspeksi_tpp.updated_at DESC');

      if ($dateRange) {
        [$startDate, $endDate] = explode(' - ', $dateRange);
        $getInspeksiTpp->where('prl_inspeksi_tpp.tanggal_inspeksi >=', $startDate)
          ->where('prl_inspeksi_tpp.tanggal_inspeksi <=', $endDate);
      }

      $data['inspeksi_tpp'] = $getInspeksiTpp->findAll();

      if (!empty($data['inspeksi_tpp'])) {
        foreach ($data['inspeksi_tpp'] as &$row) {
          // Pecah ID Pegawai dari format "1 | 2 | 3"
          $id_pegawai_array = explode(" | ", $row['id_data_pegawai']);

          // Ambil nama pegawai berdasarkan ID dari tabel `data_pegawai`
          $nama_pegawai_list = $this->dataPegawaiModel
            ->select('nama')
            ->whereIn('id_data_pegawai', $id_pegawai_array)
            ->findAll();

          // Ubah hasil query menjadi string "Andi | Siti | Joko"
          $row['nama_pegawai'] = implode("; ", array_column($nama_pegawai_list, 'nama'));
        }
        $data['nama_pegawai'] = $row['nama_pegawai'];
      }

      if (empty($data['inspeksi_tpp'])) {
        $msg = [
          'data' => '<p class="text-center text-muted">Tidak ada data</p>'
        ];
      } else {
        $msg = [
          'data' => view("prl/tpp_daftar_inspeksi", $data)
        ];
      }
      echo json_encode($msg);
    }
  }

  public function tambah_inspeksi_tpp($id = null)
  {
    $data['title'] = "PRL | Pengawasan Higiene Sanitasi Pangan Berbasis Risiko";
    $data['activeMenu'] = "tpp";

    $data['wilayahKerja'] = $this->unitKerjaModel->where('status_unit_kerja', 'Aktif')->findAll();
    $data['pegawai'] = $this->dataPegawaiModel->where('data_pegawai.status_bekerja', 'Aktif')->findAll();

    $data['siTPP'] = array();

    if ($id != null) {
      $data['getInspeksiTpp'] = $this->prlInspeksiTppModel
        ->join('prl_tpp', 'id_prl_tpp')
        ->where('id_prl_inspeksi_tpp', $id)->first();

      if ($data['getInspeksiTpp'])
        $data['siTPP'] = $this->prlTppModel->where('id_wilayah_kerja', $data['getInspeksiTpp']['id_wilayah_kerja'])->findAll();
    }

    // dd($data['getInspeksiTpp']);

    return view('crud/prl_tambah_inspeksi_tpp', $data);
  }

  // SAB ==================================================================================>
  public function sab()
  {
    $data['title'] = "PRL | SAB (Sarana Air Kebutuhan Sanitasi)";
    $data['activeMenu'] = "sab";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('prl/sab', $data);
  }

  public function sab_daftar($id = '')
  {
    if ($this->request->isAJAX()) {
      if ($id == '') {
        $data['sab'] = $this->prlSabModel
          ->select('prl_sab.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
          ->join('user', 'user.id_user = prl_sab.created_by')
          ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sab.id_wilayah_kerja')
          ->orderBy('prl_sab.updated_at DESC')
          ->findAll();
      } else {
      }
      $msg = [
        'data' => view("prl/sab_daftar", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function tambah_sab($id = null)
  {
    $data['title'] = "PRL | SAB (Sarana Air Kebutuhan Sanitasi)";
    $data['activeMenu'] = "sab";

    $data['wilayahKerja'] = $this->unitKerjaModel->where('status_unit_kerja', 'Aktif')->findAll();

    if ($id != null) {
      $data['getSab'] = $this->prlSabModel->where('id_prl_sab', $id)->first();
    }

    return view('crud/prl_tambah_sab', $data);
  }

  // INSPEKSI SAB ==================================================================================>
  public function sab_daftar_inspeksi($id = '')
  {
    if ($this->request->isAJAX()) {
      $dateRange = $this->request->getVar('dateRange');

      $getInspeksiSab = $this->prlInspeksiSabModel
        ->select('prl_sab.*, prl_inspeksi_sab.*, unit_kerja.unit_kerja as wilker, data_pegawai.nama as nama_pegawai')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = prl_inspeksi_sab.id_data_pegawai')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_inspeksi_sab.id_wilayah_kerja')
        ->join('prl_sab', 'prl_sab.id_prl_sab = prl_inspeksi_sab.id_prl_sab')
        ->orderBy('prl_inspeksi_sab.updated_at DESC');

      if ($dateRange) {
        [$startDate, $endDate] = explode(' - ', $dateRange);
        $getInspeksiSab->where('prl_inspeksi_sab.tanggal_inspeksi >=', $startDate)
          ->where('prl_inspeksi_sab.tanggal_inspeksi <=', $endDate);
      }

      $data['inspeksi_sab'] = $getInspeksiSab->findAll();

      foreach ($data['inspeksi_sab'] as &$row) {
        // Pecah ID Pegawai dari format "1 | 2 | 3"
        $id_pegawai_array = explode(" | ", $row['id_data_pegawai']);

        // Ambil nama pegawai berdasarkan ID dari tabel `data_pegawai`
        $nama_pegawai_list = $this->dataPegawaiModel
          ->select('nama')
          ->whereIn('id_data_pegawai', $id_pegawai_array)
          ->findAll();

        // Ubah hasil query menjadi string "Andi | Siti | Joko"
        $row['nama_pegawai'] = implode("; ", array_column($nama_pegawai_list, 'nama'));
      }
      $data['nama_pegawai'] = $row['nama_pegawai'];

      if (empty($data['inspeksi_sab'])) {
        $msg = [
          'data' => '<p class="text-center text-muted">Tidak ada data</p>'
        ];
      } else {
        $msg = [
          'data' => view("prl/sab_daftar_inspeksi", $data)
        ];
      }
      echo json_encode($msg);
    }
  }

  public function tambah_inspeksi_sab($id = null)
  {
    $data['title'] = "PRL | Pengawasan Sanitasi Sarana Air Kebutuhan Sanitasi";
    $data['activeMenu'] = "sab";

    $data['wilayahKerja'] = $this->unitKerjaModel->where('status_unit_kerja', 'Aktif')->findAll();
    $data['pegawai'] = $this->dataPegawaiModel->where('data_pegawai.status_bekerja', 'Aktif')->findAll();

    $data['siSAB'] = array();

    if ($id != null) {
      $data['getInspeksiSab'] = $this->prlInspeksiSabModel
        ->join('prl_sab', 'id_prl_sab')
        ->where('id_prl_inspeksi_sab', $id)->first();

      if ($data['getInspeksiSab'])
        $data['siSAB'] = $this->prlSabModel->where('id_wilayah_kerja', $data['getInspeksiSab']['id_wilayah_kerja'])->findAll();
    }

    // dd($data['getInspeksiSab']);

    return view('crud/prl_tambah_inspeksi_sab', $data);
  }

  // HSBU ==================================================================================>
  public function hsbu()
  {
    $data['title'] = "PRL | HSBU (Hygiene Sanitasi Bangunan Umum)";
    $data['activeMenu'] = "hsbu";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    return view('prl/hsbu', $data);
  }

  public function hsbu_daftar($id = '')
  {
    if ($this->request->isAJAX()) {
      if ($id == '') {
        $data['hsbu'] = $this->prlHsbuModel
          ->select('prl_hsbu.*, unit_kerja.unit_kerja as wilker, user.nama as nama_pegawai')
          ->join('user', 'user.id_user = prl_hsbu.created_by')
          ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_hsbu.id_wilayah_kerja')
          ->orderBy('prl_hsbu.updated_at DESC')
          ->findAll();
      } else {
      }
      $msg = [
        'data' => view("prl/hsbu_daftar", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function tambah_hsbu($id = null)
  {
    $data['title'] = "PRL | HSBU (Hygiene Sanitasi Bangunan Umum)";
    $data['activeMenu'] = "hsbu";

    $data['wilayahKerja'] = $this->unitKerjaModel->where('status_unit_kerja', 'Aktif')->findAll();

    if ($id != null) {
      $data['getHsbu'] = $this->prlHsbuModel->where('id_prl_hsbu', $id)->first();
    }

    return view('crud/prl_tambah_hsbu', $data);
  }

  // INSPEKSI HSBU ==================================================================================>
  public function hsbu_daftar_inspeksi($id = '')
  {
    if ($this->request->isAJAX()) {
      $dateRange = $this->request->getVar('dateRange');

      $getInspeksiHsbu = $this->prlInspeksiHsbuModel
        ->select('prl_hsbu.*, prl_inspeksi_hsbu.*, unit_kerja.unit_kerja as wilker, data_pegawai.nama as nama_pegawai')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = prl_inspeksi_hsbu.id_data_pegawai')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_inspeksi_hsbu.id_wilayah_kerja')
        ->join('prl_hsbu', 'prl_hsbu.id_prl_hsbu = prl_inspeksi_hsbu.id_prl_hsbu')
        ->orderBy('prl_inspeksi_hsbu.updated_at DESC');

      if ($dateRange) {
        [$startDate, $endDate] = explode(' - ', $dateRange);
        $getInspeksiHsbu->where('prl_inspeksi_hsbu.tanggal_inspeksi >=', $startDate)
          ->where('prl_inspeksi_hsbu.tanggal_inspeksi <=', $endDate);
      }
      $data['inspeksi_hsbu'] = $getInspeksiHsbu->findAll();

      foreach ($data['inspeksi_hsbu'] as &$row) {
        // Pecah ID Pegawai dari format "1 | 2 | 3"
        $id_pegawai_array = explode(" | ", $row['id_data_pegawai']);

        // Ambil nama pegawai berdasarkan ID dari tabel `data_pegawai`
        $nama_pegawai_list = $this->dataPegawaiModel
          ->select('nama')
          ->whereIn('id_data_pegawai', $id_pegawai_array)
          ->findAll();

        // Ubah hasil query menjadi string "Andi | Siti | Joko"
        $row['nama_pegawai'] = implode("; ", array_column($nama_pegawai_list, 'nama'));
      }
      $data['nama_pegawai'] = $row['nama_pegawai'];

      if (empty($data['inspeksi_hsbu'])) {
        $msg = [
          'data' => '<p class="text-center text-muted">Tidak ada data</p>'
        ];
      } else {
        $msg = [
          'data' => view("prl/hsbu_daftar_inspeksi", $data)
        ];
      }
      echo json_encode($msg);
    }
  }

  public function tambah_inspeksi_hsbu($id = null)
  {
    $data['title'] = "PRL | Pengawasan Hygiene Sanitasi Bangunan Umum";
    $data['activeMenu'] = "hsbu";

    $data['wilayahKerja'] = $this->unitKerjaModel->where('status_unit_kerja', 'Aktif')->findAll();
    $data['pegawai'] = $this->dataPegawaiModel->where('data_pegawai.status_bekerja', 'Aktif')->findAll();

    $data['siHSBU'] = array();

    if ($id != null) {
      $data['getInspeksiHsbu'] = $this->prlInspeksiHsbuModel
        ->join('prl_hsbu', 'id_prl_hsbu')
        ->where('id_prl_inspeksi_hsbu', $id)->first();

      if ($data['getInspeksiHsbu']) {
        $data['siHSBU'] = $this->prlHsbuModel->where('id_wilayah_kerja', $data['getInspeksiHsbu']['id_wilayah_kerja'])->findAll();

        if (!empty($data['getInspeksiHsbu']['lingkungan_luar_halaman'])) {
          $data['getInspeksiHsbu']['lingkungan_luar_halaman'] = unserialize($data['getInspeksiHsbu']['lingkungan_luar_halaman']);
        }
        if (!empty($data['getInspeksiHsbu']['ruang_bangunan'])) {
          $data['getInspeksiHsbu']['ruang_bangunan'] = unserialize($data['getInspeksiHsbu']['ruang_bangunan']);
        }
        if (!empty($data['getInspeksiHsbu']['penyehatan_air'])) {
          $data['getInspeksiHsbu']['penyehatan_air'] = unserialize($data['getInspeksiHsbu']['penyehatan_air']);
        }
        if (!empty($data['getInspeksiHsbu']['penyehatan_ruang_udara'])) {
          $data['getInspeksiHsbu']['penyehatan_ruang_udara'] = unserialize($data['getInspeksiHsbu']['penyehatan_ruang_udara']);
        }
        if (!empty($data['getInspeksiHsbu']['pengelolaan_limbah'])) {
          $data['getInspeksiHsbu']['pengelolaan_limbah'] = unserialize($data['getInspeksiHsbu']['pengelolaan_limbah']);
        }
        if (!empty($data['getInspeksiHsbu']['pencahayaan'])) {
          $data['getInspeksiHsbu']['pencahayaan'] = unserialize($data['getInspeksiHsbu']['pencahayaan']);
        }
        if (!empty($data['getInspeksiHsbu']['kebisingan_pada_ruang'])) {
          $data['getInspeksiHsbu']['kebisingan_pada_ruang'] = unserialize($data['getInspeksiHsbu']['kebisingan_pada_ruang']);
        }
        if (!empty($data['getInspeksiHsbu']['getaran_di_ruang_kerja'])) {
          $data['getInspeksiHsbu']['getaran_di_ruang_kerja'] = unserialize($data['getInspeksiHsbu']['getaran_di_ruang_kerja']);
        }
        if (!empty($data['getInspeksiHsbu']['pengendalian_vektor_penyakit'])) {
          $data['getInspeksiHsbu']['pengendalian_vektor_penyakit'] = unserialize($data['getInspeksiHsbu']['pengendalian_vektor_penyakit']);
        }
        if (!empty($data['getInspeksiHsbu']['instalasi'])) {
          $data['getInspeksiHsbu']['instalasi'] = unserialize($data['getInspeksiHsbu']['instalasi']);
        }
        if (!empty($data['getInspeksiHsbu']['pemeliharaan_jamban_dan_kamar_mandi'])) {
          $data['getInspeksiHsbu']['pemeliharaan_jamban_dan_kamar_mandi'] = unserialize($data['getInspeksiHsbu']['pemeliharaan_jamban_dan_kamar_mandi']);
        }
      }
    }

    // dd($data);

    return view('crud/prl_tambah_inspeksi_hsbu', $data);
  }
}
