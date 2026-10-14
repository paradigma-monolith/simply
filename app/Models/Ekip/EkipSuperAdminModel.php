<?php
namespace App\Models\Ekip;

use CodeIgniter\Model;

class EkipSuperAdminModel extends Model
{
    protected $table = 'ekip_super_admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user'];
}