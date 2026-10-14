<?php

namespace App\Models;

use CodeIgniter\Model;

class RelaxtimeHasilRapatModel extends Model
{
    protected $table = 'relaxtime_hasil_rapat';
    protected $primaryKey = 'id_hasil_rapat';
    protected $allowedFields = ['nama_dokumen', 'file_dokumen', 'tanggal', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}