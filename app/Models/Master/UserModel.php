<?php

namespace App\Models\Master;

use App\Models\Crud;

class UserModel extends Crud
{
  protected $table = 'user';
  protected $primaryKey = 'id_user';
  protected $useTimestamps = true;
  protected $allowedFields = ['id_role_access', 'nama', 'username', 'status_pegawai_aktif', 'password', 'email', 'no_telp', 'instagram', 'link_instagram', 'facebook', 'link_facebook', 'lainnya', 'link_lainnya', 'image', 'catatan_pribadi'];
}
