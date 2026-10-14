<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenMotivasiModel extends Model
{
    protected $table            = 'absen_motivasi';
    protected $primaryKey       = 'id_motivasi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['hari', 'jenis', 'pesan', 'image', 'signature', 'status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
