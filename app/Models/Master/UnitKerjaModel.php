<?php

namespace App\Models\Master;

use App\Models\Crud;

class UnitKerjaModel extends Crud
{
  protected $table = 'unit_kerja';
  protected $primaryKey = 'id_unit_kerja';
  protected $useTimestamps = true;
  protected $allowedFields = ['unit_kerja', 'lokasi', 'status_unit_kerja', 'created_by', 'updated_by'];
}
