<?php

namespace App\Models;

use CodeIgniter\Model;

class ArsipTemplateNaskahModel extends Model
{
  protected $table = 'arsip_template_naskah';
  protected $primaryKey = 'id_arsip_template_naskah';
  protected $useTimestamps = true;
  protected $allowedFields = ['jenis_naskah', 'nama_file', 'file', 'dokumen', 'created_by', 'updated_by'];
}
