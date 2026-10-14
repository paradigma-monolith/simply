<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeTargetBulananModel extends Model
{
  protected $table = 'relaxtime_target_bulanan';
  protected $primaryKey = 'id_relaxtime_target_bulanan';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'bulan1',
    'bulan2',
    'bulan3',
    'bulan4',
    'bulan5',
    'bulan6',
    'bulan7',
    'bulan8',
    'bulan9',
    'bulan10',
    'bulan11',
    'bulan12',
    'uraian',
    'tahun',
    'created_by',
    'updated_by',
  ];
}
