<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenSettingModel extends Model
{
    protected $table      = 'absen_setting';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['show_wfo', 'show_wfa', 'show_dl'];
}
