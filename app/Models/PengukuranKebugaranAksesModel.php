<?php

namespace App\Models;

use CodeIgniter\Model;

class PengukuranKebugaranAksesModel extends Model
{
    protected $table            = 'pengukuran_kebugaran_akses';
    protected $primaryKey       = 'id_akses';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user',
        'akses_semua_pegawai',
        'dibuat_oleh',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_user' => 'required|integer',
        'akses_semua_pegawai' => 'required|in_list[0,1]',
        'dibuat_oleh' => 'required|integer'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Cek apakah user memiliki akses untuk melihat semua pegawai
     */
    public function hasAccessToAllEmployees($userId)
    {
        try {
            $akses = $this->where('id_user', $userId)
                         ->where('akses_semua_pegawai', 1)
                         ->first();
            
            return !empty($akses);
        } catch (\Exception $e) {
            log_message('error', 'Error checking access for user ' . $userId . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Berikan akses kepada user untuk melihat semua pegawai
     */
    public function grantAccess($userId, $adminId)
    {
        $data = [
            'id_user' => $userId,
            'akses_semua_pegawai' => 1,
            'dibuat_oleh' => $adminId
        ];

        // Cek apakah sudah ada record
        $existing = $this->where('id_user', $userId)->first();
        
        if ($existing) {
            // Update existing record
            return $this->update($existing['id_akses'], [
                'akses_semua_pegawai' => 1,
                'dibuat_oleh' => $adminId
            ]);
        } else {
            // Insert new record
            return $this->insert($data);
        }
    }

    /**
     * Cabut akses user untuk melihat semua pegawai
     */
    public function revokeAccess($userId)
    {
        $existing = $this->where('id_user', $userId)->first();
        
        if ($existing) {
            return $this->update($existing['id_akses'], [
                'akses_semua_pegawai' => 0
            ]);
        }
        
        return true;
    }

    /**
     * Dapatkan daftar user yang memiliki akses khusus
     */
    public function getUsersWithAccess()
    {
        try {
            return $this->select('pengukuran_kebugaran_akses.*, user.nama, user.username, admin.nama as admin_nama')
                       ->join('user', 'user.id_user = pengukuran_kebugaran_akses.id_user')
                       ->join('user as admin', 'admin.id_user = pengukuran_kebugaran_akses.dibuat_oleh', 'left')
                       ->where('akses_semua_pegawai', 1)
                       ->orderBy('user.nama', 'ASC')
                       ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting users with access: ' . $e->getMessage());
            return [];
        }
    }
} 