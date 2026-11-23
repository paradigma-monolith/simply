<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiTracingTbLokusModel extends Model
{
    protected $table = 'sinadi_tracing_tb_lokus';
    protected $primaryKey = 'id_lokus';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nama_lokus',
        'urutan',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
