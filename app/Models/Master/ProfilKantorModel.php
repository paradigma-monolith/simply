<?php

namespace App\Models\Master;

use App\Models\Crud;

class ProfilKantorModel extends Crud
{
  protected $table = 'profil_kantor';
  protected $primaryKey = 'id_profil_kantor';
  protected $useTimestamps = true;
  protected $allowedFields = ['nama_kantor', 'alias_kantor', 'tertanggal', 'created_by', 'updated_by'];
}
