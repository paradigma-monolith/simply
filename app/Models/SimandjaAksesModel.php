<?php

namespace App\Models;

use CodeIgniter\Model;

class SimandjaAksesModel extends Model
{
  protected $table = 'simandja_akses';
  protected $primaryKey = 'id_simandja_akses';
  protected $useTimestamps = true;
  protected $allowedFields = ['akses'];
}
