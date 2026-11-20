<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiTracingTbDokumenModel extends Model
{
    protected $table = 'sinadi_tracing_tb_dokumen';
    protected $primaryKey = 'id_dokumen';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_tracing_tb',
        'kode_akun',
        'kode_dokumen',
        'nama_dokumen',
        'kategori_wajib',
        'nama_file',
        'nama_file_asli',
        'ukuran_file',
        'mime_type',
        'checklist_bkk',
        'catatan_bkk',
    ];
}
