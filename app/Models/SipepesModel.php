<?php

namespace App\Models;

use CodeIgniter\Model;

class SipepesModel extends Model
{
  protected $table = 'sipepes_doksop';
  protected $primaryKey = 'dokumen_id';
  protected $useTimestamps = true;
  protected $allowedFields = ['dokumen_id', 'no_dokumen', 'judul', 'unit_kerja', 'jenis_dokumen', 'file', 'status_pengesahan', 'tanggal_pembuatan', 'tanggal_revisi', 'revisi_ke', 'tanggal_efektif', 'diubah_oleh',  'status'];
}
