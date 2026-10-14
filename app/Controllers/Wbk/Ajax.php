<?php

namespace App\Controllers\Wbk;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

class Ajax extends BaseController
{
  use ResponseTrait;

  public function rencana_kerja_list($id = null)
  {
    if ($this->request->isAJAX()) {
      $data['currentYear'] = date('Y');
      $data['getRole'] = session()->get('role');

      if ($id == null) {
        // CURRENT YEAR
        $data['getPoin'] = $this->wbkRencanaKerjaModel
          ->select('wbk_rencana_kerja.*, COUNT(wbk_arsip.id_wbk_arsip) AS jumlah_file')
          ->join('wbk_arsip', 'wbk_rencana_kerja.id_wbk_rencana_kerja = wbk_arsip.id_wbk_rencana_kerja', 'left')
          ->where('YEAR(wbk_rencana_kerja.created_at)', $data['currentYear'])
          ->where('LENGTH(poin2) =', 0)
          ->groupBy('wbk_rencana_kerja.id_wbk_rencana_kerja')
          ->findAll();

        $data['getSubPoin'] = $this->wbkRencanaKerjaModel
          ->select('wbk_arsip.*, wbk_rencana_kerja.*, COUNT(wbk_arsip.id_wbk_arsip) AS jumlah_file')
          ->join('wbk_arsip', 'wbk_rencana_kerja.id_wbk_rencana_kerja = wbk_arsip.id_wbk_rencana_kerja', 'left')
          ->where('YEAR(wbk_rencana_kerja.created_at)', $data['currentYear'])
          ->where('LENGTH(poin2) !=', 0)
          ->groupBy('wbk_rencana_kerja.id_wbk_rencana_kerja')
          ->findAll();

        $data['getArsip'] = $this->wbkArsipModel->findAll();

        // IF ADMIN
        if ($data['getRole'] == "Admin") {
          $data['getPokja'] = $this->wbkPokjaModel
            ->where('LENGTH(keterangan) !=', 0)
            ->findAll();
        }
        // IF BUKAN ADMIN
        else {
          $data['by'] = $this->dataPegawaiModel
            ->join('user', 'user.id_user=data_pegawai.id_user')
            ->where('user.username', session()->get('username'))
            ->first();

          $data['akses'] = $this->wbkAggotaModel
            ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
            ->first();

          // IF AKSES BUKAN KOORDINATOR ATAU ANGGOTA1
          if ($data['akses']['keterangan'] == "Pengarah / Penanggung Jawab" || $data['akses']['keterangan'] == "Ketua Tim Zona Integritas" || $data['akses']['keterangan'] == "Sekretaris") {
            $data['getPokja'] = $this->wbkPokjaModel
              ->where('LENGTH(keterangan) !=', 0)
              ->findAll();
          }
          // IF AKSES KOORDINATOR ATAU ANGGOTA ADMIN
          else {
            $data['getPokja'] = $this->wbkPokjaModel
              ->where('wbk_pokja.id_wbk_pokja', $data['akses']['id_wbk_pokja'])
              ->findAll();
          }
        }
      } else {
        // CHOOSEN YEAR
        $data['getPoin'] = $this->wbkRencanaKerjaModel
          ->select('wbk_rencana_kerja.*, COUNT(wbk_arsip.id_wbk_arsip) AS jumlah_file')
          ->join('wbk_arsip', 'wbk_rencana_kerja.id_wbk_rencana_kerja = wbk_arsip.id_wbk_rencana_kerja', 'left')
          ->where('YEAR(wbk_rencana_kerja.created_at)', $id)
          ->where('LENGTH(poin2) =', 0)
          ->groupBy('wbk_rencana_kerja.id_wbk_rencana_kerja')
          ->findAll();

        $data['getSubPoin'] = $this->wbkRencanaKerjaModel
          ->select('wbk_arsip.*, wbk_rencana_kerja.*, COUNT(wbk_arsip.id_wbk_arsip) AS jumlah_file')
          ->join('wbk_arsip', 'wbk_rencana_kerja.id_wbk_rencana_kerja = wbk_arsip.id_wbk_rencana_kerja', 'left')
          ->where('YEAR(wbk_rencana_kerja.created_at)', $id)
          ->where('LENGTH(poin2) !=', 0)
          ->groupBy('wbk_rencana_kerja.id_wbk_rencana_kerja')
          ->findAll();

        $data['getArsip'] = $this->wbkArsipModel->findAll();

        // IF ADMIN
        if ($data['getRole'] == "Admin") {
          $data['getPokja'] = $this->wbkPokjaModel
            ->where('LENGTH(keterangan) !=', 0)
            ->findAll();
        }
        // IF BUKAN ADMIN
        else {
          $data['by'] = $this->dataPegawaiModel
            ->join('user', 'user.id_user=data_pegawai.id_user')
            ->where('user.username', session()->get('username'))
            ->first();

          $data['akses'] = $this->wbkAggotaModel
            ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
            ->first();

          // IF AKSES BUKAN KOORDINATOR ATAU ANGGOTA1
          if ($data['akses']['keterangan'] == "Pengarah / Penanggung Jawab" || $data['akses']['keterangan'] == "Ketua Tim Zona Integritas" || $data['akses']['keterangan'] == "Sekretaris") {
            $data['getPokja'] = $this->wbkPokjaModel
              ->where('LENGTH(keterangan) !=', 0)
              ->findAll();
          }
          // IF AKSES KOORDINATOR ATAU ANGGOTA ADMIN
          else {
            $data['by'] = $this->dataPegawaiModel
              ->join('user', 'user.id_user=data_pegawai.id_user')
              ->where('user.username', session()->get('username'))
              ->first();

            $data['getPokja'] = $this->wbkPokjaModel
              ->where('wbk_pokja.id_wbk_pokja', $data['akses']['id_wbk_pokja'])
              ->findAll();
          }
        }
      }

      $msg = [
        'rencanaKerja' => view("wbk/rencana_kerja_list", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function arsip_list($id = '')
  {
    if ($this->request->isAJAX()) {
      $data['currentYear'] = date('Y');
      $data['getRole'] = session()->get('role');

      $data['kategori'] = $this->wbkKategoriModel
        ->select('wbk_kategori.id_wbk_kategori as id_kategori, wbk_kategori.no, wbk_kategori.keterangan as nama_kategori, wbk_kategori.jenis, wbk_pokja.id_wbk_pokja as id_pokja')
        ->join('wbk_pokja', 'wbk_kategori.id_wbk_pokja = wbk_pokja.id_wbk_pokja')
        ->where('LENGTH(wbk_kategori.poin) =', 0)
        ->findAll();

      $data['subKategori'] = $this->wbkKategoriModel
        ->select('wbk_kategori.id_wbk_kategori as id_kategori, wbk_kategori.no, wbk_kategori.poin, wbk_kategori.keterangan as nama_kategori, wbk_kategori.jenis, wbk_pokja.id_wbk_pokja as id_pokja')
        ->join('wbk_pokja', 'wbk_kategori.id_wbk_pokja = wbk_pokja.id_wbk_pokja')
        ->where('LENGTH(wbk_kategori.poin) !=', 0)
        ->findAll();

      if ($id == '') {
        $data['wbkArsipFile'] = $this->wbkArsipModel
          ->select('wbk_arsip.*, wbk_anggota.nama_pegawai as nama_anggota')
          ->join('wbk_anggota', 'wbk_anggota.id_wbk_anggota = wbk_arsip.id_wbk_anggota')
          ->where('YEAR(wbk_arsip.created_at)', $data['currentYear'])
          ->findAll();

        // IF ADMIN
        if ($data['getRole'] == 'Admin') {
          $data['pokja'] = $this->wbkPokjaModel
            ->where('LENGTH(keterangan) !=', 0)
            ->findAll();
        }
        // IF BUKAN ADMIN
        else {
          // IF AKSES BUKAN KOORDINATOR ATAU ANGGOTA ADMIN
          $data['by'] = $this->dataPegawaiModel
            ->join('user', 'user.id_user=data_pegawai.id_user')
            ->where('user.username', session()->get('username'))
            ->first();

          $data['akses'] = $this->wbkAggotaModel
            ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
            ->first();

          if ($data['akses']['keterangan'] == "Pengarah / Penanggung Jawab" || $data['akses']['keterangan'] == "Ketua Tim Zona Integritas" || $data['akses']['keterangan'] == "Sekretaris") {
            $data['pokja'] = $this->wbkPokjaModel
              ->where('LENGTH(keterangan) !=', 0)
              ->findAll();
          }
          // IF AKSES KOORDINATOR ATAU ANGGOTA ADMIN
          else {
            $data['pokja'] = $this->wbkPokjaModel
              ->where('wbk_pokja.id_wbk_pokja', $data['akses']['id_wbk_pokja'])
              ->findAll();
          }
        }
      } else {
        $data['wbkArsipFile'] = $this->wbkArsipModel
          ->select('wbk_arsip.*, wbk_anggota.nama_pegawai as nama_anggota')
          ->join('wbk_anggota', 'wbk_anggota.id_wbk_anggota = wbk_arsip.id_wbk_anggota')
          ->where('YEAR(wbk_arsip.created_at)', $id)
          ->findAll();

        // IF ADMIN
        if ($data['getRole'] == 'Admin') {
          $data['pokja'] = $this->wbkPokjaModel
            ->where('LENGTH(keterangan) !=', 0)
            ->findAll();
        }
        // IF BUKAN ADMIN
        else {
          // IF AKSES BUKAN KOORDINATOR ATAU ANGGOTA ADMIN
          $data['by'] = $this->dataPegawaiModel
            ->join('user', 'user.id_user=data_pegawai.id_user')
            ->where('user.username', session()->get('username'))
            ->first();

          $data['akses'] = $this->wbkAggotaModel
            ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
            ->first();

          if ($data['akses']['keterangan'] == "Pengarah / Penanggung Jawab" || $data['akses']['keterangan'] == "Ketua Tim Zona Integritas" || $data['akses']['keterangan'] == "Sekretaris") {
            $data['pokja'] = $this->wbkPokjaModel
              ->where('LENGTH(keterangan) !=', 0)
              ->findAll();
          }
          // IF AKSES KOORDINATOR ATAU ANGGOTA ADMIN
          else {
            $data['pokja'] = $this->wbkPokjaModel
              ->where('wbk_pokja.id_wbk_pokja', $data['akses']['id_wbk_pokja'])
              ->findAll();
          }
        }
      }

      $msg = [
        'wbkArsip' => view("wbk/arsip_list", $data)
      ];
      echo json_encode($msg);
    }
  }

  public function getPokja()
  {
    if ($this->request->isAJAX()) {
      $msg = [
        'sukses' => 'Berhasil',
        'data' => $this->wbkPokjaModel->findAll(),
      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses'); //redirect aja ganti
    }
  }

  public function getKategori()
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->select('data_pegawai.id_data_pegawai as id_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $getPokja = $this->wbkAggotaModel->select('id_wbk_pokja')->where('id_data_pegawai', $by)->first();

      $msg = [
        'sukses' => 'Berhasil!!!!!!!!!!!!!11',
        'dataKategori' => $this->wbkKategoriModel
          ->where('id_wbk_pokja', $getPokja)
          ->where('LENGTH(poin) =', 0)
          ->findAll(),
        'dataSubKategori' => $this->wbkKategoriModel
          ->where('id_wbk_pokja', $getPokja)
          ->where('LENGTH(poin) !=', 0)
          ->findAll()
      ];
      echo json_encode($msg);
    } else {
      exit('Maaf tidak dapat diproses'); //redirect aja ganti
    }
  }
}
