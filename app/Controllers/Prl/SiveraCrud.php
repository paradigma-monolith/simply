<?php

namespace App\Controllers\Prl;

use App\Controllers\BaseController;

class SiveraCrud extends BaseController
{
  public function simpan_jentik()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_jentik");
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [
        'id_wilayah_kerja'         => $this->request->getVar("id_wilayah_kerja"),
        'lokasi'                   => $this->request->getVar("lokasi"),
        'bulan_pelaksanaan'        => $this->request->getVar("bulan_pelaksanaan"),
        'tanggal_mulai'            => $this->request->getVar("tanggal_mulai"),
        'tanggal_selesai'          => $this->request->getVar("tanggal_selesai"),
        'kondisi_musim'            => $this->request->getVar("kondisi_musim"),
        'wilayah'                  => $this->request->getVar("wilayah"),
        'jml_rumah_diperiksa'      => $this->request->getVar("jml_rumah_diperiksa"),
        'jml_kontainer_diperiksa'  => $this->request->getVar("jml_kontainer_diperiksa"),
        'jml_rumah_positif'        => $this->request->getVar("jml_rumah_positif"),
        'jml_kontainer_positif'    => $this->request->getVar("jml_kontainer_positif"),
        'jenis_kontainer'          => $this->request->getVar("jenis_kontainer") ? implode(',', $this->request->getVar("jenis_kontainer")) : '',
      ];

      if ($id != null && $id != '') {
        $data['id_jentik'] = $id;
        $data['updated_by'] = $by['id_user'];
      } else {
        $data['created_by'] = $by['id_user'];
        $data['updated_by'] = $by['id_user'];
      }

      if ($this->siveraJentikModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data']);
      }
    }
  }

  public function simpan_pengendalian_jentik()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar('id_jentik');
      $pengendalian = json_encode($this->request->getVar('pengendalian'));
      
      $data = [
        'id_jentik' => $id,
        'pengendalian' => $pengendalian
      ];

      if ($this->siveraJentikModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data pengendalian']);
      }
    }
  }

  public function hapus_jentik($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->siveraJentikModel->delete($id)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menghapus data']);
      }
    }
  }

  public function simpan_lalat()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_lalat");
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $tanggal          = $this->request->getVar("tanggal");
      $id_wilayah_kerja = $this->request->getVar("id_wilayah_kerja");
      $temperatur       = $this->request->getVar("temperatur");
      $kelembapan       = $this->request->getVar("kelembapan");
      $kecepatan_angin  = $this->request->getVar("kecepatan_angin");
      $musim            = $this->request->getVar("musim");
      
      $lokasi_arr = $this->request->getVar("lokasi");
      if (!is_array($lokasi_arr)) {
        $lokasi_arr = [$lokasi_arr];
      }

      $num_lokasi = count($lokasi_arr);
      $lokasi_clean = [];
      $detail_lokasi = [];
      $rata_rata_per_lokasi = [];
      $t_sums = array_fill(1, 10, 0);
      $titik_sums = array_fill(1, 5, 0);

      for ($k = 0; $k < $num_lokasi; $k++) {
        $lok_name = trim((string) ($lokasi_arr[$k] ?? ''));
        if ($lok_name === '') {
          continue;
        }
        $lokasi_clean[] = $lok_name;

        // Ambil data pengamatan t1 - t10 untuk lokasi ke-k
        $t_vals = [];
        for ($i = 1; $i <= 10; $i++) {
          $t_raw = $this->request->getVar("t{$i}");
          $val = is_array($t_raw) ? (float) ($t_raw[$k] ?? 0) : (float) ($t_raw ?? 0);
          $t_sums[$i] += $val;
          $t_vals[] = $val;
        }

        // Ambil data 5 titik tertinggi untuk lokasi ke-k
        $titik_vals = [];
        for ($t = 1; $t <= 5; $t++) {
          $titik_raw = $this->request->getVar("titik_{$t}");
          $val = is_array($titik_raw) ? (float) ($titik_raw[$k] ?? 0) : (float) ($titik_raw ?? 0);
          $titik_sums[$t] += $val;
          $titik_vals[] = $val;
        }

        $sum_top5 = array_sum($titik_vals);
        // Jika form belum mengisi titik, hitung langsung dari 5 tertinggi t_vals
        if ($sum_top5 == 0 && array_sum($t_vals) > 0) {
          $sorted = $t_vals;
          rsort($sorted);
          $titik_vals = array_slice($sorted, 0, 5);
          $sum_top5 = array_sum($titik_vals);
        }
        $avg_k = $sum_top5 / 5;
        $rata_rata_per_lokasi[] = $avg_k;

        $detail_lokasi[] = [
          'lokasi'            => $lok_name,
          't'                 => $t_vals,
          'titik'             => $titik_vals,
          'total_pengamatan'  => array_sum($t_vals),
          'total_top5'        => $sum_top5,
          'rata_rata'         => round($avg_k, 2),
          'status'            => $avg_k < 2 ? 'MS' : 'TMS',
        ];
      }

      $total_item = count($detail_lokasi);
      $lokasi_gabungan = implode(', ', $lokasi_clean);
      $rata_rata_final = $total_item > 0 ? (array_sum($rata_rata_per_lokasi) / $total_item) : 0;

      $data = [
        'tanggal'          => $tanggal,
        'id_wilayah_kerja' => $id_wilayah_kerja,
        'lokasi'           => $lokasi_gabungan,
        'temperatur'       => $temperatur,
        'kelembapan'       => $kelembapan,
        'kecepatan_angin'  => $kecepatan_angin,
        'musim'            => $musim,
        'rata_rata'        => round($rata_rata_final, 2),
        'detail_lokasi'    => json_encode($detail_lokasi),
      ];

      for ($i = 1; $i <= 10; $i++) {
        $data["t{$i}"] = $total_item > 0 ? round($t_sums[$i] / $total_item) : 0;
      }
      for ($t = 1; $t <= 5; $t++) {
        $data["titik_{$t}"] = $total_item > 0 ? round($titik_sums[$t] / $total_item) : 0;
      }

      if ($id != null && $id != '') {
        $data['id_lalat'] = $id;
        $data['updated_by'] = $by['id_user'] ?? null;
      } else {
        $data['created_by'] = $by['id_user'] ?? null;
        $data['updated_by'] = $by['id_user'] ?? null;
      }

      if ($this->siveraLalatModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data']);
      }
    }
  }

  public function simpan_pengendalian_lalat()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar('id_lalat');
      $pengendalian = json_encode($this->request->getVar('pengendalian'));
      
      $data = [
        'id_lalat' => $id,
        'pengendalian' => $pengendalian
      ];

      if ($this->siveraLalatModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data pengendalian']);
      }
    }
  }

  public function hapus_lalat($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->siveraLalatModel->delete($id)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menghapus data']);
      }
    }
  }

  public function simpan_kecoa()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_kecoa");
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [
        'tanggal'              => $this->request->getVar("tanggal"),
        'id_wilayah_kerja'     => $this->request->getVar("id_wilayah_kerja"),
        'lokasi'               => $this->request->getVar("lokasi"),
        'nama_ruangan'         => $this->request->getVar("nama_ruangan"),
        'perangkap_terpasang'  => $this->request->getVar("perangkap_terpasang"),
        'jml_kecoa_tertangkap' => $this->request->getVar("jml_kecoa_tertangkap"),
        'spesies_tertangkap'   => json_encode($this->request->getVar("spesies")), // Format: { "Periplatena americana": 1, ... }
      ];

      $terpasang = floatval($data['perangkap_terpasang']);
      $data['rata_rata'] = $terpasang > 0 ? ($data['jml_kecoa_tertangkap'] / $terpasang) : 0;

      if ($id != null && $id != '') {
        $data['id_kecoa'] = $id;
        $data['updated_by'] = $by['id_user'];
      } else {
        $data['created_by'] = $by['id_user'];
        $data['updated_by'] = $by['id_user'];
      }

      if ($this->siveraKecoaModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data']);
      }
    }
  }

  public function simpan_pengendalian_kecoa()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar('id_kecoa');
      $pengendalian = json_encode($this->request->getVar('pengendalian'));
      
      $data = [
        'id_kecoa' => $id,
        'pengendalian' => $pengendalian
      ];

      if ($this->siveraKecoaModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data pengendalian']);
      }
    }
  }

  public function hapus_kecoa($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->siveraKecoaModel->delete($id)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menghapus data']);
      }
    }
  }

  public function simpan_tikus()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_tikus");
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      // Process multiple species & quantities
      $namaList = (array) ($this->request->getVar("spesies_nama") ?? []);
      $customList = (array) ($this->request->getVar("spesies_custom") ?? []);
      $jmlList = (array) ($this->request->getVar("spesies_jumlah") ?? []);

      $spesiesMap = [];
      $sumTertangkap = 0;

      for ($i = 0; $i < count($namaList); $i++) {
        $spName = trim((string) ($namaList[$i] ?? ''));
        if ($spName === 'Lain-lain') {
          $custom = trim((string) ($customList[$i] ?? ''));
          if ($custom !== '') {
            $spName = $custom;
          }
        }
        $spJml = (int) ($jmlList[$i] ?? 0);
        if ($spName !== '' && $spJml > 0) {
          if (!isset($spesiesMap[$spName])) {
            $spesiesMap[$spName] = 0;
          }
          $spesiesMap[$spName] += $spJml;
          $sumTertangkap += $spJml;
        }
      }

      if (!empty($spesiesMap)) {
        $spesiesSaved = json_encode($spesiesMap);
      } else {
        $singleSpesies = $this->request->getVar("spesies") ?? $this->request->getVar("spesies_tikus");
        $spesies_lainnya = $this->request->getVar("spesies_lainnya");
        if ($singleSpesies === 'Lain-lain' && !empty($spesies_lainnya)) {
          $singleSpesies = trim($spesies_lainnya);
        }
        $spesiesSaved = !empty($singleSpesies) ? $singleSpesies : null;
      }

      $inputTertangkap = (int) $this->request->getVar("jml_tikus_tertangkap");
      $finalTertangkap = $sumTertangkap > 0 ? $sumTertangkap : $inputTertangkap;

      $tglMulai = $this->request->getVar("tanggal_mulai") ?: $this->request->getVar("tanggal");
      $tglSelesai = $this->request->getVar("tanggal_selesai") ?: $tglMulai;

      $data = [
        'tanggal'                => $tglMulai,
        'tanggal_mulai'          => $tglMulai,
        'tanggal_selesai'        => $tglSelesai,
        'id_wilayah_kerja'       => $this->request->getVar("id_wilayah_kerja"),
        'lokasi'                 => $this->request->getVar("lokasi"),
        'lokasi_pemasangan'      => $this->request->getVar("lokasi_pemasangan"),
        'jenis_umpan'            => $this->request->getVar("jenis_umpan"),
        'spesies'                => $spesiesSaved,
        'jml_perangkap_dipasang' => $this->request->getVar("jml_perangkap_dipasang"),
        'jml_perangkap_hilang'   => $this->request->getVar("jml_perangkap_hilang"),
        'jml_tikus_tertangkap'   => $finalTertangkap,
      ];

      // Success Trap
      $dipasang = floatval($data['jml_perangkap_dipasang']);
      $data['success_trap'] = $dipasang > 0 ? ($data['jml_tikus_tertangkap'] / $dipasang) * 100 : 0;

      if ($id != null && $id != '') {
        $data['id_tikus'] = $id;
        $data['updated_by'] = $by['id_user'];
      } else {
        $data['created_by'] = $by['id_user'];
        $data['updated_by'] = $by['id_user'];
      }

      if ($this->siveraTikusModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data']);
      }
    }
  }

  public function hapus_tikus($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->siveraTikusModel->delete($id)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menghapus data']);
      }
    }
  }

  public function simpan_pinjal()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_pinjal");
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $jml_tikus = (int) ($this->request->getVar("jml_tikus_tertangkap") ?? 0);
      $pinjal_umum = (int) ($this->request->getVar("pinjal_umum") ?? 0);
      $pinjal_khusus = (int) ($this->request->getVar("pinjal_khusus") ?? 0);

      // Indeks Pinjal = pinjal / jumlah tikus tertangkap
      $indeks_pinjal_umum = $jml_tikus > 0 ? round($pinjal_umum / $jml_tikus, 2) : 0;
      $indeks_pinjal_khusus = $jml_tikus > 0 ? round($pinjal_khusus / $jml_tikus, 2) : 0;

      $data = [
        'tanggal'                 => $this->request->getVar("tanggal"),
        'id_wilayah_kerja'        => $this->request->getVar("id_wilayah_kerja"),
        'lokasi'                  => $this->request->getVar("lokasi"),
        'jml_tikus_tertangkap'    => $jml_tikus,
        'pinjal_umum'             => $pinjal_umum,
        'pinjal_khusus'           => $pinjal_khusus,
        'indeks_pinjal_umum'      => $indeks_pinjal_umum,
        'indeks_pinjal_khusus'    => $indeks_pinjal_khusus,
      ];

      // Optional legacy fields if provided
      if ($this->request->getVar("hari_ke")) $data['hari_ke'] = $this->request->getVar("hari_ke");
      if ($this->request->getVar("lokasi_tikus_tertangkap")) $data['lokasi_tikus_tertangkap'] = $this->request->getVar("lokasi_tikus_tertangkap");
      if ($this->request->getVar("kode_spesimen")) $data['kode_spesimen'] = $this->request->getVar("kode_spesimen");
      if ($this->request->getVar("jenis_kelamin")) $data['jenis_kelamin'] = $this->request->getVar("jenis_kelamin");
      if ($this->request->getVar("berat_badan")) $data['berat_badan'] = $this->request->getVar("berat_badan");
      if ($this->request->getVar("panjang_badan")) $data['panjang_badan'] = $this->request->getVar("panjang_badan");
      if ($this->request->getVar("ekor")) $data['ekor'] = $this->request->getVar("ekor");
      if ($this->request->getVar("kaki_belakang")) $data['kaki_belakang'] = $this->request->getVar("kaki_belakang");
      if ($this->request->getVar("telinga")) $data['telinga'] = $this->request->getVar("telinga");
      if ($this->request->getVar("testis")) $data['testis'] = $this->request->getVar("testis");
      if ($this->request->getVar("jml_mamae")) $data['jml_mamae'] = $this->request->getVar("jml_mamae");
      if ($this->request->getVar("spesies_tikus")) $data['spesies_tikus'] = $this->request->getVar("spesies_tikus");

      if ($id != null && $id != '') {
        $data['id_pinjal'] = $id;
        $data['updated_by'] = $by['id_user'];
      } else {
        $data['created_by'] = $by['id_user'];
        $data['updated_by'] = $by['id_user'];
      }

      if ($this->siveraPinjalModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data']);
      }
    }
  }

  public function hapus_pinjal($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->siveraPinjalModel->delete($id)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menghapus data']);
      }
    }
  }

  public function simpan_anopheles_jentik()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_anopheles_jentik");
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $jenisHabitat = $this->request->getVar("jenis_habitat");
      if (is_array($jenisHabitat)) {
        $jenisHabitat = implode(',', $jenisHabitat);
      }

      $kondisiAir = $this->request->getVar("kondisi_air");
      if (is_array($kondisiAir)) {
        $kondisiAir = implode(',', $kondisiAir);
      }

      $kondisiPencahayaan = $this->request->getVar("kondisi_pencahayaan");
      if (is_array($kondisiPencahayaan)) {
        $kondisiPencahayaan = implode(',', $kondisiPencahayaan);
      }

      $kondisiVegetasi = $this->request->getVar("kondisi_vegetasi");
      if (is_array($kondisiVegetasi)) {
        $kondisiVegetasi = implode(',', $kondisiVegetasi);
      }

      $disurvei = (int) $this->request->getVar("jml_habitat_disurvei");
      $positif = (int) $this->request->getVar("jml_habitat_positif");
      $indeksHabitat = $disurvei > 0 ? round(($positif / $disurvei) * 100, 2) : 0.00;
      $status = ($indeksHabitat < 1.0) ? 'MS' : 'TMS';

      $data = [
        'tanggal'               => $this->request->getVar("tanggal"),
        'id_wilayah_kerja'      => $this->request->getVar("id_wilayah_kerja"),
        'lokasi'                => $this->request->getVar("lokasi"),
        'jenis_habitat'         => $jenisHabitat,
        'jenis_habitat_lainnya' => $this->request->getVar("jenis_habitat_lainnya"),
        'kondisi_pencahayaan'   => $kondisiPencahayaan,
        'kondisi_vegetasi'      => $kondisiVegetasi,
        'kondisi_air'           => $kondisiAir,
        'keterangan'            => $this->request->getVar("keterangan"),
        'jml_habitat_disurvei'  => $disurvei,
        'jml_habitat_positif'   => $positif,
        'indeks_habitat'        => $indeksHabitat,
        'status'                => $status,
      ];

      if ($id != null && $id != '') {
        $data['id_anopheles_jentik'] = $id;
        $data['updated_by'] = $by['id_user'] ?? session()->get('username');
      } else {
        $data['created_by'] = $by['id_user'] ?? session()->get('username');
        $data['updated_by'] = $by['id_user'] ?? session()->get('username');
      }

      if ($this->siveraAnophelesJentikModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data Jentik Anopheles']);
      }
    }
  }

  public function hapus_anopheles_jentik($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->siveraAnophelesJentikModel->delete($id)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menghapus data Jentik Anopheles']);
      }
    }
  }

  public function simpan_anopheles_nyamuk()
  {
    if ($this->request->isAJAX()) {
      $id = $this->request->getVar("id_anopheles_nyamuk");
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $penangkap = (int) $this->request->getVar("jml_penangkap");
      $lama = (float) $this->request->getVar("lama_penangkapan");
      if ($lama <= 0) {
        $lama = 12.0; // default 12 jam
      }

      $tangkapanDalam = $this->request->getVar("tangkapan_dalam") ?? [];
      $tangkapanLuar = $this->request->getVar("tangkapan_luar") ?? [];
      $ketDalam = $this->request->getVar("ket_dalam") ?? '';
      $ketLuar = $this->request->getVar("ket_luar") ?? '';

      $hours = ['18_19','19_20','20_21','21_22','22_23','23_24','24_01','01_02','02_03','03_04','04_05','05_06'];
      
      $totalDalam = 0;
      $cleanDalam = [];
      foreach ($hours as $h) {
        $val = isset($tangkapanDalam[$h]) ? (int)$tangkapanDalam[$h] : 0;
        $cleanDalam[$h] = $val;
        $totalDalam += $val;
      }

      $totalLuar = 0;
      $cleanLuar = [];
      foreach ($hours as $h) {
        $val = isset($tangkapanLuar[$h]) ? (int)$tangkapanLuar[$h] : 0;
        $cleanLuar[$h] = $val;
        $totalLuar += $val;
      }

      $totalTertangkap = $totalDalam + $totalLuar;
      $adaAnopheles = $totalTertangkap > 0 ? 'Ya' : 'Tidak';

      $manHours = ($penangkap > 0 && $lama > 0) ? ($penangkap * $lama) : 0;
      $mbr = $manHours > 0 ? round($totalTertangkap / $manHours, 4) : 0.0000;
      $mhd = $manHours > 0 ? round($totalTertangkap / $manHours, 4) : 0.0000;

      $mhdMbrDalam = $manHours > 0 ? round($totalDalam / $manHours, 4) : 0.0000;
      $mhdMbrLuar = $manHours > 0 ? round($totalLuar / $manHours, 4) : 0.0000;

      $dataTangkapan = [
        'dalam' => [
          'jam' => $cleanDalam,
          'total' => $totalDalam,
          'mhd_mbr' => $mhdMbrDalam,
          'ket' => $ketDalam
        ],
        'luar' => [
          'jam' => $cleanLuar,
          'total' => $totalLuar,
          'mhd_mbr' => $mhdMbrLuar,
          'ket' => $ketLuar
        ],
      ];

      $interpretasi = $this->request->getVar("interpretasi");
      if (empty($interpretasi)) {
        if ($adaAnopheles == 'Ya') {
          $interpretasi = 'Ditemukan Anopheles sehingga perlu dilakukan identifikasi spesies, analisis faktor risiko lingkungan, dan tindak lanjut pengendalian vektor.';
        } else {
          $interpretasi = 'Tidak ditemukan Anopheles sehingga belum terdapat indikasi faktor risiko penularan malaria pada saat survei.';
        }
      }

      $data = [
        'tanggal'          => $this->request->getVar("tanggal"),
        'id_wilayah_kerja' => $this->request->getVar("id_wilayah_kerja"),
        'lokasi'           => $this->request->getVar("lokasi"),
        'jml_penangkap'    => $penangkap,
        'lama_penangkapan' => $lama,
        'kondisi_cuaca'    => $this->request->getVar("kondisi_cuaca"),
        'suhu'             => $this->request->getVar("suhu") !== '' && $this->request->getVar("suhu") !== null ? (float)$this->request->getVar("suhu") : null,
        'kelembaban'       => $this->request->getVar("kelembaban") !== '' && $this->request->getVar("kelembaban") !== null ? (float)$this->request->getVar("kelembaban") : null,
        'kecepatan_angin'  => $this->request->getVar("kecepatan_angin") !== '' && $this->request->getVar("kecepatan_angin") !== null ? (float)$this->request->getVar("kecepatan_angin") : null,
        'spesies'          => $this->request->getVar("spesies") ?: 'Anopheles sp',
        'data_tangkapan'   => json_encode($dataTangkapan),
        'total_dalam'      => $totalDalam,
        'total_luar'       => $totalLuar,
        'total_tertangkap' => $totalTertangkap,
        'ada_anopheles'    => $adaAnopheles,
        'mbr'              => $mbr,
        'mhd'              => $mhd,
        'interpretasi'     => $interpretasi,
        'keterangan'       => $this->request->getVar("keterangan"),
      ];

      if ($id != null && $id != '') {
        $data['id_anopheles_nyamuk'] = $id;
        $data['updated_by'] = $by['id_user'] ?? session()->get('username');
      } else {
        $data['created_by'] = $by['id_user'] ?? session()->get('username');
        $data['updated_by'] = $by['id_user'] ?? session()->get('username');
      }

      if ($this->siveraAnophelesNyamukModel->save($data)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menyimpan data Nyamuk Anopheles']);
      }
    }
  }

  public function hapus_anopheles_nyamuk($id)
  {
    if ($this->request->isAJAX()) {
      if ($this->siveraAnophelesNyamukModel->delete($id)) {
        return json_encode(['status' => 'success']);
      } else {
        return json_encode(['status' => 'error', 'msg' => 'Gagal menghapus data Nyamuk Anopheles']);
      }
    }
  }
}
