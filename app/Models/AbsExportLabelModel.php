<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsExportLabelModel extends Model
{
  protected $table = 'abs_export_label';
  protected $primaryKey = 'id_abs_export_label';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'file',
    'lembar',
    'kertas',
    'id_awal',
    'id_akhir',
    'created_by',
    'updated_by'
  ];
}
