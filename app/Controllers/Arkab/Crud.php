<?php

namespace App\Controllers\Arkab;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function simpan_template_naskah($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->join('user', 'user.id_user = data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_arsip_template_naskah' => $id];

      $file = $this->request->getFile("unggah_naskah");
      $acak = rand();

      $cek = false;
      if ($id != null) {
        $cek = $this->arsipTemplateNaskahModel->where('id_arsip_template_naskah', $id)->first();
        if ($cek) {
          $path = './arkab_arsip/template_naskah/' . $cek['file'];
        }
      }

      if ($file != '') {
        $fileName = $file->getName();
        $cleanName = preg_replace('/[^\w\d\s.]/', '', $fileName);
        $newFile = $acak . '_' . $cleanName;
        $file->move('./arkab_arsip/template_naskah/', $newFile);
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

      if ($this->arsipTemplateNaskahModel->save($data)) {
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
  
  public function hapus_naskah($id)
  {
    if ($id) {
      $cek = $this->arsipTemplateNaskahModel->where('id_arsip_template_naskah', $id)->first();
      if ($cek) {
        $path = './arkab_arsip/template_naskah/' . $cek['file'];
      }
    }

    if ($cek && $cek['file'] != '')
      if (file_exists($path))
        unlink($path);

    $this->arsipTemplateNaskahModel->delete($id);
  }
  
  public function simpan_surat($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->join('user', 'user.id_user = data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_arkab_surat' => $id];

      $file = $this->request->getFile("unggah_dokumen");
      $acak = rand();

      $cek = false;
      if ($id != null) {
        $cek = $this->arkabSuratModel->where('id_arkab_surat', $id)->first();
        if ($cek) {
          $path = './arkab_arsip/' . $cek['dokumen'];
        }
      }

      if ($file != '') {
        $fileName = $file->getName();
        $cleanName = preg_replace('/[^\w\d\s.]/', '', $fileName);
        $newFile = $acak . '_' . $cleanName;
        $file->move('./arkab_arsip/', $newFile);
        $data += ['dokumen' => $newFile];
        if ($cek && $cek['dokumen'] != '')
          if (file_exists($path))
            unlink($path);
      }

      $dataSebelumnya = $this->arkabSuratModel
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
        ->join('arkab_jenis_surat', 'arkab_jenis_surat.id_arkab_jenis_surat = arkab_surat.id_jenis_surat')
        ->join('arkab_kode_klasifikasi', 'arkab_kode_klasifikasi.id_arkab_kode_klasifikasi = arkab_surat.id_kode_klasifikasi')
        ->where('id_arkab_surat', $id)
        ->first();

      $data += [
        'id_substansi'        => $this->request->getVar("substansi"),
        'id_data_pegawai'     => $this->request->getVar("pegawai"),
        'nomor_surat'         => $this->request->getVar("nomor_surat"),
        'id_jenis_surat'      => $this->request->getVar("jenis_surat"),
        'id_kode_klasifikasi' => $this->request->getVar("kode_klasifikasi"),
        'created_by'          => $by['id_user'],
        'updated_by'          => $by['id_user']
      ];

      // dd($data);

      if ($this->arkabSuratModel->save($data)) {
        if ($id != null) {
          // Update
          $dataSurat = $this->arkabSuratModel
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
            ->join('arkab_jenis_surat', 'arkab_jenis_surat.id_arkab_jenis_surat = arkab_surat.id_jenis_surat')
            ->join('arkab_kode_klasifikasi', 'arkab_kode_klasifikasi.id_arkab_kode_klasifikasi = arkab_surat.id_kode_klasifikasi')
            ->where('id_arkab_surat', $id)
            ->first();
          $linimasa = false;
          $elemen = '';
          $aktivitas = '';
          if ($dataSebelumnya['nomor_surat'] != $dataSurat['nomor_surat']) {
            $elemen .= "<div class='col-12'><strong>Nomor Surat</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['nomor_surat'] . "</div>
                <div class='col-6 border'>" . $dataSurat['nomor_surat'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah arsip surat dari ARKAB';
          }
          if ($dataSebelumnya['dokumen'] != $dataSurat['dokumen']) {
            $elemen .= "<div class='col-12'><strong>Dokumen</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['dokumen'] . "</div>
                <div class='col-6 border'>" . $dataSurat['dokumen'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah arsip surat dari ARKAB';
          }
          if ($dataSebelumnya['id_jenis_surat'] != $dataSurat['id_jenis_surat']) {
            $elemen .= "<div class='col-12'><strong>Jenis Surat</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['jenis_surat'] . "</div>
                <div class='col-6 border'>" . $dataSurat['jenis_surat'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah arsip surat dari ARKAB';
          }
          if ($dataSebelumnya['id_kode_klasifikasi'] != $dataSurat['id_kode_klasifikasi']) {
            $elemen .= "<div class='col-12'><strong>Kode Klasifikasi</strong></div>
                <div class='col-6 border'>" . $dataSebelumnya['kode'] . " " . $dataSebelumnya['keterangan'] . "</div>
                <div class='col-6 border'>" . $dataSurat['kode'] . " " . $dataSurat['keterangan'] . "</div>";
            $linimasa = true;
            $aktivitas = 'mengubah arsip surat dari ARKAB';
          }
          if ($linimasa == true) {
            $newId = $id;
            $timeline = [
              'icon'        => 'fas fa-edit bg-warning',
              'id_table'    => $newId,
              'table'       => 'wbk_rencana_kerja',
              'id_user'     => $by['id_user'],
              'aktivitas'   => $aktivitas,
              'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan arsip surat dengan nomor " . $dataSurat['nomor_surat'] . " untuk pegawai bernama " . $dataSurat['nama'] . "<div class='row pl-4 pr-4 pt-2'>" . $elemen . "</div>",
            ];
          }
        } else {
          // Tambah
          $getPegawai = $this->dataPegawaiModel->where('id_data_pegawai', $this->request->getVar("pegawai"))->first();
          $newId = $this->arkabSuratModel->getInsertID();
          $timeline = [
            'icon'        => 'fas fa-save bg-primary',
            'id_table'    => $newId,
            'table'       => 'wbk_rencana_kerja',
            'id_user'     => $by['id_user'],
            'aktivitas'   => 'menambahkan arsip surat di ARKAB',
            'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah menambahkan arsip surat dengan nomor surat " . $this->request->getVar("nomor_surat") . " untuk pegawai bernama " . $getPegawai['nama'],
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

  public function hapus_surat($id)
  {
    $dataSebelumnya = $this->arkabSuratModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
      ->where('id_arkab_surat', $id)
      ->first();

    if ($id) {
      $cek = $this->arkabSuratModel->where('id_arkab_surat', $id)->first();
      if ($cek) {
        $path = './arkab_arsip/' . $cek['dokumen'];
      }
    }

    if ($cek && $cek['dokumen'] != '')
      if (file_exists($path))
        unlink($path);

    if ($dataSebelumnya) {
      $this->arkabSuratModel->delete($id);

      $by = $this->userModel->where('username', session()->get('username'))->first();

      $timeline = [
        'icon'        => 'fas fa-trash-alt bg-danger',
        'id_table'    => $dataSebelumnya['id_arkab_surat'],
        'table'       => 'arkab_surat',
        'id_user'     => $by['id_user'],
        'aktivitas'   => 'menghapus arsip surat dari SIPEDIT',
        'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah menghapus arsip surat dengan nomor surat " . $dataSebelumnya['nomor_surat'] . " untuk pegawai bernama " . $dataSebelumnya['nama'],
      ];
      $this->linimasaModel->save($timeline);
    }
  }
}
