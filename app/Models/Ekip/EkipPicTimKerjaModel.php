<?php

namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipPicTimKerjaModel extends Model
{
    protected $table            = 'ekip_pic_tim_kerja';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_tim_kerja', 'id_user'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
