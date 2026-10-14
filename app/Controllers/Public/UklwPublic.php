<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

class UklwPublic extends BaseController
{
    public function landing()
    {
        $data['title'] = "Formulir Kesehatan UKLW - Kementerian Kesehatan RI";
        return view('public/uklw_landing', $data);
    }

    public function index()
    {
        $data['title'] = "Formulir Pendaftaran Kesehatan - UKLW";
        return view('public/uklw_form', $data);
    }

    public function submitForm()
    {
        // Validasi input
        $rules = [
            'identitas' => 'required',
            'nomor_identitas' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'tgl_lahir' => 'required',
            'alamat' => 'required',
            'pendidikan' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // 1. Simpan data pasien
            $dataPasien = [
                'identitas' => $this->request->getVar("identitas"),
                'nomor_identitas' => $this->request->getVar("nomor_identitas"),
                'nama' => $this->request->getVar("nama"),
                'jk' => $this->request->getVar("jk"),
                'tgl_lahir' => $this->request->getVar("tgl_lahir"),
                'alamat' => $this->request->getVar("alamat"),
                'no_hp' => $this->request->getVar("no_hp"),
                'pendidikan' => $this->request->getVar("pendidikan"),
                'pekerjaan' => $this->request->getVar("pekerjaan"),
                'status' => $this->request->getVar("status"),
                'nama_keluarga' => $this->request->getVar("nama_keluarga"),
                'hubungan_keluarga' => $this->request->getVar("hubungan_keluarga"),
                'no_hp_keluarga' => $this->request->getVar("no_hp_keluarga")
            ];

            // Validasi NIK jika dipilih
            $identitas = $this->request->getVar("identitas");
            $nomor_identitas = $this->request->getVar("nomor_identitas");
            $tgl_lahir = $this->request->getVar("tgl_lahir");
            $jk = $this->request->getVar("jk");

            // Cek apakah nomor identitas sudah ada
            $cekData = [
                'identitas' => $identitas,
                'nomor_identitas' => $nomor_identitas,
            ];

            $cekNik = $this->uklwPasienModel->where($cekData)->first();
            if ($cekNik) {
                session()->setFlashdata('error', 'Identitas dengan nomor ' . $nomor_identitas . ' sudah terdaftar!');
                return redirect()->back()->withInput();
            }

            if ($identitas == "NIK") {
                if (!$this->isValidNIK($nomor_identitas, $tgl_lahir, $jk)) {
                    session()->setFlashdata('error', 'NIK dengan nomor ' . $nomor_identitas . ' tidak valid, silahkan periksa kembali!');
                    return redirect()->back()->withInput();
                }
            }

            // Simpan data pasien
            if (!$this->uklwPasienModel->save($dataPasien)) {
                throw new \Exception('Gagal menyimpan data pasien');
            }

            $id_uklw_pasien = $this->uklwPasienModel->getInsertID();

            // 2. Simpan data skrining TBC
            $skriningTBCData = $this->request->getVar("skrining_tbc_gejala");
            if (!empty($skriningTBCData)) {
                $skrining_tbc_gejala = [];
                for ($i = 0; $i < count($skriningTBCData); $i++) {
                    $skrining_tbc_gejala[$i] = [
                        'skrining_tbc_gejala' => $skriningTBCData[$i],
                        'hasil_skrining_tbc_gejala' => $this->request->getVar("hasil_skrining_tbc_gejala" . $i),
                        'ket_skrining_tbc_gejala' => $this->request->getVar("ket_skrining_tbc_gejala")[$i] ?? '',
                    ];
                }

                $dataSkriningTBC = [
                    'id_uklw_pasien' => $id_uklw_pasien,
                    'skrining_tbc_gejala' => serialize($skrining_tbc_gejala)
                ];

                $this->uklwSkriningTBCModel->save($dataSkriningTBC);
            }

            // 3. Simpan data skrining HIV
            $skriningHIVData = $this->request->getVar("skrining_hiv");
            if (!empty($skriningHIVData)) {
                $skrining_hiv = [];
                for ($i = 0; $i < count($skriningHIVData); $i++) {
                    $skrining_hiv[$i] = [
                        'skrining_hiv' => $skriningHIVData[$i],
                        'hasil_skrining_hiv' => $this->request->getVar("hasil_skrining_hiv" . $i),
                        'ket_skrining_hiv' => $this->request->getVar("ket_skrining_hiv")[$i] ?? '',
                    ];
                }

                $dataSkriningHIV = [
                    'id_uklw_pasien' => $id_uklw_pasien,
                    'skrining_hiv' => serialize($skrining_hiv)
                ];

                $this->uklwSkriningHIVModel->save($dataSkriningHIV);
            }

            // 4. Simpan data faktor risiko PTM
            $faktorRisikoData = $this->request->getVar("skrining_penyakit_tidak_menular");
            if (!empty($faktorRisikoData)) {
                $skrining_penyakit_tidak_menular = [];
                for ($i = 0; $i < count($faktorRisikoData); $i++) {
                    $skrining_penyakit_tidak_menular[$i] = [
                        'skrining_penyakit_tidak_menular' => $faktorRisikoData[$i],
                        'hasil_skrining_penyakit_tidak_menular' => $this->request->getVar("hasil_skrining_penyakit_tidak_menular" . $i),
                        'ket_skrining_penyakit_tidak_menular' => $this->request->getVar("ket_skrining_penyakit_tidak_menular")[$i] ?? '',
                    ];
                }

                $dataFaktorRisikoPTM = [
                    'id_uklw_pasien' => $id_uklw_pasien,
                    'skrining_penyakit_tidak_menular' => serialize($skrining_penyakit_tidak_menular)
                ];

                $this->uklwFaktorRisikoPTMModel->save($dataFaktorRisikoPTM);
            }

            session()->setFlashdata('success', 'Data berhasil disimpan! Terima kasih telah mengisi formulir kesehatan.');
            return redirect()->to('/public/uklw/success');

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function success()
    {
        $data['title'] = "Pendaftaran Berhasil - UKLW";
        return view('public/uklw_success', $data);
    }

    private function isValidNIK($nik, $tgl_lahir, $jk)
    {
        // Validasi NIK berdasarkan tanggal lahir dan jenis kelamin
        $tanggal_lahir = substr($nik, 6, 6);
        
        $tgl = substr($tanggal_lahir, 0, 2);
        $bln = substr($tanggal_lahir, 2, 2);
        $thn = substr($tanggal_lahir, 4, 2);

        if ($jk == 'Perempuan') {
            $validasi_tanggal_lahir = (substr($tgl_lahir, 8, 2) + 40) == $tgl && substr($tgl_lahir, 5, 2) == $bln && substr($tgl_lahir, 2, 2) == $thn;
        } else {
            $validasi_tanggal_lahir = substr($tgl_lahir, 8, 2) == $tgl && substr($tgl_lahir, 5, 2) == $bln && substr($tgl_lahir, 2, 2) == $thn;
        }

        return $validasi_tanggal_lahir;
    }
}
