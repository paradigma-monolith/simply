<?php

namespace App\Controllers\Abs;

use App\Controllers\BaseController;
use App\Models\Abs\RawatKendaraanModel;
use App\Models\Abs\RawatVerifikatorModel;
use App\Models\KendaraanKantorDaftarModel;
use App\Models\Master\UserModel;

class RawatKendaraan extends BaseController
{
    protected $rawatKendaraanModel;
    protected $rawatVerifikatorModel;
    protected $kendaraanModel;
    protected $userModel;

    public function __construct()
    {
        $this->rawatKendaraanModel = new RawatKendaraanModel();
        $this->rawatVerifikatorModel = new RawatVerifikatorModel();
        $this->kendaraanModel = new KendaraanKantorDaftarModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['title'] = "ABS | Dashboard Rawat Kendaraan";
        $data['activeMenu'] = "abs_rawat_dashboard";

        // Logic for dashboard stats
        $data['total_pengajuan'] = $this->rawatKendaraanModel->countAll();
        $data['pending'] = $this->rawatKendaraanModel->where('status', 'pending')->countAllResults();
        $data['selesai'] = $this->rawatKendaraanModel->where('status', 'selesai')->countAllResults();
        $data['proses'] = $this->rawatKendaraanModel->whereIn('status', ['verifikasi_1', 'verifikasi_2'])->countAllResults();

        // Get history
        $data['riwayat'] = $this->rawatKendaraanModel
            ->select('abs_rawat_kendaraan.*, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, user.nama as pemohon, unit_kerja.unit_kerja')
            ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar = abs_rawat_kendaraan.id_kendaraan_kantor_daftar', 'left')
            ->join('user', 'user.id_user = abs_rawat_kendaraan.created_by', 'left')
            ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja = data_pegawai.id_unit_kerja', 'left')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Get kendaraan list for edit modal
        $data['kendaraan'] = $this->kendaraanModel->findAll();

        return view('abs/rawat_kendaraan/index', $data);
    }

    public function pengajuan()
    {
        $data['title'] = "ABS | Pengajuan Pemeliharaan";
        $data['activeMenu'] = "abs_rawat_pengajuan";
        $data['kendaraan'] = $this->kendaraanModel->findAll();

        return view('abs/rawat_kendaraan/pengajuan', $data);
    }

    public function insert()
    {
        $file = $this->request->getFile('bukti_foto');
        $newName = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('assets/abs/rawat_kendaraan', $newName);
        }

        $this->rawatKendaraanModel->save([
            'id_kendaraan_kantor_daftar' => $this->request->getPost('id_kendaraan'),
            'tgl_pengajuan' => $this->request->getPost('tgl_pengajuan'),
            'keluhan' => $this->request->getPost('keluhan'),
            'estimasi_biaya' => $this->request->getPost('estimasi_biaya'),
            'bukti_foto' => $newName,
            'status' => 'pending',
            'created_by' => session()->get('id_user')
        ]);

        return redirect()->to('/abs/rawat_kendaraan')->with('success', 'Pengajuan berhasil dikirim');
    }
    
    public function daftar_kendaraan()
    {
        $data['title'] = "ABS | Daftar Kendaraan";
        $data['activeMenu'] = "abs_rawat_kendaraan";
        $data['kendaraan'] = $this->kendaraanModel->findAll();

        return view('abs/rawat_kendaraan/kendaraan', $data);
    }

    public function pengaturan()
    {
        $data['title'] = "ABS | Pengaturan Verifikator";
        $data['activeMenu'] = "abs_rawat_pengaturan";
        
        $data['verifikator1'] = $this->rawatVerifikatorModel
            ->select('abs_rawat_verifikator.*, user.nama')
            ->join('user', 'user.id_user = abs_rawat_verifikator.id_user')
            ->where('role', 'verifikator_1')
            ->findAll();
            
        $data['verifikator2'] = $this->rawatVerifikatorModel
            ->select('abs_rawat_verifikator.*, user.nama')
            ->join('user', 'user.id_user = abs_rawat_verifikator.id_user')
            ->where('role', 'verifikator_2')
            ->findAll();

        $data['users'] = $this->userModel->orderBy('nama', 'ASC')->findAll();

        return view('abs/rawat_kendaraan/pengaturan', $data);
    }

    public function detail($id)
    {
        $data['title'] = "ABS | Detail Pengajuan";
        $data['activeMenu'] = "abs_rawat_dashboard";
        
        $data['detail'] = $this->rawatKendaraanModel
            ->select('abs_rawat_kendaraan.*, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, user.nama as pemohon, unit_kerja.unit_kerja')
            ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar = abs_rawat_kendaraan.id_kendaraan_kantor_daftar', 'left')
            ->join('user', 'user.id_user = abs_rawat_kendaraan.created_by', 'left')
            ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
            ->join('unit_kerja', 'unit_kerja.id_unit_kerja = data_pegawai.id_unit_kerja', 'left')
            ->where('id_rawat', $id)
            ->first();

        if (!$data['detail']) {
            return redirect()->to('/abs/rawat_kendaraan');
        }

        // Check verification rights
        $userId = session()->get('id_user');
        $data['is_verifikator_1'] = $this->rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_1')->first();
        $data['is_verifikator_2'] = $this->rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_2')->first();

        return view('abs/rawat_kendaraan/detail', $data);
    }

    public function verifikasi()
    {
        $data['title'] = "ABS | Verifikasi Pengajuan";
        $data['activeMenu'] = "abs_rawat_verifikasi";

        $userId = session()->get('id_user');
        $isVerifikator1 = $this->rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_1')->first();
        $isVerifikator2 = $this->rawatVerifikatorModel->where('id_user', $userId)->where('role', 'verifikator_2')->first();

        $data['pengajuan'] = [];

        if ($isVerifikator1) {
            $pengajuan1 = $this->rawatKendaraanModel
                ->select('abs_rawat_kendaraan.*, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, user.nama as pemohon, unit_kerja.unit_kerja')
                ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar = abs_rawat_kendaraan.id_kendaraan_kantor_daftar', 'left')
                ->join('user', 'user.id_user = abs_rawat_kendaraan.created_by', 'left')
                ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
                ->join('unit_kerja', 'unit_kerja.id_unit_kerja = data_pegawai.id_unit_kerja', 'left')
                ->where('abs_rawat_kendaraan.status', 'pending')
                ->findAll();
            foreach ($pengajuan1 as $p) {
                $p['role_verifikasi'] = 'Verifikator 1';
                $data['pengajuan'][] = $p;
            }
        }

        if ($isVerifikator2) {
            $pengajuan2 = $this->rawatKendaraanModel
                ->select('abs_rawat_kendaraan.*, kendaraan_kantor_daftar.nama_kendaraan, kendaraan_kantor_daftar.plat_nomor, user.nama as pemohon, unit_kerja.unit_kerja')
                ->join('kendaraan_kantor_daftar', 'kendaraan_kantor_daftar.id_kendaraan_kantor_daftar = abs_rawat_kendaraan.id_kendaraan_kantor_daftar', 'left')
                ->join('user', 'user.id_user = abs_rawat_kendaraan.created_by', 'left')
                ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
                ->join('unit_kerja', 'unit_kerja.id_unit_kerja = data_pegawai.id_unit_kerja', 'left')
                ->where('abs_rawat_kendaraan.status', 'verifikasi_2') // Status verifikasi_2 means approved by verif 1, waiting for verif 2
                ->findAll();
            foreach ($pengajuan2 as $p) {
                $p['role_verifikasi'] = 'Verifikator 2';
                $data['pengajuan'][] = $p;
            }
        }

        return view('abs/rawat_kendaraan/verifikasi_list', $data);
    }
}
