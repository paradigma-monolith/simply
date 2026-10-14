<?php

namespace App\Models\PengawasanKapal;

use CodeIgniter\Model;

class MonitoringSanitasiKapalModel extends Model
{
    protected $table = 'monitoring_sanitasi_kapal';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'tanggal',
        'wilker', 
        'unit_kerja',
        'nama_kapal',
        'jenis_dokumen',
        'bukti_geotagging',
        'laporan_tindakan',
        'catatan',
        'created_by',
        'updated_by'
    ];

    protected $validationRules = [
        'tanggal' => 'required|valid_date',
        'wilker' => 'required|max_length[255]',
        'nama_kapal' => 'required|max_length[255]',
        'bukti_geotagging' => 'permit_empty|max_length[500]',
        'laporan_tindakan' => 'permit_empty|max_length[500]',
        'catatan' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'wilker' => [
            'required' => 'Wilker harus diisi',
            'max_length' => 'Wilker maksimal 255 karakter'
        ],
        'nama_kapal' => [
            'required' => 'Nama kapal harus diisi',
            'max_length' => 'Nama kapal maksimal 255 karakter'
        ],
        'bukti_geotagging' => [
            'max_length' => 'Path file bukti geotagging maksimal 500 karakter'
        ],
        'laporan_tindakan' => [
            'max_length' => 'Path file laporan tindakan maksimal 500 karakter'
        ],
        'catatan' => [
            'max_length' => 'Catatan maksimal 1000 karakter'
        ]
    ];

    /**
     * Get data with filters
     */
    public function getFilteredData($filters = [])
    {
        $builder = $this->builder();
        
        // Filter by date range
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $builder->where('tanggal >=', $filters['start_date'])
                   ->where('tanggal <=', $filters['end_date']);
        }
        
        // Filter by unit_kerja (previously wilker)
        if (!empty($filters['wilker'])) {
            $builder->where('unit_kerja', $filters['wilker']);
        }
        
        // Filter by nama kapal
        if (!empty($filters['nama_kapal'])) {
            $builder->like('nama_kapal', $filters['nama_kapal']);
        }
        
        return $builder->orderBy('tanggal', 'DESC')
                      ->orderBy('created_at', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get unique wilker values for filter dropdown
     */
    public function getUnitKerjaOptions()
    {
        return $this->builder()
                   ->select('unit_kerja')
                   ->where('unit_kerja !=', null)
                   ->where('unit_kerja !=', '')
                   ->distinct()
                   ->orderBy('unit_kerja', 'ASC')
                   ->get()
                   ->getResultArray();
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics($filters = [])
    {
        $builder = $this->builder();
        
        // Apply filters if provided
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $builder->where('tanggal >=', $filters['start_date'])
                   ->where('tanggal <=', $filters['end_date']);
        }
        
        if (!empty($filters['wilker'])) {
            $builder->where('unit_kerja', $filters['wilker']);
        }
        
        $total = $builder->countAllResults(false);
        
        // Count with bukti geotagging
        $withBukti = $builder->where('bukti_geotagging IS NOT NULL')
                            ->where('bukti_geotagging !=', '')
                            ->countAllResults(false);
        
        // Count with laporan tindakan
        $withLaporan = $builder->where('laporan_tindakan IS NOT NULL')
                              ->where('laporan_tindakan !=', '')
                              ->countAllResults();
        
        return [
            'total' => $total,
            'with_bukti' => $withBukti,
            'with_laporan' => $withLaporan,
            'completion_rate' => $total > 0 ? round(($withBukti / $total) * 100, 2) : 0
        ];
    }
}