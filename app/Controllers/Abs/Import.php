<?php

namespace App\Controllers\Abs;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Import extends BaseController
{
  public function downloadTemplate()
  {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Master Aset');

    // Set headers
    $headers = [
      'No', 'Jenis BMN', 'Kode Satker', 'Nama Satker', 'Kode Barang', 'NUP', 
      'Nama Barang', 'Status BMN', 'Merk', 'Tipe', 'Kondisi', 'Umur Aset', 
      'Intra / Extra', 'Jenis Dokumen', 'No Dokumen', 'No Polisi', 
      'Status Sertifikasi', 'No Sertifikat', 'Tanggal Buku Pertama', 
      'Tanggal Perolehan', 'Tanggal Pengapusan', 'Nilai Perolehan Pertama', 
      'Nilai Mutasi', 'Nilai Perolehan', 'Nilai Penyusutan', 'Nilai Buku', 
      'Luas Tanah Seluruhnya', 'Luas Bangunan', 'Status Penggunaan', 'Alamat', 
      'RT/RW', 'Kelurahan/Desa', 'Kecamatan', 'Kab/Kota', 'Provinsi', 'Kode Pos', 
      'Kode Register'
    ];

    $col = 'A';
    foreach ($headers as $header) {
      $sheet->setCellValue($col . '1', $header);
      $sheet->getColumnDimension($col)->setAutoSize(true);
      $col++;
    }

    $lastCol = $col;
    $lastCol--; // Because $col++ happened after the last iteration

    // Styling header
    $headerStyle = [
      'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
      'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FF4CAF50']],
      'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);

    $writer = new Xlsx($spreadsheet);
    $filename = 'Template_Import_Aset.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit();
  }

  public function importing()
  {
    // echo '<form action="#" class="form-relaxtime-import-pagu" method="post" autocomplete="off" enctype="multipart/form-data">';
    // echo '<input type="text" name="jenis">';
    // echo '<input type="file" name="file">';
    // echo '<input type="submit">';
    // echo '</form>';
    return view('/abs/uploadFileInvenaris');
  }
  public function import()
  {
    if ($this->request->getFile('file')) {
      $file = $this->request->getFile('file');
      $by = $this->userModel->where('username', session()->get('username'))->first();

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

        $label = false;
        $getLabel = [];
        $getBarang = [];
        $validationNumber = 0;
        $cekValidation = ['NUP', 'Kode Barang', 'Kode Satker'];
        
        $countInsert = 0;
        $countUpdate = 0;
        $countIgnore = 0;
        
        $this->db = \Config\Database::connect();
        $this->db->transStart();

        foreach ($data as $column) {
          if ($label == false) {
            $label = true;
            $getLabel = [];

            foreach ($column as $index => $cell) {
              $cellStr = trim((string)$cell);
              if (in_array($cellStr, $cekValidation)) {
                  $validationNumber++;
              }
              $getLabel[$index] = $cellStr;
            }
          } else {
            if ($validationNumber >= 3) {
              $getBarang = [];
              $kodeSatker = '';
              $kodeBarang = '';
              $kodeNUP = '';
              $jenisBMN = 'Lainnya'; // Default jenis BMN
              
              // Cek jika baris kosong semua
              $isEmptyRow = true;
              foreach ($column as $cell) {
                if (trim((string)$cell) !== '') {
                  $isEmptyRow = false;
                  break;
                }
              }
              if ($isEmptyRow) continue;

              foreach ($getLabel as $i => $labelName) {
                if ($labelName === '') continue; // Skip header kosong
                
                $cellValue = isset($column[$i]) ? $column[$i] : '';
                $cellStr = trim((string)$cellValue);
                
                // Handling null_values ("nan", "-", " ", "")
                if (in_array(strtolower($cellStr), ["nan", "-", ""])) {
                    $cellValue = '';
                }
                
                if ($labelName == 'Kode Satker') {
                  $kodeSatker = trim((string)$cellValue);
                }
                if ($labelName == 'Kode Barang') {
                  $kodeBarang = trim((string)$cellValue);
                }
                if ($labelName == 'NUP') {
                  $kodeNUP = trim((string)$cellValue);
                }
                if ($labelName == 'Jenis BMN') {
                  $jenisBMN = trim((string)$cellValue) !== '' ? trim((string)$cellValue) : 'Lainnya';
                }

                $getBarang[$labelName] = $cellValue;
              }
              
              $qrCode = $kodeSatker . '*' . $kodeBarang . '*' . $kodeNUP;
              
              if(empty($kodeSatker) || empty($kodeBarang) || empty($kodeNUP)){
                continue;
              }
              
              // Handle Jenis BMN / Kategori
              $id_abs_jenis = 0;
              $cekABSJenis = $this->absJenisModel->where('jenis', $jenisBMN)->first();
              
              if ($cekABSJenis) {
                $id_abs_jenis = $cekABSJenis['id_abs_jenis'];
              } else {
                $dataJenis = [
                  'jenis' => $jenisBMN,
                  'created_by' => $by['id_user'],
                  'updated_by' => $by['id_user']
                ];
                if ($this->absJenisModel->save($dataJenis)) {
                  $id_abs_jenis = $this->absJenisModel->getInsertID();
                }
              }
              
              // Membersihkan $getLabel yang kosong sebelum diserialisasi
              $cleanLabel = array_values(array_filter($getLabel, function($val) { return $val !== ''; }));

              $cek = $this->absLokasiModel->where('kode_qr_code_pusat', $qrCode)->first();
              if (!$cek) {
                $saveData = [
                  'id_unit_kerja' => '0',
                  'id_abs_lantai' => '0',
                  'id_abs_ruang' => '0',
                  'id_abs_jenis' => $id_abs_jenis,
                  'data_label' => serialize($cleanLabel),
                  'data_barang' => serialize($getBarang),
                  'kode_qr_code_pusat' => $qrCode,
                  'kode_qr_code_internal' => uniqid('abs_', true),
                  'status_tersedia' => 'Tidak Tersedia',
                  'created_by' => $by['id_user'],
                  'updated_by' => $by['id_user'],
                ];
                $this->absLokasiModel->save($saveData);
                $countInsert++;
              } else {
                // Update data if there are changes
                $existingDataBarang = unserialize($cek['data_barang']);
                $hasChange = false;
                
                // Compare existing data with new data
                if(is_array($existingDataBarang)){
                    foreach($getBarang as $key => $val){
                        if(!array_key_exists($key, $existingDataBarang) || $existingDataBarang[$key] != $val){
                            $hasChange = true;
                            break;
                        }
                    }
                    
                    // Also check if any label was removed/changed
                    if(!$hasChange && count($existingDataBarang) != count($getBarang)){
                        $hasChange = true;
                    }
                } else {
                    $hasChange = true;
                }

                if($hasChange){
                    $updateData = [
                        'id_abs_bangunan' => $cek['id_abs_bangunan'],
                        'id_abs_jenis' => $id_abs_jenis, // Update kategori jika berubah
                        'data_label' => serialize($cleanLabel),
                        'data_barang' => serialize($getBarang),
                        'updated_by' => $by['id_user'],
                    ];
                    $this->absLokasiModel->save($updateData);
                    $countUpdate++;
                } else {
                    $countIgnore++;
                }
              }
            } else {
              $this->db->transRollback();
              session()->setFlashdata('error', "Gagal melakukan import, pastikan file Excel memiliki kolom: 'NUP', 'Kode Barang', dan 'Kode Satker'.");
              return redirect()->to('/abs/upload-file');
            }
          }
        }
        
        if ($validationNumber >= 3) {
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                session()->setFlashdata('error', "Terjadi kesalahan saat menyimpan data ke database.");
                return redirect()->to('/abs/upload-file');
            } else {
                $msg = "Import Selesai!<br/>" .
                       "Data Ditambah: " . $countInsert . "<br/>" .
                       "Data Diupdate: " . $countUpdate . "<br/>" .
                       "Data Diabaikan (Tidak ada perubahan): " . $countIgnore;
                session()->setFlashdata('success', $msg);
                return redirect()->to('/abs/data_barang');
            }
        }
      } else {
        session()->setFlashdata('error', "Format file tidak didukung! Gunakan .xls, .xlsx, atau .csv.");
        return redirect()->to('/abs/upload-file');
      }
    } else {
      session()->setFlashdata('error', "Harap pilih file terlebih dahulu.");
      return redirect()->to('/abs/upload-file');
    }
  }
}
