<?php

namespace App\Controllers\Sinadi;

use App\Controllers\BaseController;
use App\Models\SinadiPerjalananDinasModel;
use App\Models\PengawasanKapal\MonitoringSanitasiKapalModel;

class Crud extends BaseController
{
  public function konfirmasiDataSurat()
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $id_perjalanan = $this->request->getVar("id_perjalanan");
    $verifikatorKe = $this->request->getVar("verifikatorKe");
    $id_data_pegawai = $this->request->getVar("id_data_pegawai");
    $nama = $this->request->getVar("nama");
    $keterangan = $this->request->getVar("keterangan");
    $nip = $this->request->getVar("nip");
    $status = $this->request->getVar("status");
    $signature = $this->request->getVar("signature");

    $cekData = $this->sinadiPerjalananDinasModel->where('id_perjalanan', $id_perjalanan)->first();

    if ($cekData)
      if ($signature) {
        list($type, $base64String) = explode(';', $signature);
        list(, $base64Data) = explode(',', $base64String);

        $decodedData = base64_decode($base64Data);

        if (strpos($type, 'jpeg') !== false) {
          $extension = 'jpg';
        } elseif (strpos($type, 'png') !== false) {
          $extension = 'png';
        } elseif (strpos($type, 'gif') !== false) {
          $extension = 'gif';
        } elseif (strpos($type, 'bmp') !== false) {
          $extension = 'bmp';
        } elseif (strpos($type, 'webp') !== false) {
          $extension = 'webp';
        } elseif (strpos($type, 'tiff') !== false) {
          $extension = 'tiff';
        } else {
          $extension = 'jpg';
        }

        $newName = uniqid() . '.' . $extension;
        $src = '/dist/ttd_pegawai/' . $newName;
        $filePath = FCPATH . $src;
        if (file_put_contents($filePath, $decodedData)) {
          $serialize = [
            'id_data_pegawai' => $id_data_pegawai,
            'nama' => $nama,
            'nip' => $nip,
            'keterangan' => $keterangan,
            'status' => $status,
            'signature' => $src,
            'time' => date("Y-m-d H:i:s")
          ];
            
          // Inisialisasi awal sebagai array kosong
          $verifikator = [];
            
          // Jika sudah ada data sebelumnya
          if (!empty($cekData['verifikator_' . $verifikatorKe])) {
            $existing = unserialize($cekData['verifikator_' . $verifikatorKe]);
            
            // Pastikan existing adalah array
            if (is_array($existing)) {
              $verifikator = $existing;
            }
          }
            
          // Tambahkan data baru ke akhir array
          $verifikator[] = $serialize;
            
          // Simpan kembali dalam format serialized
          $verifikator = serialize($verifikator);

          $data = [
            'id_perjalanan' => $id_perjalanan,
            'verifikator_' . $verifikatorKe => $verifikator
          ];
          if ($status == "Disetujui" && $verifikatorKe <= 5) {
            $cekVerifikatorSelanjutnya = $this->sinadiVerifikatorModel->orderBy('created_at', 'desc')->first();
            if ($cekVerifikatorSelanjutnya['verifikator_' . ($verifikatorKe + 1)] == null) {
              $data += [
                'status_surat' => $status
              ];
            } else {
              $data += [
                'status_surat' => "Dalam Verifikasi"
              ];
            }
          } else {
            $data += [
              'status_surat' => $status
            ];
          }

          if ($this->sinadiPerjalananDinasModel->save($data)) {
            session()->setFlashdata('pesan', 'Data berhasil diajukan.');

            $cekDataPeserta = $this->sinadiPerjalananDinasPesertaModel->select('data_pegawai.id_user, nama_kegiatan, tempat_perjalanan')
              ->join('data_pegawai', 'data_pegawai.nip=sinadi_perjalanan_dinas_peserta.nip_pegawai')
              ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
              ->where('sinadi_perjalanan_dinas_peserta.id_perjalanan', $id_perjalanan)
              ->findAll();
            foreach ($cekDataPeserta as $limit) {
              $data = [
                'nama_pengirim'   => $by['id_user'],
                'nama_penerima'   => $limit['id_user'],
                'pesan'           =>  "<strong>" . session()->get('nama') . "</strong> telah melakukan verifikasi dengan status '" . $status . "' untuk tujuan perjalanan dinas '" . $limit['tempat_perjalanan'] . "'. <br />Nama kegiatan: " . $limit['nama_kegiatan'] . " <br />Keterangan: <u>" . $keterangan . "</u>",
              ];
              $this->notifikasiModel->save($data);
            }
          } else {
            session()->setFlashdata('error', 'Data gagal diajukan.');
          }
        }
      }

    $list = $this->request->getVar("list");
    $values = $this->request->getVar("value") ?? [];

    $daftar_periksa = [];

    foreach ($list as $item) {
      $isChecked = in_array($item, $values);

      $daftar_periksa[] = [
        'list'  => $item,
        'value' => $isChecked ? 'checked' : '',
      ];
    }

    $data = [
      'id_sinadi_lembar_verifikasi' => $this->request->getVar("id_sinadi_lembar_verifikasi"),
      'id_perjalanan' => $this->request->getVar("id_perjalanan"),
      'tgl' => $this->request->getVar("tgl"),
      'no_spby' => $this->request->getVar("no_spby"),
      'suplier' => serialize($this->request->getVar("suplier")),
      'tagihan' => $this->request->getVar("tagihan"),
      'uraian' => $this->request->getVar("uraian"),
      'total_tagihan' => $this->request->getVar("total_tagihan"),
      'jenis_daftar_periksa' => $this->request->getVar("jenis_daftar_periksa"),
      'daftar_periksa' => serialize($daftar_periksa),
      'updated_by' => $by['id_user'],
    ];
    if ($verifikatorKe == "1") {
      $data += ['created_by' => $by['id_user']];
    }

    $this->sinadiLembarVerifikasiModel->save($data);

    return redirect()->to('/sinadi/verifikasiSurat');
  }
  public function sendToVerifikator()
  {
    $id = $this->request->getVar("id_perjalanan");
    $data = [
      'id_perjalanan' => $id,
      'status_surat' => 'Diajukan'
    ];

    if ($this->sinadiPerjalananDinasModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diajukan.');
    } else {
      session()->setFlashdata('error', 'Data gagal diajukan.');
    }

    return redirect()->to('sinadi/validasi_data/' . $id);
  }
  public function setTTDPeserta()
  {
    $id_perjalanan = $this->request->getVar("id_perjalanan");

    $data = [
      'id_peserta' => $this->request->getVar("id_peserta"),
      'ttd_peserta' => $this->request->getVar("signature")
    ];

    if ($this->sinadiPerjalananDinasPesertaModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil disimpan.');
    } else {
      session()->setFlashdata('error', 'Data gagal disimpan.');
    }
    return redirect()->to('sinadi/validasi_data/' . $id_perjalanan);
  }
  public function setTTDPIC()
  {
    $id_perjalanan = $this->request->getVar("id_perjalanan");
    $signature = $this->request->getVar("signature");

    $row = $this->sinadiPerjalananDinasModel->where('id_perjalanan', $id_perjalanan)->first();
    $entries = [];
    if ($row && isset($row['verifikator_4']) && $row['verifikator_4']) {
      try {
        $entries = unserialize($row['verifikator_4']);
        if (!is_array($entries)) {
          $entries = [];
        }
      } catch (\Throwable $e) {
        $entries = [];
      }
    }

    $entries[] = [
      'time' => date('Y-m-d H:i:s'),
      'status' => 'TTD PIC',
      'nama' => $row ? ($row['pic'] ?? '') : '',
      'nip' => $row ? ($row['pic_nip'] ?? '') : '',
      'signature' => $signature,
    ];

    $saved = $this->sinadiPerjalananDinasModel->save([
      'id_perjalanan' => $id_perjalanan,
      'verifikator_4' => serialize($entries),
    ]);

    if ($saved) {
      session()->setFlashdata('pesan', 'TTD PIC berhasil disimpan.');
    } else {
      session()->setFlashdata('error', 'TTD PIC gagal disimpan.');
    }

    return redirect()->to('sinadi/validasi_data/' . $id_perjalanan);
  }
  public function set_verifikator()
  {
    if ($this->request->isAJAX()) {
      $verifikator = $this->request->getVar("verifikator");
      $nilaiData = $this->request->getVar("data");
      $tipe = strtoupper($this->request->getVar("tipe") ?? ''); // APBN/RPL

      if ($verifikator == "1" || $verifikator == "6") {
        $nilaiData = serialize($nilaiData);
      }

      $by = $this->userModel->where('username', session()->get('username'))->first();

      // Tentukan model berdasarkan tipe sumber anggaran (APBN/RPL). Jika kosong, gunakan default tunggal.
      $model = null;
      if ($tipe === 'APBN') {
        $model = $this->sinadiVerifikatorApbnModel;
      } elseif ($tipe === 'RPL') {
        $model = $this->sinadiVerifikatorRplModel;
      } else {
        $model = $this->sinadiVerifikatorModel;
      }

      $getData = $model->orderBy('created_at', 'desc')->first();
      if ($getData) {
        $data = [];

        for ($i = 1; $i <= 6; $i++) {
          if ($verifikator == $i) {
            $data['verifikator_' . $verifikator] = ($nilaiData == "" ? null : $nilaiData);
          } else {
            $data['verifikator_' . $i] = $getData['verifikator_' . $i];
          }
        }

        $data += [
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
      } else {
        $data = [
          'verifikator_' . $verifikator => ($nilaiData == "" ? null : $nilaiData),
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
      }

      $msg = [
        'icon' => 'warning',
        'text' => 'Terjadi kesalahan server, silahkan hubungi pihak terkait'
      ];
      $saved = false;
      try {
        $saved = $model->save($data);
      } catch (\Throwable $e) {
        // Jika tabel khusus belum ada, fallback ke tabel default lama
        try {
          $saved = $this->sinadiVerifikatorModel->save($data);
        } catch (\Throwable $ee) {
          $saved = false;
        }
      }
      if ($saved) {
        $msg = [
          'icon' => 'success',
          'text' => 'Data berhasil diperbaharui!'
        ];
      } else {
        $msg = [
          'icon' => 'error',
          'text' => 'Terjadi kesalahan dalam menyimpan data! Coba lagi..'
        ];
      }
      echo json_encode($msg);
    }
  }
  function konversiStringKeFloat($string)
  {
    // Menghapus tanda ribuan (.)
    $string = str_replace('.', '', $string);

    // Mengubah tanda koma menjadi titik
    $string = str_replace(',', '.', $string);

    // Menghapus karakter selain angka dan titik (untuk memungkinkan desimal)
    $string = preg_replace('/[^0-9.]/', '', $string);

    // Mengonversi string menjadi float (bilangan desimal)
    $nilai = floatval($string);

    return $nilai;
  }
  private function hitungTotal($jumlah)
  {
    $total = 0;

    foreach ($jumlah as $nilai) {
      // Menghapus tanda ribuan (.)
      $nilai = str_replace('.', '', $nilai);

      // Mengubah tanda koma menjadi titik
      $nilai = str_replace(',', '.', $nilai);

      // Menghapus karakter selain angka dan titik (untuk memungkinkan desimal)
      $nilai = preg_replace('/[^0-9.]/', '', $nilai);

      // Mengonversi string menjadi float (bilangan desimal)
      $nilai = floatval($nilai);

      // Menambahkan nilai ke total
      $total += $nilai;
    }

    return $total;
  }
  public function setUploadBuktiPembayaran() {
    $id = $this->request->getVar('id');
      
    $newBerkas = null;
    
    $berkas = $this->request->getFile('bukti_pembayaran');
    if ($berkas !== null)
      if ($berkas->isValid() && !$berkas->hasMoved()) {
        $newBerkas = uniqid() . '_file_sinadi_bukti_pembayaran_' . $berkas->getRandomName();
        $berkas->move(ROOTPATH . 'writable/file-sinadi', $newBerkas);
        if (file_exists(ROOTPATH . 'writable/file-sinadi/' . $newBerkas)) {
        } else {
          $berkas->move(ROOTPATH . 'writable/file-sinadi', $newBerkas);
        }
      }
    
    $data = [
      'id_perjalanan' => $id,
      'bukti_pembayaran' => $newBerkas,
    ];
    
    if ($this->sinadiPerjalananDinasModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
    }
    
    return redirect()->to('sinadi/verifikasiSurat');
  }
  
  public function tambah_sinadi()
  {
    // Validasi Data Peserta
    $nipList = $this->request->getVar("nip");
    $validNip = false;
    if (is_array($nipList)) {
        foreach ($nipList as $n) {
            if (!empty($n)) {
                $validNip = true;
                break;
            }
        }
    }
    
    // Cek juga manual input jika NIP otomatis kosong
    if (!$validNip) {
         $manualNipList = $this->request->getVar("manualNip");
         if (is_array($manualNipList)) {
             foreach ($manualNipList as $mn) {
                 if (!empty($mn)) {
                     $validNip = true;
                     break;
                 }
             }
         }
    }
    
    if (!$validNip) {
        session()->setFlashdata('error', 'Data peserta perjalanan dinas tidak boleh kosong. Mohon pilih pegawai atau isi data manual.');
        return redirect()->back()->withInput();
    }

    $db = \Config\Database::connect();
    $db->transStart();

    // Set jenis_perjalanan_dinas
    $jenisPerjalanan = $this->request->getVar("jenis_perjalanan_dinas");
    $kota = $jenisPerjalanan;

    // Tambahin data pada tabel  
    // hasil_kegiatan, jabatan_pegawai

    $by = $this->userModel->where('username', session()->get('username'))->first();
    $newFile = null;
    $newBerkas = null;
    
    // Upload file_hasil_kegiatan
    $file = $this->request->getFile('file_hasil_kegiatan');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $ext = $file->getClientExtension();
        $newFile = uniqid() . '_file_sinadi_filehasilkegiatan_' . time() . '.' . $ext;
        $file->move(ROOTPATH . 'writable/file-sinadi', $newFile);
    }
    
    // Upload berkas
    $berkas = $this->request->getFile('berkas');
    if ($berkas && $berkas->isValid() && !$berkas->hasMoved()) {
        $ext = $berkas->getClientExtension();
        $newBerkas = uniqid() . '_file_sinadi_berkas_' . time() . '.' . $ext;
        $berkas->move(ROOTPATH . 'writable/file-sinadi', $newBerkas);
    }

    $pic_nama = $this->request->getVar("pic_nama");
    $pic_nip = $this->request->getVar("pic_nip");

    $kategori = $this->request->getVar("kategori") == "Kelompok" ? "2" : "1";
    $data = [
      'kategori' => $kategori,
      'nama_kegiatan' => $this->request->getVar("nama_kegiatan"),
      'tgl_pelaksanaan' => $this->request->getVar("tgl_pelaksanaan"),
      'tgl_pelaksanaan_akhir' => $this->request->getVar("tgl_pelaksanaan_akhir"),
      'tempat_perjalanan' => $this->request->getVar("tempat_perjalanan"),
      'mata_anggaran' => $this->request->getVar("mata_anggaran"),
      'sumber_anggaran' => $this->request->getVar("sumber_anggaran") ?? 'APBN',
      'hasil_kegiatan' => $this->request->getVar("hasil_kegiatan"),
      'total_transport' => $this->hitungTotal($this->request->getVar("transport_jumlah")), // update_data penjumlahan
      'total_harian' => $this->hitungTotal($this->request->getVar("harian_jumlah")), // update_data penjumlahan
      'total_penginapan' => $this->hitungTotal($this->request->getVar("penginapan_jumlah")), // update_data penjumlahan
      'total_perjalanan' => $this->hitungTotal($this->request->getVar("jumlah_transfer")), // update_data penjumlahan
      'file' => $newFile,
      'berkas' => $newBerkas,
      'catatan_kegiatan' => $this->request->getVar("hasil_kegiatan"),
      'pic' => $pic_nama,
      'pic_nip' => $pic_nip,
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
      'jenis_perjalanan_dinas' => $jenisPerjalanan
    ];

    if ($this->sinadiPerjalananDinasModel->save($data)) {
      $newId = $this->sinadiPerjalananDinasModel->getInsertID();
      // dd();
      // if ($this->sinadiPerjalananDinasModel->save($data)) {
      //   $newId = $this->sinadiPerjalananDinasModel->getInsertID();
      $jmlDataPeserta = count($this->request->getVar("nip"));
      $inputManual = 0;
      $inputOtomatis = 0;
      $nip = "";
      $nama = "";
      $jabatan = "";
      $golongan = "";
      // d(count($this->request->getVar("tes")));
      for ($i = 0; $i < $jmlDataPeserta; $i++) {
        if ($this->request->getVar("nip")[$i] == "") {
          $nip = $this->request->getVar("manualNip")[$inputManual];
          $nama = $this->request->getVar("manualNama")[$inputManual];
          $jabatan = $this->request->getVar("manualJabatan")[$inputManual];
          $golongan = $this->request->getVar("manualGolongan")[$inputManual];
          $inputManual++;
        } else {
          $nip = $this->request->getVar("nip")[$i];
          $nama = $this->request->getVar("nama")[$inputOtomatis];
          $jabatan = $this->request->getVar("jabatan")[$inputOtomatis];
          $golongan = $this->request->getVar("golongan")[$inputOtomatis];
          $inputOtomatis++;
        }
        $no_sppd = '-';
        $tgl_sppd = null;
        $kota_tujuan = '';
        $kota = 'dalam kota';
        if (isset($this->request->getVar("no_sppd")[$i])) {
          $no_sppd = $this->request->getVar("no_sppd")[$i];
          $tgl_sppd = $this->request->getVar("tgl_sppd")[$i];
        }
        if (isset($this->request->getVar("kota_tujuan")[$i])) {
          if ($this->request->getVar("is_dalam_kota") == "1") {
            $kota = 'dalam kota';
            $kota_tujuan = $this->request->getVar("kota")[$i];
          } else {
            $kota = 'luar kota';
            $kota_tujuan = $this->request->getVar("kota_tujuan")[$i];
          }
        } else {
          $kota_tujuan = $this->request->getVar("kota_asal")[$i];
        }

        $data = [
          'id_perjalanan' => $newId,
          'id_sinadi_setting' => $this->sinadiSettingModel->where('status', 'Aktif')->first()['id_sinadi_setting'],
          'tgl_pergi' => $this->request->getVar("tgl_pergi")[$i],
          'tgl_pulang' => $this->request->getVar("tgl_pulang")[$i],
          'nip_pegawai' => $nip,
          'nama_pegawai' => $nama,
          'jabatan_pegawai' => $jabatan,
          'golongan' => $golongan,
          'no_surat_tugas' => $this->request->getVar("no_surat_tugas")[$i],
          'tgl_surat_tugas' => $this->request->getVar("tgl_surat_tugas")[$i],
          'no_sppd' => $no_sppd, // $this->request->getVar("no_sppd")[$i],
          'tgl_sppd' => $tgl_sppd, // $this->request->getVar("tgl_sppd")[$i],
          'kota' => $kota_tujuan,
          'kota_asal' => $this->request->getVar("kota_asal")[$i],
          'transport_hari' => $this->konversiStringKeFloat($this->request->getVar("transport_hari")[$i]),
          'transport_nominal' => $this->konversiStringKeFloat($this->request->getVar("transport_nominal")[$i]),
          'transport_jumlah' => $this->konversiStringKeFloat($this->request->getVar("transport_jumlah")[$i]),
          'harian_hari' => $this->konversiStringKeFloat($this->request->getVar("harian_hari")[$i]),
          'harian_nominal' => $this->konversiStringKeFloat($this->request->getVar("harian_nominal")[$i]),
          'harian_jumlah' => $this->konversiStringKeFloat($this->request->getVar("harian_jumlah")[$i]),
          'penginapan_hari' => $this->konversiStringKeFloat($this->request->getVar("penginapan_hari")[$i]),
          'penginapan_nominal' => $this->konversiStringKeFloat($this->request->getVar("penginapan_nominal")[$i]),
          'penginapan_jumlah' => $this->konversiStringKeFloat($this->request->getVar("penginapan_jumlah")[$i]),
          'jumlah_transfer' => $this->konversiStringKeFloat($this->request->getVar("jumlah_transfer")[$i]),
          'transportasi' => $this->request->getVar("transportasi")[$i],
        ];
        $this->sinadiPerjalananDinasPesertaModel->save($data);
      }

      // Monitoring Sanitasi Kapal Logic
      if ($this->request->getVar("monitoring_sanitasi_kapal") == "1") {
          $monitoringModel = new MonitoringSanitasiKapalModel();
          
          $buktiGeotagging = $this->request->getFile('bukti_geotagging_monitoring');
          $laporanTindakan = $this->request->getFile('laporan_tindakan_monitoring');
          
          $buktiPath = null;
          $laporanPath = null;
          
          // Folder Public (sesuai dengan Controller PengawasanKapal/Data.php)
          $baseDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'monitoring_sanitasi';
          $dirGeotagging = $baseDir . DIRECTORY_SEPARATOR . 'geotagging';
          $dirLaporan = $baseDir . DIRECTORY_SEPARATOR . 'laporan';

          if (!is_dir($dirGeotagging)) {
            @mkdir($dirGeotagging, 0775, true);
          }
          if (!is_dir($dirLaporan)) {
            @mkdir($dirLaporan, 0775, true);
          }

          if ($buktiGeotagging && $buktiGeotagging->isValid() && !$buktiGeotagging->hasMoved()) {
              $newName = $buktiGeotagging->getRandomName();
              $buktiGeotagging->move($dirGeotagging, $newName);
              // Path disimpan relatif terhadap public folder agar bisa diakses browser
              $buktiPath = 'uploads/monitoring_sanitasi/geotagging/' . $newName;
          }
          
          if ($laporanTindakan && $laporanTindakan->isValid() && !$laporanTindakan->hasMoved()) {
              $newName = $laporanTindakan->getRandomName();
              $laporanTindakan->move($dirLaporan, $newName);
              // Path disimpan relatif terhadap public folder
              $laporanPath = 'uploads/monitoring_sanitasi/laporan/' . $newName;
          }
          
          // Simpan data monitoring dengan jenis dokumen
          $jenisDokumen = $this->request->getVar('jenis_dokumen');
          $jenisDokumenStr = $jenisDokumen ? implode(',', $jenisDokumen) : null;
          
          $monitoringData = [
              'tanggal' => $this->request->getVar("tgl_pelaksanaan"),
              'wilker' => session()->get('nama'),
              'unit_kerja' => session()->get('unit_kerja'),
              'nama_kapal' => $this->request->getVar("nama_kapal_monitoring"),
              'jenis_dokumen' => $jenisDokumenStr,
              'bukti_geotagging' => $buktiPath,
              'laporan_tindakan' => null, // Laporan tindakan dikosongkan karena akan diisi via upload Nota Dinas
              'created_by' => $by['id_user'],
              'updated_by' => $by['id_user']
          ];
          
          if ($monitoringModel->save($monitoringData)) {
              $idMonitoring = $monitoringModel->getInsertID();
              // Update sinadi_perjalanan_dinas dengan id_monitoring_sanitasi
              $this->sinadiPerjalananDinasModel->update($newId, ['id_monitoring_sanitasi' => $idMonitoring]);
          }
      }

      // Linimasa
      $jmlTransfer = $this->hitungTotal($this->request->getVar("jumlah_transfer"));
      $timeline = [
        'icon'        => 'fas fa-save bg-primary',
        'id_table'    => $newId,
        'table'       => 'sinadi_perjalanan_dinas',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menambahkan data SINADI dengan total peserta yang mengikuti perjalanan sebanyak ' . $jmlDataPeserta . ' orang',
        'keterangan'  => "<p>Anggaran yang terhitung untuk tujuan tempat perjalanan di '" . $this->request->getVar("tempat_perjalanan") . "' dengan jumlah peserta tersebut sebanyak <strong>Rp" . number_format($jmlTransfer, 2, ',', '.') . "</strong> (" . terbilang($jmlTransfer) . ")</p>",
      ];
      $this->linimasaModel->save($timeline);

      $db->transComplete();

      if ($db->transStatus() === FALSE) {
          session()->setFlashdata('error', 'Gagal menyimpan data perjalanan dinas. Mohon cek kembali isian data.');
          return redirect()->back()->withInput();
      }

      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
    }
    if ($kota == 'luar kota')
      return redirect()->to('sinadi/dinasLuarKota');
    else
      return redirect()->to('sinadi/dinasDalamKota');
  }


  public function edit_sinadi()
  {

    $id_perjalanan = $this->request->getVar("id_perjalanan");
    if ($id_perjalanan == "") {
      exit();
    }

    // Tambahin data pada tabel 
    // hasil_kegiatan, jabatan_pegawai

   $cekPerjalanan = $this->sinadiPerjalananDinasPesertaModel
      ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas.id_perjalanan=sinadi_perjalanan_dinas_peserta.id_perjalanan')
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $id_perjalanan)->findAll();

    $by = $this->userModel->where('username', session()->get('username'))->first();
    $newFile = '';
    $newBerkas = '';


    $file = $this->request->getFile('file_hasil_kegiatan');
    $berkas = $this->request->getFile('berkas');
    if ($file !== null)
      if ($file->isValid() && !$file->hasMoved()) {

        $newFile = uniqid() . '_file_sinadi_' . $file->getRandomName();
        $file->move(ROOTPATH . 'writable/file-sinadi', $newFile);
        if (file_exists(ROOTPATH . 'writable/file-sinadi/' . $newFile)) {
        } else {
          $file->move(ROOTPATH . 'writable/file-sinadi', $newFile);
        }

        $fileName = $cekPerjalanan[0]['file'];
        if (!empty($fileName)) {
          $filePath = ROOTPATH . 'writable/file-sinadi/' . $fileName;

          if (file_exists($filePath)) {
            if (unlink($filePath)) {
              unset($cekPerjalanan[0]['file']);
            }
          }
        }
      }

    if ($berkas !== null)
      if ($berkas->isValid() && !$berkas->hasMoved()) {

        $newBerkas = uniqid() . '_file_sinadi_berkas_' . $berkas->getRandomName();
        $berkas->move(ROOTPATH . 'writable/file-sinadi', $newBerkas);
        if (file_exists(ROOTPATH . 'writable/file-sinadi/' . $newBerkas)) {
        } else {
          $berkas->move(ROOTPATH . 'writable/file-sinadi', $newBerkas);
        }

        $berkasName = $cekPerjalanan[0]['berkas'];
        if (!empty($berkasName)) {
          $berkasPath = ROOTPATH . 'writable/file-sinadi/' . $berkasName;

          if (file_exists($berkasPath)) {
            if (unlink($berkasPath)) {
              unset($cekPerjalanan[0]['berkas']);
            }
          }
        }
      }

    $pic = $this->dataPegawaiModel->where('nip', $this->request->getVar("sinadi_pic"))->first();

    // Cek Bentrok Jadwal (Edit)
    $tgl_pelaksanaan = $this->request->getVar("tgl_pelaksanaan");
    $tgl_pelaksanaan_akhir = $this->request->getVar("tgl_pelaksanaan_akhir");
    // Jika tgl akhir kosong, anggap sama dengan tgl mulai (1 hari)
    if (empty($tgl_pelaksanaan_akhir)) {
      $tgl_pelaksanaan_akhir = $tgl_pelaksanaan;
    }
    
    // Validasi bentrok tanggal dinonaktifkan
    // $nips = $this->request->getVar("nip");
    // if (is_array($nips)) { ... }

    $kategori = $this->request->getVar("kategori") == "Kelompok" ? "2" : "1";
    $data = [
      'id_perjalanan' => $cekPerjalanan[0]['id_perjalanan'],
      'kategori' => $kategori,
      'nama_kegiatan' => $this->request->getVar("nama_kegiatan"),
      'tgl_pelaksanaan' => $this->request->getVar("tgl_pelaksanaan"),
      'tgl_pelaksanaan_akhir' => $this->request->getVar("tgl_pelaksanaan_akhir"),
      'tempat_perjalanan' => $this->request->getVar("tempat_perjalanan"),
      'mata_anggaran' => $this->request->getVar("mata_anggaran"),
      'sumber_anggaran' => $this->request->getVar("sumber_anggaran") ?? ($cekPerjalanan[0]['sumber_anggaran'] ?? 'APBN'),
      'hasil_kegiatan' => $this->request->getVar("hasil_kegiatan"),
      'total_transport' => $this->hitungTotal($this->request->getVar("transport_jumlah")),
      'total_harian' => $this->hitungTotal($this->request->getVar("harian_jumlah")),
      'total_penginapan' => $this->hitungTotal($this->request->getVar("penginapan_jumlah")),
      'total_perjalanan' => $this->hitungTotal($this->request->getVar("jumlah_transfer")),
      'catatan_kegiatan' => $this->request->getVar("hasil_kegiatan"),
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
      'jenis_perjalanan_dinas' => $this->request->getVar("is_dalam_kota") == "1" ? 'dalam kota' : 'luar kota'
    ];
    if ($newFile != "") {
      $data += [
        'file' => $newFile,
      ];
    }
    if ($newBerkas != "") {
      $data += [
        'berkas' => $newBerkas,
      ];
    }

    if ($pic) {
      $data += [
        'pic' => $pic['nama'],
        'pic_nip' => $pic['nip'],
      ];
    } else {
      $data += [
        'pic' => $this->request->getVar("pic_nama"),
        'pic_nip' => $this->request->getVar("pic_nip")
      ];
    }

    if ($this->sinadiPerjalananDinasModel->save($data)) {
      $newId = $cekPerjalanan[0]['id_perjalanan'];
      $jmlDataPeserta = count($this->request->getVar("nip"));
      $inputManual = 0;
      $inputOtomatis = 0;
      $nip = "";
      $nama = "";
      $jabatan = "";
      $golongan = "";
      // d(count($this->request->getVar("tes")));
      $dataSebelumnya = 0;
      for ($i = 0; $i < $jmlDataPeserta; $i++) {
        if ($this->request->getVar("nip")[$i] == "") {
          $nip = $this->request->getVar("manualNip")[$inputManual];
          $nama = $this->request->getVar("manualNama")[$inputManual];
          $jabatan = $this->request->getVar("manualJabatan")[$inputManual];
          $golongan = $this->request->getVar("manualGolongan")[$inputManual];
          $inputManual++;
        } else {
          $nip = $this->request->getVar("nip")[$i];
          $nama = $this->request->getVar("nama")[$inputOtomatis];
          $jabatan = $this->request->getVar("jabatan")[$inputOtomatis];
          $golongan = $this->request->getVar("golongan")[$inputOtomatis];
          $inputOtomatis++;
        }
        $no_sppd = '-';
        $tgl_sppd = null;
        $kota_tujuan = '';
        $kota = 'dalam kota';
        if (isset($this->request->getVar("no_sppd")[$i])) {
          $no_sppd = $this->request->getVar("no_sppd")[$i];
          $tgl_sppd = $this->request->getVar("tgl_sppd")[$i];
        }
        if (isset($this->request->getVar("kota_tujuan")[$i])) {
          if ($this->request->getVar("is_dalam_kota") == "1") {
            $kota = 'dalam kota';
            $kota_tujuan = $this->request->getVar("kota")[$i];
          } else {
            $kota = 'luar kota';
            $kota_tujuan = $this->request->getVar("kota_tujuan")[$i];
          }
        } else {
          $kota_tujuan = $this->request->getVar("kota_asal")[$i];
        }
        $data = [];
        if ($dataSebelumnya != count($cekPerjalanan)) {
          $data += [
            'id_peserta' => $cekPerjalanan[$i]['id_peserta']
          ];
          $dataSebelumnya += 1;
        }

        $data += [
          'id_perjalanan' => $newId,
          'id_sinadi_setting' => $this->sinadiSettingModel->where('status', 'Aktif')->first()['id_sinadi_setting'],
          'tgl_pergi' => $this->request->getVar("tgl_pergi")[$i],
          'tgl_pulang' => $this->request->getVar("tgl_pulang")[$i],
          'nip_pegawai' => $nip,
          'nama_pegawai' => $nama,
          'jabatan_pegawai' => $jabatan,
          'golongan' => $golongan,
          'no_surat_tugas' => $this->request->getVar("no_surat_tugas")[$i],
          'tgl_surat_tugas' => $this->request->getVar("tgl_surat_tugas")[$i],
          'no_sppd' => $no_sppd, // $this->request->getVar("no_sppd")[$i],
          'tgl_sppd' => $tgl_sppd, // $this->request->getVar("tgl_sppd")[$i],
          'kota' => $kota_tujuan,
          'kota_asal' => $this->request->getVar("kota_asal")[$i],
          'transport_hari' => $this->konversiStringKeFloat($this->request->getVar("transport_hari")[$i]),
          'transport_nominal' => $this->konversiStringKeFloat($this->request->getVar("transport_nominal")[$i]),
          'transport_jumlah' => $this->konversiStringKeFloat($this->request->getVar("transport_jumlah")[$i]),
          'harian_hari' => $this->konversiStringKeFloat($this->request->getVar("harian_hari")[$i]),
          'harian_nominal' => $this->konversiStringKeFloat($this->request->getVar("harian_nominal")[$i]),
          'harian_jumlah' => $this->konversiStringKeFloat($this->request->getVar("harian_jumlah")[$i]),
          'penginapan_hari' => $this->konversiStringKeFloat($this->request->getVar("penginapan_hari")[$i]),
          'penginapan_nominal' => $this->konversiStringKeFloat($this->request->getVar("penginapan_nominal")[$i]),
          'penginapan_jumlah' => $this->konversiStringKeFloat($this->request->getVar("penginapan_jumlah")[$i]),
          'jumlah_transfer' => $this->konversiStringKeFloat($this->request->getVar("jumlah_transfer")[$i]),
          'transportasi' => $this->request->getVar("transportasi")[$i],
        ];
        $this->sinadiPerjalananDinasPesertaModel->save($data);
      }
      // Linimasa
      $jmlTransfer = $this->hitungTotal($this->request->getVar("jumlah_transfer"));
      $timeline = [
        'icon'        => 'fas fa-edit bg-warning',
        'id_table'    => $newId,
        'table'       => 'sinadi_perjalanan_dinas',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'memperbaharui data SINADI dengan total peserta yang mengikuti perjalanan sebanyak ' . $jmlDataPeserta . ' orang',
        'keterangan'  => "<p>Anggaran yang terhitung untuk tujuan tempat perjalanan di '" . $this->request->getVar("tempat_perjalanan") . "' dengan jumlah peserta tersebut sebanyak <strong>Rp" . number_format($jmlTransfer, 2, ',', '.') . "</strong> (" . terbilang($jmlTransfer) . ")</p>",
      ];
      $this->linimasaModel->save($timeline);
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
    }
    if ($kota == 'luar kota')
      return redirect()->to('sinadi/dinasLuarKota');
    else
      return redirect()->to('sinadi/dinasDalamKota');
  }


  public function sinadiHapusPeserta($id)
  {
    $cekData = $this->sinadiPerjalananDinasPesertaModel->where('id_peserta', $id)->first();
    $cekDataMaster = $this->sinadiPerjalananDinasModel
      ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
      ->where('sinadi_perjalanan_dinas_peserta.id_peserta', $id)
      ->first();

    if ($cekDataMaster) {
      $transport_jumlah = $cekDataMaster['total_transport'] - $cekData['transport_jumlah'];
      $harian_jumlah = $cekDataMaster['total_harian'] - $cekData['harian_jumlah'];
      $penginapan_jumlah = $cekDataMaster['total_penginapan'] - $cekData['penginapan_jumlah'];
      $total_perjalanan = $cekDataMaster['total_perjalanan'] - $cekData['jumlah_transfer'];
      $data = [
        'id_perjalanan' => $cekDataMaster['id_perjalanan'],
        'transport_jumlah' => $transport_jumlah,
        'harian_jumlah' => $harian_jumlah,
        'penginapan_jumlah' => $penginapan_jumlah,
        'total_perjalanan' => $total_perjalanan
      ];
      $this->sinadiPerjalananDinasModel->save($data);
    }
    if ($cekData) {
      $this->sinadiPerjalananDinasPesertaModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-user-minus bg-danger',
        'id_table'    => $cekDataMaster['id_perjalanan'],
        'table'       => 'sinadi_perjalanan_dinas',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data peserta <strong>' . $cekData['nama_pegawai'] . '</strong> dari kegiatan <i>' . $cekDataMaster['nama_kegiatan'] . '</i>',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }
  public function hapusSinadi($id)
  {
    $cekDataMaster = $this->sinadiPerjalananDinasModel
      ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas_peserta.id_perjalanan=sinadi_perjalanan_dinas.id_perjalanan')
      ->where('sinadi_perjalanan_dinas.id_perjalanan', $id)
      ->first();

    if ($cekDataMaster['file'] != '') {

      $fileName = $cekDataMaster['file'];
      $filePath = ROOTPATH . 'writable/file-sinadi/' . $fileName;

      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }
    if ($cekDataMaster['berkas'] != '') {

      $fileName = $cekDataMaster['berkas'];
      $filePath = ROOTPATH . 'writable/file-sinadi/' . $fileName;

      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }

    if ($cekDataMaster) {
      $this->sinadiPerjalananDinasModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $cekDataMaster['id_perjalanan'],
        'table'       => 'sinadi_perjalanan_dinas',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data perjalanan kegiatan <i>' . $cekDataMaster['nama_kegiatan'] . '</i>',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }

  public function insertAkses()
  {
    if ($this->request->isAjax()) {
      $akses = $this->userModel
        ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      if ($akses && $akses['status'] == 'Akses Diterima') {

        $by = $this->userModel->where('username', session()->get('username'))->first();
        $msg = [];
        $user = $this->request->getVar("id");
        $cek = $this->sinadiAksesModel->where('id_user', $user)->first();
        $icon = '';
        $userName = $this->userModel->where('id_user', $user)->first();
        $aktivitas = '';

        $data = [];
        if ($cek) {
          $data += [
            'id_sinadi_akses' => $cek['id_sinadi_akses'],
          ];
          $icon = 'fas fa-user-edit bg-warning';
          $aktivitas = 'memperbaharui akses penuh SINADI kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diterima)</i>';
        } else {
          $icon = 'fas fa-user-plus bg-success';
          $aktivitas = 'menambahkan akses baru penuh SINADI kepada user <strong>' . name_capitalize($userName['nama']) . '</strong>';
        }

        $data += [
          'id_user' => $user,
          'status' => 'Akses Diterima',
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
        if ($this->sinadiAksesModel->save($data)) {
          $newId = $this->sinadiAksesModel->getInsertID();
          $timeline = [
            'icon'        => $icon,
            'id_table'    => $newId,
            'table'       => 'sinadi_akses',
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
      $akses = $this->userModel
        ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      if ($akses && $akses['status'] == 'Akses Diterima') {
        $by = $this->userModel->where('username', session()->get('username'))->first();
        $msg = [];
        $user = $this->request->getVar("id");
        $cek = $this->sinadiAksesModel->where('id_user', $user)->first();
        $icon = '';
        $userName = $this->userModel->where('id_user', $user)->first();
        $aktivitas = '';

        $data = [];
        if ($cek) {
          $data += [
            'id_sinadi_akses' => $cek['id_sinadi_akses'],
          ];
          $icon = 'fas fa-user-alt-slash bg-danger';
          $aktivitas = 'memperbaharui akses penuh SINADI kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diblokir)</i>';
        } else {
          $icon = 'fas fa-user-times bg-danger';
          $aktivitas = 'menambahkan akses baru penuh SINADI kepada user <strong>' . name_capitalize($userName['nama']) . '</strong> dengan keterangan <i>(Akses Diblokir)</i>';
        }

        $data += [
          'id_user' => $user,
          'status' => 'Akses Diblokir',
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
        if ($this->sinadiAksesModel->save($data)) {
          $newId = $this->sinadiAksesModel->getInsertID();
          $timeline = [
            'icon'        => $icon,
            'id_table'    => $newId,
            'table'       => 'sinadi_akses',
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
      } else {
        $msg = [
          'data' => 'error'
        ];
      }
      echo json_encode($msg);
    }
  }
  public function hapusNotaDinas()
  {
    if ($this->request->isAJAX()) {
      $id_peserta = $this->request->getVar('id');
      $fileName = $this->request->getVar('file');
      $by = $this->userModel->where('username', session()->get('username'))->first();

      if ($fileName != null && $fileName != '') {
        $targetDir = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'nota-dinas';
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
          if (unlink($filePath)) {
            $data = [
              'id_peserta' => $id_peserta,
              'file' => null
            ];
            
            if ($this->sinadiPerjalananDinasPesertaModel->save($data)) {
              $timeline = [
                'icon'        => 'fas fa-trash bg-danger',
                'id_table'    => $id_peserta,
                'table'       => 'sinadi_perjalanan_dinas_peserta',
                'id_user'     => $by['id_user'],
                'aktivitas'   => 'menghapus dokumen nota dinas',
                'keterangan'  => "",
              ];
              $this->linimasaModel->save($timeline);
              
              echo json_encode(['status' => 'success', 'message' => 'Dokumen nota dinas berhasil dihapus']);
            } else {
              echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui database']);
            }
          } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus file dari server']);
          }
        } else {
          // File tidak ada di server, tapi hapus juga record di database
          $data = [
            'id_peserta' => $id_peserta,
            'file' => null
          ];
          $this->sinadiPerjalananDinasPesertaModel->save($data);
          echo json_encode(['status' => 'success', 'message' => 'File tidak ditemukan di server, record database telah dihapus']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Nama file tidak valid']);
      }
    }
  }

  public function saveNotaDinas()
  {
    $id_peserta = $this->request->getVar("id_peserta");

    $file = $this->request->getFile('file');
    $data = [];

    // Tentukan direktori tujuan secara konsisten dan pastikan tersedia
    $targetDir = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'nota-dinas';
    if (!is_dir($targetDir)) {
      @mkdir($targetDir, 0775, true);
    }
    if (is_dir($targetDir) && !is_writable($targetDir)) {
      @chmod($targetDir, 0775);
    }

    if ($file->isValid()) {

      $cekFile = $this->sinadiPerjalananDinasPesertaModel->where("id_peserta", $id_peserta)
      ->join('sinadi_perjalanan_dinas', 'id_perjalanan')->first();

      if ($cekFile && $cekFile['file'] != null) {
        $cekFilePath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $cekFile['file'];
        if (file_exists($cekFilePath)) {
          unlink($cekFilePath);
        }
      }

      if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['pdf'])) {
        // Periksa ukuran file
        $maxFileSize = (1024 * 1024 * 10) + (1024 * 5); // Misalnya, batas ukuran file adalah 10 MB

        if ($file->getSize() <= $maxFileSize) {
          // Buat nama file yang pendek dan aman agar tidak melewati batas 255 karakter pada filesystem
          $ts = date('Ymd_His');
          $rand = substr(sha1(uniqid('', true)), 0, 8);
          // Bangun nama file deskriptif (mirip pola lama) namun tetap aman dan pendek
          if ($cekFile) {
            $kegiatanRaw = $cekFile['nama_kegiatan'] ?? '';
            $pegawaiRaw = $cekFile['nama_pegawai'] ?? '';
            $sanitize = function ($str) {
              $str = preg_replace('/[\\\\\\/\\:\\*\\?\"<>\\|]+/', ' ', $str); // hapus karakter ilegal di Windows/Linux
              $str = preg_replace('/[^A-Za-z0-9 _\-\(\)]+/', '', $str); // sisakan huruf, angka, spasi, -, _ dan ()
              $str = trim(preg_replace('/\s+/', ' ', $str)); // rapikan spasi berulang
              return $str;
            };
            $kegiatan = substr($sanitize($kegiatanRaw), 0, 160);
            $pegawai  = substr($sanitize($pegawaiRaw), 0, 40);
            $newName = "nota_dinas - (" . ($kegiatan !== '' ? $kegiatan : 'Kegiatan') . ") - (" . ($pegawai !== '' ? $pegawai : 'Pegawai') . ")_{$id_peserta}_{$ts}_{$rand}.pdf";
          } else {
            $newName = "nota_dinas_{$id_peserta}_{$ts}_{$rand}.pdf";
          }

          // Logging untuk membantu diagnosa di server, termasuk panjang nama dan path penuh
          $destFull = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $newName;
          log_message('info', 'Upload Nota Dinas -> targetDir: ' . $targetDir . ' | tmp: ' . ($file->getTempName() ?: '-') . ' | is_dir: ' . (is_dir($targetDir) ? '1' : '0') . ' | is_writable: ' . (is_writable($targetDir) ? '1' : '0') . ' | newNameLen: ' . strlen($newName) . ' | destPathLen: ' . strlen($destFull));
          try {
            $file->move($targetDir, $newName);
          } catch (\Throwable $e) {
            // Fallback: coba copy dari tmp ke target bila move() gagal (mis. karena restriction hosting)
            $tmp = $file->getTempName();
            $dest = $destFull;
            $copied = @copy($tmp, $dest);
            if ($copied && file_exists($dest)) {
              @chmod($dest, 0644);
              // lanjutkan tanpa return error
            } else {
              $err = error_get_last();
              log_message('error', 'Gagal memindahkan Nota Dinas (move & copy). Exception: ' . $e->getMessage() . ' | CopyError: ' . (($err['message'] ?? 'unknown')) . ' | targetDir: ' . $targetDir . ' | tmp: ' . ($tmp ?: '-') . ' | dest: ' . $dest);
              session()->setFlashdata('error', 'Gagal menyimpan file di server. Silakan hubungi admin hosting.');
              return redirect()->to('/sinadi/notaDinas');
            }
          }
          if (file_exists(rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $newName)) {
            $data += [
              'file' => $newName
            ];
          } else {
            log_message('error', 'File Nota Dinas tidak berhasil tersimpan di tujuan: ' . rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $newName);
            session()->setFlashdata('error', 'File tidak berhasil tersimpan. Silakan coba lagi.');
            return redirect()->to('/sinadi/notaDinas');
          }
        } else {
          session()->setFlashdata('error', 'Ukuran file melebihi batas maksimum (15MB)');
          return redirect()->to('/sinadi/notaDinas');
        }
      } else {
        session()->setFlashdata('error', 'File tidak diunggah, tidak valid, atau tidak sesuai ekstensi');
        return redirect()->to('/sinadi/notaDinas');
      }
    }
    $data += [
      'id_peserta' => $id_peserta
    ];

    $by = $this->userModel->where('username', session()->get('username'))->first();

    if ($this->sinadiPerjalananDinasPesertaModel->save($data)) {
      $timeline = [
        'icon'        => 'fas fa-edit bg-warning',
        'id_table'    => $id_peserta,
        'table'       => 'sinadi_perjalanan_dinas_peserta',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'mengupload nota dinas',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);

      // Logika Upload untuk Semua Peserta
      if ($this->request->getVar('upload_all') == '1' && isset($data['file'])) {
          $sourceFile = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $data['file'];
          
          if (file_exists($sourceFile) && isset($cekFile['id_perjalanan'])) {
              // Ambil peserta lain dalam perjalanan yang sama
              $others = $this->sinadiPerjalananDinasPesertaModel
                  ->select('sinadi_perjalanan_dinas_peserta.*, data_pegawai.nama as nama_pegawai')
                  ->join('data_pegawai', 'data_pegawai.nip = sinadi_perjalanan_dinas_peserta.nip_pegawai')
                  ->where('id_perjalanan', $cekFile['id_perjalanan'])
                  ->where('id_peserta !=', $id_peserta)
                  ->findAll();

              foreach ($others as $other) {
                  // Hapus file lama jika ada
                  if ($other['file'] && file_exists(rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $other['file'])) {
                      @unlink(rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $other['file']);
                  }

                  // Generate nama file baru
                  $ts = date('Ymd_His');
                  $rand = substr(sha1(uniqid('', true)), 0, 8);
                  
                  // Gunakan nama kegiatan dari cekFile (sama untuk satu perjalanan) dan nama pegawai dari other
                  $kegiatanRaw = $cekFile['nama_kegiatan'] ?? '';
                  $pegawaiRaw = $other['nama_pegawai'] ?? '';
                  
                  // Reuse sanitize function logic (inline here for simplicity or define it before if possible)
                  $sanitize = function ($str) {
                      $str = preg_replace('/[\\\\\\/\\:\\*\\?\"<>\\|]+/', ' ', $str);
                      $str = preg_replace('/[^A-Za-z0-9 _\-\(\)]+/', '', $str);
                      $str = trim(preg_replace('/\s+/', ' ', $str));
                      return $str;
                  };

                  $kegiatan = substr($sanitize($kegiatanRaw), 0, 160);
                  $pegawai  = substr($sanitize($pegawaiRaw), 0, 40);
                  $otherNewName = "nota_dinas - (" . ($kegiatan !== '' ? $kegiatan : 'Kegiatan') . ") - (" . ($pegawai !== '' ? $pegawai : 'Pegawai') . ")_{$other['id_peserta']}_{$ts}_{$rand}.pdf";
                  
                  $destFile = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $otherNewName;

                  // Copy file
                  if (@copy($sourceFile, $destFile)) {
                      // Update Database
                      $this->sinadiPerjalananDinasPesertaModel->save([
                          'id_peserta' => $other['id_peserta'],
                          'file' => $otherNewName
                      ]);

                      // Catat Timeline untuk peserta ini
                      $this->linimasaModel->save([
                          'icon'        => 'fas fa-edit bg-warning',
                          'id_table'    => $other['id_peserta'],
                          'table'       => 'sinadi_perjalanan_dinas_peserta',
                          'id_user'     => $by['id_user'],
                          'aktivitas'   => 'mengupload nota dinas (via upload all)',
                          'keterangan'  => "",
                      ]);
                  }
              }
          }
      }

      // INTEGRASI MONITORING SANITASI KAPAL
      // Cek apakah perjalanan dinas ini memiliki monitoring sanitasi kapal
      $monitoring = $this->sinadiPerjalananDinasModel
          ->join('monitoring_sanitasi_kapal', 'monitoring_sanitasi_kapal.id = sinadi_perjalanan_dinas.id_monitoring_sanitasi', 'left') // Asumsi ada relasi atau cara linking
          ->where('id_perjalanan', $cekFile['id_perjalanan']) // id_perjalanan dari peserta yang sedang upload
          ->first();

      // Karena struktur tabel mungkin berbeda, kita cari monitoring berdasarkan id_perjalanan jika ada relasi,
      // atau jika monitoring disimpan terpisah, kita perlu cari data monitoring yang sesuai.
      // Berdasarkan kode tambah_sinadi, data monitoring disimpan di tabel monitoring_sanitasi_kapal.
      // Namun, tidak ada foreign key langsung yang disimpan di sinadi_perjalanan_dinas saat insert (berdasarkan kode tambah_sinadi sebelumnya).
      // Kita asumsikan pencarian berdasarkan tanggal dan nama kapal atau parameter lain yang unik, 
      // TAPI yang paling aman adalah jika kita tahu ID monitoringnya.
      // Jika belum ada relasi, kita coba cari berdasarkan 'created_by' dan 'tanggal' yang sama dengan perjalanan dinas ini.
      
      // ALTERNATIF: Kita update tabel monitoring_sanitasi_kapal yang terkait dengan perjalanan ini.
      // Karena kita tidak punya ID monitoring di sini, mari kita lihat apakah kita bisa menemukannya.
      // Di tambah_sinadi, monitoring disimpan.
      // Kita perlu mekanisme untuk menghubungkan Laporan Tindakan di Monitoring dengan File Nota Dinas Peserta Pertama.

      // Logika: 
      // 1. Ambil data perjalanan dinas berdasarkan id_peserta
      // 2. Cek apakah ini peserta pertama? (Opsional, atau setiap upload menimpa)
      // 3. Jika perjalanan ini terhubung dengan monitoring sanitasi (misal cek apakah ada monitoring di tanggal & user yg sama, atau kita tambahkan id_perjalanan di tabel monitoring)
      
      // Karena struktur database belum diubah untuk relasi explicit, kita gunakan pendekatan:
      // Cari data monitoring sanitasi kapal yang dibuat oleh user yang sama pada tanggal pelaksanaan yang sama (asumsi sederhana).
      // Atau lebih baik, kita update tabel monitoring_sanitasi_kapal kolom laporan_tindakan.
      
      $monitoringModel = new MonitoringSanitasiKapalModel();
      // Cari monitoring yang tanggalnya sama dengan tgl_pergi perjalanan ini dan dibuat oleh user ini (atau user pembuat perjalanan)
      // Perlu join ke tabel sinadi_perjalanan_dinas untuk dapat tgl_pelaksanaan
      $perjalanan = $this->sinadiPerjalananDinasModel->find($cekFile['id_perjalanan']);
      
      if ($perjalanan) {
          $monitoringData = $monitoringModel
              ->where('tanggal', $perjalanan['tgl_pelaksanaan'])
              ->where('created_by', $perjalanan['created_by']) // Asumsi pembuat sama
              ->orderBy('id', 'DESC') // Ambil yang terbaru jika ada banyak
              ->first();

          if ($monitoringData) {
               // Salin file nota dinas ke folder laporan monitoring
               $monitoringBaseDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'monitoring_sanitasi' . DIRECTORY_SEPARATOR . 'laporan';
               if (!is_dir($monitoringBaseDir)) {
                   @mkdir($monitoringBaseDir, 0775, true);
               }
               
               // Nama file baru untuk laporan monitoring
               $laporanExt = pathinfo($data['file'], PATHINFO_EXTENSION);
               $laporanName = 'Laporan_Tindakan_' . $monitoringData['id'] . '_' . time() . '.' . $laporanExt;
               $laporanDest = $monitoringBaseDir . DIRECTORY_SEPARATOR . $laporanName;
               $notaSource = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $data['file'];

               if (copy($notaSource, $laporanDest)) {
                   // Update tabel monitoring
                   $monitoringModel->update($monitoringData['id'], [
                       'laporan_tindakan' => 'uploads/monitoring_sanitasi/laporan/' . $laporanName
                   ]);
               }
          }
      }

      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('/sinadi/notaDinas');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('/sinadi/notaDinas');
    }
  }

  public function insertAksesTim()
  {
    if ($this->request->isAjax()) {
      $akses = $this->userModel
        ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      // Hanya Super User yang bisa mengubah akses
      if ($akses && $akses['status'] == 'Akses Diterima') {

        $by = $this->userModel->where('username', session()->get('username'))->first();
        $msg = [];
        $user = $this->request->getVar("id");
        $cek = $this->sinadiAksesModel->where('id_user', $user)->first();
        $userName = $this->userModel->where('id_user', $user)->first();

        $data = [];
        if ($cek) {
          $data += [
            'id_sinadi_akses' => $cek['id_sinadi_akses'],
            'akses_tim' => 1,
            'updated_by' => $by['id_user'],
          ];
        } else {
          $data += [
            'id_user' => $user,
            'status' => 'Akses Diblokir', // Default status
            'akses_tim' => 1,
            'created_by' => $by['id_user'],
            'updated_by' => $by['id_user'],
          ];
        }

        if ($this->sinadiAksesModel->save($data)) {
          $msg = [
            'data' => 'sukses'
          ];
        } else {
          $msg = [
            'data' => 'error'
          ];
        }
      } else {
        $msg = [
          'data' => 'error'
        ];
      }
      echo json_encode($msg);
    }
  }

  public function removeAksesTim()
  {
    if ($this->request->isAjax()) {
      $akses = $this->userModel
        ->join('sinadi_akses', 'sinadi_akses.id_user=user.id_user')
        ->where("user.username", session()->get('username'))
        ->first();
      // Hanya Super User yang bisa mengubah akses
      if ($akses && $akses['status'] == 'Akses Diterima') {

        $by = $this->userModel->where('username', session()->get('username'))->first();
        $msg = [];
        $user = $this->request->getVar("id");
        $cek = $this->sinadiAksesModel->where('id_user', $user)->first();
        
        $data = [];
        if ($cek) {
          $data += [
            'id_sinadi_akses' => $cek['id_sinadi_akses'],
            'akses_tim' => 0,
            'updated_by' => $by['id_user'],
          ];
        } else {
          $data += [
            'id_user' => $user,
            'status' => 'Akses Diblokir', // Default status
            'akses_tim' => 0,
            'created_by' => $by['id_user'],
            'updated_by' => $by['id_user'],
          ];
        }

        if ($this->sinadiAksesModel->save($data)) {
          $msg = [
            'data' => 'sukses'
          ];
        } else {
          $msg = [
            'data' => 'error'
          ];
        }
      } else {
        $msg = [
          'data' => 'error'
        ];
      }
      echo json_encode($msg);
    }
  }
}
