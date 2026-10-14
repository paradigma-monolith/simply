<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Import extends BaseController
{
  public function importing()
  {
    echo '<form action="#" class="form-relaxtime-import-pagu" method="post" autocomplete="off" enctype="multipart/form-data">';
    echo '<input type="file" name="file">';
    echo '<input type="submit">';
    echo '</form>';
  }
  public function import()
  {
    if ($this->request->getFile('file')) {
      $file = $this->request->getFile('file');

      $fileExtension = $file->getClientExtension();

      $allowedExtensions = ['xls', 'csv', 'xlsx'];

      if (in_array($fileExtension, $allowedExtensions)) {
        $fileName = $file->getRandomName();
        $file->move(ROOTPATH . 'writable/uploads', $fileName);

        $fileType = IOFactory::identify(ROOTPATH . 'writable/uploads/' . $fileName);
        $reader = IOFactory::createReader($fileType);

        $spreadsheet = $reader->load(ROOTPATH . 'writable/uploads/' . $fileName);

        unlink(ROOTPATH . 'writable/uploads/' . $fileName);

        $data = $spreadsheet->getActiveSheet()->toArray();
        $by = $this->userModel->where("username", session()->get("username"))->first();

        $label = 0;
        $dataUpdate = 0;
        foreach ($data as $column) {
          $nip = preg_replace("/[^0-9]/", "", $column[0]);
          $cek = $this->dataPegawaiModel->where('nip', $nip)
            ->join('jabatan', 'id_jabatan')
            ->join('golongan', 'id_golongan')
            ->first();

          $label++;
          if ($cek) {
            $elemen = '';
            $aktivitas = '';
            $dataBaru = [];
            if ($cek['golongan'] != $column[1] || $cek['jabatan'] != $column[2]) {
              $dataBaru = [
                'id_data_pegawai' => $cek['id_data_pegawai'],
                'updated_by' => $by['id_user']
              ];
            }

            if ($cek['golongan'] != $column[1]) {
              $elemen .= "<div class='col-12'><strong>Golongan</strong></div>
                        <div class='col-6 border'>" . $cek['golongan'] . "</div>
                        <div class='col-6 border'>" . $column[1] . "</div>";
              $aktivitas = 'melakukan perubahan data profile pegawai bernama <strong>' . name_capitalize($cek['nama']) . '</strong>';
              $cekGolongan = $this->golonganModel->where('golongan', $column[1])->first();
              if ($cekGolongan) {
                $dataBaru += [
                  'id_golongan' => $cekGolongan['id_golongan'],
                ];
              } else {
                $golongan = [
                  'golongan' => $column[1],
                ];
                if ($this->golonganModel->save($golongan)) {
                  $dataBaru += [
                    'id_golongan' => $this->golonganModel->getInsertID(),
                  ];
                }
              }
            }

            if ($cek['jabatan'] != $column[2]) {
              $elemen .= "<div class='col-12'><strong>Jabatan</strong></div>
                        <div class='col-6 border'>" . $cek['jabatan'] . "</div>
                        <div class='col-6 border'>" . $column[2] . "</div>";
              $aktivitas = 'melakukan perubahan data profile pegawai bernama <strong>' . name_capitalize($cek['nama']) . '</strong>';
              $cekJabatan = $this->jabatanModel->where('jabatan', $column[2])->first();
              if ($cekJabatan) {
                $dataBaru += [
                  'id_jabatan' => $cekJabatan['id_jabatan'],
                ];
              } else {
                $jabatan = [
                  'jabatan' => $column[2],
                ];
                if ($this->jabatanModel->save($jabatan)) {
                  $dataBaru += [
                    'id_jabatan' => $this->jabatanModel->getInsertID(),
                  ];
                }
              }
            }
            if ($dataBaru != []) {
              if ($this->dataPegawaiModel->save($dataBaru)) {
                $dataUpdate++;
                $timeline = [
                  'icon'        => 'fas fa-user-edit bg-warning',
                  'id_table'    => $cek['id_data_pegawai'],
                  'table'       => 'data_pegawai',
                  'id_user'     => $by['id_user'],
                  'aktivitas'   => $aktivitas,
                  'keterangan'  => "memperbaharui profile pegawai" . "
                                <div class='row pl-4 pr-4 pt-2'>
                                " . $elemen . "
                                </div>"
                ];
                $this->linimasaModel->save($timeline);
              }
            }
          } else {
          }
        }
        d("Data Update : " . $dataUpdate);
        dd("Total Data : " . $label);
      }
    }
  }
}
