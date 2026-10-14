<?php

namespace App\Controllers\Infografis;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function laporan()
  {
    $data['title'] = "Infografis | Laporan"; //kapal, pesawat, vaksin, infografis_validation_kapal, validation_pesawat, infografis_validation_vaksin
    $data['activeMenu'] = "infografis_laporan";
    
    $data['unitKerja'] = $this->unitKerjaModel->findAll();

    return view('infografis/laporan', $data);
  }
  public function kapal()
  {
    $data['title'] = "Infografis | Data Kapal"; //kapal, pesawat, vaksin, infografis_validation_kapal, validation_pesawat, infografis_validation_vaksin
    $data['activeMenu'] = "kapal";

    $data['data_kapal'] = $this->infografisKapalModel->select("*, infografis_kapal.created_at AS time")->join('user', 'user.id_user = infografis_kapal.updated_by')->orderBy('time', 'desc')->findAll();

    return view('users/infografis_kapal', $data);
  }
  public function dashboard_kapal()
  {
    $data['title'] = "Infografis | Data Kapal";
    $data['activeMenu'] = "dashboard_kapal";

    return view('users/infografis_dashboard_kapal', $data);
  }
  public function tambah_kapal()
  {
    $data['title'] = "Infografis | Tambah Data Kapal";
    $data['activeMenu'] = "kapal";
    $data['validation'] = \Config\Services::validation();

    if (session()->get('username')) {
      $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
        ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
        ->where('user.username', session()->get('username'))->first();

      if (!isset($getData)) {
        session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
        return redirect()->to('infografis/kapal');
      }
      // $date = date("Y-m-d");
      // $getData = $this->infografisKapalModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();

      // if ($getData) {
      //   session()->setFlashdata('info', 'Data pada hari ini ' . $date . ' pada unit kerja ' . $getData['unit_kerja'] . ' telah diisi, untuk lebih lanjut anda dapat melakukan update data.');
      //   return redirect()->to('infografis/update_kapal/' . $getData['id_infografis_kapal']);
      // }
    }

    return view('crud/infografis_tambah_kapal', $data);
  }
  public function update_kapal($id = null)
  {
    $data['title'] = "Infografis | Update Data Kapal";
    $data['activeMenu'] = "kapal";
    $data['validation'] = \Config\Services::validation();

    if (session()->get('username')) {
      $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
        ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
        ->where('user.username', session()->get('username'))->first();

      if (!isset($getData)) {
        session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
        return redirect()->to('infografis/kapal');
      }
      $cekData = $this->infografisKapalModel->where('id_infografis_kapal', $id)->first();

      if ($getData['unit_kerja'] != $cekData['unit_kerja']) {
        session()->setFlashdata('info', 'Anda berada di unit kerja ' . $getData['unit_kerja'] . '.');
        return redirect()->to('infografis/kapal');
      }
    }

    $data['infografis'] = $this->infografisKapalModel->where('id_infografis_kapal', $id)->first();

    if (isset($data['infografis'])) :
      return view('crud/infografis_update_kapal', $data);
    else :
      return redirect()->to('infografis/kapal');
    endif;
  }
  public function kapal_detail($id = null)
  {
    $data['activeMenu'] = "kapal";

    $data['infografis'] = $this->infografisKapalModel->select('infografis_kapal.*, user.nama AS created_name')->join('user', 'user.id_user=infografis_kapal.created_by')->where('id_infografis_kapal', $id)->first();
    $updated_name = $this->infografisKapalModel->select('user.nama AS updated_name')->join('user', 'user.id_user=infografis_kapal.updated_by')->where('id_infografis_kapal', $id)->first();
    $data['infografis'] += ['updated_name' => $updated_name['updated_name']];

    $data['title'] = "Infografis | Detail Data Kapal <br /><small>" . $data['infografis']['unit_kerja'] . "</small> (" . $data['infografis']['created_at'] . ")";

    if (isset($data['infografis'])) :
      return view('infografis/infografis_kapal_detail', $data);
    else :
      return redirect()->to('infografis/kapal');
    endif;
  }
  public function validation_kapal()
  {

    $data['title'] = "Infografis | Validation Data Kapal";
    $data['activeMenu'] = "infografis_validation_kapal";

    $data['rekapitulasi_kapal'] = $this->infografisKapalModel->select('
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
      created_at
      ')->groupBy('date("Y-m-d")', 'DESC')->limit(1)->findAll();

    return view('users/infografis_validation_kapal', $data);
  }


  public function pesawat()
  {
    $data['title'] = "Infografis | Data Pesawat"; //kapal, pesawat, vaksin, infografis_validation_kapal, validation_pesawat, infografis_validation_vaksin
    $data['activeMenu'] = "pesawat";

    $data['data_pesawat'] = $this->infografisPesawatModel->select("*, infografis_pesawat.created_at AS time")->join('user', 'user.id_user = infografis_pesawat.updated_by')->orderBy('time', 'desc')->findAll();

    return view('users/infografis_pesawat', $data);
  }
  public function dashboard_pesawat()
  {
    $data['title'] = "Infografis | Data Pesawat";
    $data['activeMenu'] = "dashboard_pesawat";

    return view('users/infografis_dashboard_pesawat', $data);
  }
  public function tambah_pesawat()
  {
    $data['title'] = "Infografis | Tambah Data Pesawat";
    $data['activeMenu'] = "pesawat";
    $data['validation'] = \Config\Services::validation();

    if (session()->get('username')) {
      $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
        ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
        ->where('user.username', session()->get('username'))->first();

      if (!isset($getData)) {
        session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
        return redirect()->to('infografis/pesawat');
      }
      // $date = date("Y-m-d");
      // $getData = $this->infografisPesawatModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();

      // if ($getData) {
      //   session()->setFlashdata('info', 'Data pada hari ini ' . $date . ' pada unit kerja ' . $getData['unit_kerja'] . ' telah diisi, untuk lebih lanjut anda dapat melakukan update data.');
      //   return redirect()->to('infografis/update_pesawat/' . $getData['id_infografis_pesawat']);
      // }
    }

    return view('crud/infografis_tambah_pesawat', $data);
  }
  public function update_pesawat($id = null)
  {
    $data['title'] = "Infografis | Update Data Pesawat";
    $data['activeMenu'] = "pesawat";
    $data['validation'] = \Config\Services::validation();

    if (session()->get('username')) {
      $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
        ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
        ->where('user.username', session()->get('username'))->first();

      if (!isset($getData)) {
        session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
        return redirect()->to('infografis/pesawat');
      }
      if ($id == null) {
        $date = date("Y-m-d");
        $data['data_pesawat'] = $this->infografisPesawatModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();
      }
      $cekData = $this->infografisPesawatModel->where('id_infografis_pesawat', $id)->first();

      if ($getData['unit_kerja'] != $cekData['unit_kerja']) {
        session()->setFlashdata('info', 'Anda berada di unit kerja ' . $getData['unit_kerja'] . '.');
        return redirect()->to('infografis/pesawat');
      }
    }

    $data['infografis'] = $this->infografisPesawatModel->where('id_infografis_pesawat', $id)->first();

    if (isset($data['infografis'])) :
      return view('crud/infografis_update_pesawat', $data);
    else :
      return redirect()->to('infografis/pesawat');
    endif;
  }

  public function pesawat_detail($id = null)
  {
    $data['activeMenu'] = "pesawat";

    $data['infografis'] = $this->infografisPesawatModel->select('infografis_pesawat.*, user.nama AS created_name')->join('user', 'user.id_user=infografis_pesawat.created_by')->where('id_infografis_pesawat', $id)->first();
    $updated_name = $this->infografisPesawatModel->select('user.nama AS updated_name')->join('user', 'user.id_user=infografis_pesawat.updated_by')->where('id_infografis_pesawat', $id)->first();
    $data['infografis'] += ['updated_name' => $updated_name['updated_name']];

    $data['title'] = "Infografis | Detail Data Pesawat <br /><small>" . $data['infografis']['unit_kerja'] . "</small> (" . $data['infografis']['created_at'] . ")";

    if (isset($data['infografis'])) :
      return view('infografis/infografis_pesawat_detail', $data);
    else :
      return redirect()->to('infografis/pesawat');
    endif;
  }

  public function vaksin()
  {
    $data['title'] = "Infografis | Data Vaksinasi"; //kapal, pesawat, vaksin, infografis_validation_kapal, validation_pesawat, infografis_validation_vaksin
    $data['activeMenu'] = "vaksin";

    $data['data_vaksin'] = $this->infografisVaksinModel->select("*, infografis_vaksin.created_at AS time")->join('user', 'user.id_user = infografis_vaksin.updated_by')->orderBy('time', 'desc')->findAll();

    return view('users/infografis_vaksin', $data);
  }

  public function dashboard_vaksin()
  {
    $data['title'] = "Infografis | Data Vaksinasi";
    $data['activeMenu'] = "dashboard_vaksin";

    return view('users/infografis_dashboard_vaksin', $data);
  }
  public function tambah_vaksin()
  {
    $data['title'] = "Infografis | Tambah Data Vaksinasi";
    $data['activeMenu'] = "vaksin";
    $data['validation'] = \Config\Services::validation();

    if (session()->get('username')) {
      $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
        ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
        ->where('user.username', session()->get('username'))->first();

      if (!isset($getData)) {
        session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
        return redirect()->to('infografis/vaksin');
      }
      // $date = date("Y-m-d");
      // $getData = $this->infografisVaksinModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();

      // if ($getData) {
      //   session()->setFlashdata('info', 'Data pada hari ini ' . $date . ' pada unit kerja ' . $getData['unit_kerja'] . ' telah diisi, untuk lebih lanjut anda dapat melakukan update data.');
      //   return redirect()->to('infografis/update_vaksin/' . $getData['id_infografis_vaksin']);
      // }
    }

    return view('crud/infografis_tambah_vaksin', $data);
  }
  public function update_vaksin($id = null)
  {
    $data['title'] = "Infografis | Update Data Vaksinasi";
    $data['activeMenu'] = "vaksin";
    $data['validation'] = \Config\Services::validation();

    if (session()->get('username')) {
      $getData = $this->dataPegawaiModel->join('user', 'data_pegawai.id_user=user.id_user')
        ->join('unit_kerja', 'data_pegawai.id_unit_kerja=unit_kerja.id_unit_kerja')
        ->where('user.username', session()->get('username'))->first();

      if (!isset($getData)) {
        session()->setFlashdata('info', 'Maaf hanya orang tertentu yang dapat mengakses.');
        return redirect()->to('infografis/vaksin');
      }
      if ($id == null) {
        $date = date("Y-m-d");
        $data['data_vaksin'] = $this->infografisVaksinModel->LIKE(['created_at' => $date, 'unit_kerja' => $getData['unit_kerja']])->first();
      }
      $cekData = $this->infografisVaksinModel->where('id_infografis_vaksin', $id)->first();

      if ($getData['unit_kerja'] != $cekData['unit_kerja']) {
        session()->setFlashdata('info', 'Anda berada di unit kerja ' . $getData['unit_kerja'] . '.');
        return redirect()->to('infografis/vaksin');
      }
    }

    $data['infografis'] = $this->infografisVaksinModel->where('id_infografis_vaksin', $id)->first();
    // dd($data['infografis']);

    if (isset($data['infografis'])) :
      return view('crud/infografis_update_vaksin', $data);
    else :
      return redirect()->to('infografis/vaksin');
    endif;
  }
  public function vaksin_detail($id = null)
  {
    $data['activeMenu'] = "vaksin";

    $data['infografis'] = $this->infografisVaksinModel->select('infografis_vaksin.*, user.nama AS created_name')->join('user', 'user.id_user=infografis_vaksin.created_by')->where('id_infografis_vaksin', $id)->first();
    $updated_name = $this->infografisVaksinModel->select('user.nama AS updated_name')->join('user', 'user.id_user=infografis_vaksin.updated_by')->where('id_infografis_vaksin', $id)->first();
    $data['infografis'] += ['updated_name' => $updated_name['updated_name']];

    $data['title'] = "Infografis | Detail Data Vaksinasi <br /><small>" . $data['infografis']['unit_kerja'] . "</small> (" . $data['infografis']['created_at'] . ")";

    if (isset($data['infografis'])) :
      return view('infografis/infografis_vaksin_detail', $data);
    else :
      return redirect()->to('infografis/vaksin');
    endif;
  }
}
