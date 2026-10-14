<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiVerifikatorApbnModel extends Model
{
  protected $table = 'sinadi_verifikator_apbn';
  protected $primaryKey = 'id_sinadi_verifikator_apbn';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'verifikator_1',
    'verifikator_2',
    'verifikator_3',
    'verifikator_4',
    'verifikator_5',
    'verifikator_6',
    'created_by',
    'updated_by',
  ];
}