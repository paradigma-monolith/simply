<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
  protected $table = 'notifikasi';
  protected $primaryKey = 'id_notifikasi';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'tipe_notifikasi',
    'nama_pengirim',
    'nama_penerima',
    'pesan',
    'status'
  ];
}
