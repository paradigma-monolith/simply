<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailDataModel extends Model
{
  protected $table = 'email_data';
  protected $primaryKey = 'id_email_data';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'pengirim',
    'penerima',
    'email_tujuan',
    'subjek',
    'isi',
    'status',
    'created_by',
    'updated_by',
  ];
}
