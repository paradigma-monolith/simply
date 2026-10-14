<?php

namespace App\Controllers\Prl;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  // TPP ==================================================================================>
  public function simpan_tpp($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_prl_tpp' => $id];

      $dataSebelumnya = $this->prlTppModel
        ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_tpp.*')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_tpp.id_wilayah_kerja')
        ->where('id_prl_tpp', $id)
        ->first();

      $data += [
        'id_wilayah_kerja'   => $this->request->getVar("wilayah_kerja_form"),
        'nama_tpp'           => $this->request->getVar("nama_tpp_form"),
        'penanggung_jawab'   => $this->request->getVar("penanggung_jawab_form"),
        'jml_karyawan'       => $this->request->getVar("jml_karyawan_form"),
        'kapasitas_produksi' => $this->request->getVar("kapasitas_produksi_form"),
        'created_by'         => $by['id_user'],
        'updated_by'         => $by['id_user']
      ];

      if ($this->prlTppModel->save($data)) {
        if ($id != null) {
          // Update
          $dataTpp = $this->prlTppModel
            ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_tpp.*')
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_tpp.id_wilayah_kerja')
            ->where('id_prl_tpp', $id)
            ->first();
          $linimasa = false;
          $elemen = '';
          $aktivitas = '';
          if ($dataSebelumnya['id_wilayah_kerja'] != $dataTpp['id_wilayah_kerja']) {
            $elemen .= "<div class='col-12'><strong>Wilayah Kerja</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['wilayah_kerja'] . "</div>
                <div class='col-6 border'>" . $dataTpp['wilayah_kerja'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data TPP dari PRL';
          }
          if ($dataSebelumnya['nama_tpp'] != $dataTpp['nama_tpp']) {
            $elemen .= "<div class='col-12'><strong>Nama TPP</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['nama_tpp'] . "</div>
                <div class='col-6 border'>" . $dataTpp['nama_tpp'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data TPP dari PRL';
          }
          if ($dataSebelumnya['penanggung_jawab'] != $dataTpp['penanggung_jawab']) {
            $elemen .= "<div class='col-12'><strong>Pemilik/Penanggung Jawab</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['penanggung_jawab'] . "</div>
                <div class='col-6 border'>" . $dataTpp['penanggung_jawab'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data TPP dari PRL';
          }
          if ($dataSebelumnya['jml_karyawan'] != $dataTpp['jml_karyawan']) {
            $elemen .= "<div class='col-12'><strong>Jumlah Karyawan</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['jml_karyawan'] . "</div>
                <div class='col-6 border'>" . $dataTpp['jml_karyawan'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data TPP dari PRL';
          }
          if ($dataSebelumnya['kapasitas_produksi'] != $dataTpp['kapasitas_produksi']) {
            $elemen .= "<div class='col-12'><strong>Kapasitas Produksi</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['kapasitas_produksi'] . "</div>
                <div class='col-6 border'>" . $dataTpp['kapasitas_produksi'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data TPP dari PRL';
          }
          if ($linimasa == true) {
            $newId = $id;
            $timeline = [
              'icon'        => 'fas fa-edit bg-warning',
              'id_table'    => $newId,
              'table'       => 'prl_tpp',
              'id_user'     => $by['id_user'],
              'aktivitas'   => $aktivitas,
              'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data TPP <div class='row pl-4 pr-4 pt-2'>" . $elemen . "</div>",
            ];
          }
        } else {
          // Tambah
          $newId = $this->prlTppModel->getInsertID();
          $timeline = [
            'icon'        => 'fas fa-save bg-primary',
            'id_table'    => $newId,
            'table'       => 'prl_tpp',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'menambahkan data TPP di PRL',
            'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah menambahkan data TPP dengan nama " . $this->request->getVar("nama_tpp_form"),
          ];
        }
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

  public function hapus_tpp($id)
  {
    $dataSebelumnya = $this->prlTppModel
      ->join('user', 'user.id_user = prl_tpp.created_by')
      ->where('id_prl_tpp', $id)
      ->first();

    if ($dataSebelumnya) {
      $this->prlTppModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_prl_tpp'],
        'table'       => 'prl_tpp',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data TPP dengan nama ' . $dataSebelumnya['nama_tpp'] . ' dari PRL',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }

  public function simpan_inspeksi_tpp($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_prl_inspeksi_tpp' => $id];

      $tanggalInspeksiInput = $this->request->getVar("tanggal_inspeksi");
      $tanggalInspeksiDatabase = date('Y-m-d', strtotime($tanggalInspeksiInput));

      $id_pegawai = $this->request->getVar("pegawai_tpp"); // Mengambil data sebagai array
      $id_pegawai_str = implode(" | ", $id_pegawai);

      $data += [
        'id_wilayah_kerja'                => $this->request->getVar("wilayah_kerja_tpp"),
        'tanggal_inspeksi'                => $tanggalInspeksiDatabase,
        'id_prl_tpp'                      => $this->request->getVar("nama_tpp"),
        'id_data_pegawai'                 => $id_pegawai_str,
        'penanggung_jawab_tpp'            => $this->request->getVar("penanggung_jawab"),
        'jml_karyawan_tpp'                => $this->request->getVar("jml_karyawan"),
        'kapasitas_produksi_tpp'          => $this->request->getVar("kapasitas_produksi"),
        'sec_a_satu'                      => $this->request->getVar("sec_a1"),
        'sec_a_dua'                       => $this->request->getVar("sec_a2"),
        'sec_a_tiga'                      => $this->request->getVar("sec_a3"),
        'sec_a_skor'                      => $this->request->getVar("skor_risiko_sec_a"),
        'sec_b_satu'                      => $this->request->getVar("sec_b1"),
        'sec_b_dua'                       => $this->request->getVar("sec_b2"),
        'sec_b_tiga'                      => $this->request->getVar("sec_b3"),
        'sec_b_skor'                      => $this->request->getVar("skor_risiko_sec_b"),
        'sec_c_skor'                      => $this->request->getVar("skor_risiko_sec_c"),
        'sec_c_keterangan'                => $this->request->getVar("keterangan_risiko_sec_c"),
        'sec_d_nilai_inspeksi_sebelumnya' => $this->request->getVar("nilai_inspeksi_sebelumnya"),
        'sec_d_skor'                      => $this->request->getVar("skor_risiko_sec_d"),
        'kesimpulan'                      => $this->request->getVar("kesimpulan"),
        'makanan'                         => $this->request->getVar("makanan"),
        'hasil'                           => $this->request->getVar("hasil"),
        'saran_rencana_tindakan'          => $this->request->getVar("saran_rencana_tindakan"),
        'created_by'                      => $by['id_user'],
        'updated_by'                      => $by['id_user']
      ];

      if ($this->prlInspeksiTppModel->save($data)) {
        $msg = [
          'sukses' => $data
        ];
      }
    } else {
      $msg = [
        'error' => "Maaf terjadi kesalahan saat penyimpanan, silahkan coba kembali!"
      ];
    }
    echo json_encode($msg);
  }

  public function hapus_inspeksi_tpp($id)
  {
    $dataSebelumnya = $this->prlInspeksiTppModel
      ->join('user', 'user.id_user = prl_inspeksi_tpp.created_by')
      ->join('prl_tpp', 'prl_tpp.id_prl_tpp = prl_inspeksi_tpp.id_prl_tpp')
      ->where('id_prl_inspeksi_tpp', $id)
      ->first();

    if ($dataSebelumnya) {
      $this->prlInspeksiTppModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_prl_inspeksi_tpp'],
        'table'       => 'prl_inspeksi_tpp',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data Inspeksi TPP dengan nama ' . $dataSebelumnya['nama_tpp'] . ' dari Inspeksi TPP',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }

  // CRUD SAB ==================================================================================>
  public function simpan_sab($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_prl_sab' => $id];

      $dataSebelumnya = $this->prlSabModel
        ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_sab.*')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sab.id_wilayah_kerja')
        ->where('id_prl_sab', $id)
        ->first();

      $data += [
        'id_wilayah_kerja'        => $this->request->getVar("wilayah_kerja_form"),
        'nama_sab'                => $this->request->getVar("nama_sab_form"),
        'penanggung_jawab'        => $this->request->getVar("nama_pj_form"),
        'penanggung_jawab_no_hp'  => $this->request->getVar("nomor_hp_pj_form"),
        'sumber_air'              => $this->request->getVar("sumber_air_form"),
        'kapasitas_penampungan'   => $this->request->getVar("kapasitas_penampungan_form"),
        'created_by'              => $by['id_user'],
        'updated_by'              => $by['id_user']
      ];

      if ($this->prlSabModel->save($data)) {
        if ($id != null) {
          // Update
          $dataSab = $this->prlSabModel
            ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_sab.*')
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_sab.id_wilayah_kerja')
            ->where('id_prl_sab', $id)
            ->first();
          $linimasa = false;
          $elemen = '';
          $aktivitas = '';
          if ($dataSebelumnya['id_wilayah_kerja'] != $dataSab['id_wilayah_kerja']) {
            $elemen .= "<div class='col-12'><strong>Wilayah Kerja</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['wilayah_kerja'] . "</div>
                <div class='col-6 border'>" . $dataSab['wilayah_kerja'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data SAB dari PRL';
          }
          if ($dataSebelumnya['nama_sab'] != $dataSab['nama_sab']) {
            $elemen .= "<div class='col-12'><strong>Nama SAB</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['nama_sab'] . "</div>
                <div class='col-6 border'>" . $dataSab['nama_sab'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data SAB dari PRL';
          }
          if ($dataSebelumnya['penanggung_jawab'] != $dataSab['penanggung_jawab']) {
            $elemen .= "<div class='col-12'><strong>Nama Pemilik/Penanggung Jawab</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['penanggung_jawab'] . "</div>
                <div class='col-6 border'>" . $dataSab['penanggung_jawab'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data SAB dari PRL';
          }
          if ($dataSebelumnya['penanggung_jawab_no_hp'] != $dataSab['penanggung_jawab_no_hp']) {
            $elemen .= "<div class='col-12'><strong>No. HP Pemilik/Penanggung Jawab</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['penanggung_jawab_no_hp'] . "</div>
                <div class='col-6 border'>" . $dataSab['penanggung_jawab_no_hp'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data SAB dari PRL';
          }
          if ($dataSebelumnya['sumber_air'] != $dataSab['sumber_air']) {
            $elemen .= "<div class='col-12'><strong>Kapasitas Penampungan</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['sumber_air'] . "</div>
                <div class='col-6 border'>" . $dataSab['sumber_air'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data SAB dari PRL';
          }
          if ($dataSebelumnya['kapasitas_penampungan'] != $dataSab['kapasitas_penampungan']) {
            $elemen .= "<div class='col-12'><strong>Kapasitas Penampungan</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['kapasitas_penampungan'] . "</div>
                <div class='col-6 border'>" . $dataSab['kapasitas_penampungan'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data SAB dari PRL';
          }
          if ($linimasa == true) {
            $newId = $id;
            $timeline = [
              'icon'        => 'fas fa-edit bg-warning',
              'id_table'    => $newId,
              'table'       => 'prl_sab',
              'id_user'     => $by['id_user'],
              'aktivitas'   => $aktivitas,
              'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data SAB <div class='row pl-4 pr-4 pt-2'>" . $elemen . "</div>",
            ];
          }
        } else {
          // Tambah
          $newId = $this->prlSabModel->getInsertID();
          $timeline = [
            'icon'        => 'fas fa-save bg-primary',
            'id_table'    => $newId,
            'table'       => 'prl_sab',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'menambahkan data SAB di PRL',
            'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah menambahkan data SAB dengan nama " . $this->request->getVar("nama_sab_form"),
          ];
        }
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

  public function hapus_sab($id)
  {
    $dataSebelumnya = $this->prlSabModel
      ->join('user', 'user.id_user = prl_sab.created_by')
      ->where('id_prl_sab', $id)
      ->first();

    if ($dataSebelumnya) {
      $this->prlSabModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_prl_sab'],
        'table'       => 'prl_sab',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data SAB dengan nama ' . $dataSebelumnya['nama_sab'] . ' dari PRL',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }

  public function simpan_inspeksi_sab($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_prl_inspeksi_sab' => $id];

      $tanggalInspeksiInput = $this->request->getVar("tanggal_inspeksi");
      $tanggalInspeksiDatabase = date('Y-m-d', strtotime($tanggalInspeksiInput));

      $id_pegawai = $this->request->getVar("pegawai_sab"); // Mengambil data sebagai array
      $id_pegawai_str = implode(" | ", $id_pegawai);

      $data += [
        'id_wilayah_kerja'           => $this->request->getVar("wilayah_kerja_sab"),
        'tanggal_inspeksi'           => $tanggalInspeksiDatabase,
        'id_prl_sab'                 => $this->request->getVar("nama_sab"),
        'id_data_pegawai'            => $id_pegawai_str,
        'penanggung_jawab_sab'       => $this->request->getVar("nama_pj"),
        'penanggung_jawab_no_hp_sab' => $this->request->getVar("nomor_hp_pj"),
        'sumber_air_sab'             => $this->request->getVar("sumber_air"),
        'kapasitas_penampungan_sab'  => $this->request->getVar("kapasitas_penampungan"),
        'sec_b_satu'                 => $this->request->getVar("b1"),
        'sec_b_dua'                  => $this->request->getVar("b2"),
        'sec_b_tiga'                 => $this->request->getVar("b3"),
        'sec_b_empat'                => $this->request->getVar("b4"),
        'sec_c_satu'                 => $this->request->getVar("c1"),
        'sec_c_dua'                  => $this->request->getVar("c2"),
        'sec_c_tiga'                 => $this->request->getVar("c3"),
        'sec_c_empat'                => $this->request->getVar("c4"),
        'sec_c_lima'                 => $this->request->getVar("c5"),
        'sec_c_enam'                 => $this->request->getVar("c6"),
        'sec_c_tujuh'                => $this->request->getVar("c7"),
        'sec_c_delapan'              => $this->request->getVar("c8"),
        'sec_c_sembilan'             => $this->request->getVar("c9"),
        'sec_c_sepuluh'              => $this->request->getVar("c10"),
        'hasil'                      => $this->request->getVar("hasil"),
        'saran_rencana_tindakan'     => $this->request->getVar("saran_rencana_tindak_lanjut"),
        'created_by'                 => $by['id_user'],
        'updated_by'                 => $by['id_user']
      ];

      if ($this->prlInspeksiSabModel->save($data)) {
        $msg = [
          'sukses' => $data
        ];
      }
    } else {
      $msg = [
        'error' => "Maaf terjadi kesalahan saat penyimpanan, silahkan coba kembali!"
      ];
    }
    echo json_encode($msg);
  }

  public function hapus_inspeksi_sab($id)
  {
    $dataSebelumnya = $this->prlInspeksiSabModel
      ->join('user', 'user.id_user = prl_inspeksi_sab.created_by')
      ->join('prl_sab', 'prl_sab.id_prl_sab = prl_inspeksi_sab.id_prl_sab')
      ->where('id_prl_inspeksi_sab', $id)
      ->first();

    if ($dataSebelumnya) {
      $this->prlInspeksiSabModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_prl_inspeksi_sab'],
        'table'       => 'prl_inspeksi_sab',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data Inspeksi SAB dengan nama ' . $dataSebelumnya['nama_sab'] . ' dari Inspeksi SAB',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }

  // CRUD HSBU ==================================================================================>
  public function simpan_hsbu($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_prl_hsbu' => $id];

      $dataSebelumnya = $this->prlHsbuModel
        ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_hsbu.*')
        ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_hsbu.id_wilayah_kerja')
        ->where('id_prl_hsbu', $id)
        ->first();

      $data += [
        'id_wilayah_kerja' => $this->request->getVar("wilayah_kerja_form"),
        'nama_gedung'      => $this->request->getVar("nama_hsbu_form"),
        'penanggung_jawab' => $this->request->getVar("penanggung_jawab_form"),
        'jml_karyawan'     => $this->request->getVar("jml_karyawan_form"),
        'created_by'       => $by['id_user'],
        'updated_by'       => $by['id_user']
      ];

      if ($this->prlHsbuModel->save($data)) {
        if ($id != null) {
          // Update
          $dataHsbu = $this->prlHsbuModel
            ->select('unit_kerja.unit_kerja as wilayah_kerja, prl_hsbu.*')
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja = prl_hsbu.id_wilayah_kerja')
            ->where('id_prl_hsbu', $id)
            ->first();
          $linimasa = false;
          $elemen = '';
          $aktivitas = '';
          if ($dataSebelumnya['id_wilayah_kerja'] != $dataHsbu['id_wilayah_kerja']) {
            $elemen .= "<div class='col-12'><strong>Wilayah Kerja</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['wilayah_kerja'] . "</div>
                <div class='col-6 border'>" . $dataHsbu['wilayah_kerja'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data HSBU dari PRL';
          }
          if ($dataSebelumnya['nama_gedung'] != $dataHsbu['nama_gedung']) {
            $elemen .= "<div class='col-12'><strong>Nama HSBU</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['nama_gedung'] . "</div>
                <div class='col-6 border'>" . $dataHsbu['nama_gedung'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data HSBU dari PRL';
          }
          if ($dataSebelumnya['penanggung_jawab'] != $dataHsbu['penanggung_jawab']) {
            $elemen .= "<div class='col-12'><strong>Nama Pemilik/Penanggung Jawab</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['penanggung_jawab'] . "</div>
                <div class='col-6 border'>" . $dataHsbu['penanggung_jawab'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data HSBU dari PRL';
          }
          if ($dataSebelumnya['jml_karyawan'] != $dataHsbu['jml_karyawan']) {
            $elemen .= "<div class='col-12'><strong>No. HP Pemilik/Penanggung Jawab</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['jml_karyawan'] . "</div>
                <div class='col-6 border'>" . $dataHsbu['jml_karyawan'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah data HSBU dari PRL';
          }
          if ($linimasa == true) {
            $newId = $id;
            $timeline = [
              'icon'        => 'fas fa-edit bg-warning',
              'id_table'    => $newId,
              'table'       => 'prl_hsbu',
              'id_user'     => $by['id_user'],
              'aktivitas'   => $aktivitas,
              'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data HSBU <div class='row pl-4 pr-4 pt-2'>" . $elemen . "</div>",
            ];
          }
        } else {
          // Tambah
          $newId = $this->prlSabModel->getInsertID();
          $timeline = [
            'icon'        => 'fas fa-save bg-primary',
            'id_table'    => $newId,
            'table'       => 'prl_hsbu',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'menambahkan data HSBU di PRL',
            'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah menambahkan data HSBU dengan nama " . $this->request->getVar("nama_hsbu_form"),
          ];
        }
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

  public function hapus_hsbu($id)
  {
    $dataSebelumnya = $this->prlHsbuModel
      ->join('user', 'user.id_user = prl_hsbu.created_by')
      ->where('id_prl_hsbu', $id)
      ->first();

    if ($dataSebelumnya) {
      $this->prlHsbuModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_prl_hsbu'],
        'table'       => 'prl_hsbu',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data HSBU dengan nama ' . $dataSebelumnya['nama_gedung'] . ' dari PRL',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }

  public function simpan_inspeksi_hsbu($id = null)
  {
    if ($this->request->isAJAX()) {
      $lingkungan_luar_halaman = [];
      for ($i = 0; $i < count($this->request->getVar("lingkungan_luar_halaman")); $i++) {
        $lingkungan_luar_halaman[$i] = [
          'lingkungan_luar_halaman' => $this->request->getVar("lingkungan_luar_halaman")[$i],
          'nilai_lingkungan_luar_halaman' => $this->request->getVar("nilai_lingkungan_luar_halaman")[$i],
          'skor_lingkungan_luar_halaman' => $this->request->getVar("skor_lingkungan_luar_halaman")[$i],
          'keterangan_lingkungan_luar_halaman' => $this->request->getVar("keterangan_lingkungan_luar_halaman")[$i],
        ];
      }

      $ruang_bangunan = [];
      for ($i = 0; $i < count($this->request->getVar("ruang_bangunan")); $i++) {
        $ruang_bangunan[$i] = [
          'ruang_bangunan' => $this->request->getVar("ruang_bangunan")[$i],
          'nilai_ruang_bangunan' => $this->request->getVar("nilai_ruang_bangunan")[$i],
          'skor_ruang_bangunan' => $this->request->getVar("skor_ruang_bangunan")[$i],
          'keterangan_ruang_bangunan' => $this->request->getVar("keterangan_ruang_bangunan")[$i],
        ];
      }

      $penyehatan_air = [];
      for ($i = 0; $i < count($this->request->getVar("penyehatan_air")); $i++) {
        $penyehatan_air[$i] = [
          'penyehatan_air' => $this->request->getVar("penyehatan_air")[$i],
          'nilai_penyehatan_air' => $this->request->getVar("nilai_penyehatan_air")[$i],
          'skor_penyehatan_air' => $this->request->getVar("skor_penyehatan_air")[$i],
          'keterangan_penyehatan_air' => $this->request->getVar("keterangan_penyehatan_air")[$i],
        ];
      }

      $penyehatan_ruang_udara = [];
      for ($i = 0; $i < count($this->request->getVar("penyehatan_ruang_udara")); $i++) {
        $penyehatan_ruang_udara[$i] = [
          'penyehatan_ruang_udara' => $this->request->getVar("penyehatan_ruang_udara")[$i],
          'nilai_penyehatan_ruang_udara' => $this->request->getVar("nilai_penyehatan_ruang_udara")[$i],
          'skor_penyehatan_ruang_udara' => $this->request->getVar("skor_penyehatan_ruang_udara")[$i],
          'keterangan_penyehatan_ruang_udara' => $this->request->getVar("keterangan_penyehatan_ruang_udara")[$i],
        ];
      }

      $pengelolaan_limbah = [];
      for ($i = 0; $i < count($this->request->getVar("pengelolaan_limbah")); $i++) {
        $pengelolaan_limbah[$i] = [
          'pengelolaan_limbah' => $this->request->getVar("pengelolaan_limbah")[$i],
          'nilai_pengelolaan_limbah' => $this->request->getVar("nilai_pengelolaan_limbah")[$i],
          'skor_pengelolaan_limbah' => $this->request->getVar("skor_pengelolaan_limbah")[$i],
          'keterangan_pengelolaan_limbah' => $this->request->getVar("keterangan_pengelolaan_limbah")[$i],
        ];
      }

      $pencahayaan = [];
      for ($i = 0; $i < count($this->request->getVar("pencahayaan")); $i++) {
        $pencahayaan[$i] = [
          'pencahayaan' => $this->request->getVar("pencahayaan")[$i],
          'nilai_pencahayaan' => $this->request->getVar("nilai_pencahayaan")[$i],
          'skor_pencahayaan' => $this->request->getVar("skor_pencahayaan")[$i],
          'keterangan_pencahayaan' => $this->request->getVar("keterangan_pencahayaan")[$i],
        ];
      }

      $kebisingan_pada_ruang = [];
      for ($i = 0; $i < count($this->request->getVar("kebisingan_pada_ruang")); $i++) {
        $kebisingan_pada_ruang[$i] = [
          'kebisingan_pada_ruang' => $this->request->getVar("kebisingan_pada_ruang")[$i],
          'nilai_kebisingan_pada_ruang' => $this->request->getVar("nilai_kebisingan_pada_ruang")[$i],
          'skor_kebisingan_pada_ruang' => $this->request->getVar("skor_kebisingan_pada_ruang")[$i],
          'keterangan_kebisingan_pada_ruang' => $this->request->getVar("keterangan_kebisingan_pada_ruang")[$i],
        ];
      }

      $getaran_di_ruang_kerja = [];
      for ($i = 0; $i < count($this->request->getVar("getaran_di_ruang_kerja")); $i++) {
        $getaran_di_ruang_kerja[$i] = [
          'getaran_di_ruang_kerja' => $this->request->getVar("getaran_di_ruang_kerja")[$i],
          'nilai_getaran_di_ruang_kerja' => $this->request->getVar("nilai_getaran_di_ruang_kerja")[$i],
          'skor_getaran_di_ruang_kerja' => $this->request->getVar("skor_getaran_di_ruang_kerja")[$i],
          'keterangan_getaran_di_ruang_kerja' => $this->request->getVar("keterangan_getaran_di_ruang_kerja")[$i],
        ];
      }

      $pengendalian_vektor_penyakit = [];
      for ($i = 0; $i < count($this->request->getVar("pengendalian_vektor_penyakit")); $i++) {
        $pengendalian_vektor_penyakit[$i] = [
          'pengendalian_vektor_penyakit' => $this->request->getVar("pengendalian_vektor_penyakit")[$i],
          'nilai_pengendalian_vektor_penyakit' => $this->request->getVar("nilai_pengendalian_vektor_penyakit")[$i],
          'skor_pengendalian_vektor_penyakit' => $this->request->getVar("skor_pengendalian_vektor_penyakit")[$i],
          'keterangan_pengendalian_vektor_penyakit' => $this->request->getVar("keterangan_pengendalian_vektor_penyakit")[$i],
        ];
      }

      $instalasi = [];
      for ($i = 0; $i < count($this->request->getVar("instalasi")); $i++) {
        $instalasi[$i] = [
          'instalasi' => $this->request->getVar("instalasi")[$i],
          'nilai_instalasi' => $this->request->getVar("nilai_instalasi")[$i],
          'skor_instalasi' => $this->request->getVar("skor_instalasi")[$i],
          'keterangan_instalasi' => $this->request->getVar("keterangan_instalasi")[$i],
        ];
      }

      $pemeliharaan_jamban_dan_kamar_mandi = [];
      for ($i = 0; $i < count($this->request->getVar("pemeliharaan_jamban_dan_kamar_mandi")); $i++) {
        $pemeliharaan_jamban_dan_kamar_mandi[$i] = [
          'pemeliharaan_jamban_dan_kamar_mandi' => $this->request->getVar("pemeliharaan_jamban_dan_kamar_mandi")[$i],
          'nilai_pemeliharaan_jamban_dan_kamar_mandi' => $this->request->getVar("nilai_pemeliharaan_jamban_dan_kamar_mandi")[$i],
          'skor_pemeliharaan_jamban_dan_kamar_mandi' => $this->request->getVar("skor_pemeliharaan_jamban_dan_kamar_mandi")[$i],
          'keterangan_pemeliharaan_jamban_dan_kamar_mandi' => $this->request->getVar("keterangan_pemeliharaan_jamban_dan_kamar_mandi")[$i],
        ];
      }

      $by = $this->userModel
        ->select('id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_prl_inspeksi_hsbu' => $id];

      $tanggalInspeksiInput = $this->request->getVar("tanggal_inspeksi");
      $tanggalInspeksiDatabase = date('Y-m-d', strtotime($tanggalInspeksiInput));

      $id_pegawai = $this->request->getVar("pegawai_hsbu"); // Mengambil data sebagai array
      $id_pegawai_str = implode(" | ", $id_pegawai);

      $data += [
        'id_wilayah_kerja'                    => $this->request->getVar("wilayah_kerja_hsbu"),
        'tanggal_inspeksi'                    => $tanggalInspeksiDatabase,
        'id_prl_hsbu'                         => $this->request->getVar("nama_hsbu"),
        'id_data_pegawai'                     => $id_pegawai_str,
        'penanggung_jawab_hsbu'               => $this->request->getVar("penanggung_jawab"),
        'jml_karyawan_hsbu'                   => $this->request->getVar("jml_karyawan"),
        'lingkungan_luar_halaman'             => serialize($lingkungan_luar_halaman),
        'ruang_bangunan'                      => serialize($ruang_bangunan),
        'penyehatan_air'                      => serialize($penyehatan_air),
        'penyehatan_ruang_udara'              => serialize($penyehatan_ruang_udara),
        'pengelolaan_limbah'                  => serialize($pengelolaan_limbah),
        'pencahayaan'                         => serialize($pencahayaan),
        'kebisingan_pada_ruang'               => serialize($kebisingan_pada_ruang),
        'getaran_di_ruang_kerja'              => serialize($getaran_di_ruang_kerja),
        'pengendalian_vektor_penyakit'        => serialize($pengendalian_vektor_penyakit),
        'instalasi'                           => serialize($instalasi),
        'pemeliharaan_jamban_dan_kamar_mandi' => serialize($pemeliharaan_jamban_dan_kamar_mandi),
        'total'                               => $this->request->getVar("total"),
        'saran_rencana_tindakan'              => $this->request->getVar("saran_rencana_tindakan"),
        'catatan'                             => $this->request->getVar("catatan"),
        'created_by'                          => $by['id_user'],
        'updated_by'                          => $by['id_user']
      ];

      // dd($data);
      if ($this->prlInspeksiHsbuModel->save($data)) {
        $msg = [
          'sukses' => $data
        ];
      }
    } else {
      $msg = [
        'error' => "Maaf terjadi kesalahan saat penyimpanan, silahkan coba kembali!"
      ];
    }
    echo json_encode($msg);
  }

  public function hapus_inspeksi_hsbu($id)
  {
    $dataSebelumnya = $this->prlInspeksiHsbuModel
      ->join('user', 'user.id_user = prl_inspeksi_hsbu.created_by')
      ->join('prl_hsbu', 'prl_hsbu.id_prl_hsbu = prl_inspeksi_hsbu.id_prl_hsbu')
      ->where('id_prl_inspeksi_hsbu', $id)
      ->first();

    if ($dataSebelumnya) {
      $this->prlInspeksiHsbuModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_prl_inspeksi_hsbu'],
        'table'       => 'prl_inspeksi_hsbu',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data Inspeksi HSBU dengan nama ' . $dataSebelumnya['nama_gedung'] . ' dari Inspeksi HSBU',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }
}
