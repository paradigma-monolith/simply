<?php

namespace App\Models\Abs;

use CodeIgniter\Model;

class RawatVerifikatorModel extends Model
{
    protected $table = 'abs_rawat_verifikator';
    protected $primaryKey = 'id_verifikator';
    protected $allowedFields = [
        'id_user',
        'role',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
