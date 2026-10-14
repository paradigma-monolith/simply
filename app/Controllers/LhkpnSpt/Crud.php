<?php

namespace App\Controllers\LhkpnSpt;

use App\Controllers\BaseController;

class Crud extends BaseController
{
  public function simpan_dokumen($id = null)
  {
    if ($this->request->isAJAX()) {
      $by = $this->dataPegawaiModel
        ->join('user', 'user.id_user = data_pegawai.id_user')
        ->where('user.username', session()->get('username'))
        ->first();

      $data = [];
      if ($id != null)
        $data += ['id_lhkpn_spt' => $id];

      $file = $this->request->getFile("unggah_dokumen");
      $acak = rand();

      $cek = false;
      if ($id != null) {
        $cek = $this->lhkpnSptModel->where('id_lhkpn_spt', $id)->first();
        if ($cek) {
          $path = './dokumen_lhkpn_spt/' . $cek['dokumen'];
        }
      }

      if ($file != '') {
        $fileName = $file->getName();
        $cleanName = preg_replace('/[^\w\d\s.]/', '', $fileName);
        $newFile = $acak . '_' . $cleanName;
        $file->move('./dokumen_lhkpn_spt/', $newFile);
        $data += ['dokumen' => $newFile];
        if ($cek && $cek['dokumen'] != '')
          if (file_exists($path))
            unlink($path);
      }

      $dataSebelumnya = $this->lhkpnSptModel->where('id_lhkpn_spt', $id)->first();

      $tanggalDokumenInput = $this->request->getVar("tanggal_dokumen");
      // Konversi tahun ke format tanggal (1 Januari dari tahun yang dipilih)
      $tanggalDokumen = date('Y-m-d', strtotime($tanggalDokumenInput . '-01-01'));

      $data += [
        'id_data_pegawai'   => $this->request->getVar("pegawai"),
        'tanggal_dokumen'   => $tanggalDokumen,
        'jenis_dokumen'     => $this->request->getVar("jenis_dokumen"),
        'created_by'        => $by['id_user'],
        'updated_by'        => $by['id_user']
      ];

      //   dd($data);

      if ($this->lhkpnSptModel->save($data)) {
        // if ($id != null) {
        //   // Update
        //   $dataSurat = $this->arkabSuratModel
        //     ->join('data_pegawai', 'data_pegawai.id_data_pegawai = arkab_surat.id_data_pegawai')
        //     ->join('arkab_jenis_surat', 'arkab_jenis_surat.id_arkab_jenis_surat = arkab_surat.id_jenis_surat')
        //     ->join('arkab_kode_klasifikasi', 'arkab_kode_klasifikasi.id_arkab_kode_klasifikasi = arkab_surat.id_kode_klasifikasi')
        //     ->where('id_arkab_surat', $id)
        //     ->first();
        //   $linimasa = false;
        //   $elemen = '';
        //   $aktivitas = '';
        //   if ($dataSebelumnya['nomor_surat'] != $dataSurat['nomor_surat']) {
        //     $elemen .= "<div class='col-12'><strong>Nomor Surat</strong></div>
        //         <div class='col-6 border'>" . $dataSebelumnya['nomor_surat'] . "</div>
        //         <div class='col-6 border'>" . $dataSurat['nomor_surat'] . "</div>";
        //     $linimasa = true;
        //     $aktivitas = 'mengubah arsip surat dari ARKAB';
        //   }
        //   if ($dataSebelumnya['dokumen'] != $dataSurat['dokumen']) {
        //     $elemen .= "<div class='col-12'><strong>Dokumen</strong></div>
        //         <div class='col-6 border'>" . $dataSebelumnya['dokumen'] . "</div>
        //         <div class='col-6 border'>" . $dataSurat['dokumen'] . "</div>";
        //     $linimasa = true;
        //     $aktivitas = 'mengubah arsip surat dari ARKAB';
        //   }
        //   if ($dataSebelumnya['id_jenis_surat'] != $dataSurat['id_jenis_surat']) {
        //     $elemen .= "<div class='col-12'><strong>Jenis Surat</strong></div>
        //         <div class='col-6 border'>" . $dataSebelumnya['jenis_surat'] . "</div>
        //         <div class='col-6 border'>" . $dataSurat['jenis_surat'] . "</div>";
        //     $linimasa = true;
        //     $aktivitas = 'mengubah arsip surat dari ARKAB';
        //   }
        //   if ($dataSebelumnya['id_kode_klasifikasi'] != $dataSurat['id_kode_klasifikasi']) {
        //     $elemen .= "<div class='col-12'><strong>Kode Klasifikasi</strong></div>
        //         <div class='col-6 border'>" . $dataSebelumnya['kode'] . " " . $dataSebelumnya['keterangan'] . "</div>
        //         <div class='col-6 border'>" . $dataSurat['kode'] . " " . $dataSurat['keterangan'] . "</div>";
        //     $linimasa = true;
        //     $aktivitas = 'mengubah arsip surat dari ARKAB';
        //   }
        //   if ($linimasa == true) {
        //     $newId = $id;
        //     $timeline = [
        //       'icon'        => 'fas fa-edit bg-warning',
        //       'id_table'    => $newId,
        //       'table'       => 'wbk_rencana_kerja',
        //       'id_user'     => $by['id_user'],
        //       'aktivitas'   => $aktivitas,
        //       'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah melakukan perubahan arsip surat dengan nomor " . $dataSurat['nomor_surat'] . " untuk pegawai bernama " . $dataSurat['nama'] . "<div class='row pl-4 pr-4 pt-2'>" . $elemen . "</div>",
        //     ];
        //   }
        // } else {
        //   // Tambah
        //   $getPegawai = $this->dataPegawaiModel->where('id_data_pegawai', $this->request->getVar("pegawai"))->first();
        //   $newId = $this->arkabSuratModel->getInsertID();
        //   $timeline = [
        //     'icon'        => 'fas fa-save bg-primary',
        //     'id_table'    => $newId,
        //     'table'       => 'wbk_rencana_kerja',
        //     'id_user'     => $by['id_user'],
        //     'aktivitas'   => 'menambahkan arsip surat di ARKAB',
        //     'keterangan'  => "<strong>" . session()->get('nama') . "</strong> telah menambahkan arsip surat dengan nomor surat " . $this->request->getVar("nomor_surat") . " untuk pegawai bernama " . $getPegawai['nama'],
        //   ];
        // }
        // $this->linimasaModel->save($timeline);
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

  public function hapus_dokumen($id)
  {
    if ($this->request->isAJAX()) {
      try {
        // Ambil data dokumen yang akan dihapus
        $dokumen = $this->lhkpnSptModel->find($id);
        
        if (!$dokumen) {
          return $this->response->setJSON([
            'error' => 'Data tidak ditemukan'
          ]);
        }

        // Hapus file dari server
        if ($dokumen['dokumen'] && file_exists('./dokumen_lhkpn_spt/' . $dokumen['dokumen'])) {
          unlink('./dokumen_lhkpn_spt/' . $dokumen['dokumen']);
        }

        // Hapus data dari database
        if ($this->lhkpnSptModel->delete($id)) {
          return $this->response->setJSON([
            'success' => 'Data berhasil dihapus'
          ]);
        } else {
          return $this->response->setJSON([
            'error' => 'Gagal menghapus data dari database'
          ]);
        }
      } catch (\Exception $e) {
        return $this->response->setJSON([
          'error' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
      }
    }
  }

  public function download_zip($year)
  {
      $this->lhkpnSptModel
        ->select('data_pegawai.nama as nama_pegawai, lhkpn_spt.*')
        ->join('data_pegawai', 'id_data_pegawai')
        ->join('user', 'user.id_user=data_pegawai.id_user');

      if (session()->get('role') != "Admin") {
        $this->lhkpnSptModel->where('user.username', session()->get('username'));
      }

      $dokumen = $this->lhkpnSptModel->where('YEAR(tanggal_dokumen)', $year)->findAll();

      if (empty($dokumen)) {
          return redirect()->back()->with('error', 'Tidak ada dokumen untuk tahun tersebut.');
      }

      if (class_exists('ZipArchive')) {
          $zip = new \ZipArchive();
          $zipName = 'dokumen_lhkpn_spt_' . $year . '.zip';
          
          // Use system temp file
          $tempFile = tempnam(sys_get_temp_dir(), 'zip');

          if ($zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
              foreach ($dokumen as $data) {
                  $filePath = './dokumen_lhkpn_spt/' . $data['dokumen'];
                  if (file_exists($filePath) && is_file($filePath)) {
                      // Add file to zip with original name
                      $zip->addFile($filePath, $data['dokumen']);
                  }
              }
              $zip->close();

              if (file_exists($tempFile)) {
                  header('Content-Type: application/zip');
                  header('Content-Disposition: attachment; filename="' . $zipName . '"');
                  header('Content-Length: ' . filesize($tempFile));
                  readfile($tempFile);
                  unlink($tempFile); // Delete temp file after download
                  exit;
              } else {
                  return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
              }
          } else {
              return redirect()->back()->with('error', 'Gagal membuka file ZIP.');
          }
      } else {
          // Fallback if ZipArchive is not installed
          $zip = new \App\Libraries\SimpleZip();
          foreach ($dokumen as $data) {
              $filePath = './dokumen_lhkpn_spt/' . $data['dokumen'];
              if (file_exists($filePath) && is_file($filePath)) {
                  $zip->addFile($filePath, $data['dokumen']);
              }
          }
          $zip->download('dokumen_lhkpn_spt_' . $year . '.zip');
      }
  }
}
