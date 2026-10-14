<?php

namespace App\Models;

use CodeIgniter\Model;

class IframeModel extends Model
{
  protected $table = 'iframe';
  protected $primaryKey = 'id_iframe';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "segment",
    "sidebar",
    "title",
    "link",
    "created_by",
    "updated_by",
  ];
}
