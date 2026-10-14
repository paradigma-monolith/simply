<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipPenilaiModel extends Model
{
    protected $table = 'ekip_penilai';
    protected $primaryKey = 'id';
    protected $allowedFields = ['penilai_id', 'dinilai_id'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
