<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailLampiranModel extends Model
{
  protected $table = 'email_lampiran';
  protected $primaryKey = 'id_email_lampiran';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_email_data',
    'file_nama_original',
    'file_nama_baru',
    'status',
    'created_by',
    'updated_by',
  ];
}
