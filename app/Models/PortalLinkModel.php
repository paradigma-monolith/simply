<?php

namespace App\Models;

use CodeIgniter\Model;

class PortalLinkModel extends Model
{
  protected $table = 'portal_link';
  protected $primaryKey = 'id_portal';
  protected $useTimestamps = true;
  protected $allowedFields = [
    "title",
    "link"
  ];
}
