<?php

namespace App\Controllers\Uklw;

use App\Controllers\BaseController;

class Ajax extends BaseController
{
  public function getDataPegawai()
  {
    if ($this->request->isAJAX()) {
      $data['dataPegawai'] = $this->dataPegawaiModel
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->join('status_pegawai', 'status_pegawai.id_status_pegawai=data_pegawai.id_status_pegawai')
        ->orderBy('nip', 'desc')
        ->findAll();

      $msg = [
        'data' => view('uklw/tableDataPegawai', $data)
      ];
      echo json_encode($msg);
    }
  }
  public function pasien($id)
  {
    if ($this->request->isAJAX()) {
      $data['getData'] = $this->uklwPasienModel->where('id_uklw_pasien', $id)->first();

      $msg = [
        'data' => view('uklw/viewPasien', $data)
      ];
      echo json_encode($msg);
    }
  }
  public function searchData()
  {
    if ($this->request->isAJAX()) {

      $msg = [];
      $searchTerm = $this->request->getVar("nilai");
      if ($searchTerm == null)
        $dataFromModel = $this->uklwICDSModel->orderBy('code', 'ASC')->findAll();
      else
        $dataFromModel = $this->uklwICDSModel->searchData($searchTerm);

      // Buat array untuk menyimpan data hasil transformasi
      $data = [];

      if ($dataFromModel) {
        // Transformasikan data dari model ke format yang diinginkan
        foreach ($dataFromModel as $row) {
          $data[] = [
            'value' => $row['code'], // Sesuaikan dengan nama kolom yang sesuai
            'label' => $row['code'] . ' | ' . $row['name_id'] . ' / ' . '<i>' . $row['name_en'] . '</i>',
          ];
        }

        $msg = [
          'data' => $data,
        ];
      }
      echo json_encode($msg);
    }
  }

  public function pemeriksaanView()
  {
    if ($this->request->isAJAX()) {
      $status_pemeriksaan = $this->request->getVar("status_pemeriksaan");
      $start = $this->request->getVar("start");
      $end = $this->request->getVar("end");
      $activeMenu = $this->request->getVar("activeMenu");
      $kota = $this->request->getVar("kota");

      if ($start == '' || $end == '') {
        $start = date('Y-m-d', strtotime('-3 months'));
        $end = date('Y-m-d');
      }

      $view = "";
      if ($activeMenu == "uklwPemkes" || $activeMenu == "uklwPenjamahMakanan") {
        $this->uklwPasienModel
          ->select("*, 
          uklw_pasien.nama, 
          DATE_FORMAT(uklw_pemkes.created_at, '%Y-%m-%d') as created_pemkes,
          DATE_FORMAT(uklw_catatan_pemeriksaan.created_at, '%Y-%m-%d') as created_catatan,
          created.nama AS created, 
          updated.nama as updated
        ");
        $this->uklwPasienModel->join('uklw_pemkes', 'id_uklw_pasien');
        $this->uklwPasienModel->join('uklw_catatan_pemeriksaan', 'id_uklw_pemkes', 'LEFT');
        $this->uklwPasienModel->join('user AS created', 'created.id_user=uklw_pemkes.created_by');
        $this->uklwPasienModel->join('user AS updated', 'updated.id_user=uklw_pemkes.created_by');
        if ($status_pemeriksaan != '') {
          $this->uklwPasienModel->where('status_pemeriksaan', $status_pemeriksaan);
        }
        if ($kota != '') {
          $this->uklwPasienModel->where('kota', $kota);
        }
        $this->uklwPasienModel->having('created_pemkes >=', $start);
        $this->uklwPasienModel->having('created_pemkes <=', $end);
        $this->uklwPasienModel->orderBy('uklw_pemkes.created_at', 'desc');
        $data['getData'] = $this->uklwPasienModel->findAll();
        if ($activeMenu == "uklwPenjamahMakanan") {
          $view = view('uklw/table/data_pemeriksaan_penjamah_makanan_view', $data);
        } else {
          $view = view('uklw/table/data_pemeriksaan_view', $data);
        }
      } elseif ($activeMenu == "uklwSkriningTBC") {
        $data['getData'] = $this->uklwPasienModel
          ->select("*, DATE_FORMAT(uklw_skrining_tbc.created_at, '%Y-%m-%d') as created_pemkes, uklw_pasien.nama, created.nama AS created, updated.nama as updated")
          ->join('uklw_skrining_tbc', 'id_uklw_pasien')
          ->join('user AS created', 'created.id_user=uklw_skrining_tbc.created_by')
          ->join('user AS updated', 'updated.id_user=uklw_skrining_tbc.created_by')
          ->having('created_pemkes >=', $start)
          ->having('created_pemkes <=', $end)
          ->orderBy('uklw_skrining_tbc.created_at')
          ->findAll();
        $view = view('uklw/table/data_skrining_tbc_view', $data);
      } elseif ($activeMenu == "uklwSkriningHIV") {
        $data['getData'] = $this->uklwPasienModel
          ->select("*, DATE_FORMAT(uklw_skrining_hiv.created_at, '%Y-%m-%d') as created_pemkes, uklw_pasien.nama, created.nama AS created, updated.nama as updated")
          ->join('uklw_skrining_hiv', 'id_uklw_pasien')
          ->join('user AS created', 'created.id_user=uklw_skrining_hiv.created_by')
          ->join('user AS updated', 'updated.id_user=uklw_skrining_hiv.created_by')
          ->having('created_pemkes >=', $start)
          ->having('created_pemkes <=', $end)
          ->orderBy('uklw_skrining_hiv.created_at')
          ->findAll();
        $view = view('uklw/table/data_skrining_hiv_view', $data);
      } elseif ($activeMenu == "uklwLaboratorium") {

        $data['getData'] = $this->uklwPasienModel
          ->select("*, DATE_FORMAT(uklw_laboratorium.created_at, '%Y-%m-%d') as created_pemkes, uklw_pasien.nama, created.nama AS created, updated.nama as updated")
          ->join('uklw_laboratorium', 'id_uklw_pasien')
          ->join('user AS created', 'created.id_user=uklw_laboratorium.created_by')
          ->join('user AS updated', 'updated.id_user=uklw_laboratorium.created_by')
          ->having('created_pemkes >=', $start)
          ->having('created_pemkes <=', $end)
          ->orderBy('uklw_laboratorium.created_at')
          ->findAll();
        $view = view('uklw/table/data_laboratorium_view', $data);
      }

      $msg = [
        'data' => $view,
        'cekData' => $data,
        'activeMenu' => $activeMenu,
        'start' => $start,
        'end' => $end,
      ];

      echo json_encode($msg);
    }
  }
  public function cekFarmasi($tanggal = null)
  {
    if ($this->request->isAJAX()) {
      if ($tanggal == null) {
        $tanggal = date('Y-m-d');
      }

      $cekData = $this->uklwCatatanPemeriksaanModel
        ->where('DATE(uklw_catatan_pemeriksaan.updated_at)', $tanggal)
        ->where('uklw_catatan_pemeriksaan.status_pemberian_obat', 'Tidak Diberikan')
        ->join('uklw_pemkes', 'id_uklw_pemkes')
        ->join('uklw_pasien', 'id_uklw_pasien')
        // ->orderBy('uklw_catatan_pemeriksaan.updated_at', 'desc')
        ->findAll();

      $json = [];
      foreach ($cekData as $data) {
        $planning = unserialize($data['planning']);

        $msg = [
          'id_uklw_catatan_pemeriksaan' => $data['id_uklw_catatan_pemeriksaan'],
          'nama_pasien' => $data['nama'],
          'data_obat' => $planning['planning_obat'],
        ];

        $json[] = $msg;
      }

      // foreach ($json as $data) {
      //   '<li class="list-group-item"><a href="/uklw/getCatatanPemeriksaan/pasien/' . $data['id_uklw_catatan_pemeriksaan'] . '" class="patient-link" data-name="' . $data['nama_pasien'] . '">' . $data['nama_pasien'] . '</a></li>';
      // }

      echo json_encode($json);
    }
  }
  public function getDataPasien()
  {
    if ($this->request->isAJAX()) {

      $json = $this->uklwPasienModel
        ->select('uklw_pasien.*')
        ->where('uklw_pasien.identitas !=', 'Tanpa Identitas')
        ->orderBy('updated_at', 'desc')->findAll();

      echo json_encode($json);
    }
  }
}
