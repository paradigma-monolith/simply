<?php

namespace App\Models\PengawasanKapal;

use CodeIgniter\Model;

class PengawasanKapalModel extends Model
{
  protected $DBGroup = 'db_kapal';
  protected $table = 'tbl_pasien';
  protected $primaryKey = 'id';
  protected $useTimestamps = true;
  protected $createdField = 'date_updated';
  protected $updatedField = 'date_updated';
  protected $allowedFields = [
    'nama', 'datang_dari', 'origin_country', 'gross_tonnage', 'pelabuhan', 'negara', 'jml_crew',
    'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15', 'q16', 'q17', 'q18', 'q19',
    'x1', 'x2', 'x3', 'x4', 'x5', 'x6', 'x7',
    'skoring', 'kresiko', 'hasilStatus', 'setuju_vaksin', 'surat_pernyataan_nahkoda',
    'status_verifikasi', 'catatan_verifikasi', 'verified_by', 'verified_at', 'date_updated'
  ];
}
