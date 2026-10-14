<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Feedback extends BaseController
{
    public function index()
    {
        // Check admin permission
        if (session()->get('role') != 'Admin') {
            return redirect()->to('/forbidden');
        }
        
        $data['title'] = 'Admin | Manajemen Feedback';
        $data['activeMenu'] = 'feedback';
        
        return view('admin/feedback_management', $data);
    }
    
    public function getFeedbackStats()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $stats = $this->feedbackModel->getFeedbackStats();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting feedback stats: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memuat statistik feedback'
            ]);
        }
    }
    
    public function getFeedbackList()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $status = $this->request->getGet('status');
            
            if ($status && $status !== 'semua') {
                $feedbacks = $this->feedbackModel->getFeedbackByStatus($status);
            } else {
                $feedbacks = $this->feedbackModel
                    ->select('feedback.*, user.nama as nama_user')
                    ->join('user', 'user.id_user = feedback.created_by', 'left')
                    ->orderBy('feedback.created_at', 'desc')
                    ->findAll();
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $feedbacks
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting feedback list: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memuat daftar feedback'
            ]);
        }
    }
    
    public function getFeedbackDetail($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $feedback = $this->feedbackModel
                ->select('feedback.*, user.nama as nama_user, user.email')
                ->join('user', 'user.id_user = feedback.created_by', 'left')
                ->where('feedback.id_feedback', $id)
                ->first();
            
            if (!$feedback) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Feedback tidak ditemukan'
                ]);
            }
            
            $data['feedback'] = $feedback;
            $html = view('admin/feedback_detail', $data);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $html
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting feedback detail: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memuat detail feedback'
            ]);
        }
    }
    
    public function updateFeedbackStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $newStatus = $this->request->getPost('status');
            $tanggapan = $this->request->getPost('tanggapan');
            
            // Validate status
            $validStatuses = ['Baru', 'Diproses', 'Selesai', 'Ditolak'];
            if (!in_array($newStatus, $validStatuses)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Status tidak valid'
                ]);
            }
            
            $currentUser = $this->userModel->where('username', session()->get('username'))->first();
            
            $updateData = [
                'status' => $newStatus,
                'updated_by' => $currentUser['id_user']
            ];
            
            if ($tanggapan) {
                $updateData['tanggapan'] = $tanggapan;
            }
            
            $result = $this->feedbackModel->update($id, $updateData);
            
            if ($result) {
                // Get feedback details for logging
                $feedback = $this->feedbackModel->find($id);
                
                // Log activity
                $this->linimasaModel->insert([
                    'aktivitas' => 'mengubah status feedback menjadi ' . $newStatus,
                    'keterangan' => 'Feedback: ' . $feedback['judul'],
                    'icon' => 'fas fa-edit bg-info',
                    'created_by' => $currentUser['id_user']
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Status feedback berhasil diubah'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mengubah status feedback'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating feedback status: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }
    
    public function deleteFeedback($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        try {
            $feedback = $this->feedbackModel->find($id);
            if (!$feedback) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Feedback tidak ditemukan'
                ]);
            }
            
            $result = $this->feedbackModel->delete($id);
            
            if ($result) {
                $currentUser = $this->userModel->where('username', session()->get('username'))->first();
                
                // Log activity
                $this->linimasaModel->insert([
                    'aktivitas' => 'menghapus feedback',
                    'keterangan' => 'Feedback: ' . $feedback['judul'],
                    'icon' => 'fas fa-trash bg-danger',
                    'created_by' => $currentUser['id_user']
                ]);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Feedback berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus feedback'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting feedback: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }
    
    public function exportFeedback()
    {
        // Check admin permission
        if (session()->get('role') != 'Admin') {
            return redirect()->to('/forbidden');
        }
        
        try {
            $feedbacks = $this->feedbackModel
                ->select('feedback.*, user.nama as nama_user')
                ->join('user', 'user.id_user = feedback.created_by', 'left')
                ->orderBy('feedback.created_at', 'desc')
                ->findAll();
            
            $filename = 'feedback_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($output, [
                'ID',
                'Tanggal',
                'Kategori', 
                'Prioritas',
                'Judul',
                'Deskripsi',
                'Modul',
                'Pengirim',
                'Status',
                'Tanggapan'
            ]);
            
            // CSV Data
            foreach ($feedbacks as $feedback) {
                fputcsv($output, [
                    $feedback['id_feedback'],
                    $feedback['created_at'],
                    $feedback['kategori'],
                    $feedback['prioritas'],
                    $feedback['judul'],
                    $feedback['deskripsi'],
                    $feedback['modul'] ?: '-',
                    $feedback['anonymous'] == 1 ? 'Anonim' : $feedback['nama_user'],
                    $feedback['status'],
                    $feedback['tanggapan'] ?: '-'
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            log_message('error', 'Error exporting feedback: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengekspor data feedback');
            return redirect()->to('/admin/feedback');
        }
    }
}
