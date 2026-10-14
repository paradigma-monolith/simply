<?php

namespace App\Models\PengawasanKapal;

use CodeIgniter\Model;

class LayananKesehatanKapalModel extends Model
{
    protected $table            = 'layanan_kesehatan_kapal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_kapal',
        'waktu_kedatangan',
        'pelabuhan_asal',
        'pelabuhan_tujuan',
        'lokasi_sandar',
        'jarak_kapal',
        'jumlah_crew',
        'dok_buku_kesehatan_kapal',
        'dok_sscec',
        'dok_sscec_tgl_kadaluarsa',
        'dok_p3k',
        'dok_p3k_tgl_kadaluarsa',
        'dok_phqc',
        'layanan_pemeriksaan_kesehatan',
        'layanan_penerbitan_sscec',
        'layanan_penerbitan_p3k',
        'layanan_lainnya',
        'layanan_lainnya_sebutkan',
        'fasilitasi_transportasi',
        'surat_permohonan',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}
