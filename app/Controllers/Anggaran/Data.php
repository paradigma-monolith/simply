<?php

namespace App\Controllers\Anggaran;

use App\Controllers\BaseController;
use App\Models\AnggaranModel;

class Data extends BaseController
{
    protected $anggaranModel;
    
    public function __construct()
    {
        $this->anggaranModel = new AnggaranModel();
    }
    
    public function dashboard()
    {
        $data['title'] = "Pengelolaan Anggaran | Dashboard";
        $data['activeMenu'] = "anggaran_dashboard";
        
        $currentYear = date('Y');
        
        $data['currentYear'] = $currentYear;
        
        // Get summary data
        $summary = $this->anggaranModel->getAnggaranSummary($currentYear);
        $data['totalRencana'] = $summary['total_rencana'] ?? 0;
        $data['totalRealisasi'] = $summary['total_realisasi'] ?? 0;
        $data['persentaseRealisasi'] = $data['totalRencana'] > 0 ? 
            round(($data['totalRealisasi'] / $data['totalRencana']) * 100, 2) : 0;
        
        // Calculate total deviation
        $totalDeviation = $data['totalRealisasi'] - $data['totalRencana'];
        $data['totalDeviasi'] = $totalDeviation;
        $data['persentaseDeviasi'] = $data['totalRencana'] > 0 ? 
            round(($totalDeviation / $data['totalRencana']) * 100, 2) : 0;
        
        // Get Akun 51, 52, and 53 summary
        $akunSummary = $this->anggaranModel->getAkunSummary($currentYear);
        $data['akun51'] = ['total_rencana' => 0, 'total_realisasi' => 0];
        $data['akun52'] = ['total_rencana' => 0, 'total_realisasi' => 0];
        $data['akun53'] = ['total_rencana' => 0, 'total_realisasi' => 0];
        
        foreach ($akunSummary as $akunNumber => $akun) {
            if ($akunNumber == '51') {
                $data['akun51'] = $akun;
            } elseif ($akunNumber == '52') {
                $data['akun52'] = $akun;
            } elseif ($akunNumber == '53') {
                $data['akun53'] = $akun;
            }
        }
        
        // Calculate percentages for akun
        $data['akun51']['persentase'] = $data['akun51']['total_rencana'] > 0 ? 
            round(($data['akun51']['total_realisasi'] / $data['akun51']['total_rencana']) * 100, 2) : 0;
        $data['akun52']['persentase'] = $data['akun52']['total_rencana'] > 0 ? 
            round(($data['akun52']['total_realisasi'] / $data['akun52']['total_rencana']) * 100, 2) : 0;
        $data['akun53']['persentase'] = $data['akun53']['total_rencana'] > 0 ? 
            round(($data['akun53']['total_realisasi'] / $data['akun53']['total_rencana']) * 100, 2) : 0;
        

        
        // Get all data
        $anggaranData = $this->anggaranModel->getAnggaranByYear($currentYear);
        
        // Get data by PIC
        $picSummary = [];
        
        foreach ($anggaranData as $item) {
            $pic = $item['pic'];
            if (!isset($picSummary[$pic])) {
                $picSummary[$pic] = [
                    'rencana' => 0,
                    'realisasi' => 0,
                    'count' => 0
                ];
            }
            $picSummary[$pic]['rencana'] += $item['rencana_pencairan_anggaran'];
            $picSummary[$pic]['realisasi'] += $item['realisasi'] ?? 0;
            $picSummary[$pic]['count']++;
        }
        
        $data['picSummary'] = $picSummary;
        $data['anggaranData'] = $anggaranData;
        
        return view('anggaran/dashboard', $data);
    }
    
    public function data()
    {
        $data['title'] = "Pengelolaan Anggaran | Data Anggaran";
        $data['activeMenu'] = "anggaran_data";
        
        $currentYear = date('Y');
        
        $data['currentYear'] = $currentYear;
        
        // Get all data
        $data['anggaranData'] = $this->anggaranModel->getAnggaranByYear($currentYear);
        
        return view('anggaran/data', $data);
    }
    
    public function input()
    {
        $data['title'] = "Pengelolaan Anggaran | Input Data";
        $data['activeMenu'] = "anggaran_input";
        
        return view('anggaran/input', $data);
    }
    
    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'no_kegiatan' => 'required|max_length[50]',
            'akun' => 'required|in_list[51,52,53]',
            'kegiatan' => 'required|max_length[500]',
            'rencana_pencairan_anggaran' => 'required|numeric|greater_than[0]',
            'pic' => 'required|max_length[100]',
            'realisasi' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'tahun_anggaran' => 'required|integer|greater_than[1999]|less_than[2100]',
            'bulan' => 'required|integer|greater_than[0]|less_than[13]'
        ];
        
        if (!$this->validate($rules)) {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation->getErrors()
                ])->setStatusCode(400);
            }
            
            // Store old input data in session for form repopulation
            session()->setFlashdata('old_input', $this->request->getPost());
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Keep month as number for database consistency
        $bulanInput = (int)$this->request->getPost('bulan');
        
        // Sanitize and validate input data
        $rencanaInput = $this->request->getPost('rencana_pencairan_anggaran');
        $realisasiInput = $this->request->getPost('realisasi') ?? '0';
        
        // Remove formatting from currency fields and ensure integer values
        $rencana = (int)preg_replace('/[^\d]/', '', $rencanaInput);
        $realisasi = (int)preg_replace('/[^\d]/', '', $realisasiInput);
        
        $data = [
            'no_kegiatan' => trim($this->request->getPost('no_kegiatan')),
            'akun' => $this->request->getPost('akun'),
            'kegiatan' => trim($this->request->getPost('kegiatan')),
            'rencana_pencairan_anggaran' => $rencana,
            'pic' => trim($this->request->getPost('pic')),
            'realisasi' => $realisasi,
            'tahun_anggaran' => (int)$this->request->getPost('tahun_anggaran'),
            'bulan' => $bulanInput,
            'created_by' => session()->get('id_user') ?? null
        ];
        
        // Deviasi akan dihitung otomatis di model
        try {
            if ($this->anggaranModel->insert($data)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data anggaran berhasil ditambahkan'
                    ]);
                }
                return redirect()->to('/anggaran/data')->with('success', 'Data anggaran berhasil ditambahkan');
            } else {
                // Get validation errors from model if any
                $modelErrors = $this->anggaranModel->errors();
                $errorMessage = !empty($modelErrors) ? implode(', ', $modelErrors) : 'Gagal menambahkan data anggaran';
                
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMessage,
                        'errors' => $modelErrors
                    ])->setStatusCode(500);
                }
                return redirect()->back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error inserting anggaran: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem saat menyimpan data'
                ])->setStatusCode(500);
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data');
        }
    }
    
    public function edit($id)
    {
        $data['title'] = "Pengelolaan Anggaran | Edit Data";
        $data['activeMenu'] = "anggaran_edit";
        
        $data['anggaran'] = $this->anggaranModel->find($id);
        
        if (!$data['anggaran']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data anggaran tidak ditemukan');
        }
        
        
        // Preserve old input data if there are validation errors
        if (session()->getFlashdata('errors')) {
            $data['anggaran'] = array_merge($data['anggaran'], session()->getFlashdata('old_input') ?? []);
        }
        
        return view('anggaran/edit', $data);
    }
    
    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'no_kegiatan' => 'required|max_length[50]',
            'akun' => 'required|in_list[51,52,53]',
            'kegiatan' => 'required|max_length[500]',
            'rencana_pencairan_anggaran' => 'required|numeric|greater_than[0]',
            'pic' => 'required|max_length[100]',
            'realisasi' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'tahun_anggaran' => 'required|integer|greater_than[1999]|less_than[2100]',
            'bulan' => 'required|integer|greater_than[0]|less_than[13]'
        ];
        
        if (!$this->validate($rules)) {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validation->getErrors()
                ])->setStatusCode(400);
            }
            
            // Store old input data in session for form repopulation
            session()->setFlashdata('old_input', $this->request->getPost());
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Keep month as number for database consistency
        $bulanInput = (int)$this->request->getPost('bulan');
        
        // Sanitize and validate input data
        $rencanaInput = $this->request->getPost('rencana_pencairan_anggaran');
        $realisasiInput = $this->request->getPost('realisasi') ?? '0';
        
        // Remove formatting from currency fields and ensure integer values
        $rencana = (int)preg_replace('/[^\d]/', '', $rencanaInput);
        $realisasi = (int)preg_replace('/[^\d]/', '', $realisasiInput);
        
        $data = [
            'no_kegiatan' => trim($this->request->getPost('no_kegiatan')),
            'akun' => $this->request->getPost('akun'),
            'kegiatan' => trim($this->request->getPost('kegiatan')),
            'rencana_pencairan_anggaran' => $rencana,
            'pic' => trim($this->request->getPost('pic')),
            'realisasi' => $realisasi,
            'tahun_anggaran' => (int)$this->request->getPost('tahun_anggaran'),
            'bulan' => $bulanInput,
            'updated_by' => session()->get('id_user') ?? null
        ];
        
        // Validate that the record exists before updating
        if (!$this->anggaranModel->find($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data anggaran tidak ditemukan'
                ])->setStatusCode(404);
            }
            return redirect()->to('/anggaran/data')->with('error', 'Data anggaran tidak ditemukan');
        }
        
        // Deviasi akan dihitung otomatis di model
        try {
            if ($this->anggaranModel->update($id, $data)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data anggaran berhasil diperbarui'
                    ]);
                }
                return redirect()->to('/anggaran/data')->with('success', 'Data anggaran berhasil diperbarui');
            } else {
                // Get validation errors from model if any
                $modelErrors = $this->anggaranModel->errors();
                $errorMessage = !empty($modelErrors) ? implode(', ', $modelErrors) : 'Gagal memperbarui data anggaran';
                
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMessage,
                        'errors' => $modelErrors
                    ])->setStatusCode(500);
                }
                return redirect()->back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating anggaran: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem saat memperbarui data'
                ])->setStatusCode(500);
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data');
        }
    }
    
    public function delete($id)
    {
        // Validate that the record exists before deleting
        if (!$this->anggaranModel->find($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data anggaran tidak ditemukan'
                ])->setStatusCode(404);
            }
            return redirect()->to('/anggaran/data')->with('error', 'Data anggaran tidak ditemukan');
        }
        
        try {
            if ($this->anggaranModel->delete($id)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Data anggaran berhasil dihapus'
                    ]);
                }
                return redirect()->to('/anggaran/data')->with('success', 'Data anggaran berhasil dihapus');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menghapus data anggaran'
                    ])->setStatusCode(500);
                }
                return redirect()->to('/anggaran/data')->with('error', 'Gagal menghapus data anggaran');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting anggaran: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem saat menghapus data'
                ])->setStatusCode(500);
            }
            return redirect()->to('/anggaran/data')->with('error', 'Terjadi kesalahan sistem saat menghapus data');
        }
    }
    
    public function detail($id)
    {
        $data['title'] = "Pengelolaan Anggaran | Detail Data";
        $data['activeMenu'] = "anggaran_data";
        
        $data['anggaran'] = $this->anggaranModel->find($id);
        
        if (!$data['anggaran']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data anggaran tidak ditemukan');
        }
        
        return view('anggaran/detail', $data);
    }
    
    public function getDetail($id)
    {
        $anggaran = $this->anggaranModel->find($id);
        
        if (!$anggaran) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data anggaran tidak ditemukan'
            ])->setStatusCode(404);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $anggaran
        ]);
    }
    
    public function getEdit($id)
    {
        $anggaran = $this->anggaranModel->find($id);
        
        if (!$anggaran) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data anggaran tidak ditemukan'
            ])->setStatusCode(404);
        }
        
        // Ensure bulan is integer for form compatibility
        if (isset($anggaran['bulan'])) {
            $anggaran['bulan'] = (int)$anggaran['bulan'];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $anggaran
        ]);
    }
    
    public function export()
    {
        $year = $this->request->getGet('year') ?? date('Y');
        $anggaranData = $this->anggaranModel->getAnggaranByYear($year);
        
        $filename = "anggaran_" . $year . "_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, [
            'No Kegiatan',
            'Akun',
            'Kegiatan',
            'Rencana Pencairan Anggaran',
            'PIC',
            'Realisasi',
            'Deviasi',
            'Tahun Anggaran'
        ]);
        
        // Data CSV
        foreach ($anggaranData as $row) {
            fputcsv($output, [
                $row['no_kegiatan'],
                $row['akun'] ?? '-',
                $row['kegiatan'],
                $row['rencana_pencairan_anggaran'],
                $row['pic'],
                $row['realisasi'],
                $row['deviasi'],
                $row['tahun_anggaran']
            ]);
        }
        
        fclose($output);
    }
}