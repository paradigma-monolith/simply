<?php

namespace App\Models;

use CodeIgniter\Model;

class WbkRencanaKerjaModel extends Model
{
  protected $table = 'wbk_rencana_kerja';
  protected $primaryKey = 'id_wbk_rencana_kerja';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'id_wbk_pokja',
    'id_wbk_anggota',
    'poin1',
    'poin2',
    'kegiatan',
    'indikator',
    'bukti_fisik',
    'target_jml_kegiatan',
    'target_waktu',
    'prioritas',
    'created_by',
    'updated_by'
  ];
}
