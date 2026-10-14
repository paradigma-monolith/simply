<?php

namespace App\Controllers\Wbk;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function simpan_rencana_kerja($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->select('user.id_user, data_pegawai.id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();
      $getAnggota = $this->wbkAggotaModel->where('id_data_pegawai', $by['id_data_pegawai'])->first();

      if ($id != null) {
        $selectedTargetWaktu = $this->request->getVar("target_waktu");
        $targetWaktu = implode('|', $selectedTargetWaktu);

        if ($selectedTargetWaktu && in_array("1|2|3|4|5|6|7|8|9|10|11|12", $selectedTargetWaktu)) {
          // Jika dipilih "Pilih Semua Bulan", set jumlah target menjadi 12
          $jmlTarget = 12;
        } else {
          // Jika dipilih bulan-bulan lainnya, hitung jumlahnya
          $jmlTarget = count($selectedTargetWaktu);
        }

        $switchStatus = $this->request->getVar("prioritas") === 'on' ? 'true' : 'false';
        $data = [
          'id_wbk_pokja'        => $this->request->getVar("pokja"),
          'id_wbk_anggota'      => $getAnggota['id_wbk_anggota'],
          'poin1'               => $this->request->getVar("poin1"),
          'poin2'               => $this->request->getVar("poin2"),
          'kegiatan'            => $this->request->getVar("kegiatan"),
          'indikator'           => $this->request->getVar("indikator"),
          'bukti_fisik'         => $this->request->getVar("bukti_fisik"),
          'target_jml_kegiatan' => $jmlTarget,
          'target_waktu'        => $targetWaktu,
          'prioritas'           => $switchStatus,
          'created_by'          => $by['id_user'],
          'updated_by'          => $by['id_user']
        ];
      } else {
        $data = [
          'id_wbk_pokja'        => $this->request->getVar("pokja"),
          'id_wbk_anggota'      => $getAnggota['id_wbk_anggota'],
          'poin1'               => $this->request->getVar("poin1"),
          'kegiatan'            => $this->request->getVar("kegiatan"),
          'prioritas'           => 'false',
          'created_by'          => $by['id_user'],
          'updated_by'          => $by['id_user']
        ];
      }

      if ($this->wbkRencanaKerjaModel->save($data)) {
        $newId = $this->wbkRencanaKerjaModel->getInsertID();
        $getPokja = $this->wbkPokjaModel->where('id_wbk_pokja', $this->request->getVar("pokja"))->first();
        if ($id != null)
          $timeline = [
            'icon'        => 'fas fa-save bg-primary',
            'id_table'    => $newId,
            'table'       => 'wbk_rencana_kerja',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'menambahkan unit rencana kerja di WBK STORAGE untuk ' . $getPokja['nama_pokja'],
            'keterangan'  => "",
          ];
        else
          $timeline = [
            'icon'        => 'fas fa-save bg-primary',
            'id_table'    => $newId,
            'table'       => 'wbk_rencana_kerja',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'menambahkan rencana kerja di WBK STORAGE untuk ' . $getPokja['nama_pokja'],
            'keterangan'  => "",
          ];
        $this->linimasaModel->save($timeline);
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

  public function simpan_file($idRencanaKerja = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->select('user.id_user, data_pegawai.id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();
      $getAnggota = $this->wbkAggotaModel->where('id_data_pegawai', $by['id_data_pegawai'])->first();

      $file = $this->request->getFile('unggah_dokumen');
      $acak = rand();
      $fileName = $file->getName();
      $cleanName = preg_replace('/[^\w\d\s.]/', '', $fileName);
      $inputFile = $acak . '_' . $cleanName;
      $file->move('./wbk_arsip/', $inputFile);

      if (!empty($this->request->getVar('id_wbk_rencana_kerja'))) {
        $rencanaKerja = $this->request->getVar('id_wbk_rencana_kerja');
        $targetWaktu = $this->request->getVar('target_waktu');
        $catatan = $this->request->getVar('catatan');
      } else {
        $rencanaKerja = '';
        $targetWaktu = '';
        $catatan = '';
      }

      $data = [
        'id_wbk_rencana_kerja'  => $rencanaKerja,
        'id_wbk_kategori'       => $this->request->getVar('sub_kategori'),
        'id_wbk_anggota'        => $getAnggota['id_wbk_anggota'],
        'nama_file'             => $this->request->getVar('nama_file'),
        'file'                  => $inputFile,
        'target_waktu'          => $targetWaktu,
        'catatan'               => $catatan,
        'created_by'            => $by['id_user'],
        'updated_by'            => $by['id_user']
      ];

      if ($this->wbkArsipModel->save($data)) {
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

  public function simpan_kategori($id = null)
  {
    if ($this->request->isAJAX()) {
      $data = [
        'id_wbk_pokja' => $this->request->getVar("pilih_pokja"),
        'keterangan'   => $this->request->getVar("keterangan"),
      ];

      if ($this->wbkKategoriModel->save($data)) {
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
}
