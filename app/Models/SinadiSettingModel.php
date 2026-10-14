<?php

namespace App\Models;

use CodeIgniter\Model;

class SinadiSettingModel extends Model
{
  protected $table = 'sinadi_setting';
  protected $primaryKey = 'id_sinadi_setting';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'ppk',
    'ppk_nip',
    'kasubag_adum',
    'kasubag_adum_nip',
    'kepala_kantor',
    'kepala_kantor_nip',
    'bendahara',
    'bendahara_nip',
    'nama_kantor',
    'nama_alias',
    'status',
  ];
}
