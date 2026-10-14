<?php

namespace App\Controllers\PengukuranKebugaran;

use App\Controllers\BaseController;

class Data extends BaseController
{
    protected $pengukuranKebugaranModel;
    protected $userModel;
    protected $dataPegawaiModel;
    protected $aksesModel;

    public function __construct()
    {
        $this->pengukuranKebugaranModel = new \App\Models\PengukuranKebugaranModel();
        $this->userModel = new \App\Models\Master\UserModel();
        $this->dataPegawaiModel = new \App\Models\Master\DataPegawaiModel();
        $this->aksesModel = new \App\Models\PengukuranKebugaranAksesModel();
    }

    public function index()
    {
        $data['title'] = 'Pengukuran Kebugaran';
        $data['activeMenu'] = 'pengukuranKebugaran';
        
        // Get current user
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        
        // Check if user has access to all employees (Admin or special access)
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if ($hasAllAccess) {
            $pengukuranData = $this->pengukuranKebugaranModel
                ->select('pengukuran_kebugaran.*, user.nama, data_pegawai.nip')
                ->join('user', 'user.id_user = pengukuran_kebugaran.id_user')
                ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
                ->orderBy('tanggal_pengukuran', 'DESC')
                ->findAll();
        } else {
            $pengukuranData = $this->pengukuranKebugaranModel
                ->select('pengukuran_kebugaran.*, user.nama, data_pegawai.nip')
                ->join('user', 'user.id_user = pengukuran_kebugaran.id_user')
                ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
                ->where('pengukuran_kebugaran.id_user', $currentUser['id_user'])
                ->orderBy('tanggal_pengukuran', 'DESC')
                ->findAll();
        }
        
        $data['pengukuranData'] = $pengukuranData;
        $data['isAdmin'] = ($this->getUserRole() == 'Admin');
        $data['hasAllAccess'] = $hasAllAccess;
        
        $timKerjaModel = new \App\Models\Master\TimKerjaModel();
        $data['timKerja'] = $timKerjaModel->findAll();
        
        return view('pengukuran_kebugaran/index', $data);
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Pengukuran Kebugaran';
        $data['activeMenu'] = 'pengukuranKebugaran';
        
        // Initialize default values
        $data['isAdmin'] = false;
        $data['pegawaiList'] = [];
        
        try {
            // Check if user is logged in
            if (!session()->get('username')) {
                session()->setFlashdata('error', 'Anda belum login');
                return redirect()->to('/auth/login');
            }
            
            // Check if user has access to all employees (Admin or special access)
            $currentUser = $this->userModel->where('username', session()->get('username'))->first();
            $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                           $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
            
            // Only allow admin and users with special access to add data
            if (!$hasAllAccess) {
                session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menambah data pengukuran kebugaran');
                return redirect()->to('/pengukuran_kebugaran');
            }
            
            $data['isAdmin'] = true;
            
            try {
                // Get list of users with their employee data
                $pegawaiList = $this->userModel
                    ->select('user.id_user, user.nama, data_pegawai.nip')
                    ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
                    ->orderBy('user.nama', 'ASC')
                    ->findAll();
                
                $data['pegawaiList'] = $pegawaiList;
                
            } catch (\Exception $e) {
                // If query fails, continue with empty list
                log_message('error', 'Error getting pegawai list: ' . $e->getMessage());
                $data['pegawaiList'] = [];
            }
        
        return view('pengukuran_kebugaran/tambah', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in tambah method: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            return redirect()->to('/pengukuran_kebugaran');
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Pengukuran Kebugaran';
        $data['activeMenu'] = 'pengukuranKebugaran';
        
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        
        $pengukuran = $this->pengukuranKebugaranModel->find($id);
        
        if (!$pengukuran) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/pengukuran_kebugaran');
        }
        
        // Check if user can edit this data
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if (!$hasAllAccess && $pengukuran['id_user'] != $currentUser['id_user']) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit data ini');
            return redirect()->to('/pengukuran_kebugaran');
        }
        
        $data['pengukuran'] = $pengukuran;
        
        return view('pengukuran_kebugaran/edit', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Pengukuran Kebugaran';
        $data['activeMenu'] = 'pengukuranKebugaran';
        
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        
        $pengukuran = $this->pengukuranKebugaranModel
            ->select('pengukuran_kebugaran.*, user.nama, data_pegawai.nip')
            ->join('user', 'user.id_user = pengukuran_kebugaran.id_user')
            ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
            ->find($id);
        
        if (!$pengukuran) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/pengukuran_kebugaran');
        }
        
        // Check if user can view this data
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if (!$hasAllAccess && $pengukuran['id_user'] != $currentUser['id_user']) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk melihat data ini');
            return redirect()->to('/pengukuran_kebugaran');
        }
        
        $data['pengukuran'] = $pengukuran;
        
        return view('pengukuran_kebugaran/detail', $data);
    }

    public function pengaturan()
    {
        // Only admin can access this page
        if ($this->getUserRole() != 'Admin') {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman pengaturan');
            return redirect()->to('/pengukuran_kebugaran');
        }

        $data['title'] = 'Pengaturan Akses Pengukuran Kebugaran';
        $data['activeMenu'] = 'pengukuranKebugaran';
        
        try {
            // Check if the access table exists by trying to create it
            $this->createAccessTableIfNotExists();
            
            // Get list of users with special access
            $data['usersWithAccess'] = $this->aksesModel->getUsersWithAccess();
            
                    // Get list of all users (excluding admin) for dropdown
        $data['allUsers'] = $this->userModel
            ->select('user.id_user, user.nama, user.username, data_pegawai.nip')
            ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
            ->join('role_access', 'role_access.id_role_access = user.id_role_access')
            ->where('role_access.role !=', 'Admin')
            ->orderBy('user.nama', 'ASC')
            ->findAll();
            
            return view('pengukuran_kebugaran/pengaturan', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in pengaturan method: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan sistem. Tabel akses belum dibuat. Silakan hubungi administrator.');
            return redirect()->to('/pengukuran_kebugaran');
        }
    }

    private function createAccessTableIfNotExists()
    {
        $db = \Config\Database::connect();
        
        // Check if table exists
        if (!$db->tableExists('pengukuran_kebugaran_akses')) {
            // Create the table
            $sql = "CREATE TABLE `pengukuran_kebugaran_akses` (
                `id_akses` INT AUTO_INCREMENT PRIMARY KEY,
                `id_user` INT NOT NULL,
                `akses_semua_pegawai` TINYINT(1) DEFAULT 0,
                `dibuat_oleh` INT NOT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY `unique_user` (`id_user`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $db->query($sql);
            log_message('info', 'Table pengukuran_kebugaran_akses created successfully');
        } else {
            // Table exists, check if all required columns exist
            $fields = $db->getFieldNames('pengukuran_kebugaran_akses');
            
            if (!in_array('dibuat_oleh', $fields)) {
                $db->query("ALTER TABLE pengukuran_kebugaran_akses ADD COLUMN dibuat_oleh INT NOT NULL DEFAULT 1 AFTER akses_semua_pegawai");
                log_message('info', 'Added dibuat_oleh column to pengukuran_kebugaran_akses');
            }
            
            if (!in_array('created_at', $fields)) {
                $db->query("ALTER TABLE pengukuran_kebugaran_akses ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP AFTER dibuat_oleh");
                log_message('info', 'Added created_at column to pengukuran_kebugaran_akses');
                         }
         }
     }

    public function import()
    {
        // Check if user has access (Admin or special access)
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if (!$hasAllAccess) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengimpor data');
            return redirect()->to('/pengukuran_kebugaran');
        }

        $data['title'] = 'Import Data Pengukuran Kebugaran';
        $data['activeMenu'] = 'pengukuranKebugaran';
        
        return view('pengukuran_kebugaran/import', $data);
    }

    public function downloadTemplate()
    {
        // Check if user has access (Admin or special access)
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
                       
        if (!$hasAllAccess) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengunduh template');
            return redirect()->to('/pengukuran_kebugaran');
        }

        try {
            if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Template Import');

                // Headers
                $headers = [
                    'NIP', 'Nama', 'Tanggal', 'Berat Badan (kg)', 'Tinggi Badan (cm)',
                    'Lingkar Pinggang (cm)', 'Tekanan Darah Sistolik (mmHg)', 
                    'Tekanan Darah Diastolik (mmHg)', 'Gula Darah (mg/dL)', 
                    'Kolesterol (mg/dL)', 'Asam Urat (mg/dL)'
                ];
                
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '1', $header);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $col++;
                }

                // Sample data
                $sheet->setCellValueExplicit('A2', '198506122010121001', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('B2', 'CONTOH PEGAWAI');
                $sheet->setCellValue('C2', '15/01/2025');
                $sheet->setCellValue('D2', 70.5);
                $sheet->setCellValue('E2', 170.5);
                $sheet->setCellValue('F2', 85);
                $sheet->setCellValue('G2', 120);
                $sheet->setCellValue('H2', 80);
                $sheet->setCellValue('I2', 95);
                $sheet->setCellValue('J2', 180);
                $sheet->setCellValue('K2', 6.2);

                // Set column A to text format to prevent scientific notation when user types NIP
                $sheet->getStyle('A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $filename = 'Template_Pengukuran_Kebugaran_' . date('Y-m-d') . '.xlsx';
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                
                $writer->save('php://output');
                exit();
            } else {
                throw new \Exception('PhpSpreadsheet not available');
            }
        } catch (\Exception $e) {
            // Fallback to CSV
            $filename = 'Template_Pengukuran_Kebugaran_' . date('Y-m-d') . '.csv';
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Cache-Control: max-age=0');
            
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($output, [
                'NIP', 'Nama', 'Tanggal', 'Berat Badan (kg)', 'Tinggi Badan (cm)',
                'Lingkar Pinggang (cm)', 'Tekanan Darah Sistolik (mmHg)', 
                'Tekanan Darah Diastolik (mmHg)', 'Gula Darah (mg/dL)', 
                'Kolesterol (mg/dL)', 'Asam Urat (mg/dL)'
            ]);
            
            fputcsv($output, [
                "'198506122010121001", 'CONTOH PEGAWAI', '15/01/2025', '70.5', '170.5',
                '85', '120', '80', '95', '180', '6.2'
            ]);
            
            fclose($output);
            exit();
        }
    }

    public function processImport()
    {
        // Check if user has access (Admin or special access)
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if (!$hasAllAccess) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengimpor data');
            return redirect()->to('/pengukuran_kebugaran');
        }

        // Validate file upload
        $file = $this->request->getFile('excel_file');
        if (!$file || !$file->isValid()) {
            session()->setFlashdata('error', 'File tidak valid atau tidak ditemukan');
            return redirect()->to('/pengukuran_kebugaran/import');
        }

        // Check file type
        $allowedTypes = ['xlsx', 'xls', 'csv'];
        $fileExtension = $file->getClientExtension();
        if (!in_array(strtolower($fileExtension), $allowedTypes)) {
            session()->setFlashdata('error', 'Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv');
            return redirect()->to('/pengukuran_kebugaran/import');
        }

        // Check file size (5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            session()->setFlashdata('error', 'Ukuran file terlalu besar. Maksimal 5MB');
            return redirect()->to('/pengukuran_kebugaran/import');
        }

        try {
            // Move uploaded file
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/', $newName);
            $filePath = WRITEPATH . 'uploads/' . $newName;

            // Read file based on extension
            if (strtolower($fileExtension) == 'csv') {
                $rows = $this->readCSVFile($filePath);
            } else {
                $rows = $this->readExcelFile($filePath);
            }

            // Remove header row
            array_shift($rows);

            $defaultDate = $this->request->getPost('tanggal_pengukuran');
            $updateExisting = $this->request->getPost('update_existing') == '1';
            
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 because we removed header and array is 0-indexed
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $this->processRowData($row, $rowNumber, $defaultDate, $updateExisting);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                }
            }

            // Clean up uploaded file
            unlink($filePath);

            // Set flash messages
            if ($successCount > 0) {
                session()->setFlashdata('success', "Berhasil mengimpor {$successCount} data pengukuran kebugaran");
            }
            
            if ($errorCount > 0) {
                session()->setFlashdata('errors', $errors);
            }

            if ($successCount == 0 && $errorCount == 0) {
                session()->setFlashdata('error', 'Tidak ada data yang dapat diimpor');
            }

            return redirect()->to('/pengukuran_kebugaran');

        } catch (\Exception $e) {
            // Clean up uploaded file if exists
            if (isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }
            
            session()->setFlashdata('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
            return redirect()->to('/pengukuran_kebugaran/import');
        }
    }

    private function readCSVFile($filePath)
    {
        $rows = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }

    private function readExcelFile($filePath)
    {
        // For Excel files, we'll use a simple approach
        // If PhpSpreadsheet is available, use it, otherwise convert to CSV approach
        try {
            if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                // Gunakan formatData = false agar tanggal Excel terbaca sebagai angka serial (contoh: 45306)
                // Ini mencegah bug di mana format m/d/yy dibaca salah oleh createFromFormat menjadi tahun 2027
                return $worksheet->toArray(null, true, false, false);
            } else {
                throw new \Exception('PhpSpreadsheet not available, please use CSV format');
            }
        } catch (\Exception $e) {
            throw new \Exception('Tidak dapat membaca file Excel. Silakan gunakan format CSV atau pastikan PhpSpreadsheet terinstall');
        }
    }

    private function processRowData($row, $rowNumber, $defaultDate, $updateExisting)
    {
        // Extract data from row
        $nip = trim($row[0] ?? '');
        $nama = trim($row[1] ?? '');
        $tanggal = trim($row[2] ?? '');
        $beratBadan = $row[3] ?? '';
        $tinggiBadan = $row[4] ?? '';
        $lingkarPinggang = $row[5] ?? '';
        $sistolik = $row[6] ?? '';
        $diastolik = $row[7] ?? '';
        $gulaDarah = $row[8] ?? '';
        $kolesterol = $row[9] ?? '';
        $asamUrat = $row[10] ?? '';

        // Validate required fields
        if (empty($nip)) {
            throw new \Exception("NIP tidak boleh kosong");
        }

        // Find user by NIP
        $pegawai = $this->dataPegawaiModel->where('nip', $nip)->first();
        if (!$pegawai) {
            throw new \Exception("NIP {$nip} tidak ditemukan di sistem");
        }

        // Parse date
        if (!empty($tanggal)) {
            try {
                // Cek apakah tanggal berupa serial number dari Excel (contoh: 45672)
                if (is_numeric($tanggal) && $tanggal > 10000 && $tanggal < 100000) {
                    $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal);
                    $tanggalPengukuran = $dateObj->format('Y-m-d');
                } else {
                    // Try multiple date formats (termasuk 2 digit tahun)
                    $dateFormats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y', 'd/m/y', 'd-m-y', 'm/d/y'];
                    $dateObj = false;

                    foreach ($dateFormats as $format) {
                        $dateObj = \DateTime::createFromFormat($format, $tanggal);
                        if ($dateObj) {
                            // Fix bug tahun 0025 -> 2025
                            $year = (int)$dateObj->format('Y');
                            if ($year < 100) {
                                $dateObj->setDate($year + 2000, (int)$dateObj->format('m'), (int)$dateObj->format('d'));
                            }
                            break;
                        }
                    }

                    if (!$dateObj) {
                        // Fallback terakhir menggunakan strtotime
                        $timestamp = strtotime(str_replace('/', '-', $tanggal));
                        if ($timestamp) {
                            $tanggalPengukuran = date('Y-m-d', $timestamp);
                        } else {
                            throw new \Exception("Format tanggal tidak valid. Gunakan dd/mm/yyyy");
                        }
                    } else {
                        $tanggalPengukuran = $dateObj->format('Y-m-d');
                    }
                }
            } catch (\Exception $e) {
                throw new \Exception("Format tanggal tidak valid. Gunakan dd/mm/yyyy");
            }
        } else {
            $tanggalPengukuran = $defaultDate;
        }

        // Validate numeric fields
        $numericFields = [
            'Berat Badan' => $beratBadan,
            'Tinggi Badan' => $tinggiBadan,
            'Lingkar Pinggang' => $lingkarPinggang,
            'Tekanan Darah Sistolik' => $sistolik,
            'Tekanan Darah Diastolik' => $diastolik
        ];

        foreach ($numericFields as $fieldName => $value) {
            if (empty($value) || !is_numeric($value) || $value <= 0) {
                throw new \Exception("{$fieldName} harus berupa angka positif");
            }
        }

        // Validate optional fields if provided
        if ($asamUrat !== '' && (!is_numeric($asamUrat) || $asamUrat <= 0)) {
            throw new \Exception("Asam Urat harus berupa angka positif jika diisi");
        }
        if ($gulaDarah !== '' && (!is_numeric($gulaDarah) || $gulaDarah <= 0)) {
            throw new \Exception("Gula Darah harus berupa angka positif jika diisi");
        }
        if ($kolesterol !== '' && (!is_numeric($kolesterol) || $kolesterol <= 0)) {
            throw new \Exception("Kolesterol harus berupa angka positif jika diisi");
        }

        // Calculate IMT and categories
        $tinggiBadanMeter = $tinggiBadan / 100;
        $imt = $beratBadan / ($tinggiBadanMeter * $tinggiBadanMeter);
        $imt = round($imt, 2);

        $kategoriIMT = $this->getKategoriIMT($imt);
        $jenisKelamin = $this->getJenisKelaminFromNIP($nip);
        $kategoriLingkarPinggang = $this->getKategoriLingkarPinggang($lingkarPinggang, $jenisKelamin);

        // Prepare data
        $data = [
            'id_user' => $pegawai['id_user'],
            'tanggal_pengukuran' => $tanggalPengukuran,
            'tekanan_darah_sistolik' => $sistolik,
            'tekanan_darah_diastolik' => $diastolik,
            'gula_darah_sewaktu' => $gulaDarah === '' ? null : $gulaDarah,
            'kolesterol_total' => $kolesterol === '' ? null : $kolesterol,
            'asam_urat' => $asamUrat === '' ? null : $asamUrat,
            'lingkar_pinggang' => $lingkarPinggang,
            'kategori_lingkar_pinggang' => $kategoriLingkarPinggang,
            'berat_badan' => $beratBadan,
            'tinggi_badan' => $tinggiBadan,
            'imt' => $imt,
            'kategori_imt' => $kategoriIMT
        ];

        // Check if data already exists
        if ($updateExisting) {
            $existingData = $this->pengukuranKebugaranModel
                ->where('id_user', $pegawai['id_user'])
                ->where('tanggal_pengukuran', $tanggalPengukuran)
                ->first();

            if ($existingData) {
                $data['id_pengukuran_kebugaran'] = $existingData['id_pengukuran_kebugaran'];
            }
        }

        // Save data
        if (!$this->pengukuranKebugaranModel->save($data)) {
            $errors = $this->pengukuranKebugaranModel->errors();
            throw new \Exception("Gagal menyimpan data: " . implode(', ', $errors));
        }
    }

    private function getKategoriIMT($imt)
    {
        if ($imt < 18.5) {
            return 'Underweight';
        } elseif ($imt >= 18.5 && $imt < 25) {
            return 'Normal';
        } elseif ($imt >= 25 && $imt < 30) {
            return 'Overweight';
        } else {
            return 'Obesitas';
        }
    }

    private function getJenisKelaminFromNIP($nip)
    {
        if (strlen($nip) >= 4) {
            $digit = substr($nip, -4, 1);
            return ($digit == '1') ? 'L' : 'P';
        }
        return 'L'; // Default laki-laki
    }

    private function getKategoriLingkarPinggang($lingkarPinggang, $jenisKelamin)
    {
        if ($jenisKelamin == 'L') {
            return ($lingkarPinggang > 90) ? 'Obesitas Sentral' : 'Normal';
        } else {
            return ($lingkarPinggang > 80) ? 'Obesitas Sentral' : 'Normal';
        }
    }

     public function export()
    {
        // Check if user has access (Admin or special access)
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if (!$hasAllAccess) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengekspor data');
            return redirect()->to('/pengukuran_kebugaran');
        }

        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $idTimKerja = $this->request->getPost('id_tim_kerja');

        $query = $this->pengukuranKebugaranModel
            ->select('pengukuran_kebugaran.*, user.nama, data_pegawai.nip, tim_kerja.nama_tim_kerja')
            ->join('user', 'user.id_user = pengukuran_kebugaran.id_user')
            ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
            ->join('tim_kerja', 'tim_kerja.id_tim_kerja = data_pegawai.id_tim_kerja', 'left');

        if (!empty($startDate)) {
            $query->where('pengukuran_kebugaran.tanggal_pengukuran >=', $startDate);
        }
        if (!empty($endDate)) {
            $query->where('pengukuran_kebugaran.tanggal_pengukuran <=', $endDate);
        }
        if (!empty($idTimKerja)) {
            $query->where('data_pegawai.id_tim_kerja', $idTimKerja);
        }

        $dataPengukuran = $query->orderBy('pengukuran_kebugaran.tanggal_pengukuran', 'DESC')->findAll();

        if (empty($dataPengukuran)) {
            session()->setFlashdata('error', 'Tidak ada data pada rentang tanggal atau tim kerja tersebut.');
            return redirect()->to('/pengukuran_kebugaran');
        }

        try {
            if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Data Pengukuran Kebugaran');

                // Headers
                $headers = [
                    'A1' => 'No',
                    'B1' => 'Tanggal Pengukuran',
                    'C1' => 'Nama',
                    'D1' => 'NIP',
                    'E1' => 'Tim Kerja',
                    'F1' => 'Tensi Sistolik',
                    'G1' => 'Tensi Diastolik',
                    'H1' => 'Gula Darah Sewaktu',
                    'I1' => 'Kolesterol Total',
                    'J1' => 'Asam Urat',
                    'K1' => 'Lingkar Pinggang',
                    'L1' => 'BB',
                    'M1' => 'TB',
                    'N1' => 'IMT',
                    'O1' => 'Kategori IMT'
                ];

                foreach ($headers as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                    $sheet->getStyle($cell)->getFont()->setBold(true);
                }

                $row = 2;
                $no = 1;
                foreach ($dataPengukuran as $data) {
                    $sheet->setCellValue('A' . $row, $no++);
                    $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($data['tanggal_pengukuran'])));
                    $sheet->setCellValue('C' . $row, $data['nama']);
                    // Force NIP as string to prevent scientific notation
                    $sheet->setCellValueExplicit('D' . $row, $data['nip'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('E' . $row, $data['nama_tim_kerja']);
                    $sheet->setCellValue('F' . $row, $data['tekanan_darah_sistolik']);
                    $sheet->setCellValue('G' . $row, $data['tekanan_darah_diastolik']);
                    $sheet->setCellValue('H' . $row, $data['gula_darah_sewaktu']);
                    $sheet->setCellValue('I' . $row, $data['kolesterol_total']);
                    $sheet->setCellValue('J' . $row, $data['asam_urat']);
                    $sheet->setCellValue('K' . $row, $data['lingkar_pinggang']);
                    $sheet->setCellValue('L' . $row, $data['berat_badan']);
                    $sheet->setCellValue('M' . $row, $data['tinggi_badan']);
                    $sheet->setCellValue('N' . $row, $data['imt']);
                    $sheet->setCellValue('O' . $row, $data['kategori_imt']);
                    $row++;
                }

                // Auto size columns
                foreach (range('A', 'O') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $filename = 'Data_Pengukuran_Kebugaran_' . date('YmdHis') . '.xlsx';

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                
                $writer->save('php://output');
                exit;
            } else {
                // Fallback to CSV if PhpSpreadsheet is not available
                $filename = 'Data_Pengukuran_Kebugaran_' . date('YmdHis') . '.csv';
                
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=' . $filename);
                header('Cache-Control: max-age=0');
                
                $output = fopen('php://output', 'w');
                fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
                
                fputcsv($output, [
                    'No', 'Tanggal Pengukuran', 'Nama', 'NIP', 'Tim Kerja',
                    'Tensi Sistolik', 'Tensi Diastolik', 'Gula Darah Sewaktu',
                    'Kolesterol Total', 'Asam Urat', 'Lingkar Pinggang',
                    'BB', 'TB', 'IMT', 'Kategori IMT'
                ]);
                
                $no = 1;
                foreach ($dataPengukuran as $data) {
                    fputcsv($output, [
                        $no++,
                        date('d/m/Y', strtotime($data['tanggal_pengukuran'])),
                        $data['nama'],
                        "'" . $data['nip'], // Add quote to prevent Excel from converting to scientific notation
                        $data['nama_tim_kerja'],
                        $data['tekanan_darah_sistolik'],
                        $data['tekanan_darah_diastolik'],
                        $data['gula_darah_sewaktu'],
                        $data['kolesterol_total'],
                        $data['asam_urat'],
                        $data['lingkar_pinggang'],
                        $data['berat_badan'],
                        $data['tinggi_badan'],
                        $data['imt'],
                        $data['kategori_imt']
                    ]);
                }
                
                fclose($output);
                exit;
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal mengekspor data: ' . $e->getMessage());
            return redirect()->to('/pengukuran_kebugaran');
        }
    }

    /**
     * Get current user role from database
     */
     private function getUserRole()
     {
         if (!session()->get('username')) {
             return null;
         }

         try {
             $user = $this->userModel
                 ->select('role_access.role')
                 ->join('role_access', 'role_access.id_role_access = user.id_role_access')
                 ->where('user.username', session()->get('username'))
                 ->first();

             return $user ? $user['role'] : null;
         } catch (\Exception $e) {
             log_message('error', 'Error getting user role: ' . $e->getMessage());
             return null;
         }
    }
}