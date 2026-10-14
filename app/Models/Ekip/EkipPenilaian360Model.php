<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipPenilaian360Model extends Model
{
    protected $table            = 'ekip_penilaian_360';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'penilai_id', 'dinilai_id', 'tahun', 'bulan', 'minggu_ke', 
        'hasil_pekerjaan', 'kualitas_pekerjaan', 'ketepatan_waktu', 'efektivitas_efesiensi',
        'tanggung_jawab', 'kerjasama', 'presensi', 'jumlah',
        'justifikasi_deskripsi', 'justifikasi_file'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}