<?php

namespace App\Controllers\Wbk;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function rencana_kerja()
  {
    $data['title'] = "WBK | Rencana Kerja";
    $data['activeMenu'] = "rencana_kerja";

    $data['currentYear'] = date('Y');

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    $data['getRole'] = session()->get('role');

    if ($data['getRole'] == "Admin") {
      $data['tahunData'] = $this->wbkRencanaKerjaModel
        ->select('YEAR(created_at) as tahun')
        ->groupBy('YEAR(created_at)')
        ->where('YEAR(created_at) !=', $data['currentYear'])
        ->findAll();
    } else {
      if($data['by']) {
          $data['akses'] = $this->wbkAggotaModel
            ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
            ->first();
        if($data['akses']) {
              if ($data['akses']['keterangan'] == 'Koordinator' || $data['akses']['keterangan'] == 'Anggota1') {
                $data['tahunData'] = $this->wbkRencanaKerjaModel
                  ->select('YEAR(created_at) as tahun')
                  ->groupBy('YEAR(created_at)')
                  ->where('YEAR(created_at) !=', $data['currentYear'])
                  ->where('id_wbk_pokja', $data['akses']['id_wbk_pokja'])
                  ->findAll();
              } else {
                $data['tahunData'] = $this->wbkRencanaKerjaModel
                  ->select('YEAR(created_at) as tahun')
                  ->groupBy('YEAR(created_at)')
                  ->where('YEAR(created_at) !=', $data['currentYear'])
                  ->findAll();
              }
        } else {
           return redirect()->to('forbidden');
        }
       } else {
           return redirect()->to('forbidden');
       }
    }
    // dd($data);

    return view('wbk/rencana_kerja', $data);
  }

  public function tambah_rencana_kerja($id = null)
  {
    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();
    $data['anggota'] = $this->wbkAggotaModel->findAll();

    $data['getAnggota'] = $this->wbkAggotaModel
      ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
      ->first();
    $data['pokja'] = $this->wbkPokjaModel->findAll();

    // JIKA ADA ID RENCANA KERJA
    if ($id != null) {
      $data['title'] = "WBK | Tambah Unit Rencana Kerja";
      $data['activeMenu'] = "rencana_kerja";

      $data['getRencanaKerja'] = $this->wbkRencanaKerjaModel
        ->where('id_wbk_rencana_kerja', $id)
        ->first();

      $data['getUnit'] = $this->wbkRencanaKerjaModel
        ->where('id_wbk_pokja', $data['getAnggota']['id_wbk_pokja'])
        ->where('poin1', $data['getRencanaKerja']['poin1'])
        ->where('LENGTH(poin2) !=', 0)
        ->findAll();

      if (count($data['getUnit']) > 0) {
        usort($data['getUnit'], function ($a, $b) {
          return strcmp($a['poin1'], $b['poin1']) ?: strcmp($a['poin2'], $b['poin2']);
        });

        foreach ($data['getUnit'] as $row) {
          $data['lastPoinUnit'][$row['poin1']] = $row['poin2'];
        }

        function nextPoinUnit($lastPoinUnit)
        {
          // If it's not a number, add 1 to the ASCII code to get the next point
          return chr(ord($lastPoinUnit) + 1);
        }

        $data['nextPoinUnit'] = array();

        foreach ($data['lastPoinUnit'] as $poin1 => $lastPoinUnit) {
          $nextPoinUnit = nextPoinUnit($lastPoinUnit);
          $data['nextPoinUnit'][$poin1] = $nextPoinUnit;
        }
      }
    } else {
      $data['title'] = "WBK | Tambah Rencana Kerja";
      $data['activeMenu'] = "rencana_kerja";

      $data['getRencanaKerja'] = $this->wbkRencanaKerjaModel
        ->where('id_wbk_pokja', $data['getAnggota']['id_wbk_pokja'])
        ->where('LENGTH(poin2) =', 0)
        ->findAll();

      if (count($data['getRencanaKerja']) > 0) {
        usort($data['getRencanaKerja'], function ($a, $b) {
          $id_wbk_pokja_cmp = strcmp($a['id_wbk_pokja'], $b['id_wbk_pokja']);

          if ($id_wbk_pokja_cmp != 0) {
            return $id_wbk_pokja_cmp;
          }

          // Use integer comparison for 'poin1' if 'id_wbk_pokja' is the same
          return (int)$a['poin1'] - (int)$b['poin1'];
        });

        foreach ($data['getRencanaKerja'] as $row) {
          $data['lastPoinRencanaKerja'][$row['id_wbk_pokja']] = (int)$row['poin1'];
        }

        function nextPoinRencanaKerja($lastPoinRencanaKerja)
        {
          return $lastPoinRencanaKerja + 1; // If it's a number, increment it
        }

        // Display the next points for each id_wbk_pokja
        $data['nextPoinRencanaKerja'] = array();

        foreach ($data['lastPoinRencanaKerja'] as $id_wbk_pokja => $lastPoinRencanaKerja) {
          $nextPoinRencanaKerja = nextPoinRencanaKerja($lastPoinRencanaKerja);
          $data['nextPoinRencanaKerja'][$id_wbk_pokja] = $nextPoinRencanaKerja;
        }
      }
    }

    // dd($data);

    return view('crud/wbk_tambah_rencana_kerja', $data);
  }

  public function daftar_dokumen()
  {
    $data['title'] = "WBK | Daftar Dokumen";
    $data['activeMenu'] = "daftar_dokumen";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    $data['currentYear'] = date('Y');

    $data['getRole'] = session()->get('role');

    if ($data['getRole'] == 'Admin') {
      $data['tahunData'] = $this->wbkRencanaKerjaModel
        ->select('YEAR(created_at) as tahun')
        ->groupBy('YEAR(created_at)')
        ->where('YEAR(created_at) !=', $data['currentYear'])
        ->findAll();
    } else {
      $data['akses'] = $this->wbkAggotaModel
        ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
        ->first();

      if ($data['akses']['keterangan'] == "Pengarah / Penanggung Jawab" || $data['akses']['keterangan'] == "Ketua Tim Zona Integritas" || $data['akses']['keterangan'] == "Sekretaris") {
        $data['tahunData'] = $this->wbkArsipModel
          ->select('YEAR(created_at) as tahun')
          ->groupBy('YEAR(created_at)')
          ->where('YEAR(created_at) !=', $data['currentYear'])
          ->findAll();
      } else {
        $data['tahunData'] = $this->wbkArsipModel
          ->select('YEAR(wbk_arsip.created_at) as tahun')
          ->join('wbk_anggota', 'wbk_anggota.id_wbk_anggota = wbk_arsip.id_wbk_anggota')
          ->join('wbk_pokja', 'wbk_pokja.id_wbk_pokja = wbk_anggota.id_wbk_pokja')
          ->groupBy('YEAR(wbk_arsip.created_at)')
          ->where('YEAR(wbk_arsip.created_at) !=', $data['currentYear'])
          ->where('wbk_pokja.id_wbk_pokja', $data['akses']['id_wbk_pokja'])
          ->findAll();
      }
    }

    // dd($data);

    return view('wbk/arsip', $data);
  }

  public function upload_file($id = '')
  {
    $data['title'] = "WBK | Upload File";
    $data['activeMenu'] = "daftar_dokumen";

    $data['by'] = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    $data['anggota'] = $this->wbkAggotaModel->findAll();
    $data['pokja'] = $this->wbkPokjaModel->findAll();
    $data['getAnggota'] = $this->wbkAggotaModel
      ->where('id_data_pegawai', $data['by']['id_data_pegawai'])
      ->first();

    $data['kategori'] = $this->wbkKategoriModel
      ->where('id_wbk_pokja', $data['getAnggota']['id_wbk_pokja'])
      ->where('LENGTH(wbk_kategori.poin) =', 0)
      ->findAll();

    $data['getRencanaKerja'] = $this->wbkRencanaKerjaModel
      ->where('id_wbk_rencana_kerja', $id)
      ->first();

    $data['getIdFile'] = $this->wbkArsipModel->where('id_wbk_arsip', $id)->first();

    if ($data['getIdFile']) {
      $data['getArsip'] = $this->wbkArsipModel
        ->select('wbk_arsip.*, wbk_anggota.*, wbk_pokja.*, data_pegawai.*, wbk_rencana_kerja.*')
        ->join('wbk_anggota', 'wbk_anggota.id_wbk_anggota = wbk_arsip.id_wbk_anggota')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = wbk_anggota.id_data_pegawai')
        ->join('wbk_pokja', 'wbk_pokja.id_wbk_pokja = wbk_anggota.id_wbk_pokja')
        ->join('wbk_rencana_kerja', 'wbk_rencana_kerja.id_wbk_rencana_kerja = wbk_arsip.id_wbk_rencana_kerja')
        ->where('id_wbk_arsip', $id)
        ->first();
    } else {
      if ($data['getRencanaKerja']) {
        // Pisahkan string dengan delimiter '|'
        $data['selectedMonths'] = explode('|', $data['getRencanaKerja']['target_waktu']);
      } else {
        // Handle jika data tidak ditemukan
        $data['selectedMonths'] = [];
      }

      $data['months'] = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
      ];
    }
    // dd($data);

    return view('crud/wbk_upload_file', $data);
  }
}
