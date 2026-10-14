<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
  protected $table = 'pegawai';
  protected $primaryKey = 'id_pegawai';
  protected $useTimestamps = true;
  protected $allowedFields = ['nip', 'nama', 'unit_kerja', 'email'];
}