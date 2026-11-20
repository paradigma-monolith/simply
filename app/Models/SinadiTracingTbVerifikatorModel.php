<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiTracingTbVerifikatorModel extends Model
{
    protected $table = 'sinadi_tracing_tb_verifikator';
    protected $primaryKey = 'id_verifikator';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id_user',
        'id_data_pegawai',
        'peran',
        'no_hp',
        'created_by',
    ];
}
