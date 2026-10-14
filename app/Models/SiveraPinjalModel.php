<?php

namespace App\Models;

use CodeIgniter\Model;

class SiveraPinjalModel extends Model
{
    protected $table            = 'prl_sivera_pinjal';
    protected $primaryKey       = 'id_pinjal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal', 'id_wilayah_kerja', 'lokasi', 'jml_tikus_tertangkap', 'hari_ke', 
        'lokasi_tikus_tertangkap', 'kode_spesimen', 'jenis_kelamin', 
        'berat_badan', 'panjang_badan', 'ekor', 'kaki_belakang', 'telinga', 
        'testis', 'jml_mamae', 'spesies_tikus', 'pinjal_umum', 'pinjal_khusus', 
        'indeks_pinjal_umum', 'indeks_pinjal_khusus', 'created_by', 'updated_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
