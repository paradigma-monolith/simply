<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class RoleAccessModel extends Model
{
  protected $table = 'role_access';
  protected $primaryKey = 'id_role_access';
  protected $useTimestamps = true;
  protected $allowedFields = [];
}
