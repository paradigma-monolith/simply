<?php

namespace App\Models;

use CodeIgniter\Model;

class InfografisVaksinModel extends Model
{
  protected $table = 'infografis_vaksin';
  protected $primaryKey = 'id_infografis_vaksin';
  protected $useTimestamps = true;
  protected $allowedFields = [
    'created_at', 
    'unit_kerja', 
    'phqc', 
    'cop', 
    'sscec', 
    'sscc', 
    'p3k', 
    'icv', 
    'sklt', 
    'siaos', 
    'siaj', 
    'kir', 
    'vaksin_mm', 
    'vaksin_yf', 
    'vaksin_covid', 
    'kontraindikasi', 
    'e_icv', 
    'status', 
    'created_by', 
    'updated_by', 
    'updated_pnbp', 
    'bukes', 
    'vaksin_influenza', 
    'vaksin_tifoid',
    'vaksin_polio'
  ];
}
