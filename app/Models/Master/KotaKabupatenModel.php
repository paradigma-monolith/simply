<?php

namespace App\Models\Master;

use App\Models\Crud;

class KotaKabupatenModel extends Crud
{
  protected $table = 'kota_kabupaten';
  protected $primaryKey = 'id_kota_kabupaten';
  protected $useTimestamps = true;
  protected $allowedFields = ['nama_kota_kabupaten', 'kota_kabupaten', 'created_by', 'updated_by'];
}
