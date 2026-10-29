<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AircraftSanitationModel;
use App\Models\AlatKesehatanModel;
use App\Models\ObatModel;
use App\Models\SanitasiKapalModel;

class TravelConsent extends BaseController
{
    protected $aircraftModel;
    protected $alatModel;
    protected $obatModel;
    protected $sanitasiModel;

    public function __construct()
    {
        $this->aircraftModel = new \App\Models\AircraftSanitationModel();
        $this->alatModel = new \App\Models\AlatKesehatanModel();
        $this->obatModel = new \App\Models\ObatModel();
        $this->sanitasiModel = new \App\Models\SanitasiKapalModel();
    }

    /**
     * Helper to handle upload of geotagging photos
     */
    private function uploadGeotagFile($fieldName, $oldFilePath = null)
    {
        $file = $this->request->getFile($fieldName);
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = 'uploads/travel_consent/geotagging/';
            $targetPath = FCPATH . $uploadDir;
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0777, true);
            }

            // Remove previous file if exists
            if (!empty($oldFilePath) && file_exists(FCPATH . $oldFilePath)) {
                @unlink(FCPATH . $oldFilePath);
            }

            $fileName = $file->getRandomName();
            $file->move($targetPath, $fileName);
            return $uploadDir . $fileName;
        }

        return $oldFilePath;
    }

    /**
     * Helper to delete geotagging photos from filesystem
     */
    private function deleteGeotagFile($filePath)
    {
        if (!empty($filePath) && file_exists(FCPATH . $filePath)) {
            @unlink(FCPATH . $filePath);
        }
    }

    // --- PUBLIC HOME ---
    public function public_home()
    {
        return view('travel_consent/public/home');
    }

    // ==========================================
    // PUBLIC ROUTES
    // ==========================================
    public function public_aircraft()
    {
        return view('travel_consent/public/aircraft_sanitation');
    }

    public function submit_aircraft()
    {
        $data = $this->request->getPost();
        
        // Handle JSON encodings
        $data['kabin_penumpang'] = json_encode($data['kabin_penumpang'] ?? []);
        $data['kompartemen_toilet'] = json_encode($data['kompartemen_toilet'] ?? []);
        $data['galley'] = json_encode($data['galley'] ?? []);
        $data['dokumen_kesehatan'] = json_encode($data['dokumen_kesehatan'] ?? []);
        $data['penanganan_air'] = json_encode($data['penanganan_air'] ?? []);
        $data['keadaan_makanan'] = json_encode($data['keadaan_makanan'] ?? []);
        $data['binatang_pengganggu'] = json_encode($data['binatang_pengganggu'] ?? []);

        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging');
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }
        
        $this->aircraftModel->insert($data);
        $insertId = $this->aircraftModel->getInsertID();
        $publicLink = base_url('travelconsent/doc_aircraft/' . $insertId);

        return redirect()->to('/travelconsent/public_aircraft')
            ->with('success', 'Data berhasil disimpan.')
            ->with('doc_link', $publicLink);
    }

    public function public_alat()
    {
        return view('travel_consent/public/alat_kesehatan');
    }

    public function submit_alat()
    {
        $data = $this->request->getPost();
        $data['items_p3k'] = json_encode($data['items_p3k'] ?? []);
        $data['items_emergensi'] = json_encode($data['items_emergensi'] ?? []);
        
        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging');
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }

        $this->alatModel->insert($data);
        $insertId = $this->alatModel->getInsertID();
        $publicLink = base_url('travelconsent/doc_alat/' . $insertId);

        return redirect()->to('/travelconsent/public_alat')
            ->with('success', 'Data berhasil disimpan.')
            ->with('doc_link', $publicLink);
    }

    public function public_obat()
    {
        return view('travel_consent/public/obat');
    }

    public function submit_obat()
    {
        $data = $this->request->getPost();
        $data['items_p3k'] = json_encode($data['items_p3k'] ?? []);
        $data['items_emergensi'] = json_encode($data['items_emergensi'] ?? []);
        
        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging');
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }

        $this->obatModel->insert($data);
        $insertId = $this->obatModel->getInsertID();
        $publicLink = base_url('travelconsent/doc_obat/' . $insertId);

        return redirect()->to('/travelconsent/public_obat')
            ->with('success', 'Data berhasil disimpan.')
            ->with('doc_link', $publicLink);
    }

    // --- SANITASI KAPAL ---
    public function public_sanitasi()
    {
        return view('travel_consent/public/sanitasi_kapal');
    }

    public function submit_sanitasi()
    {
        $data = $this->request->getPost();
        
        // Encode area_diperiksa to JSON
        if (isset($data['areas'])) {
            $data['area_diperiksa'] = json_encode($data['areas']);
            unset($data['areas']); // Remove the array version to prevent model save issues
        }

        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging');
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }

        $this->sanitasiModel->save($data);
        $insertId = $this->sanitasiModel->getInsertID();
        $publicLink = base_url('travelconsent/doc_sanitasi/' . $insertId);

        return redirect()->to('/travelconsent/public_sanitasi')
            ->with('success', 'Data Sanitasi Kapal berhasil dikirim!')
            ->with('doc_link', $publicLink);
    }

    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    public function index()
    {
        $data = [
            'title' => 'Travel Consent Form',
            'activeMenu' => 'travel_consent',
            'aircrafts' => $this->aircraftModel->findAll(),
            'alats' => $this->alatModel->findAll(),
            'obats' => $this->obatModel->findAll(),
            'sanitasis' => $this->sanitasiModel->findAll(),
        ];
        return view('travel_consent/admin/index', $data);
    }

    public function delete_aircraft($id)
    {
        $row = $this->aircraftModel->find($id);
        if ($row && !empty($row['foto_geotagging'])) {
            $this->deleteGeotagFile($row['foto_geotagging']);
        }
        $this->aircraftModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data dihapus.');
    }

    public function delete_alat($id)
    {
        $row = $this->alatModel->find($id);
        if ($row && !empty($row['foto_geotagging'])) {
            $this->deleteGeotagFile($row['foto_geotagging']);
        }
        $this->alatModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data dihapus.');
    }

    public function delete_obat($id)
    {
        $row = $this->obatModel->find($id);
        if ($row && !empty($row['foto_geotagging'])) {
            $this->deleteGeotagFile($row['foto_geotagging']);
        }
        $this->obatModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data dihapus.');
    }

    // ==========================================
    // AIRCRAFT METHODS
    // ==========================================
    public function edit_aircraft($id)
    {
        $data['aircraft'] = $this->aircraftModel->find($id);
        return view('travel_consent/admin/edit_aircraft', $data);
    }

    public function update_aircraft($id)
    {
        $data = $this->request->getPost();
        $existing = $this->aircraftModel->find($id);
        
        $data['kabin_penumpang'] = json_encode($data['kabin_penumpang'] ?? []);
        $data['kompartemen_toilet'] = json_encode($data['kompartemen_toilet'] ?? []);
        $data['galley'] = json_encode($data['galley'] ?? []);
        $data['dokumen_kesehatan'] = json_encode($data['dokumen_kesehatan'] ?? []);
        $data['penanganan_air'] = json_encode($data['penanganan_air'] ?? []);
        $data['keadaan_makanan'] = json_encode($data['keadaan_makanan'] ?? []);
        $data['binatang_pengganggu'] = json_encode($data['binatang_pengganggu'] ?? []);

        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging', $existing['foto_geotagging'] ?? null);
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }

        $this->aircraftModel->update($id, $data);
        return redirect()->to('/travelconsent/index')->with('success', 'Data Aircraft diperbarui.');
    }

    public function excel_aircraft($id)
    {
        $data['aircraft'] = $this->aircraftModel->find($id);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Aircraft_Sanitation_" . $id . ".xls");
        return view('travel_consent/admin/excel_aircraft', $data);
    }

    public function print_aircraft($id)
    {
        $data['aircraft'] = $this->aircraftModel->find($id);
        $data['is_print'] = true;
        return view('travel_consent/admin/edit_aircraft', $data);
    }

    // ==========================================
    // ALAT KESEHATAN METHODS
    // ==========================================
    public function edit_alat($id)
    {
        $data['alat'] = $this->alatModel->find($id);
        return view('travel_consent/admin/edit_alat', $data);
    }

    public function update_alat($id)
    {
        $data = $this->request->getPost();
        $existing = $this->alatModel->find($id);

        $data['items_p3k'] = json_encode($data['items_p3k'] ?? []);
        $data['items_emergensi'] = json_encode($data['items_emergensi'] ?? []);
        
        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging', $existing['foto_geotagging'] ?? null);
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }

        $this->alatModel->update($id, $data);
        return redirect()->to('/travelconsent/index')->with('success', 'Data Alat Kesehatan diperbarui.');
    }

    public function excel_alat($id)
    {
        $data['alat'] = $this->alatModel->find($id);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Alat_Kesehatan_" . $id . ".xls");
        return view('travel_consent/admin/excel_alat', $data);
    }

    public function print_alat($id)
    {
        $data['alat'] = $this->alatModel->find($id);
        $data['is_print'] = true;
        return view('travel_consent/admin/edit_alat', $data);
    }

    // ==========================================
    // OBAT-OBATAN METHODS
    // ==========================================
    public function edit_obat($id)
    {
        $data['obat'] = $this->obatModel->find($id);
        return view('travel_consent/admin/edit_obat', $data);
    }

    public function update_obat($id)
    {
        $data = $this->request->getPost();
        $existing = $this->obatModel->find($id);

        $data['items_p3k'] = json_encode($data['items_p3k'] ?? []);
        $data['items_emergensi'] = json_encode($data['items_emergensi'] ?? []);
        
        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging', $existing['foto_geotagging'] ?? null);
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }

        $this->obatModel->update($id, $data);
        return redirect()->to('/travelconsent/index')->with('success', 'Data Obat-obatan diperbarui.');
    }

    public function excel_obat($id)
    {
        $data['obat'] = $this->obatModel->find($id);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Obat_Obatan_" . $id . ".xls");
        return view('travel_consent/admin/excel_obat', $data);
    }

    public function print_sanitasi($id)
    {
        $data['sanitasi'] = $this->sanitasiModel->find($id);
        $data['is_print'] = true;
        return view('travel_consent/admin/edit_sanitasi', $data);
    }

    public function edit_sanitasi($id)
    {
        $data['sanitasi'] = $this->sanitasiModel->find($id);
        $data['is_print'] = false;
        return view('travel_consent/admin/edit_sanitasi', $data);
    }

    public function update_sanitasi($id)
    {
        $data = $this->request->getPost();
        $existing = $this->sanitasiModel->find($id);
        
        // Encode area_diperiksa to JSON
        if (isset($data['areas'])) {
            $data['area_diperiksa'] = json_encode($data['areas']);
            unset($data['areas']);
        } else {
            $data['area_diperiksa'] = json_encode([]); // Jika semua area dihapus
        }

        // Upload Foto Geotagging
        $uploaded = $this->uploadGeotagFile('foto_geotagging', $existing['foto_geotagging'] ?? null);
        if ($uploaded) {
            $data['foto_geotagging'] = $uploaded;
        }

        $this->sanitasiModel->update($id, $data);
        return redirect()->to('/travelconsent/index')->with('success', 'Data Sanitasi Kapal berhasil diperbarui!');
    }

    public function delete_sanitasi($id)
    {
        $row = $this->sanitasiModel->find($id);
        if ($row && !empty($row['foto_geotagging'])) {
            $this->deleteGeotagFile($row['foto_geotagging']);
        }
        $this->sanitasiModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data Sanitasi Kapal berhasil dihapus!');
    }

    public function print_obat($id)
    {
        $data['obat'] = $this->obatModel->find($id);
        $data['is_print'] = true;
        return view('travel_consent/admin/edit_obat', $data);
    }

    // ==========================================
    // PUBLIC DOCUMENT VIEW METHODS
    // ==========================================
    public function doc_aircraft($id)
    {
        $data['aircraft'] = $this->aircraftModel->find($id);
        if (!$data['aircraft']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }
        $data['is_print'] = true;
        $data['is_public_doc'] = true;
        return view('travel_consent/admin/edit_aircraft', $data);
    }

    public function doc_alat($id)
    {
        $data['alat'] = $this->alatModel->find($id);
        if (!$data['alat']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }
        $data['is_print'] = true;
        $data['is_public_doc'] = true;
        return view('travel_consent/admin/edit_alat', $data);
    }

    public function doc_obat($id)
    {
        $data['obat'] = $this->obatModel->find($id);
        if (!$data['obat']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }
        $data['is_print'] = true;
        $data['is_public_doc'] = true;
        return view('travel_consent/admin/edit_obat', $data);
    }

    public function doc_sanitasi($id)
    {
        $data['sanitasi'] = $this->sanitasiModel->find($id);
        if (!$data['sanitasi']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }
        $data['is_print'] = true;
        $data['is_public_doc'] = true;
        return view('travel_consent/admin/edit_sanitasi', $data);
    }
}
