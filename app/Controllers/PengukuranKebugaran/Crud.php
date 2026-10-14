<?php

namespace App\Controllers\PengukuranKebugaran;

use App\Controllers\BaseController;

class Crud extends BaseController
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

    public function simpan()
    {
        // Pastikan method adalah POST
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            session()->setFlashdata('error', 'Method harus POST');
            return redirect()->to('/pengukuran_kebugaran');
        }

        // Get current user
        $username = session()->get('username');
        if (!$username) {
            session()->setFlashdata('error', 'Anda belum login');
            return redirect()->to('/auth/login');
        }
        
        $currentUser = $this->userModel->where('username', $username)->first();
        if (!$currentUser) {
            session()->setFlashdata('error', 'User tidak ditemukan');
            return redirect()->to('/pengukuran_kebugaran');
        }

        // Check if user has access to add data (Admin or special access)
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        // Only allow admin and users with special access to add data
        if (!$hasAllAccess) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menambah data pengukuran kebugaran');
            return redirect()->to('/pengukuran_kebugaran');
        }
        
        // Determine target user (admin and users with special access can select other users)
        $targetUserId = $currentUser['id_user']; // Default to current user
        $selectedUserId = $this->request->getPost('id_user');
        if (!empty($selectedUserId)) {
            $targetUserId = $selectedUserId;
        }

        // Get form data
        $tanggal = $this->request->getPost('tanggal_pengukuran');
        $sistolik = $this->request->getPost('tekanan_darah_sistolik');
        $diastolik = $this->request->getPost('tekanan_darah_diastolik');
        $gulaDarah = $this->request->getPost('gula_darah_sewaktu');
        $kolesterol = $this->request->getPost('kolesterol_total');
        $asamUrat = $this->request->getPost('asam_urat');
        $lingkarPinggang = $this->request->getPost('lingkar_pinggang');
        $beratBadan = $this->request->getPost('berat_badan');
        $tinggiBadan = $this->request->getPost('tinggi_badan');

        // Validate required fields
        if (empty($tanggal) || empty($sistolik) || empty($diastolik) || 
            empty($lingkarPinggang) || empty($beratBadan) || empty($tinggiBadan)) {
            session()->setFlashdata('error', 'Semua field yang bertanda bintang harus diisi');
            return redirect()->to('/pengukuran_kebugaran/tambah')->withInput();
        }

        // Get pegawai data to determine gender from NIP
        $pegawai = $this->dataPegawaiModel->where('id_user', $targetUserId)->first();
        if (!$pegawai || empty($pegawai['nip'])) {
            session()->setFlashdata('error', 'Data pegawai atau NIP tidak ditemukan');
            return redirect()->to('/pengukuran_kebugaran/tambah')->withInput();
        }

        // Calculate IMT
        $tinggiBadanMeter = $tinggiBadan / 100;
        $imt = $beratBadan / ($tinggiBadanMeter * $tinggiBadanMeter);
        $imt = round($imt, 2);

        // Determine IMT category
        $kategoriIMT = $this->getKategoriIMT($imt);

        // Determine gender and lingkar pinggang category
        $jenisKelamin = $this->getJenisKelaminFromNIP($pegawai['nip']);
        $kategoriLingkarPinggang = $this->getKategoriLingkarPinggang($lingkarPinggang, $jenisKelamin);

        $data = [
            'id_user' => $targetUserId,
            'tanggal_pengukuran' => $tanggal,
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

        // Save data
        if ($this->pengukuranKebugaranModel->save($data)) {
            session()->setFlashdata('success', 'Data pengukuran kebugaran berhasil disimpan');
        } else {
            $errors = $this->pengukuranKebugaranModel->errors();
            session()->setFlashdata('error', 'Gagal menyimpan data: ' . implode(', ', $errors));
        }

        return redirect()->to('/pengukuran_kebugaran');
    }

    public function update($id)
    {
        // Pastikan method adalah POST
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            session()->setFlashdata('error', 'Method harus POST');
            return redirect()->to('/pengukuran_kebugaran');
        }
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();

        // Check if data exists
        $existingData = $this->pengukuranKebugaranModel->find($id);
        if (!$existingData) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/pengukuran_kebugaran');
        }

        // Check if user can edit this data
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if (!$hasAllAccess && $existingData['id_user'] != $currentUser['id_user']) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit data ini');
            return redirect()->to('/pengukuran_kebugaran');
        }

        // Get form data
        $tanggal = $this->request->getPost('tanggal_pengukuran');
        $sistolik = $this->request->getPost('tekanan_darah_sistolik');
        $diastolik = $this->request->getPost('tekanan_darah_diastolik');
        $gulaDarah = $this->request->getPost('gula_darah_sewaktu');
        $kolesterol = $this->request->getPost('kolesterol_total');
        $asamUrat = $this->request->getPost('asam_urat');
        $lingkarPinggang = $this->request->getPost('lingkar_pinggang');
        $beratBadan = $this->request->getPost('berat_badan');
        $tinggiBadan = $this->request->getPost('tinggi_badan');

        // Get pegawai data to determine gender from NIP
        $pegawai = $this->dataPegawaiModel->where('id_user', $existingData['id_user'])->first();
        if (!$pegawai || empty($pegawai['nip'])) {
            session()->setFlashdata('error', 'Data pegawai atau NIP tidak ditemukan');
            return redirect()->to('/pengukuran_kebugaran/edit/' . $id)->withInput();
        }

        // Calculate IMT
        $tinggiBadanMeter = $tinggiBadan / 100;
        $imt = $beratBadan / ($tinggiBadanMeter * $tinggiBadanMeter);
        $imt = round($imt, 2);

        // Determine IMT category
        $kategoriIMT = $this->getKategoriIMT($imt);

        // Determine gender and lingkar pinggang category
        $jenisKelamin = $this->getJenisKelaminFromNIP($pegawai['nip']);
        $kategoriLingkarPinggang = $this->getKategoriLingkarPinggang($lingkarPinggang, $jenisKelamin);

        $data = [
            'id_pengukuran_kebugaran' => $id,
            'tanggal_pengukuran' => $tanggal,
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

        if ($this->pengukuranKebugaranModel->save($data)) {
            session()->setFlashdata('success', 'Data pengukuran kebugaran berhasil diperbarui');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui data pengukuran kebugaran');
        }

        return redirect()->to('/pengukuran_kebugaran');
    }

    public function hapus($id)
    {
        $currentUser = $this->userModel->where('username', session()->get('username'))->first();

        // Check if data exists
        $existingData = $this->pengukuranKebugaranModel->find($id);
        if (!$existingData) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        // Check if user can delete this data
        $hasAllAccess = ($this->getUserRole() == 'Admin') || 
                       $this->aksesModel->hasAccessToAllEmployees($currentUser['id_user']);
        
        if (!$hasAllAccess && $existingData['id_user'] != $currentUser['id_user']) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki akses untuk menghapus data ini']);
        }

        if ($this->pengukuranKebugaranModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data']);
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
        // Ambil angka ke-4 dari belakang (digit ke-15 dari 18 digit NIP)
        if (strlen($nip) >= 4) {
            $digit = substr($nip, -4, 1); // Ambil 1 karakter dari posisi ke-4 dari belakang
            return ($digit == '1') ? 'L' : 'P'; // 1 = Laki-laki, 2 = Perempuan
        }
        return 'L'; // Default laki-laki jika tidak dapat menentukan
    }

    private function getKategoriLingkarPinggang($lingkarPinggang, $jenisKelamin)
    {
        if ($jenisKelamin == 'L') {
            // Laki-laki: di atas 90 cm = Obesitas Sentral
            return ($lingkarPinggang > 90) ? 'Obesitas Sentral' : 'Normal';
        } else {
            // Perempuan: di atas 80 cm = Obesitas Sentral
            return ($lingkarPinggang > 80) ? 'Obesitas Sentral' : 'Normal';
        }
    }

    public function grantAccess()
    {
        // Only admin can grant access
        if ($this->getUserRole() != 'Admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki akses untuk memberikan izin']);
        }

        $userId = $this->request->getPost('user_id');
        if (empty($userId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User ID tidak boleh kosong']);
        }

        $currentUser = $this->userModel->where('username', session()->get('username'))->first();
        
        if ($this->aksesModel->grantAccess($userId, $currentUser['id_user'])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Akses berhasil diberikan']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memberikan akses']);
        }
    }

    public function revokeAccess()
    {
        // Only admin can revoke access
        if ($this->getUserRole() != 'Admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki akses untuk mencabut izin']);
        }

        $userId = $this->request->getPost('user_id');
        if (empty($userId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User ID tidak boleh kosong']);
        }

        if ($this->aksesModel->revokeAccess($userId)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Akses berhasil dicabut']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mencabut akses']);
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