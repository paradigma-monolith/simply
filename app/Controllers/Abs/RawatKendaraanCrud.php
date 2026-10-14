<?php

namespace App\Controllers\Abs;

use App\Controllers\BaseController;
use App\Models\Abs\RawatKendaraanModel;
use App\Models\Abs\RawatVerifikatorModel;
use App\Models\KendaraanKantorDaftarModel;
use App\Models\Master\UserModel;

class RawatKendaraanCrud extends BaseController
{
    protected $rawatKendaraanModel;
    protected $rawatVerifikatorModel;
    protected $kendaraanKantorDaftarModel;
    protected $userModel;

    public function __construct()
    {
        $this->rawatKendaraanModel = new RawatKendaraanModel();
        $this->rawatVerifikatorModel = new RawatVerifikatorModel();
        $this->kendaraanKantorDaftarModel = new KendaraanKantorDaftarModel();
        $this->userModel = new UserModel();
    }

    public function insertPengajuan()
    {
        $id_user = session()->get('id_user');
        
        $data = [
            'id_kendaraan_kantor_daftar' => $this->request->getVar('id_kendaraan'),
            'tgl_pengajuan' => $this->request->getVar('tgl_pengajuan'),
            'keluhan' => $this->request->getVar('keluhan'),
            'estimasi_biaya' => $this->request->getVar('estimasi_biaya'),
            'status' => 'pending',
            'created_by' => $id_user,
        ];

        // Upload Bukti Foto
        $file = $this->request->getFile('bukti_foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('assets/abs/rawat_kendaraan', $newName);
            $data['bukti_foto'] = $newName;
        }

        if ($this->rawatKendaraanModel->save($data)) {
            session()->setFlashdata('success', 'Pengajuan berhasil dikirim.');
        } else {
            session()->setFlashdata('error', 'Gagal mengirim pengajuan.');
        }

        return redirect()->to('/abs/rawat_kendaraan');
    }

    public function verifikasi()
    {
        $id_rawat = $this->request->getVar('id_rawat');
        $role = $this->request->getVar('role'); // verifikator_1 or verifikator_2
        $aksi = $this->request->getVar('aksi'); // setuju or tolak
        $catatan = $this->request->getVar('catatan');
        $id_user = session()->get('id_user');

        $data = [
            'id_rawat' => $id_rawat,
        ];

        if ($role == 'verifikator_1') {
            $data['verifikator_1_id'] = $id_user;
            $data['verifikator_1_tgl'] = date('Y-m-d H:i:s');
            $data['verifikator_1_catatan'] = $catatan;
            
            if ($aksi == 'setuju') {
                $data['status'] = 'verifikasi_2'; // Lanjut ke verif 2
            } else {
                $data['status'] = 'ditolak';
            }
        } elseif ($role == 'verifikator_2') {
            $data['verifikator_2_id'] = $id_user;
            $data['verifikator_2_tgl'] = date('Y-m-d H:i:s');
            $data['verifikator_2_catatan'] = $catatan;

            if ($aksi == 'setuju') {
                $data['status'] = 'selesai';
            } else {
                $data['status'] = 'ditolak';
            }
        }

        if ($this->rawatKendaraanModel->save($data)) {
            session()->setFlashdata('success', 'Verifikasi berhasil.');
        } else {
            session()->setFlashdata('error', 'Gagal memproses verifikasi.');
        }

        return redirect()->to('/abs/rawat_kendaraan/detail/' . $id_rawat);
    }

    public function insertVerifikator()
    {
        $data = [
            'id_user' => $this->request->getVar('id_user'),
            'role' => $this->request->getVar('role'),
        ];

        if ($this->rawatVerifikatorModel->save($data)) {
            session()->setFlashdata('success', 'Verifikator berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan verifikator.');
        }
        return redirect()->to('/abs/rawat_kendaraan/pengaturan');
    }

    public function deleteVerifikator($id)
    {
        if ($this->rawatVerifikatorModel->delete($id)) {
            session()->setFlashdata('success', 'Verifikator berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus verifikator.');
        }
        return redirect()->to('/abs/rawat_kendaraan/pengaturan');
    }

    public function daftar_tambah()
    {
        $by = $this->userModel->where('username', session()->get('username'))->first();
        $data = [];

        $file = $this->request->getFile('file');
        if ($file) {
            if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tif', 'tiff', 'svg'])) {
                $maxFileSize = 1024 * 1024 * 5;
                if ($file->getSize() <= $maxFileSize) {
                    $newName = uniqid() . '_' . $file->getRandomName();
                    if ($file->move(FCPATH . '/dist/img/', $newName)) {
                        $data += [
                            'gambar' => $newName
                        ];
                    } else {
                        session()->setFlashdata('error', 'Gagal menyimpan gambar mobil.');
                        return redirect()->to('/abs/rawat_kendaraan/kendaraan');
                    }
                }
            }
        }

        $data += [
            'nama_kendaraan' => $this->request->getVar('nama_kendaraan'),
            'plat_nomor' => $this->request->getVar('plat_nomor'),
            'created_by' => $by['id_user'],
            'updated_by' => $by['id_user'],
        ];

        if ($this->kendaraanKantorDaftarModel->save($data)) {
            session()->setFlashdata('success', 'Data berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Data gagal ditambahkan.');
        }
        return redirect()->to('/abs/rawat_kendaraan/kendaraan');
    }

    public function daftar_update()
    {
        $by = $this->userModel->where('username', session()->get('username'))->first();
        $id = $this->request->getVar('id_kendaraan_kantor_daftar');
        $data = [
            'id_kendaraan_kantor_daftar' => $id
        ];
        $cekData = $this->kendaraanKantorDaftarModel->where('id_kendaraan_kantor_daftar', $id)->first();

        $file = $this->request->getFile('file');
        if ($file) {
            if ($file->isValid() && !$file->hasMoved() && in_array($file->getExtension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tif', 'tiff', 'svg'])) {
                $maxFileSize = 1024 * 1024 * 5;
                if ($file->getSize() <= $maxFileSize) {
                    $newName = uniqid() . '_' . $file->getRandomName();
                    if ($file->move(FCPATH . '/dist/img/', $newName)) {
                        $data += [
                            'gambar' => $newName
                        ];

                        // Hapus gambar lama jika hanya digunakan oleh data ini
                        $jumlahData = $this->kendaraanKantorDaftarModel->selectCount('gambar', 'jumlah')
                            ->where('gambar', $cekData['gambar'])
                            ->first();

                        if ($jumlahData['jumlah'] == 1) {
                            if (!empty($cekData['gambar'])) {
                                $imagePath = FCPATH . '/dist/img/' . $cekData['gambar'];
                                if (file_exists($imagePath)) {
                                    unlink($imagePath);
                                }
                            }
                        }
                    }
                }
            }
        }

        $data += [
            'nama_kendaraan' => $this->request->getVar('nama_kendaraan'),
            'plat_nomor' => $this->request->getVar('plat_nomor'),
            'updated_by' => $by['id_user'],
        ];

        if ($this->kendaraanKantorDaftarModel->save($data)) {
            session()->setFlashdata('success', 'Data berhasil diperbaharui.');
        } else {
            session()->setFlashdata('error', 'Data gagal diperbaharui.');
        }
        return redirect()->to('/abs/rawat_kendaraan/kendaraan');
    }

    public function daftar_delete($id)
    {
        $cekData = $this->kendaraanKantorDaftarModel->where('id_kendaraan_kantor_daftar', $id)->first();

        $jumlahData = $this->kendaraanKantorDaftarModel->selectCount('gambar', 'jumlah')
            ->where('gambar', $cekData['gambar'])
            ->first();

        if ($this->kendaraanKantorDaftarModel->delete($id)) {
            if ($jumlahData['jumlah'] == 1) {
                if (!empty($cekData['gambar'])) {
                    $imagePath = FCPATH . '/dist/img/' . $cekData['gambar'];
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }
            session()->setFlashdata('success', 'Data berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Data gagal dihapus.');
        }
        return redirect()->to('/abs/rawat_kendaraan/kendaraan');
    }

    public function deletePengajuan($id)
    {
        $cekData = $this->rawatKendaraanModel->where('id_rawat', $id)->first();
        
        if (!$cekData) {
            session()->setFlashdata('error', 'Data pengajuan tidak ditemukan.');
            return redirect()->to('/abs/rawat_kendaraan');
        }

        // Hapus file bukti foto jika ada
        if (!empty($cekData['bukti_foto'])) {
            $imagePath = FCPATH . '/assets/abs/rawat_kendaraan/' . $cekData['bukti_foto'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($this->rawatKendaraanModel->delete($id)) {
            session()->setFlashdata('success', 'Pengajuan berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus pengajuan.');
        }
        return redirect()->to('/abs/rawat_kendaraan');
    }

    public function updatePengajuan()
    {
        $id = $this->request->getVar('id_rawat');
        $data = [
            'id_rawat' => $id,
            'id_kendaraan_kantor_daftar' => $this->request->getVar('id_kendaraan'),
            'tgl_pengajuan' => $this->request->getVar('tgl_pengajuan'),
            'keluhan' => $this->request->getVar('keluhan'),
            'estimasi_biaya' => $this->request->getVar('estimasi_biaya'),
            'updated_by' => session()->get('id_user'),
        ];

        // Upload Bukti Foto Baru jika ada
        $file = $this->request->getFile('bukti_foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus foto lama
            $oldData = $this->rawatKendaraanModel->find($id);
            if (!empty($oldData['bukti_foto'])) {
                $oldPath = FCPATH . '/assets/abs/rawat_kendaraan/' . $oldData['bukti_foto'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $newName = $file->getRandomName();
            $file->move('assets/abs/rawat_kendaraan', $newName);
            $data['bukti_foto'] = $newName;
        }

        if ($this->rawatKendaraanModel->save($data)) {
            session()->setFlashdata('success', 'Pengajuan berhasil diperbarui.');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui pengajuan.');
        }

        return redirect()->to('/abs/rawat_kendaraan');
    }
}
