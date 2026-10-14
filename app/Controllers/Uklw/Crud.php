<?php

namespace App\Controllers\Uklw;

use App\Controllers\BaseController;
use App\Models\Master\UserModel;

class Crud extends BaseController
{
  public function addRiwayatPenyakit() {
      if($this->request->isAJAX()) {
          $msg = [];
          $istilah_penyakit = $this->request->getVar('istilah_penyakit');
          
          $cek = $this->uklwRiwayatPenyakitModel->where('istilah_penyakit', $istilah_penyakit)->first();
          
          if($cek) {
            $msg = [
              'icon' => 'success',
              'text' => 'Data telah berhasil disimpan sebelumnya! Berhasil ditambahkan...'
            ];  
          } else {
            $by = $this->userModel->where('username', session()->get('username'))->first();
            $data = [
                'istilah_penyakit' => $istilah_penyakit,
                'created_by' => $by['id_user'],
                'updated_by' => $by['id_user'],
            ];
            if($this->uklwRiwayatPenyakitModel->save($data)) {
                $msg = [
                  'icon' => 'success',
                  'text' => 'Data telah berhasil disimpan! Berhasil ditambahkan...'
                ];  
            } else {
                $msg = [
                  'icon' => 'error',
                  'text' => 'Data gagal disimpan! Coba kembali'
                ];  
            }
          }
          
          echo json_encode($msg);
      }
  }
  public function isValidNIK($nik, $tgl_lahir, $jk)
  {
    // Pecah NIK menjadi bagian-bagian yang sesuai
    // $wilayah = substr($nik, 0, 6);
    $tanggal_lahir = substr($nik, 6, 6);
    // $nomor_urut = substr($nik, 12, 4);


    $tgl = substr($tanggal_lahir, 0, 2);
    $bln = substr($tanggal_lahir, 2, 2);
    $thn = substr($tanggal_lahir, 4, 2);

    if ($jk == 'Perempuan') {
      $validasi_tanggal_lahir = (substr($tgl_lahir, 8, 2) + 40) == $tgl && substr($tgl_lahir, 5, 2) == $bln && substr($tgl_lahir, 2, 2) == $thn;
    } else {
      $validasi_tanggal_lahir = substr($tgl_lahir, 8, 2) == $tgl && substr($tgl_lahir, 5, 2) == $bln && substr($tgl_lahir, 2, 2) == $thn;
    }


    // Jika semua bagian valid, kembalikan true; jika tidak, kembalikan false
    return $validasi_tanggal_lahir;
  }

  public function tambahPasien()
  {
    $data = [
      'identitas' => $this->request->getVar("identitas"),
      'nomor_identitas' => $this->request->getVar("nomor_identitas"),
      'nama' => $this->request->getVar("nama"),
      'jk' => $this->request->getVar("jk"),
      'tgl_lahir' => $this->request->getVar("tgl_lahir"),
      'alamat' => $this->request->getVar("alamat"),
      'no_hp' => $this->request->getVar("no_hp"),
      'pendidikan' => $this->request->getVar("pendidikan"),
      'pekerjaan' => $this->request->getVar("pekerjaan"),
      'status' => $this->request->getVar("status"),
      'nama_keluarga' => $this->request->getVar("nama_keluarga"),
      'hubungan_keluarga' => $this->request->getVar("hubungan_keluarga"),
      'no_hp_keluarga' => $this->request->getVar("no_hp_keluarga")
    ];

    $identitas = $this->request->getVar("identitas");
    $nomor_identitas = $this->request->getVar("nomor_identitas");
    $tgl_lahir = $this->request->getVar("tgl_lahir");
    $jk = $this->request->getVar("jk");

    $cekData = [
      'identitas' => $this->request->getVar("identitas"),
      'nomor_identitas' => $this->request->getVar("nomor_identitas"),
    ];

    $cekNik = $this->uklwPasienModel->where($cekData)->first();
    if ($cekNik) {
      session()->setFlashdata('error', 'Identitas dengan nomor ' . $nomor_identitas . ' sudah tersedia!');
      return redirect()->to('/uklw/tambahPasien')->withInput();
    }

    if ($identitas == "NIK") {
      if ($this->isValidNIK($nomor_identitas, $tgl_lahir, $jk)) {
      } else {
        session()->setFlashdata('error', 'Identitas dengan nomor ' . $nomor_identitas . ' tidak valid, silahkan periksa kembali kolom inputan anda!');
        return redirect()->to('/uklw/tambahPasien')->withInput();
      }
    }

    if ($this->uklwPasienModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to('/uklw/pasien');
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to('/uklw/tambahPasien')->withInput();
    }
  }
  public function editPasien()
  {

    $dataSebelumnya = $this->uklwPasienModel->where('id_uklw_pasien', $this->request->getVar("id_uklw_pasien"))->first();
    $data = [
      'id_uklw_pasien' => $this->request->getVar("id_uklw_pasien"),
      'nik' => $this->request->getVar("nik"),
      'nama' => $this->request->getVar("nama"),
      'jk' => $this->request->getVar("jk"),
      'tgl_lahir' => $this->request->getVar("tgl_lahir"),
      'alamat' => $this->request->getVar("alamat"),
      'no_hp' => $this->request->getVar("no_hp"),
      'pendidikan' => $this->request->getVar("pendidikan"),
      'pekerjaan' => $this->request->getVar("pekerjaan"),
      'status' => $this->request->getVar("status"),
      'nama_keluarga' => $this->request->getVar("nama_keluarga"),
      'hubungan_keluarga' => $this->request->getVar("hubungan_keluarga"),
      'no_hp_keluarga' => $this->request->getVar("no_hp_keluarga")
    ];

    $identitas = $this->request->getVar("identitas");
    $nomor_identitas = $this->request->getVar("nomor_identitas");
    $tgl_lahir = $this->request->getVar("tgl_lahir");
    $jk = $this->request->getVar("jk");


    $cekData = [
      'identitas' => $this->request->getVar("identitas"),
      'nomor_identitas' => $this->request->getVar("nomor_identitas"),
    ];

    $cekNik = $this->uklwPasienModel->where($cekData)->first();
    if ($cekNik) {
      if ($cekNik['nomor_identitas'] == $dataSebelumnya['nomor_identitas']) {
      } else {
        session()->setFlashdata('error', 'NIK dengan nomor ' . $nomor_identitas . ' sudah tersedia!');
        return redirect()->to('/uklw/editPasien/' . $this->request->getVar("id_uklw_pasien"))->withInput();
      }
    }

    if ($identitas == "NIK") {
      if ($this->isValidNIK($nomor_identitas, $tgl_lahir, $jk)) {
      } else {
        session()->setFlashdata('error', 'NIK dengan nomor ' . $nomor_identitas . ' tidak valid, silahkan periksa kembali kolom inputan anda!');
        return redirect()->to('/uklw/editPasien/' . $this->request->getVar("id_uklw_pasien"))->withInput();
      }
    }

    if ($this->uklwPasienModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil diperbaharui.');
      return redirect()->to('/uklw/pasien');
    } else {
      session()->setFlashdata('error', 'Data gagal diperbaharui.');
      return redirect()->to('/uklw/editPasien/' . $this->request->getVar("id_uklw_pasien"))->withInput();
    }
  }
  public function tambahPemkesAjax()
  {
    if ($this->request->isAJAX()) {
      $msg = [];
      $data = [];
      $by = $this->userModel->where('username', session()->get('username'))->first();

      $id_uklw_pemkes = $this->request->getVar("id_uklw_pemkes");
      if ($id_uklw_pemkes != '') {
        $data += [
          'id_uklw_pemkes' => $id_uklw_pemkes,
          'updated_by' => $by['id_user'],
        ];
      } else {
        $data += [
          'created_by' => $by['id_user'],
          'updated_by' => $by['id_user'],
        ];
      }

      $id_uklw_pasien = $this->request->getVar("id_uklw_pasien");
      $data += [
        'status_pemeriksaan' => $this->request->getVar("status_pemeriksaan"),
        'id_uklw_pasien' => $id_uklw_pasien
      ];

      if ($this->request->getVar("status_pemeriksaan") == "Penjamah Makanan") {
        $wawancara = [];
        for ($i = 0; $i < count($this->request->getVar("wawancara")); $i++) {
          $wawancara[$i] = [
            'wawancara' => $this->request->getVar("wawancara")[$i],
            'hasil_wawancara' => $this->request->getVar("hasil_wawancara" . $i),
          ];
        }
        $data += [
          'wawancara' => serialize($wawancara)
        ];

        $hygiene = [];
        for ($i = 0; $i < count($this->request->getVar("hygiene")); $i++) {
          $hygiene[$i] = [
            'hygiene' => $this->request->getVar("hygiene")[$i],
            'hasil_hygiene' => $this->request->getVar("hasil_hygiene" . $i),
            'ket_hygiene' => $this->request->getVar("ket_hygiene")[$i],
          ];
        }
        $data += [
          'hygiene' => serialize($hygiene)
        ];

        $pemeriksaan_fisik_penjamah = [];
        for ($i = 0; $i < count($this->request->getVar("pemeriksaan_fisik_penjamah")); $i++) {
          $pemeriksaan_fisik_penjamah[$i] = [
            'pemeriksaan_fisik_penjamah' => $this->request->getVar("pemeriksaan_fisik_penjamah")[$i],
            'ket_pemeriksaan_fisik_penjamah' => $this->request->getVar("ket_pemeriksaan_fisik_penjamah")[$i],
          ];
        }
        $data += [
          'pemeriksaan_fisik_penjamah' => serialize($pemeriksaan_fisik_penjamah)
        ];
      }

      $pemeriksaan_fisik = [];
      for ($i = 0; $i < count($this->request->getVar("pemeriksaan_fisik")); $i++) {
        $pemeriksaan_fisik[$i] = [
          'pemeriksaan_fisik' => $this->request->getVar("pemeriksaan_fisik")[$i],
          'hasil_pemeriksaan_fisik' => $this->request->getVar("hasil_pemeriksaan_fisik")[$i],
          'satuan_pemeriksaan_fisik' => $this->request->getVar("satuan_pemeriksaan_fisik")[$i],
        ];
      }
      $data += [
        'pemeriksaan_fisik' => serialize($pemeriksaan_fisik)
      ];

      $riwayat_penyakit = [];
      for ($i = 0; $i < count($this->request->getVar("riwayat_penyakit")); $i++) {
        $riwayat_penyakit[$i] = [
          'riwayat_penyakit' => $this->request->getVar("riwayat_penyakit")[$i],
          'hasil_riwayat_penyakit' => $this->request->getVar("hasil_riwayat_penyakit_value")[$i],
        ];
      }
      $data += [
        'riwayat_penyakit' => serialize($riwayat_penyakit)
      ];

      $skrining_tbc_gejala = [];
      for ($i = 0; $i < count($this->request->getVar("skrining_tbc_gejala")); $i++) {
        $skrining_tbc_gejala[$i] = [
          'skrining_tbc_gejala' => $this->request->getVar("skrining_tbc_gejala")[$i],
          'hasil_skrining_tbc_gejala' => $this->request->getVar("hasil_skrining_tbc_gejala" . $i),
          'ket_skrining_tbc_gejala' => $this->request->getVar("ket_skrining_tbc_gejala")[$i],
        ];
      }
      $data += [
        'skrining_tbc_gejala' => serialize($skrining_tbc_gejala)
      ];


      $skrining_hiv = [];
      for ($i = 0; $i < count($this->request->getVar("skrining_hiv")); $i++) {
        $skrining_hiv[$i] = [
          'skrining_hiv' => $this->request->getVar("skrining_hiv")[$i],
          'hasil_skrining_hiv' => $this->request->getVar("hasil_skrining_hiv" . $i),
          'ket_skrining_hiv' => $this->request->getVar("ket_skrining_hiv")[$i],
        ];
      }
      $data += [
        'skrining_hiv' => serialize($skrining_hiv)
      ];

      $skrining_penyakit_tidak_menular = [];
      for ($i = 0; $i < count($this->request->getVar("skrining_penyakit_tidak_menular")); $i++) {
        $skrining_penyakit_tidak_menular[$i] = [
          'skrining_penyakit_tidak_menular' => $this->request->getVar("skrining_penyakit_tidak_menular")[$i],
          'hasil_skrining_penyakit_tidak_menular' => $this->request->getVar("hasil_skrining_penyakit_tidak_menular" . $i),
          'ket_skrining_penyakit_tidak_menular' => $this->request->getVar("ket_skrining_penyakit_tidak_menular")[$i],
        ];
      }
      $data += [
        'skrining_penyakit_tidak_menular' => serialize($skrining_penyakit_tidak_menular)
      ];

      if ($this->uklwPemkesModel->save($data)) {
        $newID = $this->uklwPemkesModel->getInsertID();
        if ($id_uklw_pemkes != '') {
          $newID = $id_uklw_pemkes;
        } else {
          $newID = $this->uklwPemkesModel->getInsertID();
        }
        $msg = [
          'data' => $newID
        ];
      }
      echo json_encode($msg);
    }
  }
  public function tambahPemkes()
  {

    $data = [];
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $id_uklw_pemkes = $this->request->getVar("id_uklw_pemkes");
    if ($id_uklw_pemkes != '') {
      $data += [
        'id_uklw_pemkes' => $id_uklw_pemkes,
        'updated_by' => $by['id_user'],
      ];
    } else {
      $data += [
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
    }

    $id_uklw_pasien = $this->request->getVar("id_uklw_pasien");
    $data += [
      'status_pemeriksaan' => $this->request->getVar("status_pemeriksaan"),
      'id_uklw_pasien' => $id_uklw_pasien,
      'kota' => $this->request->getVar("kota")
    ];

    if ($this->request->getVar("status_pemeriksaan") == "Penjamah Makanan") {
      $wawancara = [];
      for ($i = 0; $i < count($this->request->getVar("wawancara")); $i++) {
        $wawancara[$i] = [
          'wawancara' => $this->request->getVar("wawancara")[$i],
          'hasil_wawancara' => $this->request->getVar("hasil_wawancara" . $i),
        ];
      }
      $data += [
        'wawancara' => serialize($wawancara)
      ];

      $hygiene = [];
      for ($i = 0; $i < count($this->request->getVar("hygiene")); $i++) {
        $hygiene[$i] = [
          'hygiene' => $this->request->getVar("hygiene")[$i],
          'hasil_hygiene' => $this->request->getVar("hasil_hygiene" . $i),
          'ket_hygiene' => $this->request->getVar("ket_hygiene")[$i],
        ];
      }
      $data += [
        'hygiene' => serialize($hygiene)
      ];

      $pemeriksaan_fisik_penjamah = [];
      for ($i = 0; $i < count($this->request->getVar("pemeriksaan_fisik_penjamah")); $i++) {
        $pemeriksaan_fisik_penjamah[$i] = [
          'pemeriksaan_fisik_penjamah' => $this->request->getVar("pemeriksaan_fisik_penjamah")[$i],
          'ket_pemeriksaan_fisik_penjamah' => $this->request->getVar("ket_pemeriksaan_fisik_penjamah")[$i],
        ];
      }
      $data += [
        'pemeriksaan_fisik_penjamah' => serialize($pemeriksaan_fisik_penjamah)
      ];
    }

    $pemeriksaan_fisik = [];
    for ($i = 0; $i < count($this->request->getVar("pemeriksaan_fisik")); $i++) {
      $pemeriksaan_fisik[$i] = [
        'pemeriksaan_fisik' => $this->request->getVar("pemeriksaan_fisik")[$i],
        'hasil_pemeriksaan_fisik' => $this->request->getVar("hasil_pemeriksaan_fisik")[$i],
        'satuan_pemeriksaan_fisik' => $this->request->getVar("satuan_pemeriksaan_fisik")[$i],
      ];
    }

    $data += [
      'pemeriksaan_fisik' => serialize($pemeriksaan_fisik)
    ];

    $riwayat_penyakit = [];
    for ($i = 0; $i < count($this->request->getVar("riwayat_penyakit")); $i++) {
      $riwayat_penyakit[$i] = [
        'riwayat_penyakit' => $this->request->getVar("riwayat_penyakit")[$i],
        'hasil_riwayat_penyakit' => $this->request->getVar("hasil_riwayat_penyakit_value")[$i],
      ];
    }
    $data += [
      'riwayat_penyakit' => serialize($riwayat_penyakit)
    ];

    if ($this->uklwPemkesModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to("/uklw/pasien");
    }
  }
  public function tambahSkriningTBC()
  {

    $data = [];
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $id_uklw_pemkes = $this->request->getVar("id_uklw_pemkes");
    if ($id_uklw_pemkes != '') {
      $data += [
        'id_uklw_skrining_tbc' => $id_uklw_pemkes,
        'updated_by' => $by['id_user'],
      ];
    } else {
      $data += [
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
    }

    $id_uklw_pasien = $this->request->getVar("id_uklw_pasien");
    $data += [
      'id_uklw_pasien' => $id_uklw_pasien
    ];

    $skrining_tbc_gejala = [];
    for ($i = 0; $i < count($this->request->getVar("skrining_tbc_gejala")); $i++) {
      $skrining_tbc_gejala[$i] = [
        'skrining_tbc_gejala' => $this->request->getVar("skrining_tbc_gejala")[$i],
        'hasil_skrining_tbc_gejala' => $this->request->getVar("hasil_skrining_tbc_gejala" . $i),
        'ket_skrining_tbc_gejala' => $this->request->getVar("ket_skrining_tbc_gejala")[$i],
      ];
    }
    // dd($skrining_tbc_gejala);
    $data += [
      'skrining_tbc_gejala' => serialize($skrining_tbc_gejala)
    ];

    if ($this->uklwSkriningTBCModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to("/uklw/pasien");
    }
  }
  public function tambahSkriningHIV()
  {
    $data = [];
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $id_uklw_pemkes = $this->request->getVar("id_uklw_pemkes");
    if ($id_uklw_pemkes != '') {
      $data += [
        'id_uklw_skrining_hiv' => $id_uklw_pemkes,
        'updated_by' => $by['id_user'],
      ];
    } else {
      $data += [
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
    }

    $id_uklw_pasien = $this->request->getVar("id_uklw_pasien");
    $data += [
      'id_uklw_pasien' => $id_uklw_pasien
    ];

    $skrining_hiv = [];
    for ($i = 0; $i < count($this->request->getVar("skrining_hiv")); $i++) {
      $skrining_hiv[$i] = [
        'skrining_hiv' => $this->request->getVar("skrining_hiv")[$i],
        'hasil_skrining_hiv' => $this->request->getVar("hasil_skrining_hiv" . $i),
        'ket_skrining_hiv' => $this->request->getVar("ket_skrining_hiv")[$i],
      ];
    }
    $data += [
      'skrining_hiv' => serialize($skrining_hiv)
    ];

    if ($this->uklwSkriningHIVModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to("/uklw/pasien");
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to("/uklw/pasien");
    }
  }
  public function tambahFaktorRisikoPTM()
  {
    $data = [];
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $id_uklw_pemkes = $this->request->getVar("id_uklw_pemkes");
    if ($id_uklw_pemkes != '') {
      $data += [
        'id_uklw_faktor_risiko_ptm' => $id_uklw_pemkes,
        'updated_by' => $by['id_user'],
      ];
    } else {
      $data += [
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
    }

    $id_uklw_pasien = $this->request->getVar("id_uklw_pasien");
    $data += [
      'id_uklw_pasien' => $id_uklw_pasien
    ];

    $skrining_penyakit_tidak_menular = [];
    for ($i = 0; $i < count($this->request->getVar("skrining_penyakit_tidak_menular")); $i++) {
      $skrining_penyakit_tidak_menular[$i] = [
        'skrining_penyakit_tidak_menular' => $this->request->getVar("skrining_penyakit_tidak_menular")[$i],
        'hasil_skrining_penyakit_tidak_menular' => $this->request->getVar("hasil_skrining_penyakit_tidak_menular" . $i),
        'ket_skrining_penyakit_tidak_menular' => $this->request->getVar("ket_skrining_penyakit_tidak_menular")[$i],
      ];
    }
    $data += [
      'skrining_penyakit_tidak_menular' => serialize($skrining_penyakit_tidak_menular)
    ];

    if ($this->uklwFaktorRisikoPTMModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to("/uklw/pasien");
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to("/uklw/pasien");
    }
  }

  public function tambahLaboratorium()
  {
    $data = [];
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $id_uklw_pemkes = $this->request->getVar("id_uklw_pemkes");
    if ($id_uklw_pemkes != '') {
      $data += [
        'id_uklw_laboratorium' => $id_uklw_pemkes,
        'updated_by' => $by['id_user'],
      ];
    } else {
      $data += [
        'created_by' => $by['id_user'],
        'updated_by' => $by['id_user'],
      ];
    }

    $id_uklw_pasien = $this->request->getVar("id_uklw_pasien");
    $data += [
      'id_uklw_pasien' => $id_uklw_pasien
    ];

    $laboratorium_kimia_klinik = [];
    for ($i = 0; $i < count($this->request->getVar("laboratorium_kimia_klinik")); $i++) {
      $laboratorium_kimia_klinik[$i] = [
        'laboratorium_kimia_klinik' => $this->request->getVar("laboratorium_kimia_klinik")[$i],
        'hasil_laboratorium_kimia_klinik' => $this->request->getVar("hasil_laboratorium_kimia_klinik")[$i],
        'nilai_rujukan_laboratorium_kimia_klinik' => $this->request->getVar("nilai_rujukan_laboratorium_kimia_klinik")[$i],
        'kesimpulan_laboratorium_kimia_klinik' => $this->request->getVar("kesimpulan_laboratorium_kimia_klinik")[$i],
        'satuan_laboratorium_kimia_klinik' => $this->request->getVar("satuan_laboratorium_kimia_klinik")[$i],
      ];
    }
    $data += [
      'laboratorium_kimia_klinik' => serialize($laboratorium_kimia_klinik)
    ];

    $laboratorium_hematologi = [];
    for ($i = 0; $i < count($this->request->getVar("laboratorium_hematologi")); $i++) {
      $laboratorium_hematologi[$i] = [
        'laboratorium_hematologi' => $this->request->getVar("laboratorium_hematologi")[$i],
        'hasil_laboratorium_hematologi' => $this->request->getVar("hasil_laboratorium_hematologi")[$i],
        'nilai_rujukan_laboratorium_hematologi' => $this->request->getVar("nilai_rujukan_laboratorium_hematologi")[$i],
        'kesimpulan_laboratorium_hematologi' => $this->request->getVar("kesimpulan_laboratorium_hematologi")[$i],
        'satuan_laboratorium_hematologi' => $this->request->getVar("satuan_laboratorium_hematologi")[$i],
      ];
    }
    $data += [
      'laboratorium_hematologi' => serialize($laboratorium_hematologi)
    ];

    $laboratorium_kualitatif = [];
    for ($i = 0; $i < count($this->request->getVar("laboratorium_kualitatif")); $i++) {
      $laboratorium_kualitatif[$i] = [
        'laboratorium_kualitatif' => $this->request->getVar("laboratorium_kualitatif")[$i],
        'hasil_laboratorium_kualitatif' => $this->request->getVar("hasil_laboratorium_kualitatif")[$i],
        'nilai_rujukan_laboratorium_kualitatif' => $this->request->getVar("nilai_rujukan_laboratorium_kualitatif")[$i],
        'kesimpulan_laboratorium_kualitatif' => $this->request->getVar("kesimpulan_laboratorium_kualitatif")[$i],
      ];
    }
    $data += [
      'laboratorium_kualitatif' => serialize($laboratorium_kualitatif)
    ];

    if ($this->uklwLaboratoriumModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to("/uklw/pasien");
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to("/uklw/pasien");
    }
  }
  public function catatanPemeriksaan()
  {
    $subjective = $this->request->getVar("subjective");
    $objective = $this->request->getVar("id_uklw_pemkes");
    $assesment_diagnosa_medis = $this->request->getVar("assesment_diagnosa_medis");
    $assesment_kesimpulan = $this->request->getVar("assesment_kesimpulan");
    $planning_tindakan = $this->request->getVar("planning_tindakan");
    // $planning_obat = $this->request->getVar("planning_obat");
    $planning_rujuk = $this->request->getVar("planning_rujuk");
    $planning_tempat_rujuk = $this->request->getVar("planning_tempat_rujuk");
    $id_uklw_catatan_pemeriksaan = $this->request->getVar("id_uklw_catatan_pemeriksaan");

    $by = $this->userModel->where('username', session()->get('username'))->first();
    $data = [];

    if ($id_uklw_catatan_pemeriksaan != "") {
      $data += [
        'id_uklw_catatan_pemeriksaan' => $id_uklw_catatan_pemeriksaan
      ];
    } else {
      $data += [
        'created_by' => $by['id_user'],
      ];
    }
    $assesment = [
      'assesment_diagnosa_medis' => $assesment_diagnosa_medis,
      'assesment_kesimpulan' => $assesment_kesimpulan,
    ];

    $planning_obat = [];
    for ($i = 0; $i < count($this->request->getVar("planning_obat")); $i++) {
      $planning_obat[$i] = [
        'planning_obat' => $this->request->getVar("planning_obat")[$i],
        'planning_obat_jumlah' => $this->request->getVar("planning_obat_jumlah")[$i],
        'planning_obat_sehari' => $this->request->getVar("planning_obat_sehari")[$i],
        'planning_obat_waktu' => $this->request->getVar("planning_obat_waktu")[$i],
        'planning_obat_keterangan' => $this->request->getVar("planning_obat_keterangan")[$i],
      ];
    }

    $planning = [
      'planning_tindakan' => $planning_tindakan,
      'planning_obat' => $planning_obat,
      'planning_rujuk' => $planning_rujuk,
      'planning_tempat_rujuk' => $planning_tempat_rujuk,
    ];

    $data += [
      'subjective' => $subjective,
      'id_uklw_pemkes' => $objective,
      'assesment' => serialize($assesment),
      'planning' => serialize($planning),
      'id_data_pegawai_dokter' => $this->request->getVar("dokter"),
      'updated_by' => $by['id_user']
    ];

    if ($this->uklwCatatanPemeriksaanModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      return redirect()->to("/uklw/pasien");
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to("/uklw/pasien");
    }
  }

  public function deleteData($segment, $id)
  {
    if ($this->request->isAJAX()) {
      if ($segment == 'uklw_pasien')
        if ($this->uklwPasienModel->delete($id)) {
          session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        } else {
          session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
        }
      elseif ($segment == 'uklw_pemkes') {
        $cekDataMaster = $this->uklwPemkesModel
          ->join('uklw_catatan_pemeriksaan', 'id_uklw_pemkes')
          ->where('uklw_catatan_pemeriksaan.id_' . $segment, $id)->first();
        if ($cekDataMaster)
          $this->uklwCatatanPemeriksaanModel->delete($cekDataMaster['id_uklw_catatan_pemeriksaan']);

        if ($this->uklwPemkesModel->delete($id)) {
          session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        } else {
          session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
        }
      } elseif ($segment == 'uklw_skrining_tbc')
        if ($this->uklwSkriningTBCModel->delete($id)) {
          session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        } else {
          session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
        }
      elseif ($segment == 'uklw_skrining_hiv')
        if ($this->uklwSkriningHIVModel->delete($id)) {
          session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        } else {
          session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
        }
      elseif ($segment == 'uklw_laboratorium')
        if ($this->uklwLaboratoriumModel->delete($id)) {
          session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        } else {
          session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
        }
      elseif ($segment == 'uklw_pasien') {
        // Pemkes
        $cekDataMaster = $this->uklwPemkesModel
          ->join('uklw_pemkes', 'id_uklw_pasien')
          ->where('uklw_pemkes.id_' . $segment, $id)->first();
        if ($cekDataMaster) {
          $cekDataMaster2 = $this->uklwPemkesModel
            ->join('uklw_catatan_pemeriksaan', 'id_uklw_pemkes')
            ->where('uklw_catatan_pemeriksaan.id_' . $segment, $cekDataMaster['id_uklw_pemkes'])->first();
          if ($cekDataMaster2)
            $this->uklwCatatanPemeriksaanModel->delete($cekDataMaster2['id_uklw_catatan_pemeriksaan']);

          $this->uklwPemkesModel->delete($cekDataMaster['id_uklw_pemkes']);
        }
        // Skrining TBC
        $cekDataMaster = $this->uklwSkriningTBCModel
          ->join('uklw_skrining_tbc', 'id_uklw_pasien')
          ->where('uklw_skrining_tbc.id_' . $segment, $id)->first();
        if ($cekDataMaster)
          $this->uklwSkriningTBCModel->delete($cekDataMaster['id_uklw_skrining_tbc']);

        // Pemkes
        $cekDataMaster = $this->uklwSkriningHIVModel
          ->join('uklw_pemkes', 'id_uklw_pasien')
          ->where('uklw_pemkes.id_' . $segment, $id)->first();
        if ($cekDataMaster)
          $this->uklwSkriningHIVModel->delete($cekDataMaster['id_uklw_pemkes']);

        if ($this->uklwPasienModel->delete($id)) {
          session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        } else {
          session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
        }
      }
    } else {
      exit('Maaf tidak bisa diproses!');
    }
  }
  public function tambahPemkesPenjamahMakanan()
  {

    $data = [];
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $data = [
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
    ];

    $dataUser = [
      'nama' => $this->request->getVar("nama"),
      'tgl_lahir' => $this->request->getVar("tgl_lahir"),
      'jk' => $this->request->getVar("jk"),
      'pekerjaan' => $this->request->getVar("pekerjaan"),
      'pendidikan' => $this->request->getVar("pendidikan"),
      'alamat' => $this->request->getVar("alamat")
    ];
    $dataUser = $dataUser + $data;

    if ($this->uklwPasienModel->save($dataUser)) {
      $id_uklw_pasien = $this->uklwPasienModel->getInsertID();
      $data += [
        'status_pemeriksaan' => $this->request->getVar("status_pemeriksaan"),
        'id_uklw_pasien' => $id_uklw_pasien,
        'kota' => $this->request->getVar("kota")
      ];

      if ($this->request->getVar("status_pemeriksaan") == "Penjamah Makanan") {
        $wawancara = [];
        for ($i = 0; $i < count($this->request->getVar("wawancara")); $i++) {
          $wawancara[$i] = [
            'wawancara' => $this->request->getVar("wawancara")[$i],
            'hasil_wawancara' => $this->request->getVar("hasil_wawancara" . $i),
          ];
        }
        $data += [
          'wawancara' => serialize($wawancara)
        ];

        $hygiene = [];
        for ($i = 0; $i < count($this->request->getVar("hygiene")); $i++) {
          $hygiene[$i] = [
            'hygiene' => $this->request->getVar("hygiene")[$i],
            'hasil_hygiene' => $this->request->getVar("hasil_hygiene" . $i),
            'ket_hygiene' => $this->request->getVar("ket_hygiene")[$i],
          ];
        }
        $data += [
          'hygiene' => serialize($hygiene)
        ];

        $pemeriksaan_fisik_penjamah = [];
        for ($i = 0; $i < count($this->request->getVar("pemeriksaan_fisik_penjamah")); $i++) {
          $pemeriksaan_fisik_penjamah[$i] = [
            'pemeriksaan_fisik_penjamah' => $this->request->getVar("pemeriksaan_fisik_penjamah")[$i],
            'ket_pemeriksaan_fisik_penjamah' => $this->request->getVar("ket_pemeriksaan_fisik_penjamah")[$i],
          ];
        }
        $data += [
          'pemeriksaan_fisik_penjamah' => serialize($pemeriksaan_fisik_penjamah)
        ];
      }

      $pemeriksaan_fisik = [];
      for ($i = 0; $i < count($this->request->getVar("pemeriksaan_fisik")); $i++) {
        $pemeriksaan_fisik[$i] = [
          'pemeriksaan_fisik' => $this->request->getVar("pemeriksaan_fisik")[$i],
          'hasil_pemeriksaan_fisik' => $this->request->getVar("hasil_pemeriksaan_fisik")[$i],
          'satuan_pemeriksaan_fisik' => $this->request->getVar("satuan_pemeriksaan_fisik")[$i],
        ];
      }

      $data += [
        'pemeriksaan_fisik' => serialize($pemeriksaan_fisik)
      ];

      $riwayat_penyakit = [];
      for ($i = 0; $i < count($this->request->getVar("riwayat_penyakit")); $i++) {
        $riwayat_penyakit[$i] = [
          'riwayat_penyakit' => $this->request->getVar("riwayat_penyakit")[$i],
          'hasil_riwayat_penyakit' => $this->request->getVar("hasil_riwayat_penyakit_value")[$i],
        ];
      }
      $data += [
        'riwayat_penyakit' => serialize($riwayat_penyakit)
      ];

      if ($this->uklwPemkesModel->save($data)) {
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        return redirect()->to("/uklw/penjamah_makanan");
      }
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to("/uklw/tambah_penjamah_makanan");
    }
  }
  public function pengambilan_obat()
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();

    $id_uklw_catatan_pemeriksaan = ($this->request->getVar("id_uklw_catatan_pemeriksaan") == "" ? null :  $this->request->getVar("id_uklw_catatan_pemeriksaan"));
    $id_uklw_pasien = $this->request->getVar("id_uklw_pasien");
    $kriteria = $this->request->getVar("kriteria");
    $obat = [];
    for ($i = 0; $i < count($this->request->getVar("obat")); $i++) {
      $obat[$i] = [
        'obat' => $this->request->getVar("obat")[$i],
        'obat_jumlah' => $this->request->getVar("obat_jumlah")[$i],
        'obat_sehari' => $this->request->getVar("obat_sehari")[$i],
        'obat_waktu' => $this->request->getVar("obat_waktu")[$i],
        'obat_keterangan' => $this->request->getVar("obat_keterangan")[$i],
      ];
    }

    $data = [
      'id_uklw_pasien' => $id_uklw_pasien,
      'id_uklw_catatan_pemeriksaan' => $id_uklw_catatan_pemeriksaan,
      'kriteria' => $kriteria,
      'obat' => serialize($obat),
      'created_by' => $by['id_user'],
      'updated_by' => $by['id_user'],
    ];

    if ($this->uklwPengambilanObatModel->save($data)) {
      if ($id_uklw_catatan_pemeriksaan != null) {
        $dataUpdate = [
          'id_uklw_catatan_pemeriksaan' => $id_uklw_catatan_pemeriksaan,
          'status_pemberian_obat' => 'Sudah Diberikan',
          'updated_by' => $by['id_user'],
        ];
        if ($this->uklwCatatanPemeriksaanModel->save($dataUpdate)) {
          session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
          return redirect()->to("/uklw/farmasi");
        } else {
          session()->setFlashdata('pesan', 'Data berhasil ditambahkan tetapi tidak terupdate pada bagian catatan pemeriksaan.');
          return redirect()->to("/uklw/farmasi");
        }
      } else {
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        return redirect()->to("/uklw/farmasi");
      }
    } else {
      session()->setFlashdata('error', 'Data gagal ditambahkan.');
      return redirect()->to("/uklw/farmasi");
    }
  }
  public function hapus_uklw_pengambilan_obat($id)
  {
    $by = $this->userModel->where('username', session()->get('username'))->first();
    $cekData = $this->uklwPengambilanObatModel->where('id_uklw_pengambilan_obat', $id)->first();
    if ($this->uklwPengambilanObatModel->delete($id)) {
      if ($cekData['id_uklw_catatan_pemeriksaan'] != null) {
        $data = [
          'id_uklw_catatan_pemeriksaan' => $cekData['id_uklw_catatan_pemeriksaan'],
          'status_pemberian_obat' => 'Tidak Diberikan',
          'updated_by' => $by['id_user']
        ];
        $this->uklwCatatanPemeriksaanModel->save($data);
      }
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Ada kesalahan dalam penghapusan data, coba kembali!.');
    }
  }
  public function updateDataObat() 
  {
    $id = $this->request->getVar('id');
    $golongan = $this->request->getVar('golongan');
    $nama = $this->request->getVar('nama');
    $kategori = $this->request->getVar('kategori');
    $satuan = $this->request->getVar('satuan');
    $penyimpanan = $this->request->getVar('penyimpanan');
    
    $by = $this->userModel->where('username', session()->get('username'))->first();
    
    $data = [
      'golongan' => $golongan,
      'nama' => $nama,
      'kategori' => $kategori,
      'satuan' => $satuan,
      'penyimpanan' => $penyimpanan,
    ];
    
    $save = false;
    
    if($id == "") {
      $getData = $this->uklwObatModel->where($data)->first();
      if($getData) {
        session()->setFlashdata('info', 'Data sudah ada.');
      } else {
        $data += [
            'created_by' => $by['id_user'],
            'updated_by' => $by['id_user'],
        ];
        $save = true;
      }
    } else {
        $data += [
            'id_uklw_obat' => $id,
            'updated_by' => $by['id_user'],
        ];
        $save = true;
    }
    
    if($save) {
      if($this->uklwObatModel->save($data)) {
        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
      } else {
        session()->setFlashdata('error', 'Data gagal ditambahkan.');
      }
    }
    return redirect()->to("/uklw/data_obat");
  }
}
