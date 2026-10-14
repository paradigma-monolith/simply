<?php

namespace App\Controllers\Sipedit;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function tambah_data_arsip($id = null)
  {
    //  if (!$this->validate([
    // 'pegawai' => 'required',
    // 'nama_pelatihan' => 'required',
    // 'lembaga_pelatihan' => 'required',
    // 'kategori' => 'required',
    // 'tanggal' => 'required',
    // 'jumlah_jpl' => 'required',
    // 'unggah_berkas_pelatihan' => [
    //     'uploaded[unggah_berkas_pelatihan]',
    //     'ext_in[unggah_berkas_pelatihan,pdf,jpg,jpeg,png]'
    // ]
    // ])) {
    //     session()->setFlashdata('error', 'Data yang anda masukkan salah, pastikan semua data terisi dengan benar. <br/>Pastikan tipe File yang di unggah adalah PDF, JPG, JPEG, atau PNG.');
    //     return redirect()->to('/sipedit/unggah_pelatihan')->withInput();
    // }
     $rules = [
        'pegawai' => 'required',
        'nama_pelatihan' => 'required',
        'lembaga_pelatihan' => 'required',
        'kategori' => 'required',
        'tanggal' => 'required',
        'jumlah_jpl' => 'required'
    ];

    // Jika file diunggah, tambahkan validasi untuk jenis file
    if ($this->request->getFile('unggah_berkas_pelatihan')) {
        $rules['unggah_berkas_pelatihan'] = [
            'uploaded[unggah_berkas_pelatihan]',
            'ext_in[unggah_berkas_pelatihan,pdf,jpg,jpeg,png]'
        ];
    }

    if (!$this->validate($rules)) {
        session()->setFlashdata('error', 'Data yang anda masukkan salah, pastikan semua data terisi dengan benar. <br/>Jika anda mengupload file pastikan tipe file adalah <strong>PDF/ JPG/ JPEG/ PNG</strong>');
        // Mengeksekusi aksi tambahan jika validasi gagal
        return redirect()->to('/sipedit/unggah_pelatihan')->withInput();
    }
      
    $by = $this->userModel->select('id_user')->where('username', session()->get('username'))->first();

    $data = [];
    if ($id != null)
      $data += ['id_sipedit_arsip' => $id];

    $file = $this->request->getFile("unggah_berkas_pelatihan");
    $acak = rand();

    $cek = false;
    if ($id != null) {
      $cek = $this->sipeditArsipModel->where('id_sipedit_arsip', $id)->first();
      if ($cek) {
        $path = './sipedit_arsip/' . $cek['arsip_file'];
      }
    }
    if ($file != '') {
    //   $newFile = $acak . '_' . $file->getName();
      $fileName = $file->getName();
      $cleanName = preg_replace('/[^\w\d\s.]/', '', $fileName);
      $newFile = $acak . '_' . $cleanName;
      $file->move('./sipedit_arsip/', $newFile);
      $data += ['arsip_file' => $newFile];
      if ($cek && $cek['arsip_file'] != '')
        if (file_exists($path))
          unlink($path);
    }

    $tanggal_awal = $this->request->getVar("tanggal");
    $tanggal_selesai =  $this->request->getVar("tanggal");
    $tanggal_awal = substr($tanggal_awal, 0, 10);
    $tanggal_selesai = substr($tanggal_selesai, 13, 23);
    
    $dataSebelumnya = $this->sipeditArsipModel
      ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
      ->where('id_sipedit_arsip', $id)
      ->first();

    $data += [
      'id_data_pegawai'     => $this->request->getVar("pegawai"),
      'id_sipedit_kategori' => $this->request->getVar("kategori"),
      'arsip_kode'          => $this->request->getVar("nama_pelatihan"),
      'arsip_nama'          => $this->request->getVar("lembaga_pelatihan"),
      'arsip_tanggal'       => $tanggal_awal,
      'arsip_selesai'       => $tanggal_selesai,
      'arsip_jpl'           => $this->request->getVar("jumlah_jpl"),
      'created_by'          => $by['id_user'],
      'updated_by'          => $by['id_user']
    ];


    if ($this->sipeditArsipModel->save($data)) {
    
      // Linimasa
      if ($id != null) {
        // Update
        $dataArsip = $this->sipeditArsipModel
          ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
          ->where('id_sipedit_arsip', $id)
          ->first();
        $linimasa = false;
        $elemen = '';
        $aktivitas = '';
        if ($dataSebelumnya['arsip_kode'] != $dataArsip['arsip_kode']) {
          $elemen .= "<div class='col-12'><strong>Nama Pelatihan</strong></div>
              <div class='col-6 border'>" . $dataSebelumnya['arsip_kode'] . "</div>
              <div class='col-6 border'>" . $dataArsip['arsip_kode'] . "</div>";
          $linimasa = true;
          $aktivitas = 'melakukan perubahan pada data ' . $dataSebelumnya['arsip_kode'] . ' dari SIPEDIT';
        }
        if ($dataSebelumnya['arsip_nama'] != $dataArsip['arsip_nama']) {
          $elemen .= "<div class='col-12'><strong>Lembaga Pelatihan</strong></div>
              <div class='col-6 border'>" . $dataSebelumnya['arsip_nama'] . "</div>
              <div class='col-6 border'>" . $dataArsip['arsip_nama'] . "</div>";
          $linimasa = true;
          $aktivitas = 'melakukan perubahan pada data ' . $dataSebelumnya['arsip_kode'] . ' dari SIPEDIT';
        }
        if ($dataSebelumnya['id_sipedit_kategori'] != $dataArsip['id_sipedit_kategori']) {
          $elemen .= "<div class='col-12'><strong>Kategori</strong></div>
              <div class='col-6 border'>" . $dataSebelumnya['kategori_nama'] . "</div>
              <div class='col-6 border'>" . $dataArsip['kategori_nama'] . "</div>";
          $linimasa = true;
          $aktivitas = 'melakukan perubahan pada data ' . $dataSebelumnya['arsip_kode'] . ' dari SIPEDIT';
        }
        if ($dataSebelumnya['arsip_tanggal'] != $dataArsip['arsip_tanggal'] || $dataSebelumnya['arsip_selesai'] != $dataArsip['arsip_selesai']) {
          $elemen .= "<div class='col-12'><strong>Tanggal Pelaksanaan</strong></div>
          <div class='col-6 border'>" . $dataSebelumnya['arsip_tanggal'] . " s/d " . $dataSebelumnya['arsip_selesai'] . "</div>
          <div class='col-6 border'>" . $dataArsip['arsip_tanggal'] . " s/d " . $dataArsip['arsip_selesai'] . "</div>";
          $linimasa = true;
          $aktivitas = 'melakukan perubahan pada data ' . $dataSebelumnya['arsip_kode'] . ' dari SIPEDIT';
        }
        if ($dataSebelumnya['arsip_jpl'] != $dataArsip['arsip_jpl']) {
          $elemen .= "<div class='col-12'><strong>Jumlah JPL/45 menit</strong></div>
              <div class='col-6 border'>" . $dataSebelumnya['arsip_jpl'] . "</div>
              <div class='col-6 border'>" . $dataArsip['arsip_jpl'] . "</div>";
          $linimasa = true;
          $aktivitas = 'melakukan perubahan pada data ' . $dataSebelumnya['arsip_kode'] . ' dari SIPEDIT';
        }
        if ($dataSebelumnya['arsip_file'] != $dataArsip['arsip_file']) {
          $elemen .= "<div class='col-12'><strong>Dokumen Pelatihan</strong></div>
              <div class='col-6 border'>" . $dataSebelumnya['arsip_file'] . "</div>
              <div class='col-6 border'>" . $dataArsip['arsip_file'] . "</div>";
          $linimasa = true;
          $aktivitas = 'melakukan perubahan pada data ' . $dataSebelumnya['arsip_kode'] . ' dari SIPEDIT';
        }
        if ($linimasa == true) {
          $newId = $id;
          $timeline = [
            'icon'        => 'fas fa-edit bg-warning',
            'id_table'    => $newId,
            'table'       => 'sipedit_arsip',
            'id_user'     => $by['id_user'],
            'aktivitas'   => $aktivitas,
            'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan data untuk pegawai bernama " . $dataArsip['nama'] . "<div class='row pl-4 pr-4 pt-2'>" . $elemen . "</div>",
          ];
        }
      } else {
        // Tambah
        $newId = $this->sipeditArsipModel->getInsertID();
        $pegawai = $this->dataPegawaiModel->where('id_data_pegawai', $this->request->getVar("pegawai"))->first();
        $kategori = $this->sipeditArsipKategoriModel->where('id_sipedit_kategori', $this->request->getVar("kategori"))->first();
        $timeline = [
          'icon'        => 'fas fa-save bg-primary',
          'id_table'    => $newId,
          'table'       => 'sipedit_arsip',
          'id_user'     => $by['id_user'],
          'aktivitas'   => 'menambahkan data ke dalam SIPEDIT',
          'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah menambahkan data " . $kategori['kategori_nama'] . " untuk pegawai bernama " . $pegawai['nama'],
        ];
      }
      $this->linimasaModel->save($timeline);
    
      session()->setFlashdata('pesan', 'Data berhasil disimpan.');
      return redirect()->to('sipedit/arsip_saya');
    } else {
      session()->setFlashdata('error', 'Data gagal disimpan.');
      return redirect()->to('sipedit/unggah_pelatihan');
    }
  }
  public function del_sipedit($id)
  {
    $dataSebelumnya = $this->sipeditArsipModel
      ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
      ->where('id_sipedit_arsip', $id)
      ->first();

    if ($id) {
      $cek = $this->sipeditArsipModel->where('id_sipedit_arsip', $id)->first();
      if ($cek) {
        $path = './sipedit_arsip/' . $cek['arsip_file'];
      }
    }

    if ($cek && $cek['arsip_file'] != '')
      if (file_exists($path))
        unlink($path);

    if ($dataSebelumnya) {
      $this->sipeditArsipModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_sipedit_arsip'],
        'table'       => 'sipedit_arsip',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus data arsip dengan nama pelatihan ' . $dataSebelumnya['arsip_kode'] . ' dari SIPEDIT',
        'keterangan'  => "",
      ];
      $this->linimasaModel->save($timeline);
    }
  }

  public function simpan_pengaturan()
  {
      if (session()->get('role') != 'Admin') {
          return redirect()->to('home/dashboard');
      }

      $db = \Config\Database::connect();
      
      // Update Harga Satuan & Link Nakes
      $harga_nakes = $this->request->getPost('harga_nakes');
      $link_nakes = $this->request->getPost('link_nakes');
      if ($harga_nakes) {
          foreach ($harga_nakes as $jabatan => $harga) {
              $dataUpdate = ['harga' => str_replace('.', '', $harga)];
              if (isset($link_nakes[$jabatan])) {
                  $dataUpdate['link_referensi'] = $link_nakes[$jabatan];
              }
              $db->table('sipedit_harga_satuan')
                 ->where('kategori', 'Nakes')
                 ->where('jabatan', $jabatan)
                 ->update($dataUpdate);
          }
      }
      
      // Update Harga Satuan & Link Non Nakes
      $harga_non_nakes = $this->request->getPost('harga_non_nakes');
      $link_non_nakes = $this->request->getPost('link_non_nakes');
      if ($harga_non_nakes) {
          foreach ($harga_non_nakes as $jabatan => $harga) {
              $dataUpdate = ['harga' => str_replace('.', '', $harga)];
              if (isset($link_non_nakes[$jabatan])) {
                  $dataUpdate['link_referensi'] = $link_non_nakes[$jabatan];
              }
              $db->table('sipedit_harga_satuan')
                 ->where('kategori', 'Non Nakes')
                 ->where('jabatan', $jabatan)
                 ->update($dataUpdate);
          }
      }

      session()->setFlashdata('pesan', 'Pengaturan Anggaran berhasil disimpan.');
      return redirect()->back();
  }
}
