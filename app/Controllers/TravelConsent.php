<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AircraftSanitationModel;
use App\Models\AlatKesehatanModel;
use App\Models\ObatModel;

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

        // Handle signature uploads if necessary or base64 (assuming base64 for simplicity)
        
        $this->aircraftModel->insert($data);
        return redirect()->to('/travelconsent/public_aircraft')->with('success', 'Data berhasil disimpan.');
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
        
        $this->alatModel->insert($data);
        return redirect()->to('/travelconsent/public_alat')->with('success', 'Data berhasil disimpan.');
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
        
        $this->obatModel->insert($data);
        return redirect()->to('/travelconsent/public_obat')->with('success', 'Data berhasil disimpan.');
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

        $this->sanitasiModel->save($data);
        return redirect()->to('/travelconsent/public_sanitasi')->with('success', 'Data Sanitasi Kapal berhasil dikirim!');
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
        $this->aircraftModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data dihapus.');
    }

    public function delete_alat($id)
    {
        $this->alatModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data dihapus.');
    }

    public function delete_obat($id)
    {
        $this->obatModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data dihapus.');
    }

    // TODO: Add edit, update, export_excel, export_pdf for each form

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
        
        $data['kabin_penumpang'] = json_encode($data['kabin_penumpang'] ?? []);
        $data['kompartemen_toilet'] = json_encode($data['kompartemen_toilet'] ?? []);
        $data['galley'] = json_encode($data['galley'] ?? []);
        $data['dokumen_kesehatan'] = json_encode($data['dokumen_kesehatan'] ?? []);
        $data['penanganan_air'] = json_encode($data['penanganan_air'] ?? []);
        $data['keadaan_makanan'] = json_encode($data['keadaan_makanan'] ?? []);
        $data['binatang_pengganggu'] = json_encode($data['binatang_pengganggu'] ?? []);

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
        $data['items_p3k'] = json_encode($data['items_p3k'] ?? []);
        $data['items_emergensi'] = json_encode($data['items_emergensi'] ?? []);
        
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
        $data['items_p3k'] = json_encode($data['items_p3k'] ?? []);
        $data['items_emergensi'] = json_encode($data['items_emergensi'] ?? []);
        
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
        
        // Encode area_diperiksa to JSON
        if (isset($data['areas'])) {
            $data['area_diperiksa'] = json_encode($data['areas']);
            unset($data['areas']);
        } else {
            $data['area_diperiksa'] = json_encode([]); // Jika semua area dihapus
        }

        $this->sanitasiModel->update($id, $data);
        return redirect()->to('/travelconsent/index')->with('success', 'Data Sanitasi Kapal berhasil diperbarui!');
    }

    public function delete_sanitasi($id)
    {
        $this->sanitasiModel->delete($id);
        return redirect()->to('/travelconsent/index')->with('success', 'Data Sanitasi Kapal berhasil dihapus!');
    }

    public function print_obat($id)
    {
        $data['obat'] = $this->obatModel->find($id);
        $data['is_print'] = true;
        return view('travel_consent/admin/edit_obat', $data);
    }
}
