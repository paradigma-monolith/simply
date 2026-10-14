<?php

namespace App\Models;

use CodeIgniter\Model;

class WbkArsipModel extends Model
{
  protected $table = 'wbk_arsip';
  protected $primaryKey = 'id_wbk_arsip';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_wbk_arsip', 'id_wbk_rencana_kerja', 'id_wbk_kategori', 'id_wbk_anggota', 'nama_file', 'file', 'target_waktu', 'catatan', 'created_by', 'updated_by'];
}
