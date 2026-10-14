<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipPengaturanModel extends Model
{
    protected $table = 'ekip_pengaturan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tahun_aktif', 'bulan_aktif', 'minggu_aktif', 'akses_pegawai', 'notifikasi_aktif', 'jadwal_buka_hari', 'jadwal_tutup_hari', 'jadwal_tutup_jam', 't360_q1', 't360_q2', 't360_q3', 't360_q4', 'tab_kinerja_aktif', 'tab_perilaku_aktif', 'tab_360_aktif'];
    protected $useTimestamps = true;
    protected $createdField  = '';
    protected $updatedField  = 'updated_at';
}
