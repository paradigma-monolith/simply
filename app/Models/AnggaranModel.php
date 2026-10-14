<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggaranModel extends Model
{
    protected $table = 'anggaran';
    protected $primaryKey = 'id_anggaran';
    protected $allowedFields = [
        'no_kegiatan',
        'akun',
        'kegiatan',
        'rencana_pencairan_anggaran',
        'pic',
        'realisasi',
        'deviasi',
        'tahun_anggaran',
        'bulan',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'no_kegiatan' => 'required|max_length[50]',
        'akun' => 'required|in_list[51,52,53]',
        'kegiatan' => 'required|max_length[500]',
        'rencana_pencairan_anggaran' => 'required|numeric',
        'pic' => 'required|max_length[100]',
        'tahun_anggaran' => 'required|integer',
        'bulan' => 'required|max_length[20]'
    ];
    
    protected $validationMessages = [
        'no_kegiatan' => [
            'required' => 'Nomor kegiatan harus diisi',
            'max_length' => 'Nomor kegiatan maksimal 50 karakter'
        ],
        'akun' => [
            'required' => 'Akun harus dipilih',
            'in_list' => 'Akun harus berupa 51, 52, atau 53'
        ],
        'kegiatan' => [
            'required' => 'Kegiatan harus diisi',
            'max_length' => 'Kegiatan maksimal 500 karakter'
        ],
        'rencana_pencairan_anggaran' => [
            'required' => 'Rencana pencairan anggaran harus diisi',
            'numeric' => 'Rencana pencairan anggaran harus berupa angka'
        ],
        'pic' => [
            'required' => 'PIC harus diisi',
            'max_length' => 'PIC maksimal 100 karakter'
        ],
        'tahun_anggaran' => [
            'required' => 'Tahun anggaran harus diisi',
            'integer' => 'Tahun anggaran harus berupa angka'
        ],
        'bulan' => [
            'required' => 'Bulan harus diisi',
            'max_length' => 'Bulan maksimal 20 karakter'
        ]
    ];
    
    public function getAnggaranByYear($year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        
        return $this->where('tahun_anggaran', $year)
                   ->orderBy('no_kegiatan', 'ASC')
                   ->findAll();
    }
    

    

    

    
    public function getAnggaranSummary($year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        
        $result = $this->where('tahun_anggaran', $year)
                       ->selectSum('rencana_pencairan_anggaran', 'total_rencana')
                       ->selectSum('realisasi', 'total_realisasi')
                       ->first();
        
        $totalRencana = $result['total_rencana'] ?? 0;
        $totalRealisasi = $result['total_realisasi'] ?? 0;
        $persentaseRealisasi = $totalRencana > 0 ? ($totalRealisasi / $totalRencana) * 100 : 0;
        $totalDeviasi = $totalRencana - $totalRealisasi;
        
        return [
            'total_rencana' => $totalRencana,
            'total_realisasi' => $totalRealisasi,
            'persentase_realisasi' => round($persentaseRealisasi, 2),
            'total_deviasi' => $totalDeviasi
        ];
    }
    
    public function getAkunSummary($year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        
        $results = $this->select('akun')
                        ->selectSum('rencana_pencairan_anggaran', 'total_rencana')
                        ->selectSum('realisasi', 'total_realisasi')
                        ->where('tahun_anggaran', $year)
                        ->groupBy('akun')
                        ->findAll();
        
        // Format results for easier use
        $summary = [];
        foreach ($results as $result) {
            $akun = $result['akun'];
            $totalRencana = $result['total_rencana'] ?? 0;
            $totalRealisasi = $result['total_realisasi'] ?? 0;
            $persentaseRealisasi = $totalRencana > 0 ? ($totalRealisasi / $totalRencana) * 100 : 0;
            $totalDeviasi = $totalRencana - $totalRealisasi;
            
            $summary[$akun] = [
                'total_rencana' => $totalRencana,
                'total_realisasi' => $totalRealisasi,
                'persentase_realisasi' => round($persentaseRealisasi, 2),
                'total_deviasi' => $totalDeviasi
            ];
        }
        
        return $summary;
    }
    
    public function getAnggaranByPIC($pic, $year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        
        return $this->where('pic', $pic)
                   ->where('tahun_anggaran', $year)
                   ->orderBy('tahun_anggaran', 'DESC')
                   ->orderBy('bulan', 'ASC')
                   ->orderBy('no_kegiatan', 'ASC')
                   ->findAll();
    }
    
    public function getMonthlyData($year, $month = null)
    {
        $builder = $this->where('tahun_anggaran', $year);
        
        if ($month) {
            $builder->where('bulan', $month);
        }
        
        return $builder->orderBy('bulan', 'ASC')
                      ->orderBy('akun', 'ASC')
                      ->findAll();
    }
    
    public function getTotalByAccount($year, $akun)
    {
        $builder = $this->db->table($this->table);
        $result = $builder->select('SUM(rencana_pencairan_anggaran) as total_rencana, SUM(realisasi) as total_realisasi')
                         ->where('tahun_anggaran', $year)
                         ->where('akun', $akun)
                         ->get()
                         ->getRowArray();
        
        return [
            'total_rencana' => (float)($result['total_rencana'] ?? 0),
            'total_realisasi' => (float)($result['total_realisasi'] ?? 0),
            'total_deviasi' => (float)($result['total_rencana'] ?? 0) - (float)($result['total_realisasi'] ?? 0)
        ];
    }
    

    
    /**
     * Menghitung deviasi otomatis berdasarkan rencana dan realisasi
     * @param int $rencana
     * @param int $realisasi
     * @return array
     */
    public function calculateDeviation($rencana, $realisasi)
    {
        $rencana = (int) $rencana;
        $realisasi = (int) $realisasi;
        
        // Hitung selisih
        $selisih = $realisasi - $rencana;
        
        // Hitung persentase
        $persentase = $rencana > 0 ? round(($selisih / $rencana) * 100, 2) : 0;
        
        // Tentukan status
        $status = '';
        if ($selisih > 0) {
            $status = 'Over Budget';
        } elseif ($selisih < 0) {
            $status = 'Under Budget';
        } else {
            $status = 'Sesuai Rencana';
        }
        
        return [
            'selisih' => $selisih,
            'persentase' => $persentase,
            'status' => $status,
            'formatted_text' => $this->formatDeviationText($selisih, $persentase, $status)
        ];
    }
    
    /**
     * Format teks deviasi untuk ditampilkan
     * @param float $selisih
     * @param float $persentase
     * @param string $status
     * @return string
     */
    private function formatDeviationText($selisih, $persentase, $status)
    {
        $formattedSelisih = 'Rp ' . number_format(abs($selisih), 0, ',', '.');
        $formattedPersentase = abs($persentase) . '%';
        
        if ($selisih > 0) {
            return "Kelebihan {$formattedSelisih} ({$formattedPersentase}) - {$status}";
        } elseif ($selisih < 0) {
            return "Kekurangan {$formattedSelisih} ({$formattedPersentase}) - {$status}";
        } else {
            return "Tidak ada deviasi - {$status}";
        }
    }
    
    /**
     * Override insert method untuk menghitung deviasi otomatis
     */
    public function insert($data = null, bool $returnID = true)
    {
        // Ensure required fields are set with defaults
        if (!isset($data['realisasi'])) {
            $data['realisasi'] = 0;
        }
        
        // Calculate deviation before inserting
        if (isset($data['rencana_pencairan_anggaran'])) {
            $deviation = $this->calculateDeviation($data['rencana_pencairan_anggaran'], $data['realisasi']);
            $data['deviasi'] = $deviation['formatted_text'];
        }
        
        // Set timestamps if not already set
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return parent::insert($data, $returnID);
    }
    
    /**
     * Override update method untuk menghitung deviasi otomatis
     */
    public function update($id = null, $data = null): bool
    {
        // Get existing data to preserve values if not provided
        if ($id && !isset($data['realisasi'])) {
            $existing = $this->find($id);
            if ($existing) {
                $data['realisasi'] = $existing['realisasi'] ?? 0;
            }
        }
        
        // Calculate deviation before updating
        if (isset($data['rencana_pencairan_anggaran'])) {
            $realisasi = $data['realisasi'] ?? 0;
            $deviation = $this->calculateDeviation($data['rencana_pencairan_anggaran'], $realisasi);
            $data['deviasi'] = $deviation['formatted_text'];
        }
        
        // Update timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return parent::update($id, $data);
    }
}