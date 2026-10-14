<?php

namespace App\Models;

use CodeIgniter\Model;

class ManualBooksModel extends Model
{
  protected $table = 'manual_books';
  protected $primaryKey = 'id_manual_books';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'cover',
    'file',
    'nama_sistem',
    'priority',
  ];
}
