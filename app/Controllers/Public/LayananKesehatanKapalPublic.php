<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\PengawasanKapal\LayananKesehatanKapalModel;

class LayananKesehatanKapalPublic extends BaseController
{
    protected $layananKesehatanKapalModel;

    public function __construct()
    {
        $this->layananKesehatanKapalModel = new LayananKesehatanKapalModel();
    }

    public function index()
    {
        $data['title'] = "Formulir Permohonan Pengajuan Layanan Kesehatan Kapal";
        return view('public/layanan_kesehatan_kapal_form', $data);
    }

    public function submitForm()
    {
        // Validasi input
        $rules = [
            'nama_kapal' => 'required',
            'waktu_kedatangan' => 'required',
            'pelabuhan_asal' => 'required',
            'pelabuhan_tujuan' => 'required',
            'lokasi_sandar' => 'required',
            'jarak_kapal' => 'required|numeric',
            'jumlah_crew' => 'required|numeric',
            'fasilitasi_transportasi' => 'required|in_list[Ya,Tidak]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'nama_kapal' => $this->request->getVar('nama_kapal'),
                'waktu_kedatangan' => $this->request->getVar('waktu_kedatangan'),
                'pelabuhan_asal' => $this->request->getVar('pelabuhan_asal'),
                'pelabuhan_tujuan' => $this->request->getVar('pelabuhan_tujuan'),
                'lokasi_sandar' => $this->request->getVar('lokasi_sandar'),
                'jarak_kapal' => $this->request->getVar('jarak_kapal'),
                'jumlah_crew' => $this->request->getVar('jumlah_crew'),
                'dok_buku_kesehatan_kapal' => $this->request->getVar('dok_buku_kesehatan_kapal') ? 1 : 0,
                'dok_sscec' => $this->request->getVar('dok_sscec') ? 1 : 0,
                'dok_sscec_tgl_kadaluarsa' => $this->request->getVar('dok_sscec_tgl_kadaluarsa') ?: null,
                'dok_p3k' => $this->request->getVar('dok_p3k') ? 1 : 0,
                'dok_p3k_tgl_kadaluarsa' => $this->request->getVar('dok_p3k_tgl_kadaluarsa') ?: null,
                'dok_phqc' => $this->request->getVar('dok_phqc') ? 1 : 0,
                'layanan_pemeriksaan_kesehatan' => $this->request->getVar('layanan_pemeriksaan_kesehatan') ? 1 : 0,
                'layanan_penerbitan_sscec' => $this->request->getVar('layanan_penerbitan_sscec') ? 1 : 0,
                'layanan_penerbitan_p3k' => $this->request->getVar('layanan_penerbitan_p3k') ? 1 : 0,
                'layanan_lainnya' => $this->request->getVar('layanan_lainnya') ? 1 : 0,
                'layanan_lainnya_sebutkan' => $this->request->getVar('layanan_lainnya_sebutkan'),
                'fasilitasi_transportasi' => $this->request->getVar('fasilitasi_transportasi'),
            ];

            // Handle file upload
            $fileSurat = $this->request->getFile('surat_permohonan');
            if ($fileSurat && $fileSurat->isValid() && !$fileSurat->hasMoved()) {
                // Ensure directory exists
                $uploadPath = 'uploads/layanan_kesehatan_kapal/';
                if (!is_dir(FCPATH . $uploadPath)) {
                    mkdir(FCPATH . $uploadPath, 0777, true);
                }

                $newName = $fileSurat->getRandomName();
                $fileSurat->move(FCPATH . $uploadPath, $newName);
                $data['surat_permohonan'] = $uploadPath . $newName;
            }

            if (!$this->layananKesehatanKapalModel->save($data)) {
                throw new \Exception('Gagal menyimpan data layanan kesehatan kapal');
            }

            session()->setFlashdata('success', 'Permohonan layanan kesehatan kapal berhasil disubmit!');
            return redirect()->to('/public/layanan_kesehatan_kapal/success');

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function success()
    {
        $data['title'] = "Pengajuan Berhasil";
        return view('public/layanan_kesehatan_kapal_success', $data);
    }
}
