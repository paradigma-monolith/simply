<?php

namespace App\Controllers\Relaxtime;

use App\Controllers\BaseController;

class Crud extends BaseController
{
    
  public function hapus_naskah($id)
  {
    if ($id) {
      $cek = $this->relaxtimeRealisasiFileModel->where('id_relaxtime_realisasi_file', $id)->first();
      if ($cek) {
        $path = './relaxtime/realisasi/' . $cek['file'];
      }
    }

    if ($cek && $cek['file'] != '')
      if (file_exists($path))
        unlink($path);

    $this->relaxtimeRealisasiFileModel->delete($id);
  }

  public function simpan_hasil_rapat()
  {
    $file = $this->request->getFile('file_dokumen');
    
    if ($file && $file->isValid() && !$file->hasMoved()) {
      $acak = rand();
      $fileName = $file->getName();
      $cleanName = preg_replace('/[^\w\d\s.]/', '', $fileName);
      $newFile = $acak . '_' . $cleanName;
      
      $file->move('./uploads/relaxtime/hasil_rapat/', $newFile);

      $data = [
        'nama_dokumen' => $this->request->getPost('nama_dokumen'),
        'tanggal' => $this->request->getPost('tanggal'),
        'file_dokumen' => $newFile
      ];

      $this->relaxtimeHasilRapatModel->insert($data);
      session()->setFlashdata('pesan', 'Dokumen berhasil diupload!');
    } else {
      session()->setFlashdata('error', 'Gagal mengupload dokumen!');
    }

    return redirect()->to('/relaxtime/hasil_rapat_monev');
  }

  public function hapus_hasil_rapat($id)
  {
    $dokumen = $this->relaxtimeHasilRapatModel->find($id);
    
    if ($dokumen) {
      $path = './uploads/relaxtime/hasil_rapat/' . $dokumen['file_dokumen'];
      if (file_exists($path)) {
        unlink($path);
      }
      $this->relaxtimeHasilRapatModel->delete($id);
      session()->setFlashdata('pesan', 'Dokumen berhasil dihapus!');
    }

    return redirect()->to('/relaxtime/hasil_rapat_monev');
  }
  
  public function upload_file($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_relaxtime_realisasi_file' => $id];

      $file = $this->request->getFile("unggah_naskah");
      $acak = rand();

      $cek = false;
      if ($id != null) {
        $cek = $this->relaxtimeRealisasiFileModel->where('id_relaxtime_realisasi_file', $id)->first();
        if ($cek) {
          $path = './relaxtime/realisasi/' . $cek['file'];
        }
      }

      if ($file != '') {
        $fileName = $file->getName();
        $cleanName = preg_replace('/[^\w\d\s.]/', '', $fileName);
        $newFile = $acak . '_' . $cleanName;
        $file->move('./relaxtime/realisasi/', $newFile);
        $data += ['file' => $newFile];
        if ($cek && $cek['file'] != '')
          if (file_exists($path))
            unlink($path);
      }

      $data += [
        'jenis_naskah'  => $this->request->getVar("jenis_naskah"),
        'nama_file'     => $this->request->getVar("nama_file"),
        'created_by'    => $by['id_user'],
        'updated_by'    => $by['id_user']
      ];

      if ($this->relaxtimeRealisasiFileModel->save($data)) {
        $msg = [
          'sukses' => $data
        ];
      } else {
        $msg = [
          'error' => "Maaf terjadi kesalahan saat penyimpanan, silahkan coba kembali!"
        ];
      }
      echo json_encode($msg);
    }
  }
  public function realisasi()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel->where("username", session()->get('username'))->first();

      $realisasi = $this->request->getVar("realisasi");
      $realisasi = preg_replace("/[^0-9,]/", "", $realisasi);
      $realisasi = str_replace(',', '.', $realisasi);

      $data = [
        "id_relaxtime_detail_uraian" => $this->request->getVar("id_realisasi"),
        "realisasi" => $realisasi,
        "tanggal" => $this->request->getVar("tanggal"),
        "created_by" => $by['id_user'],
        "updated_by" => $by['id_user'],
      ];

      $msg = [false];
      if (strtotime($this->request->getVar("tanggal")) !== false) {
        if ($this->relaxtimeRealisasiModel->save($data)) {
          //   $newId = $this->relaxtimeRealisasiModel->getInsertID();
          //   $timeline = [
          //     'icon'        => 'fas fa-save bg-primary',
          //     'id_table'    => $newId,
          //     'table'       => 'relaxtime_realisasi',
          //     'id_user'     => $by['id_user'],
          //     'aktivitas'   => 'menambahkan data RELAXTIME yang terealisasikan sebanyak ' . $this->request->getVar("realisasi") . ' pada tanggal yang dipilih (' . mediumdate_indo($this->request->getVar("tanggal")) . ')',
          //     'keterangan'  => "",
          //   ];
          //   $this->linimasaModel->save($timeline);

          $msg = [
            "data" => 'sukses'
          ];
        }
      }

      echo json_encode($msg);
    }
  }
  public function realisasiUpdate()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_realisasi");
      $dataSebelumnya = $this->relaxtimeRealisasiModel->where('id_relaxtime_realisasi', $id)->first();
      $realisasi = $this->request->getVar("realisasi");
      $realisasi = preg_replace("/[^0-9,]/", "", $realisasi);
      $realisasi = str_replace(',', '.', $realisasi);

      $linimasa = false;
      $elemen = '';
      if ($dataSebelumnya['tanggal'] != $this->request->getVar("tanggal")) {
        $elemen .= "<div class='col-12'><strong>Tanggal</strong></div>
              <div class='col-6 border'>" . $dataSebelumnya['tanggal'] . "</div>
              <div class='col-6 border'>" . $this->request->getVar("tanggal") . "</div>";
        $linimasa = true;
      }
      if ($dataSebelumnya['realisasi'] != $realisasi) {
        $elemen .= "<div class='col-12'><strong>Realisasi</strong></div>
            <div class='col-6 border'>Rp" . number_format($dataSebelumnya['realisasi'], 2, ',', '.') . "</div>
            <div class='col-6 border'>Rp" . number_format($realisasi, 2, ',', '.') . "</div>";
        $linimasa = true;
      }
      $msg = [false];
      if ($linimasa) {

        $by = $this->userModel->where("username", session()->get('username'))->first();

        $data = [
          "id_relaxtime_realisasi" => $this->request->getVar("id_realisasi"),
          "realisasi" => $realisasi,
          "tanggal" => $this->request->getVar("tanggal"),
          "updated_by" => $by['id_user'],
        ];

        if (strtotime($this->request->getVar("tanggal")) !== false) {
          if ($this->relaxtimeRealisasiModel->save($data)) {
            $timeline = [
              'icon'        => 'fas fa-edit bg-warning',
              'id_table'    => $id,
              'table'       => 'relaxtime_realisasi',
              'id_user'     => $by['id_user'],
              'aktivitas'   => 'memperbaharui data RELAXTIME',
              'keterangan'  => '<div class="row">' . $elemen . '</div class="row">',
            ];
            $this->linimasaModel->save($timeline);

            $msg = [
              "data" => 'sukses'
            ];
          }
        }
      } else {
        $msg = [
          "data" => 'tidak ada perubahan'
        ];
      }

      echo json_encode($msg);
    }
  }
  public function insertAkses()
  {
    if ($this->request->isAjax()) {
      $by = $this->userModel->where('username', session()->get('username'))->first();
      $msg = [];
      $user = $this->request->getVar("id");
      $cek = $this->relaxtimeAksesModel->where('id_user', $user)->first();
      $icon = '';
      $userName = $this->userModel->where('id_user', $user)->first();
      $aktivitas = '';

      $data = [];
      if ($cek) {
        $data += [
          'id_relaxtime_akses' => $cek['id_relaxtime_akses'],
        ];
        $icon = 'fas fa-user-edit bg-warning';
        $aktivitas = 'memperbaharui akses RELAXTIME PAGU kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diterima)</i>';
      } else {
        $icon = 'fas fa-user-plus bg-success';
        $aktivitas = 'menambahkan akses baru RELAXTIME PAGU kepada user <strong>' . name_capitalize($userName['nama']) . '</strong>';
      }

      $data += [
        'id_user' => $user,
        'status' => 'Akses Diterima',
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
      if ($this->relaxtimeAksesModel->save($data)) {
        $newId = $this->relaxtimeAksesModel->getInsertID();
        $timeline = [
          'icon'        => $icon,
          'id_table'    => $newId,
          'table'       => 'relaxtime_akses',
          'id_user'     => $by['id_user'],
          'aktivitas'   => $aktivitas,
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        $msg = [
          'data' => 'sukses'
        ];
      } else {
        $msg = [
          'data' => 'error'
        ];
      }
      echo json_encode($msg);
    }
  }
  public function removeAkses()
  {
    if ($this->request->isAjax()) {
      $by = $this->userModel->where('username', session()->get('username'))->first();
      $msg = [];
      $user = $this->request->getVar("id");
      $cek = $this->relaxtimeAksesModel->where('id_user', $user)->first();
      $icon = '';
      $userName = $this->userModel->where('id_user', $user)->first();
      $aktivitas = '';

      $data = [];
      if ($cek) {
        $data += [
          'id_relaxtime_akses' => $cek['id_relaxtime_akses'],
        ];
        $icon = 'fas fa-user-alt-slash bg-danger';
        $aktivitas = 'memperbaharui akses RELAXTIME PAGU kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diblokir)</i>';
      } else {
        $icon = 'fas fa-user-times bg-danger';
        $aktivitas = 'menambahkan akses baru RELAXTIME PAGU kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diblokir)</i>';
      }

      $data += [
        'id_user' => $user,
        'status' => 'Akses Diblokir',
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
      if ($this->relaxtimeAksesModel->save($data)) {
        $newId = $this->relaxtimeAksesModel->getInsertID();
        $timeline = [
          'icon'        => $icon,
          'id_table'    => $newId,
          'table'       => 'relaxtime_akses',
          'id_user'     => $by['id_user'],
          'aktivitas'   => $aktivitas,
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
        $msg = [
          'data' => 'sukses'
        ];
      } else {
        $msg = [
          'data' => 'error'
        ];
      }
      echo json_encode($msg);
    }
  }
  public function updateSetting()
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel->where('username', session()->get('username'))->first();
      $variabel = $this->request->getVar("data");
      $hasil = $this->parseVariable($variabel);

      $data = [
        'id_relaxtime_setting' => 1,
      ];
      for ($i = 0; $i < count($hasil); $i += 2) {
        $data += [
          $hasil[$i] => $hasil[($i + 1)],
        ];
      }

      $pesan = '';
      $pesanTambahan = '';
      if ($hasil[0] == 'batas_nilai_bawah') {
        $pesan = "batas nilai bawah";
        $pesanTambahan = "menjadi " . $hasil[1] . "%";
      } else if ($hasil[0] == 'batas_nilai_atas') {
        $pesan = "batas nilai atas";
        $pesanTambahan = "menjadi " . $hasil[1] . "%";
      } else if ($hasil[0] == 'id_users') {
        $pesan = "daftar pegawai yang dikirimkan email";
      } else if ($hasil[0] == 'subjek') {
        $pesan = "isi pesan";
      }

      if ($this->relaxtimeSettingModel->save($data)) {
        $newId = $this->relaxtimeSettingModel->getInsertID();
        $timeline = [
          'icon'        => "fas fa-edit bg-warning",
          'id_table'    => $newId,
          'table'       => 'relaxtime_setting',
          'id_user'     => $by['id_user'],
          'aktivitas'   => "memperbaharui data " . $pesan . " pada RELAXTIME " . ($pesanTambahan != "" ? $pesanTambahan : ""),
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);

        $msg = [
          'cek' => $data,
          'data' => "sukses"
        ];
      } else {
        $msg = [
          'cek' => array(0),
          'data' => 'error'
        ];
      }

      echo json_encode($msg);
    }
  }
  private function parseVariable($input)
  {
    $pieces = explode(', ', $input);

    $result = [];
    foreach ($pieces as $piece) {
      // Cek apakah nilai setelah koma adalah angka 1
      if ($piece === '1') {
        $result[] = $piece;
      } else {
        // Jika bukan '1', tambahkan langsung ke array
        $result[] = $piece;
      }
    }

    return $result;
  }
  public function editPagu()
  {
    if ($this->request->isAJAX()) {
      $segment        = $this->request->getVar("segment");
      $id             = $this->request->getVar("id");
      $digit_kode     = $this->request->getVar("digit_kode");
      $digit_uraian   = $this->request->getVar("digit_uraian");
      $kode           = $this->request->getVar("kode");
      $uraian         = $this->request->getVar("uraian");
      $vol            = $this->request->getVar("vol");
      $sat            = $this->request->getVar("sat");
      $harga          = $this->request->getVar("harga");
      $jumlah         = $this->request->getVar("jumlah");
      $newId = null;
      $update = false;
      $table = '';

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $msg = [];

      if ($segment == 'rkt') {
        $cek = $this->relaxtimeRKTModel->where('id_relaxtime_rkt', $id)->first();
        $data = [
          'id_relaxtime_rkt' => $id,
          'kode_rkt' => $kode,
          'deskripsi_rkt' => $uraian,
          'updated_at' => $by['id_user']
        ];

        // Jika Kode baru
        if ($cek['kode_rkt'] != $kode) {
          $data_baru = $this->relaxtimeRKTModel->where('kode_rkt', $kode)->findAll();
          $count_kode = count($data_baru);

          // Jika Kode baru sebenarnya sudah ada
          if ($count_kode > 0) {
            $msg = [
              'icon' => 'warning',
              'title' => 'Terdapat kode yang sama!',
              'text' => 'Tidak dapat melakukan pembaharuan data terhadap kode (' . $kode . ') yang sama!'
            ];
          } else {
            if ($this->relaxtimeRKTModel->save($data)) {
              $msg = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil diperbaharui!'
              ];
              $update = true;
              $update = 'relaxtime_rkt';
              $newId = $this->relaxtimeRKTModel->getInsertID();
            }
          }
        } else {
          if ($this->relaxtimeRKTModel->save($data)) {
            $msg = [
              'icon' => 'success',
              'title' => 'Berhasil!',
              'text' => 'Data berhasil diperbaharui!'
            ];
            $update = true;
            $update = 'relaxtime_rkt';
            $newId = $this->relaxtimeRKTModel->getInsertID();
          }
        }
      } else if ($segment == "kro") {
        $cek = $this->relaxtimeKROModel->where('id_relaxtime_kro', $id)->first();
        $data = [
          'id_relaxtime_kro' => $id,
          'digit_kro' => $digit_kode,
          'detail_digit_kro' => $digit_uraian,
          'kode_kro' => $kode,
          'deskripsi_kro' => $uraian,
          'updated_at' => $by['id_user']
        ];

        // Jika Kode baru
        if ($cek['digit_kro'] != $digit_kode || $cek['kode_kro'] != $kode) {
          $data_baru = $this->relaxtimeKROModel->where('digit_kro', $digit_kode)->where('kode_kro', $kode)->findAll();
          $count_kode = count($data_baru);

          // Jika Kode baru sebenarnya sudah ada
          if ($count_kode > 0) {
            $msg = [
              'icon' => 'warning',
              'title' => 'Terdapat kode yang sama!',
              'text' => 'Tidak dapat melakukan pembaharuan data terhadap kode (' . $digit_kode . '.' . $kode . ') yang sama!'
            ];
          } else {
            if ($this->relaxtimeKROModel->save($data)) {
              $msg = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil diperbaharui!'
              ];
              $update = true;
              $update = 'relaxtime_kro';
              $newId = $this->relaxtimeKROModel->getInsertID();
            }
          }
        } else {
          if ($this->relaxtimeKROModel->save($data)) {
            $msg = [
              'icon' => 'success',
              'title' => 'Berhasil!',
              'text' => 'Data berhasil diperbaharui!'
            ];
            $update = true;
            $update = 'relaxtime_kro';
            $newId = $this->relaxtimeKROModel->getInsertID();
          }
        }
      } else if ($segment == "ro") {
        $cek = $this->relaxtimeROModel->where('id_relaxtime_ro', $id)->first();
        $data = [
          'id_relaxtime_ro' => $id,
          'kode_relaxtime_ro' => $kode,
          'deskripsi_ro' => $uraian,
          'vol_ro' => $vol,
          'sat_ro' => $sat,
          'updated_at' => $by['id_user']
        ];

        // Jika Kode baru
        if ($cek['kode_relaxtime_ro'] != $kode) {
          $data_baru = $this->relaxtimeROModel->where('kode_relaxtime_ro', $kode)->where('id_relaxtime_kro', $cek['id_relaxtime_kro'])->findAll();
          $count_kode = count($data_baru);

          // Jika Kode baru sebenarnya sudah ada
          if ($count_kode > 0) {
            $msg = [
              'icon' => 'warning',
              'title' => 'Terdapat kode yang sama!',
              'text' => 'Tidak dapat melakukan pembaharuan data terhadap kode ('  . $kode . ') pada KRO yang sama!'
            ];
          } else {
            if ($this->relaxtimeROModel->save($data)) {
              $msg = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil diperbaharui!'
              ];
              $update = true;
              $update = 'relaxtime_ro';
              $newId = $this->relaxtimeROModel->getInsertID();
            }
          }
        } else {
          if ($this->relaxtimeROModel->save($data)) {
            $msg = [
              'icon' => 'success',
              'title' => 'Berhasil!',
              'text' => 'Data berhasil diperbaharui!'
            ];
            $update = true;
            $update = 'relaxtime_ro';
            $newId = $this->relaxtimeROModel->getInsertID();
          }
        }
      } else if ($segment == "komponen") {
        $cek = $this->relaxtimeKomponenModel->where('id_relaxtime_komponen', $id)->first();
        $data = [
          'id_relaxtime_komponen' => $id,
          'kode_komponen' => $kode,
          'deskripsi_komponen' => $uraian,
          'updated_at' => $by['id_user']
        ];

        // Jika Kode baru
        if ($cek['kode_komponen'] != $kode) {
          $data_baru = $this->relaxtimeKomponenModel->where('kode_komponen', $kode)->where('id_relaxtime_ro', $cek['id_relaxtime_ro'])->findAll();
          $count_kode = count($data_baru);

          // Jika Kode baru sebenarnya sudah ada
          if ($count_kode > 0) {
            $msg = [
              'icon' => 'warning',
              'title' => 'Terdapat kode yang sama!',
              'text' => 'Tidak dapat melakukan pembaharuan data terhadap kode ('  . $kode . ') pada RO yang sama!'
            ];
          } else {
            if ($this->relaxtimeKomponenModel->save($data)) {
              $msg = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil diperbaharui!'
              ];
              $update = true;
              $update = 'relaxtime_komponen';
              $newId = $this->relaxtimeKomponenModel->getInsertID();
            }
          }
        } else {
          if ($this->relaxtimeKomponenModel->save($data)) {
            $msg = [
              'icon' => 'success',
              'title' => 'Berhasil!',
              'text' => 'Data berhasil diperbaharui!'
            ];
            $update = true;
            $update = 'relaxtime_komponen';
            $newId = $this->relaxtimeKomponenModel->getInsertID();
          }
        }
      } else if ($segment == "sub-komponen") {
        $cek = $this->relaxtimeKomponenModel->where('id_relaxtime_komponen', $id)->first();
        $data = [
          'id_relaxtime_komponen' => $id,
          'kode_sub_komponen' => $kode,
          'deskripsi_sub_komponen' => $uraian,
          'updated_at' => $by['id_user']
        ];

        // Jika Kode baru
        if ($cek['kode_sub_komponen'] != $kode) {
          $data_baru = $this->relaxtimeKomponenModel->where('kode_sub_komponen', $kode)->where('kode_komponen', $cek['kode_komponen'])->findAll();
          $count_kode = count($data_baru);

          // Jika Kode baru sebenarnya sudah ada
          if ($count_kode > 0) {
            $msg = [
              'icon' => 'warning',
              'title' => 'Terdapat kode yang sama!',
              'text' => 'Tidak dapat melakukan pembaharuan data terhadap kode ('  . $kode . ') pada Komponen yang sama!'
            ];
          } else {
            if ($this->relaxtimeKomponenModel->save($data)) {
              $msg = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil diperbaharui!'
              ];
              $update = true;
              $update = 'relaxtime_komponen';
              $newId = $this->relaxtimeKomponenModel->getInsertID();
            }
          }
        } else {
          if ($this->relaxtimeKomponenModel->save($data)) {
            $msg = [
              'icon' => 'success',
              'title' => 'Berhasil!',
              'text' => 'Data berhasil diperbaharui!'
            ];
            $update = true;
            $update = 'relaxtime_komponen';
            $newId = $this->relaxtimeKomponenModel->getInsertID();
          }
        }
      } else if ($segment == "uraian") {
        $cek = $this->relaxtimeUraianModel->where('id_relaxtime_uraian', $id)->first();
        $data = [
          'id_relaxtime_uraian' => $id,
          'kode_uraian' => $kode,
          'deskripsi_uraian' => $uraian,
          'updated_at' => $by['id_user']
        ];

        // Jika Kode baru
        if ($cek['kode_uraian'] != $kode) {
          $data_baru = $this->relaxtimeUraianModel->where('kode_uraian', $kode)->findAll();
          $count_kode = count($data_baru);

          // Jika Kode baru sebenarnya sudah ada
          if ($count_kode > 0) {
            $msg = [
              'icon' => 'warning',
              'title' => 'Terdapat kode yang sama!',
              'text' => 'Tidak dapat melakukan pembaharuan data terhadap kode ('  . $kode . ') yang sama!'
            ];
          } else {
            if ($this->relaxtimeUraianModel->save($data)) {
              $msg = [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Data berhasil diperbaharui!'
              ];
              $update = true;
              $update = 'relaxtime_uraian';
              $newId = $this->relaxtimeUraianModel->getInsertID();
            }
          }
        } else {
          if ($this->relaxtimeUraianModel->save($data)) {
            $msg = [
              'icon' => 'success',
              'title' => 'Berhasil!',
              'text' => 'Data berhasil diperbaharui!'
            ];
            $update = true;
            $update = 'relaxtime_uraian';
            $newId = $this->relaxtimeUraianModel->getInsertID();
          }
        }
      } else if ($segment == "detil") {
        $cek = $this->relaxtimeDetailUraianModel->where('id_relaxtime_detail_uraian', $id)->first();
        $data = [
          'id_relaxtime_detail_uraian' => $id,
          'deskripsi_detail_uraian' => $uraian,
          'vol_detail_uraian' => $vol,
          'sat_detail_uraian' => $sat,
          'harga_detail_uraian' => $harga,
          'jumlah_detail_uraian' => $jumlah,
          'updated_at' => $by['id_user']
        ];

        if ($this->relaxtimeDetailUraianModel->save($data)) {
          $msg = [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Data berhasil diperbaharui!'
          ];
          $update = true;
          $update = 'relaxtime_detail_uraian';
          $newId = $this->relaxtimeDetailUraianModel->getInsertID();
        }
      } else {
        $msg = [
          'icon' => 'error',
          'title' => 'Gagal melakukan update!',
          'text' => 'Ada kesalahan saat update, silahkan muat ulang halaman!'
        ];
      }

      if ($update == true) {
        $timeline = [
          'icon'        => 'fas fa-save bg-primary',
          'id_table'    => $newId,
          'table'       => $table,
          'id_user'     => $by['id_user'],
          'aktivitas'   => 'melakukan perubahan pada data pagu',
          'keterangan'  => "",
        ];
        $this->linimasaModel->save($timeline);
      }

      echo json_encode($msg);
    }
  }
  public function dltDetailUraian($id)
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $cekDataMaster = $this->relaxtimeDetailUraianModel->where('id_relaxtime_detail_uraian', $id)->first();

    if ($this->relaxtimeDetailUraianModel->delete($id)) {
      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $cekDataMaster['id_relaxtime_detail_uraian'],
        'table'       => 'relaxtime_detail_uraian',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data baris uraian (' . $cekDataMaster['deskripsi_detail_uraian'] . ') pada relaxtime di tahun <i>' . $cekDataMaster['tahun'] . '</i>',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }
  public function dltRO($id)
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $cekDataMaster = $this->relaxtimeROModel->where('id_relaxtime_ro', $id)->first();

    if ($this->relaxtimeROModel->delete($id)) {
      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $cekDataMaster['id_relaxtime_ro'],
        'table'       => 'relaxtime_ro',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data baris uraian (' . $cekDataMaster['deskripsi_ro'] . ') pada relaxtime di tahun <i>' . $cekDataMaster['tahun'] . '</i>',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }
  public function perbaharuiTargetBulanan()
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $tahun = $this->request->getVar("tahun");

    $cekData = $this->relaxtimeTargetBulananModel->where('tahun', $tahun)->first();
    $data = [];
    if ($cekData) {
      $data += ['id_relaxtime_target_bulanan' => $cekData['id_relaxtime_target_bulanan']];
      $data += ['updated_by' => $by['id_user']];
    } else {
      $data += ['created_by' => $by['id_user']];
    }

    $dataUraian = [];
    for ($i = 1; $i <= 12; $i++) {
      $data += ["bulan" . $i => $this->request->getVar("bulan" . $i)];
      $dataUraian += [
        'bulan' . $i . '51' => $this->request->getVar('bulan' . $i . '51'),
        'bulan' . $i . '52' => $this->request->getVar('bulan' . $i . '52'),
        'bulan' . $i . '53' => $this->request->getVar('bulan' . $i . '53'),
      ];
    }
    $data += ['uraian' => serialize($dataUraian)];
    $data += ["tahun" => $this->request->getVar("tahun")];

    if ($this->relaxtimeTargetBulananModel->save($data)) {
      $newId = $this->relaxtimeTargetBulananModel->getInsertID();

      $timeline = [
        'icon'        => 'fas fa-pen-square bg-info',
        'id_table'    => $newId,
        'table'       => 'relaxtime_target_bulanan',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'memperbaharui data target realisasi bulanan di tahun ' . $tahun,
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);

      session()->setFlashdata('pesan', 'Data berhasil diperbaharui!');
      return redirect()->to('relaxtime/target_bulanan')->withInput();
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan, silahkan coba kembali!');
      return redirect()->to('relaxtime/target_bulanan')->withInput();
    }
  }
}
