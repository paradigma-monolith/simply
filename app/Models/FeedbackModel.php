<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'id_feedback';
    protected $allowedFields = [
        'kategori',
        'prioritas',
        'judul',
        'deskripsi',
        'modul',
        'anonymous',
        'status',
        'tanggapan',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'kategori' => 'required|in_list[UI/UX,Fitur,Bug,Performa,Keamanan,Lainnya]',
        'prioritas' => 'required|in_list[Rendah,Sedang,Tinggi,Kritis]',
        'judul' => 'required|max_length[100]',
        'deskripsi' => 'required|max_length[1000]',
        'modul' => 'permit_empty|max_length[100]',
        'anonymous' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'kategori' => [
            'required' => 'Kategori harus dipilih',
            'in_list' => 'Kategori tidak valid'
        ],
        'prioritas' => [
            'required' => 'Prioritas harus dipilih',
            'in_list' => 'Prioritas tidak valid'
        ],
        'judul' => [
            'required' => 'Judul harus diisi',
            'max_length' => 'Judul maksimal 100 karakter'
        ],
        'deskripsi' => [
            'required' => 'Deskripsi harus diisi',
            'max_length' => 'Deskripsi maksimal 1000 karakter'
        ],
        'modul' => [
            'max_length' => 'Modul maksimal 100 karakter'
        ]
    ];
    
    /**
     * Get feedback by status
     */
    public function getFeedbackByStatus($status = 'Baru')
    {
        return $this->select('feedback.*, user.nama as nama_user')
                   ->join('user', 'user.id_user = feedback.created_by', 'left')
                   ->where('feedback.status', $status)
                   ->orderBy('feedback.created_at', 'desc')
                   ->findAll();
    }
    
    /**
     * Get feedback statistics
     */
    public function getFeedbackStats()
    {
        $stats = [];
        
        // Count by status
        $stats['total'] = $this->countAll();
        $stats['baru'] = $this->where('status', 'Baru')->countAllResults(false);
        $stats['diproses'] = $this->where('status', 'Diproses')->countAllResults(false);
        $stats['selesai'] = $this->where('status', 'Selesai')->countAllResults(false);
        $stats['ditolak'] = $this->where('status', 'Ditolak')->countAllResults(false);
        
        // Count by category
        $categories = $this->select('kategori, COUNT(*) as jumlah')
                          ->groupBy('kategori')
                          ->findAll();
        
        $stats['kategori'] = [];
        foreach ($categories as $cat) {
            $stats['kategori'][$cat['kategori']] = $cat['jumlah'];
        }
        
        // Count by priority
        $priorities = $this->select('prioritas, COUNT(*) as jumlah')
                          ->groupBy('prioritas')
                          ->findAll();
        
        $stats['prioritas'] = [];
        foreach ($priorities as $pri) {
            $stats['prioritas'][$pri['prioritas']] = $pri['jumlah'];
        }
        
        return $stats;
    }
    
    /**
     * Get recent feedback
     */
    public function getRecentFeedback($limit = 10)
    {
        return $this->select('feedback.*, user.nama as nama_user')
                   ->join('user', 'user.id_user = feedback.created_by', 'left')
                   ->orderBy('feedback.created_at', 'desc')
                   ->limit($limit)
                   ->findAll();
    }
    
    /**
     * Update feedback status
     */
    public function updateStatus($id, $status, $tanggapan = null)
    {
        $data = [
            'status' => $status,
            'updated_by' => session()->get('id_user')
        ];
        
        if ($tanggapan) {
            $data['tanggapan'] = $tanggapan;
        }
        
        return $this->update($id, $data);
    }
}
