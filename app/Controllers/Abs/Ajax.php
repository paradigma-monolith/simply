<?php

namespace App\Controllers\Abs;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;
  public function getLantai()
  {
    if ($this->request->isAJAX()) {
      $data['lantai'] = $this->absLantaiModel
        ->select('abs_lantai.*, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = abs_lantai.created_by')
        ->orderBy('abs_lantai.lantai', 'ASC')
        ->findAll();

      echo json_encode(['data' => view('/abs/table-lantai', $data)]);
    }
  }
  public function getRuang()
  {
    if ($this->request->isAJAX()) {
      $data['ruang'] = $this->absRuangModel
        ->select('abs_ruang.*, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = abs_ruang.created_by')
        ->orderBy('abs_ruang.ruang', 'ASC')
        ->findAll();

      echo json_encode(['data' => view('/abs/table-ruang', $data)]);
    }
  }
  public function getJenisBarang()
  {
    if ($this->request->isAJAX()) {
      $data['jenis'] = $this->absJenisModel
        ->select('abs_jenis.*, user.nama as nama_pegawai')
        ->join('user', 'user.id_user = abs_jenis.created_by')
        ->orderBy('abs_jenis.jenis', 'ASC')
        ->findAll();

      echo json_encode(['data' => view('/abs/table-jenis-barang', $data)]);
    }
  }
  public function getDataBarang()
  {
    if ($this->request->isAJAX()) {
      $label1 = $this->request->getVar("label"); // all, status_tersedia, jenis, unit_kerja
      $label2 = $this->request->getVar("label2"); // Lantai
      $label3 = $this->request->getVar("label3"); // Ruang
      $nilai1 = $this->request->getVar("nilai");
      $nilai2 = $this->request->getVar("nilai2");
      $nilai3 = $this->request->getVar("nilai3");

      $data['unit_kerja'] = $this->unitKerjaModel->orderBy('unit_kerja', 'asc')->findAll();
      $data['lantai'] = $this->absLantaiModel->orderBy('lantai', 'asc')->findAll();
      $data['ruang'] = $this->absRuangModel->orderBy('ruang', 'asc')->findAll();
      $data['jenis'] = $this->absJenisModel->orderBy('jenis', 'asc')->findAll();

      if ($label1 != null && $label2 != null && $label3 != null) {
        // $data = "Pasti Data Ruang";
        $data['getData'] = $this->absLokasiModel
          ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
          ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
          ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
          ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left')
          ->where('unit_kerja.unit_kerja', ($nilai1 ? $nilai1 : null))
          ->where('abs_lantai.lantai', ($nilai2 ? $nilai2 : null))
          ->where('abs_ruang.ruang', ($nilai3 ? $nilai3 : null))
          ->orderBy('abs_lokasi.created_at', 'desc')
          ->findAll();
        $view = '/abs/ruang_data_barang';
      } else if ($label1 != null && $label2 != null) {
        // $data = "Pasti Data Lantai";
        $data['getData'] = $this->absLokasiModel
          ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
          ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
          ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
          ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left')
          ->where('unit_kerja.unit_kerja', ($nilai1 ? $nilai1 : null))
          ->where('abs_lantai.lantai', ($nilai2 ? $nilai2 : null))
          ->orderBy('abs_lokasi.created_at', 'desc')
          ->findAll();
        $view = '/abs/lantai_data_barang';
      } else if ($label1 != null) {
        if ($label1 == 'all') {
          // $data = "Semua data tanpa kecuali";
          $data['getData'] = $this->absLokasiModel
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
            ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
            ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
            ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left')
            ->orderBy('abs_lokasi.created_at', 'desc')
            ->findAll();
          $view = '/abs/all_data_barang';
        } else if ($label1 == 'unit_kerja') {
          // $data = "Semua Data Unit Kerja";
          $data['getData'] = $this->absLokasiModel
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
            ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
            ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
            ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left')
            ->where('unit_kerja.unit_kerja', ($nilai1 ? $nilai1 : null))
            ->orderBy('abs_lokasi.created_at', 'desc')
            ->findAll();
          $view = '/abs/unit_kerja_data_barang';
        } else if ($label1 == 'status_tersedia') {
          // $data = "Semua Data berdasarkan Status";
          $data['getData'] = $this->absLokasiModel
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
            ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
            ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
            ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left')
            ->where('abs_lokasi.status_tersedia', ($nilai1 ? $nilai1 : null))
            ->orderBy('abs_lokasi.created_at', 'desc')
            ->findAll();
          $view = '/abs/status_data_barang';
        } else if ($label1 == 'jenis') {
          // $data = "Semua Data berdasarkan Jenis";
          $data['getData'] = $this->absLokasiModel
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja=abs_lokasi.id_unit_kerja', 'left')
            ->join('abs_lantai', 'abs_lantai.id_abs_lantai=abs_lokasi.id_abs_lantai', 'left')
            ->join('abs_ruang', 'abs_ruang.id_abs_ruang=abs_lokasi.id_abs_ruang', 'left')
            ->join('abs_jenis', 'abs_jenis.id_abs_jenis=abs_lokasi.id_abs_jenis', 'left')
            ->where('abs_jenis.jenis', ($nilai1 ? $nilai1 : null))
            ->orderBy('abs_lokasi.created_at', 'desc')
            ->findAll();
          $view = '/abs/kategori_data_barang';
        } else {
          // $data = "Data tidak ditemukan";
          $data['getData'] = array();
        }
      } else {
        // $data = "Data tidak ditemukan";
        $data['getData'] = array();
      }

      $msg = [
        'data' => view($view, $data)
      ];
      echo json_encode($msg);
    }
  }
  public function getImageAbs()
  {
    if ($this->request->isAJAX()) {
      $data['kategori'] = $this->absLokasiModel->join('abs_jenis', 'id_abs_jenis')
        ->join('abs_image', 'id_abs_image')
        ->groupBy('id_abs_jenis, id_abs_image')
        ->findAll();

      $data['filterKategori'] = $this->absLokasiModel->join('abs_jenis', 'id_abs_jenis')
        ->join('abs_image', 'id_abs_image')
        ->groupBy('id_abs_jenis')
        ->findAll();

      $gambar = $this->absImageModel->findAll();
      $dataImage = [];
      foreach ($gambar as $i => $g) {
        $setGambar = '';
        foreach ($data['kategori'] as $k) {
          if ($k['id_abs_image'] == $g['id_abs_image']) {
            if ($setGambar == '') {
              $setGambar .= $k['id_abs_jenis'];
            } else {
              $setGambar .= ', ' . $k['id_abs_jenis'];
            }
          }
        }
        $dataImage[$i] = [
          'id_abs_image' => $g['id_abs_image'],
          'data_jenis' => $setGambar,
          'image' => $g['image']
        ];
      }
      $data['gambar'] = $dataImage;

      $msg = [
        'data' => view('abs/getImageAbs', $data)
      ];
      echo json_encode($msg);
    }
  }
  public function loadLabelABS()
  {
    if ($this->request->isAJAX()) {
      $data['label'] = $this->absLokasiModel
        ->groupBy('data_label')
        ->findAll();

      $msg = [
        'data' => view('abs/getLabelAbs', $data)
      ];
      echo json_encode($msg);
    }
  }
  public function dataABS()
  {
    if ($this->request->isAJAX()) {
      $qr = $this->request->getVar("qrCode");
      $data['label'] = $this->absLokasiModel
        ->join('unit_kerja', 'id_unit_kerja', 'LEFT')
        ->join('abs_lantai', 'id_abs_lantai', 'LEFT')
        ->join('abs_ruang', 'id_abs_ruang', 'LEFT')
        ->join('abs_jenis', 'id_abs_jenis', 'LEFT')
        ->join('abs_image', 'id_abs_image', 'LEFT')
        ->where("kode_qr_code_pusat", $qr)
        ->first();

      $msg = [
        'getData' => $data['label'],
        'data' => view('abs/getVerifikasiDataAbs', $data)
      ];
      echo json_encode($msg);
    }
  }
  public function getFile()
  {
    if ($this->request->isAJAX()) {
      $data['getFile'] = $this->absExportLabelModel->join('user', 'user.id_user=abs_export_label.created_by')->orderBy('abs_export_label.created_at', 'asc')->findAll();

      $msg = [
        'data' => view('/abs/getFile', $data)
      ];
      echo json_encode($msg);
    }
  }
  public function countLabel()
  {
    if ($this->request->isAJAX()) {
      $count = $this->absLokasiModel->orderBy('id_abs_bangunan', 'asc')->findAll();
      $kertas = $this->request->getVar("kertas");
      $by = $this->userModel->where('username', session()->get('username'))->first();

      $x = count($count);
      $data = [];
      $n = 0;
      $berhasil = 0;
      for ($i = 0; $i < $x; $i++) {
        $nama_file = 'abs_file_' . date('d-m-y-H-i-s')  . '.pdf';
        $data = [
          'kertas' => $kertas,
          'id_awal' => $count[$i]['id_abs_bangunan']
        ];

        if ($x >= ($i + 440)) {
          $data += [
            'id_akhir' => $count[$i + ($i == 0 ? 440 : 441)]['id_abs_bangunan']
          ];
          $i += ($i == 0 ? 440 : 441);
        } else {
          $data += [
            'id_akhir' => $count[$x - 1]['id_abs_bangunan']
          ];
          $i = $x;
        }
        $cekData = $this->absExportLabelModel->where($data)->findAll();
        if ($cekData) {
        } else {

          $isData = $this->absLokasiModel
            ->where('id_abs_bangunan >=', $data['id_awal'])
            ->where('id_abs_bangunan <=', $data['id_akhir'])
            ->findAll();
          $lembar = 0;
          if ($kertas == "1m") {
            $lembar = 1;
          } else {
            $lembar = (count($isData) / 24) + 1;
          }

          $data += [
            'file' => $nama_file,
            'lembar' => $lembar,
            'created_by' => $by['id_user'],
            'updated_by' => $by['id_user'],
          ];
          if ($this->absExportLabelModel->save($data)) {
            $berhasil++;
          }
        }
        $n++;
      }

      echo json_encode(['data' => $cekData]);
    }
  }
  public function data_abs()
  {
    if ($this->request->isAJAX()) {

      $kode_barang = $this->request->getVar("kode_barang");

      $data['nama_barang'] = $this->request->getVar("nama_barang");
      $data['getData'] = $this->absLokasiModel->like('data_barang', $kode_barang)
        ->join('unit_kerja', 'id_unit_kerja', 'left')
        ->join('abs_ruang', 'id_abs_ruang', 'left')
        ->join('abs_lantai', 'id_abs_lantai', 'left')
        ->findAll();
      $data['kode_barang'] = $kode_barang;

      $msg = [
        'data' => view('abs/data_barang', $data)
      ];

      echo json_encode($msg);
    }
  }
  public function pengajuan_pemindahan()
  {
    if ($this->request->isAJAX()) {
      $username = $this->request->getVar("username");
      $cek = $this->userModel->where('username', $username)->first();

      $data_asal = [];
      $data_tujuan = [];
      for ($i = 0; $i < count($this->request->getVar("data_asal")); $i++) {
        $data_asal[$i] = $this->request->getVar("data_asal")[$i];
        $tujuan = '';
        if ($i == 0) {
          $tujuan = $this->request->getVar("data_tujuan")[$i];
          $tujuan = $this->unitKerjaModel->where('id_unit_kerja', $tujuan)->first()['unit_kerja'];
        } elseif ($i == 1) {
          $tujuan = $this->request->getVar("data_tujuan")[$i];
          $tujuan = $this->absLantaiModel->where('id_abs_lantai', $tujuan)->first()['lantai'];
        } elseif ($i == 2) {
          $tujuan = $this->request->getVar("data_tujuan")[$i];
          $tujuan = $this->absRuangModel->where('id_abs_ruang', $tujuan)->first()['ruang'];
        }
        $data_tujuan[$i] = $tujuan;
      }

      $data_asal = serialize($data_asal);
      $data_tujuan = serialize($data_tujuan);

      $data = [
        'data_asal' => $data_asal,
        'data_tujuan' => $data_tujuan,
      ];
      if ($cek) {
        $data += [
          'id_user' => $cek['id_user'],
          'id_abs_bangunan' => $this->request->getVar("id_abs"),
          'status' => 'System-Authorized Actions'
        ];
        $msg = [
          'pesan' => 'Sukses'
        ];
      } else {
        $data += [
          'id_user' => '0',
          'status' => 'Ditolak'
        ];
        $msg = [
          'pesan' => 'Ditolak'
        ];
        if (session()->get('blocked')) {
          $ses_data['blocked'] = session()->get('blocked') + 1;
          session()->set($ses_data);
        } else {
          $ses_data['blocked'] = 1;
          session()->set($ses_data);
        }
      }
      if (session()->get('blocked') && session()->get('blocked') > 3) {
      } else {
        if ($data_asal != $data_tujuan) {
          if ($this->absPengajuanPemindahanModel->save($data)) {
            $msg += [
              'data' => 'sukses'
            ];
            if ($cek) {
              for ($i = 0; $i < count($this->request->getVar("data_asal")); $i++) {
                $pembaharuanData['id_abs_bangunan'] = $this->request->getVar("id_abs");
                $pembaharuanData['updated_by'] = $cek['id_user'];
                if ($i == 0) {
                  $id_unit_kerja = $this->request->getVar("data_tujuan")[$i];
                  $pembaharuanData['id_unit_kerja'] = $id_unit_kerja;
                } elseif ($i == 1) {
                  $id_abs_lantai = $this->request->getVar("data_tujuan")[$i];
                  $pembaharuanData['id_abs_lantai'] = $id_abs_lantai;
                } elseif ($i == 2) {
                  $id_abs_ruang = $this->request->getVar("data_tujuan")[$i];
                  $pembaharuanData['id_abs_ruang'] = $id_abs_ruang;
                }
              }
              $this->absLokasiModel->save($pembaharuanData);
            }
          }
        } else {
          $msg += [
            'data' => 'gagal'
          ];
        }
      }
      echo json_encode($msg);
    }
  }
  public function pengajuan_pemeliharaan()
  {
    if ($this->request->isAJAX()) {
      $username = $this->request->getVar("username");
      $cek = $this->userModel->where('username', $username)->first();
      $id_abs_bangunan = $this->request->getVar("id_abs");
      $keterangan = $this->request->getVar("keterangan");

      $data = [];
      $msg = [];
      if ($cek) {
        $base64_string = $keterangan;

        // Extract base64 images
        preg_match_all('/data:image\/(.*?);base64,(.*?)"/', $base64_string, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
          $image_extension = $match[1]; // Get the image extension
          $image_data = base64_decode($match[2]); // Get the image data
          $filename = uniqid() . '.' . $image_extension;
          $filepath = FCPATH . 'uploads_abs/' . $filename; // Save the image in the uploads_abs folder (adjust the path as needed)

          helper('filesystem');
          write_file($filepath, $image_data);
          // Update the base64 string with the file path
          $base64_string = str_replace('data:image/' . $image_extension . ';base64,' . $match[2], '/uploads_abs/' . $filename, $base64_string);
        }

        $data += [
          'id_user' => $cek['id_user'],
          'id_abs_bangunan' => $id_abs_bangunan,
          'keterangan' => $base64_string,
          'created_by' => $cek['id_user'],
          'updated_by' => $cek['id_user'],
        ];
        if ($this->absPengajuanPemeliharaanModel->save($data)) {
          $msg = [
            'pesan' => 'Sukses',
            'data' => 'sukses',
            'cekData' => $filepath
          ];
        }
      } else {
        if (session()->get('blocked')) {
          $ses_data['blocked'] = session()->get('blocked') + 1;
          session()->set($ses_data);
        } else {
          $ses_data['blocked'] = 1;
          session()->set($ses_data);
        }
      }
      echo json_encode($msg);
    }
  }
  
  public function konfirmasiDataInventaris()
  {
    if ($this->request->isAJAX()) {
      $id_abs_bangunan = $this->request->getVar("id_abs_bangunan");
      $id_unit_kerja = $this->request->getVar("id_unit_kerja");
      $id_abs_lantai = $this->request->getVar("id_abs_lantai");
      $id_abs_ruang = $this->request->getVar("id_abs_ruang");
      $id_abs_jenis = $this->request->getVar("id_abs_jenis");

      $data = [
        'id_abs_bangunan' => $id_abs_bangunan,
        'id_unit_kerja' => $id_unit_kerja,
        'id_abs_lantai' => $id_abs_lantai,
        'id_abs_ruang' => $id_abs_ruang,
        'id_abs_jenis' => $id_abs_jenis,
      ];

      $msg = [];
      if ($this->absLokasiModel->save($data)) {
        $msg = [
          'data' => 'sukses'
        ];
      }
      echo json_encode($msg);
    }
  }
}
