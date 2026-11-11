<?php

namespace App\Controllers\PengawasanKapal;

use App\Controllers\BaseController;
use App\Models\PengawasanKapal\PengawasanKapalModel;
use App\Models\PengawasanKapal\PengawasanKapalAksesModel;
use App\Models\PengawasanKapal\PengawasanKapalTargetAksesModel;
use App\Models\PengawasanKapal\MonitoringSanitasiKapalModel;
use App\Models\Master\UserModel;

class Data extends BaseController
{
  public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
  {
    // Call parent initController first
    parent::initController($request, $response, $logger);
    
    // Ensure models are initialized (they should be from BaseController, but double-check)
    if (!isset($this->pengawasanKapalModel)) {
      $this->pengawasanKapalModel = new PengawasanKapalModel();
    }
    if (!isset($this->pengawasanKapalAksesModel)) {
      $this->pengawasanKapalAksesModel = new PengawasanKapalAksesModel();
    }
    if (!isset($this->pengawasanKapalTargetAksesModel)) {
      $this->pengawasanKapalTargetAksesModel = new PengawasanKapalTargetAksesModel();
    }
    if (!isset($this->monitoringSanitasiKapalModel)) {
      $this->monitoringSanitasiKapalModel = new MonitoringSanitasiKapalModel();
    }
    if (!isset($this->userModel)) {
      $this->userModel = new UserModel();
    }
  }
  public function pengawasan_kapal()
  {
    $data['activeMenu'] = "pengawasanKapal";
    $data['title'] = "Digitalisasi Pengawasan Kekarantinaan Kesehatan di Pelabuhan";

    return view('pengawasan_kapal/pengawasan_kapal', $data);
  }

  public function detail_pengawasan_kapal($id = null)
  {
    $data['activeMenu'] = "pengawasanKapal";
    $data['title'] = "Pengawasan Kapal";
    $data['pengawasanKapal'] = $this->pengawasanKapalModel
      ->where('id', $id)
      ->first();
    // dd($data);

    return view('pengawasan_kapal/detail_pengawasan_kapal', $data);
  }

  public function monitoring_sanitasi_kapal()
  {
    $data['activeMenu'] = "monitoringSanitasiKapal";
    $data['title'] = "Monitoring Sanitasi Kapal";
    
    // Force refresh session data for unit_kerja if not present
    if (!session()->get('unit_kerja')) {
        $username = session()->get('username');
        if ($username) {
            $user = $this->userModel
                ->join('data_pegawai', 'user.id_user=data_pegawai.id_user', 'left')
                ->join('unit_kerja', 'unit_kerja.id_unit_kerja=data_pegawai.id_unit_kerja', 'left')
                ->where('username', $username)->first();
                
            if ($user && isset($user['unit_kerja'])) {
                session()->set('unit_kerja', $user['unit_kerja']);
            }
        }
    }

    return view('pengawasan_kapal/monitoring_sanitasi_kapal', $data);
  }

  public function data_pengawasan_kapal()
  {
    if ($this->request->isAJAX()) {
      $data['activeMenu'] = "pengawasanKapal";
      $data['title'] = "Pengawasan Kapal";

      $dateRange = $this->request->getVar('dateRange'); // Ambil dateRange dari request

      $pengawasanKapalQuery = $this->pengawasanKapalModel;
      $data['lokasiArray'] = $this->pengawasanKapalTargetAksesModel
            ->join('data_pegawai', 'data_pegawai.id_data_pegawai=pengawasan_kapal_target_akses.id_data_pegawai')
            ->where('data_pegawai.nama', session()->get('nama'))
            ->first();
            
      if(($data['lokasiArray'] && $data['lokasiArray']['lokasi'] != null) || session()->get('role') == "Admin") {
        if($data['lokasiArray']) {
            $data['lokasiArray'] = json_decode($data['lokasiArray']['lokasi'],true);
            
            $data['unitKerjaArray'] = null;
                $lokasiData = $this->unitKerjaModel->whereIn('id_unit_kerja', $data['lokasiArray'])->findAll();
                $data['unitKerjaArray'] = array_column($lokasiData, 'lokasi');
                
                // Map lokasi to pelabuhan values (handle case sensitivity and name differences)
                $pelabuhanArray = [];
                foreach ($data['unitKerjaArray'] as $lokasi) {
                    switch (strtolower($lokasi)) {
                        case 'cirebon':
                            $pelabuhanArray[] = 'cirebon';
                            break;
                        case 'indramayu':
                            $pelabuhanArray[] = 'Indramayu';
                            break;
                        case 'subang':
                            $pelabuhanArray[] = 'patimban';
                            break;
                        default:
                            $pelabuhanArray[] = $lokasi;
                            break;
                    }
                }
                
            $pengawasanKapalQuery = $this->pengawasanKapalModel
              ->whereIn('pelabuhan', $pelabuhanArray);
        }
        
        // Jika ada filter tanggal, gunakan rentang tanggal
        if ($dateRange) {
            [$startDate, $endDate] = explode(' - ', $dateRange);
            $pengawasanKapalQuery->where('DATE(tbl_pasien.date_updated) >=', $startDate)
              ->where('DATE(tbl_pasien.date_updated) <=', $endDate);
        }
        
        // Selalu urutkan berdasarkan date_updated terbaru
        $data['pengawasanKapal'] = $pengawasanKapalQuery->orderBy('tbl_pasien.date_updated DESC')->findAll();
        
        $msg['data'] = view("pengawasan_kapal/data_pengawasan_kapal", $data);
        $msg['pengawasanKapal'] = $data['pengawasanKapal']; // Kirim data mentah untuk infografis
      } else {
        $msg['redirect'] = '/home';
      }
      
      
      echo json_encode($msg);
    }
  }

  public function pengawasan_kapal_akses()
  {
    $data['title'] = "Pengaturan Akses Pengawasan Kapal";
    $data['activeMenu'] = "pengawasanKapalAkses";

    $data['akses'] = $this->userModel
      ->join('pengawasan_kapal_akses', 'pengawasan_kapal_akses.id_user=user.id_user', 'LEFT')
      ->where("user.username", session()->get('username'))
      ->first();

    if ($data['akses']['id_pengawasan_kapal_akses']) {
      if ($data['akses']['status'] == 'Akses Diblokir') {
        $data['activeMenu'] = "pengawasanKapalBlockir";
      }
    } else {
      $data['activeMenu'] = "pengawasanKapalBlockir";
    }

    $data['user'] = $this->userModel
      ->select('user.id_user, user.nama, user.id_user, pengawasan_kapal_akses.id_pengawasan_kapal_akses, pengawasan_kapal_akses.status')
      ->join('pengawasan_kapal_akses', 'pengawasan_kapal_akses.id_user=user.id_user', 'LEFT')
      ->where("user.status_pegawai_aktif", "Aktif")
      ->orderBy("user.nama", "ASC")
      ->findAll();

    $data['targetAkses'] = $this->pengawasanKapalTargetAksesModel
      ->join('data_pegawai', 'id_data_pegawai', 'right')
      ->where('status_bekerja', 'Aktif')
      ->orderBy("data_pegawai.nama", "ASC")
      ->findAll();
    $data['unitKerja'] = $this->unitKerjaModel->findAll();

    // dd($data);

    return view("pengawasan_kapal/pengawasan_kapal_akses", $data);
  }

  public function export_excel()
  {
    // Load PhpSpreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set headers
    $headers = [
      'A1' => 'No',
      'B1' => 'Waktu Submit',
      'C1' => 'Nama Kapal',
      'D1' => 'Datang Dari',
      'E1' => 'Ke Pelabuhan',
      'F1' => 'Negara Asal',
      'G1' => 'Gross Tonnage',
      'H1' => 'Kategori',
      'I1' => 'Skoring',
      'J1' => 'Faktor Resiko',
      'K1' => 'Status',
      'L1' => 'Verifikasi',
      'M1' => 'Q1 - Negara yang dikunjungi (14 hr terakhir)',
      'N1' => 'Q2 - Orang meninggal bukan karena kecelakaan',
      'O1' => 'Q3 - Crew dievakuasi karena sakit/meninggal',
      'P1' => 'Q4 - Kasus penyakit diduga menular',
      'Q1' => 'Q5 - Crew dengan gejala penyakit',
      'R1' => 'Q6 - Jumlah crew sakit lebih besar dari biasanya',
      'S1' => 'Q7 - Orang sakit diatas kapal saat ini',
      'T1' => 'Q8 - Crew sakit sudah dikonsulkan ke dokter',
      'U1' => 'Q9 - Kondisi penyebab infeksi/penyebaran penyakit',
      'V1' => 'Q10 - Pengolahan/penyimpanan makanan hygienis',
      'W1' => 'Q11 - Bahan makanan busuk/expired dipisahkan',
      'X1' => 'Q12 - Suplai air bersih diperiksa dan bersertifikat',
      'Y1' => 'Q13 - Air tidak berbau, berasa, berwarna',
      'Z1' => 'Q14 - Tempat sampah selalu tertutup',
      'AA1' => 'Q15 - Limbah B3 dikelola dengan baik',
      'AB1' => 'Q16 - Terdapat tikus/kecoa/binatang pembawa penyakit',
      'AC1' => 'Q17 - Tindakan sanitasi dalam 1 bulan terakhir',
      'AD1' => 'Q18 - Penumpang gelap/crew tidak terdaftar',
      'AE1' => 'Q19 - Hewan piaraan sakit',
      'AF1' => 'X1 - Maritime Declaration of Health',
      'AG1' => 'X2 - Dokumen Sertifikat Sanitasi',
      'AH1' => 'X3 - Sertifikat P3K/obat-obatan',
      'AI1' => 'X4 - Buku Kesehatan',
      'AJ1' => 'X5 - Last port clearance',
      'AK1' => 'X6 - Dokumen vaksinasi crew',
      'AL1' => 'X7 - Crew list dan pengukuran suhu tubuh'
    ];
    
    foreach ($headers as $cell => $value) {
      $sheet->setCellValue($cell, $value);
    }
    
    // Style headers
    $sheet->getStyle('A1:AL1')->getFont()->setBold(true);
    $sheet->getStyle('A1:AL1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
    
    // Get data with date filter if provided
    $dateRange = $this->request->getVar('dateRange');
    $pengawasanKapalQuery = $this->pengawasanKapalModel;

    // Apply location filter if not admin
    $scope = $this->resolvePengawasanKapalLocationScope();

    if ($scope['authorized']) {
      if ($scope['restrictToPorts']) {
        if (empty($scope['pelabuhanArray'])) {
          $pengawasanKapal = [];
        } else {
          $pengawasanKapalQuery = $pengawasanKapalQuery->whereIn('pelabuhan', $scope['pelabuhanArray']);
        }
      }

      if (!isset($pengawasanKapal)) {
        // Apply date filter
        if ($dateRange) {
          [$startDate, $endDate] = explode(' - ', $dateRange);
          $pengawasanKapalQuery->where('DATE(tbl_pasien.date_updated) >=', $startDate)
            ->where('DATE(tbl_pasien.date_updated) <=', $endDate);
        }

        $pengawasanKapal = $pengawasanKapalQuery->orderBy('tbl_pasien.date_updated DESC')->findAll();
      }
      
      // Fill data
      $row = 2;
      $no = 1;
      foreach ($pengawasanKapal as $data) {
        // Determine category based on Master's Statement Letter upload
        $kategori = 'Dalam Negeri';
        if (isset($data['surat_pernyataan_nahkoda']) && !empty($data['surat_pernyataan_nahkoda'])) {
          $kategori = 'Luar Negeri';
        }
        
        $sheet->setCellValue('A' . $row, $no++);
        // Handle timestamp field
        $waktuSubmit = '-';
        if (isset($data['created_at'])) {
          $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['created_at']));
        } elseif (isset($data['date_created'])) {
          $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['date_created']));
        } elseif (isset($data['created_date'])) {
          $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['created_date']));
        } elseif (isset($data['date_updated'])) {
          $waktuSubmit = date('d/m/Y H:i:s', strtotime($data['date_updated']));
        }
        $sheet->setCellValue('B' . $row, $waktuSubmit);
        $sheet->setCellValue('C' . $row, $data['nama']);
        $sheet->setCellValue('D' . $row, $data['datang_dari']);
        $sheet->setCellValue('E' . $row, $data['pelabuhan']);
        $sheet->setCellValue('F' . $row, isset($data['origin_country']) ? $data['origin_country'] : '-');
        $sheet->setCellValue('G' . $row, isset($data['gross_tonnage']) ? $data['gross_tonnage'] : '-');
        $sheet->setCellValue('H' . $row, $kategori);
        $sheet->setCellValue('I' . $row, $data['skoring']);
        
        // Calculate kresiko if empty
        $kresiko = $data['kresiko'];
        if (empty($kresiko) && isset($data['skoring'])) {
          $skoring = (int)$data['skoring'];
          if ($skoring >= 20) {
            $kresiko = 'TINGGI';
          } elseif ($skoring >= 10 && $skoring <= 19) {
            $kresiko = 'SEDANG';
          } else {
            $kresiko = 'RENDAH';
          }
        }
        $sheet->setCellValue('J' . $row, $kresiko);
        
        // Calculate hasilStatus if empty
        $hasilStatus = $data['hasilStatus'];
        
        // Convert old KONFIRMASI status to SANDAR
        if ($hasilStatus == 'KONFIRMASI') {
            $hasilStatus = 'SANDAR';
        }
        
        if (empty($hasilStatus) && isset($data['skoring'])) {
          $skoring = (int)$data['skoring'];
          if ($skoring >= 20) {
            $hasilStatus = 'ZONA KARANTINA';
          } else {
            $hasilStatus = 'SANDAR';
          }
        }
        $sheet->setCellValue('K' . $row, $hasilStatus);
        
        // Verifikasi column
        $verifikasiText = '-';
        $hasVerificationInfo = (!empty($data['catatan_verifikasi']) || !empty($data['verified_by']));
        if ($hasVerificationInfo) {
            $verText = '';
            if (!empty($data['verified_by'])) {
                $verText .= 'Diverifikasi oleh: ' . $data['verified_by'];
                if (!empty($data['verified_at'])) {
                    $verText .= ' (' . date('d/m/Y H:i', strtotime($data['verified_at'])) . ')';
                }
            }
            if (!empty($data['catatan_verifikasi'])) {
                if (!empty($verText)) $verText .= "\n";
                $verText .= 'Catatan: ' . $data['catatan_verifikasi'];
            }
            $verifikasiText = $verText;
        }
        $sheet->setCellValue('L' . $row, $verifikasiText);
        $sheet->getStyle('L' . $row)->getAlignment()->setWrapText(true);
        
        // Self assessment questions mapping
        $sheet->setCellValue('M' . $row, isset($data['negara']) ? $data['negara'] : (isset($data['q1']) ? $data['q1'] : '-'));
        $sheet->setCellValue('N' . $row, (isset($data['q2']) && $data['q2'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('O' . $row, (isset($data['q3']) && $data['q3'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('P' . $row, (isset($data['q4']) && $data['q4'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('Q' . $row, (isset($data['q5']) && $data['q5'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('R' . $row, (isset($data['q6']) && $data['q6'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('S' . $row, (isset($data['q7']) && $data['q7'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('T' . $row, (isset($data['q8']) && $data['q8'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('U' . $row, (isset($data['q9']) && $data['q9'] === '20') ? 'Ya' : 'Tidak');
        
        $sheet->setCellValue('V' . $row, (isset($data['q10']) && $data['q10'] === '0') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('W' . $row, (isset($data['q11']) && $data['q11'] === '0') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('X' . $row, (isset($data['q12']) && $data['q12'] === '0') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('Y' . $row, (isset($data['q13']) && $data['q13'] === '0') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('Z' . $row, (isset($data['q14']) && $data['q14'] === '0') ? 'Ya' : 'Tidak');
        
        $sheet->setCellValue('AA' . $row, (isset($data['q15']) && $data['q15'] === '0') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('AB' . $row, (isset($data['q16']) && $data['q16'] === '20') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('AC' . $row, (isset($data['q17']) && $data['q17'] === '5') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('AD' . $row, (isset($data['q18']) && $data['q18'] === '10') ? 'Ya' : 'Tidak');
        $sheet->setCellValue('AE' . $row, (isset($data['q19']) && $data['q19'] === '10') ? 'Ya' : 'Tidak');
        
        // Documents X1 to X7
        $sheet->setCellValue('AF' . $row, (isset($data['x1']) && $data['x1'] === '1') ? 'Ada' : 'Tidak Ada');
        $sheet->setCellValue('AG' . $row, (isset($data['x2']) && $data['x2'] === '1') ? 'Ada' : 'Tidak Ada');
        $sheet->setCellValue('AH' . $row, (isset($data['x3']) && $data['x3'] === '1') ? 'Ada' : 'Tidak Ada');
        $sheet->setCellValue('AI' . $row, (isset($data['x4']) && $data['x4'] === '1') ? 'Ada' : 'Tidak Ada');
        $sheet->setCellValue('AJ' . $row, (isset($data['x5']) && $data['x5'] === '1') ? 'Ada' : 'Tidak Ada');
        $sheet->setCellValue('AK' . $row, (isset($data['x6']) && $data['x6'] === '1') ? 'Ada' : 'Tidak Ada');
        $sheet->setCellValue('AL' . $row, (isset($data['x7']) && $data['x7'] === '1') ? 'Ada' : 'Tidak Ada');

        $row++;
      }
      
      // Auto-size columns
      $allColumns = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                     'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL'];
      foreach($allColumns as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
      }
      
      // Create filename
      $filename = 'Data_Pengawasan_Kapal_' . date('Y-m-d_H-i-s') . '.xlsx';
      
      // Set headers for download
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      
      $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
      $writer->save('php://output');
      exit;
    } else {
      return redirect()->to('/home');
    }
  }

  public function verify_status()
  {
    // Check if user is logged in using the correct session variable
    if (!session()->get('username')) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Unauthorized access'
      ]);
    }

    // Check if user has access to pengawasan kapal functionality
    $lokasiArray = $this->pengawasanKapalTargetAksesModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=pengawasan_kapal_target_akses.id_data_pegawai')
      ->where('data_pegawai.nama', session()->get('nama'))
      ->first();
      
    // Only allow access if user has location access or is Admin
    if (!($lokasiArray && $lokasiArray['lokasi'] != null) && session()->get('role') != "Admin") {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Akses tidak diizinkan'
      ]);
    }

    // Get POST data - support both kapal_id (from data page) and id_pengawasan (from detail page)
    $kapalId = $this->request->getPost('kapal_id') ?: $this->request->getPost('id_pengawasan');
    $statusManual = $this->request->getPost('status_manual');
    $catatanVerifikasi = $this->request->getPost('catatan_verifikasi');

    // Validate required fields
    if (empty($kapalId) || empty($statusManual)) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Data tidak lengkap'
      ]);
    }

    // Validate status options
    $validStatuses = ['SANDAR', 'ZONA KARANTINA'];
    if (!in_array($statusManual, $validStatuses)) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Status tidak valid'
      ]);
    }

    try {
      // Get current user info using the correct session variables
      $userName = session()->get('nama') ?? session()->get('username');

      // Ambil data existing untuk mempertahankan date_updated
      $existingData = $this->pengawasanKapalModel->find($kapalId);
      if (!$existingData) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Data tidak ditemukan'
        ]);
      }

      // Update the record with verification data, preserving original date_updated
      $updateData = [
        'hasilStatus' => $statusManual,
        'status_verifikasi' => 'verified',
        'catatan_verifikasi' => $catatanVerifikasi,
        'verified_by' => $userName,
        'verified_at' => date('Y-m-d H:i:s'),
        'date_updated' => $existingData['date_updated'] // Pertahankan date_updated asli
      ];

      $result = $this->pengawasanKapalModel->update($kapalId, $updateData);

      if ($result) {
        return $this->response->setJSON([
          'success' => true,
          'message' => 'Status kapal berhasil diverifikasi'
        ]);
      } else {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Gagal menyimpan verifikasi'
        ]);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function delete_data()
  {
    // Initialize models if not already done
    if (!isset($this->pengawasanKapalModel)) {
      $this->pengawasanKapalModel = new PengawasanKapalModel();
    }
    if (!isset($this->pengawasanKapalTargetAksesModel)) {
      $this->pengawasanKapalTargetAksesModel = new PengawasanKapalTargetAksesModel();
    }
    
    // Check if user is logged in
    if (!session()->get('username')) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Unauthorized access'
      ]);
    }

    // Check if user has access to pengawasan kapal functionality
    $lokasiArray = $this->pengawasanKapalTargetAksesModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=pengawasan_kapal_target_akses.id_data_pegawai')
      ->where('data_pegawai.nama', session()->get('nama'))
      ->first();
      
    // Only allow access if user has location access or is Admin
    if (!($lokasiArray && $lokasiArray['lokasi'] != null) && session()->get('role') != "Admin") {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Akses tidak diizinkan untuk menghapus data'
      ]);
    }

    // Get POST data
    $id = $this->request->getPost('id');

    // Validate required fields
    if (empty($id)) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'ID data tidak valid'
      ]);
    }

    try {
      // Check if data exists
      $existingData = $this->pengawasanKapalModel->find($id);
      if (!$existingData) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Data tidak ditemukan'
        ]);
      }

      // Delete the record
      $result = $this->pengawasanKapalModel->delete($id);

      if ($result) {
        return $this->response->setJSON([
          'success' => true,
          'message' => 'Data pengawasan kapal berhasil dihapus'
        ]);
      } else {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Gagal menghapus data'
        ]);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  // ========== LAYANAN KESEHATAN KAPAL METHODS ==========

  public function layanan_kesehatan_kapal()
  {
    $data['activeMenu'] = "layananKesehatanKapal";
    $data['title'] = "Layanan Kesehatan Kapal";
    
    // Check if user is logged in
    if (!session()->get('username')) {
      return redirect()->to('/auth/login');
    }

    $this->layananKesehatanKapalModel = new \App\Models\PengawasanKapal\LayananKesehatanKapalModel();
    $query = $this->layananKesehatanKapalModel->orderBy('created_at', 'DESC');

    // Handle Filters
    $start_date = $this->request->getGet('start_date');
    $end_date = $this->request->getGet('end_date');

    // Default to current year if no filter is applied
    if (!$start_date && !$end_date) {
        $start_date = date('Y-01-01'); // First day of the current year
        $end_date = date('Y-m-d', strtotime('+30 days'));    // Current date + 30 days
    }

    if ($start_date && $end_date) {
        $query->where('DATE(waktu_kedatangan) >=', $start_date)
              ->where('DATE(waktu_kedatangan) <=', $end_date);
    }

    $data['start_date'] = $start_date;
    $data['end_date'] = $end_date;
    $data['layananKesehatan'] = $query->findAll();

    return view('pengawasan_kapal/layanan_kesehatan_kapal', $data);
  }

  public function export_excel_layanan_kesehatan_kapal()
  {
    if (!session()->get('username')) {
      return redirect()->to('/auth/login');
    }

    $this->layananKesehatanKapalModel = new \App\Models\PengawasanKapal\LayananKesehatanKapalModel();
    $query = $this->layananKesehatanKapalModel->orderBy('created_at', 'DESC');

    $start_date = $this->request->getGet('start_date');
    $end_date = $this->request->getGet('end_date');

    // Default to current year if no filter is applied (should match the view)
    if (!$start_date && !$end_date) {
        $start_date = date('Y-01-01');
        $end_date = date('Y-m-d', strtotime('+30 days'));
    }

    if ($start_date && $end_date) {
        $query->where('DATE(waktu_kedatangan) >=', $start_date)
              ->where('DATE(waktu_kedatangan) <=', $end_date);
    }

    $layananKesehatan = $query->findAll();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Layanan Kesehatan Kapal');

    // Headers
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Waktu Kedatangan');
    $sheet->setCellValue('C1', 'Nama Kapal');
    $sheet->setCellValue('D1', 'Pelabuhan Asal');
    $sheet->setCellValue('E1', 'Pelabuhan Tujuan');
    $sheet->setCellValue('F1', 'Jumlah Crew');
    $sheet->setCellValue('G1', 'Buku Kesehatan Kapal');
    $sheet->setCellValue('H1', 'SSCEC/SSCC');
    $sheet->setCellValue('I1', 'Sertifikat P3K');
    $sheet->setCellValue('J1', 'PHQC');
    $sheet->setCellValue('K1', 'Pemeriksaan Kesehatan');
    $sheet->setCellValue('L1', 'Penerbitan SSCEC/SSCC');
    $sheet->setCellValue('M1', 'Penerbitan Sertifikat P3K');
    $sheet->setCellValue('N1', 'Layanan Lainnya');
    $sheet->setCellValue('O1', 'Fasilitasi Transportasi');
    $sheet->setCellValue('P1', 'Waktu Submit');

    // Make headers bold
    $sheet->getStyle('A1:P1')->getFont()->setBold(true);

    $row = 2;
    $no = 1;
    foreach ($layananKesehatan as $data) {
        $sheet->setCellValue('A' . $row, $no++);
        $sheet->setCellValue('B' . $row, date('d/m/Y H:i', strtotime($data['waktu_kedatangan'])));
        $sheet->setCellValue('C' . $row, $data['nama_kapal']);
        $sheet->setCellValue('D' . $row, $data['pelabuhan_asal']);
        $sheet->setCellValue('E' . $row, $data['pelabuhan_tujuan']);
        $sheet->setCellValue('F' . $row, $data['jumlah_crew']);
        $sheet->setCellValue('G' . $row, $data['dok_buku_kesehatan_kapal'] ? 'Ya' : 'Tidak');
        $sheet->setCellValue('H' . $row, $data['dok_sscec'] ? 'Ya (Exp: ' . ($data['dok_sscec_tgl_kadaluarsa'] ? date('d/m/Y', strtotime($data['dok_sscec_tgl_kadaluarsa'])) : '-') . ')' : 'Tidak');
        $sheet->setCellValue('I' . $row, $data['dok_p3k'] ? 'Ya (Exp: ' . ($data['dok_p3k_tgl_kadaluarsa'] ? date('d/m/Y', strtotime($data['dok_p3k_tgl_kadaluarsa'])) : '-') . ')' : 'Tidak');
        $sheet->setCellValue('J' . $row, $data['dok_phqc'] ? 'Ya' : 'Tidak');
        $sheet->setCellValue('K' . $row, $data['layanan_pemeriksaan_kesehatan'] ? 'Ya' : 'Tidak');
        $sheet->setCellValue('L' . $row, $data['layanan_penerbitan_sscec'] ? 'Ya' : 'Tidak');
        $sheet->setCellValue('M' . $row, $data['layanan_penerbitan_p3k'] ? 'Ya' : 'Tidak');
        $sheet->setCellValue('N' . $row, $data['layanan_lainnya'] ? $data['layanan_lainnya_sebutkan'] : 'Tidak');
        $sheet->setCellValue('O' . $row, $data['fasilitasi_transportasi']);
        $sheet->setCellValue('P' . $row, date('d/m/Y H:i:s', strtotime($data['created_at'])));
        $row++;
    }

    // Auto-size columns
    foreach(range('A','P') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $filename = 'Data_Layanan_Kesehatan_Kapal_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
  }

  public function edit_layanan_kesehatan_kapal($id = null)
  {
    $data['activeMenu'] = "layananKesehatanKapal";
    $data['title'] = "Edit Layanan Kesehatan Kapal";
    
    if (!session()->get('username')) {
      return redirect()->to('/auth/login');
    }

    $this->layananKesehatanKapalModel = new \App\Models\PengawasanKapal\LayananKesehatanKapalModel();
    $data['layanan'] = $this->layananKesehatanKapalModel->find($id);

    if (!$data['layanan']) {
      session()->setFlashdata('error', 'Data tidak ditemukan');
      return redirect()->to('/layanan_kesehatan_kapal');
    }

    return view('pengawasan_kapal/edit_layanan_kesehatan_kapal', $data);
  }

  public function update_layanan_kesehatan_kapal($id = null)
  {
    if (!session()->get('username')) {
      return redirect()->to('/auth/login');
    }

    $rules = [
        'nama_kapal' => 'required',
        'waktu_kedatangan' => 'required',
        'pelabuhan_asal' => 'required',
        'pelabuhan_tujuan' => 'required',
        'jumlah_crew' => 'required|numeric',
        'fasilitasi_transportasi' => 'required|in_list[Ya,Tidak]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $this->layananKesehatanKapalModel = new \App\Models\PengawasanKapal\LayananKesehatanKapalModel();
    
    try {
        $data = [
            'nama_kapal' => $this->request->getVar('nama_kapal'),
            'waktu_kedatangan' => $this->request->getVar('waktu_kedatangan'),
            'pelabuhan_asal' => $this->request->getVar('pelabuhan_asal'),
            'pelabuhan_tujuan' => $this->request->getVar('pelabuhan_tujuan'),
            'jumlah_crew' => $this->request->getVar('jumlah_crew'),
            'dok_buku_kesehatan_kapal' => $this->request->getVar('dok_buku_kesehatan_kapal') ? 1 : 0,
            'dok_sscec' => $this->request->getVar('dok_sscec') ? 1 : 0,
            'dok_sscec_tgl_kadaluarsa' => $this->request->getVar('dok_sscec_tgl_kadaluarsa') ?: null,
            'dok_p3k' => $this->request->getVar('dok_p3k') ? 1 : 0,
            'dok_p3k_tgl_kadaluarsa' => $this->request->getVar('dok_p3k_tgl_kadaluarsa') ?: null,
            'dok_phqc' => $this->request->getVar('dok_phqc') ? 1 : 0,
            'layanan_pemeriksaan_kesehatan' => $this->request->getVar('layanan_pemeriksaan_kesehatan') ? 1 : 0,
            'layanan_penerbitan_sscec' => $this->request->getVar('layanan_penerbitan_sscec') ? 1 : 0,
            'layanan_penerbitan_p3k' => $this->request->getVar('layanan_penerbitan_p3k') ? 1 : 0,
            'layanan_lainnya' => $this->request->getVar('layanan_lainnya') ? 1 : 0,
            'layanan_lainnya_sebutkan' => $this->request->getVar('layanan_lainnya_sebutkan'),
            'fasilitasi_transportasi' => $this->request->getVar('fasilitasi_transportasi'),
        ];

        // Handle file upload
        $fileSurat = $this->request->getFile('surat_permohonan');
        if ($fileSurat && $fileSurat->isValid() && !$fileSurat->hasMoved()) {
            $uploadPath = 'uploads/layanan_kesehatan_kapal/';
            if (!is_dir(FCPATH . $uploadPath)) {
                mkdir(FCPATH . $uploadPath, 0777, true);
            }

            $newName = $fileSurat->getRandomName();
            $fileSurat->move(FCPATH . $uploadPath, $newName);
            $data['surat_permohonan'] = $uploadPath . $newName;

            // Delete old file
            $oldData = $this->layananKesehatanKapalModel->find($id);
            if ($oldData['surat_permohonan'] && file_exists(FCPATH . $oldData['surat_permohonan'])) {
                unlink(FCPATH . $oldData['surat_permohonan']);
            }
        }

        $this->layananKesehatanKapalModel->update($id, $data);
        session()->setFlashdata('success', 'Data layanan kesehatan kapal berhasil diupdate!');
        return redirect()->to('/layanan_kesehatan_kapal');

    } catch (\Exception $e) {
        session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        return redirect()->back()->withInput();
    }
  }

  public function delete_layanan_kesehatan_kapal($id = null)
  {
    if (!session()->get('username')) {
      return redirect()->to('/auth/login');
    }

    $this->layananKesehatanKapalModel = new \App\Models\PengawasanKapal\LayananKesehatanKapalModel();
    $data = $this->layananKesehatanKapalModel->find($id);

    if (!$data) {
      session()->setFlashdata('error', 'Data tidak ditemukan');
      return redirect()->to('/layanan_kesehatan_kapal');
    }

    // Delete associated file if exists
    if (!empty($data['surat_permohonan']) && file_exists(FCPATH . $data['surat_permohonan'])) {
      @unlink(FCPATH . $data['surat_permohonan']);
    }

    if ($this->layananKesehatanKapalModel->delete($id)) {
      session()->setFlashdata('success', 'Data berhasil dihapus');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus');
    }

    return redirect()->to('/layanan_kesehatan_kapal');
  }

  // ========== MONITORING SANITASI KAPAL METHODS ==========

  public function data_monitoring_sanitasi_kapal()
  {
    if ($this->request->isAJAX()) {
      $filters = [];
      
      // Handle dateRange parameter from JavaScript
      $dateRange = $this->request->getVar('dateRange');
      if ($dateRange) {
        $dates = explode(' - ', $dateRange);
        if (count($dates) == 2) {
          $filters['start_date'] = trim($dates[0]);
          $filters['end_date'] = trim($dates[1]);
        }
      }
      
      // Handle other filters
      $filters['wilker'] = $this->request->getVar('wilker');
      $filters['nama_kapal'] = $this->request->getVar('nama_kapal');

      $data['monitoringSanitasi'] = $this->monitoringSanitasiKapalModel->getFilteredData($filters);
      $data['statistics'] = $this->monitoringSanitasiKapalModel->getStatistics($filters);

      return $this->response->setJSON([
        'success' => true,
        'data' => $data['monitoringSanitasi'],
        'statistics' => $data['statistics']
      ]);
    }

    return $this->response->setStatusCode(404);
  }

  public function get_wilker_options()
  {
    if ($this->request->isAJAX()) {
      $wilkerOptions = $this->monitoringSanitasiKapalModel->getUnitKerjaOptions();
      
      return $this->response->setJSON([
        'success' => true,
        'data' => $wilkerOptions
      ]);
    }

    return $this->response->setStatusCode(404);
  }

  public function user_data()
  {
    if ($this->request->isAJAX()) {
      $userId = session()->get('id_user');
      
      // Debug: log session data
      log_message('debug', 'Session data: ' . json_encode([
        'id_user' => session()->get('id_user'),
        'nama' => session()->get('nama'),
        'username' => session()->get('username')
      ]));
      
      if ($userId) {
        // Get user data from database
        $userData = $this->userModel->find($userId);
        
        if ($userData) {
          return $this->response->setJSON([
            'success' => true,
            'data' => [
              'nama' => $userData['nama'] ?? 'User Name',
              'id_user' => $userData['id_user'],
              'username' => $userData['username'] ?? '',
              'email' => $userData['email'] ?? ''
            ]
          ]);
        }
      }
      
      // Fallback to session data if user not found in database
      return $this->response->setJSON([
        'success' => true,
        'data' => [
          'nama' => session()->get('nama') ?? 'Default User',
          'id_user' => session()->get('id_user') ?? 0,
          'username' => session()->get('username') ?? 'default',
          'email' => session()->get('email') ?? 'default@email.com'
        ]
      ]);
    }

    return $this->response->setStatusCode(404);
  }

  public function save_monitoring_sanitasi_kapal()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(404);
    }

    // Get form data
    $jenisDokumen = $this->request->getPost('jenis_dokumen');
    $jenisDokumenStr = $jenisDokumen ? implode(',', $jenisDokumen) : null;

    $data = [
      'tanggal' => $this->request->getPost('tanggal'),
      'wilker' => $this->request->getPost('wilker'),
      'unit_kerja' => $this->request->getPost('unit_kerja'),
      'nama_kapal' => $this->request->getPost('nama_kapal'),
      'jenis_dokumen' => $jenisDokumenStr,
      'created_by' => session()->get('id_user'),
      'updated_by' => session()->get('id_user')
    ];

    // Handle file uploads
    $buktiGeotagging = $this->request->getFile('bukti_geotagging');
    $laporanTindakan = $this->request->getFile('laporan_tindakan');

    try {
      // Upload bukti geotagging if provided
      if ($buktiGeotagging && $buktiGeotagging->isValid() && !$buktiGeotagging->hasMoved()) {
        $buktiPath = $this->uploadFile($buktiGeotagging, 'uploads/monitoring_sanitasi/geotagging/');
        if ($buktiPath) {
          $data['bukti_geotagging'] = $buktiPath;
        }
      }

      // Upload laporan tindakan if provided
      if ($laporanTindakan && $laporanTindakan->isValid() && !$laporanTindakan->hasMoved()) {
        $laporanPath = $this->uploadFile($laporanTindakan, 'uploads/monitoring_sanitasi/laporan/');
        if ($laporanPath) {
          $data['laporan_tindakan'] = $laporanPath;
        }
      }

      // Save to database
      $result = $this->monitoringSanitasiKapalModel->insert($data);

      if ($result) {
        return $this->response->setJSON([
          'success' => true,
          'message' => 'Data monitoring sanitasi kapal berhasil disimpan'
        ]);
      } else {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Gagal menyimpan data',
          'errors' => $this->monitoringSanitasiKapalModel->errors()
        ]);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function update_monitoring_sanitasi_kapal()
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(404);
    }

    $id = $this->request->getPost('id');
    if (!$id) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'ID tidak valid'
      ]);
    }

    // Check if record exists
    $existingData = $this->monitoringSanitasiKapalModel->find($id);
    if (!$existingData) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Data tidak ditemukan'
      ]);
    }

    // Get form data
    $jenisDokumen = $this->request->getPost('jenis_dokumen');
    $jenisDokumenStr = $jenisDokumen ? implode(',', $jenisDokumen) : null;

    $data = [
      'tanggal' => $this->request->getPost('tanggal'),
      'wilker' => $this->request->getPost('wilker'),
      'unit_kerja' => $this->request->getPost('unit_kerja'),
      'nama_kapal' => $this->request->getPost('nama_kapal'),
      'jenis_dokumen' => $jenisDokumenStr,
      'updated_by' => session()->get('id_user')
    ];

    // Handle file uploads
    $buktiGeotagging = $this->request->getFile('bukti_geotagging');
    $laporanTindakan = $this->request->getFile('laporan_tindakan');

    try {
      // Upload new bukti geotagging if provided
      if ($buktiGeotagging && $buktiGeotagging->isValid() && !$buktiGeotagging->hasMoved()) {
        // Delete old file if exists
        if ($existingData['bukti_geotagging'] && file_exists(FCPATH . $existingData['bukti_geotagging'])) {
          unlink(FCPATH . $existingData['bukti_geotagging']);
        }
        
        $buktiPath = $this->uploadFile($buktiGeotagging, 'uploads/monitoring_sanitasi/geotagging/');
        if ($buktiPath) {
          $data['bukti_geotagging'] = $buktiPath;
        }
      }

      // Upload new laporan tindakan if provided
      if ($laporanTindakan && $laporanTindakan->isValid() && !$laporanTindakan->hasMoved()) {
        // Delete old file if exists
        if ($existingData['laporan_tindakan'] && file_exists(FCPATH . $existingData['laporan_tindakan'])) {
          unlink(FCPATH . $existingData['laporan_tindakan']);
        }
        
        $laporanPath = $this->uploadFile($laporanTindakan, 'uploads/monitoring_sanitasi/laporan/');
        if ($laporanPath) {
          $data['laporan_tindakan'] = $laporanPath;
        }
      }

      // Update database
      $result = $this->monitoringSanitasiKapalModel->update($id, $data);

      if ($result) {
        return $this->response->setJSON([
          'success' => true,
          'message' => 'Data monitoring sanitasi kapal berhasil diperbarui'
        ]);
      } else {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Gagal memperbarui data',
          'errors' => $this->monitoringSanitasiKapalModel->errors()
        ]);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function edit_monitoring_sanitasi_kapal($id = null)
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(404);
    }

    if (!$id) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'ID tidak valid'
      ]);
    }

    try {
      $data = $this->monitoringSanitasiKapalModel->find($id);
      if (!$data) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Data tidak ditemukan'
        ]);
      }

      return $this->response->setJSON([
        'success' => true,
        'data' => $data
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function detail_monitoring_sanitasi_kapal($id = null)
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(404);
    }

    if (!$id) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'ID tidak valid'
      ]);
    }

    try {
      $data = $this->monitoringSanitasiKapalModel->find($id);
      if (!$data) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Data tidak ditemukan'
        ]);
      }

      return $this->response->setJSON([
        'success' => true,
        'data' => $data
      ]);
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function delete_monitoring_sanitasi_kapal($id = null)
  {
    if (!$this->request->isAJAX()) {
      return $this->response->setStatusCode(404);
    }

    if (!$id) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'ID tidak valid'
      ]);
    }

    try {
      // Get existing data to delete files
      $existingData = $this->monitoringSanitasiKapalModel->find($id);
      if (!$existingData) {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Data tidak ditemukan'
        ]);
      }

      // Delete associated files
      if ($existingData['bukti_geotagging'] && file_exists(FCPATH . $existingData['bukti_geotagging'])) {
        unlink(FCPATH . $existingData['bukti_geotagging']);
      }
      if ($existingData['laporan_tindakan'] && file_exists(FCPATH . $existingData['laporan_tindakan'])) {
        unlink(FCPATH . $existingData['laporan_tindakan']);
      }

      // Delete from database
      $result = $this->monitoringSanitasiKapalModel->delete($id);

      if ($result) {
        return $this->response->setJSON([
          'success' => true,
          'message' => 'Data monitoring sanitasi kapal berhasil dihapus'
        ]);
      } else {
        return $this->response->setJSON([
          'success' => false,
          'message' => 'Gagal menghapus data'
        ]);
      }
    } catch (\Exception $e) {
      return $this->response->setJSON([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function export_monitoring_sanitasi_kapal()
  {
    $filters = [
      'wilker' => $this->request->getVar('wilker'),
      'nama_kapal' => $this->request->getVar('nama_kapal')
    ];

    // Handle dateRange parameter
    $dateRange = $this->request->getVar('dateRange');
    if ($dateRange) {
      $dates = explode(' - ', $dateRange);
      if (count($dates) == 2) {
        $filters['start_date'] = trim($dates[0]);
        $filters['end_date'] = trim($dates[1]);
      }
    } else {
      $filters['start_date'] = $this->request->getVar('start_date');
      $filters['end_date'] = $this->request->getVar('end_date');
    }

    $data = $this->monitoringSanitasiKapalModel->getFilteredData($filters);

    // Create CSV content
    $filename = 'monitoring_sanitasi_kapal_' . date('Y-m-d_H-i-s') . '.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // CSV headers
    fputcsv($output, [
      'No',
      'Tanggal',
      'Wilker',
      'Unit Kerja',
      'Nama Kapal',
      'Bukti Geotagging',
      'Laporan Tindakan',
      'Dibuat Pada'
    ]);
    
    // CSV data
    $no = 1;
    foreach ($data as $row) {
      fputcsv($output, [
        $no++,
        $row['tanggal'],
        $row['wilker'],
        $row['unit_kerja'] ?? '-',
        $row['nama_kapal'],
        $row['bukti_geotagging'] ? 'Ada' : 'Tidak Ada',
        $row['laporan_tindakan'] ? 'Ada' : 'Tidak Ada',
        $row['created_at']
      ]);
    }
    
    fclose($output);
    exit;
  }

  private function uploadFile($file, $path)
  {
    if (!$file->isValid()) {
      return false;
    }

    // Create directory if not exists
    if (!is_dir(FCPATH . $path)) {
      mkdir(FCPATH . $path, 0755, true);
    }

    // Generate unique filename
    $newName = date('Y-m-d_H-i-s') . '_' . $file->getRandomName();
    
    // Move file
    if ($file->move(FCPATH . $path, $newName)) {
      return $path . $newName;
    }

    return false;
  }

  /**
   * Resolve location access and mapped pelabuhan filters for current user.
   *
   * @return array{authorized: bool, restrictToPorts: bool, lokasiIds: ?array, unitKerjaArray: ?array, pelabuhanArray: array}
   */
  private function resolvePengawasanKapalLocationScope(): array
  {
    $isAdmin = session()->get('role') === 'Admin';
    $targetRow = $this->pengawasanKapalTargetAksesModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai=pengawasan_kapal_target_akses.id_data_pegawai')
      ->where('data_pegawai.nama', session()->get('nama'))
      ->first();

    $lokasiIds = [];
    if ($targetRow && !empty($targetRow['lokasi'])) {
      $decoded = json_decode((string) $targetRow['lokasi'], true);
      if (is_array($decoded)) {
        $lokasiIds = array_values(array_filter(
          $decoded,
          static fn ($id): bool => $id !== null && $id !== ''
        ));
      }
    }

    if (!$isAdmin && empty($lokasiIds)) {
      return [
        'authorized' => false,
        'restrictToPorts' => false,
        'lokasiIds' => null,
        'unitKerjaArray' => null,
        'pelabuhanArray' => [],
      ];
    }

    // Empty or "null" target_akses rows on Admin must fall back to unrestricted access rather than failing whereIn().
    if (empty($lokasiIds)) {
      return [
        'authorized' => true,
        'restrictToPorts' => false,
        'lokasiIds' => null,
        'unitKerjaArray' => null,
        'pelabuhanArray' => [],
      ];
    }

    $lokasiData = $this->unitKerjaModel->whereIn('id_unit_kerja', $lokasiIds)->findAll();
    $unitKerjaArray = array_column($lokasiData, 'lokasi');

    $portMap = [
      'cirebon' => 'cirebon',
      'indramayu' => 'Indramayu',
      'subang' => 'patimban',
    ];
    $pelabuhanArray = [];
    foreach ($unitKerjaArray as $lokasi) {
      $key = strtolower((string) $lokasi);
      $pelabuhanArray[] = $portMap[$key] ?? $lokasi;
    }

    return [
      'authorized' => true,
      'restrictToPorts' => true,
      'lokasiIds' => $lokasiIds,
      'unitKerjaArray' => $unitKerjaArray,
      'pelabuhanArray' => array_values(array_unique($pelabuhanArray)),
    ];
  }

  public function laporan_pengawasan_kapal()
  {
    $data['activeMenu'] = "laporanPengawasanKapal";
    $data['title'] = "Laporan Pengawasan Kapal";

    $scope = $this->resolvePengawasanKapalLocationScope();
    if (!$scope['authorized']) {
      session()->setFlashdata('error', 'Anda belum memiliki akses lokasi Laporan Pengawasan Kapal.');
      return redirect()->to('/home');
    }

    $data['lokasiArray'] = $scope['lokasiIds'];
    $data['unitKerjaArray'] = $scope['unitKerjaArray'];

    if ($scope['restrictToPorts']) {
      if (empty($scope['pelabuhanArray'])) {
        $data['pengawasanKapal'] = [];
        return view('pengawasan_kapal/laporan_pengawasan_kapal', $data);
      }
      $pengawasanKapalQuery = $this->pengawasanKapalModel
        ->whereIn('pelabuhan', $scope['pelabuhanArray']);
    } else {
      $pengawasanKapalQuery = $this->pengawasanKapalModel;
    }

    // Selalu urutkan berdasarkan waktu submit terbaru (date_updated)
    $data['pengawasanKapal'] = $pengawasanKapalQuery
      ->orderBy('tbl_pasien.date_updated', 'DESC')
      ->findAll();

    return view('pengawasan_kapal/laporan_pengawasan_kapal', $data);
  }
}
